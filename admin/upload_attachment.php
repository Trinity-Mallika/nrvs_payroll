<?php include("../adminsession.php");
$pagename = "upload_attachment.php";
$title = "Upload Attachment";
$tblname = "upload_attachments";
$tblpkey = "attachment_id";
$module = "Upload Attachment";
$submodule = "Upload Attachment List";
$btn_name = "Save";
$imgpath1 = 'uploaded/attachments/';
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

if (isset($_POST['submit'])) {
    $doc_id  = $obj->test_input($_POST['doc_id']);
    $remark  = $obj->test_input($_POST['remark']);
    $imageName = $_FILES["upload_attachment"]['name'];
    $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'jfif', 'xlsx'];

    $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

    $form_data = array(
        "doc_id" => $doc_id,
        "remark" => $remark,
        "createdby" => $loginid,
        "sessionid"   => $sessionid,
        "unit_id"   => $unitid,
        "ipaddress" => $ipaddress
    );

    if ($keyvalue == 0) {
        if (isset($_FILES["upload_attachment"]) && !empty($_FILES["upload_attachment"]['name'])) {
            $imageFileType = strtolower(pathinfo($_FILES["upload_attachment"]['name'], PATHINFO_EXTENSION));
            if (in_array($imageFileType, $allowedTypes)) {
                $upload_attachment = $obj->uploadImage($imgpath1, $_FILES["upload_attachment"]);
                $form_data['upload_attachment'] = $upload_attachment;
            }
        }
        $form_data["createdate"] = $createdate;
        // print_r($form_data);
        // die;
        $obj->insert_record($tblname, $form_data);
        $action = 1;
        $process = "insert";
    } else {

        if (!empty($imageName) && in_array($imageFileType, $allowedTypes)) {
            $old = $obj->getvalfield($tblname, "upload_attachment", "attachment_id='$keyvalue'");
            if (!empty($old)) {
                @unlink($imgpath1 . $old);
            }
            $filename = $obj->uploadImage($imgpath1, $_FILES["upload_attachment"]);
            $form_data['upload_attachment'] = $filename;
        }
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
    $doc_id =  $sqledit['doc_id'];
    $remark =  $sqledit['remark'];
    $upload_attachment =  $sqledit['upload_attachment'];
    $img = '';
} else {
    $doc_id = "";
    $remark = "";
    $upload_attachment = "";
    $img = 'upload_attachment';
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
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?> <a href="upload_attachment_list.php" class="float-end btn btn-primary btn-sm">Attachment List</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <label for="doc_id" class="form-label">Document Type<span
                                                    class="text-danger fw-bold">*</span></label>
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
                                        <div class="col-md-3">
                                            <label class="form-label">
                                                Attachment <span class="text-danger fw-bold">*</span>
                                            </label>

                                            <input type="file" class="form-control form-control-sm"
                                                name="upload_attachment" id="upload_attachment" value="<?= $upload_attachment ?>">

                                            <?php if (!empty($upload_attachment)) {
                                                $ext = strtolower(pathinfo($upload_attachment, PATHINFO_EXTENSION));
                                            ?>
                                                <div class="mt-2">
                                                    <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) { ?>
                                                        <img src="<?= $imgpath1 . $upload_attachment ?>"
                                                            style="height:50px;border:1px solid #ccc;">
                                                    <?php } else { ?>
                                                        <a href="<?= $imgpath1 . $upload_attachment ?>"
                                                            target="_blank" class="btn btn-sm btn-secondary">
                                                            View Uploaded <?= strtoupper($ext) ?>
                                                        </a>
                                                    <?php } ?>
                                                </div>

                                                <input type="hidden" name="old_attachment"
                                                    value="<?= $upload_attachment ?>">
                                            <?php } ?>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <label for="remark" class="form-label">Remark </label>
                                            <textarea type="text" id="remark" name="remark" class="form-control form-control-sm" value="<?php echo $remark ?>" autocomplete="off"></textarea>
                                        </div>

                                        <div class="col-lg-4 mb-3 mt-2">
                                            <br>
                                            <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> " onClick="return checkinputmaster('doc_id,<?= $img ?>')">
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