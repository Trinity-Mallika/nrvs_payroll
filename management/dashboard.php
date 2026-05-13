<?php include("../adminsession.php");
// print_r($_SESSION);
// die;

$pagename = "dashboard.php";

// if (isset($_GET['header_session_id'])) {
//     $header_session_id = $obj->test_input($_GET['header_session_id']);
// } else {
//     $header_session_id = $sessionid;
// };

$header_session_id = isset($_GET['header_session_id']) ? $_GET['header_session_id'] : $sessionid;
$header_unit_id = isset($_GET['header_unit_id']) ? $_GET['header_unit_id'] : $unitid;

$datecurrent = date('Y-m-d');

$total_emp = $obj->getvalfield("employee_master", "count(*)", "unit_id='$header_unit_id' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE()))");

$todayin = $obj->getvalfield("attendance_entry", "count(*)", "attendance_status IN ('Present','Half Day','Incomplete','Absent') and attendance_date='$datecurrent' and sessionid='$header_session_id' AND unit_id='$header_unit_id'");

$today_leave = $obj->getvalfield("attendance_entry", "count(*)", "attendance_status IN ('Weekly Leave','Earning Leave','C Off','Leave') and attendance_date='$datecurrent' and sessionid='$header_session_id' AND unit_id='$header_unit_id'");


//$today_ab = $obj->getvalfield("attendance_entry", "count(*)", "attendance_status IN ('Absent') and attendance_date='$datecurrent' and sessionid='$header_session_id' AND unit_id='$unitid'");

$total_absent = $total_emp - ($todayin + $today_leave);

$currentMonth = (int) date('m');
if ($currentMonth == 1) {
    $lastMonth = 12;
} else {
    $lastMonth = $currentMonth - 1;
}
$currentYear = date('Y');


$department = $obj->executequery("SELECT * FROM department_master WHERE unit_id='$header_unit_id' ");

$deptLabels = [];
$deptData   = [];

foreach ($department as $row) {
    $deptLabels[] = $row['department_name'];
    $salary_count = $obj->getvalfield("salary_structure", "count(*)", "unit_id='$header_unit_id' and month='$currentMonth' and year='$currentYear' and department_id='$row[department_id]' and sessionid='$header_session_id'");

    $deptData[] = (int)$salary_count;
}

$monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$salaryTrend = [];
for ($m = 1; $m <= 12; $m++) {
    $salary_count = $obj->getvalfield(
        "salary_structure",
        "SUM(total_pay_sal_after_ded)",
        "unit_id='$header_unit_id'
         AND month='$m' AND payment_status!=0
         AND year='$currentYear' and sessionid='$header_session_id'"
    );

    $salaryTrend[] = (int)($salary_count ?? 0);
}
$total_pf = $obj->getvalfield("salary_structure", "SUM(pf_emp)", "unit_id='$header_unit_id' AND sessionid='$header_session_id'");
$total_esic = $obj->getvalfield("salary_structure", "SUM(esic_emp)", "unit_id='$header_unit_id' AND sessionid='$header_session_id'");

$last_month_salary = $obj->getvalfield("salary_structure", "SUM(total_pay_sal_after_ded)", "unit_id='$header_unit_id' AND month='$lastMonth' AND year='$currentYear' and sessionid='$header_session_id' and payment_status!=0") ?? 0;

$last_month_hold_salary = $obj->getvalfield("salary_structure", "SUM(total_pay_sal_after_ded)", "unit_id='$header_unit_id' AND month='$lastMonth' AND year='$currentYear'  and sessionid='$header_session_id' and payment_status=0") ?? 0;

$hold_employee = $obj->getvalfield("salary_structure", "count(emp_id)", "unit_id='$header_unit_id'  and sessionid='$header_session_id' and payment_status=0") ?? 0;

$manual_att = $obj->getvalfield("attendance_entry", "count(emp_id)", "unit_id='$header_unit_id'  and sessionid='$header_session_id' and (entry_type='manual' OR entry_type_out='manual')") ?? 0;




$depart_res = $obj->executequery("
    SELECT 
        dm.department_name,
        COUNT(em.emp_id) AS actual_employees
    FROM employee_master em
    LEFT JOIN department_master dm 
        ON em.department_id = dm.department_id
    where em.sessionid='$header_session_id' and em.unit_id='$header_unit_id'
    GROUP BY em.department_id
    ORDER BY actual_employees DESC
    LIMIT 10
");
$dept_names = [];
$dept_counts = [];

foreach ($depart_res as $row) {
    $dept_names[] = $row['department_name'];
    $dept_counts[] = $row['actual_employees'];
}

$overtime_res = $obj->executequery("
    SELECT 
        em.emp_code,
        em.first_name,
        SEC_TO_TIME(SUM(TIME_TO_SEC(ae.overtime))) AS total_overtime
    FROM attendance_entry ae

    LEFT JOIN employee_master em 
        ON ae.emp_id = em.emp_id

    WHERE em.unit_id='$header_unit_id'  
    AND em.sessionid='$header_session_id'
    AND month = '$currentMonth' 
    AND year = '$currentYear'
    AND TIME_TO_SEC(ae.overtime) > 0

    GROUP BY ae.emp_id
    ORDER BY SUM(TIME_TO_SEC(ae.overtime)) DESC
    LIMIT 10
");

$leave_summary = $obj->executequery("
    SELECT 

    COUNT(DISTINCT CASE 
        WHEN ml.leave_type='earning' AND ml.remining_leave > 0 
        THEN ml.emp_id END) AS earning_emp_count,
 
    COUNT(DISTINCT CASE 
        WHEN ml.leave_type='weekly' AND ml.remining_leave > 0 
        THEN ml.emp_id END) AS weekly_emp_count 
 
  

    FROM emp_monthly_leave ml

    WHERE ml.unit_id='$header_unit_id'
    AND ml.sessionid='$header_session_id'
");
$data = $leave_summary[0];

$earning_count = $data['earning_emp_count'];
$weekly_count  = $data['weekly_emp_count'];
$casual_count  = $data['weekly_emp_count'];



$joinData = $obj->executequery("
    SELECT 
        DATE_FORMAT(date_of_joining, '%Y-%m') as month,
        COUNT(*) as total
    FROM employee_master
    WHERE date_of_joining >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH), '%Y-%m-01') AND unit_id='$header_unit_id'
    GROUP BY month
    ORDER BY month ASC
");

$exitData = $obj->executequery("
    SELECT 
        DATE_FORMAT(resignation_date, '%Y-%m') as month,
        COUNT(*) as total
    FROM employee_exit
    WHERE is_approved = 1
    AND resignation_date >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH), '%Y-%m-01')  AND unit_id='$header_unit_id'
    GROUP BY month
    ORDER BY month ASC
");

$months = [];
$joinArr = [];
$exitArr = [];

for ($i = 5; $i >= 0; $i--) {
    $monthKey = date('Y-m', strtotime("-$i months"));
    $months[] = date('M Y', strtotime($monthKey));

    $joinArr[$monthKey] = 0;
    $exitArr[$monthKey] = 0;
}

// Joining
foreach ($joinData as $row) {
    $joinArr[$row['month']] = $row['total'];
}

// Exit
foreach ($exitData as $row) {
    $exitArr[$row['month']] = $row['total'];
}

$labels = json_encode(array_values($months));
$joinValues = json_encode(array_values($joinArr));
$exitValues = json_encode(array_values($exitArr));
?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="sm" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">


<head>

    <meta charset="utf-8" />
    <title>Dashboard</title>
    <?php include('inc/css.php') ?>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* KPI */

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 18px;
            margin-bottom: 20px;
        }

        .kpi {
            padding: 18px;
            border-radius: 8px;
        }

        .kpi h4 {
            font-size: 13px;
            opacity: .9;
            color: white;
        }

        .kpi h2 {
            font-size: 26px;
            margin-top: 6px;
            color: white;
        }

        .blue {
            background: #0944a5;
        }

        .green {
            background: #09933c;
        }

        .red {
            background: #ab0707;
        }

        .orange {
            background: #c95504;
        }

        .purple {
            background: #23179f;
        }

        .darkblue {
            background: #0f2c7a;
        }

        /* CARD */

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
        }

        .grid2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .grid3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .bottom {
            display: grid;
            grid-template-columns: 1fr 1fr 2fr;
            gap: 20px;
        }

        /* LEAVE */

        .leave-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }

        .overtime-scroll {
            max-height: 300px;
            /* height control karo */
            overflow-y: auto;
            /* vertical scroll */
        }

        canvas {
            max-height: 240px;
        }

        .marquee {
            overflow: hidden;
            height: 200px;
            position: relative;
        }

        .marquee-content {
            display: flex;
            flex-direction: column;
            animation: scrollUp 6s linear infinite;
        }

        @keyframes scrollUp {
            0% {
                transform: translateY(100%);
            }

            100% {
                transform: translateY(-100%);
            }
        }

        .alert-box .alert {
            padding: 10px;
        }

        .card-header {
            padding: 10px;
        }
    </style>

</head>

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
                    <div class="main">

                        <!-- KPI -->

                        <div class="kpi-grid">

                            <div class="kpi blue">
                                <h4>Total Employees</h4>
                                <h2><?= $total_emp ?></h2>
                            </div>

                            <div class="kpi green">
                                <h4>Present Today</h4>
                                <h2><?= $todayin ?></h2>
                            </div>

                            <div class="kpi red">
                                <h4>Absent Today</h4>
                                <h2><?= $total_absent ?></h2>
                            </div>
                            <div class="kpi orange">
                                <h4>On Leave</h4>
                                <h2><?= $today_leave ?></h2>
                            </div>

                            <div class="kpi purple">
                                <h4>Last Month Hold Salary</h4>
                                <h2>₹ <?= $last_month_hold_salary ?></h2>
                            </div>

                            <div class="kpi darkblue">
                                <h4>Last Month Salary Cost</h4>
                                <h2>₹ <?= $last_month_salary ?></h2>
                            </div>

                        </div>

                        <!-- TOP CHARTS -->
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-header border-bottom-dashed">
                                        <h5 class="mb-0"> Attendance Overview</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="attendanceChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-header border-bottom-dashed">
                                        <h5 class="mb-0">Manpower by Department</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="deptChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MIDDLE -->

                        <div class="row">
                            <div class="col-lg-4 col-md-4">
                                <div class="card card-height-100">
                                    <div class="card-header border-bottom-dashed">
                                        <h5 class="mb-0">Salary Cost Trend</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="salaryChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-lg-4 col-md-4">
                                <div class="card card-height-100">
                                    <div class="card-header border-bottom-dashed">
                                        <h5 class="mb-0">Overtime Analysis</h5>
                                    </div>
                                    <div class="card-body overtime-scroll">

                                        <?php foreach ($overtime_res as $row) { ?>
                                            <div class="alert alert-primary alert-dismissible alert-label-icon rounded-label fade show material-shadow mb-1" role="alert">
                                                <i class="ri-time-line label-icon"></i>
                                                <strong>
                                                    <?= $row['first_name']; ?> (<?= $row['emp_code']; ?>)
                                                </strong>
                                                - <?= $row['total_overtime']; ?> hrs
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-lg-4 col-md-4">
                                <div class="card card-height-100">
                                    <div class="card-header border-bottom-dashed">
                                        <h5 class="mb-0">Alerts & Notifications</h5>
                                    </div>
                                    <div class="card-body alert-box">
                                        <div class="marquee">
                                            <div class="marquee-content">
                                                <div class="alert border-0 alert-danger material-shadow" role="alert">
                                                    <strong>⚠ </strong> <?= $hold_employee ?> Salaries on Hold
                                                </div>
                                                <div class="alert border-0 alert-danger material-shadow" role="alert">
                                                    <strong>⚠ </strong> <?= $manual_att ?> Manual Attendance Entries
                                                </div>
                                                <div class="alert border-0 alert-danger material-shadow" role="alert">
                                                    <strong>⚠ </strong> High Overtime Cost
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="card card-height-100">
                                    <div class="card-header border-bottom-dashed">
                                        <h5 class="mb-0">Leave Balance</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                                Casual Leave <span class="badge bg-success"><?= $casual_count ?></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                                Week Off <span class="badge bg-danger"><?= $weekly_count ?></span>
                                                </span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                                Earned Leave <span class="badge bg-secondary"><?= $earning_count ?></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BOTTOM -->
                        <div class="row">

                            <div class="col-lg-3 col-md-3">
                                <div class="card card-height-100">
                                    <div class="card-header border-bottom-dashed">
                                        <h5 class="mb-0">Compliance Summary</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show material-shadow" role="alert">
                                            <i class="ri-money-rupee-circle-line label-icon"></i>Provident Fund <strong>₹ <?= $total_pf ?></strong>
                                        </div>
                                        <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show material-shadow" role="alert">
                                            <i class="ri-money-rupee-circle-line label-icon"></i>ESIC <strong>₹ <?= $total_esic ?> </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="card card-height-100">
                                    <div class="card-header border-bottom-dashed">
                                        <h5 class="mb-0">Graph</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="bottomChart"></canvas>
                                    </div>
                                </div>
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
        /* Attendance */
        new Chart(attendanceChart, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent', 'Leave'],
                datasets: [{
                    data: ['<?= $todayin ?>', '<?= $total_absent ?>', '<?= $today_leave ?>'],
                    backgroundColor: ['#09933c', '#ab0707', '#e6ba0a']
                }]
            }
        });

        /* Department */
        const deptLabels = <?= json_encode($dept_names) ?>;
        const deptData = <?= json_encode($dept_counts) ?>;

        new Chart(deptChart, {
            type: 'bar',
            data: {
                labels: deptLabels,
                datasets: [{
                    data: deptData,
                    backgroundColor: '#1057ca'
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

        /* Bottom */
        // new Chart(bottomChart, {
        //     type: 'bar',
        //     data: {
        //         labels: ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
        //         datasets: [{
        //             data: [12, 19, 3, 5, 2, 3, 7]
        //         }]
        //     }
        // });
    </script>

    <script>
        function changeHeaderData() {
            var sessionid = document.getElementById("header_session_id").value;
            var unitid = document.getElementById("header_unit_id_check").value;

            location = "dashboard.php?header_session_id=" + sessionid + "&header_unit_id=" + unitid;
        }

        const salaryLabels = <?php echo json_encode($monthLabels); ?>;
        const salaryData = <?php echo json_encode($salaryTrend); ?>;

        new Chart(salaryChart, {
            type: 'bar',
            data: {
                labels: salaryLabels,
                datasets: [{
                    label: 'Total Salary',
                    data: salaryData,
                    backgroundColor: '#3b3edf'
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Salary Amount'
                        }
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

    <script>
        const bottomChart = document.getElementById('bottomChart');

        new Chart(bottomChart, {
            type: 'bar',
            data: {
                labels: <?= $labels ?>,
                datasets: [{
                        label: 'Joining',
                        data: <?= $joinValues ?>,
                        backgroundColor: '#0ab39c'
                    },
                    {
                        label: 'Exit',
                        data: <?= $exitValues ?>,
                        backgroundColor: '#f36775'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>

</body>

</html>