<?php include("../adminsession.php");
$title = "Month Wise Attendance Report";// purana sahi wala hai
$pagename = "month_wise_attendance_report.php";
$module = "Search Attendance";
$submodule = "Month Wise Attendance List";
$btn_name = "Search";
$keyvalue = 0;
$tblname = "attendance_entry";
$tblpkey = "attendance_id";

$crit2 = " and 1=1";

if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit2 .= " and e.department_id = '$department_id'";
    }
    $department_name = $obj->getvalfield(
        "department_master",
        "department_name",
        "department_id='$department_id'"
    );
} else {
    $department_id = "";
    $department_name = "All";
};

if (isset($_GET['action'])) {
    $action = addslashes(trim($_GET['action']));
} else {
    $action = "";
}

$month = date('m');
$year = date('Y');
$year_month = "";
if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = $obj->test_input($_GET['month']);

    $year = $obj->test_input($_GET['year']);
    $month_name = date("F", mktime(0, 0, 0, $_GET['month'], 10));
} else {
    $month_name = '';
}
$lastDateOfMonth = date("Y-m-t", strtotime("$year-$month-01"));
$get_days = $obj->getDaysArray($month, $year);
$length = count($get_days);

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
    $showFields = [2, 3, 7, 8, 9];
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


if (isset($_REQUEST['ajax_emp_shift_hrs'])) {
    $ajax_emp_shift_hrs = $_REQUEST['ajax_emp_shift_hrs'];
    $empp_shift_id = $_REQUEST['empp_shift_id'] ?? 0;

    $options = "<option value=''>Please Select</option>";
    // die;
    $selected = "";
    if ($ajax_emp_shift_hrs != "" || $ajax_emp_shift_hrs > 0) {
        $res = $obj->executequery("Select * from shift_master where unit_id='$unitid' AND HOUR(working_hour) = '$ajax_emp_shift_hrs' order by shift_id asc");

        foreach ($res as $row) {
            $selected = ($empp_shift_id == $row['shift_id']) ? 'selected' : '';
            $options .= "<option value='" . $row['shift_id'] . "' $selected>" . $row['shift_name'] . " / " . $row['working_hour'] . " Hrs" . "</option>";
        }
    }

    echo $options;
    die;
}

?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

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
                <div class="row">
                    <div class="col-lg-12">
                        <fieldset class="mt-2">
                            <?php if (!isset($_GET['search'])) { ?>
                                <form action="<?php echo $pagename; ?>" method="get">
                                    <div class="card">
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
                                            <div class="row">
                                                <div class="col-lg-3 mb-3">
                                                    <label for="department_id" class="form-label">Department Name<span class="text-danger fw-bold"></span></label>
                                                    <select class="form-select chosen-select" name="department_id" id="department_id">
                                                        <option value="">All</option>
                                                        <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_name asc");
                                                        foreach ($res as $key) {
                                                            echo "<option value='" . $key['department_id'] . "'>" . $key['department_name'] . "</option>";
                                                        } ?>
                                                    </select>
                                                    <script>
                                                        document.getElementById('department_id').value = '<?= $department_id; ?>';
                                                    </script>
                                                </div>
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
                                                <div class="col-md-3 md-2">
                                                    <strong><label for="Month">Month<span class="text-danger fw-bold">*</span></label></strong></br>
                                                    <select name="month" class="chosen-select form-control form-control" id="month">
                                                        <option value="">--Select Month--</option>
                                                        <?php for ($iM = 1; $iM <= 12; $iM++) {
                                                        ?>
                                                            <option value="<?php echo str_pad($iM, 2, '0', STR_PAD_LEFT); ?>"><?php echo date("F", strtotime(str_pad($iM, 2, '0', STR_PAD_LEFT) . "/12/10")); ?></option>

                                                        <?php
                                                        } ?>
                                                    </select>
                                                    <script>
                                                        document.getElementById('month').value = '<?php echo $month; ?>';
                                                    </script>
                                                </div>
                                                <div class="col-md-3 md-2">
                                                    <strong><label for="Fields">Fields<span class="text-danger fw-bold"></span></label></strong>
                                                    <select id="show_field" class="form-control" multiple>
                                                        <!-- <option value="1">Mobile Number</option> -->
                                                        <option value="2">Emp Code</option>
                                                        <option value="3">Emp Name</option>
                                                        <option value="4">Aadhaar No</option>
                                                        <option value="5">Present Salary</option>
                                                        <option value="6">Grade</option>
                                                        <option value="7">Department</option>
                                                        <option value="8">Designation</option>
                                                        <option value="9">Date of Joining</option>
                                                        <option value="10">Job Location</option>
                                                        <option value="11">Shift Hours</option>
                                                    </select>
                                                </div>
                                                <input type="hidden" name="show_field_encoded" id="show_field_encoded">

                                                <div class="col-md-3 mt-4 ">
                                                    <input type="submit" class="btn btn-primary add-btn" onclick="return checkinputmaster('year,month')" name="search" value="Search">
                                                    <a href="<?php echo $pagename; ?>" class="btn btn-danger" name="reset" id="reset">Reset</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <?php } ?>
                        </fieldset>
                    </div>
                </div>
              <?php if (isset($_GET['search'])) {   ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-1">
            <div class="card-header border-bottom-dashed" style="margin-bottom: 0px; padding-bottom: 0px;">
                <div class="row g-4 align-items-center">
                    <div class="col-sm" style="margin-top: 8px;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h5 class="card-title mb-0">
                                <?= $submodule; ?>
                            </h5>

                            <div class="ms-2 card-title mb-0">
                                <b>
                                    <?php if (!empty($_GET['year'])) { ?>
                                        Year: <?= $_GET['year']; ?>
                                    <?php } ?>

                                    <?php if (!empty($month_name)) { ?>
                                        | Month: <?= $month_name; ?>
                                    <?php } ?>

                                    <?php if (!empty($department_name)) { ?>
                                        | Dept: <?= $department_name; ?>
                                    <?php } ?>
                                </b>
                            </div>

                            <div>
                                <a href="<?php echo $pagename; ?>" class="btn btn-sm btn-primary">
                                    Search Again
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">

<?php

/* =========================================================
   EMPLOYEES
========================================================= */

$employees = $obj->executequery("
    SELECT 
        e.emp_id,
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
        ON s.working_hour = e.shift_id 

    WHERE e.unit_id = '$unitid'
    AND e.date_of_joining <= '$lastDateOfMonth'
    AND (
        e.resign_status != '1'
        OR (
            e.resign_status = '1'
            AND e.last_working_date >= CURDATE()
        )
    )
    $crit2

    GROUP BY e.emp_id
    ORDER BY e.emp_code
");

if (empty($employees)) {
    $employees = [];
}

$empIds = array_column($employees, 'emp_id');
$empIdsStr = implode(',', $empIds);

$fromDate = "$year-$month-01";
$toDate   = date("Y-m-t", strtotime($fromDate));

/* =========================================================
   ATTENDANCE ENTRY
========================================================= */

$attendanceRows = $obj->executequery("
    SELECT 
        emp_id,
        attendance_date,
        attendance_status,
        intime,
        in_remark,
        shift_id

    FROM attendance_entry

    WHERE emp_id IN ($empIdsStr)
    AND attendance_date BETWEEN '$fromDate' AND '$toDate'
    AND unit_id='$unitid'
");

$attendanceMap = [];

foreach ($attendanceRows as $row) {

    $attendanceMap[$row['emp_id']][$row['attendance_date']] = $row;
}

/* =========================================================
   SUMMARY
========================================================= */

$summaryRows = $obj->executequery("
    SELECT emp_id,

        SUM(attendance_status='Present') AS present,
        SUM(attendance_status='Half Day') AS halfday,
        SUM(attendance_status='Weekly Leave') AS leavecnt,
        SUM(attendance_status='Earning Leave') AS leavecnt2,
        SUM(attendance_status='Half Earning Leave') AS halfearn,
        SUM(attendance_status='Half Weekly Leave') AS halfweek,
        SUM(attendance_status='C Off') AS coff,
        SUM(attendance_status='Half C Off') AS halfcoff

    FROM attendance_entry

    WHERE emp_id IN ($empIdsStr)
    AND attendance_date BETWEEN '$fromDate' AND '$toDate'
    AND unit_id='$unitid'

    GROUP BY emp_id
");

$summary = [];

foreach ($summaryRows as $row) {

    $summary[$row['emp_id']] = $row;
}

/* =========================================================
   HOLIDAYS
========================================================= */

$holidayRows = $obj->executequery("
    SELECT date
    FROM holiday_entry
    WHERE FIND_IN_SET('$unitid', unit_id)
    AND date BETWEEN '$fromDate' AND '$toDate'
");

$holidays = [];

foreach ($holidayRows as $h) {

    $holidays[$h['date']] = true;
}

/* =========================================================
   SALARY GENERATED
========================================================= */

$salaryRows = $obj->executequery("
    SELECT emp_id, COUNT(*) cnt
    FROM salary_structure
    WHERE emp_id IN ($empIdsStr)
    AND month='$month'
    AND year='$year'
    AND unit_id='$unitid'
    GROUP BY emp_id
");

$salaryGenerated = [];

foreach ($salaryRows as $s) {

    $salaryGenerated[$s['emp_id']] = $s['cnt'];
}

/* =========================================================
   MISS PUNCH LOGIC
========================================================= */

$missPunchMap = [];

if (!empty($empIdsStr)) {

    $punchData = $obj->executequery("
        SELECT 
            l.emp_id,
            l.attendance_date,
            l.attendance_stamp,
            l.in_status,
            ae.shift_id,
            sm.in_time

        FROM attendance_log l

        LEFT JOIN attendance_entry ae
            ON ae.emp_id = l.emp_id
            AND ae.attendance_date = l.attendance_date

        LEFT JOIN shift_master sm
            ON sm.working_hour = ae.shift_id

        WHERE l.emp_id IN ($empIdsStr)
        AND l.attendance_date BETWEEN '$fromDate' AND '$toDate'
        AND l.unit_id='$unitid'

        ORDER BY l.emp_id, l.attendance_stamp
    ");

    $punchMap = [];

    foreach ($punchData as $row) {

        $empId   = $row['emp_id'];
        $date    = $row['attendance_date'];
        $status  = strtoupper($row['in_status']);

        $stamp   = strtotime($row['attendance_stamp']);

        $shiftIn = !empty($row['in_time'])
            ? $row['in_time']
            : '06:00:00';

        if (!isset($punchMap[$empId])) {
            $punchMap[$empId] = [];
        }

        /* =========================
           IN ENTRY
        ========================= */

        if ($status == 'IN') {

            $punchMap[$empId][$date][] = [
                'in_stamp' => $stamp,
                'out_stamp' => ''
            ];
        }

        /* =========================
           OUT ENTRY
        ========================= */

        if ($status == 'OUT') {

            $matched = false;

            foreach (array_reverse($punchMap[$empId], true) as $pDate => $entries) {

                foreach (array_reverse($entries, true) as $idx => $entry) {

                    if (
                        !empty($entry['in_stamp']) &&
                        empty($entry['out_stamp'])
                    ) {

                        $inStamp = $entry['in_stamp'];

                        // next day shift in + 4 hr
                        $allowedOutTime = strtotime(
                            date('Y-m-d', strtotime($pDate . ' +1 day'))
                            . ' ' .
                            $shiftIn
                        ) + (4 * 3600);

                        if (
                            $stamp > $inStamp &&
                            $stamp <= $allowedOutTime
                        ) {

                            // assign previous day OUT
                            $punchMap[$empId][$pDate][$idx]['out_stamp'] = $stamp;

                            $matched = true;

                            break 2;
                        }
                    }
                }
            }

            // unmatched OUT ignored
        }
    }

    /* =========================================================
       FINAL MISS PUNCH CHECK
       ONLY:
       IN EXISTS
       BUT OUT NOT FOUND
    ========================================================= */

    foreach ($punchMap as $empId => $dates) {

        foreach ($dates as $pDate => $entries) {

            foreach ($entries as $entry) {

                if (
                    !empty($entry['in_stamp']) &&
                    empty($entry['out_stamp'])
                ) {

                    $missPunchMap[$empId][$pDate] = true;
                }
            }
        }
    }
}

?>

            <!-- floating scrollbar -->
            <div class="auto-scroll-wrapper">

                <div class="table-responsive">

                    <table id="buttons-datatables"
                        class="table table-sm table-bordered table-hover align-middle display">

                        <thead class="table-light">

                            <tr>

                                <th>S.No.</th>

                                <?php
                                foreach ($showFields as $fid) {

                                    if (!isset($fieldMap[$fid])) continue;

                                    echo "<th>{$fieldMap[$fid]['label']}</th>";
                                }
                                ?>

                                <th>Present <br> Days</th>
                                <th>Total <br> Week <br> Off</th>
                                <th>Total <br>Payable <br>Day</th>
                                <th>Total <br> Earn <br> Leave</th>
                                <th>C-OFF</th>
                                <th>Punch <br> All</th>

                                <?php for ($i = 1; $i <= $length; $i++) { ?>
                                    <th class="text-center">
                                        D<?= $i; ?>
                                    </th>
                                <?php } ?>

                            </tr>

                        </thead>

                        <tbody>

<?php

$slno = 1;

$currentDate = date("Y-m-d");

$totalPresentDays = 0;
$totalWeekOff = 0;
$totalEarnLEave = 0;

$chkedit = $obj->check_editBtn($pagename, $loginid);

foreach ($employees as $emp) {

    $empId = $emp['emp_id'];

    $allow_weekly_off = $emp['allow_weekly_off'];

    $is_esic = $emp['is_esic'];

    $setting_type = ($is_esic == 1)
        ? 'ESIC'
        : 'Non ESIC';

    $sum = $summary[$empId] ?? [
        'present' => 0,
        'halfday' => 0,
        'leavecnt' => 0,
        'leavecnt2' => 0,
        'halfearn' => 0,
        'halfweek' => 0,
        'coff' => 0,
        'halfcoff' => 0
    ];

    $totalAttendance =
        $sum['present']
        + ($sum['halfday'] / 2)
        + $sum['leavecnt']
        + $sum['leavecnt2']
        + ($sum['halfearn'] / 2)
        + ($sum['halfweek'] / 2)
        + $sum['coff']
        + ($sum['halfcoff'] / 2);

    $real_total_att =
        $sum['present']
        + ($sum['halfday'] / 2);

    $salaryCount = $salaryGenerated[$empId] ?? 0;

    $totalPresentDays += $totalAttendance;

    $monthly_leave = $obj->getTotalLeaveByWorkingDays(
        $setting_type,
        $real_total_att,
        $unitid
    );

    $week_leave = $obj->totalWeeklyLeave(
        $unitid,
        $real_total_att,
        $allow_weekly_off
    );

    $totalWeekOff += $week_leave;
    $totalEarnLEave += $monthly_leave;

    $tpd = $totalAttendance + $week_leave;

    $extra_coff = 0;

    if ($tpd > $length) {

        $extra_coff = $tpd - $length;
        $tpd = $length;
    }

    echo "<tr>";

    echo "<td>" . $slno++ . "</td>";

    foreach ($showFields as $fid) {

        if (!isset($fieldMap[$fid])) continue;

        $key = $fieldMap[$fid]['key'];

        $value = $emp[$key] ?? '-';

        if ($fid == 9 && !empty($value)) {

            $value = $obj->dateformatindia($value);
        }

        echo "<td>{$value}</td>";
    }

    echo "<td class='text-center fw-bold' style='background:#f3e8ff'>" . number_format($totalAttendance, 1) . "</td>";

    echo "<td class='text-center fw-bold text-dark' style='background:#fff3cd'>" . number_format($week_leave, 1) . "</td>";

    echo "<td class='text-center fw-bold text-dark' style='background:#98defa'>" . number_format($tpd, 1) . "</td>";

    echo "<td class='text-center fw-bold text-dark' style='background:#e7f1ff'>" . number_format($monthly_leave, 1) . "</td>";

    echo "<td class='text-center fw-bold text-danger' style='background:#ffe5e5'>" . number_format($extra_coff, 1) . "</td>";

    if ($chkedit == 1) {

        echo "<td class='text-center' style='cursor:pointer'
            onclick=\"add_all_att('{$emp['shift_id']}','','{$month}','{$year}','{$empId}','{$salaryCount}')\">
            <i class='ri-add-line text-primary'></i>
        </td>";

    } else {

        echo "<td class='text-center'>
            <i class='ri-forbid-2-line text-danger'></i>
        </td>";
    }

    for ($d = 1; $d <= $length; $d++) {

        $date = sprintf('%s-%s-%02d', $year, $month, $d);

        if ($date > $currentDate) {

            echo "<td style='background:#ddd;text-align:center'>-</td>";

            continue;
        }

        $row = $attendanceMap[$empId][$date] ?? null;

        $status = $row['attendance_status'] ?? 'A';

        $intime = $row['intime'] ?? '';

        $remark = $row['in_remark'] ?? '';

        $shift = $row['shift_id'] ?? $emp['shift_id'];

        $isHoliday = isset($holidays[$date]);

        switch ($status) {

            case 'Present':
                $txt = 'P';
                $bg = 'rgb(173,233,179)';
                break;

            case 'Half Day':
                $txt = 'HD';
                $bg = 'rgb(255,246,163)';
                break;

            case 'Incomplete':
                $txt = 'I';
                $bg = 'rgb(233,61,61)';
                break;

            case 'Weekly Leave':
                $txt = 'WL';
                $bg = 'rgb(229,204,255)';
                break;

            case 'Earning Leave':
                $txt = 'L';
                $bg = 'rgb(121,170,248)';
                break;

            case 'Half Earning Leave':
                $txt = 'HL';
                $bg = 'rgb(121,246,248)';
                break;

            case 'Half Weekly Leave':
                $txt = 'HW';
                $bg = 'rgb(226,237,109)';
                break;

            case 'C Off':
                $txt = 'C';
                $bg = 'rgb(121,246,248)';
                break;

            case 'Half C Off':
                $txt = 'HC';
                $bg = 'rgb(226,237,109)';
                break;

            default:

                // ONLY MISS PUNCH CHECK
                if (isset($missPunchMap[$empId][$date])) {

                    $txt = 'M';
                    $bg  = '#f7b1f2';

                } else {

                    $txt = $isHoliday ? 'PL' : 'A';

                    $bg = $isHoliday
                        ? 'rgb(180,210,255)'
                        : 'rgb(251,175,175)';
                }
        }

        echo "<td style='background:$bg;text-align:center;padding:0'
                id='cell_{$empId}_{$date}'>

                <span
                    style='display:block;padding:8px;cursor:pointer'

                    onclick=\"openPunchModal(
                        '$date',
                        '$intime',
                        '$remark',
                        '{$emp['shift_id']}',
                        '$shift',
                        '$month',
                        '$year',
                        '$empId',
                        '$salaryCount'
                    )\">

                    <b>$txt</b>

                </span>

            </td>";
    }

    echo "</tr>";
}
?>

                        </tbody>

                        <tfoot>

                            <tr style="background:#e9ecef;font-weight:bold">

                                <td colspan="<?php echo count($showFields) + 1; ?>"
                                    class="text-end">

                                    Total

                                </td>

                                <td class="text-center">
                                    <?= number_format($totalPresentDays, 1); ?>
                                </td>

                                <td class="text-center">
                                    <?= number_format($totalWeekOff, 1); ?>
                                </td>

                                <td class="text-center">
                                    <?= number_format($totalEarnLEave, 1); ?>
                                </td>

                                <td></td>

                                <td colspan="<?= $length ?>"></td>

                            </tr>

                        </tfoot>

                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>
        </div>
        <!-- Content close-->
    </div>
    </div>
    <div class="modal fade" id="salaryGeneratedModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 rounded-4 shadow">

                <!-- Header -->
                <div class="modal-header border-0 justify-content-end pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body text-center px-4 pb-4">

                    <!-- Icon -->
                    <div class="mb-3">
                        <i class="ri-error-warning-fill fs-1 text-danger"></i>
                    </div>

                    <!-- Message -->
                    <h6 class="fw-bold text-danger mb-2">
                        Salary Already Generated
                    </h6>

                    <p class="small mb-3">
                        Salary for this month has already been generated.
                        Please delete the salary first before modifying attendance.
                    </p>

                    <!-- Action Button -->
                    <a href="#" id="salaryReportLink"
                        class="btn btn-primary btn-sm px-3">
                        <i class="ri-money-rupee-circle-line me-1"></i>
                        View Salary Report
                    </a>

                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 pt-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-4"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Attendance Punch &nbsp;&nbsp;<a id="show_att_details" class="float-end" target="_blank" title="View">
                            <i class="ri-eye-line align-bottom text-primary fs-4"></i>
                        </a></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr class="mb-0 mt-2">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-12 mb-2">
                            <label for="">Attandance</label>
                            <select name="punch_status" id="punch_status" class="form-select form-select-sm">
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="first_half">Half Day (1st Half)</option>
                                <option value="second_half">Half Day (2nd Half)</option>
                                <option value="weekly_leave">Weekly Leave</option>

                                <option value="earn_leave">Earn Leave</option>
                                <option value="half_weekly_leave">Half Weekly Leave</option>
                                <option value="half_earn_leave">Half Earn Leave</option>
                                <option value="c_off">C-Off</option>
                                <option value="half_c_off">Half C-Off</option>
                            </select>
                        </div>
                        <div class="col-lg-12 " id="punchShiftBox">
                            <label for="punch_att_shift_id" class="form-label ">Shift<span
                                    class="text-danger fw-bold"> </span></label>
                            <select class="form-select form-select-sm chosen-select"
                                name="punch_att_shift_id" id="punch_att_shift_id">
                                <option value="">Select</option>

                            </select>
                        </div>
                        <input type="hidden" id="punch_time">
                        <input type="hidden" id="punch_attdate">
                        <input type="hidden" id="current_month">
                        <input type="hidden" id="current_year">
                        <input type="hidden" id="employee_id">
                        <div class="col-lg-12 col-12">
                            <label for="">Remark</label>
                            <textarea name="punching_remark" id="punching_remark" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <hr class="mb-0 mt-2">
                <?php
                $chkedit = $obj->check_editBtn($pagename, $loginid);
                if ($chkedit == 1) { ?>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="savebutton" onclick="savePunch()">Punch</button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AllAttendenceModal" tabindex="-1" aria-labelledby="AllAttendenceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="AllAttendenceModalLabel">Attendance Punch &nbsp;&nbsp;<a id="show_attAll_details" class="float-end" target="_blank" title="View">
                            <i class="ri-eye-line align-bottom text-primary fs-4"></i>
                        </a></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr class="mb-0 mt-2">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-12 mb-2">
                            <label for="">Attandance</label>
                            <select name="punch_all_status" id="punch_all_status" class="form-select form-select-sm">
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="first_half">Half Day (1st Half)</option>
                                <option value="second_half">Half Day (2nd Half)</option>
                                <option value="weekly_leave">Weekly Leave</option>
                                <option value="earn_leave">Earn Leave</option>
                                <option value="half_weekly_leave">Half Weekly Leave</option>
                                <option value="half_earn_leave">Half Earn Leave</option>
                                <option value="c_off">C-Off</option>
                                <option value="half_c_off">Half C-Off</option>
                            </select>
                        </div>
                        <div class="col-lg-12 col-12 mb-2">
                            <label for="">Attandance Type</label>
                            <select name="punch_all_type" id="punch_all_type" class="form-select form-select-sm">
                                <option value="1">With Weekly Off</option>
                                <option value="0">Without Weekly Off</option>
                            </select>
                        </div>
                        <div class="col-lg-12 " id="punchShiftBox">
                            <label for="all_att_shift_id" class="form-label ">Shift<span
                                    class="text-danger fw-bold"> </span></label>
                            <select class="form-select form-select-sm chosen-select"
                                name="all_att_shift_id" id="all_att_shift_id">
                                <option value="">Select</option>

                            </select>
                        </div>
                        <input type="hidden" id="punch_all_month">
                        <input type="hidden" id="punch_all_year">
                        <input type="hidden" id="punch_all_employee_id">
                        <div class="col-lg-12 col-12">
                            <label for="">Remark</label>
                            <textarea name="punching_all_remark" id="punching_all_remark" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <hr class="mb-0 mt-2">
                <?php
                $chkedit = $obj->check_editBtn($pagename, $loginid);
                if ($chkedit == 1) { ?>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveAllbutton" onclick="saveAllPunch()">Punch All</button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- script tag -->
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>

    <!-- script tag -->

    <script>
        const statusMap = {
            "Present": {
                txt: "P",
                bg: "rgb(173,233,179)"
            },
            "Absent": {
                txt: "A",
                bg: "rgb(251,175,175)"
            },
            "first_half": {
                txt: "HD",
                bg: "rgb(255,246,163)"
            },
            "second_half": {
                txt: "HD",
                bg: "rgb(255,246,163)"
            },
            "weekly_leave": {
                txt: "WL",
                bg: "rgb(229,204,255)"
            },
            "earn_leave": {
                txt: "L",
                bg: "rgb(121,170,248)"
            },
            "half_earn_leave": {
                txt: "HL",
                bg: "rgb(121,246,248)"
            },
            "half_weekly_leave": {
                txt: "HW",
                bg: "rgb(226,237,109)"
            },
            "c_off": {
                txt: "C",
                bg: "rgb(121,246,248)"
            },
            "half_c_off": {
                txt: "HC",
                bg: "rgb(226,237,109)"
            }
        };

        var s = statusMap[punch_all_status] || statusMap["Absent"];
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


            // $('#buttons-datatables').DataTable().destroy();
            // $('#buttons-datatables').DataTable({
            //     autoWidth: false,
            //     dom: "lBfrtip",
            //     buttons: [
            //         "csv",
            //         {
            //             extend: "excel",
            //             pageSize: "LEGAL",
            //             footer: true
            //         }
            //     ],
            // });




        });


        function openPunchModal(attdate, time, remark, emp_shift_hrs, empp_shift_id, month, year, emp_id, salary_generate_count) {
            document.getElementById('punch_attdate').value = attdate;
            document.getElementById('punching_remark').value = remark;
            document.getElementById('current_month').value = month;
            document.getElementById('current_year').value = year;
            document.getElementById('employee_id').value = emp_id;
            month2 = parseInt(month);
            if (salary_generate_count > 0) {
                $('#salaryReportLink').attr(
                    'href',
                    'salary_generate_report.php?emp_id=' + emp_id + '&month=' + month2 + '&year=' + year + '&submit=Search'
                );
                $('#salaryGeneratedModal').modal('show');
            } else {
                $.ajax({
                    type: "POST",
                    url: "",
                    data: {
                        ajax_emp_shift_hrs: emp_shift_hrs,
                        empp_shift_id: empp_shift_id
                    },
                    success: function(data) {
                        $("#punch_att_shift_id").html(data).trigger("change.select2");
                    }
                });
                $('#exampleModal').modal('show');
            }

            $('#show_att_details').attr(
                'href',
                'employee_wise_attendance.php?emp_id=' + emp_id + '&currentYear=' + year + '&currentMonth=' + month + '&date=' + attdate
            );

        };

        function savePunch() {
            var btn = document.getElementById('savebutton');
            var punchtime = document.getElementById('punch_time').value;
            var attdate = document.getElementById('punch_attdate').value;
            var punch_remark = document.getElementById('punching_remark').value;
            var punch_status = document.getElementById('punch_status').value;
            var punch_shift_id = document.getElementById('punch_att_shift_id').value;
            var currentMonth = document.getElementById('current_month').value;
            var currentYear = document.getElementById('current_year').value;
            var emp_id = document.getElementById('employee_id').value;



            if (punch_shift_id == "") {
                alert("Please Select Shift Name");
                return false;
            }
            btn.disabled = true;
            btn.value = 'Saving...';
            jQuery.ajax({
                type: 'POST',
                url: 'ajax_att_save_punch.php',
                data: 'punchtime=' + punchtime + '&emp_id=' + emp_id + '&attdate=' + attdate + '&currentYear=' + currentYear + '&currentMonth=' + currentMonth + '&punch_remark=' + punch_remark + '&punch_status=' + punch_status + '&punch_shift_id=' + punch_shift_id,
                dataType: 'html',
                success: function(data) {
                    //alert(data);
                    // showatttype();
                    $('#exampleModal').modal('hide');
                    var emp_id = document.getElementById('employee_id').value;
                    var attdate = document.getElementById('punch_attdate').value;
                    var status = document.getElementById('punch_status').value;

                    var txt = 'A';
                    var bg = 'rgb(251,175,175)';

                    if (status == 'Present') {
                        txt = 'P';
                        bg = 'rgb(173,233,179)';
                    } else if (status == 'first_half' || status == 'second_half') {
                        txt = 'HD';
                        bg = 'rgb(255,246,163)';
                    } else if (status == 'Incomplete') {
                        txt = 'I';
                        bg = 'rgb(233,61,61)';
                    } else if (status == 'weekly_leave') {
                        txt = 'WL';
                        bg = 'rgb(229,204,255)';
                    } else if (status == 'earn_leave') {
                        txt = 'L';
                        bg = 'rgb(121,170,248)';
                    } else if (status == 'half_earn_leave') {
                        txt = 'HL';
                        bg = 'rgb(121,246,248)';
                    } else if (status == 'half_weekly_leave') {
                        txt = 'HW';
                        bg = 'rgb(226,237,109)';
                    } else if (status == 'c_off') {
                        txt = 'C';
                        bg = 'rgb(121,246,248)';
                    } else if (status == 'half_c_off') {
                        txt = 'HC';
                        bg = 'rgb(226,237,109)';
                    }

                    // 🔥 update only that cell
                    var cellId = "#cell_" + emp_id + "_" + attdate;

                    $(cellId).css("background", bg);
                    $(cellId).find("b").text(txt);


                    document.getElementById('punching_remark').value = '';
                    $('#punch_status').val('Present').trigger('chosen:updated').trigger('change');

                    btn.disabled = false;
                    btn.value = 'Save change';
                    // total(emp_id, currentMonth, currentYear);
                    //  location.reload();
                },
                error: function() {
                    btn.disabled = false;
                    btn.value = 'Punch In';
                    Swal.fire("Error", "Error while uploading. Try again.");
                }

            }); //ajax close

        }

        function add_all_att(emp_shift_hrs, empp_shift_id, month, year, emp_id, salary_generate_count) {
            document.getElementById('punch_all_month').value = month;
            document.getElementById('punch_all_year').value = year;
            document.getElementById('punch_all_employee_id').value = emp_id;
            if (salary_generate_count > 0) {
                $('#salaryReportLink').attr(
                    'href',
                    'salary_generate_report.php?emp_id=' + emp_id
                );
                $('#salaryGeneratedModal').modal('show');
            } else {


                $.ajax({
                    type: "POST",
                    url: "",
                    data: {
                        ajax_emp_shift_hrs: emp_shift_hrs,
                        empp_shift_id: empp_shift_id
                    },
                    success: function(data) {
                        $("#all_att_shift_id").html(data).trigger("change.select2");
                    }
                });
                $('#AllAttendenceModal').modal('show');
            }

            $('#show_attAll_details').attr(
                'href',
                'employee_wise_attendance.php?emp_id=' + emp_id + '&currentYear=' + year + '&currentMonth=' + month
            );
        };


        function saveAllPunch() {
            var btn = document.getElementById('saveAllbutton');
            var punch_remark = document.getElementById('punching_all_remark').value;
            var punch_all_status = document.getElementById('punch_all_status').value;
            var punch_shift_id = document.getElementById('all_att_shift_id').value;
            var punchtime = ' <?= date("H:i:s"); ?>';
            var currentMonth = document.getElementById('punch_all_month').value;
            var currentYear = document.getElementById('punch_all_year').value;
            var emp_id = document.getElementById('punch_all_employee_id').value;
            var punch_all_type = document.getElementById('punch_all_type').value;

            if (punch_shift_id == "") {
                alert("Please Select Shift Name");
                return false;
            }

            btn.disabled = true;
            btn.value = 'Saving...';
            Swal.fire({
                title: 'Please wait...',
                text: 'Applying attendance for all days',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            jQuery.ajax({
                type: 'POST',
                url: 'ajax_att_save_all_punch.php',
                data: 'emp_id=' + emp_id + '&currentYear=' + currentYear + '&currentMonth=' + currentMonth + '&punch_remark=' + punch_remark + '&punch_status=' + punch_all_status + '&punch_shift_id=' + punch_shift_id + '&punchtime=' + punchtime + '&punch_all_type=' + punch_all_type,
                dataType: 'html',
                success: function(data) {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Attendance saved successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        $('#AllAttendenceModal').modal('hide');
                        var emp_id = document.getElementById('punch_all_employee_id').value;

                        var s = statusMap[punch_all_status] || statusMap["Absent"];

                        for (var d = 1; d <= '<?= $length ?>'; d++) {

                            var day = d.toString().padStart(2, '0');
                            var date = currentYear + "-" + currentMonth + "-" + day;

                            var cellId = "#cell_" + emp_id + "_" + date;

                            if ($(cellId).length) {
                                $(cellId).css("background", s.bg);
                                $(cellId).find("b").text(s.txt);
                            }
                        }


                        document.getElementById('punching_all_remark').value = '';
                        $('#punch_all_status').val('Present').trigger('chosen:updated').trigger('change');
                        btn.disabled = false;
                        btn.value = 'Save change';
                        // location.reload();
                    });



                },
                error: function() {
                    btn.disabled = false;
                    btn.value = 'Punch In';
                    Swal.fire("Error", "Error while uploading. Try again.");
                }

            }); //ajax close

        }
    </script>
</body>

</html>