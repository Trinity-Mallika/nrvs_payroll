<?php include("../adminsession.php");
$pagename = "monthly_salary_cost.php";
$title = "Monthly Salary Cost";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Monthly Salary Cost";
$submodule = "Monthly Salary Cost";
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
    $options = "<option value=''>All</option>";
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
                                                <select class="form-select chosen-select" name="unit_id" id="unit_id">
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
                    <?php } ?>
                    <?php if (isset($_GET['search'])) { ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">

                                    <h5 class="card-title mb-0">
                                        <?= $submodule; ?>
                                    </h5>

                                    <h5 class="mb-0 fw-bold text-primary">
                                        <?= strtoupper(date('F', mktime(0, 0, 0, $month, 1))) . " - " . $year; ?>
                                    </h5>

                                    <a href="<?= $pagename; ?>" class="btn btn-primary btn-sm">
                                        Search Again
                                    </a>

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
                                                    <th>Department</th>
                                                    <th>Employee Count</th>
                                                    <th>Basic Salary</th>
                                                    <th>Increment</th>
                                                    <th>Revised Salary</th>
                                                    <th>Working Day</th>
                                                    <th>Basic DA</th>
                                                    <th>Medical Allowances</th>
                                                    <th>HRA</th>
                                                    <th>Special Allowances</th>
                                                    <th>Total Allowances</th>
                                                    <th>Monthly Gross Salary</th>
                                                    <th>Overtime Cost</th>
                                                    <th>Bonus </th>
                                                    <th>PF</th>
                                                    <th>ESIC</th>
                                                    <th>Employer PF</th>
                                                    <th>Employer ESIC</th>
                                                    <th>Total Monthly Gross Salary</th>
                                                    <th>Additional Pay</th>
                                                    <th>Sal Adv.</th>
                                                    <th>Loan</th>
                                                    <th>Other Deduction</th>
                                                    <th>Total Payable</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $slno = 1;
                                                    $gt_employee = 0;
                                                    $gt_basic_salary = 0;
                                                    $gt_increment = 0;
                                                    $gt_revised_salary = 0;
                                                    $gt_working_day = 0;
                                                    $gt_basic_da = 0;
                                                    $gt_medical = 0;
                                                    $gt_hra = 0;
                                                    $gt_special = 0;
                                                    $gt_allowance = 0;
                                                    $monthly_gross = 0;
                                                    $gt_pf_emp = 0;
                                                    $gt_esic_emp = 0;
                                                    $gt_pf_employer = 0;
                                                    $gt_esic_employer = 0;
                                                    $gt_net_salary = 0;
                                                    $gt_additional = 0;
                                                    $gt_other_ded = 0;
                                                    $total_loan = 0;
                                                    $total_adv = 0;
                                                    $gt_other_ded = 0;
                                                    $gt_pay_after_ded = 0;
                                                    $fromDate = "$year-$month-01";
                                                    $toDate   = date("Y-m-t", strtotime($fromDate));
                                                    $res = $obj->executequery("SELECT dm.department_name, ss.department_id,
                                                        SUM(ss.basic_salary) AS total_basic_salary,
                                                        SUM(ss.increment) AS total_increment,
                                                        SUM(ss.revised_salary) AS total_revised_salary,
                                                        SUM(ss.total_working_days) AS total_working_days,
                                                        SUM(ss.basic_da) AS total_basic_da,
                                                        SUM(ss.hra) AS total_hra,
                                                        SUM(ss.medical) AS total_medical,
                                                        SUM(ss.conveyance) AS total_conveyance,
                                                        SUM(ss.special_allow) AS total_special_allow,
                                                        (SUM(ss.medical) + SUM(ss.conveyance) + SUM(ss.special_allow)) AS total_allowance,
                                                        SUM(ss.total_salary) AS total_salary,
                                                        COUNT(ss.emp_id) AS total_employee,
                                                        SUM(ss.pf_emp) AS total_emp_pf,
                                                        SUM(ss.pf_employer) AS total_employer_pf,
                                                        SUM(ss.esic_emp) AS total_emp_esic,
                                                        SUM(ss.advance_amt) AS total_advance_amt,
                                                        SUM(ss.loan_amt) AS total_loan_amt,
                                                        SUM(ss.esic_employer) AS total_employer_esic,
                                                        SUM(ss.additional_payment) AS total_additional_payment,
                                                        SUM(ss.other_deduction) AS total_other_deduction,
                                                        SUM(ss.total_pay_sal_after_ded) AS total_pay_sal_after_ded,
                                                        SUM(ss.tds_deduction) AS tds_deduction,

                                                        SUM(ss.total_net_salary) AS total_net_salary
                                                        FROM salary_structure as ss

                                                        LEFT JOIN department_master dm
                                                        ON ss.department_id = dm.department_id

                                                        WHERE ss.month='$month'
                                                        AND ss.year='$year' $crit2
                                                        GROUP BY ss.department_id
                                                        ORDER BY dm.department_name ASC
                                                        ");
                                                    foreach ($res as $row) {
                                                        $gt_employee += $row['total_employee'];
                                                        $gt_basic_salary += $row['total_basic_salary'];
                                                        $gt_increment += $row['total_increment'];
                                                        $gt_revised_salary += $row['total_revised_salary'];
                                                        $gt_working_day += $row['total_working_days'];
                                                        $gt_basic_da += $row['total_basic_da'];
                                                        $gt_medical += $row['total_medical'];
                                                        $gt_hra += $row['total_hra'];
                                                        $gt_special += $row['total_special_allow'];
                                                        $gt_allowance += $row['total_allowance'];
                                                        $monthly_gross += $row['total_salary'];

                                                        $gt_pf_emp += $row['total_emp_pf'];
                                                        $gt_esic_emp += $row['total_emp_esic'];
                                                        $gt_pf_employer += $row['total_employer_pf'];
                                                        $gt_esic_employer += $row['total_employer_esic'];
                                                        $total_loan += $row['total_loan_amt'];
                                                        $total_adv += $row['total_advance_amt'];

                                                        $gt_net_salary += $row['total_net_salary'];
                                                        $gt_additional += $row['total_additional_payment'];
                                                        $gt_other_ded += $row['total_other_deduction'];
                                                       // $gt_pay_after_ded += $row['total_pay_sal_after_ded'];
                                                        $total_payable = ($row['total_net_salary'] + $row['total_additional_payment']) - $row['total_other_deduction'] - $row['total_loan_amt'] - $row['total_advance_amt'] - $row['tds_deduction'];
                                                        $gt_pay_after_ded += $total_payable;
                                                    ?>
                                                <tr>
                                                    <td><?php echo $slno++; ?>
                                                        <a
                                                            href='monthly_salary_cost_details.php?unit_id=<?= $unit_id ?>&department_id=<?= $row['department_id'] ?>&month=<?= $month ?>&year=<?= $year ?>&search=Search'>
                                                            <i class='ri-eye-fill align-bottom text-primary'></i>
                                                        </a>
                                                    </td>
                                                    <td><?php echo $unit_name; ?></td>

                                                    <td> <?= $row['department_name']; ?> </td>
                                                    <td class="text-end"> <?= $row['total_employee']; ?> </td>
                                                    <td class="text-end"><?= $row["total_basic_salary"]; ?></td>
                                                    <td class="text-end"><?= $row["total_increment"]; ?></td>
                                                    <td class="text-end"><?= $row["total_revised_salary"]; ?></td>
                                                    <td class="text-end"><?= $row["total_working_days"]; ?></td>
                                                    <td class="text-end"><?= $row["total_basic_da"]; ?></td>
                                                    <td class="text-end"><?= $row['total_medical']; ?></td>
                                                    <td class="text-end"><?= $row['total_hra']; ?></td>
                                                    <td class="text-end"><?= $row['total_special_allow']; ?></td>
                                                    <td class="text-end"><?= $row['total_allowance']; ?></td>
                                                    <td class="text-end"><?= $row['total_salary']; ?></td>

                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-end"><?= $row['total_emp_pf']; ?></td>
                                                    <td class="text-end"><?= $row['total_emp_esic']; ?></td>
                                                    <td class="text-end"><?= $row['total_employer_pf']; ?></td>
                                                    <td class="text-end"><?= $row['total_employer_esic']; ?></td>
                                                    <td class="text-end"><?= $row['total_net_salary']; ?></td>
                                                    <td class="text-end"><?= $row['total_additional_payment']; ?></td>
                                                    <td class="text-end"><?= $row['total_loan_amt']; ?></td>
                                                    <td class="text-end"><?= $row['total_advance_amt']; ?></td>
                                                    <td class="text-end"><?= $row['total_other_deduction']; ?></td>
                                                    <td class="text-end"><?= $total_payable; ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                         <tfoot>
    <tr style="font-weight:bold;background:#f1f1f1;">
        <td colspan="3" class="text-end">Grand Total</td>

        <td class="text-end"><?= number_format($gt_employee) ?></td>
        <td class="text-end"><?= number_format($gt_basic_salary, 2) ?></td>
        <td class="text-end"><?= number_format($gt_increment, 2) ?></td>
        <td class="text-end"><?= number_format($gt_revised_salary, 2) ?></td>
        <td class="text-end"><?= number_format($gt_working_day, 2) ?></td>
        <td class="text-end"><?= number_format($gt_basic_da, 2) ?></td>
        <td class="text-end"><?= number_format($gt_medical, 2) ?></td>
        <td class="text-end"><?= number_format($gt_hra, 2) ?></td>
        <td class="text-end"><?= number_format($gt_special, 2) ?></td>
        <td class="text-end"><?= number_format($gt_allowance, 2) ?></td>
        <td class="text-end"><?= number_format($monthly_gross, 2) ?></td>

        <td></td>
        <td></td>

        <td class="text-end"><?= number_format($gt_pf_emp, 2) ?></td>
        <td class="text-end"><?= number_format($gt_esic_emp, 2) ?></td>
        <td class="text-end"><?= number_format($gt_pf_employer, 2) ?></td>
        <td class="text-end"><?= number_format($gt_esic_employer, 2) ?></td>

        <td class="text-end"><?= number_format($gt_net_salary, 2) ?></td>
        <td class="text-end"><?= number_format($gt_additional, 2) ?></td>
        <td class="text-end"><?= number_format($total_loan, 2) ?></td>
        <td class="text-end"><?= number_format($total_adv, 2) ?></td>
        <td class="text-end"><?= number_format($gt_other_ded, 2) ?></td>
        <td class="text-end"><?= number_format($gt_pay_after_ded, 2) ?></td>
    </tr>
</tfoot>
                                        </table>
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