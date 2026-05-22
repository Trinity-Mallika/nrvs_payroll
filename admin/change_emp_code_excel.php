<?php include("../adminsession.php");
$pagename = "change_emp_code_excel.php";
$title = "Change Emp Code Excel Upload";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Change Emp Code Excel Upload";
$submodule = "Change Emp Code Excel Upload List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
require_once __DIR__ . '/src/SimpleXLSX.php';

if (isset($_POST['upload_excel'])) {
    $totalRecords = 0;
    $successCount = 0;
    $skippedCount = 0;
    $skippedData = [];

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

                    // list($old_emp_code, $emp_name, $remark, $unit_name, $department_name, $new_emp_code) = $data;
                    list($old_emp_code, $new_emp_code, $unit_name) = $data;

                    $totalRecords++;

                    if (empty($old_emp_code) || empty($new_emp_code)) {
                        $skippedCount++;
                        $skippedData[] = "Row $totalRecords: Empty Old/New Emp Code";
                        continue;
                    }
                    $unit_id = $obj->getvalfield(
                        "unit_master",
                        "unit_id",
                        "unit_name LIKE '%" . addslashes(trim($unit_name)) . "%'"
                    );

                    if (empty($unit_id)) {
                        $skippedCount++;
                        $skippedData[] = "EmpCode $old_emp_code: Unit not found ($unit_name)";
                        continue;
                    }

                    $emp_id = $obj->getvalfield(
                        "employee_master",
                        "emp_id",
                        "emp_code='$old_emp_code' AND unit_id='$unit_id'",
                    );

                    if (empty($emp_id)) {
                        $skippedCount++;
                        $skippedData[] = "EmpCode $old_emp_code: Employee not found";
                        continue;
                    }

                    $duplicate = $obj->getvalfield(
                        "employee_master",
                        "emp_id",
                        "emp_code='$new_emp_code' AND unit_id='$unit_id'"
                    );

                    if (!empty($duplicate)) {
                        $skippedCount++;
                        $skippedData[] = "EmpCode $old_emp_code: New Emp Code already exists ($new_emp_code)";
                        continue;
                    };
                    $update = $obj->update_record(
                        "employee_master",
                        ["emp_id" => $emp_id],
                        ["emp_code" => $new_emp_code, "biomatric_id" => $new_emp_code, "lastupdated" => date('Y:m:d')]
                    );
                    $successCount++;
                }
            }
        }
    }

    echo "<script>
location='{$pagename}?action=1&total={$totalRecords}&success={$successCount}&skipped={$skippedCount}&reasons=" . urlencode(implode('|', $skippedData)) . "'
</script>";
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

                <?php if (isset($_GET['action']) && $_GET['action'] == 1) { ?>
                    <div class="alert alert-info">
                        <strong>Excel Upload Summary</strong><br>
                        Total Records : <b><?= $_GET['total'] ?></b><br>
                        Success : <b class="text-success"><?= $_GET['success'] ?></b><br>
                        Skipped : <b class="text-danger"><?= $_GET['skipped'] ?></b>
                    </div>

                    <?php if (!empty($_GET['reasons'])) { ?>
                        <div class="alert alert-warning">
                            <strong>Skipped Reasons:</strong><br>
                            <?php
                            $reasons = explode('|', urldecode($_GET['reasons']));
                            foreach ($reasons as $r) {
                                echo "• " . $r . "<br>";
                            }
                            ?>
                        </div>
                    <?php } ?>
                <?php } ?>
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