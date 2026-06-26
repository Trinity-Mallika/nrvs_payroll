<?php include("../adminsession.php");
$title = "Manual Attendance Report";
$pagename = "manual_att_report.php";
$module = "Search Attendance";
$submodule = "Manual Attendance List";
$btn_name = "Search";
$keyvalue = 0;
$tblname = "attendance_entry";
$tblpkey = "attendance_id";

$crit2 = "where 1=1";
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
$totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$get_days = $obj->getDaysArray($month, $year);
$length = count($get_days);


$currentMonth = date('m');
$currentYear = date('Y');
$currentDay = date('d');

if ($month == $currentMonth && $year == $currentYear) {
    $effectiveDays = $currentDay; // current date tak
} else {
    $effectiveDays = $totalDaysInMonth; // poora month
}

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
                <?php if (!isset($_GET['search'])) {   ?>
                <div class="row">
                    <div class="col-lg-12">
                        <fieldset class="mt-2">
                            <form method="get">
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
                                                    onclick="return checkinputmaster('unit_id,year,month')"
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
                <?php } ?>
                <?php if (isset($_GET['search'])) {   ?>
                <div class="row mt-4 mb-4">
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?>
                                                <a href="manual_att_report.php"
                                                    class="float-end btn btn-primary btn-sm ms-4">Search Again</a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php
                                    $employees = $obj->executequery("SELECT e.emp_id,e.unit_id,e.department_id,e.is_esic,e.emp_code,e.first_name,e.last_name,e.mobile_no,e.aadhar_no,e.shift_id,
                                                        e.basic_salary,e.date_of_joining,e.job_location,g.grade_name,d.department_name,des.designation,s.working_hour AS shift_hours  FROM employee_master e

                                                    LEFT JOIN grade_master g
                                                        ON g.grade_id = e.grade_id

                                                    LEFT JOIN department_master d
                                                        ON d.department_id = e.department_id

                                                    LEFT JOIN designation_master des
                                                        ON des.designation_id = e.designation_id

                                                    LEFT JOIN shift_master s
                                                        ON s.working_hour = e.shift_id

                                                    $crit2 and e.unit_id = '$unitid' group by e.emp_id
                                                ");

                                    if (empty($employees)) {
                                        $employees = [];
                                    }
                                    $empIds = array_column($employees, 'emp_id');
                                    $empIdsStr = implode(',', $empIds);

                                    $fromDate = "$year-$month-01";
                                    $toDate   = date("Y-m-t", strtotime($fromDate));

                                    $currentDate = date("Y-m-d");

                                    $summaryRows = $obj->executequery("SELECT
                                        a.emp_id,
                                        a.attendance_date,
                                        a.attendance_status,
                                        a.prev_attendance_status,
                                        a.in_remark,
                                        a.out_remark,
                                        a.createdate,
                                        a.entry_type,
                                        a.machineid,
                                        a.entry_type_out,
                                        a.intime,
                                        a.outtime,
                                        a.lastupdated,
                                        u.username as u_username,
                                        u.fullname as u_fullname,
                                        u.mobile as u_mobile,
                                        u.email as u_email,
                                        cu.username as c_username,
                                        cu.fullname as c_fullname,
                                        cu.mobile as c_mobile,
                                        cu.email as c_email
                                    FROM attendance_entry a
                                    LEFT JOIN user u
                                        ON u.userid = a.updateby
                                    LEFT JOIN user cu
                                        ON cu.userid = a.createdby
                                    WHERE a.emp_id IN ($empIdsStr) and a.unit_id = '$unitid'
                                    AND a.attendance_date BETWEEN '$fromDate' AND '$toDate'
                                    AND (a.entry_type='manual' OR a.entry_type_out='manual')
                                    ORDER BY a.emp_id, a.attendance_date
                                ");
                                    $summary = [];
                                    foreach ($summaryRows as $row) {
                                        $summary[$row['emp_id']][] = $row;
                                    }


                                    ?>
                                <!-- floating scrollbar -->
                                <div class="auto-scroll-wrapper">
                                    <div class="table-responsive">
                                        <table id="buttons-datatables"
                                            class="table table-sm table-bordered table-hover align-middle display ">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>S.No.</th>

                                                    <?php
                                                        foreach ($showFields as $fid) {
                                                            if (!isset($fieldMap[$fid])) continue;
                                                            echo "<th>{$fieldMap[$fid]['label']}</th>";
                                                        }
                                                        ?>
                                                    <th>Date </th>
                                                    <th>Original Status</th>
                                                    <th>Updated Status</th>
                                                    <th>Punch In</th>
                                                    <th>IN Type</th>
                                                    <th>Punch Out</th>
                                                    <th>Out Type</th>
                                                    <th>Machine ID</th>
                                                    <th>IN/OUT Reason</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $slno = 1;
                                                    $currentDate = date("Y-m-d");

                                                    foreach ($employees as $emp) {

                                                        $empId = $emp['emp_id'];

                                                        if (!isset($summary[$empId])) continue;

                                                        foreach ($summary[$empId] as $att) {

                                                            echo "<tr id='tr_{$att['emp_id']}' data-details='
                                                            <div style=\"background:#dafced; padding:4px;\">

                                                            " . (!empty($att['c_fullname']) ? "
                                                                Added by (
                                                                User: {$att['c_fullname']},
                                                                Username: {$att['c_username']},
                                                                Mobile: {$att['c_mobile']},
                                                                Email: {$att['c_email']},
                                                                Date: {$att['createdate']}
                                                                )<br>
                                                            " : "") . "

                                                            " . (!empty($att['u_fullname']) ? "
                                                                Last Edited by (
                                                                User: {$att['u_fullname']},
                                                                Username: {$att['u_username']},
                                                                Mobile: {$att['u_mobile']},
                                                                Email: {$att['u_email']},
                                                                Date: {$att['lastupdated']}
                                                                )
                                                            " : "") . "

                                                            </div>
                                                        '>";
                                                         echo "<td class='details-control text-center'>
        <span class='me-1'>" . $slno++ . "</span>
        <i class='ri-add-circle-fill text-primary'></i>
      </td>";


                                                            foreach ($showFields as $fid) {
                                                                if (!isset($fieldMap[$fid])) continue;

                                                                $key = $fieldMap[$fid]['key'];
                                                                $value = $emp[$key] ?? '-';

                                                                if ($fid == 9 && !empty($value)) {
                                                                    $value = $obj->dateformatindia($value);
                                                                }

                                                                echo "<td>{$value}</td>";
                                                            }

                                                            echo "<td>" .$obj->dateformatindia( $att['attendance_date'])  . "</td>";
                                                            echo "<td>" . $att['prev_attendance_status'] . "</td>";
                                                            echo "<td>" . $att['attendance_status'] . "</td>";
                                                            echo "<td>" . $att['intime'] . "</td>";
                                                            echo "<td>" . $att['entry_type'] . "</td>";
                                                            echo "<td>" . $att['outtime'] . "</td>";
                                                            echo "<td>" . $att['entry_type_out'] . "</td>";
                                                            echo "<td>" . $att['machineid'] . "</td>";
                                                            echo "<td>
                                                                <strong>Reason:</strong> " . ($att['in_remark'] ?: '-') . "<br>

                                                            </td>";


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
    $(document).ready(function() {
        var table = $('#buttons-datatables').DataTable();
        $('#buttons-datatables tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                var details = tr.data('details');
                row.child(details).show();
                tr.addClass('shown');
            }
        });
    });
    </script>
</body>

</html>