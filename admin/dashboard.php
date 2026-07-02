<?php include("../adminsession.php");
// print_r($_SESSION);
// die;

$pagename = "dashboard.php";

if (isset($_GET['header_session_id'])) {
    $header_session_id = $obj->test_input($_GET['header_session_id']);
} else {
    $header_session_id = $sessionid;
};

$datecurrent = date('Y-m-d');
 
$sql = "
    SELECT
        COUNT(
            CASE 
                WHEN (resign_status != '1' 
                OR (resign_status = '1' AND last_working_date >= CURDATE()))
                THEN 1
            END
        ) AS total_emp,

       COUNT(
            CASE
                WHEN is_active = 1
                    AND (
                            resign_status != '1'
                            OR (resign_status = '1' AND last_working_date >= CURDATE())
                        )
                THEN 1
            END
        ) AS total_active_emp,

        COUNT(
            CASE 
                WHEN DAY(dob) = DAY(CURDATE())
                 AND MONTH(dob) = MONTH(CURDATE())
                THEN 1
            END
        ) AS today_dob,

        COUNT(
            CASE 
                WHEN DAY(anniversary_date) = DAY(CURDATE())
                 AND MONTH(anniversary_date) = MONTH(CURDATE())
                THEN 1
            END
        ) AS today_anny,

        COUNT(
            CASE 
                WHEN DAY(date_of_joining) = DAY(CURDATE())
                 AND MONTH(date_of_joining) = MONTH(CURDATE())
                THEN 1
            END
        ) AS work_anny,

        COUNT(
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= 60
                THEN 1
            END
        ) AS sixty_plus_emp

    FROM employee_master
    WHERE unit_id = '$unitid'
";

$emp_res = $obj->executequery($sql);
$emp_row = $emp_res[0]; // agar executequery result object return karta hai

$total_emp      = $emp_row['total_emp'] ?? 0;
$total_active_emp      = $emp_row['total_active_emp'] ?? 0;
$today_dob      = $emp_row['today_dob'] ?? 0;
$today_anny     = $emp_row['today_anny'] ?? 0;
$work_anny      = $emp_row['work_anny'] ?? 0;
$sixty_plus_emp = $emp_row['sixty_plus_emp'] ?? 0;
 
$sql = "
SELECT
    COUNT(CASE WHEN attendance_status = 'Incomplete' THEN 1 END) AS today_incomplete,

    COUNT(CASE WHEN attendance_status = 'Present' THEN 1 END) AS today_present,

    COUNT(CASE WHEN attendance_status = 'Half Day' THEN 1 END) AS today_half_day,

    COUNT(CASE 
        WHEN attendance_status IN ('Present','Half Day','Incomplete','Absent')
        THEN 1
    END) AS todayin,

    COUNT(CASE 
        WHEN attendance_status IN ('Weekly Leave','Earning Leave','C Off','Leave','Extra Off')
        THEN 1
    END) AS today_leave

FROM attendance_entry
WHERE attendance_date = '$datecurrent'
  AND sessionid = '$header_session_id'
  AND unit_id = '$unitid'
";

$att_res = $obj->executequery($sql);
$att = $att_res[0];

$today_incomplete = $att['today_incomplete'] ?? 0;
$today_present    = $att['today_present'] ?? 0;
$today_half_day   = $att['today_half_day'] ?? 0;
$todayin          = $att['todayin'] ?? 0;
$today_leave      = $att['today_leave'] ?? 0;

 

// $today_ab = $obj->getvalfield("attendance_entry", "count(*)", "attendance_status IN ('Absent') and attendance_date='$datecurrent' and sessionid='$header_session_id' AND unit_id='$unitid'");


$currentMonth = (int) date('m');
$currentYear = date('Y');
if ($currentMonth == 1) {
    $lastMonth = 12;
    $lastYear  = $currentYear - 1;
} else {
    $lastMonth = $currentMonth - 1;
    $lastYear  = $currentYear;
}

$total_absent = $total_emp - ($today_incomplete + $today_present + $today_half_day + $today_leave);

$department = $obj->executequery("SELECT * FROM department_master WHERE unit_id='$unitid' ");

$deptLabels = [];
$deptData   = [];

foreach ($department as $row) { 
    $deptLabels[] = $row['department_name'];
    $salary_count = $obj->getvalfield("salary_structure", "count(*)", "unit_id='$unitid' and month='$lastMonth' and year='$lastYear' and department_id='$row[department_id]' and sessionid='$header_session_id'");

    $deptData[] = (int)$salary_count;
}

 

$monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$salaryTrend = [];
for ($m = 1; $m <= 12; $m++) {
    $salary_count = $obj->getvalfield(
        "salary_structure",
        "SUM(total_salary)",
        "unit_id='$unitid'
         AND month='$m'
         AND year='$currentYear' and sessionid='$header_session_id'"
    );

    $salaryTrend[] = (int)($salary_count ?? 0);
}

$sql = "
    SELECT 
        COALESCE(SUM(total_pay_sal_after_ded), 0) AS last_month_salary,
        COALESCE(SUM(total_net_salary), 0) AS total_net_salary,
        COALESCE(SUM(additional_payment), 0) AS additional_payment,
        COALESCE(SUM(other_deduction), 0) AS other_deduction,
        COALESCE(SUM(pf_emp), 0) AS total_pf,
        COALESCE(SUM(esic_emp), 0) AS total_esic,
        COALESCE(SUM(tds_deduction), 0) AS total_tds,
        COALESCE(SUM(pf_employer), 0) AS total_pf_emp,
        COALESCE(SUM(loan_amt), 0) AS total_loan_amt,
        COALESCE(SUM(advance_amt), 0) AS total_advance_amt,
        COALESCE(SUM(esic_employer), 0) AS total_esic_emp
    FROM salary_structure
    WHERE unit_id = '$unitid'
      AND month = '$lastMonth'
      AND year = '$lastYear'
      AND sessionid = '$header_session_id'
";
$res = $obj->executequery($sql);
$sal = $res[0]; 

$total_net_salary  = $sal['total_net_salary'] ?? 0;
$additional_payment  = $sal['additional_payment'] ?? 0;
$other_deduction  = $sal['other_deduction'] ?? 0;
$total_pf          = $sal['total_pf'] ?? 0;
$total_esic        = $sal['total_esic'] ?? 0;
$total_tds         = $sal['total_tds'] ?? 0;
$total_pf_emp      = $sal['total_pf_emp'] ?? 0;
$total_esic_emp    = $sal['total_esic_emp'] ?? 0;
$total_advance_amt    = $sal['total_advance_amt'] ?? 0;
$total_loan_amt    = $sal['total_loan_amt'] ?? 0;
$total_tds    = $sal['total_tds'] ?? 0;
$last_month_salary = $total_net_salary + $additional_payment -($other_deduction + $total_advance_amt + $total_loan_amt + $total_tds)  ;

$deduction_esic_pf = $total_pf_emp + $total_esic_emp;


$deduction_summary = $obj->executequery("SELECT 
            SUM(
                CASE 
                    WHEN lad.type='Advance' 
                        AND lad.status = 1
                    THEN lad.amount
                    ELSE 0
                END
            ) as advance_deduction,

            SUM(
                CASE 
                    WHEN lad.type='Loan' 
                        AND lad.status = 1
                    THEN lad.amount
                    ELSE 0
                END
            ) as loan_deduction

        FROM loan_advance_details lad

        INNER JOIN loan_advance la 
            ON lad.loan_advance_id = la.loan_advance_id

        WHERE la.unit_id='$unitid'
        AND lad.month='$lastMonth'
        AND lad.year = YEAR(CURDATE()) ");
$sum = $deduction_summary[0];
 

?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title>Dashboard</title>
    <?php include('inc/css.php') ?>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>
<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #f4f6fb;
        color: #2b2f38;
    }

    /* Cards */
    .card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        margin-bottom: 0px;
    }

    .card-header {
        background: transparent;
        font-weight: 600;
        border-bottom: 1px solid #eef0f5;
    }

    /* Stat cards */
    .stat-card {
        color: #fff;
        padding: 22px;
    }

    .stat-blue {
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
    }

    .stat-green {
        background: linear-gradient(135deg, #16a34a, #22c55e);
    }

    .stat-orange {
        background: linear-gradient(135deg, #f97316, #fb923c);
    }

    .stat-purple {
        background: linear-gradient(135deg, #7c3aed, #a855f7);
    }

    .stat-card small {
        opacity: .85;
    }

    .stat-card h3 {
        margin-top: 8px;
        font-weight: 700;
    }

    /* Buttons */
    .btn {
        border-radius: 10px;
        font-weight: 500;
    }

    /* Table */
    .table thead th {
        background: #f1f3f9;
        font-weight: 600;
    }

    .table tbody tr:hover {
        background: #f9faff;
    }

    /* Charts spacing */
    canvas {
        max-height: 220px;
    }
</style>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include('inc/header.php') ?>

        <!-- ========== App Menu ========== -->
        <?php include('inc/sidebar.php') ?>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">

                <div class="container-fluid">

                    <!-- STATISTICS -->
                    <div class="row g-4 mb-4">
                         <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header text-center bg-primary ">
                                    <h5 class="text-white mb-0">Employee</h5>
                                </div>
                                <div class="card-body pt-1 pb-2">
                                    <div class="row">
                                        <div class="col-3">
                                             <img src="img/total-user.png" class="w-100" alt="">
                                        </div>
                                        <div class="col-9 pt-1 text-end">
                                            <h6 class="mb-1">
                                                <a href="employee_report.php?submit=Search"
                                                    class="text-primary  text-decoration-none fw-bold ">
                                                    <?= number_format($total_emp, 2) ?> Total Employee
                                                </a>
                                            </h6>
                                            <h6 class="mb-1">
                                               <a href="employee_report.php?resign_status=1&&submit=Search"
                                                    class="text-primary text-decoration-none fw-bold">
                                                    <?= number_format($total_active_emp, 2) ?> Active Employee
                                                </a>
                                            </h6>

                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card -->
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-3">
                            <!-- card -->
                            <a class="card h-100" href="salary_generate_report.php">
                                <div class="card-header text-center bg-primary ">
                                    <h5 class="text-white mb-0">Last Month Salary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3">
                                            <img src="img/month-salary.png" class="w-100" alt="">
                                        </div>
                                        <div class="col-9">
                                            <h3 class="mt-2 text-end fw-bold">₹
                                                <?= number_format($last_month_salary, 2)   ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </a><!-- end card -->
                        </div><!-- end col -->
                        <div class="col-xl-3 col-md-6 mb-3">
                            <!-- card -->
                            <div class="card h-100">
                                <div class="card-header text-center bg-primary ">
                                    <h5 class="text-white mb-0">Today Attendance</h5>
                                </div>
                                <div class="card-body pt-1 pb-2">
                                    <div class="row">
                                        <div class="col-3">
                                            <img src="img/today-attandance.png" class="w-100 pt-2" alt="">
                                        </div>
                                        <div class="col-9 pt-1 text-end">

                                            <h6 class="mb-1">
                                                <a href="day_wise_attendence_report.php?attendance_date=<?= $datecurrent; ?>&att_action=Incomplete"
                                                    class="text-warning  text-decoration-none fw-bold">
                                                    <?= $today_incomplete ?> Incomplete
                                                </a>
                                            </h6>
                                            <h6 class="mb-1">
                                                <a href="day_wise_attendence_report.php?attendance_date=<?= $datecurrent; ?>&att_action=Present"
                                                    class="text-success text-decoration-none fw-bold">
                                                    <?= $today_present ?> Present
                                                </a>
                                            </h6>
                                            <h6 class="mb-1">
                                                <a href="day_wise_attendence_report.php?attendance_date=<?= $datecurrent; ?>&att_action=Half Day"
                                                    class="text-primary text-decoration-none fw-bold">
                                                    <?= $today_half_day ?> Half Day
                                                </a>
                                            </h6>
                                            <h6 class="mb-1">
                                                <a href="day_wise_attendence_report.php?attendance_date=<?= $datecurrent; ?>&att_action=Absent"
                                                    class="text-danger text-decoration-none fw-bold">
                                                    <?= $total_absent ?> Absent
                                                </a>
                                            </h6>
                                            <!-- <h6>
                                                <a href="day_wise_attendence_report.php?attendance_date=<?= $datecurrent; ?>&att_action=Leave"
                                                    class="text-warning text-decoration-none fw-bold">
                                                    <?= $today_leave ?> Leave
                                                </a>
                                            </h6> -->
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card -->
                        </div><!-- end col -->
                        <div class="col-xl-3 col-md-6 mb-3">

                            <div class="card h-100">
                                <div class="card-header text-center bg-primary ">
                                    <h5 class="text-white mb-0">PF Deduction</h5>
                                </div>
                                <div class="card-body pt-1 pb-2">
                                    <div class="row">
                                        <div class="col-3">
                                            <img src="img/deduction.png" class="w-100" alt="">
                                        </div>
                                        <div class="col-9 pt-1 text-end">
                                            <h6 class="mb-1">
                                                <a href="#"
                                                    class="text-primary  text-decoration-none fw-bold ">
                                                    <?= number_format($total_pf, 2) ?> PF Employee
                                                </a>
                                            </h6>
                                            <h6 class="mb-1">
                                                <a href="#"
                                                    class="text-primary text-decoration-none fw-bold">
                                                    <?= number_format($total_pf_emp, 2) ?> PF Employer
                                                </a>
                                            </h6>

                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card -->
                        </div>
                        <div class="col-xl-3 col-md-6 mt-0 mb-3">

                            <div class="card h-100">
                                <div class="card-header text-center bg-primary ">
                                    <h5 class="text-white mb-0">ESIC Deduction</h5>
                                </div>
                                <div class="card-body pt-1 pb-2">
                                    <div class="row">
                                        <div class="col-3">
                                            <img src="img/deduction.png" class="w-100" alt="">
                                        </div>
                                        <div class="col-9 pt-1 text-end">
                                            <h6 class="mb-1">
                                                <a href="#"
                                                    class="text-primary  text-decoration-none fw-bold ">
                                                    <?= number_format($total_esic, 2) ?> ESIC Employee
                                                </a>
                                            </h6>
                                            <h6 class="mb-1">
                                                <a href="#"
                                                    class="text-primary text-decoration-none fw-bold">
                                                    <?= number_format($total_esic_emp, 2) ?> ESIC Employer
                                                </a>
                                            </h6>

                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card -->
                        </div>
                        <div class="col-xl-3 col-md-6 mt-0 mb-3">

                            <div class="card h-100">
                                <div class="card-header text-center bg-primary ">
                                    <h5 class="text-white mb-0">Loan/Adv Ded.</h5>
                                </div>
                                <div class="card-body pt-1 pb-2">
                                    <div class="row">
                                        <div class="col-3">
                                            <img src="img/deduction.png" class="w-100" alt="">
                                        </div>
                                        <div class="col-9 pt-1 text-end">
                                            <h6 class="mb-1">
                                                <a href="#"
                                                    class="text-primary  text-decoration-none fw-bold">
                                                    ₹ <?= number_format($total_loan_amt, 2) ?> Loan
                                                </a> 
                                            </h6>
                                            <h6 class="mb-1">
                                                <a href="#"
                                                    class="text-primary text-decoration-none fw-bold">
                                                    ₹ <?= number_format($total_advance_amt, 2) ?> Advance
                                                </a>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card -->
                        </div>

                        <!-- end col -->
                        <div class="col-xl-3 col-md-6 mt-0 mb-3">
                            <!-- card -->
                            <a class="card h-100" href="employee_report.php?sixty_plus_age=1&submit=Search">
                                <div class="card-header text-center bg-primary ">
                                    <h5 class="text-white mb-0">60+ Age Employee</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3">
                                            <img src="img/employee.png" class="w-100" alt="">
                                        </div>
                                        <div class="col-9">
                                            <h3 class="mt-2 text-end fw-bold"><?= $sixty_plus_emp; ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </a><!-- end card -->
                        </div><!-- end col -->

                        <div class="col-xl-3 col-md-6 mt-0 mb-3">
                            <!-- card -->
                            <a class="card h-100" href="employee_report.php?dob=<?= $datecurrent; ?>&submit=Search">
                                <div class="card-header text-center bg-primary ">
                                    <h5 class="text-white mb-0">Today Birthday</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3">
                                            <img src="img/happy-birthday.png" class="w-100" alt="">
                                        </div>
                                        <div class="col-9">
                                            <h3 class="mt-2 text-end fw-bold"><?= $today_dob; ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </a><!-- end card -->
                        </div>
                        <div class="col-xl-3 col-md-6 mt-0">
                            <!-- card -->
                            <a class="card h-100"
                                href="employee_report.php?anniversary_date=<?= $datecurrent; ?>&submit=Search">
                                <div class="card-header text-center bg-primary ">
                                    <h5 class="text-white mb-0">Today Anniversary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3">
                                            <img src="img/anniversary.png" class="w-100" alt="">
                                        </div>
                                        <div class="col-9">
                                            <h3 class="mt-2 text-end fw-bold"><?= $today_anny; ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </a><!-- end card -->
                        </div>
                        <div class="col-xl-3 col-md-6 mt-0">
                            <!-- card -->
                            <a class="card h-100"
                                href="employee_report.php?work_anniversary=<?= $datecurrent; ?>&submit=Search">
                                <div class="card-header text-center bg-primary ">
                                    <h5 class="text-white mb-0">Work Anniversary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3">
                                            <img src="img/working-hours.png" class="w-100" alt="">
                                        </div>
                                        <div class="col-9">
                                            <h3 class="mt-2 text-end fw-bold"><?= $work_anny; ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </a><!-- end card -->
                        </div>
                    </div>

                    <!-- CHARTS -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header border-bottom-dashed">Salary Trend</div>
                                <div class="card-body">
                                    <canvas id="salaryChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header border-bottom-dashed">Attendance Distribution</div>
                                <div class="card-body">
                                    <canvas id="attendanceChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header border-bottom-dashed">Department Cost</div>
                                <div class="card-body">
                                    <canvas id="departmentChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ACTIONS -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header border-bottom-dashed">Payroll Processing</div>
                                <div class="card-body d-grid gap-2">
                                    <a href="salary_generate.php" class="btn btn-outline-primary">Generate Salary</a>
                                    <a href="salary_generate_report.php" class="btn btn-outline-secondary">Recalculate
                                        Payroll</a>
                                    <a href="upload_attachment.php" class="btn btn-outline-success">Upload
                                        Attachment</a>
                                    <a href="salary_generate_report.php" class="btn btn-outline-dark">Salary Slip</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header border-bottom-dashed">Statutory Compliance</div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">PF
                                        <strong>₹<?= $total_pf; ?></strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">ESIC
                                        <strong>₹<?= $total_esic; ?></strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">PF Employer
                                        <strong>₹<?= $total_pf_emp; ?></strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">ESIC
                                        Employer<strong>₹<?= $total_esic_emp; ?></strong></li>

                                </ul>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header border-bottom-dashed">Quick Actions</div>
                                <div class="card-body d-grid gap-2">
                                    <a href="employee_master.php" class="btn btn-primary">Add Employee</a>
                                    <a href="employee_wise_attendance.php" class="btn btn-warning">Apply Leave</a>
                                    <a href="employee_wise_attendance.php" class="btn btn-info">Manual Attendance</a>
                                    <a href="employee_report.php" class="btn btn-secondary">View Employee Reports</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- EMPLOYEE TABLE -->

                    <div class="card" id="customerList">
                        <div class="card-header border-bottom-dashed">
                            <div class="row g-4 align-items-center">
                                <div class="col-sm">
                                    <h5 class="card-title mb-0"> Today's Present List<span class="text-danger"></span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-sm table-bordered"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <td>SNo.</td>
                                            <th>Emp Code</th>
                                            <th>Employee Name</th>
                                            <th>Department</th>
                                            <th>In Time</th>
                                            <th>Out Time</th>
                                            <td>Status</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sno = 1;
                                        $res = $obj->executequery("SELECT ae.*, em.first_name, em.last_name,em.emp_code FROM attendance_entry ae LEFT JOIN employee_master em ON ae.emp_id = em.emp_id WHERE ae.unit_id = '$unitid' and ae.attendance_status IN ('Present', 'Incomplete','Half Day') and ae.attendance_date = '$datecurrent' ORDER BY ae.attendance_id DESC");

                                        foreach ($res as $row) {
                                            $department_name = $obj->getvalfield("department_master", "department_name", "department_id='$row[department_id]'");
                                        ?>
                                            <tr>
                                                <td><?= $sno++; ?></td>
                                                <td><?= $row['emp_code']; ?></td>
                                                <td><?= $row['emp_code']; ?>-<?= ucfirst($row['first_name'] ?? ''); ?>
                                                    <?= ucfirst($row['last_name'] ?? ''); ?></td>
                                                <td><?= $department_name; ?></td>
                                                <td>
                                                    <?= !empty($row["intime"]) ? date("h:i A", strtotime($row["intime"])) : "-" ?>
                                                </td>
                                                <td>
                                                    <?= !empty($row["outtime"]) ? date("h:i A", strtotime($row["outtime"])) : "-" ?>
                                                </td>
                                                <td> <span class="badge bg-success"><a
                                                            href="employee_wise_attendance.php?emp_id=<?= $row['emp_id'] ?>&currentYear=<?= $currentYear ?>&currentMonth=<?= $currentMonth ?>&date=<?= $row['attendance_date']; ?>"
                                                            target="_blank" class="text-white">
                                                            <?= $row['attendance_status']; ?>
                                                        </a> </span></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <!-- CHART SCRIPTS -->

    <script>
        function changeHeaderSession(sessionid) {
            location = "dashboard.php?header_session_id=" + sessionid;
        }
        const salaryLabels = <?php echo json_encode($monthLabels); ?>;
        const salaryData = <?php echo json_encode($salaryTrend); ?>;
        new Chart(salaryChart, {
            type: 'line',
            data: {
                labels: salaryLabels,
                datasets: [{
                    data: salaryData,
                    borderColor: '#4f46e5',
                    tension: .4,
                    fill: false
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        new Chart(attendanceChart, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent', 'Leave'],
                datasets: [{
                    data: ['<?= $todayin ?>', '<?= $total_absent ?>', '<?= $today_leave ?>'],
                    backgroundColor: ['#22c55e', '#ef4444', '#facc15']
                }]
            }
        });
        const departmentLabels = <?php echo json_encode($deptLabels); ?>;
        const departmentData = <?php echo json_encode($deptData); ?>;
        new Chart(departmentChart, {
            type: 'bar',
            data: {
                labels: departmentLabels,
                datasets: [{
                    data: departmentData,
                    backgroundColor: '#7c3aed'
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>

</body>

</html>