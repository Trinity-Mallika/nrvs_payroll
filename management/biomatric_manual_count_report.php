<?php include("../adminsession.php");
$pagename = "biomatric_manual_count_report.php";
$title = "Biometric vs Manual Attendance Count";
$tblname = "attendance_entry";
$tblpkey = "attendance_id";
$module = "Biometric vs Manual Attendance Count";
$submodule = "Biometric vs Manual Attendance Count";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit2 = '';
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit2 .= " and ae.department_id = '$department_id'";
    }
} else {
    $department_id = "";
};

if (isset($_GET['unit_id'])) {
    $unit_id = $obj->test_input($_GET['unit_id']);
    if ($unit_id != '') {
        $crit2 .= " and ae.unit_id = '$unit_id'";
    }
} else {
    $unit_id = $unitid;
};
 
$month = date('m');
$year = date('Y');
$year_month = "";
if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = $obj->test_input($_GET['month']);

    $year = $obj->test_input($_GET['year']);
}

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
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

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
                    <?php if (!isset($_GET['search'])) { ?>
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
                                                <label for="unit_id" class="form-label">Unit Name<span
                                                        class="text-danger fw-bold"></span></label>
                                                <select class="form-select chosen-select" name="unit_id" id="unit_id"
                                                    onchange="get_department(this.value);">
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
                                                <label for="department_id" class="form-label">Department Name<span
                                                        class="text-danger fw-bold"></span></label>
                                                <select class="form-select chosen-select" name="department_id"
                                                    id="department_id">
                                                    <option value="">All</option>

                                                </select>

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

                                            <div class="col-md-3 mt-4 ">
                                                <input type="submit" class="btn btn-primary add-btn"
                                                    onclick="return checkinputmaster('month,year')" name="search"
                                                    value="Search">
                                                <a href="<?php echo $pagename; ?>" class="btn btn-danger" name="reset"
                                                    id="reset">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                    <?php } ?>
                    <?php if (isset($_GET['search'])) { ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> </h5>
                                            <h5 class="mb-0 fw-bold text-primary">
                                                <?= strtoupper(date('F', mktime(0, 0, 0, $month, 1))) . " - " . $year; ?>
                                            </h5>

                                            <a href="<?php echo $pagename; ?>" class="btn btn-primary btn-sm">
                                                Search Again
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive auto-scroll-wrapper">
                                    <table id="buttons-datatables" class="display table table-sm table-bordered"
                                        style="width:100%">
                                        <thead>
                                            <tr class="table-primary">
                                                <th>Sr No.</th>
                                                <th>Department</th>
                                                <th>Punch By Machine</th>
                                                <th>Punch By Manual</th>
                                                <th>view details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $slno = 1;

                                                $total_machine = 0;
                                                $total_manual = 0;
                                                $res = $obj->executequery("SELECT
                                                ae.unit_id,
                                                ae.department_id,
                                                um.unit_name,
                                                dm.department_name,

                                                COUNT(DISTINCT CASE
                                                    WHEN ae.entry_type = 'machine'
                                                        OR ae.entry_type_out = 'machine'
                                                    THEN ae.emp_id
                                                END) AS machine_emp_count,

                                                COUNT(DISTINCT CASE
                                                    WHEN ae.entry_type = 'manual'
                                                        OR ae.entry_type_out = 'manual'
                                                    THEN ae.emp_id
                                                END) AS manual_emp_count

                                            FROM attendance_entry ae

                                            LEFT JOIN employee_master em
                                                ON ae.emp_id = em.emp_id

                                            LEFT JOIN department_master dm
                                                ON em.department_id = dm.department_id

                                            LEFT JOIN unit_master um
                                                ON ae.unit_id = um.unit_id

                                            WHERE ae.month = '$month'
                                            AND ae.year = '$year'
                                            $crit2

                                            GROUP BY ae.department_id
                                            ORDER BY dm.department_name ASC
                                                    ");
                                                foreach ($res as $row) {

                                                   
                                                ?>
                                            <tr>
                                                <td>
                                                    <?= $slno++ ?>
                                                </td>
                                                <td><?= $row['department_name'] ?></td>
                                                <td class="text-end">
                                                    <?= $row['machine_emp_count'] ?>
                                                </td>

                                                <td class="text-end">
                                                    <?= $row['manual_emp_count'] ?>
                                                </td>
                                                <td class="text-center"> <a target="_blank"
                                                        href="biomatric_manual_att_report.php?unit_id=<?= $row['unit_id'] ?>&department_id=<?= $row['department_id'] ?>&month=<?= $month ?>&year=<?= $year ?>&search=Search">
                                                        view
                                                    </a></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" class="text-end"><b>Grand Total</b></td>

                                                <td class="text-end">

                                                </td>

                                                <td class="text-end">

                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>


                                </div>
                            </div>

                        </div>
                    </div>
                    <?php } ?>
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