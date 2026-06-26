<?php include("../adminsession.php");
$title = "Performance Incentive";
$pagename = "perform_incentive.php";
$module = "Search Performance Incentive";
$submodule = "Performance Incentive List";
$btn_name = "Search";
$keyvalue = 0;
$tblname = "attendance_entry";
$tblpkey = "attendance_id";

$crit2 = " ";

if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit2 .= " and e.department_id = '$department_id'";
    }
} else {
    $department_id = "";
};

 
if (isset($_GET['action'])) {
    $action = addslashes(trim($_GET['action']));
} else {
    $action = "";
}

$month = (int)date('m');
$year = date('Y');
$year_month = "";
if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = (int)$obj->test_input($_GET['month']);

    $year = $obj->test_input($_GET['year']);
}
$totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$get_days = $obj->getDaysArray($month, $year);
$length = count($get_days);


$currentMonth = date('m');
$currentYear = date('Y');
$currentDay = date('d');

if ($month == $currentMonth && $year == $currentYear) {
    $effectiveDays = $currentDay; // current date tak
} else {
    $effectiveDays = $totalDaysInMonth; // poora month
}

$showFields = [];

if (isset($_REQUEST['show_field_encoded'])) {

    if ($_REQUEST['show_field_encoded'] !== '') {
        $showFields = array_map(
            'intval',
            explode(',', $_REQUEST['show_field_encoded'])
        );
    } else {
        $showFields = [];
    }
} else {
    $showFields = [2, 3, 7, 8];
}


$fieldMap = [
    // 1 => ['label' => 'Mobile Number',     'key' => 'mobile_no'],
    2 => ['label' => 'Emp Code',         'key' => 'emp_code'],
    3 => ['label' => 'Emp Name',         'key' => 'first_name'],
    4 => ['label' => 'Aadhaar No',         'key' => 'aadhar_no'],
    5 => ['label' => 'Present Salary',    'key' => 'basic_salary'],
    6 => ['label' => 'Grade',             'key' => 'grade_name'],
    7 => ['label' => 'Department',        'key' => 'department_name'],
    8 => ['label' => 'Designation',       'key' => 'designation'],
    9 => ['label' => 'Date of Joining',   'key' => 'date_of_joining'],
    10 => ['label' => 'Job Location',       'key' => 'job_location'],
    11 => ['label' => 'Shift Hours',        'key' => 'shift_hours'],
]; 
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
table.dataTable>thead>tr>th:not(.sorting_disabled),
table.dataTable>thead>tr>td:not(.sorting_disabled) {
    padding-right: 5px !important;
}

table.dataTable>thead>tr>th:last-child:not(.sorting_disabled),
table.dataTable>thead>tr>td:last-child:not(.sorting_disabled) {
    padding-right: 20px !important;
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
                <?php if (!isset($_GET['search'])) { ?>
                <div class="row">
                    <div class="col-lg-12">
                        <fieldset class="mt-2">
                            <form action="<?php echo $pagename; ?>" method="get">
                                <div class="card">
                                    <div class="card-header border-bottom-dashed">
                                        <div class="row g-4 align-items-center">
                                            <div class="col-sm">
                                                <div>
                                                    <h5 class="card-title mb-0"><?= $module; ?><a
                                                            href="perform_incent_excel.php"
                                                            class="float-end btn btn-primary btn-sm">Add Employee Incentive</a> </h5>
                                                </div>

                                            </div>
                                        </div>
                                    </div> 
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-lg-3 mb-3">
                                                <label for="department_id" class="form-label">Department<span
                                                        class="text-danger fw-bold">*</span></label>
                                                <select class="form-select form-select-sm chosen-select"
                                                    name="department_id" id="department_id">
                                                    <option value="">Select</option>
                                                    <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_name asc");
                                                        foreach ($res as $key) { ?>
                                                    <option value="<?= $key['department_id']; ?>">
                                                        <?= $key['department_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <script>
                                                document.getElementById('department_id').value =
                                                    '<?= $department_id; ?>';
                                                </script>
                                            </div>
                                            <div class="col-md-3 md-2">
                                                <strong><label for="Month">Month<span
                                                            class="text-danger fw-bold">*</span></label></strong></br>
                                                <select name="month" class="chosen-select form-control form-control-sm"
                                                    id="month">

                                                    <?php for ($iM = 1; $iM <= 12; $iM++) {
                                                        ?>
                                                    <option value="<?php echo str_pad($iM, 2, '0', STR_PAD_LEFT); ?>">
                                                        <?php echo date("F", strtotime(str_pad($iM, 2, '0', STR_PAD_LEFT) . "/12/10")); ?>
                                                    </option>

                                                    <?php
                                                        } ?>
                                                </select>
                                                <script>
                                                document.getElementById('month').value =
                                                    '<?= str_pad($month, 2, "0", STR_PAD_LEFT); ?>';
                                                </script>
                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <label for="year" class="form-label">Year<span
                                                        class="text-danger fw-bold">*</span></label>
                                                <select class="form-select chosen-select form-control-sm" name="year"
                                                    id="year">

                                                    <?php
                                                        $startYear = 2025;
                                                        $endYear = 2100;
                                                        for ($year1 = $startYear; $year1 <= $endYear; $year1++) {
                                                            echo "<option value=\"$year1\">$year1</option>";
                                                        } ?>
                                                </select>
                                                <script>
                                                document.getElementById('year').value = '<?php echo $year ?>'
                                                </script>
                                            </div>

                                            <div class="col-md-3 mt-4 ">
                                                <input type="submit" class="btn btn-sm btn-primary add-btn"
                                                    onclick="return checkinputmaster('year,month')" name="search"
                                                    value="Search">
                                                <a href="<?php echo $pagename; ?>" class="btn btn-sm btn-danger" name="reset"
                                                    id="reset">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                </div>
                <?php } ?>
                <?php if (isset($_GET['search'])) {   ?>
                <div class="row mt-4 mb-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-0"><?php echo $submodule; ?> </h5>
                                        <h5 class="mb-0 fw-bold text-primary">
                                            <?= strtoupper(date('F', mktime(0, 0, 0, $month, 1))) . " - " . $year; ?>
                                        </h5>

                                        <a href="<?php echo $pagename; ?>" class="btn btn-primary btn-sm">
                                            Search Again
                                        </a>

                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php

                                    $fromDate = "$year-$month-01";
                                    $toDate   = date("Y-m-t", strtotime($fromDate));
                                       $firstDateOfMonth = date("Y-m-01", strtotime("$year-$month-01"));
                            $lastDateOfMonth = date("Y-m-t", strtotime("$year-$month-01")); 
                                    $employees = $obj->executequery("
                                    SELECT 
                                        e.emp_id,
                                        e.unit_id,
                                        e.allow_weekly_off,
                                        e.department_id,
                                        e.is_esic,
                                        e.emp_code,
                                        e.first_name,
                                        e.last_name,
                                        e.mobile_no,
                                        e.aadhar_no,
                                        e.shift_id,
                                        e.basic_salary,
                                        e.date_of_joining,
                                        e.job_location,
                                        g.grade_name,
                                        d.department_name,
                                        d.earn_leave_check,
                                        d.c_off_check,
                                        des.designation,
                                        s.working_hour AS shift_hours

                                    FROM employee_master e

                                    LEFT JOIN grade_master g 
                                        ON g.grade_id = e.grade_id

                                    LEFT JOIN department_master d 
                                        ON d.department_id = e.department_id

                                    LEFT JOIN designation_master des 
                                        ON des.designation_id = e.designation_id

                                    LEFT JOIN shift_master s 
                                        ON s.shift_id = e.shift_id
                                
                                    LEFT JOIN (
                                        SELECT a1.*
                                        FROM emp_active_status a1
                                        INNER JOIN (
                                            SELECT 
                                                emp_id,
                                                MAX(active_id) AS last_id
                                            FROM emp_active_status
                                            WHERE (
                                                    YEAR(last_inactive_date) < '$year'
                                                    OR (
                                                        YEAR(last_inactive_date) = '$year'
                                                        AND MONTH(last_inactive_date) <= '$month'
                                                    )
                                                )
                                            GROUP BY emp_id
                                        ) a2 
                                        ON a1.active_id = a2.last_id
                                    ) eas 
                                        ON eas.emp_id = e.emp_id

                                    WHERE 
                                        e.unit_id = '$unitid'   
                                        AND e.is_active = '1'
                                        AND e.date_of_joining <= '$lastDateOfMonth'

                                        AND (
                                            e.resign_status != '1' 
                                            OR (
                                                e.resign_status = '1' 
                                                AND e.last_working_date >= '$firstDateOfMonth'
                                            )
                                        )
                                        AND (
                                            eas.active_id IS NULL
                                            OR eas.is_active = '1'
                                        )
                                        $crit2  and e.unit_id='$unitid' and e.is_perform_incen='1'
                                    GROUP BY e.emp_id
                                    ORDER BY e.emp_code
                                "); 

                                    if (empty($employees)) {
                                        $employees = [];
                                    }
                                    $empIds = array_column($employees, 'emp_id');

                                    if (empty($empIds)) {
                                        $empIdsStr = "0";
                                    } else {
                                        $empIdsStr = implode(',', $empIds);
                                    }
                                    $currentDate = date("Y-m-d");

                                    $summaryRows = $obj->executequery("
                                                        SELECT emp_id,
                                                            SUM(attendance_status='Present') AS present,
                                                            SUM(attendance_status='Half Day') AS halfday,
                                                            SUM(attendance_status='Weekly Leave') AS leavecnt,
                                                            SUM(attendance_status='Earning Leave') AS leavecnt2,
                                                            SUM(attendance_status='Half Earning Leave') AS halfearn,
                                                            SUM(attendance_status='Half Weekly Leave') AS halfweek,
                                                            SUM(attendance_status='Leave') AS op_leave,
                                                            SUM(attendance_status='Half Leave') AS halfopleave,
                                                            SUM(attendance_status='Half Extra Off') AS half_extra_off,
                                                            SUM(attendance_status='Extra Off') AS extra_off,
                                                            SUM(attendance_status='C Off') AS coff,
                                                            SUM(attendance_status='Half C Off') AS halfcoff,
                                                            SUM(attendance_status='Public Holiday') AS public_holiday,
                                                            SUM(
                                                                CASE
                                                                    WHEN attendance_status = 'Leave' THEN 1
                                                                    WHEN attendance_status = 'Half Leave' THEN 0.5
                                                                    ELSE 0
                                                                END
                                                            ) AS total_opening_leave,
                                                            SUM(
                                                                CASE
                                                                    WHEN attendance_status = 'Extra Off' THEN 1
                                                                    WHEN attendance_status = 'Half Extra Off' THEN 0.5
                                                                    ELSE 0
                                                                END
                                                            ) AS total_used_extra_off,
                                                            SUM(CASE
                                                                    WHEN late_in IS NOT NULL
                                                                    AND late_in != '00:00:00'
                                                                THEN 1 ELSE 0 END) AS late_in_count,
                                                            SUM(CASE
                                                                    WHEN early_out IS NOT NULL
                                                                    AND early_out != '00:00:00'
                                                                THEN 1 ELSE 0 END) AS early_out_count

                                                        FROM attendance_entry
                                                        WHERE emp_id IN ($empIdsStr)
                                                        AND month='$month' AND year='$year'
                                                        GROUP BY emp_id
                                                    ");
                                    $summary = [];
                                    foreach ($summaryRows as $row) {
                                        $summary[$row['emp_id']] = $row;
                                    }
 
                                    ?>
                                <!-- floating scrollbar -->
                                <div class="auto-scroll-wrapper">
                                    <div class="table-responsive">
                                        <table id="buttons-datatables"
                                            class="table table-sm table-bordered table-hover align-middle display ">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Emp Code</th>
                                                    <th>Emp Name</th>
                                                    <th>Department</th>
                                                    <th>Basic Salary</th>
                                                    <th>Weekly Off</th>
                                                    <th>Used Week Off</th>
                                                    <th>curr. C-Off <br><span class="text-danger fw-bold">Performance
                                                            Incentive</span></th>
                                                    <th>Performance Incentive Sal.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $slno = 1;
                                                    $currentDate = date("Y-m-d");
                                                   
                                                    $total_week_leave = 0; 
                                                    $total_used_weekly = 0;
                                                    $total_monthly_leave = 0;
                                                    $total_used_monthly = 0; 
                                                    $total_curr_coff = 0; 
                                                    $total_performance_incentive_salary = 0;
                                                    foreach ($employees as $emp) {
                                                        $empId = $emp['emp_id'];
                                                        $depart_id = $emp['department_id'];
                                                        $allow_earn_leave_carry = $emp['earn_leave_check'];
                                                        $allow_weekly_off = $emp['allow_weekly_off'];
                                                        $is_allow_c_off = $emp['c_off_check'];
                                                        $date_of_joining = $emp['date_of_joining'];

                                                        $is_esic = $emp['is_esic'];
                                                        $setting_type = ($is_esic  == 1) ? 'ESIC' : 'Non ESIC';

                                                        $sum   = $summary[$empId] ?? ['present' => 0, 'halfday' => 0, 'leavecnt' => 0, 'leavecnt2' => 0, 'halfearn' => 0, 'halfweek' => 0, 'coff' => 0, 'halfcoff' => 0, 'op_leave' => 0, 'halfopleave' => 0, 'half_extra_off' => 0, 'extra_off' => 0, 'total_opening_leave' => 0, 'total_used_extra_off' => 0, 'public_holiday' => 0];

                                                        $totalAttendance = $sum['present'] + ($sum['halfday'] / 2) + $sum['leavecnt'] + $sum['leavecnt2'] + ($sum['halfearn'] / 2) + ($sum['halfweek'] / 2) + $sum['coff'] + ($sum['halfcoff'] / 2) + ($sum['halfopleave'] / 2) + ($sum['half_extra_off'] / 2) + $sum['op_leave'] + $sum['extra_off'] + $sum['public_holiday']; 
                                                      
                                                        $real_total_att = $sum['present'] + ($sum['halfday'] / 2);
                                                        

                                                        $is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$emp[unit_id]'"); 

                                                        $week_leave = $obj->totalWeeklyLeave($unitid, $real_total_att, $empId,$month,$year);

                                                        $earn_leave_present = $real_total_att + $week_leave; 
                                                        $monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $earn_leave_present, $unitid); 

                                                        $result = $obj->calculateLeaveUsage(
                                                            $effectiveDays,
                                                            $totalAttendance,
                                                            $week_leave,
                                                            $monthly_leave,
                                                            // $three_month_leave,
                                                            $is_allow_c_off,
                                                            $is_all_leave_add,
                                                            $allow_earn_leave_carry,0,0,$date_of_joining,$month,$year
                                                        );

                                                        $used_weekly_leave = $result['used_weekly'];
                                                        $used_monthly_leave = $result['used_monthly'];
                                                        $used_three_month_leave = $result['used_coff'];
                                                        $total_payable_days = $result['total_working_days'];  
                                                        
                                                        $current_coff = $week_leave - $used_weekly_leave;  
                                                        $current_earning = $monthly_leave - $used_monthly_leave;
  
                                                        $total_week_leave += $week_leave;
                                                        $total_used_weekly += $used_weekly_leave;
                                                        $total_curr_coff += $current_coff;

                                                        $perDaySalary = $emp['basic_salary'] / $totalDaysInMonth;
                                                      $performance_incentive_salary = round($current_coff * $perDaySalary);

 
                                                       
                                                        echo "<tr>";
                                                        echo "<td>
                                                                <span class='me-2'>" . $slno++ . "</span> 
                                                            </td>";

                                                        echo "<td class='text-center'>" . $emp['emp_code'] . "</td>";
                                                        echo "<td class='text-center'>" . $emp['first_name'] . "</td>";
                                                        echo "<td class='text-center'>" . $emp['department_name'] . "</td>";
                                                        echo "<td class='text-center'>" . $emp['basic_salary'] . "</td>";
                                                       
                                                      
                                                        
                                                        echo "<td class='text-center'>" . number_format($week_leave, 1) . "</td>";
                                                        echo "<td class='text-center'>" . number_format($used_weekly_leave, 1) . "</td>"; 
                                                        echo "<td class='text-center'>" . number_format($current_coff, 1) . "</td>"; 
                                                        echo "<td class='text-center'>" . number_format($performance_incentive_salary, 1) . "</td>"; 
                                                        echo "</tr>";
                                                    }

                                                    ?>
                                            </tbody>
                                            <tfoot>
                                                <tr style="font-weight:bold;background:#f5f5f5;">
                                                    <td colspan="5" class="text-end">
                                                        TOTAL
                                                    </td>
                                                    <td class="text-center">
                                                        <?= number_format($total_week_leave, 1) ?>
                                                    </td>

                                                    <td class="text-center">
                                                        <?= number_format($total_used_weekly, 1) ?>
                                                    </td>

                                                    <td class="text-center">
                                                        <?= number_format($total_curr_coff, 1) ?>
                                                    </td>
                                                    <td class='text-center'>
                                                        <?= number_format($total_performance_incentive_salary, 2) ?>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
            <!-- Content close-->
        </div>
    </div>





    <!-- script tag -->
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <!-- script tag -->

    <script>
    $(document).ready(function() {
        $(".chosen-select").select2();
        const $select = $("#show_field").select2({
            placeholder: "Select Fields",
            width: "100%",
            closeOnSelect: false
        });

        const selectedFields = <?= json_encode($showFields) ?> || [];
        if (selectedFields.length) {
            $select.val(selectedFields.map(String)).trigger("change");
        }

        $("form").on("submit", function() {
            const selected = $select.val() || [];
            $("#show_field_encoded").val(selected.join(","));
            $select.removeAttr("name");
        });

    });
    </script>
</body>

</html>