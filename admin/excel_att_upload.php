<?php include("../adminsession.php");
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

if (isset($_POST['submit'])) {
    $totalRecords = 0;
    $insertedCount = 0;
    $skippedCount = 0;
    $skippedEpicNumbers = array();
    $insertedEmployees = [];   // emp_id based
    $skippedEmployees  = [];

    if (isset($_FILES['upload_excel']['tmp_name']) && $_FILES['upload_excel']['error'] == UPLOAD_ERR_OK) {
        $fileType = pathinfo($_FILES['upload_excel']['name'], PATHINFO_EXTENSION);
        $month  = $obj->test_input($_POST['file_month']);
        $year = $obj->test_input($_POST['file_year']);
        $firstRow = true;

        if ($fileType === 'xlsx') {
            if ($xlsx = SimpleXLSX::parse($_FILES['upload_excel']['tmp_name'])) {
                foreach ($xlsx->rows() as $k => $data) {
                    if ($firstRow) {
                        $firstRow = false;
                        continue;
                    }


                    $totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                    list($emp_code, $date1, $date2, $date3, $date4, $date5, $date6, $date7, $date8, $date9, $date10, $date11, $date12, $date12, $date14, $date15, $date16, $date17, $date18, $date19, $date20, $date21, $date22, $date23,  $date24,  $date25, $date26, $date27,  $date28, $date29, $date30,  $date31) = $data;

                    if (empty($emp_code)) {
                        continue;
                    }
                    $emp_id = 0;
                    if (!empty(trim($emp_code))) {
                        $emp_id = $obj->getvalfield(
                            "employee_master",
                            "emp_id",
                            "emp_code='$emp_code' AND unit_id='$unitid'"

                        );
                    }

                    $totalRecords++;
                    $shift_id = 0;
                    $department_id = $obj->getvalfield("employee_master", "department_id", "emp_id='$emp_id'");
                    $basic_salary  = $obj->getvalfield("employee_master", "basic_salary", "emp_id='$emp_id'");
                    $shift_hrs  = $obj->getvalfield("employee_master", "shift_id", "emp_id='$emp_id'");
                    $shift_id      = $obj->getvalfield("shift_master", "shift_id", "working_hour  LIKE '%$shift_hrs%' and unit_id='$unitid' order by shift_id desc limit 1");

                    $office_in  = $obj->getvalfield("shift_master", "in_time", "shift_id='$shift_id'");
                    $office_out = $obj->getvalfield("shift_master", "out_time", "shift_id='$shift_id'");
                    $shift_wh  = $obj->getvalfield("shift_master", "working_hour", "shift_id='$shift_id'");

                    for ($day = 1; $day <= 31; $day++) {
                        $punchtime = date('H:i:s',);
                        if (!isset($data[$day])) continue;
                        $status = strtoupper(trim($data[$day]));
                        if ($status == '') continue;

                        //  Skip invalid dates (Feb 30 etc)
                        if (!checkdate($month, $day, $year)) continue;

                        $attendance_date = sprintf('%04d-%02d-%02d', $year, $month, $day);

                        if ($status == 'A' || $status == 'a') {
                            $where = array(
                                'emp_id' => $emp_id,
                                'attendance_date'  => $attendance_date,
                                'year'   => $year,
                                'month'   => $month,
                                'unit_id'   => $unitid
                            );

                            $obj->delete_record('attendance_entry', $where);
                        } else {
                            $isDuplicate = $obj->getvalfield($tblname, "COUNT(*)", "emp_id = '$emp_id' and attendance_date='$attendance_date' and month='$month' and year='$year'");

                            if ($isDuplicate > 0) {
                                // $skippedEpicNumbers[] = [
                                //     'emp_code'     => $emp_code,
                                // ];

                                // $skippedCount++;
                                // continue;

                                if (!isset($skippedEmployees[$emp_code])) {
                                    $skippedEmployees[$emp_code] = [
                                        'emp_code' => $emp_code
                                    ];
                                }
                                continue;
                            }


                            $exists = $obj->getvalfield(
                                "attendance_entry",
                                "COUNT(*)",
                                "emp_id='$emp_id' AND attendance_date='$attendance_date'"
                            );
                            if ($exists) continue;

                            $intime = NULL;
                            $outtime = NULL;
                            $working_hours = '00:00:00';
                            $attendance_status = 'Absent';

                            $form_data = array(
                                'emp_id' => $emp_id,
                                'department_id' => $department_id,
                                'attendance_date' => $attendance_date,
                                'attendance_stamp' => $attendance_date . ' ' . $punchtime,
                                'month' => $month,
                                'year' => $year,
                                'createdate' => date('Y-m-d'),
                                'createtime' => $punchtime,
                                'ipaddress' => $ipaddress,
                                'basic_salary' => $basic_salary,
                                'shift_id' => $shift_id,
                                'in_status' => 'IN',
                                'out_status' => 'OUT',
                                'entry_type' => 'manual',
                                'unit_id' => $unitid,
                                'sessionid' => $sessionid
                            );

                            $shiftStart = new DateTime($attendance_date . ' ' . $office_in);
                            $shiftEnd   = new DateTime($attendance_date . ' ' . $office_out);
                            $interval = $shiftStart->diff($shiftEnd);
                            if ($shiftEnd <= $shiftStart) {
                                $shiftEnd->modify('+1 day');
                            }


                            $totalMinutes =
                                ($interval->days * 24 * 60) +
                                ($interval->h * 60) +
                                $interval->i;

                            $halfMinutes = $totalMinutes / 2;
                            $firstHalfStart  = clone $shiftStart;
                            $firstHalfEnd    = (clone $shiftStart)->modify("+{$halfMinutes} minutes");

                            $secondHalfStart = clone $firstHalfEnd;
                            $secondHalfEnd   = clone $shiftEnd;

                            if ($status == 'P' || $status == 'p') {
                                $form_data['intime'] = $shiftStart->format('H:i:s');
                                $form_data['outtime'] = $shiftEnd->format('H:i:s');
                                $form_data['attendance_status'] = 'Present';
                                $form_data['attheadid'] = '1';

                                $form_data['working_hours'] = sprintf(
                                    '%02d:%02d:%02d',
                                    floor($totalMinutes / 60),
                                    $totalMinutes % 60,
                                    0
                                );
                            }
                            if ($status == 'HD' || $status == 'hd' || $status == 'Hd' || $status == 'hD') {

                                $form_data['intime'] = $firstHalfStart->format('H:i:s');
                                $form_data['outtime'] = $firstHalfEnd->format('H:i:s');
                                $form_data['attendance_status'] = 'Half Day';
                                $form_data['attheadid'] = '3';
                                $form_data['working_hours'] = sprintf(
                                    '%02d:%02d:%02d',
                                    floor($halfMinutes / 60),
                                    $halfMinutes % 60,
                                    0
                                );
                            }
                            if ($status == 'L' || $status == 'l') {
                                $form_data['attendance_status'] = 'Leave';
                            }

                            // print_r($form_data);
                            // die;

                            $obj->insert_record("attendance_entry", $form_data);
                            // $insertedCount++;
                            $insertedEmployees[$emp_id] = true;
                        }
                    }
                    $insertedCount = count($insertedEmployees);
                    $skippedEpicNumbers = array_values($skippedEmployees);
                    $skippedCount = count($skippedEpicNumbers);
                }
            }
        }
    }

    $skippedData = urlencode(json_encode($skippedEpicNumbers));
    echo "<script>
location = '$pagename?action=1&total=$totalRecords&inserted=$insertedCount&skipped=$skippedCount&skipped_epics=$skippedData';
</script>";
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
                        <p>Skipped (Duplicates): <?php echo htmlspecialchars($_GET['skipped']); ?></p>
                        <?php
                        if (!empty($_GET['skipped_epics'])) {
                            $skippedEpicNumbers = json_decode(urldecode($_GET['skipped_epics']), true);

                            echo "<table   cellpadding='5'>
                            <tr>
                                <th>Emp Code</th>
                            </tr>";

                            foreach ($skippedEpicNumbers as $row) {
                                echo "<tr>
                                <td>{$row['emp_code']}</td>
                            </tr>";
                            }

                            echo "
                        </table>";
                        }
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