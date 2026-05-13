<?php include("../adminsession.php");
$pagename = "pf_excel.php";
$title = "PF Excel Upload";
$tblname = "salary_structure";
$tblpkey = "salary_struc_id";
$module = "PF Excel Upload";
$submodule = "PF Excel Upload List";
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

                    list($emp_code, $emp_name, $pf_emp) = $data;

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

                    // Yes / No convert
                    if (!empty($pf_emp)) {
                        $is_pf = 1;
                    } else {
                        $is_pf = 0;
                    }

                    // Update record
                    $obj->update_record(
                        'employee_master',
                        ['emp_id' => $emp_id, 'unit_id' => $unitid],
                        ['is_pf' => $is_pf, 'lastupdated' => $createdate]
                    );
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
                                                <h5 class="card-title mb-0">File Upload <a href="pf_excel.xlsx" class="float-end btn btn-primary btn-sm">Download Sample File</a></h5>
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