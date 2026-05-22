<?php include("../adminsession.php");

$pagename = "mngmt_privilege.php";
$title = "Management Privilage Entry";
$module = "Management Privilage Master";
$submodule = "Management Privilage Master";
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

if (isset($_GET['action']))
    $action = addslashes(trim($_GET['action']));
else
    $action = "";

if (isset($_POST['submit'])) {

    $userid = $_POST['userid'];
    $page_id = isset($_POST['page_id']) ? $_POST['page_id'] : [];

    if ($userid != '') {
        $where = array('userid' => $userid);
        $obj->delete_record($tblname, $where);
        for ($i = 0; $i < sizeof($page_id); $i++) {
            $form_data = array('userid' => $userid, 'page_id' => $page_id[$i], 'ipaddress' => $ipaddress, 'createdate' => $createdate, 'createdby' => $loginid, 'type' => 'mngmt');
            $obj->insert_record($tblname, $form_data);
            $action = 1;
            $process = "insert";
        }
    }
    echo "<script>location='$pagename?userid=$userid&action=$action'</script>";
}

if (isset($_GET[$tblpkey])) {

    $btn_name = "Update";
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
                    <div class="col-lg-12">
                        <fieldset class="mt-2">
                            <form action="" method="post">
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
                                            <div class="col-md-3">
                                                <strong> <label for="userid">Select User<span class="text-danger fw-bold"> *</span></label></strong>
                                                <div class="input-group mb-3">
                                                    <select autofocus name="userid" id="userid" class="chosen-select form-control" onchange="getusertype(this.value);">
                                                        <option value="">---Select User Type---</option>
                                                        <?php
                                                        $result = $obj->executequery("Select * from user where usertype='management' order by username asc");
                                                        foreach ($result as $row_get) {
                                                        ?>
                                                            <option value="<?php echo $row_get['userid']; ?>"><?php echo $row_get['username']; ?></option>
                                                        <?php  } ?>
                                                    </select>
                                                    <script>
                                                        document.getElementById('userid').value = '<?php echo  $userid; ?>';
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($userid > 0) { ?>
                                    <div class="row mt-4 mb-4">
                                        <div class="col-lg-12">
                                            <div class="card">
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
                                                        <table id="buttons-datatables" class="display table table-sm table-bordered" style="width:100%">
                                                            <thead>
                                                                <tr class="table-primary">
                                                                    <th>
                                                                        Page Menu
                                                                    </th>
                                                                    <th>
                                                                        <input type="checkbox" id="checkAllPages" /> Select All Pages
                                                                    </th>

                                                                </tr>
                                                            </thead>

                                                            <?php

                                                            //$where = array("menuname" => "Master");
                                                            $where = array("type" => "mngmt");

                                                            $sql_get = $obj->select_data("m_userprivilege", $where);

                                                            foreach ($sql_get as $row_get) {

                                                                $page_id = $row_get['page_id'];

                                                                $where = array("page_id" => $page_id, "userid" => $userid);

                                                                $module_page = $obj->count_method("privilage_setting", $where);

                                                                $pagedit = $obj->getvalfield("privilage_setting", "pagedit", "page_id='$page_id' and userid ='$userid'");

                                                                $pagedel = $obj->getvalfield("privilage_setting", "pagedel", "page_id='$page_id' and userid ='$userid'");
                                                            ?>
                                                                <tr>

                                                                    <td>
                                                                        <label class="fw-semibold">
                                                                            &nbsp;<?php echo $row_get['menuname']; ?>
                                                                        </label>
                                                                    </td>
                                                                    <td style="width:50%">
                                                                        <label style="width:100%" class="fw-semibold">
                                                                            <input type="checkbox" name="page_id[]" value="<?php echo $row_get['page_id']; ?>" <?php if ($module_page != '0') { ?> checked <?php } ?> />
                                                                            &nbsp;<?php echo $row_get['page_heading']; ?>
                                                                        </label>
                                                                    </td>


                                                                </tr>
                                                            <?php } ?>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center mb-2">
                                        <button type="submit" name="submit" class="btn btn-primary" onClick="return checkinputmaster('userid'); ">
                                            <?php echo $btn_name; ?></button>
                                        <a href="<?php echo $pagename; ?>" name="reset" id="reset" class="btn btn-success">Reset</a>
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
                    document.querySelectorAll("input[name='page_id[]']").forEach(cb => cb.checked = this.checked);
                });

            });
        }
    </script>
</body>

</html>