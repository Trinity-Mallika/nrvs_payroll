<?php include("../adminsession.php");
$pagename = "emp_turnover_report.php";
$title = "Employee Turnover Report";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Employee Turnover Report";
$submodule = "Employee Turnover Report";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = '';

if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and department_id = '$department_id'";
    }
} else {
    $department_id = "";
};

if (isset($_GET['unit_id'])) {
    $unit_id = $obj->test_input($_GET['unit_id']);
    if ($unit_id != '') {
        $crit .= " and unit_id = '$unit_id'";
    }
} else {
    $unit_id = "";
};
$unit_name = $obj->getvalfield("unit_master", "unit_name", "unit_id='$unit_id'");
$month = date('m');
$year = date('Y');
$year_month = "";
if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = $obj->test_input($_GET['month']);

    $year = $obj->test_input($_GET['year']);
}

$joining_count = $obj->getvalfield(
    "employee_master",
    "COUNT(emp_id)",
    "MONTH(date_of_joining)='$month' AND YEAR(date_of_joining)='$year' $crit"
);

// Exit Count
$exit_count = $obj->getvalfield(
    "employee_exit",
    "COUNT(exit_id)",
    "MONTH(resignation_date)='$month' AND YEAR(resignation_date)='$year' $crit AND is_approved='1'"
);

// Total Employee
$total_emp = $obj->getvalfield(
    "employee_master",
    "COUNT(emp_id)",
    "1=1 $crit"
);

// Attrition
$attrition = ($total_emp > 0) ? round(($exit_count / $total_emp) * 100, 2) : 0;

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
    .table-borderless tr td {
        border: 0px !important;
        padding-bottom: 0px;
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
                <?php include('inc/alert.php'); ?>
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
                                                <strong><label for="Month">Month<span class="text-danger fw-bold"> </span></label></strong></br>
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
                                            <div class="col-lg-3 col-12">
                                                <label for="year" class="form-label">Year<span class="text-danger fw-bold"> </span></label>
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


                                            <div class="col-md-3 mt-4 ">
                                                <input type="submit" class="btn btn-primary add-btn" onclick="return checkinputmaster('month,year')" name="search" value="Search">
                                                <a href="<?php echo $pagename; ?>" class="btn btn-danger" name="reset" id="reset">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                    <div class="row mt-3">

                        <!-- Total Employees -->
                        <div class="col-lg-3">
                            <a href="employee_report.php?unit_id=<?= $unit_id ?>&department_id=<?= $department_id ?>&submit=Search" class="text-decoration-none" target="_blank">
                                <div class="card border-0 shadow-sm bg-info h-100">
                                    <div class="card-body text-center">

                                        <h6 class="text-white">Total Employees</h6>

                                        <h1 class="text-white fw-bold">
                                            <?= $total_emp ?>
                                        </h1>

                                        <small class="text-white">
                                            Current Workforce
                                        </small>

                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Employees Joined -->
                        <div class="col-lg-3">
                            <a href="employee_report.php?unit_id=<?= $unit_id ?>&department_id=<?= $department_id ?>&month=<?= $month ?>&year=<?= $year ?>&submit=Search" class="text-decoration-none" target="_blank">
                                <div class="card border-0 shadow-sm bg-success h-100">
                                    <div class="card-body text-center">

                                        <h6 class="text-white">Employees Joined</h6>

                                        <h1 class="text-white fw-bold">
                                            <?= $joining_count ?>
                                        </h1>

                                        <small class="text-white">
                                            <?= date("F", strtotime($year . "-" . $month . "-01")) ?> <?= $year ?>
                                        </small>

                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Employees Exit -->
                        <div class="col-lg-3">
                            <a href="emp_separation_list.php?unit_id=<?= $unit_id ?>&month=<?= $month ?>&year=<?= $year ?>&submit=Search" class="text-decoration-none" target="_blank">
                                <div class="card border-0 shadow-sm bg-danger h-100">
                                    <div class="card-body text-center">
                                        <h6 class="text-white">Employees Exit</h6>
                                        <h1 class="text-white fw-bold">
                                            <?= $exit_count ?>
                                        </h1>
                                        <small class="text-white">
                                            <?= date("F", strtotime($year . "-" . $month . "-01")) ?> <?= $year ?>
                                        </small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Attrition Rate -->
                        <div class="col-lg-3">
                            <div class="card border-0 shadow-sm bg-warning h-100">
                                <div class="card-body text-center">

                                    <h6 class="text-dark">Attrition Rate</h6>

                                    <h1 class="text-dark fw-bold">
                                        <?= $attrition ?>%
                                    </h1>

                                    <small class="text-dark">
                                        Employee Attrition
                                    </small>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    </div>
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            $(".chosen-select").select2({
                width: '100%',
                search_contains: true
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