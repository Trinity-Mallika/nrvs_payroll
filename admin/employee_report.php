<?php include("../adminsession.php");
$pagename = "employee_report.php";
$title = "Employee Master";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Employee Master";
$submodule = "Employee Master List";
$crit = "where 1=1";
if (isset($_GET['branch_id'])) {
    $branch_id = $obj->test_input($_GET['branch_id']);
    if ($branch_id > 0) {
        $crit .= " and e.branch_id='$branch_id'";
    }
} else {
    $branch_id = "";
}
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id > 0) {
        $crit .= " and e.department_id='$department_id'";
    }
} else {
    $department_id = "";
}

if (isset($_GET['post_id'])) {
    $post_id = $obj->test_input($_GET['post_id']);
    if ($post_id > 0) {
        $crit .= " and e.post_id='$post_id'";
    }
} else {
    $post_id = "";
}

if (isset($_GET['sal_type'])) {
    $sal_type = $obj->test_input($_GET['sal_type']);
    if ($sal_type != '') {
        $crit .= " and e.sal_type='$sal_type'";
    }
} else {
    $sal_type = "";
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
                <div class="row">
                    <form>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
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
                                        <div class="col-lg-4 mb-3">
                                            <label for="branch_id" class="form-label">Branch Name<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select chosen-select" name="branch_id" id="branch_id">
                                                <option value="">Select</option>
                                                <?php $res = $obj->executequery("Select * from branch_master where status='1'");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['branch_id']; ?>"><?= $key['branch_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('branch_id').value = '<?= $branch_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="department_id" class="form-label">Department<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select chosen-select" name="department_id" id="department_id">
                                                <option value="">Select</option>
                                                <?php $res = $obj->executequery("Select * from department_master order by department_name asc");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['department_id']; ?>"><?= $key['department_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('department_id').value = '<?= $department_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="post_id" class="form-label">Post<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select chosen-select" name="post_id" id="post_id">
                                                <option value="">Select</option>
                                                <?php $res = $obj->executequery("Select * from post_master order by post_name asc");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['post_id']; ?>"><?= $key['post_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('post_id').value = '<?= $post_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="sal_type" class="form-label">Salary Type<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select chosen-select" name="sal_type" id="sal_type">
                                                <option value="">Select</option>
                                                <option value="Weekly">Weekly</option>
                                                <option value="Monthly">Monthly</option>
                                            </select>
                                            <script>
                                                document.getElementById('sal_type').value = '<?= $sal_type; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-2 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary add-btn" value="Search">
                                            <a href="<?php echo $pagename ?>" type="button" class="btn btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> <span class="text-danger"></span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Sr No.</th>
                                                <th>Branch Name</th>
                                                <th>Emp Code</th>
                                                <th>Employee Name</th>
                                                <th>Contact No.</th>
                                                <th>Department</th>
                                                <th>Post</th>
                                                <th>Email</th>
                                                <th>DOB</th>
                                                <th>DOJ</th>
                                                <th>Salary</th>
                                                <th>Salary Type</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $slno = 1;
                                            $res = $obj->executequery("SELECT e.*, b.branch_name,d.department_name FROM $tblname e LEFT JOIN branch_master b ON e.branch_id=b.branch_id left join department_master as d on e.department_id=d.department_id $crit ORDER BY e.$tblpkey DESC");
                                            foreach ($res as $row) {
                                                $post_name = $obj->getvalfield("post_master", "post_name", "post_id='{$row['post_id']}'");
                                            ?>
                                                <tr>
                                                    <td><?php echo $slno++; ?></td>
                                                    <td><?php echo $row["branch_name"]; ?></td>
                                                    <td><?php echo $row["biometric"]; ?></td>
                                                    <td><?php echo $row["emp_name"]; ?></td>
                                                    <td><?php echo $row["mobile_no"]; ?></td>
                                                    <td><?php echo $row["department_name"]; ?></td>
                                                    <td><?php echo $post_name; ?></td>
                                                    <td><?php echo $row["email_id"]; ?></td>
                                                    <td><?php echo $obj->dateformatindia($row["dob"]); ?></td>
                                                    <td><?php echo $obj->dateformatindia($row["doj"]); ?></td>
                                                    <td><?php echo number_format($row["salary"], 2); ?></td>
                                                    <td><?php echo $row["sal_type"]; ?></td>
                                                    <td><?php echo ($row["status"] == 1) ? "Enable" : "Disable"; ?></td>
                                                    <td>
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <li class="list-inline-item " data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                                <a href="<?php echo $pagename ?>?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>" class="edit-item-btn">
                                                                    <i class="ri-pencil-fill align-bottom text-success"></i>
                                                                </a>
                                                            </li>
                                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                                <a class="remove-item-btn" type="button" onclick="funDel(<?php echo $row[$tblpkey]; ?>);">
                                                                    <i class="ri-delete-bin-fill align-bottom text-danger"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
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
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            $(".chosen-select").chosen({
                width: '100%',
                search_contains: true
            });
        });
    </script>
</body>

</html>