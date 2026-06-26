<?php
ini_set('max_execution_time', 3600);
set_time_limit(3600);
include("../adminsession.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pagename = "excel_att_upload_day_wise.php";
$title = "Day Wise Att. Upload ";
$module = "Day Wise Att. Upload";
$submodule = "Employee's Attandence List";
$tblname = "attendance_entry";
$tblpkey = "attendance_id";
$btn_name = "Save";
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
require_once __DIR__ . '/src/SimpleXLSX.php';

$empMap = [];
$empRows = $obj->executequery("
    SELECT emp_id, emp_code, department_id, basic_salary, shift_id, date_of_joining
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
 $skipEmpCodes = [];
$skipReasons = [];
    $att_date = $obj->test_input($_POST['att_date']);
    $month = (int)date('m', strtotime($att_date));
    $year  = (int)date('Y', strtotime($att_date));
    $day   = (int)date('d', strtotime($att_date));

    $insertRows = [];
    $processedEmpIds = [];
    $skipRows = [];
    if ($xlsx = SimpleXLSX::parse($_FILES['upload_excel']['tmp_name'])) {

        foreach ($xlsx->rows() as $k => $data) {


            if ($k == 0) continue; // header

            $emp_code = strtoupper(trim((string)$data[0]));
          

            if (!$emp_code) {
                continue;
            }

            if (!isset($empMap[$emp_code])) {

                $skipEmpCodes[] = $emp_code;
                $skipReasons[] = $emp_code . " - Employee Not Found";

                continue;
            }
           // if (!$emp_code || !isset($empMap[$emp_code])) continue;

            $emp = $empMap[$emp_code];
            $emp_id = $emp['emp_id'];
            $doj = $emp['date_of_joining'];
            if (!empty($doj) && strtotime($att_date) < strtotime($doj)) {
                $skipEmpCodes[] = $emp_code;
                $skipReasons[] = $emp_code .
                    " - Attendance cannot be recorded for " .
                    date('d-m-Y', strtotime($att_date)) .
                    " because the employee joined on " .
                    date('d-m-Y', strtotime($doj));

                continue;
            }

            $processedEmpIds[$emp_id] = true;

            $shift_wh_key = trim($emp['shift_id']);

            if (!isset($shiftMap[$shift_wh_key])) continue;
            // print_r($shift_wh_key);
            // die;
            $shift = $shiftMap[$shift_wh_key];
            $shift_id   = $shift['shift_id'];
            $office_in  = $shift['in_time'];
            $office_out = $shift['out_time'];
            $shift_wh   = $shift['working_hour'];
 
            $status = strtoupper(trim((string)$data[1]));
       
            if ($status !== '' && $status !== 'A') {

                $attendance_date = $att_date;
              
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
                    'createdby' => $loginid,
                    'sessionid' => $sessionid
                ];

                if ($status === 'P') {
                    $row['attendance_status'] = 'Present';
                    $row['intime'] = $office_in;
                    $row['outtime'] = $office_out;
                    $row['working_hours'] = $shift_wh;
                } elseif ($status === 'HD') {
                    $row['attendance_status'] = 'Half Day';
                    $row['working_hours'] = $obj->hoursToTime(
                        $obj->timeToHours($shift_wh) / 2
                    );
                } elseif ($status === 'L') {
                    $row['attendance_status'] = 'Earning Leave';
                } elseif ($status === 'C') {
                    $row['attendance_status'] = 'C Off';
                } elseif ($status === 'HL') {
                    $row['attendance_status'] = 'Half Earning Leave';
                } elseif ($status === 'HC') {
                    $row['attendance_status'] = 'Half C Off';
                } elseif ($status === 'C2') {
                    $row['attendance_status'] = 'Extra Off';
                } elseif ($status === 'HC2') {
                    $row['attendance_status'] = 'Half Extra Off';
                }

                $insertRows[] = $row;
            }
                    }
                }

                if (!empty($processedEmpIds)) {
                    $empIdStr = implode(',', array_keys($processedEmpIds));
                    $obj->bulk_delete('attendance_entry', [
                        'emp_id'  => array_keys($processedEmpIds),
                        'month'   => $month,
                        'attendance_date'   => $att_date,
                        'year'    => $year,
                        'unit_id' => $unitid
                    ]);
                    $obj->bulk_delete('attendance_log', [
                        'emp_id'  => array_keys($processedEmpIds),
                        'month'   => $month,
                        'attendance_date'   => $att_date,
                        'year'    => $year,
                        'unit_id' => $unitid
                    ]);
                } 

                foreach (array_chunk($insertRows, 500) as $chunk) {
                    $obj->bulk_insert('attendance_entry', $chunk);
                }
                $form_data1 = array(
                        "primary_id" => 0,
                        "flag" => 'Record Deleted Successfully',
                        "activity_type" => 'Deleted',
                        "createdby" => $loginid,
                        "pagename" => 'excel_att_upload_day_wise.php',
                        "created_date" => $createdate,
                        "created_time" => date('H:i:s'),
                        "unit_id" => $unitid,
                        'ipaddress' => $ipaddress,
                        "sessionid" => $sessionid
                    );
                $logactivity = $obj->insert_record("logactivity_master", $form_data1);
                $insertedCount = count(array_unique(array_column($insertRows, 'emp_id')));
               $skipCount = count($skipReasons);
                $skipEmpStr = urlencode(implode(', ', $skipReasons));
        //die;
        // echo "<script>
    //     location='$pagename?action=1&total=$insertedCount&inserted=$insertedCount';
    // </script>";
 
    echo "<script>
location='$pagename?action=1&total=$insertedCount&inserted=$insertedCount&skip_count=$skipCount&skip_emp=$skipEmpStr';
</script>";
}

?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

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

                                        </div>

                                        <div class="col-md-4 text-end">
                                            <a href="day_wise_att_upload.xlsx" class="btn btn-primary btn-sm">
                                                Download Sample File
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label for="">Application From<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <input type="date" name="att_date" id="att_date"
                                                class="form-control form-control-sm me-2" value="<?=date('Y-m-d')?>">
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <strong><label for="">Upload File <span
                                                        class="text-danger fw-bold">*</span></label></strong>
                                            <input type="file" name="upload_excel" id="upload_excel"
                                                class="form-control form-control-sm" accept=".xlsx">
                                        </div>
                                        <div class="col-lg-4 mb-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary btn-sm"
                                                value="<?php echo $btn_name ?> "
                                                onClick="return checkinputmaster('att_date,upload_excel')">
                                            <a href="<?php echo $pagename ?>" type="button"
                                                class="btn btn-danger btn-sm">Reset</a>
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
                <?php if (isset($_GET['skip_count']) && $_GET['skip_count'] > 0) { ?>
                <div class="alert alert-danger">
                    <strong>Skipped Records: <?= $_GET['skip_count'] ?></strong>
                    <hr>

                    <?php
                        $reasons = explode(',', urldecode($_GET['skip_emp']));
                        foreach($reasons as $reason){
                            echo "<div>• ".trim($reason)."</div>";
                        }
                    ?>
                </div>
                <?php } ?>
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