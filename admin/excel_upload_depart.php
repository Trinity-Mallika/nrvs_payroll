<?php include("../adminsession.php");
$pagename = "emp_overtime.php";
$title = "Employee Overtime";
$tblname = "emp_overtime";
$tblpkey = "overtime_id";
$module = "Employee Overtime";
$submodule = "Employee Overtime List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
require_once __DIR__ . '/src/SimpleXLSX.php';

function getOrCreateId($obj, $table, $tblid, $nameField, $nameValue, $extraWhere, $insertData)
{
    $id = $obj->getvalfield(
        $table,
        $tblid,
        "$nameField='$nameValue' $extraWhere"
    );
    if (!$id) {
        $obj->insert_record($table, $insertData);
        $id = $obj->getvalfield(
            $table,
            $tblid,
            "$nameField='$nameValue' $extraWhere"
        );
    }
    return $id;
}


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

                    list($division, $subdivision, $department, $designation) = $data;
                    if (!$division || !$subdivision || !$department || !$designation) {
                        continue;
                    }

                    $division_id = getOrCreateId(
                        $obj,
                        "division_master",
                        "division_id",
                        "division_name",
                        trim($division),
                        "AND unit_id='$unitid'",
                        [
                            "division_name" => trim($division),
                            "createdby" => $loginid,
                            "ipaddress" => $ipaddress,
                            "createdate" => $createdate,
                            "unit_id" => $unitid,
                            "sessionid" => $sessionid
                        ]
                    );

                    $subdivision_id = getOrCreateId(
                        $obj,
                        "subdivision_master",
                        "subdivision_id",
                        "sub_division_name",
                        trim($subdivision),
                        "AND division_id='$division_id' AND unit_id='$unitid'",
                        [
                            "division_id" => $division_id,
                            "sub_division_name" => trim($subdivision),
                            "createdby" => $loginid,
                            "ipaddress" => $ipaddress,
                            "createdate" => $createdate,
                            "unit_id" => $unitid,
                            "sessionid" => $sessionid
                        ]
                    );


                    $department_id = getOrCreateId(
                        $obj,
                        "department_master",
                        "department_id",
                        "department_name",
                        trim($department),
                        "AND division_id='$division_id' AND subdivision_id='$subdivision_id' AND unit_id='$unitid'",
                        [
                            "division_id" => $division_id,
                            "subdivision_id" => $subdivision_id,
                            "department_name" => trim($department),
                            "createdby" => $loginid,
                            "ipaddress" => $ipaddress,
                            "createdate" => $createdate,
                            "unit_id" => $unitid,
                            "sessionid" => $sessionid
                        ]
                    );

                    $designation_id = getOrCreateId(
                        $obj,
                        "designation_master",
                        "designation_id",
                        "designation",
                        trim($designation),
                        "AND department_id='$department_id' AND unit_id='$unitid'",
                        [
                            "department_id" => $department_id,
                            "designation" => trim($designation),
                            "createdby" => $loginid,
                            "ipaddress" => $ipaddress,
                            "createdate" => $createdate,
                            "unit_id" => $unitid,
                            "sessionid" => $sessionid
                        ]
                    );
                }
            }
        }
    }

    echo "<script>alert('Excel data uploaded successfully');location.href='$pagename';</script>";
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
                                                <h5 class="card-title mb-0">File Upload <a href="overtime_excel.xlsx" class="float-end btn btn-primary btn-sm">Download Sample File</a></h5>
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
                                        <div class="col-lg-4 mt-2">
                                            <br>
                                            <input type="submit" name="upload_excel" class="btn btn-sm btn-primary add-btn" value="Upload" onClick="return checkinputmaster('file_month,file_year,file_upload')">
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