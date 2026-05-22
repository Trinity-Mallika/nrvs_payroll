<?php include("../adminsession.php");
$pagename = "loan_advance.php";
$title = "Loan Advance List";
$tblname = "loan_advance";
$tblpkey = "loan_advance_id";
$module = "Loan Advance";
$submodule = "Loan Advance List";
$btn_name = "Save";
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
                <?php //include('inc/alert.php'); 
                ?>
                <div class="row">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?> <a href="loan_advance.php" class="float-end btn btn-sm btn-primary">List</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Company - Branch</label>
                                            <input type="text" class="form-control form-control-sm" placeholder="Search...">
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Department Name</label>
                                            <select name="department_id" id="department_id" class="form-select form-select-sm">
                                                <option value="">Select Department</option>
                                                <option value="1">HR</option>
                                                <option value="2">Finance</option>
                                                <option value="3">IT</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Designation Name</label>
                                            <select name="Designation_id" id="Designation_id" class="form-select form-select-sm">
                                                <option value="">Select Designation</option>
                                                <option value="1">HR</option>
                                                <option value="2">Finance</option>
                                                <option value="3">IT</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Level Name</label>
                                            <select name="Level_id" id="Level_id" class="form-select form-select-sm">
                                                <option value="">Select Level</option>
                                                <option value="1">HR</option>
                                                <option value="2">Finance</option>
                                                <option value="3">IT</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Grade Name</label>
                                            <select name="Grade_id" id="Grade_id" class="form-select form-select-sm">
                                                <option value="">Select Grade</option>
                                                <option value="1">HR</option>
                                                <option value="2">Finance</option>
                                                <option value="3">IT</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Employee Name</label>
                                            <input type="text" class="form-control form-control-sm" placeholder="Search...">
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Employee Code</label>
                                            <input type="text" class="form-control form-control-sm" placeholder="Search...">
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Appr Status</label>
                                            <input type="text" class="form-control form-control-sm" placeholder="Search...">
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Application Date From <input type="checkbox"></label>
                                            <input type="date" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Application Date To</label>
                                            <input type="date" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Employee Group </label>
                                            <select name="employee_id" id="employee_id" class="form-select form-select-sm">
                                                <option value="">Select Employee</option>
                                                <option value="1">John Doe</option>
                                                <option value="2">Jane Smith</option>
                                                <option value="3">Robert Johnson</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Duration Month Year</label>
                                            <div class="d-flex">
                                                <input type="date" class="form-control form-control-sm me-2" placeholder="Search...">
                                                <span class="mt-1">-</span>
                                                <input type="date" class="form-control form-control-sm ms-2" placeholder="Search...">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Duration Month Year To</label>
                                            <div class="d-flex">
                                                <input type="date" class="form-control form-control-sm me-2" placeholder="Search...">
                                                <span class="mt-1">-</span>
                                                <input type="date" class="form-control form-control-sm ms-2" placeholder="Search...">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Loan Status </label>
                                            <select name="employee_id" id="employee_id" class="form-select form-select-sm">
                                                <option value="">Select Loan Status</option>
                                                <option value="1">Approved</option>
                                                <option value="2">Pending</option>
                                                <option value="3">Rejected</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Type </label>
                                            <select name="employee_id" id="employee_id" class="form-select form-select-sm">
                                                <option value="">Select Loan Type</option>
                                                <option value="1">Approved</option>
                                                <option value="2">Pending</option>
                                                <option value="3">Rejected</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Voucher</label>
                                            <div class="d-flex">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="radioDefault" id="all">
                                                    <label class="form-check-label" for="all">
                                                        All
                                                    </label>
                                                </div>
                                                <div class="form-check ms-2">
                                                    <input class="form-check-input" type="radio" name="radioDefault" id="unpost">
                                                    <label class="form-check-label" for="unpost">
                                                        Un-Post
                                                    </label>
                                                </div>
                                                <div class="form-check ms-2">
                                                    <input class="form-check-input" type="radio" name="radioDefault" id="post" checked>
                                                    <label class="form-check-label" for="post">
                                                        Post
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?> </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>SNo</th>
                                            <th>Date</th>
                                            <th>Employee Name</th>
                                            <th>
                                                Branch Name<br>
                                                Department Name <br>
                                                Designation Name
                                            </th>
                                            <th>Set<br>Off</th>
                                            <th>
                                                Adv./Loan<br>
                                                <small class="fw-normal">Amt. + Int</small>
                                            </th>
                                            <th>
                                                No. Of<br>Installment
                                            </th>
                                            <th>Total<br>Amt.</th>
                                            <th>
                                                Inst.<br>From
                                            </th>
                                            <th>
                                                Inst.<br>To
                                            </th>
                                            <th>
                                                Post<br>Status
                                            </th>
                                            <th>Status</th>
                                            <th>
                                                Doc.
                                            </th>
                                            <th>
                                                Doc.<br>Upload
                                            </th>
                                            <th>Edit</th>
                                            <th>Del.</th>
                                            <th>Aprv.</th>
                                            <th>SetOff</th>
                                            <th>Print</th>
                                            <th>
                                                <input class="form-check-input" type="checkbox">
                                            </th>
                                        </tr>

                                        </thead>
                                        <tbody>

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>

                    </form>
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
</body>

</html>