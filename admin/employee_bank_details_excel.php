<?php include("../adminsession.php");
$pagename = "employee_bank_details_excel.php";
$title = "Bank Details Excel Upload";
$tblname = "emp_bank_details";
$tblpkey = "emp_bank_id";
$module = "Bank Details Excel Upload";
$submodule = "Bank Details Excel Upload List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
require_once __DIR__ . '/src/SimpleXLSX.php';


if (isset($_POST['upload_excel'])) {
    $totalRecords = 0;
    $insertedCount = 0;
    $skippedCount = 0;
    $skippedEpicNumbers = array();

    if (isset($_FILES['file_upload']['tmp_name']) && $_FILES['file_upload']['error'] == UPLOAD_ERR_OK) {
        $fileType = pathinfo($_FILES['file_upload']['name'], PATHINFO_EXTENSION);

        $firstRow = true;
        if ($fileType === 'xlsx') {
            if ($xlsx = SimpleXLSX::parse($_FILES['file_upload']['tmp_name'])) {
                foreach ($xlsx->rows() as $k => $data) {
                    if ($firstRow) {
                        $firstRow = false;
                        continue;
                    }

                    list($emp_code, $bank_name, $acc_holder_name, $acc_no, $ifsc_code) = $data;


                    if (empty($emp_code)) {
                        continue;
                    }
                    $emp_id = 0;
                    if (!empty(trim($emp_code))) {
                        $emp_id = $obj->getvalfield(
                            "employee_master",
                            "emp_id",
                            "emp_code='$emp_code' AND unit_id='$unitid'"

                        );
                    }

                    $totalRecords++;

                    if ($emp_id == 0 || $emp_id == "") {
                        $skippedCount++;
                        $skippedEpicNumbers[] = $emp_code;
                        continue;
                    }
                    $bank_id = 0;
                    if (!empty(trim($bank_name))) {
                        $bank_name = trim($bank_name);

                        $bank_id = $obj->getvalfield(
                            "bank_master",
                            "bank_id",
                            "bank_name LIKE '%" . addslashes($bank_name) . "%'
                     AND unit_id='$unitid'"
                        );

                        if ($bank_id == 0) {
                            $bank_id = $obj->insert_record_lastid(
                                "bank_master",
                                [
                                    "bank_name" => trim($bank_name),
                                    "createdby" => $loginid,
                                    "unit_id"   => $unitid,
                                    "ipaddress" => $ipaddress
                                ]
                            );
                        }
                    }

                    $form_data = array(
                        "emp_id"   => $emp_id,
                        "bank_id"   => $bank_id,
                        "acc_holder_name"   => $acc_holder_name,
                        "ifsc_code"   => $ifsc_code,
                        "account_no"   => $acc_no,
                        "is_active"   => 1,
                        "createdby"   => $loginid,
                        "ipaddress"   => $ipaddress,
                        "sessionid"   => $sessionid,
                        "unit_id"   => $unitid
                    );

                    $obj->insert_record($tblname, $form_data);
                    $insertedCount++;
                }
            }
        }
    }
    echo "<script>location='$pagename?action=1&total=$totalRecords&inserted=$insertedCount&skipped=$skippedCount&skipped_epics=" . implode(",", $skippedEpicNumbers) . "'</script>";
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
                <?php include('inc/alert.php');
                ?>
                <?php if (isset($_GET['action']) && $_GET['action'] == 1) { ?>
                    <div class="alert alert-info">
                        <strong>Excel Upload Summary</strong><br>
                        Total Records : <b><?= $_GET['total'] ?? 0 ?></b><br>
                        Updated Records : <b class="text-success"><?= $_GET['inserted'] ?? 0 ?></b><br>
                        Skipped Records : <b class="text-danger"><?= $_GET['skipped'] ?? 0 ?></b>
                    </div>

                    <?php if (!empty($_GET['skipped_epics'])) { ?>
                        <div class="alert alert-warning">
                            <strong>Skipped Employee Codes:</strong><br>
                            <?= $_GET['skipped_epics']; ?>
                        </div>
                    <?php } ?>
                <?php } ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0">File Upload <a href="esic_excel.xlsx" class="float-end btn btn-primary btn-sm">Download Sample File</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-lg-4 mb-3">
                                            <label for="file_upload" class="form-label">File Upload<span class="text-danger fw-bold">*</span></label>
                                            <input type="file" id="file_upload" name="file_upload" class="form-control form-control-sm" value="" autocomplete="off" />
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <br>
                                            <input type="submit" name="upload_excel" class="btn btn-sm btn-primary add-btn" value="Upload" onClick="return checkinputmaster('file_upload')">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>


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