<?php include("../adminsession.php");
$title = "Leave Balance Report Report";
$pagename = "leave_balance_report.php";
$module = "Search Attendance";
$submodule = "Leave Balance Report List";
$btn_name = "Search";
$keyvalue = 0;
$tblname = "employee_master";
$tblpkey = "emp_id";

$crit2 = "where 1=1";

if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit2 .= " and ml.department_id = '$department_id'";
    }
} else {
    $department_id = "";
};

if (isset($_GET['unit_id'])) {
    $unit_id = $obj->test_input($_GET['unit_id']);
    if ($unit_id != '') {
        $crit2 .= " and  ml.unit_id='$unit_id'";
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
                <?php if (isset($_GET['search'])) {   ?>
                    <div class="row mt-4 mb-4">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title mb-0"> <?= $submodule; ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $sql = "SELECT 
                                        ml.emp_id,
                                        ml.department_id,
                                        um.unit_name,

                                        SUM(CASE WHEN ml.leave_type='earning' THEN ml.total_leave ELSE 0 END) AS earning_total_leave,
                                        SUM(CASE WHEN ml.leave_type='earning' THEN ml.remining_leave ELSE 0 END) AS earning_remaining_leave,

                                        SUM(CASE WHEN ml.leave_type='weekly' THEN ml.total_leave ELSE 0 END) AS weekly_total_leave,
                                        SUM(CASE WHEN ml.leave_type='weekly' THEN ml.remining_leave ELSE 0 END) AS weekly_remaining_leave,

                                        e.emp_code,
                                        e.first_name,
                                        e.last_name,
                                        e.aadhar_no,
                                        e.basic_salary,
                                        e.date_of_joining,
                                        e.job_location,
                                        e.opening_balance,

                                        d.department_name,
                                        des.designation,
                                        g.grade_name,
                                        s.working_hour AS shift_hours

                                    FROM emp_monthly_leave ml

                                    LEFT JOIN employee_master e 
                                        ON e.emp_id = ml.emp_id

                                    LEFT JOIN unit_master um 
                                        ON ml.unit_id = um.unit_id

                                    LEFT JOIN department_master d 
                                        ON d.department_id = ml.department_id

                                    LEFT JOIN designation_master des 
                                        ON des.designation_id = e.designation_id

                                    LEFT JOIN grade_master g 
                                        ON g.grade_id = e.grade_id

                                    LEFT JOIN shift_master s 
                                        ON s.shift_id = e.shift_id

                                    $crit2

                                    GROUP BY ml.emp_id

                                    ORDER BY e.emp_code ASC
                                    ";

                                    $leaveRows = $obj->executequery($sql);

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
                                                        <th>Opening Leave</th>
                                                        <th>Total Due Earning Leave </th>

                                                        <th>Total Due Weekly Off</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $slno = 1;

                                                    $total_opening = 0;
                                                    $total_earning = 0;
                                                    $total_weekly = 0;
                                                    $currentDate = date("Y-m-d");

                                                    foreach ($leaveRows as $row) {
                                                        $total_opening += $row['opening_balance'];
                                                        $total_earning += $row['earning_remaining_leave'];
                                                        $total_weekly += $row['weekly_remaining_leave'];
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

                                                        // Opening balance
                                                        echo "<td>" . $row['opening_balance'] . "</td>";

                                                        // Earning Leave

                                                        echo "<td>" . $row['earning_remaining_leave'] . "</td>";



                                                        // Weekly Leave

                                                        echo "<td>" . $row['weekly_remaining_leave'] . "</td>";


                                                        echo "</tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr style="font-weight:bold; background:#f1f1f1;">
                                                        <td colspan="<?php echo 2 + count($showFields); ?>" align="right">Total</td>
                                                        <td><?php echo $total_opening; ?></td>
                                                        <td><?php echo $total_earning; ?></td>
                                                        <td><?php echo $total_weekly; ?></td>
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