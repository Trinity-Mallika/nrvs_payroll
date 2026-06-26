<?php include("../adminsession.php");
$pagename = "account_open.php";
$title = "Account Open";
$tblname = "";
$tblpkey = "";
$module = "Account Open";
$submodule = "Account Open List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

if (isset($_POST['submit'])) {

    $sub_division_name  = $obj->test_input($_POST['sub_division_name']);
    $division_id  = $obj->test_input($_POST['division_id']);

    $count = $obj->getvalfield($tblname, "count(*)", "sub_division_name='$sub_division_name' and division_id='$division_id' and unit_id='$unitid' and $tblpkey!='$keyvalue'");

    $form_data = array(
        "sub_division_name" => $sub_division_name,
        "division_id" => $division_id,
        "createdby" => $loginid,
        "unit_id" => $unitid,
        "sessionid" => $sessionid,
        "ipaddress" => $ipaddress
    );
    if ($count > 0) {
        $action = 4;
        $process = "duplicate";
    } else {
        if ($keyvalue == 0) {
            $form_data["createdate"] = $createdate;
            $obj->insert_record($tblname, $form_data);
            $action = 1;
            $process = "insert";
        } else {
            $form_data["lastupdated"] = $createdate;
            $form_data["updatedby"] = $loginid;
            $where = array($tblpkey => $keyvalue);
            $obj->update_record($tblname, $where, $form_data);
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
    $sub_division_name =  $sqledit['sub_division_name'];
    $division_id =  $sqledit['division_id'];
} else {
    $sub_division_name = "";
    $division_id = "";
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

                <div class="row">
                    <div class="col-lg-12">

                        <div class="card">

                            <div class="card-header">
                                <h5>TO WHOM IT MAY CONCERN</h5>
                            </div>

                            <div class="card-body">

                                <form method="post" action="employee_concern_pdf.php" target="_blank">

                                    <div class="row">

                                        <div class="col-md-3 mb-3">
                                            <label>Employee Name<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" name="first_name" id="first_name"
                                                class="form-control form-control-sm">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Employee Code</label>
                                            <input type="text" name="emp_code" id="emp_code"
                                                class="form-control form-control-sm">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Father Name<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" name="father_name" id="father_name"
                                                class="form-control form-control-sm">
                                        </div>

                                        <div class="col-lg-3 ">
                                            <label for="department_id" class="form-label">Department<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="department_id" id="department_id"
                                                onchange="get_designation(this.value);">
                                                <option value="">Select</option>
                                                <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_name asc");
                                                        foreach ($res as $key) { ?>
                                                <option value="<?= $key['department_id']; ?>">
                                                    <?= $key['department_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById('department_id').value =
                                                '<?= $department_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 ">
                                            <label for="designation_id" class="form-label">Designation<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="designation_id" id="designation_id">
                                                <option value="">Please Select</option>

                                            </select>

                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label>Salary <span class="text-danger fw-bold">*</span></label>
                                            <input type="text" name="basic_salary" id="basic_salary"
                                                class="form-control form-control-sm">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label>Permanent Address <span class="text-danger fw-bold">*</span></label>
                                            <textarea name="permanent_address" class="form-control" rows="3"
                                                required></textarea>
                                        </div>

                                    </div>
                                    <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn"
                                        value="Print"
                                        onClick="return checkinputmaster('first_name,father_name,department_id,designation_id,basic_salary')">
                                </form>

                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>

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
    function get_designation(department_id, designation_id = 0) {
        const keyvalue = '0';
        $.ajax({
            type: "POST",
            url: 'ajax/ajax_fetch_family_details.php',
            data: {
                department_id: department_id,
                designation_id: designation_id,
                keyvalue: keyvalue,
                type: 'get_designation'
            },

            success: function(data) {
                $('#designation_id').html(data).trigger("change.select2");
            }
        });

    }
    </script>
</body>

</html>