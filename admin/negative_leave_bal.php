<?php include("../adminsession.php");
$title = "Leave Deficit Report"; // purana sahi wala hai
$pagename = "negative_leave_bal.php";
$module = "Search Attendance";
$submodule = "Leave Deficit Report";
$btn_name = "Search";
$keyvalue = 0;
$tblname = "attendance_entry";
$tblpkey = "attendance_id";
$is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$unitid'");
$crit2 = " and 1=1";
 $unitname = $obj->getvalfield("unit_master","unit_name","unit_id='$unitid'");

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
 
$month = date('n');
$year = date('Y');
$year_month = "";
if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = (int)$obj->test_input($_GET['month']);

    $year = $obj->test_input($_GET['year']);
    $month_name = date("F", mktime(0, 0, 0, $_GET['month'], 10));
} else {
    $month_name = '';
}
 
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
    2 => ['label' => 'Emp Code',         'key' => 'emp_code', 'class' => 'col-fixed'],
    3 => ['label' => 'Emp Name',         'key' => 'first_name','class' => 'col-fixed'],
    4 => ['label' => 'Aadhaar No',         'key' => 'aadhar_no'],
    5 => ['label' => 'Present Salary',    'key' => 'basic_salary' ],
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

table tr th.col-fixed,
table tr td.col-fixed {
    position: sticky !important;
    z-index: 9;
    background: #d9dce7;
}

table tr th.col-fixed:nth-child(1),
table tr td.col-fixed:nth-child(1) {
    left: 0px;
}

table tr th.col-fixed:nth-child(2),
table tr td.col-fixed:nth-child(2) {
    left: 41px;
}

table tr th.col-fixed:nth-child(3),
table tr td.col-fixed:nth-child(3) {
    left: 83px;
}
</style>

<body>
    <?php include('inc/header.php') ?>
    <?php include('inc/loader.php') ?>
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
                                                <label for="department_id" class="form-label">Department Name<span
                                                        class="text-danger fw-bold"></span></label>
                                                <select class="form-select chosen-select" name="department_id"
                                                    id="department_id">
                                                    <option value="">All</option>
                                                    <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_name asc");
                                                        foreach ($res as $key) {
                                                            echo "<option value='" . $key['department_id'] . "'>" . $key['department_name'] . "</option>";
                                                        } ?>
                                                </select>
                                                <script>
                                                document.getElementById('department_id').value =
                                                    '<?= $department_id; ?>';
                                                </script>
                                            </div>
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
                                            <div class="col-md-3 md-2">
                                                <strong><label for="Month">Month<span
                                                            class="text-danger fw-bold">*</span></label></strong></br>
                                                <select name="month" class="chosen-select form-control form-control"
                                                    id="month">
                                                    <option value="">--Select Month--</option>
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
                                                    '<?php echo str_pad($month, 2, "0", STR_PAD_LEFT); ?>';
                                                </script>
                                            </div>
                                            <div class="col-md-3 md-2">
                                                <strong><label for="Fields">Fields<span
                                                            class="text-danger fw-bold"></span></label></strong>
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
                                                <input type="submit" class="btn btn-primary add-btn"
                                                    onclick="return checkinputmaster('year,month')" name="search"
                                                    value="Search">
                                                <a href="<?php echo $pagename; ?>" class="btn btn-danger" name="reset"
                                                    id="reset">Reset</a>
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
                            <div class="card-header border-bottom-dashed"
                                style="margin-bottom: 0px; padding-bottom: 0px;">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm" style="margin-top: 8px;">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                            <h5 class="card-title mb-0">
                                                <?= $submodule; ?>
                                            </h5>
                                            <div class="ms-2 card-title mb-0"><b>
                                                    <?php if (!empty($_GET['year'])) { ?>
                                                    Year: <?= $_GET['year']; ?>
                                                    <?php } ?>
                                                    <?php if (!empty($month_name)) { ?>
                                                    | Month: <?= $month_name; ?>
                                                    <?php } ?>

                                                    <?php if (!empty($department_name)) { ?>
                                                    | Dept: <?= $department_name; ?>
                                                    <?php } ?></b>
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
                            $firstDateOfMonth = date("Y-m-01", strtotime("$year-$month-01"));
                            $lastDateOfMonth = date("Y-m-t", strtotime("$year-$month-01")); 
                            
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

                                        /* Show employee if:
                                        1. No record in emp_active_status => Active
                                        2. Last record is active
                                        */
                                        AND (
                                            eas.active_id IS NULL
                                            OR eas.is_active = '1'
                                        )  

                                        $crit2

                                    GROUP BY e.emp_id
                                    ORDER BY e.emp_code
                                "); 
                                if (empty($employees)) {
                                    $employees = [];
                                }
                                $empIds = array_column($employees, 'emp_id');
                                if (empty($empIds)) {
                                    $empIdsStr = '0';
                                } else {
                                    $empIdsStr = implode(',', $empIds);
                                }
                                 
                                $fromDate  = date("Y-m-d", strtotime("$year-$month-01"));
 
                                $toDate   = date("Y-m-t", strtotime($fromDate));

                                $prevStart = date('Y-m-01',strtotime("$fromDate -1 month"));
                                $prevEnd = date('Y-m-t',strtotime("$fromDate -1 month"));

                                $attendanceRows = $obj->executequery("
                                                SELECT emp_id, attendance_date, attendance_status, intime, in_remark, shift_id
                                                FROM attendance_entry
                                                WHERE emp_id IN ($empIdsStr)
                                                AND attendance_date BETWEEN '$fromDate' AND '$toDate' AND unit_id='$unitid'
                                            ");

                                $attendanceMap = [];
                                // foreach ($attendanceRows as $row) {
                                //     $attendanceMap[$row['emp_id']][$row['attendance_date']] = $row;
                                // } 
                                foreach ($attendanceRows as $row) {
                                    $attendanceMap[$row['emp_id']][$row['attendance_date']][] = $row;
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
                                                            SUM(attendance_status='Public Holiday') AS publicho
                                                        FROM attendance_entry
                                                        WHERE emp_id IN ($empIdsStr)
                                                        AND attendance_date BETWEEN '$fromDate' AND '$toDate' AND unit_id='$unitid'
                                                        GROUP BY emp_id
                                                    ");

                                $summary = [];
                                foreach ($summaryRows as $row) {
                                    $summary[$row['emp_id']] = $row;
                                }

                                $holidayAttendanceStart = date('Y-m-d', strtotime($fromDate . ' -1 day'));
                                $holidayAttendanceEnd   = date('Y-m-d', strtotime($toDate . ' +1 day'));
                                $holidayAttendanceRows = $obj->executequery("
                                                            SELECT 
                                                                emp_id,
                                                                attendance_date,
                                                                attendance_status

                                                            FROM attendance_entry

                                                            WHERE emp_id IN ($empIdsStr)

                                                            AND attendance_date BETWEEN '$holidayAttendanceStart' 
                                                            AND '$holidayAttendanceEnd'

                                                            AND unit_id='$unitid'
                                                        ");
                                                        $holidayAttendanceMap = [];

                                foreach ($holidayAttendanceRows as $row) {

                                    $holidayAttendanceMap[$row['emp_id']][$row['attendance_date']]
                                        = $row['attendance_status'];
                                }
                                $holidayRows = $obj->executequery("
                                                                SELECT date , holiday_type
                                                                FROM holiday_entry
                                                                WHERE FIND_IN_SET('$unitid', unit_id)
                                                                AND date BETWEEN '$fromDate' AND '$toDate'
                                                            ");

                                $holidays = [];
                                foreach ($holidayRows as $h) {
                                    $holidays[$h['date']] = true;
                                }
                                $salaryRows = $obj->executequery("
                                                                    SELECT emp_id, COUNT(*) cnt
                                                                    FROM salary_structure
                                                                    WHERE emp_id IN ($empIdsStr)
                                                                    AND month='$month' AND year='$year' AND unit_id='$unitid'
                                                                    GROUP BY emp_id
                                                                ");
                                $salaryGenerated = [];
                                foreach ($salaryRows as $s) {
                                    $salaryGenerated[$s['emp_id']] = $s['cnt'];
                                }

                                $earningLeaveRows = $obj->earningLeaveRows($sessionid,$month,$year);
                            
                                $usedEarnMap = [];

                                foreach($earningLeaveRows as $r){

                                    $usedEarnMap[$r['emp_id']] = $r['used_leave'];
                                }
                                $earningUploadRows=$obj->earningUploadRows($sessionid,$month,$year); 
                                $earningUploadMap = [];

                                foreach($earningUploadRows as $r){

                                    $earningUploadMap[$r['emp_id']] = $r['total_leave'];
                                }

                                $uploadArr = [];

                                $res = $obj->executequery("
                                    SELECT emp_id,
                                        month,
                                        SUM(total_leave) total_leave
                                    FROM emp_monthly_leave
                                    WHERE leave_type='eoff'
                                    AND year='$year'
                                    GROUP BY emp_id,month
                                ");

                                foreach($res as $row){
                                    $uploadArr[$row['emp_id']][(int)$row['month']] = (float)$row['total_leave'];
                                }

                                $usedArr = [];
                                $res = $obj->executequery("
                                    SELECT emp_id,
                                        month,
                                        SUM(
                                                CASE
                                                    WHEN attendance_status='Extra Off' THEN 1
                                                    WHEN attendance_status='Half Extra Off' THEN 0.5
                                                    ELSE 0
                                                END
                                        ) total_used
                                    FROM attendance_entry
                                    WHERE year='$year'
                                    AND attendance_status IN ('Extra Off','Half Extra Off')
                                    GROUP BY emp_id,month
                                ");
                                foreach($res as $row){
                                    $usedArr[$row['emp_id']][(int)$row['month']] = (float)$row['total_used'];
                                }
    
                       
                                $coffUsedRows = $obj->getEmpUsedCoff(
                                    $empIdsStr,
                                    $sessionid,
                                    $month,
                                    $year
                                );

                                $usedCoffMap = [];

                                foreach ($coffUsedRows as $r) {

                                    $usedCoffMap[$r['emp_id']] = $r['used_coff'];
                                } 
                            
                                $coffUploadRows = $obj->getEmpUploadedCoff(
                                    $empIdsStr,
                                    $sessionid,
                                    $month,
                                    $year
                                ); 
                                $coffUploadMap = []; 
                                foreach ($coffUploadRows as $r) {

                                    $coffUploadMap[$r['emp_id']] = $r['total_leave'];
                                } 

                                        $reportFromDate = "$year-$month-01";          // report start
                                        $fromDate_att = date('Y-m-d', strtotime("$reportFromDate -1 day")); // fetch start
                                        $toDate_att  = date("Y-m-t", strtotime($reportFromDate));  
                                $punchData = $obj->executequery("
                                    SELECT 
                                        l.emp_id,
                                        em.shift_id,
                                        l.attendance_date,
                                        l.attendance_stamp,
                                        l.in_status
                                    FROM attendance_log l left join employee_master em on em.emp_id=l.emp_id
                                    WHERE l.emp_id IN ($empIdsStr)
                                    AND l.attendance_date BETWEEN '$fromDate_att' AND '$toDate_att'
                                    ORDER BY l.emp_id, l.attendance_stamp
                                ");
                                $punchMap = [];
                                $lastOpen = [];
                                foreach ($punchData as $row) {
                                    $emp    = $row['emp_id'];
                                    $date   = $row['attendance_date'];
                                    $status = $row['in_status'];
                                    $time   = date("H:i", strtotime($row['attendance_stamp']));
                                    $stamp  = strtotime($row['attendance_stamp']);

                                    if (!isset($punchMap[$emp])) {
                                        $punchMap[$emp] = [];
                                    }

                                    /* ================= GET SHIFT WINDOW ================= */

                                    $working_hrs = $row['shift_id'] ?? '08:00:00';
                                
                                    $shift_row = $obj->executequery("
                                        SELECT in_time 
                                        FROM shift_master 
                                        WHERE working_hour='$working_hrs' 
                                        ORDER BY in_time ASC 
                                        LIMIT 1
                                    ");

                                    $morning_in = $shift_row[0]['in_time'] ?? '06:00:00';

                                    /* ================= IN LOGIC ================= */
                                    if ($status == 'IN') {

                                        if (!isset($punchMap[$emp][$date])) {
                                            $punchMap[$emp][$date] = [];
                                        }

                                        $punchMap[$emp][$date][] = [
                                            'in'  => $time,
                                            'in_stamp' => $stamp,
                                            'out' => '',
                                        
                                        ];

                                        $lastOpen[$emp] = [
                                            'date'  => $date,
                                            'index' => count($punchMap[$emp][$date]) - 1,
                                            'stamp' => $stamp
                                        ];
                                    }

        
                                    if ($status == 'OUT') {
                                        $matched = false;
                                        foreach (array_reverse($punchMap[$emp], true) as $pDate => $entries) {
                                            foreach (array_reverse($entries, true) as $idx => $entry) {
                                                if (!empty($entry['in'])) {
                                                    $inStamp = $entry['in_stamp'];
                                                    $base_date = date('Y-m-d', strtotime($pDate . ' +1 day'));
                                                    $max_out = strtotime($base_date . ' ' . $morning_in) + (4 * 3600);
                                                    if (
                                                        (
                                                            $stamp > $inStamp ||
                                                            date('Y-m-d', $stamp) > date('Y-m-d', $inStamp)
                                                        )
                                                        && $stamp <= $max_out
                                                    ) {
                                                        // 🔥 ALWAYS overwrite
                                                    // agar already OUT hai → naya pair banao
                                                    if (!empty($punchMap[$emp][$pDate][$idx]['out'])) {

                                                        $punchMap[$emp][$pDate][] = [
                                                            'in' => '',
                                                            'out' => $time
                                                        ];

                                                    } else {

                                                        $punchMap[$emp][$pDate][$idx]['out'] = $time;
                                                    }

                                                        $matched = true;
                                                        break 2;
                                                    }
                                                }
                                            }
                                        }
                                        if (!$matched) {

                                            // ✅ SAME DATE me hi show hoga
                                            if (!isset($punchMap[$emp][$date])) {
                                                $punchMap[$emp][$date] = [];
                                            }

                                            $punchMap[$emp][$date][] = [
                                                'in'  => '',
                                                'out' => $time
                                            ];
                                        }
                                    }
                                }
                                $missPunchMap = [];

                                foreach ($punchMap as $empId => $dates) {

                                    foreach ($dates as $pDate => $entries) {

                                        $hasIn = false;

                                        foreach ($entries as $p) {
                                            if (!empty($p['in'])) {
                                                $hasIn = true;
                                                break;
                                            }
                                        }

                                        // ✅ ONLY OUT (no IN)
                                        if (!$hasIn && !empty($entries)) {
                                            $missPunchMap[$empId][$pDate] = 'Miss Punch';
                                        }
                                    }
                                }
                                    ?>
                            <!-- floating scrollbar -->
                            <div class="auto-scroll-wrapper">
                                <div class="table-responsive">
                                    <table id="buttons-datatables"
                                        class="table table-sm table-bordered table-hover align-middle display bg-white">
                                        <thead class="table-primary">
                                            <tr>
                                                <th class="col-fixed">S.No.</th>
                                                <?php
                                                        foreach ($showFields as $fid) {
                                                            if (!isset($fieldMap[$fid])) continue;
                                                            $class = isset($fieldMap[$fid]['class'])
                                                                ? ' class="' . $fieldMap[$fid]['class'] . '"'
                                                                : '';
                                                            echo "<th {$class}>{$fieldMap[$fid]['label']}</th>";
                                                        }
                                                        ?>

                                                <th>Present <br> Days</th>
                                                <th>Half <br> Days</th>
                                                <th>Week <br> Off</th>
                                                <th>Used<br>Week <br> Off</th>
                                                <th>Curr.<br>Earn <br> Leave</th>
                                                <th>Availed<br>Leave</th>
                                                <th>C-Off<br>Adj.</th>
                                                <th>Absent</th>
                                                <th>Total <br>Present <br>Day</th>
                                                <th>Prev.<br>Earn<br>Leave</th>
                                                <th>Tot.<br>Earn <br> Leave</th>
                                                <th>Prev <br>C-<br>Off</th>
                                                <th>Curr.<br>C-OFF</th>
                                                <th>Tot.<br>C Off</th>
                                                <th>Extra Off</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                    $slno = 1;
                                                    $currentDate = date("Y-m-d");
                                                    $totalPresentDays = 0;
                                                    $totalWeekOff = 0;
                                                    $total_tpd = 0;
                                                    $totalEarnLEave = 0; 
                                                    $totalHoliday = 0;
                                                    $totalAbsent = 0;
                                                    $totalPrevCoff = 0;
                                                    $totalExtraOff = 0;
                                                    $totalPendingEL = 0;
                                                    $totalOpeningLeave = 0;
                                                    $totalPenCoff = 0;
                                                    $total_final_earn_leave = 0;
                                                    $total_final_coff = 0; 
                                                    $total_availed_leave = 0; 
                                                    $total_present_day= 0; 
                                                    $total_half_day= 0; 
                                                    $total_taken_coff= 0; 
                                                    $total_used_weekly= 0; 

                                                    $chkedit = $obj->check_editBtn($pagename, $loginid);
                                                    foreach ($employees as $emp) {
                                                        $empId = $emp['emp_id'];
                                                        $department_id = $emp['department_id'];
                                                        $date_of_joining = $emp['date_of_joining'];
                                                        $allow_weekly_off = $emp['allow_weekly_off'];
                                                        $is_allow_c_off = $emp['c_off_check'];
                                                        $basic_salary = $emp['basic_salary'];
                                                        $allow_earn_leave_carry = $emp['earn_leave_check']; 
                                                        $uploaded_coff = $obj->getvalfield("emp_leave_allotment", "coff", "emp_id='$empId'");

                                                        $is_esic = $emp['is_esic'];
                                                        $setting_type = ($is_esic  == 1) ? 'ESIC' : 'Non ESIC';

                                                        $sum   = $summary[$empId] ?? ['present' => 0, 'halfday' => 0, 'leavecnt' => 0, 'leavecnt2' => 0, 'halfearn' => 0, 'halfweek' => 0, 'coff' => 0, 'halfcoff' => 0, 'op_leave' => 0, 'halfopleave' => 0, 'half_extra_off' => 0, 'extra_off' => 0,'publicho'=>0];

                                                        $totalAttendance = $sum['present'] + ($sum['halfday'] / 2) + $sum['leavecnt'] + $sum['leavecnt2'] + ($sum['halfearn'] / 2) + ($sum['halfweek'] / 2) + $sum['coff'] + ($sum['halfcoff'] / 2) + ($sum['halfopleave'] / 2) + ($sum['half_extra_off'] / 2) + $sum['op_leave'] + $sum['extra_off']+ $sum['publicho'];

                                                        $present_day = $sum['present']; 
                                                        $half_day = $sum['halfday'];
                                                        $taken_coff = $sum['extra_off']+ ($sum['half_extra_off'] / 2)+ ($sum['halfcoff'] / 2) + $sum['coff']; 
                                                    

                                                        $show_leave = $sum['present'] + ($sum['halfday'] / 2) + $sum['leavecnt'] +($sum['halfweek'] / 2) + $sum['coff'] + ($sum['halfcoff'] / 2) + ($sum['half_extra_off'] / 2) + $sum['extra_off'];

                                                        $availed_leave = $sum['leavecnt2'] + ($sum['halfearn'] / 2)+ $sum['op_leave']+ ($sum['halfopleave'] / 2);

                                                      

                                                        $real_total_att = $sum['present'] + ($sum['halfday'] / 2);
                                                    
                                                        $salaryCount = $salaryGenerated[$empId] ?? 0;
                                                        //$totalPresentDays +=  $totalAttendance;
                                                       
                                                        $week_leave = $obj->totalWeeklyLeave($unitid, $real_total_att, $empId,$month, $year);
                                                        
                                                        $earn_leave_present = $real_total_att + $week_leave;
                                                        $monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $earn_leave_present, $unitid);
                                                        $holidayData = $obj->getHolidayCountWithSandwichRule2(
                                                            $empId,
                                                            $holidayRows,
                                                            $holidayAttendanceMap
                                                        );

                                                        $holiday = $holidayData['total']; 
                                                    
                                                        $total_earning_leave =($earningUploadMap[$empId] ?? 0) -($usedEarnMap[$empId] ?? 0); 

                                                        $extraOff = $obj->getExtraOffBalance2(
                                                            $empId,
                                                            $month,
                                                            $uploadArr,
                                                            $usedArr
                                                        );
                                                        $extra_off['balance'] = $extraOff['balance'];
                                        
                                                    
                                                        //$tpd = $totalAttendance + $holiday + $week_leave;
                                                        $tpd = $totalAttendance + $week_leave;
                                                        $prev_coff =  ($coffUploadMap[$empId] ?? 0)-($usedCoffMap[$empId] ?? 0);
 
                                                        $result = $obj->calculateLeaveUsage(
                                                            $length,
                                                            $totalAttendance,
                                                            $week_leave,
                                                            $monthly_leave,
                                                            // $three_month_leave,
                                                            $is_allow_c_off,
                                                            $is_all_leave_add,
                                                            $allow_earn_leave_carry,0,0,$date_of_joining,$month,$year
                                                        );
    
                                                        $used_monthly_leave  = $result['used_monthly']; 
                                                        $used_weekly  = $result['used_weekly']; 
                                                        $used_monthly_leave  = $result['used_monthly']; 
                                                        $tpd  = $result['total_working_days']; 
                                                        $absent  = $result['absent']; 
                                                        $hasNegativeLeave = (
                                                            $total_earning_leave < 0 ||
                                                            $prev_coff < 0 ||
                                                            $extra_off['balance'] < 0
                                                        );

                                                        if (!$hasNegativeLeave) {
                                                            continue;
                                                        }


                                                        $total_availed_leave +=$availed_leave;
                                                        $total_present_day += $present_day;
                                                        $total_half_day += $half_day;
                                                        $total_taken_coff += $taken_coff;
                                                        $totalWeekOff += $week_leave;
                                                        $totalEarnLEave += $monthly_leave;
                                                        $totalPresentDays +=  $show_leave;
                                                        $rem_month_leave = $monthly_leave-$used_monthly_leave;
                                                        $pen_coff = $week_leave-$used_weekly; 
                                                        $total_tpd += $tpd;
                                                        $total_used_weekly += $used_weekly; 
                                                        $totalHoliday += $holiday;
                                                        $totalAbsent += $absent;
                                                        $totalPrevCoff += $prev_coff;
                                                        $totalExtraOff += $extra_off['balance'];
                                                        $totalPendingEL += $total_earning_leave; 
                                                        $totalPenCoff += $pen_coff; 
                                                        $final_earn_leave = $total_earning_leave + $rem_month_leave; 
                                                        $final_coff = $prev_coff + $pen_coff;
                                                        $total_final_earn_leave += $final_earn_leave;
                                                        $total_final_coff += $final_coff;

                                                       
                                                        echo "<tr>";
                                                        echo "<td class='col-fixed'>" . $slno++ . "</td>";
                                                        foreach ($showFields as $fid) {
                                                            if (!isset($fieldMap[$fid])) continue;

                                                            $key = $fieldMap[$fid]['key'];
                                                            $class = isset($fieldMap[$fid]['class'])
                                                                ? ' class="' . $fieldMap[$fid]['class'] . '"'
                                                                : '';
                                                            $value = $emp[$key] ?? '-';
                                                            // special formatting
                                                            if ($fid == 9 && !empty($value)) { // Date of Joining
                                                                $value = $obj->dateformatindia($value);
                                                            }
                                                            echo "<td {$class}>{$value}</td>";
                                                        } 

                                                        echo "<td class='text-center fw-bold'  >" . number_format($present_day, 1) . "</td>";

                                                        echo "<td class='text-center fw-bold'  >" . number_format($half_day, 1) . "</td>";
                                                    
                                                        echo "<td class='text-center fw-bold text-dark'  >" . number_format($week_leave, 1) . "</td>";
                                                        echo "<td class='text-center fw-bold text-dark'  >" . number_format($used_weekly, 1) . "</td>";
                                                        echo "<td class='text-center fw-bold text-dark'  >" . number_format($monthly_leave, 1) . "</td>";
                                                        echo "<td class='text-center fw-bold text-dark'>" . number_format($availed_leave, 1) . "</td>";
                                                        echo "<td class='text-center fw-bold text-dark'  >" . number_format($taken_coff, 1) . "</td>";
                                                        echo "<td class='text-center fw-bold text-danger'  >" . number_format($absent, 1) . "</td>";
                                                        echo "<td class='text-center fw-bold text-dark' style='background:#d4ede8'>" . number_format($tpd, 1) . "</td>";
                                                        echo "<td class='text-center fw-bold text-dark' style='background:#e7f1ff' >" . number_format($total_earning_leave, 1) . "</td>";
                                                        

                                                    echo "<td class='text-center fw-bold text-dark'  >" 
                                                            . number_format($final_earn_leave, 1) . 
                                                        "</td>";
                                                        
                                                        echo "<td class='text-center fw-bold text-dark' style='background:#f3e8ff'>" . number_format($prev_coff, 1) . "</td>";
                                                        echo "<td class='text-center fw-bold text-dark' >" . number_format($pen_coff, 1) . "</td>";

                                                        echo "<td class='text-center fw-bold text-dark'  >" 
                                                            . number_format($final_coff, 1) . 
                                                        "</td>";

                                                        echo "<td class='text-center fw-bold text-dark' style='background:#fce5c2'>" . number_format($extra_off['balance'], 1) . "</td>";  
                                                        echo "</tr>";
                                                    }

                                                ?>
                                        </tbody>
                                        <tfoot>
                                            <tr style="background:#e9ecef;font-weight:bold">
                                                <td></td>
                                                <td colspan="<?= count($showFields) ; ?>" class="text-end">
                                                    Total
                                                </td>
                                                <td class="text-center fw-bold">
                                                    <?= number_format($total_present_day,1) ?>
                                                </td>
                                                <td class="text-center fw-bold"><?= number_format($total_half_day,1) ?>
                                                </td>
                                                <td class="text-center fw-bold"><?= number_format($totalWeekOff,1) ?>
                                                </td>
                                                <td class="text-center fw-bold">
                                                    <?= number_format($total_used_weekly,1) ?>
                                                </td>
                                                <td class="text-center fw-bold"><?= number_format($totalEarnLEave,1) ?>
                                                </td>
                                                <td class="text-center fw-bold">
                                                    <?= number_format($total_availed_leave,1) ?>
                                                </td>
                                                <td class="text-center fw-bold">
                                                    <?= number_format($total_taken_coff,1) ?>
                                                </td>
                                                <td class="text-center fw-bold text-danger">
                                                    <?= number_format($totalAbsent,1) ?>
                                                </td>
                                                <td class="text-center fw-bold" style="background:#d4ede8">
                                                    <?= number_format($total_tpd,1) ?>
                                                </td>
                                                <td class="text-center fw-bold" style="background:#e7f1ff">
                                                    <?= number_format($totalPendingEL,1) ?>
                                                </td>
                                                <td class="text-center fw-bold">
                                                    <?= number_format($total_final_earn_leave,1) ?>
                                                </td>
                                                <td class="text-center fw-bold" style="background:#f3e8ff">
                                                    <?= number_format($totalPrevCoff,1) ?>
                                                </td>
                                                <td class="text-center fw-bold"><?= number_format($totalPenCoff,1) ?>
                                                </td>
                                                <td class="text-center fw-bold">
                                                    <?= number_format($total_final_coff,1) ?>
                                                </td>
                                                <td class="text-center fw-bold" style="background:#fce5c2">
                                                    <?= number_format($totalExtraOff,1) ?>
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
            <?php } ?>
        </div>
        <!-- Content close-->
    </div>
    </div>
    
    <?php include('inc/footer.php') ?>

    <!-- script tag -->
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>

    <!-- script tag -->

    <script>
    $(document).ready(function() {
        $('#buttons-datatables').DataTable().destroy();
        const exportTitle = "Attendece Details Sheet - " + new Date().toLocaleDateString('en-GB').replace(/\//g,
            '-');

        $('#buttons-datatables').DataTable({
            dom: "lBfrtip",
            buttons: [
                "copy",
                "csv",
                {
                    extend: "excel",
                    footer: true,
                    title: "<?= strtoupper($unitname); ?>",

                    filename: "<?= strtoupper($unitname); ?> ATTEN <?= strtoupper(date('F', mktime(0,0,0,$month,1))) . '-' . $year; ?>",
                    messageTop: "ATTENDANCE DETAILS SHEET FOR MONTH  <?= strtoupper(date('F', mktime(0,0,0,$month,1))) . '-' . $year; ?>",

                    exportOptions: {
                        columns: ':not(.no-export)',
                        format: {
                            header: function(data, columnIdx) {
                                return $('#buttons-datatables thead tr:eq(0) th')
                                    .eq(columnIdx)
                                    .text();
                            }
                        }
                    }
                },
                {
                    extend: "print",
                    pageSize: "LEGAL",
                    footer: true,
                    title: exportTitle,
                    exportOptions: {
                        columns: ':not(.no-export)'
                    }
                },
                {
                    extend: "pdf",
                    pageSize: "LEGAL",
                    footer: true,
                    title: exportTitle,
                    exportOptions: {
                        columns: ':not(.no-export)'
                    }
                }
            ],
        });
        $(".chosen-select").select2({
            width: '100%',
        });
    });
 
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