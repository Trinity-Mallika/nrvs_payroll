<?php
include("appsession.php");

// $curr_year  = date('Y');
// $curr_month = date('n');
$emp_id     = $_SESSION['emp_id'];
$title = 'Dashboard';
// /* ===== DATA ===== */
// $total_days = cal_days_in_month(CAL_GREGORIAN, $curr_month, $curr_year);

// $present_days = $obj->getvalfield(
//     "attendance_entry",
//     "count(*)",
//     "emp_id='$emp_id' AND attendance_status='Present'"
// );

$on_time = $obj->getvalfield(
    "attendance_entry",
    "count(*)",
    "emp_id='$emp_id' AND attendance_status='Present'"
);

$late_in = $obj->getvalfield(
    "attendance_entry",
    "count(*)",
    "emp_id='$emp_id' AND attendance_status='Late'"
);

$early_exit = $obj->getvalfield(
    "attendance_entry",
    "count(*)",
    "emp_id='$emp_id' AND attendance_status='Half Day'"
);

// $working_days = $present_days;
// $absent = max(0, $total_days - $present_days);
$curr_year  = date('Y');
$curr_month = date('n');
$today      = date('Y-m-d');

$emp_id = $_SESSION['emp_id'];

/* =========================
   🔹 EMPLOYEE DETAILS
========================= */
$empData = $obj->select_record("employee_master", ["emp_id" => $emp_id]);

$emp_name  = $empData['first_name'] ?? '';
$emp_code  = $empData['emp_code'] ?? '';
$dept      = $empData['department'] ?? '';
$desig     = $empData['designation'] ?? '';
$photo     = !empty($empData['photo']) ? $empData['photo'] : 'default.png';

/* =========================
   🔹 ATTENDANCE SUMMARY
========================= */
$total_days = cal_days_in_month(CAL_GREGORIAN, $curr_month, $curr_year);

$present_days = $obj->getvalfield(
    "attendance_entry",
    "count(*)",
    "emp_id='$emp_id' 
     AND attendance_status='Present'
     AND MONTH(attendance_date)='$curr_month'
     AND YEAR(attendance_date)='$curr_year'"
);

$late_in = $obj->getvalfield(
    "attendance_entry",
    "count(*)",
    "emp_id='$emp_id' 
     AND attendance_status='Late'
     AND MONTH(attendance_date)='$curr_month'
     AND YEAR(attendance_date)='$curr_year'"
);

$half_day = $obj->getvalfield(
    "attendance_entry",
    "count(*)",
    "emp_id='$emp_id' 
     AND attendance_status='Half Day'
     AND MONTH(attendance_date)='$curr_month'
     AND YEAR(attendance_date)='$curr_year'"
);

$working_days = $present_days;
$absent = max(0, $total_days - ($present_days + $late_in + $half_day));

/* =========================
   🔹 TODAY STATUS
========================= */
$todayStatus = $obj->getvalfield(
    "attendance_entry",
    "attendance_status",
    "emp_id='$emp_id' AND attendance_date='$today'"
);

/* =========================
   🔹 LAST PUNCH
========================= */
$lastPunch = $obj->executequery("
    SELECT intime, outtime 
    FROM attendance_entry 
    WHERE emp_id='$emp_id'
    ORDER BY attendance_date DESC 
    LIMIT 1
");

/* =========================
   🔹 LEAVE BALANCE
========================= */
// $leave = $obj->select_record("leave_balance", ["emp_id" => $emp_id]);

// $cl = $leave['cl'] ?? 0;
// $sl = $leave['sl'] ?? 0;
// $el = $leave['el'] ?? 0;
$cl = "0";
$sl = "0";
$el = "0";
/* =========================
   🔹 UPCOMING HOLIDAYS
========================= */
// $holidays = $obj->executequery("
//     SELECT holiday_name, holiday_date 
//     FROM holiday_master 
//     WHERE holiday_date >= CURDATE()
//     ORDER BY holiday_date ASC 
//     LIMIT 3
// ");

/* =========================
   🔹 PENDING REQUESTS
========================= */
// $pendingReq = $obj->getvalfield(
//     "leave_request",
//     "count(*)",
//     "emp_id='$emp_id' AND status='Pending'"
// );

$pendingReq = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Pass</title>
    <!-- css links  files -->
    <?php include("inc/css-file.php"); ?>

</head>
<style>
    body {
        margin: 0;
        background: #f1f5f9;
        font-family: 'Segoe UI', sans-serif;
    }

    /* HEADER */
    .header {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: white;
        padding: 25px 20px 60px;
        border-radius: 0 0 30px 30px;
    }

    .header h5 {
        margin: 0;
    }

    .header small {
        opacity: 0.8;
    }

    /* MAIN CARD */
    .main-card {
        background: white;
        margin: -40px 15px 15px;
        padding: 20px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    /* CHART */
    .chart-box {
        width: 200px;
        margin: auto;
        position: relative;
    }

    .chart-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .chart-center h2 {
        margin: 0;
        font-weight: 600;
    }

    .chart-center small {
        color: #6b7280;
    }

    /* GRID */
    .stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-top: 20px;
    }

    /* STAT CARD */
    .stat-card {
        border-radius: 16px;
        padding: 16px;
        color: white;
        font-weight: 500;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
    }

    .green {
        background: linear-gradient(135deg, #22c55e, #4ade80);
    }

    .blue {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
    }

    .orange {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
    }

    .red {
        background: linear-gradient(135deg, #ef4444, #f87171);
    }

    /* BUTTON */
    .main-btn {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: white;
        padding: 14px;
        border-radius: 12px;
        text-align: center;
        margin-top: 20px;
        font-weight: 500;
        box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3);
    }

    .dashboard-container {
        padding: 10px 15px;
    }

    /* PROFILE */
    .profile-card {
        display: flex;
        align-items: center;
        gap: 12px;
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: white;
        padding: 15px;
        border-radius: 15px;
    }

    .profile-img {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* GRID */
    .quick-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 12px;
    }

    .quick-card {
        padding: 12px;
        border-radius: 12px;
        color: white;
        text-align: center;
    }

    .green {
        background: #22c55e;
    }

    .blue {
        background: #3b82f6;
    }

    .orange {
        background: #f59e0b;
    }

    .red {
        background: #ef4444;
    }
</style>


<body class="dashboard">
    <section class="top-sec ">
        <?php include("inc/header.php"); ?>

        <div class="dashboard-container mt-5">

            <!-- 🔹 PROFILE -->
            <div class="profile-card">
                <img src="uploaded/employee/<?= $photo ?>" class="profile-img">
                <div>
                    <h5><?= $emp_name ?> 👋</h5>
                    <small>ID: <?= $emp_code ?></small><br>
                    <small><?= $dept ?> / <?= $desig ?></small>
                </div>
            </div>

            <!-- 🔹 QUICK CARDS -->
            <div class="quick-grid">

                <div class="quick-card green">
                    <h4><?= $cl + $sl + $el ?></h4>
                    <small>Leave Balance</small>
                </div>

                <div class="quick-card blue">
                    <h4><?= $todayStatus ?: '--' ?></h4>
                    <small>Today Status</small>
                </div>

                <div class="quick-card orange">
                    <h4><?= $lastPunch[0]['in_time'] ?? '--' ?></h4>
                    <small>Last Punch</small>
                </div>

                <div class="quick-card red">
                    <h4><?= $pendingReq ?></h4>
                    <small>Pending</small>
                </div>

            </div>

        </div>


        <div class="main-card">
            <div class="mt-3 header">



                <h4>WELCOME <br> <?php echo $obj->getvalfield("employee_master", "first_name", "emp_id='$emp_id'"); ?>👋</h4>
                <small><?php echo $obj->getvalfield("employee_master", "emp_code", "emp_id='$emp_id'"); ?></small>

            </div>
            <!-- CHART -->
            <div class="chart-box">
                <canvas id="chart"></canvas>
                <div class="chart-center">
                    <h2><?= $working_days ?></h2>
                    <small>Working Days</small>
                </div>
            </div>

            <!-- STATS -->
            <div class="stats">

                <div class="stat-card green">
                    <h4><?= $on_time ?></h4>
                    <small>On Time</small>
                </div>

                <div class="stat-card blue">
                    <h4><?= $late_in ?></h4>
                    <small>Late In</small>
                </div>

                <div class="stat-card orange">
                    <h4><?= $early_exit ?></h4>
                    <small>Early Exit</small>
                </div>

                <div class="stat-card red">
                    <h4><?= $absent ?></h4>
                    <small>Absent</small>
                </div>

            </div>

            <!-- BUTTON -->
            <div class="main-btn">
                <a href="attendance_details.php">
                    View Attendance Details </a>
            </div>

        </div>

        <script>
            new Chart(document.getElementById("chart"), {
                type: "doughnut",
                data: {
                    datasets: [{
                        data: [<?= $on_time ?>, <?= $late_in ?>, <?= $early_exit ?>, <?= $absent ?>],
                        backgroundColor: [
                            "#22c55e",
                            "#3b82f6",
                            "#f59e0b",
                            "#ef4444"
                        ]
                    }]
                },
                options: {
                    cutout: "75%",
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>

    </section>

    <!-- js script files -->
    <?php include("inc/js-file.php"); ?>
</body>




</html>