<?php include("../adminsession.php");
$pagename = "emp_separation.php";
$title = "Employee Separation Master";
$tblname = "employee_exit";
$tblpkey = "exit_id";
$module = "Employee Separation Master";
$submodule = "Employee Separation Master List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

if (isset($_POST['submit'])) {

    $emp_id  = $obj->test_input($_POST['emp_id']);
    $exit_type  = $obj->test_input($_POST['exit_type']);
    $resignation_date  = $obj->test_input($_POST['resignation_date']);
    $last_working_date  = $obj->test_input($_POST['last_working_date']);
    $notice_period  = $obj->test_input($_POST['notice_period']);
    $reason_for_leaving  = $obj->test_input($_POST['reason_for_leaving']);
    $is_approved  = '0';

    $count = $obj->getvalfield($tblname, "count(*)", "emp_id='$emp_id' and unit_id='$unitid' and is_approved='$is_approved' and $tblpkey!='$keyvalue'");

    $form_data = array(
        "emp_id" => $emp_id,
        "exit_type" => $exit_type,
        "resignation_date" => $resignation_date,
        "last_working_date" => $last_working_date,
        "notice_period" => $notice_period,
        "reason_for_leaving" => $reason_for_leaving,
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
    $reason_for_leaving =  $sqledit['reason_for_leaving'];
    $notice_period =  $sqledit['notice_period'];
    $last_working_date =  $sqledit['last_working_date'];
    $resignation_date =  $sqledit['resignation_date'];
    $exit_type =  $sqledit['exit_type'];
    $emp_id =  $sqledit['emp_id'];
} else {
    $reason_for_leaving = "";
    $notice_period = "";
    $last_working_date = "";
    $resignation_date = "";
    $noticeexit_type_period = "";
    $emp_id = "";
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
                                                <h5 class="card-title mb-0"> <?= $module; ?> <a href="emp_separation_list.php" class="float-end btn btn-primary btn-sm">List</a> </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">
                                            <label for="division_id" class="form-label">Employee Name<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id" id="emp_id">
                                                <option value="">Select</option>
                                                <?php
                                                //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND resign_status != '1' ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?> <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('emp_id').value =
                                                    '<?= $emp_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="exit_type" class="form-label">Employee Exit Type<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="exit_type" id="exit_type">
                                                <option value="">Select</option>
                                                <option value="Resignation">Resignation</option>
                                                <option value="Termination">Termination</option>
                                                <option value="Absconded">Absconded</option>
                                                <option value="Contract End">Contract End</option>
                                                <option value="Retirement">Retirement</option>
                                            </select>
                                            <script>
                                                document.getElementById('exit_type').value =
                                                    '<?= $exit_type; ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label for="resignation_date" class="form-label">Resignation Date<span class="text-danger fw-bold">*</span></label>
                                            <input type="date" id="resignation_date" name="resignation_date" class="form-control form-control-sm" value="<?php echo $resignation_date ?>" autocomplete="off" />
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="last_working_date" class="form-label">Last Working Date (LWD)<span class="text-danger fw-bold">*</span></label>
                                            <input type="date" id="last_working_date" name="last_working_date" class="form-control form-control-sm" value="<?php echo $last_working_date ?>" autocomplete="off" />
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label for="notice_period" class="form-label">Notice Period (Days)<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="notice_period" name="notice_period" class="form-control form-control-sm" value="<?php echo $notice_period ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <label for="reason_for_leaving" class="form-label">Reason for Leaving<span class="text-danger fw-bold"> </span></label>
                                            <textarea type="text" id="reason_for_leaving" name="reason_for_leaving" class="form-control form-control-sm" autocomplete="off"><?php echo $reason_for_leaving ?></textarea>
                                        </div>


                                        <div class="col-lg-3 mb-3 mt-2">
                                            <br>
                                            <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> " onClick="return checkinputmaster('emp_id,exit_type,resignation_date,last_working_date,notice_period')">
                                            <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>
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