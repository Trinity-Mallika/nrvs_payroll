<?php include("../adminsession.php");
$pagename = "salary_generate_detail.php";
$title = "Salary Generate Detail";
$module = "Salary Generate Detail";
$submodule = "Salary Generate List";
$tblname = "salary_structure";
$tblpkey = "salary_struc_id";
$btn_name = "Save";
$crit = "";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$today = new DateTime();
if (isset($_GET['month'])) {
    $month = $_GET['month'];
    if ($month != '') {
        $crit .= " and  month='$month'";
    }
} else {
    $month = "";
}
if (isset($_GET['year'])) {
    $year = $_GET['year'];
    if ($year != '') {
        $crit .= " and  year='$year'";
    }
} else {
    $year = "";
}
$total_working_day = 0;
if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    $emp_data =  $obj->select_record("employee_master", array("emp_id" => $emp_id));
    $basic_salary = $emp_data['basic_salary'];
    $first_name = $emp_data['first_name'];
    $last_name = $emp_data['last_name'];
    $emp_code = $emp_data['emp_code'];
    $mobile_no = $emp_data['mobile_no'];
    $department_id = $emp_data['department_id'];
    $department_id = $emp_data['department_id'];
    $department = $obj->getvalfield("department_master", "department_name", "department_id='$department_id'");

    $is_allow_c_off = $obj->getvalfield("department_master", "c_off_check", "department_id='$department_id'");

    $unit = $obj->getvalfield("unit_master", "unit_name", "unit_id='$emp_data[unit_id]'");
    $is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$emp_data[unit_id]'");
    $date_of_joining = $emp_data['date_of_joining'];
    $is_pf = $emp_data['is_pf'];
    $is_esic = $emp_data['is_esic'];
    $setting_type = ($is_esic  == 1) ? 'ESIC' : 'Non ESIC';
    $holiday  = $obj->getvalfield("holiday_entry", "count(*)", "unit_id='$unitid' AND MONTH(date) = '$month' AND YEAR(date) = '$year'");
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year) ?? 31;

    $total_present = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$month' and year='$year' and attendance_status='Present'");
    // echo $total_present;
    // die;
    $total_half = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$month' and year='$year' and attendance_status='Half Day'");

    $total_att_leave = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$month' and year='$year' and attendance_status='Leave'");

    $overtime_days = $obj->getvalfield("emp_overtime", "no_of_overtime", "emp_id='$emp_id' and month='$month' and year='$year'");

    $total_working_day = $total_present + ($total_half / 2);
    $count = $obj->getvalfield($tblname, "count(*)", "emp_id='$emp_id' and month='$month' and year ='$year'");


    $monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $total_working_day, $unitid);
    $week_leave = $obj->totalWeeklyLeave($unitid, $total_working_day);
    $three_month_leave = $obj->getLeave($emp_id, $month, $year);
    // echo $three_month_leave;
    // die;

    $loan =  $obj->getvalfield("emi_setting_details", "amount_detail", "emp_id='$emp_id' and month_detail='$month' and year_detail='$year'") ?? 0;

    $emi_setting_id =  $obj->getvalfield("emi_setting_details", "emi_setting_id", "emp_id='$emp_id' and month_detail='$month' and year_detail='$year'");

    if ($count > 0 && $keyvalue == 0) {
        $msgtype = "<span class='text-danger fw-bold'>Salary for this month has already been processed !!</span>";
        $actType = 1;
    } else {
        $msgtype  = '';
        $actType = 2;
    }
} else {
    $emp_id = $first_name = $last_name = $emp_code = $mobile_no = $department = $unit = $date_of_joining = "";
    $actType = '';
    $msgtype  = '';
    $is_pf  = '0';
    $is_esic  = '0';
}


if (isset($_POST['submit'])) {
    $basic_salary   = $obj->test_input($_POST['basic_salary'] ?? '');
    $increment      = $obj->test_input($_POST['increment'] ?? '');
    $revised_salary = $obj->test_input($_POST['revised_salary'] ?? '');
    // $basic_pf_rate  = $obj->test_input($_POST['basic_pf_rate'] ?? '');
    //$pf_esic_basic  = $obj->test_input($_POST['pf_esic_basic'] ?? '');
    $total_working_days   = $obj->test_input($_POST['total_working_days'] ?? '');
    $basic_da       = $obj->test_input($_POST['basic_da'] ?? '');
    $hra            = $obj->test_input($_POST['hra'] ?? '');
    $medical        = $obj->test_input($_POST['medical'] ?? '');
    $conveyance     = $obj->test_input($_POST['conveyance'] ?? '');
    $special        = $obj->test_input($_POST['special_allow'] ?? '');
    $total_salary   = $obj->test_input($_POST['total_salary'] ?? '');
    $pf_emp         = $obj->test_input($_POST['pf_emp'] ?? '');
    $esic_emp       = $obj->test_input($_POST['esic_emp'] ?? '');
    $pf_employer    = $obj->test_input($_POST['pf_employer'] ?? '');
    $esic_employer  = $obj->test_input($_POST['esic_employer'] ?? '');
    $pf_rate  = $obj->test_input($_POST['pf_rate'] ?? '');
    $esic_rate  = $obj->test_input($_POST['esic_rate'] ?? '');
    $pf_paid_basic  = $obj->test_input($_POST['pf_paid_basic'] ?? '');
    $esic_paid_basic  = $obj->test_input($_POST['esic_paid_basic'] ?? '');
    $present_days  = $obj->test_input($_POST['present_days'] ?? '');
    $paid_holiday  = $obj->test_input($_POST['paid_holiday'] ?? '');
    $weekly_off  = $obj->test_input($_POST['weekly_off'] ?? '');
    $leave_days  = $obj->test_input($_POST['leave_days'] ?? '');
    $c_off_leave  = $obj->test_input($_POST['c_off_leave'] ?? '');
    $overtime_days  = $obj->test_input($_POST['overtime_days'] ?? '');
    $total_net_salary  = $obj->test_input($_POST['total_payable_salary'] ?? '');
    //$is_loan_ded = isset($_POST['is_loan_ded']) ? 1 : 0;

    $opening_leave_balance = $three_month_leave - $c_off_leave;
    $usedCOff = $c_off_leave;
    // echo  $opening_leave_balance;
    // die;

    $form_data = array(
        "emp_id"             => $emp_id,
        "department_id"             => $department_id,
        "month"             => $month,
        "year"             => $year,
        "basic_salary"       => $basic_salary,
        "increment"       => $increment,
        "revised_salary"     => $revised_salary,
        // "basic_pf_rate"      => $basic_pf_rate,
        // "pf_esic_basic"      => $pf_esic_basic,
        "pf_rate"      => $pf_rate,
        // "is_loan_ded"      => $is_loan_ded,
        "esic_rate"      => $esic_rate,
        "pf_paid_basic"      => $pf_paid_basic,
        "esic_paid_basic"      => $esic_paid_basic,
        "paid_holiday"      => $paid_holiday,
        "present_day"      => $present_days,
        "weekly_off"      => $weekly_off,
        "advance_leave"      => $total_att_leave,
        "leave_days"      => $leave_days,
        "c_off_leave"      => $c_off_leave,
        "total_c_off"      => $three_month_leave,
        "overtime_days"      => $overtime_days,
        "total_working_days" => $total_working_days,
        "basic_da"           => $basic_da,
        "hra"                => $hra,
        "medical"            => $medical,
        "conveyance"         => $conveyance,
        "special_allow"      => $special,
        "total_salary"       => $total_salary,
        "total_net_salary"       => $total_net_salary,
        "pf_emp"             => $pf_emp,
        "esic_emp"           => $esic_emp,
        "pf_employer"        => $pf_employer,
        "esic_employer"      => $esic_employer,
        "payment_status" => '1',
        "createdby"          => $loginid,
        "unit_id"          => $unitid,
        "ipaddress"          => $ipaddress,
        "sessionid" => $sessionid,
        "createdate"       => date('Y-m-d H:i:s')
    );
    $is_esic  = $obj->getvalfield("employee_master", "is_esic", "emp_id='$emp_id'");

    $total_leave = $monthly_leave + $week_leave + $holiday;
    $totalAllowedDays = $total_working_day + $total_att_leave + $overtime_days + $total_leave;
    $overtimeDays = max(0, $totalAllowedDays - $daysInMonth);

    $overtime_data = [
        "emp_id" => $emp_id,
        "department_id" => $department_id,
        "month" => $month,
        "year" => $year,
        "basic_salary" => $basic_salary,
        "total_leave" => $overtimeDays,
        "remining_leave" => $overtimeDays,
        "unit_id" => $unitid,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid,
        "createdate" => date("Y-m-d H:i:s")
    ];
    // print_r($overtime_data);
    // die;

    if ($keyvalue == 0) {
        $form_data["createdate"] = $createdate;
        // print_r($form_data);
        // die;
        $obj->insert_record($tblname, $form_data);

        if ($opening_leave_balance != '') {
            $obj->update_record("employee_master", array('emp_id' => $emp_id), array('opening_balance' => $opening_leave_balance));
        }

        if ($overtimeDays > 0  && $is_allow_c_off == '1') {
            $where = array(
                'emp_id' => $emp_id,
                'month'  => $month,
                'year'   => $year,
                'unit_id'   => $unitid
            );
            $obj->delete_record('emp_monthly_leave', $where);
            $obj->insert_record('emp_monthly_leave', $overtime_data);
        }

        $baseDate = date('Y-m-d', strtotime("$year-$month-01"));
        $fromDate = date('Y-m-01', strtotime("-3 months", strtotime($baseDate)));
        $toDate   = date('Y-m-t', strtotime("-1 month", strtotime($baseDate)));
        $remainingToDeduct = $usedCOff;

        // Oldest first (FIFO)
        $rows = $obj->executequery("SELECT month_leave_id, remining_leave, month, year FROM emp_monthly_leave WHERE emp_id = '$emp_id' AND STR_TO_DATE(CONCAT(year,'-',month,'-01'), '%Y-%m-%d') BETWEEN '$fromDate' AND '$toDate' AND remining_leave > 0 ORDER BY month_leave_id ASC");

        foreach ($rows as $row) {
            if ($remainingToDeduct <= 0) break;

            $deduct = min($row['remining_leave'], $remainingToDeduct);

            $obj->update_record(
                "emp_monthly_leave",
                ['month_leave_id' => $row['month_leave_id']],
                ['remining_leave' => $row['remining_leave'] - $deduct]
            );
            $remainingToDeduct -= $deduct;
        }

        // if ($loan > 0 && $is_loan_ded == '0') {
        //     $emi_data = $obj->executequery("select * from emi_setting_details where emi_setting_id='$emi_setting_id'  AND (
        //         year_detail > '$year'
        //         OR (year_detail = '$year' AND month_detail >= '$month')
        //       )  ORDER BY year_detail DESC, month_detail DESC ");
        //     foreach ($emi_data as $emi) {
        //         list($newMonth, $newYear) = $obj->nextMonthYear(
        //             $emi['month_detail'],
        //             $emi['year_detail']
        //         );

        //         $obj->update_record("emi_setting_details", array('emi_setting_details_id' => $emi['emi_setting_details_id']), array('month_detail' => $newMonth, 'year_detail' => $newYear));
        //     }
        // }

        $action = 1;
        $process = "insert";
    } else {
        $form_data["lastupdated"] = $createdate;
        $where = array($tblpkey => $keyvalue);
        $obj->update_record($tblname, $where, $form_data);
        if ($overtimeDays > 0 && $is_allow_c_off == '1') {
            $where = array(
                'emp_id' => $emp_id,
                'month'  => $month,
                'year'   => $year,
                'unit_id'   => $unitid
            );
            $obj->delete_record('emp_monthly_leave', $where);
            $obj->insert_record('emp_monthly_leave', $overtime_data);
        }
        $action = 2;
        $process = "updated";
    }

    $query = $_GET;
    unset($query['action']);
    $query['action'] = $action;
    $url = $pagename . '?' . http_build_query($query);
    // echo "<script>location='$url'</script>";
    echo "<script>location='salary_generate_report.php'</script>";
};


if ($keyvalue != 0) {
    $btn_name = "Update";
    $edit_data = $obj->select_record("salary_structure", array("salary_struc_id" => $keyvalue));
    $basic_salary = $edit_data['basic_salary'] ?? '';
    $increment = $edit_data['increment'] ?? '';
    $emp_id = $edit_data['emp_id'];
    $month = $edit_data['month'];
    $year = $edit_data['year'];
    $revised_salary = $edit_data['revised_salary'];
    //$basic_pf_rate = $edit_data['basic_pf_rate'];
    // $pf_esic_basic = $edit_data['pf_esic_basic'];
    $total_working_days = $edit_data['total_working_days'];
    $basic_da = $edit_data['basic_da'];
    $hra = $edit_data['hra'];
    $medical = $edit_data['medical'];
    $conveyance = $edit_data['conveyance'];
    $special_allow = $edit_data['special_allow'];
    $total_salary = $edit_data['total_salary'];
    $pf_emp = $edit_data['pf_emp'];
    $esic_emp = $edit_data['esic_emp'];
    $pf_employer = $edit_data['pf_employer'];
    $esic_employer = $edit_data['esic_employer'];
    $pf_rate = $edit_data['pf_rate'];
    $esic_rate = $edit_data['esic_rate'];
    $pf_paid_basic = $edit_data['pf_paid_basic'];
    $esic_paid_basic = $edit_data['esic_paid_basic'];
    $total_working_day = $edit_data['present_day'];
    $paid_holiday = $edit_data['paid_holiday'];
    $weekly_off = $edit_data['weekly_off'];
    $leave_days = $edit_data['leave_days'];
    $c_off_leave = $edit_data['c_off_leave'];
    $total_c_off = $edit_data['total_c_off'];
    $overtime_days = $edit_data['overtime_days'];
    $total_payable_salary = $edit_data['total_net_salary'];
    //$is_loan_ded = $edit_data['is_loan_ded'];
} else {
    $total_working_days = $total_working_day;
    $increment =  $basic_pf_rate =  $revised_salary =  $pf_esic_basic =  $basic_da =   $hra = $medical =  $conveyance =   $special_allow =   $total_salary = $pf_emp = $esic_emp =  $pf_employer =  $esic_employer = $pf_rate = $esic_rate = $basic_pf_rate = $pf_paid_basic = $esic_paid_basic =   $pf_rate =   $esic_rate = $pf_paid_basic =  $esic_paid_basic = "";
    $present_days = $paid_holiday = $weekly_off = $leave_days = $c_off_leave = $total_c_off = $total_payable_salary = "";
    // $is_loan_ded = '1';
}


$slabs = $obj->executequery("SELECT sm.slab_id,sm.from_salary,sm.to_salary, ss.basic_percent,ss.hra_percent,ss.medical_allow,ss.conve_allow,ss.pf_per,ss.esic_per,ss.pf_emp_per,ss.esic_emp_per FROM salary_slab sm JOIN salary_slab_master ss ON ss.slab_id = sm.slab_id ORDER BY sm.from_salary ASC");
?>
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

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

    .border-green {
        border: 2px solid #0ab39c !important;
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
                <?php include('inc/alert.php');
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <label for="emp_id" class="form-label">Employee Name<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id" id="emp_id">
                                                <option value="">Select Employee</option>
                                                <?php
                                                //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?> <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('emp_id').value =
                                                    '<?= $emp_id; ?>';
                                            </script>
                                        </div>
                                        <!-- Month -->
                                        <div class="col-lg-3 col-12">
                                            <label for="month" class="form-label">Month<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select chosen-select" name="month" id="month">
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
                                            <script>
                                                document.getElementById('month').value = '<?php echo $month ?>'
                                            </script>
                                        </div>

                                        <!-- Year -->
                                        <div class="col-lg-3 col-12">
                                            <label for="year" class="form-label">Year<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select chosen-select" name="year" id="year">
                                                <option value="">Select</option>
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
                                        <div class="col-lg-3 col-12 mt-4">
                                            <input type="submit" name="search" class="btn btn-sm btn-primary add-btn" value="Search" onClick="return checkinputmaster('emp_id,month,year')">
                                            <a href="<?php echo $pagename ?>" class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>
                                        <div class="col-lg-12">
                                            <?php if ($msgtype != "") {
                                            ?>
                                                <span><?php echo $msgtype . "<br>"  ?></span><?php } ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php if ($emp_id > 0) { ?>
                            <div class="card card-body">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr class="text-primary">
                                            <th>Employee Code</th>
                                            <th>Employee Name</th>
                                            <th>Date Of Joining</th>
                                            <th>Department</th>
                                            <th>Unit</th>
                                            <th>Contact No.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?= $emp_code ?></td>
                                            <td><?= $first_name . " " . $last_name ?></td>
                                            <td><?= $obj->dateformatindia($date_of_joining); ?></td>
                                            <td><?= $department ?></td>
                                            <td><?= $unit ?></td>
                                            <td><?= $mobile_no ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                        <?php if ($actType == 2) { ?>
                            <div class="card bg-body">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row align-items-center">

                                        <div class="col-lg-3 col-md-12 mb-2 mb-lg-0">
                                            <h5 class="card-title text-primary mb-0">
                                                Monthly Payment
                                            </h5>
                                        </div>

                                        <div class="col-lg-9 col-md-12">
                                            <div class="d-flex flex-wrap gap-4 fw-semibold">

                                                <div>
                                                    Total C-Off :
                                                    <span id="total_c_off_text" class="text-dark">
                                                        <?= ($keyvalue > 0) ? $total_c_off : $three_month_leave; ?>
                                                    </span>
                                                </div>

                                                <div>
                                                    Used C-Off : <?php if ($keyvalue > 0) { ?>
                                                        <span class="text-danger">
                                                            <?= $c_off_leave ?>
                                                        </span>
                                                    <?php } else { ?>
                                                        <span id="used_c_off_text" class="text-danger"></span>
                                                    <?php } ?>
                                                </div>
                                                <div>
                                                    Remaining C-Off :
                                                    <?php if ($keyvalue > 0) { ?>
                                                        <span id="remaining_c_off_text" class="text-success">
                                                            <?= $total_c_off - $c_off_leave ?></span>
                                                    <?php } else { ?>
                                                        <span id="remaining_c_off_text" class="text-success"></span>
                                                    <?php } ?>
                                                </div>
                                                <div>
                                                    This Month Leave : <?= $total_att_leave ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card-body">
                                <form method="post" id="salaryForm">
                                    <div class="row">
                                        <!-- <div class="col-lg-2 mb-3 mt-4">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    name="is_loan_ded"
                                                    id="is_loan_ded"
                                                    value="1">
                                                <label class="form-check-label" for="is_loan_ded">
                                                    Loan Deduction
                                                </label>
                                            </div>
                                        </div> -->
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Basic Salary </label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $basic_salary; ?>" onkeyup="calculateForm()" name="basic_salary" id="basic_salary">
                                        </div>

                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Revised Gross Salary</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $revised_salary ?>" name="revised_salary" id="revised_salary">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="pf_rate">Basic PF Rate</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $pf_rate ?>" name="pf_rate" id="pf_rate">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="esic_rate">Basic ESIC Rate</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $esic_rate ?>" name="esic_rate" id="esic_rate">
                                        </div>
                                        <!-- <div class="col-lg-2 col-12 mb-3">
                                                <label for="basic_pf_rate">Basic+PF+ESIC Rate</label>
                                                <input type="text" class="form-control form-control-sm"   name="basic_pf_rate" id="basic_pf_rate">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="">PF+ESIC Paid Basic</label>
                                                <input type="text" class="form-control form-control-sm"  name="pf_esic_basic" id="pf_esic_basic">
                                            </div> -->
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="pf_paid_basic">PF Paid Basic</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $pf_paid_basic ?>" name="pf_paid_basic" id="pf_paid_basic">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="esic_paid_basic"> ESIC Paid Basic</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $esic_paid_basic ?>" name="esic_paid_basic" id="esic_paid_basic">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Present Days</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $total_working_day ?>" name="present_days" id="present_days" readonly>
                                        </div>
                                        <!-- <div class="col-lg-2 col-12 mb-3"> -->
                                        <!-- <label for="">Advaced Leave</label> -->
                                        <input type="hidden" class="form-control form-control-sm" value="<?= $total_att_leave ?>" name="advance_leave" id="advance_leave" readonly>
                                        <!-- </div> -->
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Paid Holidays</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $holiday ?>" name="paid_holiday" id="paid_holiday" readonly>
                                        </div>

                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Weekly Off</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $week_leave ?>" name="weekly_off" id="weekly_off" readonly>
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Earn Leave</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $monthly_leave ?>" name="leave_days" id="leave_days" readonly>
                                        </div>

                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Used C - Off</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $three_month_leave ?>" name="c_off_leave" id="c_off_leave" readonly>
                                        </div>

                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="overtime_days">Overtime Days</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $overtime_days ?? 0 ?>" name="overtime_days" id="overtime_days" readonly>
                                        </div>

                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Total Payable Days</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $total_working_days ?>" name="total_working_days" id="total_working_days" onkeyup="calculateForm()" readonly>
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Basic + DA</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $basic_da ?>" name="basic_da" id="basic_da">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">HRA</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $hra ?>" name="hra" id="hra">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Medical Allowance </label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $medical ?>" name="medical" id="medical">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Conveyance Allowance </label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $conveyance ?>" name="conveyance" id="conveyance">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Special Allowance</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $special_allow ?>" name="special_allow" id="special_allow">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Total Salary </label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $total_salary ?>" name="total_salary" id="total_salary">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for=""> PF Emp Share </label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $pf_emp ?>" name="pf_emp" id="pf_emp">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">ESIC Emp Share </label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $esic_emp ?>" name="esic_emp" id="esic_emp">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">PF Employer Share</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $pf_employer ?>" name="pf_employer" id="pf_employer">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">ESIC Employer Share</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $esic_employer ?>" name="esic_employer" id="esic_employer">
                                        </div>

                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="" class="text-success">Total Net Salary </label>
                                            <input type="text" class="form-control form-control-sm border-green" value="<?= $total_payable_salary ?>" name="total_payable_salary" id="total_payable_salary">
                                        </div>

                                        <div class="col-lg-12  text-center mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary add-btn" value="<?= $btn_name ?>" <?= $total_working_days > 0 ? '' : 'disabled' ?>>
                                        </div>
                                    </div>
                                </form>
                            </div>
                    </div>
                <?php } ?>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            $(".chosen-select").select2({
                width: '100%',
            });
            calculateForm();

        });


        function round(val) {
            return Math.round(Number(val) || 0);
        }
    </script>
    <script>
        function calculateForm() {
            const keyval = '<?= $keyvalue; ?>';
            if (keyval == 0) {
                totalWorking();
            }
            //alert('hi');

            const salarySlabs = <?= json_encode($slabs); ?>;
            const is_pf = '<?= $is_pf; ?>';
            const is_esic = '<?= $is_esic; ?>';
            const month = '<?= $month; ?>';
            const year = '<?= $year; ?>';
            const totalDaysInMonth = new Date(year, month, 0).getDate();
            //alert(totalDaysInMonth);
            const form = document.getElementById('salaryForm');

            let presentSalary = parseFloat(document.getElementById('basic_salary').value) || 0;


            // let incrementSalary = parseFloat(document.getElementById('increment').value) || 0;
            let daysWorked = parseFloat(document.getElementById('total_working_days').value) || 0;


            let revisedSalaryInput = document.getElementById('revised_salary');
            // let basicRateInput = document.getElementById('basic_pf_rate'); //rate
            // let pfBasicInput = document.getElementById('pf_esic_basic'); //paid

            let pf_rate = document.getElementById('pf_rate');
            let esic_rate = document.getElementById('esic_rate');
            let pf_paid_basic = document.getElementById('pf_paid_basic');
            let esic_paid_basic = document.getElementById('esic_paid_basic');

            let total_payable_salary = document.getElementById('total_payable_salary');

            let basicDAInput = document.getElementById('basic_da');
            let hraInput = document.getElementById('hra');
            let medicalInput = document.getElementById('medical');
            let conveyanceInput = document.getElementById('conveyance');
            let specialInput = document.getElementById('special_allow');
            let totalSalaryInput = document.getElementById('total_salary');

            let pfEmpInput = document.getElementById('pf_emp');
            let esicEmpInput = document.getElementById('esic_emp');
            let pfEmployerInput = document.getElementById('pf_employer');
            let esicEmployerInput = document.getElementById('esic_employer');


            //  let totalRevisedSalary = presentSalary + incrementSalary;
            let totalRevisedSalary = presentSalary;
            revisedSalaryInput.value = Math.round(totalRevisedSalary);


            let slab = salarySlabs.find(s =>
                totalRevisedSalary >= parseFloat(s.from_salary) &&
                (parseFloat(s.to_salary) == 0 || totalRevisedSalary < parseFloat(s.to_salary))
            );

            if (!slab) return;



            let basicRate = round(totalRevisedSalary * slab.basic_percent / 100);

            let basicDA = round(basicRate / totalDaysInMonth * daysWorked);
            let hra = round(basicDA * slab.hra_percent / 100);
            let perDaySal = round(totalRevisedSalary / totalDaysInMonth * daysWorked);

            if (daysWorked > totalDaysInMonth) {
                daysWorked = totalDaysInMonth;
            }

            let medical = round(slab.medical_allow / totalDaysInMonth * daysWorked);
            let convey = round(slab.conve_allow / totalDaysInMonth * daysWorked);
            let special = perDaySal - (basicDA + hra + medical + convey);

            let pf_val = 0;
            let pf_emp_val = 0;
            let esic_val = 0;
            let esic_emp_val = 0;
            let pf_amount = 0;
            let pf_rate_val = 0;
            let pf_paid_basic_val = 0;
            let esic_rate_val = 0;
            let esic_paid_basic_val = 0;

            total_payable_amt = basicDA + hra + medical + convey + special;
            //console.log('total_payable_amt', total_payable_amt);
            //console.log('basicRate', basicDA);

            // if (daysWorked > totalDaysInMonth) {
            //     pf_days = totalDaysInMonth;
            // } else {
            //     pf_days = daysWorked;
            // }

            if (is_pf == 1) {
                pf_basic_check = round(basicRate / totalDaysInMonth * daysWorked);
                pf_rate_val = 15000;
                if (pf_basic_check <= 15000) {

                    pf_paid_basic_val = round(basicRate / totalDaysInMonth * daysWorked);
                    pf_val = round(pf_paid_basic_val * slab.pf_per / 100);
                    pf_emp_val = round(pf_paid_basic_val * slab.pf_emp_per / 100);

                } else {

                    pf_paid_basic_val = 15000;
                    pf_val = round(pf_paid_basic_val * slab.pf_per / 100);
                    pf_emp_val = round(pf_paid_basic_val * slab.pf_emp_per / 100);
                }
            } else {
                pf_rate_val = '0';
                //pf_rate_val = basicRate;
                // pf_paid_basic_val = basicDA;
                pf_paid_basic_val = '0';
            }


            if (is_esic == '1') {
                if (basicRate <= 21000) {
                    esic_val = Math.ceil(basicDA * slab.esic_per / 100);
                    esic_emp_val = round(basicDA * slab.esic_emp_per / 100);
                }
            }



            esic_rate.value = basicRate;
            esic_paid_basic.value = basicDA;

            pf_rate.value = pf_rate_val;
            pf_paid_basic.value = pf_paid_basic_val;

            basicDAInput.value = basicDA;
            hraInput.value = hra;
            medicalInput.value = medical;
            conveyanceInput.value = convey;
            specialInput.value = special;
            totalSalaryInput.value = basicDA + hra + medical + convey + special;

            pfEmpInput.value = pf_val;
            esicEmpInput.value = esic_val;
            pfEmployerInput.value = pf_emp_val;
            esicEmployerInput.value = esic_emp_val;
            total_payable_salary.value = total_payable_amt - pf_val - esic_val;
        }
    </script>
    <script>
        function totalWorking() {

            const daysInMonth = parseInt('<?= $daysInMonth ?>') || 0;
            const $total_att_leave = parseInt('<?= $total_att_leave ?>') || 0;
            const totalCOffBalance = parseFloat('<?= $three_month_leave ?>') || 0;
            const is_allow_c_off = parseFloat('<?= $is_allow_c_off ?>') || 0;
            const is_all_leave_add = parseFloat('<?= $is_all_leave_add ?>') || 0;

            let presentDays = parseFloat(document.getElementById('present_days').value) || 0;
            let holidays = parseFloat(document.getElementById('paid_holiday').value) || 0;
            let weeklyOff = parseFloat(document.getElementById('weekly_off').value) || 0;
            let leaveDays = parseFloat(document.getElementById('leave_days').value) || 0;
            let advance_leave = parseFloat(document.getElementById('advance_leave').value) || 0;
            let overtime_days = parseFloat(document.getElementById('overtime_days').value) || 0;

            let baseTotal = 0;
            if (is_all_leave_add == '1') {
                baseTotal = presentDays + holidays + weeklyOff + leaveDays + advance_leave + overtime_days;
            } else {
                baseTotal = presentDays + holidays + weeklyOff + advance_leave + overtime_days;
            }

            let shortage = daysInMonth - baseTotal;

            let usedCOff = 0;
            if (shortage > 0) {
                usedCOff = Math.min(shortage, totalCOffBalance);
            }


            document.getElementById('c_off_leave').value = usedCOff;
            document.getElementById('used_c_off_text').innerText = usedCOff;
            document.getElementById('remaining_c_off_text').innerText =
                Math.max(0, totalCOffBalance - usedCOff);

            let totalWorkingDays = baseTotal + usedCOff;
            totalWorkingDays = Math.min(totalWorkingDays, daysInMonth);
            if (is_allow_c_off == '1') {
                document.getElementById('total_working_days').value = totalWorkingDays;

            } else {
                document.getElementById('total_working_days').value = baseTotal + totalCOffBalance;
                document.getElementById('c_off_leave').value = totalCOffBalance;
                document.getElementById('used_c_off_text').innerText = totalCOffBalance;
                document.getElementById('remaining_c_off_text').innerText = '0'
            }
        }
    </script>


</body>

</html>