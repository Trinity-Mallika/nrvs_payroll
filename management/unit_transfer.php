<?php include("../adminsession.php");
$pagename = "unit_transfer.php";
$title = "Unit Transfer Master";
$tblname = "on_duty_master";
$tblpkey = "on_duty_id";
$module = "Unit Transfer Master";
$submodule = "Unit Transfer Master List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$emp_id = (isset($_GET['emp_id'])) ? $obj->test_input($_GET['emp_id']) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
if ($emp_id > 0) {
    $emp_data = $obj->select_record("employee_master", ['emp_id' => $emp_id]);
    $first_name = $emp_data['first_name'] ?? '';
    $last_name = $emp_data['last_name'] ?? '';
    $emp_code = $emp_data['emp_code'] ?? '';
    $date_of_joining = $emp_data['date_of_joining'] ?? '';
    $basic_salary = $emp_data['basic_salary'] ?? '';
    $shift_hrs = $emp_data['shift_id'] ?? '';
} else {
    $first_name = '';
    $last_name = '';
    $emp_code = '';
    $date_of_joining = '';
    $basic_salary = '';
    $shift_hrs = '';
}
 

if (isset($_POST['unit_iddd'])) {
    $unit_id = $obj->test_input($_POST['unit_iddd']);
    // Department
    $dept_html = '<option value="">Select Department</option>';
    $dept = $obj->executequery("SELECT * FROM department_master WHERE unit_id='$unit_id' order by department_name asc");
    foreach ($dept as $d) {
        $dept_html .= '<option value="' . $d['department_id'] . '">' . $d['department_name'] . '</option>';
    }
    $max_code = $obj->getvalfield(
        "employee_master",
        "MAX(emp_code)",
        "1=1"
    );
    $new_emp_code = ($max_code != "") ? $max_code + 1 : 1;

    echo json_encode([
        "department" => $dept_html,
        "new_emp_code" => $new_emp_code,
        "new_bio_id" => $new_emp_code,
        // "designation" => $des_html,
    ]);
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
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
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
                                    <div class="row">
                                        <div class="col-lg-4 mb-2">
                                            <label for="emp_id" class="form-label">Employee Name<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id"
                                                id="emp_id" onchange="get_url(this.value);">
                                                <option value="0">Select Employee</option>
                                                <?php
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                <option value="<?= $key['emp_id']; ?>">
                                                    <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?>
                                                    <?= ucfirst($key['last_name'] ?? ''); ?> </option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById('emp_id').value = '<?= $emp_id; ?>';
                                            </script>
                                        </div>

                                        <?php

                                        $emp_detail = $obj->select_record("employee_master",["emp_id"=>$emp_id]);
                                        
                                        if (!empty($emp_detail)) { ?>

                                        <div class="col-lg-8">
                                            <div class="border rounded p-3 bg-light">

                                                <div class="row">

                                                    <div class="col-md-4">
                                                        <b>Emp Code</b><br>
                                                        <?= $emp_detail['emp_code']; ?>
                                                    </div>
                                                    <div class="col-md-4  ">
                                                        <b>Employee Name</b><br>
                                                        <?= $emp_detail['first_name'] . ' ' . $emp_detail['last_name']; ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <b>Department</b><br>
                                                        <?= $obj->getvalfield("department_master","department_name","department_id='$emp_detail[department_id]'"); ?>
                                                    </div> 
                                                    <div class="col-md-4 mt-2">
                                                        <b>Designation</b><br>
                                                        <?= $obj->getvalfield("designation_master","designation","designation_id='$emp_detail[designation_id]'"); ?>
                                                    </div> 
                                                    <div class="col-md-4 mt-2">
                                                        <b>Basic Salary</b><br>
                                                        ₹ <?= number_format($emp_detail['basic_salary'], 2); ?>
                                                    </div> 
                                                    <div class="col-md-4 mt-2">
                                                        <b>Shift</b><br>
                                                        <?= $emp_detail['shift_id']; ?>
                                                    </div> 
                                                </div>

                                            </div>
                                        </div>

                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (isset($_GET['emp_id'])) { ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <div class="table-responsive">
                                                <table class="display table table-sm table-bordered" style="width:100%">
                                                    <thead>
                                                        <tr class="table-primary">
                                                            <th>Sr No.</th>
                                                            <th>Employee Name</th>
                                                            <th>Prev Emp Code</th>
                                                            <th>Date From - To</th>
                                                            <th>Unit Name</th>
                                                            <th>Department / Designation </th>
                                                            <th>Transfer/Entry By</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $sn = 1;
                                                            $res = $obj->executequery("SELECT bt.* , um.unit_name, uc.fullname,uc.mobile, uc.username,dpt.department_name,des.designation,  em.first_name , em.last_name , em.emp_code from emp_branch_transfer as bt left join unit_master um on bt.unit_id=um.unit_id left join department_master dpt on bt.department_id=dpt.department_id left join designation_master des on bt.designation_id=des.designation_id left join employee_master em on bt.emp_id=em.emp_id left join user uc on bt.createdby = uc.userid  where bt.emp_id='$emp_id' order by branch_transfer_id desc");

                                                            foreach ($res as $key) {
                                                                $last_work_date = ($key['last_work_date'] && $key['last_work_date'] !== '0000-00-00')
                                                                    ? $obj->dateformatindia($key['last_work_date'])
                                                                    : 'Till End';
                                                            ?>
                                                        <tr>
                                                            <td><?= $sn++ ?></td>
                                                            <td><?= $first_name . " " . $last_name . " - (" . $emp_code . ")" ?>
                                                            </td>
                                                            <td><?= $key['prev_emp_code'] ?></td>
                                                            <td>
                                                                <?= $obj->dateformatindia($key['joining_date']) . " - " . $last_work_date; ?><br>
                                                                <small>Shift Hrs : <?= $key['shift_hrs']; ?> </small>
                                                            </td>
                                                            <td><?= $key['unit_name']; ?> </td>
                                                            <td><b><?= $key['department_name']; ?></b><br>
                                                                <small><?= $key['designation']; ?> </small>
                                                            </td>
                                                            <td><b><?= $key['username']; ?></b><br>
                                                                <small><?= $key['fullname']; ?> </small><br>
                                                                Dt: <?= $key['createdate']; ?>
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
                        </div>
                        <?php } ?>

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

    function get_url(emp_id) {
        location = '<?= $pagename ?>?emp_id=' + emp_id;
    }
    </script>
</body>

</html>