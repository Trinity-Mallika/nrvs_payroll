<?php include("../adminsession.php");
$pagename = "emp_salary_update.php";
$title = "Employee Salary Update";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Employee Salary Update";
$submodule = "Employee Salary Update List";
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

                    list($emp_code,$present_salary, $increment, $new_salary) = $data;

                    if (empty($emp_code)) {
                        continue;
                    }

                    $emp_id = $obj->getvalfield(
                        "employee_master",
                        "emp_id",
                        "emp_code='$emp_code' AND unit_id='$unitid'"
                    );

                    $totalRecords++;

                    /* 🔹 VALIDATION */
                    if (!is_numeric($new_salary) || $new_salary <= 0) {
                        $skippedCount++;
                        continue;
                    }

                    if ($emp_id > 0) {

                        // get current salary
                        $current_basic = $obj->getvalfield(
                            "employee_master",
                            "basic_salary",
                            "emp_id='$emp_id'"
                        );

                        // update
                        $update_data = array(
                            "prev_salary" => $current_basic,
                            "basic_salary" => $new_salary
                        );

                        $obj->update_record(
                            "employee_master",
                            array("emp_id" => $emp_id),
                            $update_data
                        );

                        $insertedCount++;
                    } else {
                        $skippedCount++;
                        $skippedEpicNumbers[] = $emp_code;
                    }
                }
            }
        }
    }

    echo "<script>location='$pagename?action=1&total=$totalRecords&inserted=$insertedCount&skipped=$skippedCount&skipped_epics=" . implode(",", $skippedEpicNumbers) . "'</script>";
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
                                                <h5 class="card-title mb-0">Employee Salary Update <a
                                                        href="new_salary_excel.xlsx"
                                                        class="float-end btn btn-primary btn-sm">Download Sample
                                                        File</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <label for="file_upload" class="form-label">File Upload<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <input type="file" id="file_upload" name="file_upload"
                                                class="form-control form-control-sm" value="" autocomplete="off" />
                                        </div>
                                      
                                            <div class="col-lg-4 mb-3">
                                                <br>
                                                <input type="submit" name="upload_excel"
                                                    class="btn btn-sm btn-primary add-btn" value="Upload"
                                                    onClick="return checkinputmaster('file_upload')">

                                            </div>
                                         
                                    </div>

                                    <?php if (isset($_GET['action']) && $_GET['action'] == 1) { ?>
                                        <div class="col-lg-12 mt-3">
                                            <div class="alert alert-success shadow-sm" style="border-left:5px solid #28a745;">

                                                <h6 class="mb-2"><i class="ri-checkbox-circle-fill"></i> Upload Summary</h6>

                                                <p class="mb-1">
                                                    <strong>Total Records:</strong> <?= $_GET['total'] ?? 0 ?>
                                                </p>

                                                <p class="mb-1 text-success">
                                                    <strong>Inserted (Updated):</strong> <?= $_GET['inserted'] ?? 0 ?>
                                                </p>

                                                <p class="mb-1 text-danger">
                                                    <strong>Skipped:</strong> <?= $_GET['skipped'] ?? 0 ?>
                                                </p>

                                                <?php if (!empty($_GET['skipped_epics'])) { ?>
                                                    <p class="mb-0 text-warning">
                                                        <strong>Skipped Emp Codes:</strong> <?= $_GET['skipped_epics'] ?>
                                                    </p>
                                                <?php } ?>

                                            </div>
                                        </div>
                                    <?php } ?>
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
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' +
                        submodule + '&pagename=' + pagename,
                    dataType: 'html',
                    success: function(data) {
                        location = '<?php echo $pagename; ?>';
                    }
                });
                $('#deleteRecordModal').modal('hide');
            });
        };
    </script>
</body>

</html>