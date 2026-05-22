<?php
include("appsession.php");
 
$currentYear  = date('Y');
$currentMonth = date('n');

$emp_id     = $_SESSION['emp_id'];
$title = 'Dashboard';



$today      = date('Y-m-d');

$emp_id = $_SESSION['emp_id'];

/* =========================
   🔹 EMPLOYEE DETAILS
========================= */
$empRes = $obj->executequery("
    SELECT 
        em.*,
        dm.department_name,
        dem.designation,
        gm.grade_name
    FROM employee_master as em
    LEFT JOIN department_master dm 
        ON em.department_id = dm.department_id
    LEFT JOIN designation_master dem 
        ON em.designation_id = dem.designation_id
    LEFT JOIN grade_master gm 
        ON em.grade_id = gm.grade_id
    WHERE em.emp_id = '$emp_id'
    LIMIT 1
");

$empData = $empRes[0] ?? [];
$emp_name  = $empData['first_name'] ?? '';
$emp_code  = $empData['emp_code'] ?? '';
$unitid  = $empData['unit_id'] ?? '';
$sessionid  = $empData['sessionid'] ?? '';

$dept      = $empData['department_name'] ?? '';
$desig     = $empData['designation'] ?? '';
$grade     = $empData['grade_name'] ?? '';
$is_esic = $empData['is_esic'];

$total_earning_leave = $obj->getEarningLeave($emp_id, $sessionid);
//$three_month_leave = $obj->getLeave($emp_id, $currentMonth, $currentYear);
$extra_off =$obj->getExtraOffBalance($emp_id, $currentMonth, $currentYear);

$setting_type = ($is_esic == 1) ? 'ESIC' : 'Non ESIC';

$photo = !empty($empData['profile_image'])
    ? "../admin/uploaded/emp_documents/" . $empData['profile_image']
    : 'img/user.jpg';


/* =========================
   🔹 ATTENDANCE SUMMARY
========================= */
$total_days = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

$today_day = date('j');
$late_in_time = $obj->getvalfield(
    "attendance_entry",
    "late_in",
    "emp_id='$emp_id' 
     AND attendance_date = CURDATE()"
);
$early_out_time = $obj->getvalfield(
    "attendance_entry",
    "early_out",
    "emp_id='$emp_id' 
     AND attendance_date = CURDATE()"
);

$todayStatus = $obj->getvalfield(
    "attendance_entry",
    "attendance_status",
    "emp_id='$emp_id' AND attendance_date='$today'"
);

$lastPunch = $obj->executequery("
    SELECT intime, outtime 
    FROM attendance_entry 
    WHERE emp_id='$emp_id'
    ORDER BY attendance_date DESC 
    LIMIT 1
");

$holidays = $obj->executequery("
    SELECT holiday_tittle, date 
    FROM holiday_entry 
    WHERE date >= CURDATE() 
    AND  FIND_IN_SET('$unitid', unit_id)
    ORDER BY date ASC 
    LIMIT 1
");

$last_in  = !empty($lastPunch[0]['intime'])
    ? date("h:i A", strtotime($lastPunch[0]['intime']))
    : '--';

$last_out = !empty($lastPunch[0]['outtime'])
    ? date("h:i A", strtotime($lastPunch[0]['outtime']))
    : '--';
$pendingReq = 0;


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
            WHEN attendance_status IN ('Present','Weekly Leave','Earning Leave','C Off') THEN 1 
            ELSE 0 
        END) AS total_present,

        SUM(CASE 
            WHEN attendance_status IN ('Half Day','Half Weekly Leave','Half Earning Leave','Half C Off') THEN 1 
            ELSE 0 
        END) AS total_half,

        SUM(CASE 
            WHEN attendance_status IN ('Weekly Leave','Earning Leave','C Off') THEN 1
            WHEN attendance_status IN ('Half Weekly Leave','Half Earning Leave','Half C Off') THEN 0.5
            ELSE 0
        END) AS total_leave,
        
     SUM(
    CASE 
        WHEN late_in IS NOT NULL 
        AND late_in != '00:00:00' 
        THEN 1 
        ELSE 0 
    END
) AS late_in

    FROM attendance_entry
    WHERE emp_id = '$emp_id' 
    AND month = '$currentMonth' 
    AND year = '$currentYear'
");

$row = $res[0] ?? [];

$total_present1 = $row['total_present1'] ?? 0;
$total_half1    = $row['total_half1'] ?? 0;
$late_in    = $row['late_in'] ?? 0;

$total_present  = $row['total_present'] ?? 0;
$total_half     = $row['total_half'] ?? 0;
$total_leave     = $row['total_leave'] ?? 0;

$real_total_attandence = $total_present1 + ($total_half1 / 2);
$total_attandence      = $total_present + ($total_half / 2);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>NRVS</title>
    <!-- css links  files -->
    <?php include("inc/css-file.php"); ?>

</head>
<style>
    body {
        margin: 0;
        background: #f1f5f9;
        font-family: 'Segoe UI', sans-serif;
    }

    .holiday {
        background: #6f42c1;
        color: #fff;
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
        background: linear-gradient(135deg, #124069, #124069);
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

    .card.card-red {
        border: 1px solid red;
    }

    .box {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin: auto;
    }

    /* PRESENT */
    .box.present {
        background: #22c55e;
        color: #fff;
    }

    /* HALF DAY */
    .box.half {
        background: #a855f7;
        color: #fff;
    }

    /* ABSENT */
    .box.absent {
        background: #ef4444;
        color: #fff;
    }

    /* INCOMPLETE */
    .box.incomplete {
        border: 2px dashed #f59e0b;
        color: #f59e0b;
        background: #fff7ed;
    }

    /* WEEKLY OFF */
    .box.weekoff {
        background: #6366f1;
        color: #fff;
    }

    /* LEAVE */
    .box.leave {
        background: #14b8a6;
        color: #fff;
    }

    /* C OFF */
    .box.coff {
        background: #0ea5e9;
        color: #fff;
    }

    /* HOLIDAY */
    .box.holiday {
        background: #6f42c1;
        color: #fff;
    }

    /* FUTURE / EMPTY */
    .box.day {
        border: 2px solid #0ea5e9;
        background: transparent;
    }
</style>


<body class="dashboard">
    <section class="top-sec ">
        <?php include("inc/header.php"); ?>
        <div class="container">
            <div class="card border-0 shadow-lg mb-2 today-date-card">
                <div class="d-flex flex-row align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <img src="<?= $photo ?>" class="profile-img">
                        <div class="ms-3">
                            <h5 class="text-blue mb-1"><?= $emp_name ?> 👋</h5>
                            <h6 class="text-dark fw-semibold mb-0">ID: <?= $emp_code ?></h6>
                            <h6 class="text-dark fw-semibold mb-0"><?= $dept ?> / <?= $desig ?></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-lg mb-2 today-date-card">
                <div class="d-flex flex-row align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <h1 class="today-date">
                            <span id="dayNumber"></span><sup id="daySuffix"></sup>
                        </h1>

                        <div class="ms-3">
                            <h5 class="text-blue mb-1" id="dayName"></h5>
                            <h6 class="text-secondary mb-0" id="monthYear"></h6>
                        </div>
                    </div>

                    <!-- <div class="d-flex justify-content-between mb-2">
                        <button class="btn btn-sm btn-primary" id="prevWeek">←</button>
                        <button class="btn btn-sm btn-primary ms-2" id="nextWeek">→</button>
                    </div> -->

                </div>
                <div class="mt-3">
                    <h5 class="text-blue mb-3">This week status</h5>
                    <table class="table table-sm week-status table-borderless mb-0">
                        <tr class="text-center">
                            <th>Mo</th>
                            <th>Tu</th>
                            <th>We</th>
                            <th>Th</th>
                            <th>Fr</th>
                            <th>Sa</th>
                            <th>Su</th>
                        </tr>
                        <tr class="text-center" id="weekRow">

                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-6 pe-1">
                    <div class="card shadow-lg mb-2 border-card-white bg-blue p-1 h-80">
                        <h3 class="text-center"><?= $extra_off['balance']  ?></h3>
                        <h6 class="text-center text-white">Extra Off Balance</h6>
                    </div>
                </div>

                <div class="col-6 ps-1">
                    <div class="card shadow-lg mb-2 border-card-white bg-green p-1 h-80">
                        <h3 class="text-center"><?=  $total_earning_leave ?></h3>
                        <h6 class="text-center text-white">Earn Leave Balance</h6>
                    </div>
                </div>
                <div class="col-6 pe-1">
                    <div class="card shadow-lg mb-2 border-card-white bg-red p-1 h-80">
                        <h3 class="text-center"><?= $todayStatus ?: '----' ?></h3>
                        <h6 class="text-center text-white">Today Status </h6>
                    </div>
                </div>
                <div class="col-6 ps-1">
                    <div class="card shadow-lg mb-2 border-card-white bg-pink justify-content-around p-1 h-80">
                        <?php if (!empty($holidays)) { ?>
                            <?php foreach ($holidays as $h) { ?>
                                <div style="font-size:16px;text-align:center;" class="fw-bold">
                                    <?= date('d M', strtotime($h['date'])) ?> - <?= $h['holiday_tittle'] ?>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <small>No upcoming holidays</small>
                        <?php } ?>

                        <h6 class="text-center">Upcoming Holidays</h6>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-6 pe-1">
                    <div class="card shadow-lg mb-2 border-card-blue bg-light-blue">
                        <h6>IN : <?= $last_in ?></h6>
                        <h6>OUT: <?= $last_out ?></h6>
                        <small>Last Punch</small>
                    </div>
                </div>
                <div class="col-6 ps-1">
                    <div class="card shadow-lg mb-2 border-card-red bg-light-red">
                        <h6>Late In: <?= $late_in_time ?: '--' ?></h6>
                        <h6>Early Out: <?= $early_out_time ?: '--' ?></h6>
                        <small>Today Tracking</small>
                    </div>
                </div>
                <div class="col-12 mt-2 mb-1">
                    <h5>Attendance</h5>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-lg mb-2 today-date-card p-2 bg-darkc-sm">
                        <div class="row">
                            <div class="col-8 ">
                                <small class="fw-bold text-white">Present :</small>
                            </div>
                            <div class="col-4 text-center">
                                <h5 class="mb-0"><?= $total_present1 ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-lg mb-2 today-date-card p-2 bg-darkc-sm">
                        <div class="row">
                            <div class="col-8 ">
                                <small class="fw-bold text-white">Late In:</small>
                            </div>
                            <div class="col-4 text-center">
                                <h5 class="mb-0"><?= $late_in ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-lg mb-2 today-date-card p-2 bg-darkc-sm">
                        <div class="row">
                            <div class="col-8 ">
                                <small class="fw-bold text-white">Half Day:</small>
                            </div>
                            <div class="col-4 text-center">
                                <h5 class="mb-0"><?= $total_half1 ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-lg mb-2 today-date-card p-2 bg-darkc-sm">
                        <div class="row">
                            <div class="col-8 ">
                                <small class="fw-bold text-white">Leave:</small>
                            </div>
                            <div class="col-4 text-center">
                                <h5 class="mb-0"><?= $total_leave ?> </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </section>

    <!-- js script files -->
    <?php include("inc/js-file.php"); ?>
    <script>
        let weekOffset = 0;

        // load current week on page load
        loadWeek(weekOffset);

        function loadWeek(offset) {

            $.ajax({
                url: "week_attendance.php",
                type: "POST",
                data: {
                    week: offset
                },
                success: function(res) {
                    let response = JSON.parse(res);
                    if (response.status === "success") {
                        $("#weekRow").html(response.html);
                        updateDateUI(response.start, offset);
                    }
                }
            });
        }
        $("#prevWeek").click(function() {
            weekOffset--;
            loadWeek(weekOffset);
        });

        // next
        $("#nextWeek").click(function() {
            weekOffset++;
            loadWeek(weekOffset);
        });

        function updateDateUI(startDate, offset) {

            let date;

            if (offset === 0) {
                // current week → show TODAY
                date = new Date();
            } else {
                // previous/next week → show Monday date
                date = new Date(startDate);
            }

            let day = date.getDate();
            let dayName = date.toLocaleDateString('en-US', {
                weekday: 'long'
            });
            let monthYear = date.toLocaleDateString('en-US', {
                month: 'long',
                year: 'numeric'
            });

            // suffix (st, nd, rd, th)
            let suffix = 'th';
            if (day % 10 === 1 && day !== 11) suffix = 'st';
            else if (day % 10 === 2 && day !== 12) suffix = 'nd';
            else if (day % 10 === 3 && day !== 13) suffix = 'rd';

            // set values
            $("#dayNumber").text(day);
            $("#daySuffix").text(suffix);
            $("#dayName").text(dayName);
            $("#monthYear").text(monthYear);
        }
    </script>
</body>

</html>