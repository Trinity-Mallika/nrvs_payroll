<?php include("../adminsession.php");
$pagename = "department_master.php";
$title = "DEPARTMENT Master";
$tblname = "department_master";
$tblpkey = "department_id";
$module = "Department Master";
$submodule = "Department Master List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

if (isset($_POST['submit'])) {
    // $category_id = $obj->test_input($_POST['category_id']);
    $subdivision_id  = $obj->test_input($_POST['subdivision_id']??0);
    $department_name  = $obj->test_input($_POST['department_name']);
    $emp_id  = $obj->test_input($_POST['emp_id']);
    $c_off_check = isset($_POST['c_off_check']) ? 1 : 0;
    $allow_add_leave = isset($_POST['allow_add_leave']) ? 1 : 0;

    $count = $obj->getvalfield($tblname, "count(*)", "department_name='$department_name' and subdivision_id='$subdivision_id' and unit_id='$unitid' and $tblpkey!='$keyvalue'");

    $form_data = array(
        // "category_id" => $category_id,
        "subdivision_id" => $subdivision_id,
        "department_name" => $department_name,
        "emp_id" => $emp_id,
        "c_off_check"   => $c_off_check,
        "allow_add_leave"   => $allow_add_leave,
        "createdby" => $loginid,
        "sessionid"   => $sessionid,
        "unit_id"   => $unitid,
        "ipaddress" => $ipaddress
    );
    if ($count > 0) {
        $action = 4;
        $process = "duplicate";
    } else {
        if ($keyvalue == 0) {
            $form_data["createdate"] = $createdate;
            $lastid = $obj->insert_record_lastid($tblname, $form_data);

            $update_employee = [
                'reporting_manager' => $emp_id,
                'allow_add_leave' => $allow_add_leave
            ];

            $where_employee = [
                'department_id' => $lastid,
                'unit_id'       => $unitid
            ];
            $obj->update_record("employee_master", $where_employee, $update_employee);

            $action = 1;
            $process = "insert";
        } else {
            $form_data["lastupdated"] = $createdate;
            $form_data["updatedby"] = $loginid;
            $where = array($tblpkey => $keyvalue);
            $obj->update_record($tblname, $where, $form_data);

            $update_employee = [
                'reporting_manager' => $emp_id,
                'allow_add_leave' => $allow_add_leave,
            ];

            $where_employee = [
                'department_id' => $keyvalue,
                'unit_id'       => $unitid
            ];
            $obj->update_record("employee_master", $where_employee, $update_employee);

            $action = 2;
            $process = "updated";
        }
    }
    echo "<script>location='$pagename?action=$action'</script>";
}


if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    // $category_id = $sqledit['category_id'];
    $subdivision_id =  $sqledit['subdivision_id'];
    $department_name =  $sqledit['department_name'];
    $emp_id =  $sqledit['emp_id'];
    $c_off_check   = $sqledit['c_off_check'];
    $allow_add_leave   = $sqledit['allow_add_leave'];
} else {
    // $category_id = "";
    $subdivision_id = "";
    $department_name = "";
    $emp_id = "";
    $c_off_check   = 0;
    $allow_add_leave   = 0;
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
                    <form method="post" action="">
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?><a href="department_master_list.php" class="float-end btn btn-primary btn-sm">Department List</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        
                                        <div class="col-lg-3 mb-3">
                                            <label for="subdivision_id" class="form-label">Sub Division Name<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="subdivision_id" id="subdivision_id">
                                                <option value="">Select</option>
                                                <?php $res = $obj->executequery("Select * from subdivision_master where unit_id='$unitid' order by subdivision_id asc");
                                                foreach ($res as $key) {
                                                    $division_name = $obj->getvalfield("division_master", "division_name", "division_id=' $key[division_id]'"); ?>
                                                    <option value="<?= $key['subdivision_id']; ?>">
                                                        <?= $key['sub_division_name']; ?> / <?= $division_name ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('subdivision_id').value =
                                                    '<?= $subdivision_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="department_name" class="form-label">Department Name<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="department_name" name="department_name" class="form-control form-control-sm" placeholder="Enter Department Name" value="<?php echo $department_name ?>" autocomplete="off" />
                                        </div>
                                        <div class="col-lg-3 mb-3 mt-4">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    name="c_off_check"
                                                    id="c_off_check"
                                                    value="1"
                                                    <?= ($c_off_check == 1) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="c_off_check">
                                                    C-Off Allowed
                                                </label>
                                            </div>
                                            <small class="text-danger">
                                                Click to allow C-Off for this department.
                                            </small>
                                        </div>

                                         <div class="col-lg-3 col-12">
                                            <label for="emp_id" class="form-label">Reporting Manager Name<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id"
                                                id="emp_id">
                                                <option value="">Select Employee</option>
                                                <?php
                                                    //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                    $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                    foreach ($res as $key) { ?>
                                                <option value="<?= $key['emp_id']; ?>">
                                                    <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?>
                                                    <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById('emp_id').value =
                                                '<?= $emp_id; ?>';
                                            </script>
                                        </div>

                                         <div class="col-lg-3 mb-3 mt-4">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    name="allow_add_leave"
                                                    id="allow_add_leave"
                                                    value="1"
                                                    <?= ($allow_add_leave == 1) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="allow_add_leave">
                                                   Add Leave on Week Offs
                                                </label>
                                            </div> 
                                        </div>

                                        
                                        <?php $chkadd = $obj->check_addBtn($pagename, $loginid);
                                        if ($chkadd == 1) {  ?>
                                            <div class="col-lg-4 mb-3 mt-2">
                                                <br>
                                                <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                                <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> " onClick="return checkinputmaster('department_name,emp_id')">
                                                <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                            </div>
                                        <?php } ?>
                                    </div>
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

        function funDel(id) {
            $('#deleteRecordModal').modal('show');
            tblname = '<?php echo $tblname; ?>';
            tblpkey = '<?php echo $tblpkey; ?>';
            pagename = '<?php echo $pagename; ?>';
            submodule = '<?php echo $submodule; ?>';

            $('#delete-record').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/delete_master.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' + submodule + '&pagename=' + pagename,
                    dataType: 'html',
                    success: function(data) {
                        location = '<?php echo $pagename; ?>';
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
    </script>
</body>

</html>