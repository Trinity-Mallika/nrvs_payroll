<?php
ini_set('max_execution_time', 3600);
set_time_limit(3600);
include("../adminsession.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pagename = "excel_att_upload.php";
$title = "Excel Upload Employee's Attandence ";
$module = "Excel Upload Employee's Attandence";
$submodule = "Employee's Attandence List";
$tblname = "attendance_entry";
$tblpkey = "attendance_id";
$btn_name = "Save";
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
require_once __DIR__ . '/src/SimpleXLSX.php';

$empMap = [];
$empRows = $obj->executequery("
    SELECT emp_id, emp_code, department_id, basic_salary, shift_id
    FROM employee_master
    WHERE unit_id='$unitid'
");

foreach ($empRows as $e) {
    $empMap[trim($e['emp_code'])] = $e;
}

$shiftMap = [];
$shiftRows = $obj->executequery("
    SELECT shift_id, in_time, out_time, working_hour
    FROM shift_master
    WHERE unit_id='$unitid'
");

foreach ($shiftRows as $s) {
    // KEY = working_hour (08:00:00, 12:00:00 etc.)
    $key = trim($s['working_hour']);
    $shiftMap[$key] = $s;
}


foreach ($shiftRows as $s) {
    $shiftMap[$s['shift_id']] = $s;
}


if (isset($_POST['submit'])) {

    $month = $obj->test_input($_POST['file_month']);
    $year  = $obj->test_input($_POST['file_year']);

    $totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $endDay = ($year == date('Y') && $month == date('m')) ? date('d') : $totalDaysInMonth;

    $insertRows = [];

    // === COUNTERS & TRACKERS ===
    $excelRowCount     = 0;
    $dbMatchedEmpIds   = [];
    $insertEmpIds      = [];
    $processedEmpIds   = [];
    $notInDbEmpCodes   = [];
    $zeroAttendanceEmpCodes = [];
    $skipReasons       = []; // emp_code => reason

    if ($xlsx = SimpleXLSX::parse($_FILES['upload_excel']['tmp_name'])) {

        foreach ($xlsx->rows() as $k => $data) {

            if ($k == 0) continue; // header

            $excelRowCount++;

            $emp_code = strtoupper(trim((string)$data[0]));
            if (!$emp_code) continue;

            // EMP NOT IN DB
            if (!isset($empMap[$emp_code])) {
                $notInDbEmpCodes[$emp_code] = true;
                $skipReasons[$emp_code] = 'EMP_NOT_IN_DB';
                continue;
            }

            $emp = $empMap[$emp_code];
            $emp_id = $emp['emp_id'];

            $dbMatchedEmpIds[$emp_id] = true;
            $processedEmpIds[$emp_id] = true;

            // SHIFT CHECK
            $shift_wh_key = trim($emp['shift_id']);
            if (!isset($shiftMap[$shift_wh_key])) {
                $skipReasons[$emp_code] = 'SHIFT_NOT_FOUND';
                continue;
            }

            $shift = $shiftMap[$shift_wh_key];
            $shift_id   = $shift['shift_id'];
            $office_in  = $shift['in_time'];
            $office_out = $shift['out_time'];
            $shift_wh   = $shift['working_hour'];

            $hasAttendance = false;

            for ($day = 1; $day <= $endDay; $day++) {

                $rawStatus = (string)($data[$day] ?? '');
                $status = strtoupper(preg_replace('/[^A-Z]/', '', $rawStatus));

                if ($status === '' || $status === 'A') {
                    continue;
                }

                $attendance_date = sprintf('%04d-%02d-%02d', $year, $month, $day);

                $row = [
                    'emp_id' => $emp_id,
                    'department_id' => $emp['department_id'],
                    'attendance_date' => $attendance_date,
                    'attendance_stamp' => $attendance_date . ' ' . $office_in,
                    'month' => $month,
                    'year' => $year,
                    'createdate' => date('Y-m-d'),
                    'createtime' => $office_in,
                    'ipaddress' => $ipaddress,
                    'basic_salary' => $emp['basic_salary'],
                    'shift_id' => $shift_id,
                    'in_status' => 'IN',
                    'out_status' => 'OUT',
                    'entry_type' => 'manual',
                    'entry_type_out' => 'manual',
                    'unit_id' => $unitid,
                    'sessionid' => $sessionid
                ];

                switch ($status) {
                    case 'P':
                        $row['attendance_status'] = 'Present';
                        $row['intime'] = $office_in;
                        $row['outtime'] = $office_out;
                        $row['working_hours'] = $shift_wh;
                        $row['attheadid'] = 1;
                        break;

                    case 'HD':
                        $row['attendance_status'] = 'Half Day';
                        $row['working_hours'] = $obj->hoursToTime(
                            $obj->timeToHours($shift_wh) / 2
                        );
                        $row['attheadid'] = 3;
                        break;
                    case 'HL':
                        $row['attendance_status'] = 'Half Day';
                        $row['working_hours'] = $obj->hoursToTime(
                            $obj->timeToHours($shift_wh) / 2
                        );
                        $row['attheadid'] = 3;
                        break;

                    case 'L':
                        $row['attendance_status'] = 'Leave';
                        $row['working_hours'] = '00:00';
                        $row['attheadid'] = 2;
                        break;

                    default:
                        $skipReasons[$emp_code] = 'INVALID_STATUS:' . $rawStatus;
                        continue 2;
                }

                $insertRows[] = $row;
                $insertEmpIds[$emp_id] = true;
                $hasAttendance = true;
            }

            if (!$hasAttendance) {
                $zeroAttendanceEmpCodes[$emp_code] = true;
                $skipReasons[$emp_code] = 'ALL_ABSENT_OR_BLANK';
            }
        }
    }

    // DELETE OLD DATA
    if (!empty($processedEmpIds)) {
        $obj->bulk_delete('attendance_entry', [
            'emp_id'  => array_keys($processedEmpIds),
            'month'   => $month,
            'year'    => $year,
            'unit_id' => $unitid
        ]);
    }

    // INSERT NEW DATA
    foreach (array_chunk($insertRows, 500) as $chunk) {
        $obj->bulk_insert('attendance_entry', $chunk);
    }

    // WRITE FILES
    if (!empty($notInDbEmpCodes)) {
        file_put_contents(
            __DIR__ . '/emp_not_in_db.txt',
            implode(PHP_EOL, array_keys($notInDbEmpCodes))
        );
    }

    if (!empty($skipReasons)) {
        $lines = [];
        foreach ($skipReasons as $code => $reason) {
            $lines[] = $code . ' => ' . $reason;
        }

        file_put_contents(
            __DIR__ . '/attendance_skip_diagnostics.txt',
            implode(PHP_EOL, $lines)
        );
    }

    // FINAL COUNTS
    $totalExcelRows = $excelRowCount;
    $dbMatchedCount = count($dbMatchedEmpIds);
    $insertedCount  = count($insertEmpIds);
    $notInDbCount   = $totalExcelRows - $dbMatchedCount;
    $skippedCount   = $dbMatchedCount - $insertedCount;

    header("Location: $pagename?action=1"
        . "&excel=$totalExcelRows"
        . "&dbmatched=$dbMatchedCount"
        . "&inserted=$insertedCount"
        . "&notindb=$notInDbCount"
        . "&skipped=$skippedCount");
    exit;
}



?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?></title>
    <?php include('inc/css.php') ?>
</head>

<body>
    <?php include('inc/header.php') ?>
    <?php include('inc/sidebar.php') ?>
    <!-- end auth-page-wrapper -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <?php include('inc/bredcrum.php') ?>
                <?php include('inc/alert.php'); ?>
                <div class="row">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="card-title mb-1">
                                                <?= $module; ?>
                                            </h5>
                                            <small class="text-danger fw-semibold">
                                                <b> Note:</b> <br>
                                                • Employee Code is compulsory.<br>
                                                • Employee Code must be unique.<br>
                                                • Rows with missing or duplicate values will be skipped during upload.
                                            </small>
                                        </div>

                                        <div class="col-md-4 text-end">
                                            <a href="excel_attendance.xlsx" class="btn btn-primary btn-sm">
                                                Download Sample File
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <label for="month" class="form-label">Month<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="file_month" id="file_month">
                                                <option value="">Select</option>
                                                <?php
                                                $months = [
                                                    1 => 'January',
                                                    2 => 'February',
                                                    3 => 'March',
                                                    4 => 'April',
                                                    5 => 'May',
                                                    6 => 'June',
                                                    7 => 'July',
                                                    8 => 'August',
                                                    9 => 'September',
                                                    10 => 'October',
                                                    11 => 'November',
                                                    12 => 'December'
                                                ];
                                                foreach ($months as $value => $name) {
                                                    echo "<option value=\"$value\">$name</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="year" class="form-label">Year<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="file_year" id="file_year">
                                                <option value="">Select</option>
                                                <?php
                                                $startYear = 2025;
                                                $endYear = 2100;
                                                for ($year1 = $startYear; $year1 <= $endYear; $year1++) {
                                                    echo "<option value=\"$year1\">$year1</option>";
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <strong><label for="">Upload File <span class="text-danger fw-bold">*</span></label></strong>
                                            <input type="file" name="upload_excel" id="upload_excel" class="form-control form-control-sm" accept=".xlsx">
                                        </div>
                                        <div class="col-lg-4 mb-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary btn-sm" value="<?php echo $btn_name ?> " onClick="return checkinputmaster('file_month,file_year,upload_excel')">
                                            <a href="<?php echo $pagename ?>" type="button" class="btn btn-danger btn-sm">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php if (isset($_GET['total'])): ?>
                    <div class="alert alert-info mt-3">
                        <strong>Data Processing Results:</strong>
                        <p>Total Records: <?php echo htmlspecialchars($_GET['total']); ?></p>
                        <p>Successfully Inserted: <?php echo htmlspecialchars($_GET['inserted']); ?></p>

                        <?php

                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <!-- Content close-->
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            $(".chosen-select").select2({
                width: '100%',
                search_contains: true
            });
        });
    </script>
</body>

</html>