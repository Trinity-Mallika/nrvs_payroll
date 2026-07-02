<?php include("../adminsession.php");
$title = "Misspunch Report";
$pagename = "misspunch_report.php";
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

if (isset($_GET['attendance_date'])) {
    $attendance_date = $obj->test_input($_GET['attendance_date']);
} else {
    $attendance_date = date('Y-m-d');
}

/* 👉 GET MONTH & YEAR FROM SELECTED DATE */
$month = date('m', strtotime($attendance_date));
$year  = date('Y', strtotime($attendance_date));
$get_days = $obj->getDaysArray($month, $year);
$length = count($get_days);

if (isset($_GET['att_action'])) {
    $att_action = $obj->test_input($_GET['att_action']);
} else {
    $att_action = "misspunch";
}


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
                                                        class="text-danger fw-bold"> </span></label>
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
                                              <div class="col-lg-3 mb-3">
                                                <label for="emp_id" class="form-label">Attendence Date<span class="text-danger fw-bold">*</span></label>
                                                <input type="date" name="attendance_date" id="attendance_date" class="form-control form-control-sm" value="<?= $attendance_date ?>">
                                            </div>
                                            
                                             <div class="col-lg-3 mb-3">
                                            <label for="att_action" class="form-label">Action<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select" name="att_action" id="att_action">
                                                <option value="misspunch">Misspunch</option>
                                                <option value="Incomplete">Incomplete</option>
                                                <option value="Present">Present</option>
                                                <option value="Earning Leave">Earning Leave</option>
                                                <option value="Weekly Leave">Weekly Leave</option>
                                                <option value="Half Day">Half Day</option>
                                                 
                                            </select>
                                            <script>
                                                document.getElementById('att_action').value =
                                                    '<?= $att_action; ?>';
                                            </script>
                                        </div>

                                            <div class="col-md-3 mt-4 ">
                                                <input type="submit" class="btn btn-primary add-btn"
                                                    onclick="return checkinputmaster('year,month')"
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
// echo "<pre>";
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

                                <div class="table-responsive">

                                    <table id="buttons-datatables"
                                        class="table table-sm table-bordered table-hover align-middle display">

                                        <thead class="table-light">

                                            <tr>

                                                <th>Unit Name</th>

                                                  <th>Emp Code</th>
                                                    <th>Emp Name</th>
                                                    <th>Department</th>
                                                    <th>Designation</th>
                                                    <th>Attendance Date</th>
  <th>Status</th>

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
                                               
                                                <th>WH</th>
                                                <th>OT</th>
                                              
                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php

                                                $daysInMonth = date('t', strtotime($fromDate));

                                                foreach ($employees as $emp) {

                                                    $empId = $emp['emp_id'];

                                                
                                                        $date = date("Y-m-d", strtotime($attendance_date));
                                                        $punches = $punchMap[$empId][$date] ?? [];
                                                        $wh     = $entryMap[$empId][$date]['working_hours'] ?? '';
$ot     = $entryMap[$empId][$date]['overtime'] ?? '';
 $status = $entryMap[$empId][$date]['status'] ?? '';

    // ✅ MISS PUNCH override (TOP PRIORITY)
    if (isset($missPunchMap[$empId][$date])) {
        $status = 'Miss Punch';
    }

    /* ================= FILTER APPLY ================= */

    if (!empty($att_action)) {

        if ($att_action == 'misspunch') {
            if ($status != 'Miss Punch') {
                continue;
            }
        } else {
            if ($status != $att_action) {
                continue;
            }
        }
    }
                                                        echo "<tr>";
                                                        echo "<td>{$emp['unit_name']}</td>";
                                                        echo "<td>{$emp['emp_code']}</td>";
                                                        echo "<td>{$emp['first_name']}</td>";
                                                        echo "<td>{$emp['department_name']}</td>";
                                                        echo "<td>{$emp['designation']}</td>";                                    
                                                        echo "<td>" . date("d-M-Y", strtotime($date)) . "</td>";
                                                         echo "<td>$status</td>";
                                                        for ($i = 0; $i < 6; $i++) {

                                                            $in  = $punches[$i]['in'] ?? '';
                                                            $out = $punches[$i]['out'] ?? '';

                                                            echo "<td>$in</td>";
                                                            echo "<td>$out</td>";
                                                        }
 

                                                        echo "<td>$wh</td>";
                                                        echo "<td>$ot</td>";
                                                       

                                                        echo "</tr>";

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
            // $('#example').DataTable();
            $(".chosen-select").select2({
                width: '100%',
                search_contains: true
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