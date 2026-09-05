<?php include("../adminsession.php");

$pagename = "user_privilage.php";
$title = "User Privilege Entry";
$module = "User Privilege Master";
$submodule = "User Privilege Master";
$btn_name = "Save";
$keyvalue = 0;
$tblname = "privilage_setting";
$tblpkey = "privilage_id";

if (isset($_GET['userid']))
    $userid = $_GET['userid'];
else
    $userid = '';

$dup = '';

$page_id = '';
$pagedit = "";
$pagedel = "";
$page_add = "";
$page_print = "";
$page_approve = "";
$page_special = "";

if (isset($_GET['action']))
    $action = addslashes(trim($_GET['action']));
else
    $action = "";

if (isset($_POST['submit'])) {

    $userid = $_POST['userid'];
    $page_id = isset($_POST['page_id']) ? $_POST['page_id'] : [];
    $pagedit = isset($_POST['pagedit']) ? $_POST['pagedit'] : [];
    $pagedel = isset($_POST['pagedel']) ? $_POST['pagedel'] : [];

    $page_add = isset($_POST['page_add']) ? $_POST['page_add'] : [];
    $page_print = isset($_POST['page_print']) ? $_POST['page_print'] : [];
    $page_approve = isset($_POST['page_approve']) ? $_POST['page_approve'] : [];
    $page_special = isset($_POST['page_special']) ? $_POST['page_special'] : [];

    if ($userid != '') {
        $where = array('userid' => $userid);
        $obj->delete_record($tblname, $where);
        for ($i = 0; $i < sizeof($page_id); $i++) {
            $form_data = array('userid' => $userid, 'page_id' => $page_id[$i], 'ipaddress' => $ipaddress, 'createdate' => $createdate, 'createdby' => $loginid, 'type' => 'hrms');
            $obj->insert_record($tblname, $form_data);
            $action = 1;
            $process = "insert";
        }

        if (is_countable($pagedit) && count($pagedit) > 0) {
            foreach ($pagedit as $key_edit => $value_edit) {
                $where = array('userid' => $userid, 'page_id' => $key_edit);
                $fdata = array('pagedit' => $value_edit);
                $obj->update_record($tblname, $where, $fdata);
            }
        }
        if (is_countable($pagedel) && count($pagedel) > 0) {
            foreach ($pagedel as $key_del => $value_del) {
                $where = array('userid' => $userid, 'page_id' => $key_del);
                $fdata = array('pagedel' => $value_del);
                $obj->update_record($tblname, $where, $fdata);
            }
        }
        if (is_countable($page_add) && count($page_add) > 0) {
            foreach ($page_add as $key_add => $value_add) {
                $where = array('userid' => $userid, 'page_id' => $key_add);
                $fdata = array('page_add' => $value_add);
                $obj->update_record($tblname, $where, $fdata);
            }
        }
        if (is_countable($page_print) && count($page_print) > 0) {
            foreach ($page_print as $key_print => $value_print) {
                $where = array('userid' => $userid, 'page_id' => $key_print);
                $fdata = array('page_print' => $value_print);
                $obj->update_record($tblname, $where, $fdata);
            }
        }
        if (is_countable($page_approve) && count($page_approve) > 0) {
            foreach ($page_approve as $key_approve => $value_approve) {
                $where = array('userid' => $userid, 'page_id' => $key_approve);
                $fdata = array('page_approve' => $value_approve);
                $obj->update_record($tblname, $where, $fdata);
            }
        }

        if (is_countable($page_special) && count($page_special) > 0) {
            foreach ($page_special as $key_special => $value_special) {
                $where = array('userid' => $userid, 'page_id' => $key_special);
                $fdata = array('page_special' => $value_special);
                $obj->update_record($tblname, $where, $fdata);
            }
        }
    }
    echo "<script>location='$pagename?userid=$userid&action=$action'</script>";
}

if (isset($_GET[$tblpkey])) {

    $btn_name = "Update";
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

.menu-header {
    background: linear-gradient(135deg, #4e73df, #224abe);
    color: #fff;
    font-weight: 600;
    font-size: 15px;
}

.type-header {
    background: #eef4ff;
    color: #0d6efd;
    font-weight: 600;
    border-left: 4px solid #0d6efd;
}

.page-row:hover {
    background: #f8f9fa;
}

.permission-label {
    font-size: 13px;
    font-weight: 500;
}

.permission-label input[type="checkbox"] {
    transform: scale(1.1);
}

.table> :not(caption)>*>* {
    vertical-align: middle;
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
                            <form action="" method="post">
                                <div class="card">
                                    <div class="card-header border-bottom-dashed">
                                        <div class="row g-4 align-items-center">
                                            <div class="col-sm">
                                                <div>
                                                    <h5 class="card-title mb-0">Copy Previlege</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong> <label for="fromuserid">From User<span
                                                            class="text-danger fw-bold"> *</span></label></strong>
                                                <div class="input-group mb-3">
                                                    <select autofocus name="fromuserid" id="fromuserid"
                                                        class="chosen-select form-control">
                                                        <option value="">---Select User Type---</option>
                                                        <?php
                                                        $result = $obj->executequery("Select * from user where usertype NOT IN ('management', 'super_management') order by username asc");
                                                        foreach ($result as $row_get) {
                                                            $unit_name = $obj->getvalfield("unit_master", "unit_name", "unit_id='$row_get[unit_id]'");
                                                        ?>
                                                        <option value="<?php echo $row_get['userid']; ?>">
                                                            <?= $row_get['username']; ?> / <?= $unit_name ?></option>
                                                        <?php  } ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <strong> <label for="touserid">To User<span class="text-danger fw-bold">
                                                            *</span></label></strong>
                                                <div class="input-group mb-3">
                                                    <select autofocus name="touserid" id="touserid"
                                                        class="chosen-select form-control">
                                                        <option value="">---Select User Type---</option>
                                                        <?php
                                                        $result = $obj->executequery("Select * from user where usertype NOT IN ('management', 'super_management') order by username asc");
                                                        foreach ($result as $row_get) {
                                                            $unit_name = $obj->getvalfield("unit_master", "unit_name", "unit_id='$row_get[unit_id]'");
                                                        ?>
                                                        <option value="<?php echo $row_get['userid']; ?>">
                                                            <?= $row_get['username']; ?> / <?= $unit_name ?></option>
                                                        <?php  } ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-3 mt-2">
                                                <br>
                                                <input type="button" class="btn btn-sm btn-primary"
                                                    value="Copy Privilege" onclick="copy_user_privilage();">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">

                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header border-bottom-dashed">
                                                <div class="row g-4 align-items-center">
                                                    <div class="col-sm">
                                                        <div>
                                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> <span
                                                                    class="text-danger"></span></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="col-md-3">
                                                    <strong> <label for="userid">Select User<span
                                                                class="text-danger fw-bold"> *</span></label></strong>
                                                    <div class="input-group mb-3">
                                                        <select autofocus name="userid" id="userid"
                                                            class="chosen-select form-control"
                                                            onchange="getusertype(this.value);">
                                                            <option value="">---Select User Type---</option>
                                                            <?php
                                                        $result = $obj->executequery("Select * from user where usertype NOT IN ('management', 'super_management') order by username asc");
                                                        foreach ($result as $row_get) {
                                                            $unit_name = $obj->getvalfield("unit_master", "unit_name", "unit_id='$row_get[unit_id]'");
                                                        ?>
                                                            <option value="<?php echo $row_get['userid']; ?>">
                                                                <?= $row_get['username']; ?> / <?= $unit_name ?>
                                                            </option>
                                                            <?php  } ?>
                                                        </select>
                                                        <script>
                                                        document.getElementById('userid').value =
                                                            '<?php echo  $userid; ?>';
                                                        </script>
                                                    </div>
                                                </div>
                                                <?php if ($userid >0) { ?>
                                                <table class="display table table-sm table-bordered" style="width:100%">
                                                    <thead>
                                                        <tr class="table-primary">
                                                            <th>
                                                                Page Menu
                                                            </th>
                                                            <th>
                                                                <input type="checkbox" id="checkAllPages"
                                                                    class="form-check-input" /> Select
                                                                All Pages
                                                            </th>

                                                            <th>
                                                                <label>
                                                                    <input type="checkbox" id="checkAllEdit"
                                                                        class="form-check-input m-0" />
                                                                    <span>Edit All</span>
                                                                </label>
                                                            </th>
                                                            <th>
                                                                <label>
                                                                    <input type="checkbox" id="checkAllDelete"
                                                                        class="form-check-input" /> Delete
                                                                    All
                                                                </label>
                                                            </th>
                                                            <th>
                                                                <label>
                                                                    <input type="checkbox" id="checkAllAdd"
                                                                        class="form-check-input" /> Add All
                                                                </label>
                                                            </th>
                                                            <th>
                                                                <label>
                                                                    <input type="checkbox" id="checkAllPrint"
                                                                        class="form-check-input" /> Print All
                                                                </label>

                                                            </th>
                                                            <th>
                                                                <label>
                                                                    <input type="checkbox" id="checkAllApprove"
                                                                        class="form-check-input" />
                                                                    Approve All
                                                                </label>
                                                            </th>
                                                            <th>
                                                                <label>
                                                                    <input type="checkbox" id="checkAllSpecial"
                                                                        class="form-check-input" />
                                                                    Special
                                                                </label>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <?php

                                                            //$where = array("menuname" => "Master");
                                                            $where = array("type" => "hrms");

                                                           $sql_get = $obj->executequery("
                                                                SELECT *
                                                                FROM m_userprivilege
                                                                WHERE type='hrms' and enable=1
                                                                ORDER BY menuname,page_type,page_heading
                                                            ");
                                                            $current_menu = '';
                                                            $current_type = '';
                                                            foreach ($sql_get as $row_get) {

                                                            if ($current_menu != $row_get['menuname']) {

                                                            $current_menu = $row_get['menuname'];
                                                            $current_type = '';

                                                            echo '
                                                            <tr class="table-secondary">
                                                                <td colspan="8">
                                                                    <h6 class="mb-0">'.$current_menu.'</h6>
                                                                </td>
                                                            </tr>';
                                                        }

                                                        // Page Type Heading
                                                        if ($current_type != $row_get['page_type']) {

                                                            $current_type = $row_get['page_type'];

                                                           

                                                            echo '
                                                            <tr class="text-center">
                                                                <td colspan="8">
                                                                    <strong class="text-primary fw-bold">'.$current_type.'</strong>
                                                                </td>
                                                            </tr>';
                                                        }

                                                                $page_id = $row_get['page_id'];

                                                                $where = array("page_id" => $page_id, "userid" => $userid);

                                                                $module_page = $obj->count_method("privilage_setting", $where);

                                                                $page_data = $obj->select_record("privilage_setting", ['page_id' => $page_id, 'userid' => $userid]);

                                                                $pagedit = $page_data['pagedit'] ?? '';
                                                                $pagedel = $page_data['pagedel'] ?? '';
                                                                $page_add = $page_data['page_add'] ?? '';
                                                                $page_print = $page_data['page_print'] ?? '';
                                                                $page_approve = $page_data['page_approve'] ?? '';
                                                                $page_special = $page_data['page_special'] ?? '';
                                                            ?>
                                                    <tr>

                                                        <td><input type="hidden" name="all_page_ids" id="all_page_ids">
                                                            <!-- <label class="fw-semibold">
                                                                &nbsp;<?php echo $row_get['menuname']; ?>
                                                            </label> -->
                                                        </td>
                                                        <td>
                                                            <label style="width:100%" class="fw-semibold">
                                                                <input type="checkbox" name="page_id[]"
                                                                    class="form-check-input"
                                                                    value="<?php echo $row_get['page_id']; ?>"
                                                                    <?php if ($module_page != '0') { ?> checked
                                                                    <?php } ?> />
                                                                &nbsp;<?php echo $row_get['page_heading']; ?>
                                                            </label>
                                                        </td>

                                                        <td>
                                                            <label>
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="pagedit[<?php echo $page_id; ?>]" value="1"
                                                                    <?php if ($pagedit == '1') { ?> checked
                                                                    <?php } ?> />
                                                                &nbsp;<span class="fw-bold"
                                                                    style="color:#00F;">Edit</span>
                                                            </label>
                                                        </td>

                                                        <td>
                                                            <label> <input type="checkbox" class="form-check-input"
                                                                    name="pagedel[<?php echo $page_id; ?>]" value="1"
                                                                    <?php if ($pagedel == '1') { ?> checked
                                                                    <?php } ?> />
                                                                &nbsp;<span class="fw-bold"
                                                                    style="color:#F00;">Delete</span>
                                                            </label>
                                                        </td>

                                                        <td>
                                                            <label> <input type="checkbox" class="form-check-input"
                                                                    name="page_add[<?php echo $page_id; ?>]" value="1"
                                                                    <?php if ($page_add == '1') { ?> checked
                                                                    <?php } ?> />
                                                                &nbsp;<span class="fw-bold"
                                                                    style="color:#28a745;">Add</span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <label> <input type="checkbox" class="form-check-input"
                                                                    name="page_print[<?php echo $page_id; ?>]" value="1"
                                                                    <?php if ($page_print == '1') { ?> checked
                                                                    <?php } ?> />
                                                                &nbsp;<span class="fw-bold"
                                                                    style="color:#0d6efd;">Print</span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <label> <input type="checkbox" class="form-check-input"
                                                                    name="page_approve[<?php echo $page_id; ?>]"
                                                                    value="1" <?php if ($page_approve == '1') { ?>
                                                                    checked <?php } ?> />
                                                                &nbsp;<span class="fw-bold"
                                                                    style="color:#6f42c1;">Approve</span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <label> <input type="checkbox" class="form-check-input"
                                                                    name="page_special[<?php echo $page_id; ?>]"
                                                                    value="1" <?php if ($page_special == '1') { ?>
                                                                    checked <?php } ?> />
                                                                &nbsp;<span class="fw-bold"
                                                                    style="color:#fd7e14;">Special</span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </table>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($userid >0) { ?>
                                <div class="col-md-12 text-center mb-2">
                                    <button type="submit" name="submit" class="btn btn-primary"
                                        onClick="return checkinputmaster('userid'); ">
                                        <?php echo $btn_name; ?></button>
                                    <a href="<?php echo $pagename; ?>" name="reset" id="reset"
                                        class="btn btn-success">Reset</a>
                                </div>
                                <?php } ?>

                            </form>
                        </fieldset>
                    </div>
                </div>


            </div>
            <!-- Content close-->
        </div>
    </div>
    <!-- script tag -->
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>

    <!-- script tag -->
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
    function getusertype(userid) {
        if (userid != '') {
            window.location.href = '?userid=' + userid;
        }
    }

    let userid = '<?= $userid ?>';
    if (userid > 0) {
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("checkAllPages").addEventListener("change", function() {
                document.querySelectorAll("input[name='page_id[]']").forEach(cb => cb.checked = this
                    .checked);
            });

            document.getElementById("checkAllEdit").addEventListener("change", function() {
                document.querySelectorAll("input[name^='pagedit']").forEach(cb => cb.checked = this
                    .checked);
            });

            document.getElementById("checkAllDelete").addEventListener("change", function() {
                document.querySelectorAll("input[name^='pagedel']").forEach(cb => cb.checked = this
                    .checked);
            });
            document.getElementById("checkAllAdd").addEventListener("change", function() {
                document.querySelectorAll("input[name^='page_add']").forEach(cb => cb.checked = this
                    .checked);
            });
            document.getElementById("checkAllPrint").addEventListener("change", function() {
                document.querySelectorAll("input[name^='page_print']").forEach(cb => cb.checked = this
                    .checked);
            });
            document.getElementById("checkAllApprove").addEventListener("change", function() {
                document.querySelectorAll("input[name^='page_approve']").forEach(cb => cb.checked = this
                    .checked);
            });
            document.getElementById("checkAllSpecial").addEventListener("change", function() {
                document.querySelectorAll("input[name^='page_special']").forEach(cb => cb.checked = this
                    .checked);
                document.querySelectorAll("input[name^='page_approve']").forEach(cb => cb.checked = this
                    .checked);
                document.querySelectorAll("input[name^='page_print']").forEach(cb => cb.checked = this
                    .checked);
                document.querySelectorAll("input[name^='pagedel']").forEach(cb => cb.checked = this
                    .checked);
                document.querySelectorAll("input[name^='page_add']").forEach(cb => cb.checked = this
                    .checked);
                document.querySelectorAll("input[name^='pagedit']").forEach(cb => cb.checked = this
                    .checked);
            });
        });
    }


    function copy_user_privilage() {
        var fromuserid = $("#fromuserid").val();
        var touserid = $("#touserid").val();
        if (fromuserid == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please select From User'
            });
            $("#fromuserid").focus();
            return false;
        }
        if (touserid == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please select To User'
            });
            $("#touserid").focus();
            return false;
        }
        if (fromuserid == touserid) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Selection',
                text: 'From User and To User cannot be same'
            });
            return false;
        }
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to copy all privileges to selected user.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Yes, Copy!',
            cancelButtonText: 'Cancel'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "ajax_copy_privilage.php",
                    type: "POST",
                    data: {
                        fromuserid: fromuserid,
                        touserid: touserid
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Please Wait...',
                            text: 'Copying privileges...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {

                        if ($.trim(response) == "success") {

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Privileges copied successfully!',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });

                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Something went wrong. Please try again.'
                        });
                    }
                });
            }
        });
    }
    </script>

</body>

</html>