<?php include("../adminsession.php");
$pagename = "month_wise_salary_variance.php";
$title = "Month Wise Salary Variance";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Month Wise Salary Variance";
$submodule = "Month Wise Salary Variance";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$unit_id = (isset($_GET['unit_id'])) ? $obj->test_input($_GET['unit_id']) : $unitid;
$from_month = (isset($_GET['from_month'])) ? $obj->test_input($_GET['from_month']) : date('m');
$to_month = (isset($_GET['to_month'])) ? $obj->test_input($_GET['to_month']) : date('m');
$year = (isset($_GET['year'])) ? $obj->test_input($_GET['year']) : date('Y');
$crit2 = '';
$unit_name = $obj->getvalfield("unit_master", "unit_name", "unit_id='$unit_id'");
// $prev_month = $month - 1;
// $prev_year = $year;

// if ($month == 1) {
//     $prev_month = 12;
//     $prev_year = $year - 1;
// }


$months = [];
for ($m = $from_month; $m <= $to_month; $m++) {
    $months[] = str_pad($m, 2, '0', STR_PAD_LEFT);
}

$last_month = '';
$second_last_month = '';

if (count($months) >= 2) {
    $last_month = $months[count($months) - 1];
    $second_last_month = $months[count($months) - 2];
}
$total_month = [];

foreach ($months as $m) {
    $total_month[$m] = 0;
}

$month_cases = "";
foreach ($months as $m) {
    $month_cases .= "
    SUM(CASE WHEN ss.month = '$m' THEN ss.total_pay_sal_after_ded ELSE 0 END) AS m$m,";
}

$month_cases = rtrim($month_cases, ',');


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
                                                    <h5 class="card-title mb-0"> <?= $module; ?> <a href="monthly_salary_cost_details.php" class="float-end btn btn-primary btn-sm" target="_blank">View Details</a></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-3 mb-3">
                                                <label for="unit_id" class="form-label">Unit Name<span class="text-danger fw-bold"></span></label>
                                                <select class="form-select chosen-select" name="unit_id" id="unit_id">
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
                                            <div class="col-md-3 md-2">
                                                <strong><label for="from_month">From Month<span class="text-danger fw-bold">*</span></label></strong></br>
                                                <select name="from_month" class="chosen-select form-control form-control" id="from_month">

                                                    <?php for ($iM = 1; $iM <= 12; $iM++) {
                                                    ?>
                                                        <option value="<?php echo str_pad($iM, 2, '0', STR_PAD_LEFT); ?>"><?php echo date("F", strtotime(str_pad($iM, 2, '0', STR_PAD_LEFT) . "/12/10")); ?></option>

                                                    <?php
                                                    } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('from_month').value = '<?php echo $from_month; ?>';
                                                </script>
                                            </div>
                                            <div class="col-md-3 md-2">
                                                <strong><label for="to_month">To Month<span class="text-danger fw-bold">*</span></label></strong></br>
                                                <select name="to_month" class="chosen-select form-control form-control" id="to_month">

                                                    <?php for ($iM = 1; $iM <= 12; $iM++) {
                                                    ?>
                                                        <option value="<?php echo str_pad($iM, 2, '0', STR_PAD_LEFT); ?>"><?php echo date("F", strtotime(str_pad($iM, 2, '0', STR_PAD_LEFT) . "/12/10")); ?></option>

                                                    <?php
                                                    } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('to_month').value = '<?php echo $to_month; ?>';
                                                </script>
                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <label for="year" class="form-label">Year<span class="text-danger fw-bold">*</span></label>
                                                <select class="form-select chosen-select" name="year" id="year">

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
                                                <input type="submit" class="btn btn-primary add-btn" name="search" value="Search" onclick="return validateMonthRange()">
                                                <a href="<?php echo $pagename; ?>" class="btn btn-danger" name="reset" id="reset">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                    <?php if (isset($_GET['search'])) { ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"><?php echo $submodule; ?> </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive auto-scroll-wrapper">

                                        <table id="buttons-datatables" class="display table table-sm table-bordered" style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>Sr No.</th>
                                                    <th>Unit Name</th>
                                                    <?php foreach ($months as $m) { ?>
                                                        <th><?= date("F", mktime(0, 0, 0, $m, 10)) ?></th>
                                                    <?php } ?>
                                                    <th> Variation
                                                        <?php if (!empty($last_month) && !empty($second_last_month)) { ?>
                                                            (<?= date("F", mktime(0, 0, 0, (int)$second_last_month, 10)) ?> →
                                                            <?= date("F", mktime(0, 0, 0, (int)$last_month, 10)) ?>)
                                                        <?php } ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;
                                                $res = $obj->executequery("SELECT 
                                                        ss.unit_id,
                                                        um.unit_name,
                                                        $month_cases
                                                    FROM salary_structure ss
                                                    LEFT JOIN unit_master um ON um.unit_id = ss.unit_id
                                                    WHERE ss.year='$year'
                                                    " . (!empty($unit_id) ? " AND ss.unit_id='$unit_id'" : "") . "
                                                    GROUP BY ss.unit_id
                                                    ORDER BY um.unit_name ASC
                                                ");

                                                foreach ($res as $row) {
                                                    $variation = 0;

                                                    if (!empty($last_month) && !empty($second_last_month)) {
                                                        $variation = $row['m' . $last_month] - $row['m' . $second_last_month];
                                                    }
                                                ?>
                                                    <tr>
                                                        <td><?= $slno++; ?></td>
                                                        <td><?= $row['unit_name'] ?></td>

                                                        <?php foreach ($months as $m) {

                                                            $val = $row['m' . $m];
                                                            $total_month[$m] += $val;
                                                        ?>
                                                            <td class="text-end">
                                                                <?= number_format($row['m' . $m], 2) ?>
                                                            </td>
                                                        <?php } ?>
                                                        <td class="text-end <?= ($variation < 0) ? 'text-danger' : 'text-success' ?>">
                                                            <?= number_format($variation, 2) ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr style="font-weight:bold; background:#f1f1f1;">
                                                    <td colspan="2" class="text-end">Total</td>
                                                    <?php
                                                    $total_variation = 0;

                                                    if (!empty($last_month) && !empty($second_last_month)) {
                                                        $total_variation = $total_month[$last_month] - $total_month[$second_last_month];
                                                    }
                                                    foreach ($months as $m) {
                                                    ?>
                                                        <td class="text-end">
                                                            <?= number_format($total_month[$m], 2) ?>
                                                        </td>
                                                    <?php } ?>
                                                    <td class="text-end">
                                                        <?= number_format($total_variation, 2) ?>
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

        });
    </script>
    <script>
        function validateMonthRange() {
            let fromMonth = document.getElementById("from_month").value;
            let toMonth = document.getElementById("to_month").value;

            if (fromMonth && toMonth && parseInt(fromMonth) > parseInt(toMonth)) {
                alert("Invalid month range selected!");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>