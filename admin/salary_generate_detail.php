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
$unit_pf_rate = $obj->getvalfield("unit_master", "pf_rate", "unit_id='$unitid'");
$unit_esic_rate = $obj->getvalfield("unit_master", "esic_rate", "unit_id='$unitid'");

$today = new DateTime();
if (isset($_GET['month'])) {
    $month = $_GET['month'];
    if ($month != '') {
        $crit .= " and  month='$month'";
    }
} else {
    $month = (int)date('m');
}

if (isset($_GET['year'])) {
    $year = $_GET['year'];
    if ($year != '') {
        $crit .= " and  year='$year'";
    }
} else {
    $year = date('Y');
}

if (!empty($_GET['month']) && !empty($_GET['year'])) {
    $month = (int)$_GET['month'];
    $year  = (int)$_GET['year'];
    if ($month == 1) {
        $prev_month = 12;
        $prev_year  = $year - 1;
    } else {
        $prev_month = $month - 1;
        $prev_year  = $year;
    }
} else {
    $prev_month = "";
    $prev_year  = "";
}
$total_working_day = 0;
if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    $emp_data =  $obj->select_record("employee_master", array("emp_id" => $emp_id));
    $last_increment_date = $emp_data['last_increment_date'];
    //$db_basic_salary  = $obj->getvalfield($tblname, "basic_salary", "emp_id='$emp_id' and month='$prev_month' and year='$prev_year'");
    $loan_data = $obj->select_record("loan_advance_details", ['emp_id' => $emp_id, 'month' => $month, 'year' => $year, 'status' => 1, 'type' => 'Loan']);
    $advance_data = $obj->select_record("loan_advance_details", ['emp_id' => $emp_id, 'month' => $month, 'year' => $year, 'status' => 1, 'type' => 'Advance']);
    $loan_amt  =  $loan_data['amount'] ?? 0;
    $loan_details_id  =  $loan_data['loan_details_id'] ?? 0;
    $advance_amt  =  $advance_data['amount'] ?? 0;
    $advance_details_id  =  $advance_data['loan_details_id'] ?? 0;
    $basic_salary = $emp_data['basic_salary'];
    $first_name = $emp_data['first_name'];
    $last_name = $emp_data['last_name'];
    $emp_code = $emp_data['emp_code'];
    $mobile_no = $emp_data['mobile_no'];
    $department_id = $emp_data['department_id'];
    $designation_id = $emp_data['designation_id'];
    $opening_balance_save = $emp_data['used_opening_balance'];
    $opening_balance_date = $emp_data['opening_date'];

    $depart_data  = $obj->select_record('department_master', array('department_id' => $department_id));
    $department = $depart_data['department_name'] ?? '';
    $is_allow_c_off = $depart_data['c_off_check'] ?? '';

    $unit = $obj->getvalfield("unit_master", "unit_name", "unit_id='$emp_data[unit_id]'");
    $is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$emp_data[unit_id]'");
    $date_of_joining = $emp_data['date_of_joining'];
    $is_pf = $emp_data['is_pf'];
    $is_esic = $emp_data['is_esic'];
    $allow_weekly_off = $emp_data['allow_weekly_off'];
    $setting_type = ($is_esic  == 1) ? 'ESIC' : 'Non ESIC';

    $holidayData = $obj->getHolidayCountWithSandwichRule(
        $emp_id,
        $unitid,
        $month,
        $year
    );
    $holiday     = $holidayData['total'] ?? 0;

    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year) ?? 31;

    $att_res = $obj->executequery("
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
                WHEN attendance_status IN ('Present','Weekly Leave','Earning Leave','C Off','Leave') THEN 1 
                ELSE 0 
            END) AS total_present,

            SUM(CASE 
                WHEN attendance_status IN ('Half Day','Half Weekly Leave','Half Earning Leave','Half C Off','Half Leave') THEN 1 
                ELSE 0 
            END) AS total_half,

            SUM(CASE 
                WHEN attendance_status IN ('Half Extra Off') THEN 0.5
                ELSE 0 
            END) AS total_half_extra_off,

            SUM(CASE 
                WHEN attendance_status IN ('Extra Off') THEN 1
                ELSE 0 
            END) AS total_extra_off,
             SUM(
            CASE 
                WHEN attendance_status = 'Leave' THEN 1
                WHEN attendance_status = 'Half Leave' THEN 0.5
                ELSE 0
            END
        ) AS total_opening_leave

        FROM attendance_entry
        WHERE emp_id = '$emp_id' 
        AND month = '$month' 
        AND year = '$year' AND unit_id='$unitid'
    ");
    $row = $att_res[0] ?? [];

    $total_present1 = $row['total_present1'] ?? 0;
    $total_half1    = $row['total_half1'] ?? 0;

    $total_present  = $row['total_present'] ?? 0;
    $total_half     = $row['total_half'] ?? 0;
    $total_half_extra_off     = $row['total_half_extra_off'] ?? 0;
    $total_extra_off     = $row['total_extra_off'] ?? 0;
    $total_opening_leave     = $row['total_opening_leave'] ?? 0;
    $used_extra_off = $total_extra_off + ($total_half_extra_off/2);
 
    $total_att_leave = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$month' AND unit_id='$unitid' and year='$year' and attendance_status IN('Leave') ");

    $overtime_days = $obj->getvalfield("emp_overtime", "no_of_overtime", "emp_id='$emp_id' and month='$month' and year='$year' AND unit_id='$unitid'");

    $total_working_day = $total_present + ($total_half / 2);
    $real_total_working_day = $total_present1 + ($total_half1 / 2);

    $count = $obj->getvalfield($tblname, "count(*)", "emp_id='$emp_id' and month='$month' and year ='$year'");
    $week_leave = $obj->totalWeeklyLeave($unitid, $real_total_working_day, $allow_weekly_off);
    $earn_leave_present =  $real_total_working_day+$week_leave;
    $monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $earn_leave_present, $unitid);
    

    //$three_month_leave = $obj->getLeave($emp_id, $month, $year);
    $extra_off =$obj->getExtraOffBalance($emp_id, $month, $year);
    $opening_leave_balance =$obj->get_opening_leave_balance($emp_id, $sessionid);

    $total_earning_leave = $obj->getEarningLeave($emp_id, $sessionid);

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
    $increment      = $obj->test_input($_POST['increment'] ?? '0');
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
   // $c_off_leave  = $obj->test_input($_POST['c_off_leave'] ?? '');
    $overtime_days  = $obj->test_input($_POST['overtime_days'] ?? '');
    $total_net_salary  = $obj->test_input($_POST['total_payable_salary'] ?? '');
    $additional_payment  = $obj->test_input($_POST['additional_payment'] ?? '');
    $total_pay_sal_after_ded  = $obj->test_input($_POST['total_pay_sal_after_ded'] ?? '');
    $other_deduction  = $obj->test_input($_POST['other_deduction'] ?? '');
    $tds_deduction  = $obj->test_input($_POST['tds_deduction'] ?? '');

    //$opening_leave_balance = $three_month_leave - $c_off_leave;
    //$usedCOff = $c_off_leave;

   
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
        "esic_rate"      => $esic_rate,
        "pf_paid_basic"      => $pf_paid_basic,
        "esic_paid_basic"      => $esic_paid_basic,
        "paid_holiday"      => $paid_holiday,
        "present_day"      => $present_days,
        "weekly_off"      => $weekly_off, // used week off
     
        "leave_days"      => $leave_days,// used earn leave
        //"c_off_leave"      => $c_off_leave, 
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
        "total_week_leave"  => $week_leave,
        "total_earn_leave"  => $monthly_leave,
        "pre_earn_leave"  => $total_earning_leave,
        "createdby"          => $loginid,
        "unit_id"          => $unitid,
        "ipaddress"          => $ipaddress,
        "sessionid" => $sessionid,
        "createdate"       => date('Y-m-d H:i:s'),
        "additional_payment" => $additional_payment,
        "total_pay_sal_after_ded" => $total_pay_sal_after_ded,
        "other_deduction" => $other_deduction,
        "tds_deduction" => $tds_deduction,
        "loan_amt" => $loan_amt,
        "advance_amt" => $advance_amt,
        "used_extra_off" => $used_extra_off,
        "total_opening_leave" => $total_opening_leave,
        "extra_off_balance" => $extra_off['balance'],
        "leave_balance" => $opening_leave_balance,
    );

    $overtimeDays = $week_leave - $weekly_off;
    $remining_earn_leave = $monthly_leave - $leave_days;

    $overtime_data = [
        "emp_id" => $emp_id,
        "department_id" => $department_id,
        "month" => $month,
        "year" => $year,
        "basic_salary" => $basic_salary,
        "total_leave" => $overtimeDays,
        "remining_leave" => $overtimeDays,
        "leave_type" => 'weekly',
        "unit_id" => $unitid,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid,
        "createdate" => date("Y-m-d H:i:s")
    ];
    //$previous_remining_leave = $previous_earn_leave + $remining_earn_leave;
    $remining_earn_leave_data = [
        "emp_id" => $emp_id,
        "department_id" => $department_id,
        "month" => $month,
        "year" => $year,
        "basic_salary" => $basic_salary,
        "total_leave" => $remining_earn_leave,
        "remining_leave" => $remining_earn_leave,
        "leave_type" => 'earning',
        "unit_id" => $unitid,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid,
        "createdate" => date("Y-m-d H:i:s")
    ];

    if ($keyvalue == 0) {
        $form_data["createdate"] = $createdate;
        $obj->update_record("loan_advance_details", ['loan_details_id' => $loan_details_id], ['is_paid' => 1, 'paid_date' => $createdate]);
        $obj->update_record("loan_advance_details", ['loan_details_id' => $advance_details_id], ['is_paid' => 1, 'paid_date' => $createdate]);

        $lastid = $obj->insert_record_lastid($tblname, $form_data);
  
        if ($overtimeDays > 0  && $is_allow_c_off == '1') {
            $overtime_data['salary_struc_id'] = $lastid;
            $obj->insert_record('emp_monthly_leave', $overtime_data);
        }

        if ($remining_earn_leave > 0  && $is_allow_c_off == '1') {
            $remining_earn_leave_data['salary_struc_id'] = $lastid;
            $obj->insert_record('emp_monthly_leave', $remining_earn_leave_data);
        }
 
        $action = 1;
        $process = "insert";

        $form_data1 = array(
            "primary_id" => $lastid,
            "flag" => $title,
            "activity_type" => 'Inserted',
            "createdby" => $loginid,
            "pagename" => $pagename,
            "created_date" => $createdate,
            "created_time" => date('H:i:s'),
            "unit_id" => $unitid,
            'ipaddress' => $ipaddress,
            "sessionid" => $sessionid
        );
        $logactivity = $obj->insert_record("logactivity_master", $form_data1);

         if($increment > 0){

           $tot_sal = $increment + $basic_salary;
            $obj->update_record(
                "employee_master",
                ['emp_id' => $emp_id],
                ['basic_salary' => $tot_sal, 'last_increment_date' => $createdate, 'prev_salary' => $basic_salary]
            );

            $form_increment = array(
            'emp_id' => $emp_id,
            'unit_id' => $unitid,
            'department_id' => $department_id,
            'salary_struc_id' => $lastid,
            'designation_id' => $designation_id,
            'basic_salary' => $tot_sal,
            'promotion_date' => $createdate,
            'type' => 'increment',
            'status' => '1',
            "createdate" => $createdate,
            "createdby" => $loginid,
            "ipaddress" => $ipaddress,
            "sessionid" => $sessionid
        );
        $lastid_inc =  $obj->insert_record_lastid("emp_promotion", $form_increment);
        $form_data1 = array(
            "primary_id" => $lastid_inc,
            "flag" => "Employee Increment",
            "activity_type" => 'Inserted',
            "createdby" => $loginid,
            "ipaddress" => $ipaddress,
            "unit_id" => $unitid,
            "created_date" => $createdate,
            "created_time" => date("H:i:s"),
            "sessionid" => $sessionid
        );
        $logactivity = $obj->insert_record("logactivity_master", $form_data1);
    }
    } else {
        $form_data["lastupdated"] = $createdate;
        $form_data["updatedby"] = $loginid;
        $where = array($tblpkey => $keyvalue);
        $obj->update_record($tblname, $where, $form_data);
        if ($overtimeDays > 0 && $is_allow_c_off == '1') {
            $where = array(
                'emp_id' => $emp_id,
                'salary_struc_id' => $keyvalue,
                'month'  => $month,
                'year'   => $year,
                'leave_type'   => 'weekly',
                'unit_id'   => $unitid
            );
            $obj->delete_record('emp_monthly_leave', $where);
            $obj->insert_record('emp_monthly_leave', $overtime_data);
        }

        if ($remining_earn_leave > 0  && $is_allow_c_off == '1') {
            $obj->insert_record('emp_monthly_leave', $remining_earn_leave_data);
        }
        if($increment > 0){
            $tot_sal = $increment + $basic_salary;
            $obj->update_record(
                "employee_master",
                ['emp_id' => $emp_id],
                ['basic_salary' => $tot_sal, 'last_increment_date' => $createdate, 'prev_salary' => $basic_salary]
            );

            $where2 = array(
                $tblpkey => $keyvalue,
                "emp_id" => $emp_id,
                "unit_id" => $unitid,
                "type" => 'increment'
            );

            $form_increment = array(
            'emp_id' => $emp_id,
            'unit_id' => $unitid,
            'department_id' => $department_id,
            'salary_struc_id' => $keyvalue,
            'designation_id' => $designation_id,
            'basic_salary' => $tot_sal,
            'promotion_date' => $createdate,
            'type' => 'increment',
            'status' => '1',
            "createdate" => $createdate,
            "createdby" => $loginid,
            "ipaddress" => $ipaddress,
            "sessionid" => $sessionid
            );

            // CHECK RECORD EXISTS
            $checkIncrement = $obj->select_record("emp_promotion", $where2);
            if (!empty($checkIncrement)) {
                $obj->update_record(
                    "emp_promotion",
                    $where2,
                    $form_increment
                );
            } else {
                // INSERT
                $lastid_inc = $obj->insert_record_lastid(
                    "emp_promotion",
                    $form_increment
                );
            }
            
        }
        $action = 2;
        $process = "updated";

        $form_data1 = array(
            "primary_id" => $keyvalue,
            "flag" => $title,
            "activity_type" => 'Updated',
            "createdby" => $loginid,
            "pagename" => $pagename,
            "created_date" => $createdate,
            "created_time" => date('H:i:s'),
            "unit_id" => $unitid,
            'ipaddress' => $ipaddress,
            "sessionid" => $sessionid
        );
        $logactivity = $obj->insert_record("logactivity_master", $form_data1);
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
   // $c_off_leave = $edit_data['c_off_leave'];
    $total_c_off = $edit_data['total_c_off'];
    $used_overtime_days = $edit_data['overtime_days'];
    $total_payable_salary = $edit_data['total_net_salary'];
    $total_week_leave = $edit_data['total_week_leave'];
    $total_earn_leave = $edit_data['total_earn_leave'];
    $pre_earn_leave = $edit_data['pre_earn_leave'];
    $additional_payment = $edit_data['additional_payment'];
    $total_pay_sal_after_ded = $edit_data['total_pay_sal_after_ded'];
    $other_deduction = $edit_data['other_deduction'];
    $tds_deduction = $edit_data['tds_deduction'];
    $used_extra_off = $edit_data['used_extra_off'];
    $total_opening_leave = $edit_data['total_opening_leave'];
    $extra_off_balance = $edit_data['extra_off_balance'];
    $leave_balance = $edit_data['leave_balance'];
} else {
    $total_working_days = $total_working_day;
    $increment =  $basic_pf_rate =  $revised_salary =  $pf_esic_basic =  $basic_da = $hra = $medical =  $conveyance =   $special_allow =   $total_salary = $pf_emp = $esic_emp =  $pf_employer =  $esic_employer = $pf_rate = $esic_rate = $basic_pf_rate = $pf_paid_basic = $esic_paid_basic =   $pf_rate =   $esic_rate = $pf_paid_basic =  $esic_paid_basic = "";
    $present_days = $paid_holiday =  $c_off_leave = $total_c_off = $total_payable_salary = "";
    $total_week_leave =  $total_earn_leave = $weekly_off = $leave_days = $pre_earn_leave = $used_overtime_days = '0';
    $total_pay_sal_after_ded = $additional_payment = $other_deduction = $tds_deduction= $extra_off_balance = $leave_balance = '0';
}

$slabs = $obj->executequery("SELECT sm.slab_id,sm.from_salary,sm.to_salary, ss.basic_percent,ss.hra_percent,ss.medical_allow,ss.conve_allow,ss.pf_per,ss.esic_per,ss.pf_emp_per,ss.esic_emp_per FROM salary_slab sm JOIN salary_slab_master ss ON ss.slab_id = sm.slab_id ORDER BY sm.from_salary ASC");
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
                    <?php if (!isset($_GET['search'])) { ?>
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
                                            <label for="emp_id" class="form-label">Employee Name<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id"
                                                id="emp_id">
                                                <option value="">Select Employee</option>
                                                <?php
                                                    //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                    $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                    foreach ($res as $key) { ?>
                                                <option value="<?= $key['emp_id']; ?>">
                                                    <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?>
                                                    <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById('emp_id').value =
                                                '<?= $emp_id; ?>';
                                            </script>
                                        </div>
                                        <!-- Month -->
                                        <div class="col-lg-3 col-12">
                                            <label for="month" class="form-label">Month<span
                                                    class="text-danger fw-bold">*</span></label>
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
                                            <label for="year" class="form-label">Year<span
                                                    class="text-danger fw-bold">*</span></label>
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
                                            <input type="submit" name="search" class="btn btn-sm btn-primary add-btn"
                                                value="Search" onClick="return checkinputmaster('emp_id,month,year')">
                                            <a href="<?php echo $pagename ?>"
                                                class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php  if (isset($_GET['search'])) { ?>
                    <div class="col-lg-12">
                        <div class="mb-12 text-end">
                            <a href="<?php echo $pagename; ?>" class="btn btn-primary btn-sm">
                                Search Again
                            </a>
                        </div>
                        <?php if ($emp_id > 0) { ?>
                        <div class="card card-body">
                            <div class="mb-12 text-center text-primary fw-bold">
                                <?= date('F', mktime(0, 0, 0, $month, 1)) . ' - ' . $year ?>
                            </div>
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr class="text-primary">
                                        <th>Employee Code</th>
                                        <th>Employee Name</th>
                                        <th>Date Of Joining</th>
                                        <th>Department</th>
                                        <th>Unit</th>
                                        <th>Contact No.</th>
                                        <th>Is PF</th>
                                        <th>Is ESIC</th>
                                        <th>Allow Weekly Off</th>
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
                                        <td>
                                            <input type="checkbox" class="form-check-input" data-type="pf"
                                                data-empid="<?= $emp_id ?>" <?= ($is_pf == 1) ? 'checked' : '' ?>
                                                onchange="update_pf_esic_check(this)">
                                        </td>

                                        <td>
                                            <input type="checkbox" class="form-check-input" data-type="esic"
                                                data-empid="<?= $emp_id ?>" <?= ($is_esic == 1) ? 'checked' : '' ?>
                                                onchange="update_pf_esic_check(this)">
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-check-input" data-type="allow_weekly_off"
                                                data-empid="<?= $emp_id ?>"
                                                <?= ($allow_weekly_off == 1) ? 'checked' : '' ?>
                                                onchange="update_pf_esic_check(this)">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="col-lg-12 mt-2">
                                <?php if ($msgtype != "") {
                                                ?>
                                <span><?php echo $msgtype . "<br>"  ?></span><?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if ($actType == 2) { ?>
                        <div class="card">
                            <div class="card-header border-bottom-dashed">
                                <h6 class="card-title text-primary mb-3"> Monthly Payment </h6>
                                <div class="align-items-center bg-body p-2 row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="d-flex justify-content-between  fw-semibold">

                                            <span> Total Earn Leave :
                                                <?= ($keyvalue > 0) ? $pre_earn_leave : $total_earning_leave; ?>
                                            </span>
                                            <span>Extra Off Balance :<span id="total_c_off_text" class="text-dark">
                                                 <?= ($keyvalue > 0) ? $extra_off_balance : $extra_off['balance']; ?>     </span>
                                            </span>
 
                                            <span>Leave Balance :<span class="text-dark">
                                                <?= ($keyvalue > 0) ? $leave_balance : $opening_leave_balance; ?>    </span>
                                            </span>

                                            <span> This Month Week Off : <?php if ($keyvalue > 0) { ?>
                                                <?= $total_week_leave ?>
                                                <?php } else { ?>
                                                <?= $week_leave ?>
                                                <?php } ?>
                                            </span>
                                            <span> This Month Earning Leave :<?php if ($keyvalue > 0) { ?>
                                                <?= $total_earn_leave ?>
                                                <?php } else { ?>
                                                <?= $monthly_leave ?>
                                                <?php } ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post" id="salaryForm">
                                    <div class="row">
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Basic Salary </label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $basic_salary; ?>" onkeyup="calculateForm()"
                                                name="basic_salary" id="basic_salary" readonly>
                                        </div>

                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="increment">Increment</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $increment ?>" name="increment" id="increment"
                                                onkeyup="calculateForm()">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Revised Gross Salary</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $revised_salary ?>" name="revised_salary"
                                                id="revised_salary">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="pf_rate">Basic PF Rate</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $pf_rate ?>" name="pf_rate" id="pf_rate">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="esic_rate">Basic ESIC Rate</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $esic_rate ?>" name="esic_rate" id="esic_rate">
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
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $pf_paid_basic ?>" name="pf_paid_basic" id="pf_paid_basic">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="esic_paid_basic"> ESIC Paid Basic</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $esic_paid_basic ?>" name="esic_paid_basic"
                                                id="esic_paid_basic">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Present Days</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $total_working_day ?>" name="present_days" id="present_days"
                                                readonly>
                                        </div>

                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="" class="text-success">Paid Holidays</label>
                                            <input type="text" class="form-control form-control-sm border-green"
                                                value="<?= $holiday ?>" name="paid_holiday" id="paid_holiday" readonly>
                                        </div>
                                        <!-- <div class="col-lg-2 col-12 mb-3">
                                                <label for="">Prev Three Month C - Off</label>
                                                <input type="text" class="form-control form-control-sm" value="< $c_off_leave ?>" name="c_off_leave" id="c_off_leave" readonly>
                                            </div> -->
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Used Week Off</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $weekly_off ?>" name="weekly_off" id="weekly_off" readonly>
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Used Earn Leave</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $leave_days ?>" name="leave_days" id="leave_days" readonly>
                                        </div>

                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="overtime_days">Used Extra Off</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $used_extra_off ?? 0 ?>" name="overtime_days"
                                                id="overtime_days" readonly>
                                        </div>

                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Total Payable Days</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $total_working_days ?>" name="total_working_days"
                                                id="total_working_days" onkeyup="calculateForm()" readonly>
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Basic + DA</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $basic_da ?>" name="basic_da" id="basic_da">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">HRA</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $hra ?>"
                                                name="hra" id="hra">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Medical Allowance </label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $medical ?>" name="medical" id="medical">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Conveyance Allowance </label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $conveyance ?>" name="conveyance" id="conveyance">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">Special Allowance</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $special_allow ?>" name="special_allow" id="special_allow">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <!-- <label for="">Total Salary </label> -->
                                            <label for="">Gross Salary </label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $total_salary ?>" name="total_salary" id="total_salary">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for=""> PF Emp Share </label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $pf_emp ?>" name="pf_emp" id="pf_emp">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">ESIC Emp Share </label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $esic_emp ?>" name="esic_emp" id="esic_emp">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">PF Employer Share</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $pf_employer ?>" name="pf_employer" id="pf_employer">
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="">ESIC Employer Share</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $esic_employer ?>" name="esic_employer" id="esic_employer">
                                        </div>


                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="" class="text-success">Total Gross Salary</label>
                                            <input type="text" class="form-control form-control-sm border-green"
                                                value="<?= $total_payable_salary ?>" name="total_payable_salary"
                                                id="total_payable_salary">
                                        </div>
                                        <div class="col-lg-1 col-12 mb-3">
                                            <label for="loan_amt">Loan</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $loan_amt ?>" name="loan_amt" id="loan_amt" readonly>
                                        </div>
                                        <div class="col-lg-1 col-12 mb-3">
                                            <label for="advance_amt">Advance</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $advance_amt ?>" name="advance_amt" id="advance_amt"
                                                readonly>
                                        </div>
                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="additional_payment">Additional</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $additional_payment ?>" name="additional_payment"
                                                id="additional_payment" onkeyup="calculateForm()">
                                        </div>

                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="other_deduction">Other Deduction</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $other_deduction ?>" name="other_deduction"
                                                id="other_deduction" onkeyup="calculateForm()">
                                        </div>

                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="tds_deduction">TDS Deduction</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $tds_deduction ?>" name="tds_deduction" id="tds_deduction"
                                                onkeyup="calculateForm()">
                                        </div>

                                        <div class="col-lg-2 col-12 mb-3">
                                            <label for="total_pay_sal_after_ded" class="text-success"> Total Net Salary
                                            </label>
                                            <input type="text" class="form-control form-control-sm border-green"
                                                value="<?= $total_pay_sal_after_ded ?>" name="total_pay_sal_after_ded"
                                                id="total_pay_sal_after_ded">
                                        </div>

                                        <div class="col-lg-12  text-center mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary add-btn"
                                                value="<?= $btn_name ?>"
                                                <?= $total_working_days > 0 ? '' : 'disabled' ?>>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php } ?>
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
        // if (keyval == 0) {
        totalWorking();
        //}
        //alert('hi');

        const salarySlabs = <?= json_encode($slabs); ?>;
        const is_pf = '<?= $is_pf; ?>';
        const unit_pf_rate = '<?= $unit_pf_rate; ?>';
        const unit_esic_rate = '<?= $unit_esic_rate; ?>';
        const is_esic = '<?= $is_esic; ?>';
        const month = '<?= $month; ?>';
        const year = '<?= $year; ?>';
        const totalDaysInMonth = new Date(year, month, 0).getDate();
        //alert(totalDaysInMonth);
        const form = document.getElementById('salaryForm');

        let presentSalary = parseFloat(document.getElementById('basic_salary').value) || 0;


        let incrementSalary = parseFloat(document.getElementById('increment').value) || 0;
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

        //additional
        let loanAmt = parseFloat($('#loan_amt').val()) || 0;
        let advanceAmt = parseFloat($('#advance_amt').val()) || 0;

        let total_pay_sal_after_ded_input = document.getElementById('total_pay_sal_after_ded');
        let additional_payment = parseFloat(document.getElementById('additional_payment').value) || 0;
        let other_deduction = parseFloat(document.getElementById('other_deduction').value) || 0;
        let tds_deduction = parseFloat(document.getElementById('tds_deduction').value) || 0;

        let totalRevisedSalary = presentSalary + incrementSalary;
        //let totalRevisedSalary = presentSalary;
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
        // unit_pf_rate
        // unit_esic_rate
        pf_basic_check = round(basicRate / totalDaysInMonth * daysWorked);
        if (is_pf == 1) {

            pf_rate_val = unit_pf_rate;
            if (pf_basic_check <= unit_pf_rate) {

                pf_paid_basic_val = round(basicRate / totalDaysInMonth * daysWorked);
                pf_val = round(pf_paid_basic_val * slab.pf_per / 100);
                pf_emp_val = round(pf_paid_basic_val * slab.pf_emp_per / 100);
            } else {
                pf_paid_basic_val = unit_pf_rate;
                pf_val = round(pf_paid_basic_val * slab.pf_per / 100);
                pf_emp_val = round(pf_paid_basic_val * slab.pf_emp_per / 100);
            }
        } else {
            if (totalRevisedSalary <= unit_pf_rate) {
                pf_rate_val = unit_pf_rate;
                if (pf_basic_check <= unit_pf_rate) {
                    pf_paid_basic_val = round(basicRate / totalDaysInMonth * daysWorked);
                    pf_val = round(pf_paid_basic_val * slab.pf_per / 100);
                    pf_emp_val = round(pf_paid_basic_val * slab.pf_emp_per / 100);
                } else {
                    pf_paid_basic_val = unit_pf_rate;
                    pf_val = round(pf_paid_basic_val * slab.pf_per / 100);
                    pf_emp_val = round(pf_paid_basic_val * slab.pf_emp_per / 100);
                }
            } else {
                pf_rate_val = '0';
                //pf_rate_val = basicRate;
                // pf_paid_basic_val = basicDA;
                pf_paid_basic_val = '0';
            }
        }

        esic_paid_basic_val = round(basicRate / totalDaysInMonth * daysWorked);
        if (is_esic == '1') {

            //if (basicRate <= unit_esic_rate) {
            esic_val = Math.ceil(esic_paid_basic_val * slab.esic_per / 100);
            esic_emp_val = round(esic_paid_basic_val * slab.esic_emp_per / 100);
            // }
        } else {
            // if (totalRevisedSalary <= unit_esic_rate) {
            if (basicRate <= unit_esic_rate) {
                esic_val = Math.ceil(esic_paid_basic_val * slab.esic_per / 100);
                esic_emp_val = round(esic_paid_basic_val * slab.esic_emp_per / 100);
            }
            // }
        }

        esic_rate.value = basicRate;
        // esic_paid_basic.value = basicDA;
        esic_paid_basic.value = esic_paid_basic_val;

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

        total_gross_salary = total_payable_amt - pf_val - esic_val;
        payable_salary = total_gross_salary + additional_payment;
        payable_salary = payable_salary - other_deduction - loanAmt - advanceAmt - tds_deduction;
        // total_payable_salary.value = total_payable_amt - pf_val - esic_val;
        //total_net = total_payable_amt - pf_val - esic_val + additional_payment;
        // netGrossInput.value = total_gross_salary
        total_pay_sal_after_ded_input.value = payable_salary;
        total_payable_salary.value = total_gross_salary;
    }
    </script>
    <script>
    function totalWorking() {

        const daysInMonth = parseInt('<?= $daysInMonth ?>') || 0;
        const weeklyBalance = parseFloat('<?= $week_leave ?>') || 0;
        const monthlyBalance = parseFloat('<?= $monthly_leave ?>') || 0;
        const is_allow_c_off = parseInt('<?= $is_allow_c_off ?>') || 0;
        const is_all_leave_add = parseFloat('<?= $is_all_leave_add ?>') || 0;
        const overtimeDays = parseFloat('<?= $used_extra_off ?>') || 0;

        let presentDays = parseFloat($('#present_days').val()) || 0;
        let holidays = parseFloat($('#paid_holiday').val()) || 0;
        let advanceLeave = parseFloat($('#advance_leave').val()) || 0;
        // let overtimeDays = parseFloat($('#overtime_days').val()) || 0;

        //let baseTotal = presentDays + holidays + advanceLeave + overtimeDays;
        let baseTotal = presentDays + holidays;
        //let baseTotal = presentDays;
        // console.log('baseTotal', baseTotal);

        let usedWeeklyLeave = 0;
        let usedMonthlyLeave = 0;
        let usedCOff = 0;
        let totalWorkingDays = 0;
        let used_overtime = 0;
        // CASE 1: C-OFF ALLOWED
        if (is_allow_c_off === 1) {

            let shortage = daysInMonth - baseTotal;
            if (shortage < 0) shortage = 0;

            used_overtime = Math.min(shortage, overtimeDays);
            shortage -= used_overtime;

            // usedCOff = Math.min(shortage, totalCOffBalance);
            // shortage -= usedCOff;
            // Weekly → Monthly → C-Off

            usedWeeklyLeave = Math.min(shortage, weeklyBalance);
            shortage -= usedWeeklyLeave;

            if (is_all_leave_add === 1) {
                usedMonthlyLeave = Math.min(shortage, monthlyBalance);
                shortage -= usedMonthlyLeave;
            }

            totalWorkingDays = baseTotal + usedWeeklyLeave + usedMonthlyLeave + used_overtime;
            totalWorkingDays = Math.min(totalWorkingDays, daysInMonth);

        }
        // CASE 2: C-OFF NOT ALLOWED
        else {
            used_overtime = overtimeDays;
            usedWeeklyLeave = weeklyBalance;
            if (is_all_leave_add === 1) {
                usedMonthlyLeave = monthlyBalance;
            }

            usedCOff = 0;

            totalWorkingDays =
                baseTotal +
                usedWeeklyLeave +
                used_overtime +
                usedMonthlyLeave;
        }

        // ✅ SET UI VALUES

        $('#weekly_off').val(usedWeeklyLeave);
        $('#leave_days').val(usedMonthlyLeave);
        //$('#c_off_leave').val(usedCOff);
        $('#overtime_days').val(used_overtime);

        //$('#used_c_off_text').text(usedCOff);

        $('#total_working_days').val(totalWorkingDays);
    }

    function update_pf_esic_check(el) {
        let emp_id = el.dataset.empid;
        let type = el.dataset.type;
        let value = el.checked ? 1 : 0;
        $.ajax({
            url: 'update_pf_esic.php',
            type: 'POST',
            data: {
                emp_id: emp_id,
                type: type,
                value: value
            },
            success: function(res) {
                location.reload();
            },
            error: function() {
                alert('Something went wrong!');
                el.checked = !el.checked;
            }
        });
    }
    </script>


</body>

</html>