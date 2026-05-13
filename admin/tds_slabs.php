<?php include("../adminsession.php");
$pagename = "tds_slabs.php";
$title = "TDS Slab";
$tblname = "tds_slabs";
$tblpkey = "tds_slab_id";
$module = "TDS Slab";
$submodule = "TDS Slab List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';


if (isset($_POST['submit'])) {

    $min_income  = $obj->test_input($_POST['min_income']);
    $max_income  = $obj->test_input($_POST['max_income']);
    $tax_rate  = $obj->test_input($_POST['tax_rate']);


    $form_data = array(
        "min_income" => $min_income,
        "max_income" => $max_income,
        "tax_rate" => $tax_rate,
        "createdby" => $loginid,
        "unit_id" => $unitid,
        "sessionid" => $sessionid,
        "ipaddress" => $ipaddress
    );

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


    echo "<script>location='$pagename?action=$action'</script>";
}


if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $min_income =  $sqledit['min_income'];
    $max_income =  $sqledit['max_income'];
    $tax_rate =  $sqledit['tax_rate'];
} else {
    $min_income = '';
    $max_income = '';
    $tax_rate =  '';
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
                                                <h5 class="card-title mb-0"> <?= $module; ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">
                                            <label for="min_income" class="form-label">Min Income<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="min_income" name="min_income" class="form-control form-control-sm" placeholder="Enter Min Income" value="<?php echo $min_income ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="max_income" class="form-label">Max Income<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="max_income" name="max_income" class="form-control form-control-sm" placeholder="Enter Max Income" value="<?php echo $max_income ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="tax_rate" class="form-label">Rate Tax<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="tax_rate" name="tax_rate" class="form-control form-control-sm" placeholder="Enter Rate Tax" value="<?php echo $tax_rate ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-3 mb-3 mt-2">
                                            <br>
                                            <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> " onClick="return checkinputmaster('min_income,max_income,tax_rate')">
                                            <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
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
                                    <table id="buttons-datatables" class="display table table-sm table-bordered" style="width:100%">
                                        <thead>
                                            <tr class="table-primary">
                                                <th>Sr No.</th>
                                                <th>Min Income</th>
                                                <th>Max Income</th>
                                                <th>Tax Rate</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php
                                                $slno = 1;
                                                $res = $obj->executequery("select * from $tblname order by $tblpkey desc");
                                                foreach ($res as $row) {  ?>
                                                <tr>
                                                    <td><?php echo $slno++; ?></td>
                                                    <td><?php echo $row["min_income"]; ?></td>
                                                    <td><?php echo $row["max_income"]; ?></td>
                                                    <td><?php echo $row["tax_rate"]; ?></td>
                                                    <td>
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <li class="list-inline-item " data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                                <a href="<?php echo $pagename ?>?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>" class="edit-item-btn"><i class="ri-pencil-fill align-bottom text-success"></i></a>
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
    <?php include('inc/delete.php') ?>
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