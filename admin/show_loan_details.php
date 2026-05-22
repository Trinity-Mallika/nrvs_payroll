<?php include("../adminsession.php");
$pagename = "show_loan_details.php";
$title = "Loan/Advance Details";
$tblname = "loan_advance";
$tblpkey = "loan_advance_id";
$module = "Loan/Advance Details";
$submodule = "Loan/Advance Details";
$btn_name = "Search";
$imgpath1 = 'uploaded/on_duty/';
$crit = '';
if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    if ($emp_id != '') {
        $crit .= " and la.emp_id='$emp_id'";
    }
} else {
    $emp_id = "";
};
 
if (isset($_GET['type'])) {
    $type = $obj->test_input($_GET['type']);
    if ($type != '') {
        $crit .= " and la.type='$type'";
    }
} else {
    $type = "";
};
if (isset($_GET['application_month'])) {
    $application_month = $obj->test_input($_GET['application_month']);
     if ($application_month != '') {
        $crit .= " and la.month='$application_month'";
    }
} else {
    $application_month =date('n');
};

if (isset($_GET['year'])) {
    $year = $obj->test_input($_GET['year']);
     if ($year != '') {
        $crit .= " and la.year='$year'";
    }
} else {
    $year = date('Y');
};

 
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

.detail-row {
    display: none;
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
                <?php //include('inc/alert.php'); 
                ?>
                <div class="row">
                    <?php if(!isset($_GET['submit'])) { ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a href="loan_advance.php"
                                                    class="float-end btn btn-sm btn-primary">Add</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-lg-3 mb-2">
                                            <label for="emp_id" class="form-label">Employee Name<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id"
                                                id="emp_id">
                                                <option value="">Select Employee</option>
                                                <?php
                                                    $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                    foreach ($res as $key) { ?>
                                                <option value="<?= $key['emp_id']; ?>">
                                                    <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?>
                                                    <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById('emp_id').value = '<?= $emp_id; ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label for="">Type </label>
                                            <select name="type" id="type"
                                                class="form-select form-select-sm form-select-sm">
                                                <option value="">All</option>
                                                <option value="Loan">Loan</option>
                                                <option value="Advance">Advance</option>

                                            </select>
                                            <script>
                                            document.getElementById('type').value = '<?= $type ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Application Month</label>
                                            <select name="application_month" id="application_month"
                                                class="form-select form-select-sm chosen-select">
                                                <option value="">Select Month</option>
                                                <option value="1">January</option>
                                                <option value="2">February</option>
                                                <option value="3">March</option>
                                                <option value="4">April</option>
                                                <option value="5">May</option>
                                                <option value="6">June</option>
                                                <option value="7">July</option>
                                                <option value="8">August</option>
                                                <option value="9">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-3 col-12">
                                            <label for="year" class="form-label">Year<span
                                                    class="text-danger fw-bold"></span></label>
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
                                            
                                            document.getElementById('application_month').value = '<?php echo $application_month ?>'
                                            </script>
                                        </div>



                                        <div class="col-lg-12 text-center mt-4">

                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn"
                                                value="<?php echo $btn_name ?> ">
                                            <a href=" <?php echo $pagename ?>" type="button"
                                                class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if(isset($_GET['submit'])) { ?>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a href="<?=$pagename?>"
                                                    class="float-end btn btn-sm btn-primary">Search Again</a></h5>
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
                                                    <th>SNo</th>
                                                    <th>Emp Code</th>
                                                    <th>Emp Name</th>
                                                    <th>Month-Year</th>
                                                    <th>Type</th>
                                                    <th>Amount</th>
                                                    <th>Remark</th>
                                                </tr>

                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;
                                                 $grand_total = 0;
                                                $res = $obj->executequery("
                                                        SELECT 
                                                            la.*,

                                                            em.first_name,
                                                            em.last_name,
                                                            em.emp_code,

                                                            cu.fullname as created_name,
                                                            cu.username as created_username,
                                                            cu.mobile as created_mobile 
 

                                                        FROM loan_advance_details la

                                                        LEFT JOIN employee_master em 
                                                            ON la.emp_id = em.emp_id

                                                        LEFT JOIN user cu 
                                                            ON la.createdby = cu.userid
                                                      
                                                        WHERE la.unit_id = '$unitid' $crit  and la.status=1
                                                        ORDER BY la.$tblpkey DESC
                                                    ");
 
                                                foreach ($res as $row) {
 $grand_total += $row['amount'];
                                                ?>
                                                <tr data-details="
                                        <div style='background:#dafced; padding:4px;'>
                                        <?php if (!empty($row['created_name'])): ?>
                                        Added by (User: <?= $row['created_name'] ?>,
                                        Username: <?= $row['created_username'] ?>,
                                        Mobile: <?= $row['created_mobile'] ?>,
                                        Date: <?= $row['createdate'] ?>,)<br>
                                        <?php endif; ?> 
                                    ">

                                                    <td class="details-control text-center" style="cursor:pointer;">
                                                        <?php echo $slno++; ?>
                                                        <i class="ri-add-circle-fill text-primary"></i>

                                                    </td>

                                                    <td><?= $row['emp_code'] ?></td>
                                                    <td>
                                                        <?= $row['first_name'] . " " . $row['last_name']   ?>
                                                    </td>
                                                      <td><?= $row['type'] ?></td>

                                                    <td>
                                                        <?= date("F", mktime(0, 0, 0, $row['month'], 1)); ?>
                                                        -
                                                        <?= $row['year']; ?>
                                                    </td>
                                                    <td class="text-end"><?= $row['amount']; ?></td>
                                                    <td><?= $row['remark']; ?></td>
                                                </tr>
                                                <?php }  ?>
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-success fw-bold">
                                                    <td colspan="5" class="text-end">
                                                        Total Amount
                                                    </td>

                                                    <td class="text-end">
                                                        ₹ <?= number_format($grand_total, 2) ?>
                                                    </td>

                                                    <td></td>
                                                </tr>
                                            </tfoot>

                                        </table>


                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    </div><!-- Loan / Advance Approval Modal -->
    
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