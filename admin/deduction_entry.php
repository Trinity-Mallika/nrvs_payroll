<?php include("../adminsession.php");
$pagename = "deduction_entry.php";
$title = "Employee Deduction";
$tblname = "emp_deduction";
$tblpkey = "deduction_id";
$module = "Employee Deduction";
$submodule = "Employee Deduction List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
require_once __DIR__ . '/src/SimpleXLSX.php';
if (isset($_POST['submit'])) {
    $emp_id  = $obj->test_input($_POST['emp_id']);
    $month = $obj->test_input($_POST['month']);
    $year = $obj->test_input($_POST['year']);
    $lpg_ded = $obj->test_input($_POST['lpg_ded']);
    $shoes_ded = $obj->test_input($_POST['shoes_ded']);
    $other = $obj->test_input($_POST['other']);

    $count = $obj->getvalfield($tblname, "count(*)", "emp_id='$emp_id' and month='$month'and year='$year' and $tblpkey!='$keyvalue'");
    $form_data = array(
        "emp_id" => $emp_id,
        "month" => $month,
        "year" => $year,
        "lpg_ded" => $lpg_ded,
        "shoes_ded" => $shoes_ded,
        "other" => $other,
        "unit_id" => $unitid,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid,
    );
    if ($count > 0) {
        $action = 4;
        $process = "duplicate";
    } else {
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
    }
    echo "<script>location='$pagename?action=$action'</script>";
}


if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $emp_id =  $sqledit['emp_id'];
    $month = $sqledit['month'];
    $year = $sqledit['year'];
    $lpg_ded = $sqledit['lpg_ded'];
    $shoes_ded = $sqledit['shoes_ded'];
    $other = $sqledit['other'];
} else {
    $emp_id = "";
    $month = "";
    $lpg_ded = "";
    $shoes_ded = "";
    $other = "";
    $year = "";
}


if (isset($_POST['upload_excel'])) {
    $totalRecords = 0;
    $insertedCount = 0;
    $skippedCount = 0;
    $skippedEpicNumbers = array();


    if (isset($_FILES['file_upload']['tmp_name']) && $_FILES['file_upload']['error'] == UPLOAD_ERR_OK) {
        // print_r('hii');
        // die;
        $fileType = pathinfo($_FILES['file_upload']['name'], PATHINFO_EXTENSION);
        $file_month  = $obj->test_input($_POST['file_month']);
        $file_year = $obj->test_input($_POST['file_year']);

        $firstRow = true;
        if ($fileType === 'xlsx') {
            if ($xlsx = SimpleXLSX::parse($_FILES['file_upload']['tmp_name'])) {
                foreach ($xlsx->rows() as $k => $data) {
                    if ($firstRow) {
                        $firstRow = false;
                        continue;
                    }

                    list($emp_code, $lpg_ded, $shoes_ded, $other_ded) = $data;

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

                    $isDuplicate = $obj->getvalfield($tblname, "COUNT(*)", "emp_id = '$emp_id' and month='$file_month' and year='$file_year'");

                    if ($isDuplicate > 0) {
                        $skippedEpicNumbers[] = $emp_id;
                        $skippedCount++;
                        continue;
                    }

                    $form_data1 = array(
                        "emp_id" => $emp_id,
                        "month" => $file_month,
                        "year" => $file_year,
                        "lpg_ded" => $lpg_ded,
                        "other" => $other_ded,
                        "shoes_ded" => $shoes_ded,
                        "createdby"   => $loginid,
                        "ipaddress"   => $ipaddress,
                        "sessionid"   => $sessionid,
                        "createdate"   => $createdate,
                        "unit_id"   => $unitid
                    );

                    $obj->insert_record($tblname, $form_data1);
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

                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> Employee Details
                                                    <a href="deduction_list.php" class="float-end btn btn-primary btn-sm">List</a>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="emp_id" class="form-label">Employee Name<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id" id="emp_id">
                                                <option value="">All</option>
                                                <?php
                                                //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?> <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('emp_id').value =
                                                    '<?= $emp_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="month" class="form-label">Month<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="month" id="month">
                                                <option value="">Select</option>
                                                <?php
                                                $months = [
                                                    1 => 'January',
                                                    2 => 'February',
                                                    3 => 'March',
                                                    4 => 'April',
                                                    5 => 'May',
                                                    6 => 'June',
                                                    7 => 'July',
                                                    8 => 'August',
                                                    9 => 'September',
                                                    10 => 'October',
                                                    11 => 'November',
                                                    12 => 'December'
                                                ];
                                                foreach ($months as $value => $name) {
                                                    echo "<option value=\"$value\">$name</option>";
                                                }
                                                ?>
                                            </select>
                                            <script>
                                                document.getElementById('month').value = '<?php echo $month ?>'
                                            </script>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="year" class="form-label">Year<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="year" id="year">
                                                <option value="">Select</option>
                                                <?php
                                                $startYear = 2025;
                                                $endYear = 2100;
                                                for ($year1 = $startYear; $year1 <= $endYear; $year1++) {
                                                    echo "<option value=\"$year1\">$year1</option>";
                                                } ?>
                                            </select>
                                            <script>
                                                document.getElementById('year').value = '<?php echo $year ?>'
                                            </script>
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <label for="lpg_ded" class="form-label">LPG Deduction<span class="text-danger"></span></label>
                                            <input type="text" id="lpg_ded" name="lpg_ded" class="form-control form-control-sm" placeholder="Enter LPG Deduction" value="<?= $lpg_ded ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <label for="shoes_ded" class="form-label">Shoes Deduction<span class="text-danger"></span></label>
                                            <input type="text" id="shoes_ded" name="shoes_ded" class="form-control form-control-sm" placeholder="Enter Shoes Deduction" value="<?= $shoes_ded ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="other" class="form-label">Other Deduction<span class="text-danger"></span></label>
                                            <input type="text" id="other" name="other" class="form-control form-control-sm" placeholder="Enter Other Deduction" value="<?= $other ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <?php $chkadd = $obj->check_addBtn($pagename, $loginid);
                                        if ($chkadd == 1) {  ?>
                                            <div class="col-lg-4 mb-3">
                                                <br>
                                                <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                                <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?>" onClick="return checkinputmaster('emp_id,month,year')">
                                                <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0">File Upload <a href="deduction_excel.xlsx" class="float-end btn btn-primary btn-sm">Download Sample File</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="month" class="form-label">Month<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="file_month" id="file_month">
                                                <option value="">Select</option>
                                                <?php
                                                $months = [
                                                    1 => 'January',
                                                    2 => 'February',
                                                    3 => 'March',
                                                    4 => 'April',
                                                    5 => 'May',
                                                    6 => 'June',
                                                    7 => 'July',
                                                    8 => 'August',
                                                    9 => 'September',
                                                    10 => 'October',
                                                    11 => 'November',
                                                    12 => 'December'
                                                ];
                                                foreach ($months as $value => $name) {
                                                    echo "<option value=\"$value\">$name</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="year" class="form-label">Year<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="file_year" id="file_year">
                                                <option value="">Select</option>
                                                <?php
                                                $startYear = 2025;
                                                $endYear = 2100;
                                                for ($year1 = $startYear; $year1 <= $endYear; $year1++) {
                                                    echo "<option value=\"$year1\">$year1</option>";
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <label for="file_upload" class="form-label">File Upload<span class="text-danger fw-bold">*</span></label>
                                            <input type="file" id="file_upload" name="file_upload" class="form-control form-control-sm" value="" autocomplete="off" />
                                        </div>
                                        <?php $chkadd = $obj->check_addBtn($pagename, $loginid);
                                        if ($chkadd == 1) {  ?>
                                            <div class="col-lg-4 mb-3">
                                                <br>
                                                <input type="submit" name="upload_excel" class="btn btn-sm btn-primary add-btn" value="Upload" onClick="return checkinputmaster('file_month,file_year,file_upload')">
                                            </div>
                                        <?php } ?>
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