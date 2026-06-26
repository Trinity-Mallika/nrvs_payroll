<?php include("../adminsession.php");
$pagename = "monthly_salary_cost_details.php";
$title = "Monthly Salary Cost Details";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Monthly Salary Cost Details";
$submodule = "Monthly Salary Cost Details";
$btn_name = "Save";
$imgpath = "uploaded/emp_documents/";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$month = (isset($_GET['month'])) ? $obj->test_input($_GET['month']) : date('m');
$year = (isset($_GET['year'])) ? $obj->test_input($_GET['year']) : date('Y');
$crit = "";
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and ss.department_id = '$department_id'";
    }
} else {
    $department_id = "";
};

if (isset($_GET['unit_id'])) {
    $unit_id = $obj->test_input($_GET['unit_id']);
    if ($unit_id != '') {
        $crit .= " and ss.unit_id = '$unit_id'";
    }
} else {
    $unit_id = $unitid;
};

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
$show_tds_column = false;
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
                                                        <h5 class="card-title mb-0"> <?= $module; ?><a
                                                                href="monthly_salary_cost.php"
                                                                class="float-end btn btn-primary btn-sm">Back</a></h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-lg-3 mb-3">
                                                    <label for="unit_id" class="form-label">Unit Name<span
                                                            class="text-danger fw-bold">*</span></label>
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
                                            <?= strtoupper(date('F', mktime(0, 0, 0, $month, 1))) . ' - ' . $year; ?>
                                        </h5>

                                        <div>
                                            <a href="monthly_salary_cost.php" class="btn btn-secondary btn-sm me-2">
                                                Back
                                            </a>

                                            <a href="<?= $pagename; ?>" class="btn btn-primary btn-sm">
                                                Search Again
                                            </a>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="buttons-datatables" class="display table table-sm table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th> S No </th>
                                                    <th>Emp Code</th>
                                                    <th>Emp Name</th>
                                                    <th>Department</th>

                                                    <th class="text-end">Basic Salary</th>
                                                    <th class="text-end">Increment</th>
                                                    <th>Gross Salary</th>
                                                    <th>Working days</th>
                                                    <th>Earn Basic + DA</th>
                                                    <th>Earn HRA</th>
                                                    <th>Medical Allowance</th>
                                                    <th>Convey Allowance</th>
                                                    <th>Special Allowance</th>
                                                    <th>Monthly Gross Salary</th>
                                                    <th>Total Monthly Gross Salary</th>
                                                    <th>PF Ded</th>
                                                    <th>ESIC Emp Share</th>
                                                    <th>Sal. Adv</th>
                                                    <th>Loan </th>
                                                    <th>Additional Payment</th>
                                                    <th>Other Deduction</th>
                                                    <?php if ($show_tds_column) { ?>
                                                    <th>TDS</th>
                                                    <?php } ?>

                                                    <th>Total Deduction</th>
                                                    <th>Net Salary</th>
                                                </tr>

                                            </thead>
                                            <tbody>
                                                <?php
                                                        $slno = 1;
                                                        $total_basic_salary = 0;
                                                        $total_increment = 0;
                                                        $total_gross_sal = 0; 
                                                        $total_net = 0;
                                                        $total_payable_salary = 0;
                                                        $total_working_days = 0;
                                                        $total_basic_da = 0;
                                                        $total_hra = 0;
                                                        $total_medical = 0;
                                                        $total_conveyance = 0;
                                                        $total_special = 0;
                                                        $total_pf = 0;
                                                        $total_esic = 0;
                                                        $total_pf_share = 0;
                                                        $total_esic_share = 0;
                                                        $total_loan_amt = 0;
                                                        $total_advance_amt = 0;
                                                        $total_add_amt = 0;
                                                        $total_other_amt = 0;
                                                        $total_tds_amt = 0;
                                                        $total_deduction = 0;
                                                        $total_monthly_gross = 0;
                                                        $res = $obj->executequery("SELECT ss.*, d.department_name,um.unit_name,cu.fullname as created_name,cu.username as created_username,cu.mobile as created_mobile, uu.fullname as updated_name, uu.username as updated_username,
                                                            uu.mobile as updated_mobile, em.first_name,em.department_id, em.emp_code, em.last_name FROM salary_structure ss LEFT JOIN employee_master em ON ss.emp_id = em.emp_id LEFT JOIN unit_master um ON ss.unit_id = um.unit_id LEFT JOIN department_master d ON em.department_id = d.department_id LEFT JOIN user cu on ss.createdby=cu.userid LEFT JOIN user uu on ss.updatedby=uu.userid WHERE ss.unit_id = '$unitid' AND ss.payment_status IN ('1','2') $crit ORDER BY em.emp_code ASC");
                                                        
                                                        $total_records = count($res);

                                                        foreach ($res as $row) {
                                                        
                                                            $total_net += $row['total_salary'];
                                                            $total_net_salary = ($row['total_net_salary'] + $row['additional_payment']) - $row['other_deduction'] - $row['loan_amt'] - $row['advance_amt'] - $row['tds_deduction'];
                                                            //$total_net_salary = $row['total_pay_sal_after_ded'];
                                                            $total_payable_salary += $total_net_salary;
                                                            $total_working_days += $row['total_working_days'];
                                                            $total_basic_da += $row['basic_da'];
                                                            $total_hra += $row['hra'];
                                                            $total_medical += $row['medical'];
                                                            $total_conveyance += $row['conveyance'];
                                                            $total_special += $row['special_allow'];
                                                            $total_pf += $row['pf_emp'];
                                                            $total_esic += $row['esic_emp'];
                                                            $total_pf_share += $row['pf_employer'];
                                                            $total_esic_share += $row['esic_employer'];
                                                            $total_loan_amt += $row['loan_amt'];
                                                            $total_advance_amt += $row['advance_amt'];
                                                            $total_add_amt += $row['additional_payment'];
                                                            $total_other_amt += $row['other_deduction'];
                                                            $total_tds_amt += $row['tds_deduction'];
    
                                                            $total_basic_salary+=$row['basic_salary'];
                                                            $total_increment+=$row['increment'];
                                                            $total_gross_sal+=$row['revised_salary'];
                                                            $total_monthly_gross+=$row['total_net_salary'];
                                                            if ($row['tds_deduction'] > 0) {
                                                                $show_tds_column = true;
                                                            }

                                                            $deduction = $row['pf_emp']+ $row['esic_emp']+ $row['loan_amt']+$row['advance_amt']+ $row['other_deduction']+$row['tds_deduction'];
                                                            $total_deduction += $deduction;

                                                        ?>
                                                <tr id="tr_<?= $row["salary_struc_id"]; ?>" data-details="
                                        <div style='background:#dafced; padding:4px;'>
                                        <?php if (!empty($row['created_name'])): ?>
                                        Added by (User: <?= $row['created_name'] ?>,
                                        Username: <?= $row['created_username'] ?>,
                                        Mobile: <?= $row['created_mobile'] ?>,
                                        Date: <?= $row['createdate'] ?>,)<br>
                                        <?php endif; ?>

                                        <?php if (!empty($row['updated_name'])): ?>
                                        Last Edited by (User: <?= $row['updated_name'] ?>,
                                        Username: <?= $row['updated_username'] ?>,
                                        Mobile: <?= $row['updated_mobile'] ?>,
                                        Date: <?= $row['lastupdated'] ?>) 
                                        <?php endif; ?>
                                        </div>
                                    ">

                                                    <td class="details-control text-center" style="cursor:pointer;">
                                                        <?php echo $slno++; ?>
                                                        <i class="ri-add-circle-fill text-primary"></i>
                                                    </td>


                                                    <td><?= $row['emp_code']; ?></td>
                                                    <td><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                                                    <td><?=$row['department_name']; ?></td>

                                                    <td class="text-end"><?= $row['basic_salary']; ?></td>
                                                    <td class="text-end"><?= $row['increment']; ?></td>
                                                    <td class="text-end"><?= $row['revised_salary']; ?></td>

                                                    <td class="text-end"><?= $row['total_working_days']; ?></td>
                                                    <td class="text-end"><?= $row['basic_da']; ?></td>
                                                    <td class="text-end"><?= $row['hra']; ?></td>
                                                    <td class="text-end"><?= $row['medical']; ?></td>
                                                    <td class="text-end"><?= $row['conveyance']; ?></td>
                                                    <td class="text-end"><?= $row['special_allow']; ?></td>
                                                    <td class="text-end"><?= $row['total_salary']; ?></td>
                                                    <td class="text-end"><?= $row['total_net_salary']; ?></td>
                                                    <td class="text-end"><?= $row['pf_emp']; ?></td>
                                                    <td class="text-end"><?= $row['esic_emp']; ?></td>
                                                    <td class="text-end"><?= $row['advance_amt']; ?></td>
                                                    <td class="text-end"><?= $row['loan_amt']; ?></td>
                                                    <td class="text-end"><?= $row['additional_payment']; ?></td>
                                                    <td class="text-end"><?= $row['other_deduction']; ?></td>
                                                    <?php if ($show_tds_column) { ?>
                                                    <td class="text-end"><?= $row['tds_deduction']; ?></td>
                                                    <?php } ?>
                                                    <td class="text-end"><?= $deduction; ?></td>
                                                    <td class="text-end"><?= $total_net_salary; ?></td>

                                                </tr>
                                                <?php } ?>
                                            </tbody>

                                            <tfoot class="table-light">
                                                <tr>
                                                    <th colspan="4">Total Net Pay</th>

                                                    <th class="text-end"><?= number_format($total_basic_salary, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_increment, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_gross_sal, 2) ?></th>

                                                    <th class="text-end"><?= number_format($total_working_days, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_basic_da, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_hra, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_medical, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_conveyance, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_special, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_net, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_monthly_gross, 2) ?></th>

                                                    <th class="text-end"><?= number_format($total_pf, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_esic, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_advance_amt, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_loan_amt, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_add_amt, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_other_amt, 2) ?></th>

                                                    <?php if ($show_tds_column) { ?>
                                                    <th class="text-end"><?= number_format($total_tds_amt, 2) ?></th>
                                                    <?php } ?>

                                                    <th class="text-end"><?= number_format($total_deduction, 2) ?></th>
                                                    <th class="text-end"><?= number_format($total_payable_salary, 2) ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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

    function funDel(id) {
        $('#deleteRecordModal').modal('show');
        tblname = '<?php echo $tblname; ?>';
        tblpkey = '<?php echo $tblpkey; ?>';
        imgpath = '<?php echo $imgpath; ?>';
        pagename = '<?php echo $pagename; ?>';
        submodule = '<?php echo $submodule; ?>';
        $('#delete-record').click(function() {
            $.ajax({
                type: 'POST',
                url: 'ajax/delete_master_emp.php',
                data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&imgpath=' +
                    imgpath + '&submodule=' + submodule + '&pagename=' + pagename,
                dataType: 'html',
                success: function(data) {
                    $("#tr_" + id).hide();
                    // alert(data);
                    // location.reload();
                }
            });
            $('#deleteRecordModal').modal('hide');
        });
    };

    function numberOnly(evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\.|\s/;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }

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