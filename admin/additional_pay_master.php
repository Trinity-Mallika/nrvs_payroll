<?php include("../adminsession.php");
$pagename = "additional_pay_master.php";
$title = "Additional Payment Master";
$tblname = "additional_payment";
$tblpkey = "add_payid";
$module = "Additional Payment Master";
$submodule = "Additional Payment Master List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
require_once __DIR__ . '/src/SimpleXLSX.php';


function calculateTotalAdditionalPayment(
    $basic_arear = 0,
    $other_reimbursement = 0,
    $increment_arear = 0,
    $bonus = 0,
    $leave_encasement = 0,
    $notice_period = 0
) {
    return
        floatval($basic_arear) +
        floatval($other_reimbursement) +
        floatval($increment_arear) +
        floatval($bonus) +
        floatval($leave_encasement) +
        floatval($notice_period);
}


if (isset($_POST['submit'])) {
    $emp_id  = $obj->test_input($_POST['emp_id']);
    $month = $obj->test_input($_POST['month']);
    $year = date("Y");
    $basic_arear = $obj->test_input($_POST['basic_arear']);
    $other_reimbursement = $obj->test_input($_POST['other_reimbursement']);
    $increment_arear = $obj->test_input($_POST['increment_arear']);
    $bonus = $obj->test_input($_POST['bonus']);
    $leave_encasement = $obj->test_input($_POST['leave_encasement']);
    $notice_period = $obj->test_input($_POST['notice_period']);
    $total_additional_payment = $obj->test_input($_POST['total_additional_payment']);
    $remark = $obj->test_input($_POST['remark']);

    $form_data = array(
        "emp_id" => $emp_id,
        "month" => $month,
        "year" => $year,
        "basic_arear" => $basic_arear,
        "other_reimbursement" => $other_reimbursement,
        "increment_arear" => $increment_arear,
        "bonus" => $bonus,
        "leave_encasement" => $leave_encasement,
        "notice_period" => $notice_period,
        "total_additional_payment" => $total_additional_payment,
        "remark" => $remark,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid,
    );

    $count = $obj->getvalfield($tblname, "count(*)", "emp_id='$emp_id' and month='$month' and year='$year' and $tblpkey!='$keyvalue'");
    if ($count > 0) {
        $action = 4;
        $process = "duplicate";
    } else {

        if ($keyvalue == 0) {
            $form_data["createdby"] = $loginid;
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
    $basic_arear = $sqledit['basic_arear'];
    $other_reimbursement = $sqledit['other_reimbursement'];
    $increment_arear = $sqledit['increment_arear'];
    $bonus = $sqledit['bonus'];
    $leave_encasement = $sqledit['leave_encasement'];
    $notice_period = $sqledit['notice_period'];
    $total_additional_payment = $sqledit['total_additional_payment'];
    $remark = $sqledit['remark'];
} else {
    $emp_id = "";
    $month = "";
    $year = date("Y");
    $basic_arear = "";
    $other_reimbursement = "";
    $increment_arear = "";
    $bonus = "";
    $leave_encasement = "";
    $notice_period = "";
    $total_additional_payment = "";
    $remark = "";
}

if (isset($_POST['upload_excel'])) {
    $totalRecords = 0;
    $insertedCount = 0;
    $skippedCount = 0;
    $skippedEpicNumbers = array();

    if (isset($_FILES['file_upload']['tmp_name']) && $_FILES['file_upload']['error'] == UPLOAD_ERR_OK) {
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

                    list(
                        $emp_code,
                        $additional_payment,
                        $basic_arear,
                        $other_reimbursement,
                        $increment_arear,
                        $bonus,
                        $leave_encasement,
                        $notice_period
                    ) = $data;


                    $total_additional_payment = calculateTotalAdditionalPayment(
                        $basic_arear,
                        $other_reimbursement,
                        $increment_arear,
                        $bonus,
                        $leave_encasement,
                        $notice_period
                    );


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

                    $count = $obj->getvalfield(
                        $tblname,
                        "count(*)",
                        "emp_id='$emp_id' AND month='$file_month' AND year='$file_year'"
                    );

                    if ($count > 0) {
                        $skippedCount++;
                        continue;
                    }

                    $totalRecords++;

                    $form_data1 = array(
                        "emp_id" => $emp_id,
                        "month"  => $file_month,
                        "year"   => $file_year,
                        "basic_arear" => $basic_arear,
                        "other_reimbursement" => $other_reimbursement,
                        "increment_arear" => $increment_arear,
                        "bonus" => $bonus,
                        "leave_encasement" => $leave_encasement,
                        "notice_period" => $notice_period,
                        "total_additional_payment" => $total_additional_payment,
                        "createdby"   => $loginid,
                        "ipaddress"   => $ipaddress,
                        "sessionid"   => $sessionid,
                        "createdate" => $createdate
                    );

                    // print_r($where);
                    // die;
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
                                                    <a href="additional_pay_list.php" class="float-end btn btn-primary btn-sm">List</a>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="emp_id" class="form-label">Rembursment<span class="text-danger fw-bold">*</span></label>
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
                                            <label for="basic_arear" class="form-label">Arear <span class="text-danger" id="net_payment"></span></label>
                                            <input type="text" id="basic_arear" name="basic_arear" class="form-control form-control-sm" placeholder="Enter Arear" value="<?= $basic_arear ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="other_reimbursement" class="form-label">Other Reimbursement <span class="text-danger" id="net_payment"></span></label>
                                            <input type="text" id="other_reimbursement" name="other_reimbursement" class="form-control form-control-sm" placeholder="Enter Other Reimbursement" value="<?= $other_reimbursement ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="increment_arear" class="form-label">Increment Arear <span class="text-danger" id="net_payment"></span></label>
                                            <input type="text" id="increment_arear" name="increment_arear" class="form-control form-control-sm" placeholder="Enter Increment Arear" value="<?= $increment_arear ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="bonus" class="form-label">Bonus <span class="text-danger" id="net_payment"></span></label>
                                            <input type="text" id="bonus" name="bonus" class="form-control form-control-sm" placeholder="Enter Bonus" value="<?= $bonus ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="leave_encasement" class="form-label">Leave encasement <span class="text-danger" id="net_payment"></span></label>
                                            <input type="text" id="leave_encasement" name="leave_encasement" class="form-control form-control-sm" placeholder="Enter Leave encasement" value="<?= $leave_encasement ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="notice_period" class="form-label">Notice period <span class="text-danger" id="net_payment"></span></label>
                                            <input type="text" id="notice_period" name="notice_period" class="form-control form-control-sm" placeholder="Enter Notice period" value="<?= $notice_period ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="total_additional_payment" class="form-label">Total Additional Payment <span class="text-danger" id="net_payment"></span></label>
                                            <input type="text" id="total_additional_payment" name="total_additional_payment" class="form-control form-control-sm" placeholder="Enter Total Additional Payment" value="<?= $total_additional_payment ?>" autocomplete="off" onkeypress="numberOnly(event);" readonly />
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="remark" class="form-label">Remark <span class="text-danger" id="net_payment"></span></label>
                                            <textarea id="remark" name="remark" class="form-control form-control-sm" placeholder="Enter Remark" autocomplete="off"><?= $remark ?></textarea>
                                        </div>
                                        <?php $chkadd = $obj->check_addBtn($pagename, $loginid);
                                        if ($chkadd == 1) {  ?>
                                            <div class="col-lg-4 mb-3">
                                                <br>
                                                <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                                <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?>" onClick="return checkinputmaster('emp_id,month')">
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
                                                <h5 class="card-title mb-0">File Upload <a href="additional_payment.xlsx" class="float-end btn btn-primary btn-sm">Download Sample File</a></h5>
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

                                        <?php if (isset($_GET['total'])) { ?>
                                            <div class="col-lg-12 mt-3">
                                                <div class="alert alert-info mb-0">
                                                    <h6 class="mb-2"><i class="ri-information-line"></i> Excel Upload Summary</h6>

                                                    <table class="table table-sm table-bordered mb-0">
                                                        <tr>
                                                            <th width="40%">Total Records in Excel</th>
                                                            <td><strong><?php echo $_GET['total']; ?></strong></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Successfully Inserted</th>
                                                            <td class="text-success">
                                                                <strong><?php echo $_GET['inserted']; ?></strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Skipped Records</th>
                                                            <td class="text-danger">
                                                                <strong><?php echo $_GET['skipped']; ?></strong>
                                                            </td>
                                                        </tr>

                                                        <?php if (!empty($_GET['skipped_epics'])) { ?>
                                                            <tr>
                                                                <th>Skipped Employee Codes</th>
                                                                <td>
                                                                    <?php echo str_replace(",", ", ", $_GET['skipped_epics']); ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </table>
                                                </div>
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

        function calculateTotalAdditionalPayment() {
            let total = 0;

            const fields = [
                'basic_arear',
                'other_reimbursement',
                'increment_arear',
                'bonus',
                'leave_encasement',
                'notice_period'
            ];

            fields.forEach(function(id) {
                let value = parseFloat(document.getElementById(id).value);
                if (isNaN(value)) value = 0;
                total += value;
            });

            document.getElementById('total_additional_payment').value = total.toFixed(2);
        }

        // Calculate while typing
        $(document).on('input', '#basic_arear,#other_reimbursement,#increment_arear,#bonus,#leave_encasement,#notice_period', function() {
            calculateTotalAdditionalPayment();
        });

        // Calculate on page load (Edit mode)
        $(document).ready(function() {
            calculateTotalAdditionalPayment();
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