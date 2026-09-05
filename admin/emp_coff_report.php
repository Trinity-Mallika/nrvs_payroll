<?php include("../adminsession.php");

$pagename  = "emp_coff_report.php";
$title     = "EMPLOYEE LEAVE DETAILS";
$module    = "Leave Management";
$submodule = "Employee Leave Details";

$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$department_id = isset($_GET['department_id']) ? $_GET['department_id'] : '';
$emp_id = isset($_GET['emp_id']) ? $_GET['emp_id'] : '';
$currentMonth = date('n');

$currentYear = date('Y');
$currentDay = date('d');
$is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$unitid'");
$crit = "";

if ($department_id != '') {
    $crit .= " AND em.department_id = '$department_id'";
}

if ($emp_id != '') {
    $crit .= " AND em.emp_id = '$emp_id'";
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
.table th,
.table td {
    vertical-align: middle;
    text-align: center;
    white-space: nowrap;
    font-size: 12px;
}

.month-header {
    background: #405189 !important;
    color: #fff !important;
}

.sub-header {
    background: #e9edf7 !important;
    font-size: 11px;
    font-weight: 600;
}

.filter-card {
    background: #f8f9fa;
    border-radius: 10px;
}
</style>

<body>

    <?php include('inc/header.php') ?>
    <?php include('inc/sidebar.php') ?>

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <?php include('inc/bredcrum.php') ?>

                <div class="row">
                    <?php if (!isset($_GET['emp_id'])) { ?>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <?= $submodule ?>
                                </h5>
                            </div>
                            <!-- FILTER -->
                            <div class="card-body border-bottom filter-card">

                                <form method="GET">

                                    <div class="row g-3 align-items-end">

                                        <div class="col-md-2">
                                            <label class="form-label">Year</label>
                                            <select name="year" class="form-select form-select-sm">

                                                <?php
                                                    for ($y = date('Y'); $y >= 2020; $y--) {
                                                    ?>
                                                <option value="<?= $y ?>" <?= ($year == $y) ? 'selected' : '' ?>>
                                                    <?= $y ?>
                                                </option>
                                                <?php } ?>

                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Department</label>

                                            <select name="department_id"
                                                class="form-select form-select-sm chosen-select">

                                                <option value="">All Department</option>

                                                <?php
                                                    $dept = $obj->executequery("
                                                        SELECT * 
                                                        FROM department_master
                                                        WHERE unit_id='$unitid'
                                                        ORDER BY department_name ASC
                                                    ");

                                                    foreach ($dept as $d) {
                                                    ?>

                                                <option value="<?= $d['department_id'] ?>"
                                                    <?= ($department_id == $d['department_id']) ? 'selected' : '' ?>>
                                                    <?= $d['department_name'] ?>
                                                </option>

                                                <?php } ?>

                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Employee</label>

                                            <select name="emp_id" class="form-select form-select-sm chosen-select">

                                                <option value="">All Employee</option>

                                                <?php
                                                    $emp =  $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");

                                                    foreach ($emp as $e) {
                                                    ?>

                                                <option value="<?= $e['emp_id'] ?>"
                                                    <?= ($emp_id == $e['emp_id']) ? 'selected' : '' ?>>

                                                    <?= $e['emp_code'] ?> -
                                                    <?= $e['first_name'] ?>
                                                    <?= $e['last_name'] ?>

                                                </option>

                                                <?php } ?>

                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">

                                                <i class="ri-search-line"></i>
                                                Search
                                            </button>
                                        </div>

                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- TABLE -->
                    <?php if (isset($_GET['emp_id'])) { ?>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <a href="<?= $pagename ?>" class="float-end btn btn-primary btn-sm ms-2">Search
                                        Again</a>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="auto-scroll-wrapper">
                                    <div class="table-responsive">

                                        <table id="buttons-datatables1"
                                            class="table table-bordered table-sm align-middle">

                                            <thead>

                                                <tr class="table-primary">

                                                    <th rowspan="2">Emp Code</th>
                                                    <th rowspan="2">Employee Name</th>
                                                    <th rowspan="2">Department</th>
                                                    <th rowspan="2">Opening Leave</th>

                                                    <?php
                                                        $months = [
                                                            1 => 'Jan',
                                                            2 => 'Feb',
                                                            3 => 'Mar',
                                                            4 => 'Apr',
                                                            5 => 'May',
                                                            6 => 'Jun',
                                                            7 => 'Jul',
                                                            8 => 'Aug',
                                                            9 => 'Sep',
                                                            10 => 'Oct',
                                                            11 => 'Nov',
                                                            12 => 'Dec'
                                                        ];

                                                        foreach ($months as $key => $m) {
                                                            // show only till current month
                                                            if ($key > $currentMonth) {
                                                                break;
                                                            }
                                                        ?>
                                                    <th colspan="13" class="month-header">
                                                        <?= $m ?>
                                                    </th>
                                                    <?php } ?>

                                                </tr>

                                                <tr class="sub-header">

                                                    <?php
                                                        for ($i = 1; $i <= $currentMonth; $i++) {
                                                        ?>

                                                    <th>Att.</th>
                                                    <th>WO</th>
                                                    <th>Used WO</th>
                                                    <th>Rem WO</th>

                                                    <th>Earn</th>
                                                    <th>Used EL</th>
                                                    <th>Rem EL</th>

                                                    <th>Extra Off</th>
                                                    <th>Used EO</th>
                                                    <th>Rem EO</th>

                                                    <!-- <th>Used Leave</th>
                                                    <th>Rem Leave</th> -->

                                                    <th>TPD</th>

                                                    <?php } ?>

                                                </tr>

                                            </thead>

                                            <?php

                                                $sql = "
                                                    SELECT 
                                                        em.emp_id,
                                                        em.emp_code,
                                                        em.first_name,
                                                        em.last_name,
                                                        em.allow_weekly_off,
                                                        em.is_esic,
                                                        dm.department_name,
                                                        dm.c_off_check

                                                    FROM employee_master em

                                                    LEFT JOIN department_master dm
                                                        ON dm.department_id = em.department_id

                                                    WHERE em.unit_id='$unitid'
                                                    $crit

                                                    ORDER BY em.emp_code ASC
                                                    ";

                                                $employees = $obj->executequery($sql);


                                                // ======================
                                                // FETCH OPENING LEAVE
                                                // ======================

                                                $opening_leave_data = [];

                                                $resOpening = $obj->executequery("
                                                        SELECT 
                                                            emp_id,
                                                            COALESCE(SUM(opening_leave),0) as opening_leave

                                                        FROM emp_leave_allotment

                                                        WHERE sessionid='$sessionid'

                                                        GROUP BY emp_id
                                                    ");

                                                foreach ($resOpening as $r) {
                                                    $opening_leave_data[$r['emp_id']] = $r['opening_leave'];
                                                }


                                                // ======================
                                                // FETCH ATTENDANCE DATA
                                                // ======================

                                                $attendanceData = [];

                                                $resAttendance = $obj->executequery("
                                                        SELECT 

                                                            emp_id,
                                                            month,
                                                            year,

                                                            SUM(CASE 
                                                                WHEN attendance_status = 'Present' THEN 1 
                                                                ELSE 0 
                                                            END) AS total_present1,

                                                            SUM(CASE 
                                                                WHEN attendance_status = 'Half Day' THEN 1 
                                                                ELSE 0 
                                                            END) AS total_half1,

                                                            SUM(CASE 
                                                                WHEN attendance_status IN 
                                                                ('Present','Weekly Leave','Earning Leave','C Off','Extra Off','Leave')
                                                                THEN 1 ELSE 0 
                                                            END) AS total_present,

                                                            SUM(CASE 
                                                                WHEN attendance_status IN 
                                                                ('Half Day','Half Weekly Leave','Half Earning Leave',
                                                                'Half C Off','Half Extra Off','Half Leave')
                                                                THEN 1 ELSE 0 
                                                            END) AS total_half,

                                                            COALESCE(SUM(
                                                                CASE 
                                                                    WHEN attendance_status = 'Extra Off' THEN 1
                                                                    WHEN attendance_status = 'Half Extra Off' THEN 0.5
                                                                    ELSE 0
                                                                END
                                                            ),0) AS used_extra_off,
                                                            COALESCE(SUM(
                                                                CASE 
                                                                    WHEN attendance_status = 'Leave' THEN 1
                                                                    WHEN attendance_status = 'Half Leave' THEN 0.5
                                                                    ELSE 0
                                                                END
                                                            ),0) AS used_opening_leave

                                                        FROM attendance_entry

                                                        WHERE year='$year'
                                                        AND unit_id='$unitid'

                                                        GROUP BY emp_id, month, year
                                                    ");

                                                foreach ($resAttendance as $r) {

                                                    $attendanceData[$r['emp_id']][$r['month']] = $r;
                                                }


                                                // ======================
                                                // FETCH EXTRA OFF
                                                // ======================

                                                $extraOffData = [];

                                                $resExtra = $obj->executequery("
                                                        SELECT 
                                                            emp_id,
                                                            month,
                                                            year,
                                                            COALESCE(SUM(total_leave),0) as total_extra_off
                                                        FROM emp_monthly_leave

                                                        WHERE leave_type='eoff'
                                                        AND year='$year'

                                                        GROUP BY emp_id, month, year
                                                    ");
                                                foreach ($resExtra as $r) {
                                                    $extraOffData[$r['emp_id']][$r['month']] = $r['total_extra_off'];
                                                }
                                                ?>
                                            <tbody>
                                                <?php foreach ($employees as $row) {
                                                        $emp_id = $row['emp_id'];
                                                        $opening_leave = $opening_leave_data[$emp_id] ?? 0;
                                                    ?>
                                                <tr>

                                                    <td><?= $row['emp_code'] ?></td>

                                                    <td>
                                                        <?= $row['first_name'] ?>
                                                        <?= $row['last_name'] ?>
                                                    </td>

                                                    <td><?= $row['department_name'] ?></td>

                                                    <td><?= number_format($opening_leave, 1) ?></td>

                                                    <?php

                                                            for ($m = 1; $m <= $currentMonth; $m++) {
                                                                $m = (int)$m;

                                                                $att = $attendanceData[$emp_id][$m] ?? [];

                                                                $total_present1 = $att['total_present1'] ?? 0;
                                                                $total_half1    = $att['total_half1'] ?? 0;

                                                                $total_present  = $att['total_present'] ?? 0;
                                                                $total_half     = $att['total_half'] ?? 0;

                                                                $used_extra_off = $att['used_extra_off'] ?? 0;
                                                                // $used_opening_leave = $att['used_opening_leave'] ?? 0;
                                                                // $rem_opening_leave = $obj->get_opening_leave_balance($emp_id, $sessionid, $m, $year);

                                                                $real_total_attandence = $total_present1 + ($total_half1 / 2);

                                                                $total_attandence = $total_present + ($total_half / 2);

                                                                $allow_weekly_off = $row['allow_weekly_off'];

                                                                $is_esic = $row['is_esic'];

                                                                $setting_type = ($is_esic == 1) ? 'ESIC' : 'Non ESIC';

                                                                // WEEK OFF
                                                                $week_off = $obj->totalWeeklyLeave(
                                                                    $unitid,
                                                                    $real_total_attandence,
                                                                    $emp_id,$m,$year
                                                                );

                                                                // EARN LEAVE
                                                                $earn_leave_present = $real_total_attandence + $week_off;

                                                                $earn_leave = $obj->getTotalLeaveByWorkingDays(
                                                                    $setting_type,
                                                                    $earn_leave_present,
                                                                    $unitid
                                                                );

                                                                // EFFECTIVE DAYS
                                                                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $m, $year);

                                                                if ($m == $currentMonth && $year == $currentYear) {
                                                                    $effectiveDays = $currentDay;
                                                                } else {
                                                                    $effectiveDays = $daysInMonth;
                                                                }

                                                                // CALCULATE
                                                                $result = $obj->calculateLeaveUsage(
                                                                    $effectiveDays,
                                                                    $total_attandence,
                                                                    $week_off,
                                                                    $earn_leave,
                                                                    $row['c_off_check'],
                                                                    $is_all_leave_add
                                                                );

                                                                $used_weekly_leave = $result['used_weekly'];

                                                                $used_monthly_leave = $result['used_monthly'];

                                                                $total_payable_days = $result['total_working_days'];

                                                                $rem_wo = $week_off - $used_weekly_leave;

                                                                $rem_el = $earn_leave - $used_monthly_leave;

                                                                // EXTRA OFF
                                                                $total_extra_off = $extraOffData[$emp_id][$m] ?? 0;

                                                                $balance = $total_extra_off - $used_extra_off;
                                                            ?>

                                                    <td><?= $total_attandence ?></td>

                                                    <td><?= $week_off ?></td>

                                                    <td><?= $used_weekly_leave ?></td>

                                                    <td><?= $rem_wo ?></td>

                                                    <td><?= $earn_leave ?></td>

                                                    <td><?= $used_monthly_leave ?></td>

                                                    <td><?= $rem_el ?></td>

                                                    <td><?= $total_extra_off ?></td>

                                                    <td><?= $used_extra_off ?></td>

                                                    <td><?= $balance ?></td>

                                                    <!-- <td>< $used_opening_leave ?></td>
                                                    <td><$rem_opening_leave ?></td> -->

                                                    <td><?= $total_payable_days ?></td>

                                                    <?php } ?>

                                                </tr>

                                                <?php } ?>

                                            </tbody>

                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>
    </div>

    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <script>
    $(document).ready(function() {
        // $('#example1').DataTable();
        $(".chosen-select").select2({
            width: '100%',
            search_contains: true
        });

    });
    </script>

</body>

</html>