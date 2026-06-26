<?php include("../adminsession.php");
$pagename = "employee_attendence_report.php";
$title = "Employee Attendence Report";
$tblname = "";
$tblpkey = "";
$module = "Employee Attendence Report";
$submodule = "Employee Attendence Report";
$btn_name = "Save";
$imgpath = "uploaded/emp_documents/";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = ' and 1=1';

$currentMonth = isset($_GET['month']) ? $_GET['month'] : date('m');
$currentYear  = isset($_GET['year']) ? $_GET['year'] : date('Y');
$emp_id       = isset($_GET['emp_id']) ? $_GET['emp_id'] : 0;

$search = isset($_GET['submit']);

if ($emp_id > 0) {
    $emp_data = $obj->select_record("employee_master", ['emp_id' => $emp_id]);

    $basic_salary = $emp_data['basic_salary'] ?? '';
    $mobile_no    = $emp_data['mobile_no'] ?? '';
    $shift_id     = $emp_data['shift_id'] ?? '';
    $allow_weekly_off     = $emp_data['allow_weekly_off'] ?? ''; 
    $date_of_joining = $emp_data['date_of_joining'];
    $department_id = $emp_data['department_id'] ?? '';
    $shift_working_hour = $emp_data['shift_id'] ?? '';
    $is_esic = $emp_data['is_esic'];
    $setting_type = ($is_esic  == 1) ? 'ESIC' : 'Non ESIC';

    $depart_data = $obj->select_record("department_master", ['department_id' => $department_id]);

    $department_name = $depart_data['department_name']??'';  
    $is_allow_c_off = $depart_data['c_off_check']??'';  
    $allow_earn_leave_carry = $depart_data['earn_leave_check']??'';  

    $shift_data = $obj->select_record("shift_master", ['shift_id' => $shift_id]);
    $shift_in_time  = $shift_data['in_time'] ?? '';
    $shift_out_time = $shift_data['out_time'] ?? '';

    $is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$emp_data[unit_id]'");
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
<style>
.table-borderless tr td {
    border: 0px !important;
    padding-bottom: 0px;
}
</style>

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

                    <div class="col-lg-6 col-md-6">
                        <div class="card card-height-100" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a
                                                    href="att_summary_report.php"
                                                    class="float-end btn btn-primary btn-sm">Back</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="department_id" class="form-label">Employee<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id">

                                                <option value="">Select</option>

                                                <?php
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $row) {
                                                ?>

                                                <option value="<?= $row['emp_id'] ?>"
                                                    <?= $emp_id == $row['emp_id'] ? 'selected' : '' ?>>

                                                    <?= $row['emp_code'] ?> - <?= $row['first_name'] ?>
                                                    <?= $row['last_name'] ?>

                                                </option>

                                                <?php } ?>

                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="department_id" class="form-label">Month<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="month">
                                                <option value="">Select</option>

                                                <?php
                                                $currentMonthNum = date('m');
                                                $currentYearNum  = date('Y');

                                                for ($m = 1; $m <= 12; $m++) {
                                                    if ($currentYear == $currentYearNum && $m > $currentMonthNum) {
                                                        $disabled = 'disabled';
                                                    } else {
                                                        $disabled = '';
                                                    }

                                                    $monthName = date("F", mktime(0, 0, 0, $m, 10));
                                                ?>
                                                <option value="<?= $m ?>" <?= $currentMonth == $m ? 'selected' : '' ?>
                                                    <?= $disabled ?>>
                                                    <?= $monthName ?>
                                                </option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="department_id" class="form-label">Year<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="year">
                                                <option value="">Select</option>

                                                <?php
                                                $currentYearNum = date("Y");
                                                $start = $currentYearNum - 5;
                                                $end   = $currentYearNum;

                                                for ($y = $start; $y <= $end; $y++) {
                                                    echo '<option value="' . $y . '" ' . ($currentYear == $y ? 'selected' : '') . '>' . $y . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary add-btn btn-sm"
                                                value="Search">
                                            <a href="<?php echo $pagename ?>"
                                                class="btn btn-danger add-btn btn-sm">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php if ($search && $emp_id > 0) { ?>
                    <div class="col-lg-6 col-md-6">
                        <div class="card card-height-100" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> Employee Details </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-lg-12">
                                        <table class="table table-bordered table-striped-columns">
                                            <thead>
                                                <tr>
                                                    <th>Basic Salary </th>
                                                    <td> <?= $basic_salary ?></td>
                                                    <th>Contact Details </th>
                                                    <td> <?= $mobile_no ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Shift Hours </th>
                                                    <td> <?= $shift_working_hour ?> Hrs</td>
                                                    <th>Department </th>
                                                    <td> <?= $department_name ?></td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($search && $emp_id > 0) {
                        $last_day_of_month = date('Y-m-t', strtotime("$currentYear-$currentMonth-01"));
                        if ($currentYear == date('Y') && $currentMonth == date('m')) {
                            $max_day = date('d');
                        } else {
                            $max_day = date('d', strtotime($last_day_of_month));
                        }

                         
                        $extra_off =$obj->getExtraOffBalance($emp_id, $currentMonth, $currentYear); 
                        $total_earning_leave = $obj->getEarningLeave($emp_id, $sessionid, $currentMonth, $currentYear);
                        $pending_coff =$obj->getEmpCoffLeave($emp_id, $sessionid, $currentMonth, $currentYear);

                        $res = $obj->executequery("
                            SELECT 
                                SUM(CASE 
                                    WHEN attendance_status = 'Present' THEN 1 
                                    ELSE 0 
                                END) AS total_present1,

                                SUM(CASE 
                                    WHEN attendance_status = 'Half Day' THEN 1 
                                    ELSE 0 
                                END) AS total_half1,

                                SUM(CASE 
                                    WHEN attendance_status IN ('Present','Weekly Leave','Earning Leave','C Off','Extra Off','Leave','Public Holiday') THEN 1 
                                    ELSE 0 
                                END) AS total_present,

                                SUM(CASE 
                                    WHEN attendance_status IN ('Extra Off') THEN 1 
                                    WHEN attendance_status IN ('Half Extra Off') THEN 0.5 
                                    ELSE 0 
                                END) AS tot_extra,

                                SUM(CASE 
                                    WHEN attendance_status IN ('C Off') THEN 1 
                                    WHEN attendance_status IN ('Half C Off') THEN 0.5 
                                    ELSE 0 
                                END) AS tot_coff,

                                SUM(CASE 
                                    WHEN attendance_status IN ('Earning Leave') THEN 1 
                                    WHEN attendance_status IN ('Half Earning Leave') THEN 0.5 
                                    ELSE 0 
                                END) AS tot_earning,

                                SUM(CASE 
                                    WHEN attendance_status IN ('Half Day','Half Weekly Leave','Half Earning Leave','Half C Off','Half Extra Off','Half Leave') THEN 1 
                                    ELSE 0 
                                END) AS total_half

                            FROM attendance_entry
                            WHERE emp_id = '$emp_id' 
                            AND month = '$currentMonth' 
                            AND year = '$currentYear' AND unit_id='$unitid'
                        ");
  
                        $row = $res[0] ?? [];
 
                        $total_present1 = $row['total_present1'] ?? 0;
                        $total_half1    = $row['total_half1'] ?? 0;
                        $total_present  = $row['total_present'] ?? 0;
                        $total_half     = $row['total_half'] ?? 0;
                        $tot_extra     = $row['tot_extra'] ?? 0;
                        $tot_coff     = $row['tot_coff'] ?? 0;
                        $tot_earning     = $row['tot_earning'] ?? 0; 
 

                        $real_total_attandence = $total_present1 + ($total_half1 / 2);
                        $total_attandence      = $total_present + ($total_half / 2);
 

                        $week_leave = $obj->totalWeeklyLeave($unitid, $real_total_attandence,$emp_id,$currentMonth ,$currentYear);
                        $earn_leave_day = $real_total_attandence + $week_leave;

                        $monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $earn_leave_day, $unitid);

                        //$three_month_leave = $obj->getLeave($emp_id, $currentMonth, $currentYear);

                        $holidayData = $obj->getHolidayCountWithSandwichRule($emp_id, $unitid, $currentMonth, $currentYear);

                        $holiday_total     = $holidayData['total'] ?? 0;
                        $holiday_national  = $holidayData['national'] ?? 0;
                        $holiday_religious = $holidayData['religious'] ?? 0;
                        $holiday_seasonal  = $holidayData['seasonal'] ?? 0;

                        // $total_payable_days = $total_attandence + $week_leave + $monthly_leave;

                        $result = $obj->calculateLeaveUsage(
                            $max_day,
                            $total_attandence,
                            $week_leave,
                            $monthly_leave,
                            //$three_month_leave,
                            $is_allow_c_off,
                            $is_all_leave_add,
                            $allow_earn_leave_carry,0,0,$date_of_joining,$currentMonth,$currentYear

                        );

                        $total_payable_days = $result['total_working_days'];
                    ?>
                    <div class="col-lg-12">
                        <div class="card card-body pt-2 pb-2">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="fs-13">Present: <b><?= $real_total_attandence ?></b></td>
                                    <td class="fs-13">
                                        Attendance With Availed Leave : <?= $total_attandence ?></b> <br>
                                        <span class="text-secondary">EO : <?= $tot_extra ?> | C :
                                            <?= $tot_coff ?> | L : <?= $tot_earning ?></span>
                                    </td>

                                    <td class="fs-13">Weekly Off : <b><?= $week_leave ?></b></td>
                                    <td class="fs-13">Earn Leave : <?= $monthly_leave ?></td>
                                    <td class="fs-13">Total Payable Days : <b><?= $total_payable_days ?></b> </td>
                                    <td class="fs-13">
                                        Holiday : <?= $holiday_total ?></b> <br>
                                        <span class="text-secondary">NH : <?= $holiday_national ?> | RH :
                                            <?= $holiday_religious ?> | SH : <?= $holiday_seasonal ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <h6 class="bg-body-secondary border-bottom-outset fs-15 mt-3 p-3 rounded-2 shadow-lg d-flex justify-content-between align-items-center"
                            style="background: linear-gradient(90deg, #edf2b7, #ffffff); border-color:#7696ff;">

                            <span>Extra Off : <?= $extra_off['balance'] ?></span>

                            <span>C-Off : <?= $pending_coff ?></span>
                            <!-- <span>Opening Leave Balance : <$opening_leave_balance ?></span> -->

                            <span>Pending Earn leave : <?= $total_earning_leave ?></span>

                        </h6>
                    </div>
                    <?php } ?>
                    <?php if ($search && $emp_id > 0) {
                    ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> List </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="buttons-datatables" class="display table table-sm table-bordered"
                                        style="width:100%">
                                        <thead>
                                            <tr class="table-primary">
                                                <th>Sr No.</th>
                                                <th>Attendence Date </th>
                                                <th>In Time </th>
                                                <th>Entry IN Type</th>
                                                <th>Out Time</th>
                                                <th>Entry Out Type</th>
                                                <th>Total Working Hour</th>

                                                <th>Machine Id</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                                $i = 1;
                                                $days = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

                                                $att_list = $obj->executequery("SELECT * FROM attendance_entry WHERE emp_id='$emp_id' AND month='$currentMonth' AND year='$currentYear'");

                                                $attendance = [];

                                                foreach ($att_list as $row) {
                                                    $attendance[$row['attendance_date']] = $row;
                                                }
                                                $today = date('Y-m-d');



                                                for ($d = 1; $d <= $max_day; $d++) {

                                                    $date = $currentYear . "-" . str_pad($currentMonth, 2, '0', STR_PAD_LEFT) . "-" . str_pad($d, 2, '0', STR_PAD_LEFT);

                                                    if (isset($attendance[$date])) {

                                                        $row = $attendance[$date];

                                                        $attendance_date = $row['attendance_date'];
                                                        $machineid  = $row['machineid'];
                                                        $intime  = $row['intime'];
                                                        $outtime = $row['outtime'];
                                                        $working_hours = $row['working_hours'];
                                                        $entry_type = $row['entry_type'];
                                                        $entry_type_out = $row['entry_type_out'];
                                                        $status = $row['attendance_status'];
                                                    } else {

                                                        $attendance_date = $date;
                                                        $machineid = '';
                                                        $intime = '';
                                                        $outtime = '';
                                                        $working_hours = '';
                                                        $entry_type = '';
                                                        $entry_type_out = '';
                                                        $status = 'Absent';
                                                    }

                                                    if ($status == "Present") {
                                                        $badge = "success";
                                                    } elseif ($status == "Absent") {
                                                        $badge = "danger";
                                                    } else {
                                                        $badge = "primary";
                                                    }
                                                ?>

                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $obj->dateformatindia($attendance_date) ?></td>
                                                <td><?= $intime ?></td>
                                                <td><?= ucfirst($entry_type) ?></td>
                                                <td><?= $outtime ?></td>
                                                <td><?= ucfirst($entry_type_out) ?></td>
                                                <td><?= $working_hours ?></td>

                                                <td><?= $machineid ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $badge ?> fs-11"><?= $status ?></span>
                                                </td>
                                            </tr>
                                            <?php } ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php } ?>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    </div>
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <script>
    $(document).ready(function() {
        $(".chosen-select").select2({
            width: '100%',
            search_contains: true
        });

        $('#show_field').select2({
            width: '100%'
        });
    });
 
    function numberOnly(evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\.|\s/;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }
    </script>
</body>

</html>