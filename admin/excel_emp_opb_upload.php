<?php include("../adminsession.php");
$pagename = "excel_emp_opb_upload.php";
$title = "Employee Openig Balance";
$tblname = "emp_monthly_leave";
$tblpkey = "month_leave_id";
$module = "Employee Openig Balance";
$submodule = "Employee Openig Balance List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
require_once __DIR__ . '/src/SimpleXLSX.php';
 

if (isset($_POST['upload_excel'])) {

    $totalRecords = 0;
    $insertedCount = 0;
    $skippedCount = 0;
    $skippedEpicNumbers = array();
  
    if ( isset($_FILES['file_upload']['tmp_name']) && $_FILES['file_upload']['error'] == UPLOAD_ERR_OK ) {
        $fileType = pathinfo($_FILES['file_upload']['name'], PATHINFO_EXTENSION);
        $insertRows = [];
        $empIds = [];
        $firstRow = true;
 
        if ($fileType === 'xlsx' ) {
            if ($xlsx = SimpleXLSX::parse($_FILES['file_upload']['tmp_name'])) {
                foreach ($xlsx->rows() as $k => $data) {
                    
                    // skip header
                    if ($firstRow) {
                        $firstRow = false;
                        continue;
                    }
                    // columns
                    $emp_code      = trim($data[0] ?? '');
                    // $eoff          = trim($data[1] ?? '');
                    // $eoff2          = trim($data[2] ?? '');
                    $coff          = trim($data[1] ?? '');
                    // $lastColumnIndex = count($data) - 1;
                    // $coff = trim($data[$lastColumnIndex] ?? '');
              
                    $month =3;
                    $year = date('Y');
                    if (empty($emp_code)) {
                        continue;
                    }

                    $totalRecords++;
                    // default value
                    
                    $coff  = $coff === '' ? 0 : $coff;
                   

                    // get emp id
                    $emp_id = $obj->getvalfield(
                        "employee_master",
                        "emp_id",
                        "emp_code='$emp_code' AND unit_id='$unitid'"
                    );

                    if (empty($emp_id)) {

                        $skippedCount++;
                        $skippedEpicNumbers[] = $emp_code;
                        continue;
                    }

                    $empIds[] = $emp_id; 
                    $department_id = $obj->getvalfield("employee_master","department_id","emp_id='$emp_id'");
                    $insertRows[]= [
                        "emp_id" => $emp_id,
                        "department_id" => $department_id,
                        "month" => $month,
                        "year" => $year, 
                        "total_leave" => $coff,
                        "remining_leave" => $coff,
                        "is_opb" => 1,
                        "leave_type" => 'earning',
                        "unit_id" => $unitid,
                        "createdby" => $loginid,
                        "ipaddress" => $ipaddress,
                        "sessionid" => $sessionid,
                        "createdate" => date("Y-m-d H:i:s")
                    ];

                    $insertedCount++;
                }

                // // DELETE OLD DATA
                // if (!empty($empIds)) {
                //     $empIds = array_unique($empIds);
                //     $obj->bulk_delete(
                //         'emp_monthly_leave',
                //         [
                //             'emp_id'   => $empIds,
                //             'unit_id'  => $unitid,
                //             'sessionid'=> $sessionid
                //         ]
                //     );
                // }

                // INSERT NEW DATA
                if (!empty($insertRows)) {

                    foreach (array_chunk($insertRows, 500) as $chunk) {

                        $obj->bulk_insert(
                            'emp_monthly_leave',
                            $chunk
                        );
                    }
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
                                                <h5 class="card-title mb-0">File Upload <a href="emp_opb.xlsx" class="float-end btn btn-primary btn-sm">Download Sample File</a></h5>
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

    </script>
</body>

</html>