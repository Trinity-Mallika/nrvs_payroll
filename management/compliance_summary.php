<?php include("../adminsession.php");
$pagename = "compliance_summary.php";
$title = "Compliance Summary (PF / ESIC)";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Compliance Summary (PF / ESIC)";
$submodule = "Compliance Summary (PF / ESIC)";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit2 = '';
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit2 .= " and ss.department_id = '$department_id'";
    }
} else {
    $department_id = "";
};

if (isset($_GET['unit_id'])) {
    $unit_id = $obj->test_input($_GET['unit_id']);
    if ($unit_id != '') {
        $crit2 .= " and ss.unit_id = '$unit_id'";
    }
} else {
    $unit_id = $unitid;
};
$unit_name = $obj->getvalfield("unit_master", "unit_name", "unit_id='$unit_id'");
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

$slabs = $obj->executequery("SELECT
    ss.basic_percent,
    ss.hra_percent,
    ss.pf_per,
    ss.esic_per,
    ss.pf_emp_per,
    ss.esic_emp_per,
    s.heading as slab_name
FROM salary_slab_master ss
LEFT JOIN salary_slab s ON s.slab_id = ss.slab_id
");


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
                                                    onclick="return checkinputmaster('year,month')" name="search"
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
                                <div class="auto-scroll-wrapper">
                                    <div class="table-responsive">
                                        <table id="buttons-datatables" class="display table table-sm table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>Sr No.</th>
                                                    <th>Unit Name</th>
                                                    
                                                    <th>Emp Code</th>
                                                    <th>Emp Name</th>
                                                    <th>Department</th>
                                                    <th>Basic Salary</th>
                                                    <th>Increament</th>
                                                    <th>PF Wages</th>
                                                    <th>Emp PF</th>
                                                    <th>Employer PF</th>
                                                    <th>Emp ESIC</th>
                                                    <th>ESIC Employer </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $slno = 1;
                                                    $gt_pf_wages = 0;
                                                    $gt_basic_salary = 0;
                                                    $gt_increament = 0;
                                                    $gt_pf_emp = 0;
                                                    $gt_pf_employer = 0;
                                                    $gt_esic_emp = 0;
                                                    $gt_esic_employer = 0;
                                                    $res = $obj->executequery("SELECT
                                                        um.unit_name,
                                                        dm.department_name,
                                                        em.emp_code,
                                                        em.first_name,

                                                        ss.pf_paid_basic,
                                                        ss.esic_paid_basic,
                                                        ss.basic_salary,
                                                        ss.increment,
                                                        ss.pf_emp,
                                                        ss.pf_employer,
                                                        ss.esic_emp,
                                                        ss.esic_employer

                                                        FROM salary_structure ss

                                                        LEFT JOIN employee_master em
                                                        ON ss.emp_id = em.emp_id

                                                        LEFT JOIN department_master dm
                                                        ON ss.department_id = dm.department_id

                                                        LEFT JOIN unit_master um
                                                        ON ss.unit_id = um.unit_id

                                                        WHERE ss.month='$month'
                                                        AND ss.year='$year' $crit2 AND (ss.pf_emp > 0 OR ss.esic_emp > 0)

                                                        ORDER BY dm.department_name ASC
                                                        ");
                                                    foreach ($res as $row) {
                                                        $gt_pf_wages += $row['pf_paid_basic'];
                                                        $gt_pf_emp += $row['pf_emp'];
                                                        $gt_pf_employer += $row['pf_employer'];
                                                        $gt_esic_emp += $row['esic_emp'];
                                                        $gt_esic_employer += $row['esic_employer'];
                                                        $gt_basic_salary += $row['basic_salary'];
                                                        $gt_increament += $row['increment'];
                                                    ?>
                                                <tr>
                                                    <td><?= $slno++ ?></td>
                                                    <td><?= $row['unit_name'] ?></td>
                                                 
                                                    <td><?= $row['emp_code'] ?></td>
                                                    <td><?= $row['first_name'] ?></td>
                                                    <td><?= $row['department_name'] ?></td>
                                                    <td class="text-end"><?= number_format($row['basic_salary'], 2) ?>
                                                    </td>
                                                    <td class="text-end"><?= number_format($row['increment'], 2) ?></td>
                                                    <td class="text-end"><?= number_format($row['pf_paid_basic'], 2) ?>
                                                    </td>
                                                    <td class="text-end"><?= number_format($row['pf_emp'], 2) ?></td>
                                                    <td class="text-end"><?= number_format($row['pf_employer'], 2) ?>
                                                    </td>
                                                    <td class="text-end"><?= number_format($row['esic_emp'], 2) ?></td>
                                                    <td class="text-end"><?= number_format($row['esic_employer'], 2) ?>
                                                    </td>
                                                </tr>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr style="font-weight:bold;background:#f1f1f1;">

                                                    <td colspan="5" class="text-end">Grand Total</td>

                                                    <td class="text-end"><?= number_format($gt_basic_salary, 2) ?></td>
                                                    <td class="text-end"><?= number_format($gt_increament, 2) ?></td>
                                                    <td class="text-end"><?= number_format($gt_pf_wages, 2) ?></td>

                                                    <td class="text-end"><?= number_format($gt_pf_emp, 2) ?></td>

                                                    <td class="text-end"><?= number_format($gt_pf_employer, 2) ?></td>

                                                    <td class="text-end"><?= number_format($gt_esic_emp, 2) ?></td>

                                                    <td class="text-end"><?= number_format($gt_esic_employer, 2) ?></td>

                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="alert alert-info mt-4" style="font-size:14px;">
                                    <strong>Notes:</strong><br><br>

                                    <div class="row">
                                        <?php foreach ($slabs as $slab) { ?>

                                        <div class="col-md-6 mb-3">
                                            <div
                                                style="background:#fff; padding:10px; border-radius:5px; border:1px solid #ddd;">

                                                <b>Salary Slab: <?= $slab['slab_name'] ?></b><br>

                                                Basic: <?= $slab['basic_percent'] ?>% |
                                                HRA: <?= $slab['hra_percent'] ?>% <br>

                                                PF:
                                                ( Employee: <?= $slab['pf_per'] ?>%,
                                                Employer: <?= $slab['pf_emp_per'] ?>% ) <br>

                                                ESIC:
                                                ( Employee: <?= $slab['esic_per'] ?>%,
                                                Employer: <?= $slab['esic_emp_per'] ?>% )

                                            </div>
                                        </div>

                                        <?php } ?>
                                    </div>
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