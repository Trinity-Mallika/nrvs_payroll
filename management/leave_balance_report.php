<?php include("../adminsession.php");
$title = "Leave Balance Report Report";
$pagename = "leave_balance_report.php";
$module = "Search Attendance";
$submodule = "Leave Balance Report List";
$btn_name = "Search";
$keyvalue = 0;
$tblname = "employee_master";
$tblpkey = "emp_id";

$crit2 = " ";
$month=(int)date('n');
$year=(int)date('Y');

if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit2 .= " and e.department_id = '$department_id'";
    }
} else {
    $department_id = "";
};

if (isset($_GET['unit_id'])) {
    $unit_id = $obj->test_input($_GET['unit_id']);
    if ($unit_id != '') {
        $crit2 .= " and e.unit_id='$unit_id'";
    }
} else {
    $unit_id = $unitid;
};

if (isset($_GET['action'])) {
    $action = addslashes(trim($_GET['action']));
} else {
    $action = "";
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

if (isset($_POST['department_idd'])) {
    $department_id = $_POST['department_idd'];
    $unit_id = $_POST['unit_id'];
    $options = "<option value=''>Please Select</option>";
    $selected = "";
    if ($unit_id != "" || $unit_id > 0) {

        $res = $obj->executequery("Select * from department_master where unit_id='$unit_id' order by department_name asc");

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
                <?php include('inc/bredcrum.php') ?>
                <?php if (!isset($_GET['search'])) { ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <fieldset >
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
                                                    <label for="unit_id" class="form-label">Unit Name<span class="text-danger fw-bold"></span></label>
                                                    <select class="form-select chosen-select" name="unit_id" id="unit_id" onchange="get_department(this.value);">
                                                        <option value="">All</option>
                                                        <?php $res = $obj->executequery("Select * from unit_master order by unit_name asc");
                                                        foreach ($res as $key) {
                                                            echo "<option value='" . $key['unit_id'] . "'>" . $key['unit_name'] . "</option>";
                                                        } ?>
                                                    </select>
                                                    <script>
                                                        document.getElementById('unit_id').value = '<?= $unit_id; ?>';
                                                    </script>
                                                </div>
                                                <div class="col-lg-3 mb-3">
                                                    <label for="department_id" class="form-label">Department Name<span class="text-danger fw-bold"></span></label>
                                                    <select class="form-select chosen-select" name="department_id" id="department_id">
                                                        <option value="">All</option>

                                                    </select>

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
                                                    <input type="submit" class="btn btn-primary add-btn" name="search" value="Search">
                                                    <a href="<?php echo $pagename; ?>" class="btn btn-danger" name="reset" id="reset">Reset</a>
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
                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title mb-0"><?php echo $submodule; ?> </h5>
                                                 

                                                <a href="<?php echo $pagename; ?>" class="btn btn-primary btn-sm">
                                                    Search Again
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php

                                  
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
                                        um.unit_name,
                                        s.working_hour AS shift_hours

                                    FROM employee_master e

                                    LEFT JOIN grade_master g 
                                        ON g.grade_id = e.grade_id

                                    LEFT JOIN department_master d 
                                        ON d.department_id = e.department_id

                                    LEFT JOIN unit_master um
                                        ON e.unit_id = um.unit_id

                                    LEFT JOIN designation_master des 
                                        ON des.designation_id = e.designation_id

                                    LEFT JOIN shift_master s 
                                        ON s.shift_id = e.shift_id
                                 
                                    WHERE 
                                        e.unit_id = '$unitid'   
                                        AND e.is_active = '1'
                                      AND (e.resign_status != '1' OR (e.resign_status = '1' AND e.last_working_date >= CURDATE()))
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

                                $earningLeaveRows = $obj->executequery("
                                SELECT 
                                    emp_id,

                                    SUM(
                                        CASE
                                            WHEN attendance_status IN ('Earning Leave', 'Leave') THEN 1
                                            WHEN attendance_status IN ('Half Earning Leave', 'Half Leave') THEN 0.5
                                            ELSE 0
                                        END
                                    ) used_leave

                                FROM attendance_entry

                                WHERE sessionid='$sessionid'

                                AND (
                                    year < '$year'
                                    OR (year='$year' AND month <= '$month')
                                )

                                GROUP BY emp_id

                            ");

                            $usedEarnMap = [];

                            foreach($earningLeaveRows as $r){

                                $usedEarnMap[$r['emp_id']] = $r['used_leave'];
                            }

                            $earningUploadRows = $obj->executequery("
                            SELECT 
                                emp_id,

                                SUM(total_leave) total_leave

                            FROM emp_monthly_leave

                            WHERE leave_type='earning'
                            AND sessionid='$sessionid'

                            AND (
                                year < '$year'
                                OR (year='$year' AND month < '$month')
                            )

                            GROUP BY emp_id

                        ");

                        $earningUploadMap = [];

                        foreach($earningUploadRows as $r){

                            $earningUploadMap[$r['emp_id']] = $r['total_leave'];
                        }

                         $current_date  = date("Y-m-d", strtotime("$year-$month-01"));

                        $current_month = (int)date("m", strtotime($current_date));
                        $current_year  = (int)date("Y", strtotime($current_date));

                        $prev_month    = (int)date("m", strtotime("$current_date -1 month"));
                        $prev_year     = (int)date("Y", strtotime("$current_date -1 month"));

                        $extraUploadRows = $obj->executequery("
                                SELECT 
                                    emp_id,
                                    COALESCE(SUM(total_leave),0) total_extra_off

                                FROM emp_monthly_leave

                                WHERE leave_type='eoff'

                                AND (
                                    (month='$current_month' AND year='$current_year')
                                    OR
                                    (month='$prev_month' AND year='$prev_year')
                                )

                                GROUP BY emp_id
                            ");

                            $extraUploadMap = [];

                            foreach($extraUploadRows as $r){

                                $extraUploadMap[$r['emp_id']] = $r['total_extra_off'];
                            }
                            /* ================= USED EXTRA OFF ================= */

                            $extraUsedRows = $obj->executequery("
                                SELECT 
                                    emp_id,

                                    COALESCE(SUM(
                                        CASE 
                                            WHEN attendance_status='Extra Off' THEN 1
                                            WHEN attendance_status='Half Extra Off' THEN 0.5
                                            ELSE 0
                                        END
                                    ),0) used_extra

                                FROM attendance_entry

                                WHERE (
                                    (
                                        MONTH(attendance_date) = '$current_month'
                                        AND YEAR(attendance_date) = '$current_year'
                                    )
                                    OR
                                    (
                                        MONTH(attendance_date) = '$prev_month'
                                        AND YEAR(attendance_date) = '$prev_year'
                                    )
                                )

                                AND attendance_status IN ('Extra Off','Half Extra Off')

                                GROUP BY emp_id
                            ");

                            $extraUsedMap = [];

                            foreach($extraUsedRows as $r){

                                $extraUsedMap[$r['emp_id']] = $r['used_extra'];
                            }

                            /* ================= USED C-OFF ================= */

                            $coffUploadRows = $obj->getEmpUploadedCoff(
                                $empIdsStr,
                                $sessionid,
                                $month,
                                $year
                            );

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
                            $coffUploadMap = [];

                            foreach ($coffUploadRows as $r) {

                                $coffUploadMap[$r['emp_id']] = $r['total_leave'];
                            }
 
                                    ?>
                                    <!-- floating scrollbar -->
                                    <div class="auto-scroll-wrapper">
                                        <div class="table-responsive">
                                            <table id="buttons-datatables" class="table table-sm table-bordered table-hover align-middle display ">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Unit Name</th>
                                                        <?php
                                                        foreach ($showFields as $fid) {
                                                            if (!isset($fieldMap[$fid])) continue;
                                                            echo "<th>{$fieldMap[$fid]['label']}</th>";
                                                        }
                                                        ?>
                                                        <th>Total Leave</th>
                                                        <th>Used Leave</th>
                                                        <th>Balance Leave</th>
                                                        <th>Total C Off</th>
                                                        <th>Used C Off</th>
                                                        <th>Balance C Off</th>
                                                        <th>Total Extra Off</th>
                                                        <th>Used Extra Off</th>
                                                        <th>Balance Extra Off</th>  
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $slno = 1;

                                                    $total_earning_upload = 0;
                                                    $total_earning_used   = 0;
                                                    $total_earning_bal    = 0;

                                                    $total_coff_upload = 0;
                                                    $total_coff_used   = 0;
                                                    $total_coff_bal    = 0;

                                                    $total_extra_upload = 0;
                                                    $total_extra_used   = 0;
                                                    $total_extra_bal    = 0; 

                                                    foreach ($employees as $row) {
                                                        $empId = $row['emp_id'];
                                                        $total_earning_leave =($earningUploadMap[$empId] ?? 0) -($usedEarnMap[$empId] ?? 0); 
                                                        $extra_off = [ 
                                                            'balance' =>
                                                                ($extraUploadMap[$empId] ?? 0)
                                                                -
                                                                ($extraUsedMap[$empId] ?? 0)
                                                        ];

                                                        $prev_coff =  ($coffUploadMap[$empId] ?? 0)-($usedCoffMap[$empId] ?? 0);

                                                        $total_earning_upload += ($earningUploadMap[$empId] ?? 0);
                                                        $total_earning_used   += ($usedEarnMap[$empId] ?? 0);
                                                        $total_earning_bal    += $total_earning_leave;

                                                        $total_coff_upload += ($coffUploadMap[$empId] ?? 0);
                                                        $total_coff_used   += ($usedCoffMap[$empId] ?? 0);
                                                        $total_coff_bal    += $prev_coff;

                                                        $total_extra_upload += ($extraUploadMap[$empId] ?? 0);
                                                        $total_extra_used   += ($extraUsedMap[$empId] ?? 0);
                                                        $total_extra_bal    += $extra_off['balance'];
                                                      
                                                        echo "<tr>";
                                                        echo "<td>" . $slno++ . "</td>";
                                                        echo "<td>" . $row['unit_name'] . "</td>";

                                                        foreach ($showFields as $fid) {

                                                            if (!isset($fieldMap[$fid])) continue;

                                                            $key = $fieldMap[$fid]['key'];
                                                            $value = $row[$key] ?? '-';

                                                            if ($fid == 9 && !empty($value)) {
                                                                $value = $obj->dateformatindia($value);
                                                            }

                                                            echo "<td>{$value}</td>";
                                                        }
                                                       
                                                        echo "<td class='fw-bold'>" . ($earningUploadMap[$empId] ?? 0) . "</td>";
                                                        echo "<td class='text-danger fw-bold'>" . ($usedEarnMap[$empId] ?? 0) . "</td>";
                                                        echo "<td class='text-success fw-bold'>" . $total_earning_leave . "</td>";

                                                        echo "<td class='fw-bold'>" . ($coffUploadMap[$empId] ?? 0) . "</td>";
                                                        echo "<td class='text-danger fw-bold'>" . ($usedCoffMap[$empId] ?? 0) . "</td>";
                                                        echo "<td class='text-success fw-bold'>" . $prev_coff . "</td>";

                                                        echo "<td class='fw-bold'>" . ($extraUploadMap[$empId] ?? 0) . "</td>";
                                                        echo "<td class='text-danger fw-bold'>" . ($extraUsedMap[$empId] ?? 0) . "</td>";
                                                        echo "<td class='text-success fw-bold' >" . $extra_off['balance'] . "</td>";
                                                      
                                                        echo "</tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr style="font-weight:bold; background:#f1f1f1;">
                                                        <td colspan="<?= 2 + count($showFields); ?>" class="text-end">
                                                            Total
                                                        </td>

                                                        <td class='fw-bold'><?= number_format($total_earning_upload, 2) ?></td>
                                                        <td class='text-danger fw-bold'><?= number_format($total_earning_used, 2) ?></td>
                                                        <td class='text-success fw-bold'><?= number_format($total_earning_bal, 2) ?></td>

                                                        <td class='fw-bold'><?= number_format($total_coff_upload, 2) ?></td>
                                                        <td class='text-danger fw-bold'><?= number_format($total_coff_used, 2) ?></td>
                                                        <td class='text-success fw-bold'><?= number_format($total_coff_bal, 2) ?></td>

                                                        <td class='fw-bold'><?= number_format($total_extra_upload, 2) ?></td>
                                                        <td class='text-danger fw-bold'><?= number_format($total_extra_used, 2) ?></td>
                                                        <td class='text-success fw-bold'><?= number_format($total_extra_bal, 2) ?></td>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }

                ?>
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
            get_department('<?= $unit_id ?>', '<?= $department_id ?>');
        });


        function get_department(unit_id, department_id = 0) {
            $.ajax({
                type: "POST",
                url: '',
                data: {
                    department_idd: department_id,
                    unit_id: unit_id,
                },
                success: function(data) {
                    $('#department_id').html(data).trigger("change.select2");
                }
            });

        }
    </script>
</body>

</html>