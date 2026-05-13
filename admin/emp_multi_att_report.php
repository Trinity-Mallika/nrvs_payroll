<?php include("../adminsession.php");
$title = "Month Wise Attendance Report";
$pagename = "emp_multi_att_report.php";
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
} else {
    $department_id = "";
};
if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    if ($emp_id != '') {
        $crit2 .= " and e.emp_id = '$emp_id'";
    }
} else {
    $emp_id = "";
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

if (isset($_POST['emp_idd'])) {
    $emp_id = $_POST['emp_idd'];
    $department_id = $obj->getvalfield("employee_master", "department_id", "emp_id='$emp_id'");
    $options = "<option value=''>Please Select</option>";
    $selected = "";
    if ($emp_id != "" || $emp_id > 0) {

        $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_name asc");

        foreach ($res as $row) {
            $selected = ($department_id == $row['department_id']) ? 'selected' : '';
            $options .= "<option value='" . $row['department_id'] . "' $selected>" . $row['department_name'] . "  </option>";
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
</style>

<body>
    <?php include('inc/header.php') ?>
    <?php include('inc/sidebar.php') ?>
    <!-- end auth-page-wrapper -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <?php include('inc/bredcrum.php') ?>
                <div class="row">
                    <div class="col-lg-12">
                        <fieldset class="mt-2">
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
                                                <label for="bill_no" class="form-label">Employee<span
                                                        class="text-danger fw-bold"></span></label>
                                                <select class="form-select form-select-sm chosen-select" name="emp_id"
                                                    id="emp_id" onchange="get_department(this.value)">
                                                    <option value="">Select</option>
                                                    <?php
                                                    //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                    $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                    foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['emp_code']; ?> -
                                                        <?= ucfirst($key['first_name'] ?? ''); ?>
                                                        <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                    <?php } ?>
                                                </select>
                                                <script>
                                                document.getElementById('emp_id').value =
                                                    '<?= $emp_id; ?>';
                                                </script>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="department_id" class="form-label">Department Name<span
                                                        class="text-danger fw-bold">*</span></label>
                                                <select class="form-select chosen-select" name="department_id"
                                                    id="department_id">
                                                    <option value="">Please Select</option>
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
                                                document.getElementById('month').value = '<?php echo $month; ?>';
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
                                                    onclick="return checkinputmaster('department_id,year,month')"
                                                    name="search" value="Search">
                                                <a href="<?php echo $pagename; ?>" class="btn btn-danger" name="reset"
                                                    id="reset">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                </div>
                <?php if (isset($_GET['search'])) { ?>

                <div class="row mt-4 mb-4">
                    <div class="col-lg-12">
                        <div class="card">

                            <div class="card-header border-bottom-dashed">
                                <h5 class="card-title mb-0"><?= $submodule; ?></h5>
                            </div>

                            <div class="card-body">

                                <?php

                                    $fromDate = "$year-$month-01";
                                    $toDate   = date("Y-m-t", strtotime($fromDate));

                                    /* ---------------- EMPLOYEES ---------------- */

                                    $employees = $obj->executequery("
                                        SELECT 
                                        e.emp_id,
                                        e.emp_code,
                                        e.first_name,
                                        e.last_name,
                                        e.mobile_no,
                                        e.aadhar_no,
                                        e.shift_id,
                                        um.unit_name,
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

                                        LEFT JOIN unit_master um 
                                        ON um.unit_id = e.unit_id

                                        LEFT JOIN department_master d 
                                        ON d.department_id = e.department_id

                                        LEFT JOIN designation_master des 
                                        ON des.designation_id = e.designation_id

                                        LEFT JOIN shift_master s 
                                        ON s.working_hour = e.shift_id 

                                        WHERE e.unit_id='$unitid' $crit2
                                        GROUP BY e.emp_id
                                        ");


                                    $empIds = array_column($employees, 'emp_id');
                                    $empIdsStr = implode(',', $empIds);

                                    /* ---------------- PUNCH DATA ---------------- */

                                    $punchMap = [];
                                    $lastOpen = []; // last open IN per employee

                                    if (!empty($empIdsStr)) {

                                    $entryMap = [];

 

    $entryData = $obj->executequery("
        SELECT emp_id, attendance_date, working_hours, overtime, attendance_status
        FROM attendance_entry
        WHERE emp_id IN ($empIdsStr)
        AND attendance_date BETWEEN '$fromDate' AND '$toDate'
    ");

    foreach ($entryData as $row) {
        $entryMap[$row['emp_id']][$row['attendance_date']] = [
            'working_hours' => $row['working_hours'],
            'overtime'      => $row['overtime'],
            'status'        => $row['attendance_status']
        ];
    }

                                    $punchData = $obj->executequery("
    SELECT 
        l.emp_id,
        em.shift_id,
        l.attendance_date,
        l.attendance_stamp,
        l.in_status
    FROM attendance_log l left join employee_master em on em.emp_id=l.emp_id
    WHERE l.emp_id IN ($empIdsStr)
    AND l.attendance_date BETWEEN '$fromDate' AND '$toDate'
    ORDER BY l.emp_id, l.attendance_stamp
");

                                        

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

    /* ================= OUT LOGIC ================= */
   if ($status == 'OUT') {

    $matched = false;

    foreach (array_reverse($punchMap[$emp], true) as $pDate => $entries) {

        foreach (array_reverse($entries, true) as $idx => $entry) {

            if (!empty($entry['in'])) {

                $inStamp = $entry['in_stamp'];

                $base_date = date('Y-m-d', strtotime($pDate . ' +1 day'));
                $max_out = strtotime($base_date . ' ' . $morning_in) + (4 * 3600);
//             echo "<pre>";
// echo "IN Date: " . $pDate . "\n";
// echo "IN Time: " . date('Y-m-d H:i:s', $inStamp) . "\n";
// echo "OUT Time: " . date('Y-m-d H:i:s', $stamp) . "\n";
// echo "Max Out Time: " . date('Y-m-d H:i:s', $max_out) . "\n";
// echo "</pre>";

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
                                    }
                                    ?>

                                <div class="table-responsive">

                                    <table id="buttons-datatables"
                                        class="table table-sm table-bordered table-hover align-middle display">

                                        <thead class="table-light">

                                            <tr>

                                                <th>Unit Name</th>

                                                <?php
                                                    foreach ($showFields as $fid) {
                                                        if (!isset($fieldMap[$fid])) continue;
                                                        echo "<th>{$fieldMap[$fid]['label']}</th>";
                                                    }
                                                    ?>

                                                <th>Attendance</th>

                                                <th>In1</th>
                                                <th>Out1</th>
                                                <th>In2</th>
                                                <th>Out2</th>
                                                <th>In3</th>
                                                <th>Out3</th>
                                                <th>In4</th>
                                                <th>Out4</th>
                                                <th>In5</th>
                                                <th>Out5</th>
                                                <th>In6</th>
                                                <th>Out6</th>
                                                <th>In7</th>
                                                <th>Out7</th>
                                                <th>In8</th>
                                                <th>Out8</th>
                                                <th>In9</th>
                                                <th>Out9</th>
                                                <th>WH</th>
                                                <th>OT</th>
                                                <th>Status</th>
                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php

                                                $daysInMonth = date('t', strtotime($fromDate));

                                                foreach ($employees as $emp) {

                                                    $empId = $emp['emp_id'];

                                                    for ($d = 1; $d <= $daysInMonth; $d++) {

                                                        $date = date("Y-m-d", strtotime("$year-$month-$d"));
                                                        $punches = $punchMap[$empId][$date] ?? [];

                                                        echo "<tr>";
                                                        echo "<td>{$emp['unit_name']}</td>";

                                                        foreach ($showFields as $fid) {

                                                            if (!isset($fieldMap[$fid])) continue;

                                                            $key = $fieldMap[$fid]['key'];
                                                            $value = $emp[$key] ?? '-';

                                                            if ($fid == 9 && !empty($value)) {
                                                                $value = $obj->dateformatindia($value);
                                                            }

                                                            echo "<td>{$value}</td>";
                                                        }

                                                        echo "<td>" . date("d-M-Y", strtotime($date)) . "</td>";

                                                        for ($i = 0; $i < 9; $i++) {

                                                            $in  = $punches[$i]['in'] ?? '';
                                                            $out = $punches[$i]['out'] ?? '';

                                                            echo "<td>$in</td>";
                                                            echo "<td>$out</td>";
                                                        }
$wh     = $entryMap[$empId][$date]['working_hours'] ?? '';
$ot     = $entryMap[$empId][$date]['overtime'] ?? '';
$status = $entryMap[$empId][$date]['status'] ?? '';

                                                        echo "<td>$wh</td>";
                                                        echo "<td>$ot</td>";
                                                        echo "<td>$status</td>";

                                                        echo "</tr>";
                                                    }
                                                }

                                                ?>

                                        </tbody>

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

    function get_department(emp_id) {
        $.ajax({
            type: "POST",
            url: '',
            data: {

                emp_idd: emp_id,
            },
            success: function(data) {
                $('#department_id').html(data).trigger("change.select2");
            }
        });

    }
    </script>
</body>

</html>