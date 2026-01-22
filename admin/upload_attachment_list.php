<?php include("../adminsession.php");
$pagename = "upload_attachment.php";
$title = "Upload Attachment";
$tblname = "upload_attachments";
$tblpkey = "attachment_id";
$module = "Upload Attachment";
$submodule = "Upload Attachment List";
$btn_name = "Save";
$imgpath1 = 'uploaded/attachments/';
$btn_name = "Search";

$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = ' and 1=1';
$fromdate = $_GET['fromdate'] ?? '';
$todate = $_GET['todate'] ?? '';


if ($fromdate != '' && $todate != '') {
    $crit .= " AND  createdate BETWEEN '$fromdate' AND '$todate'";
} elseif ($fromdate != '') {
    $crit .= " AND  createdate >= '$fromdate'";
} elseif ($todate != '') {
    $crit .= " AND  createdate <= '$todate'";
}


if (isset($_GET['doc_id'])) {
    $doc_id = $obj->test_input($_GET['doc_id']);
    if ($doc_id != '') {
        $crit .= " and doc_id='$doc_id'";
    }
} else {
    $doc_id = "";
};

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
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a href="upload_attachment.php" class="float-end btn btn-primary btn-sm">Add New</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-lg-3 mb-2">
                                            <label for="" class="form-label">From Date/To Date </label>
                                            <div class="input-group input-group-sm">
                                                <input type="date" class="form-control form-control-sm" name="fromdate" id="fromdate" placeholder='dd-mm-yyyy' value="<?php echo $fromdate; ?>">
                                                <span class="input-group-text">To</span>
                                                <input type="date" class="form-control form-control-sm" name="todate" id="todate" placeholder='dd-mm-yyyy' value="<?php echo $todate; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="doc_id" class="form-label">Document Type<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="doc_id" id="doc_id">
                                                <option value="">Select</option>
                                                <?php $res = $obj->executequery("Select * from document_master where unit_id='$unitid' order by document_name asc");
                                                foreach ($res as $key) {
                                                ?>
                                                    <option value="<?= $key['doc_id']; ?>">
                                                        <?= $key['document_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('doc_id').value =
                                                    '<?= $doc_id; ?>';
                                            </script>
                                        </div>


                                        <div class="col-lg-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="Search">
                                            <a href="<?php echo $pagename ?>" class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> </h5>
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
                                                <th>Document Type</th>
                                                <th>Attachment</th>
                                                <th>Remark</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $slno = 1;
                                            $res = $obj->executequery("SELECT * FROM $tblname where unit_id='$unitid' $crit ORDER BY $tblpkey desc ");
                                            foreach ($res as $row) {
                                                $doc_name = $obj->getvalfield("document_master", "document_name", "doc_id='$row[doc_id]'");

                                            ?>
                                                <tr>
                                                    <td><?php echo $slno++; ?></td>
                                                    <td><?= $doc_name; ?></td>

                                                    <td><a href="<?= $imgpath1 . $row['upload_attachment'] ?>" class="btn btn-sm btn-primary" target="_blank">view</a></td>

                                                    <td><?php echo $row["remark"]; ?></td>
                                                    <td>
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <li class="list-inline-item " data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                                <a href="upload_attachment.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>" class="edit-item-btn">
                                                                    <i class="ri-pencil-fill align-bottom text-success"></i>
                                                                </a>
                                                            </li>

                                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                                <a class="remove-item-btn" type="button" onclick="funDel('<?php echo $row[$tblpkey]; ?>','<?php echo $row['upload_attachment']; ?>');">
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
            $(".chosen-select").select2({
                width: '100%',
                search_contains: true
            });

        });

        function funDel(id, imgname) {
            $('#deleteRecordModal').modal('show');
            tblname = '<?php echo $tblname; ?>';
            tblpkey = '<?php echo $tblpkey; ?>';
            var keyvalue = '<?= $keyvalue; ?>';
            var imgpath = '<?= $imgpath1; ?>';
            $('#delete-record').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/delete_attachment_img.php',
                    data: {
                        id: id,
                        tblname: tblname,
                        tblpkey: tblpkey,
                        imgname: imgname,
                        imgpath: imgpath
                    },
                    success: function(data) {
                        location.reload();
                    }
                });
                $('#deleteRecordModal').modal('hide');
            });
        }

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