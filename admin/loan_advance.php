<?php include("../adminsession.php");
$pagename = "loan_advance.php";
$title = "Loan Advance List";
$tblname = "loan_advance";
$tblpkey = "loan_advance_id";
$module = "Loan Advance";
$submodule = "Loan Advance List";
$btn_name = "Save";
$imgpath1 = 'uploaded/on_duty/';
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
if (isset($_POST['submit'])) {
    $loan_date  = $obj->test_input($_POST['loan_date']);
    $emp_id = $obj->test_input($_POST['emp_id']);
    $loan_type_id = $obj->test_input($_POST['loan_type_id'] ?? 0);
    $type = $obj->test_input($_POST['type']);
    $reference_no = $obj->test_input($_POST['reference_no'] ?? '');
    $loan_adv_amt = $obj->test_input($_POST['loan_adv_amt'] ?? 0);
    $interest_amount = $obj->test_input($_POST['interest_amount'] ?? 0);
    $start_month = $obj->test_input($_POST['start_month'] ?? 0);
    $start_year = $obj->test_input($_POST['start_year'] ?? 0);
    $no_of_inst = $obj->test_input($_POST['no_of_inst'] ?? 0);
    $instollment_amount = $obj->test_input($_POST['inst_amount'] ?? 0);
    $total_amount = $obj->test_input($_POST['total_amount'] ?? 0);
    $remark = $obj->test_input($_POST['remark'] ?? '');
    $guarantor_name1 = $obj->test_input($_POST['guarantor_name1'] ?? '');
    $guarantor_name2 = $obj->test_input($_POST['guarantor_name2'] ?? '');
    $purpose_of_loan_adv = $obj->test_input($_POST['purpose_of_loan_adv'] ?? "");
    $inst_month  = $_POST['inst_month'] ?? [];
    $inst_year   = $_POST['inst_year'] ?? [];
    $inst_amount = $_POST['installment_amount'] ?? [];
    $inst_remark = $_POST['installment_remark'] ?? [];

    $attach_file = $_FILES["attach_file"] ?? '';

    $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'jfif', 'xlsx'];
    $imageName = $_FILES["attach_file"]['name'];
    $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

    $last_index = count($inst_amount) - 1;
    $last_month = $inst_month[$last_index];
    $last_year  = $inst_year[$last_index];

    $form_data = array(
        "emp_id" => $emp_id,
        "loan_date" => $loan_date,
        "loan_type_id" => $loan_type_id,
        "type" => $type,
        "reference_no" => $reference_no,
        "loan_adv_amt" => $loan_adv_amt,
        "interest_amount" => $interest_amount,
        "start_month" => $start_month,
        "start_year" => $start_year,
        "last_month" => $last_month,
        "last_year" => $last_year,
        "no_of_inst" => $no_of_inst,
        "inst_amount" => $instollment_amount,
        "total_amount" => $total_amount,
        "remark" => $remark,
        "guarantor_name1" => $guarantor_name1,
        "guarantor_name2" => $guarantor_name2,
        "purpose_of_loan_adv" => $purpose_of_loan_adv,
        "unit_id" => $unitid,
        "createdby" => $loginid,
        "sessionid" => $sessionid,
        "ipaddress" => $ipaddress
    );

    if ($keyvalue == 0) {
        if (isset($_FILES["attach_file"]) && !empty($_FILES["attach_file"]['name'])) {
            $imageFileType = strtolower(pathinfo($_FILES["attach_file"]['name'], PATHINFO_EXTENSION));
            if (in_array($imageFileType, $allowedTypes)) {
                $attach_file = $obj->uploadImage($imgpath1, $_FILES["attach_file"]);
                $form_data['attach_file'] = $attach_file;
            }
        }

        $form_data["createdate"] = $createdate;


        // print_r($form_data);
        // die;
        $lastid = $obj->insert_record_lastid($tblname, $form_data);
        for ($i = 0; $i < count($inst_amount); $i++) {

            $details_data = array(
                "loan_advance_id" => $lastid,
                "month"           => $inst_month[$i],
                "year"            => $inst_year[$i],
                "amount"          => $inst_amount[$i],
                "remark"          => $inst_remark[$i],
                "unit_id" => $unitid,
                "type" => $type,
                "emp_id" => $emp_id,
                "createdby" => $loginid,
                "sessionid" => $sessionid,
                "createdate" => $createdate,
                "ipaddress" => $ipaddress
            );

            $obj->insert_record("loan_advance_details", $details_data);
        }
        $form_data1 = array(
            "primary_id" => $lastid,
            "flag" => $title,
            "activity_type" => 'Inserted',
            "createdby" => $loginid,
            "pagename" => $pagename,
            "created_date" => $createdate,
            "created_time" => date('H:i:s'),
            "unit_id" => $unitid,
            'ipaddress' => $ipaddress,
            "sessionid" => $sessionid
        );
        $logactivity = $obj->insert_record("logactivity_master", $form_data1);

        $action = 1;
        $process = "insert";
    } else {
        if (!empty($imageName) && in_array($imageFileType, $allowedTypes)) {
            $old = $obj->getvalfield($tblname, "attach_file", "$tblpkey='$keyvalue'");
            if (!empty($old)) {
                @unlink($imgpath1 . $old);
            }
            $filename = $obj->uploadImage($imgpath1, $_FILES["attach_file"]);
            $form_data['attach_file'] = $filename;
        }
        $form_data["lastupdated"] = $createdate;
        $form_data["updatedby"] = $loginid;
        $where = array($tblpkey => $keyvalue);
        $obj->update_record($tblname, $where, $form_data);
        $obj->delete_record("loan_advance_details", array("loan_advance_id" => $keyvalue));
        for ($i = 0; $i < count($inst_amount); $i++) {
            $details_data = array(
                "loan_advance_id" => $keyvalue,
                "month"           => $inst_month[$i],
                "year"            => $inst_year[$i],
                "amount"          => $inst_amount[$i],
                "remark"          => $inst_remark[$i],
                "unit_id" => $unitid,
                "emp_id" => $emp_id,
                "type" => $type,
                "createdby" => $loginid,
                "sessionid" => $sessionid,
                "createdate" => $createdate,
                "ipaddress" => $ipaddress
            );
            $obj->insert_record("loan_advance_details", $details_data);
        }
        $form_data1 = array(
            "primary_id" => $keyvalue,
            "flag" => $title,
            "activity_type" => 'Updated',
            "createdby" => $loginid,
            "pagename" => $pagename,
            "created_date" => $createdate,
            "created_time" => date('H:i:s'),
            "unit_id" => $unitid,
            'ipaddress' => $ipaddress,
            "sessionid" => $sessionid
        );
        $logactivity = $obj->insert_record("logactivity_master", $form_data1);
        $action = 2;
        $process = "updated";
    }
    echo "<script>location='$pagename?action=$action'</script>";
}


if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $loan_date =  $sqledit['loan_date'];
    $emp_id = $sqledit['emp_id'];
    $loan_type_id = $sqledit['loan_type_id'] ?? "";
    $type = $sqledit['type'] ?? "";
    $reference_no = $sqledit['reference_no'] ?? "";
    $loan_adv_amt = $sqledit['loan_adv_amt'] ?? 0;
    $attach_file = $sqledit['attach_file'];
    $guarantor_name1 = $sqledit['guarantor_name1'];
    $guarantor_name2 = $sqledit['guarantor_name2'];
    $purpose_of_loan_adv = $sqledit['purpose_of_loan_adv'];
    $total_amount  = $sqledit['total_amount'] ?? 0;
    $interest_amount  = $sqledit['interest_amount'] ?? 0;
    $inst_amount   = $sqledit['inst_amount'] ?? 0;
    $no_of_inst   = $sqledit['no_of_inst'] ?? 0;
    $remark = $sqledit['remark'] ?? "";
    $start_month = $sqledit['start_month'] ?? 0;
    $start_year = $sqledit['start_year'] ?? 0;
    $genrate_installment = "1";
    $img = "";
} else {
    $loan_date = date('Y-m-d');
    $emp_id = "";
    $loan_type_id = "";
    $type = "Advance";
    $reference_no  = "";
    $loan_adv_amt  = "";
    $attach_file = "";
    $genrate_installment = "0";
    $start_month = (int)date('m');
    $start_year = (int)date('Y');
    $interest_amount =  $no_of_inst = $inst_amount = $total_amount = $remark = $guarantor_name1 = $guarantor_name2 = $purpose_of_loan_adv = "";
    $img = "attach_file";
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
                <div class="row">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?> <a href="loan_advance_list.php" class="float-end btn btn-sm btn-primary ms-2">List</a><a href="show_loan_details.php" class="float-end btn btn-sm btn-primary ms-2">Show Loan/Advance Details</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 mb-2">
                                           <div class="d-flex justify-content-between align-items-center">
                                                <label for="emp_id" class="form-label mb-1">
                                                    Employee Name
                                                    <span class="text-danger fw-bold">*</span>
                                                </label>

                                                <button type="button"
                                                    class="btn btn-sm btn-primary"
                                                    onclick="showLoanAdvanceDetails();">
                                                    Show
                                                </button>
                                            </div>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id" id="emp_id">
                                                <option value="">Select Employee</option>
                                                <?php
                                                //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?> <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('emp_id').value = '<?= $emp_id; ?>';
                                            </script>
                                            
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="">Date<span class="text-danger fw-bold">*</span></label>
                                            <input type="date" class="form-control form-control-sm" name="loan_date" id="loan_date" value="<?= $loan_date; ?>">
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="type">Type</label>
                                            <select name="type" id="type" class="form-select form-select-sm" onchange="show_details(this.value);">
                                                <option value="Advance">Advance</option>
                                                <option value="Loan">Loan</option>
                                            </select>
                                            <script>
                                                document.getElementById('type').value = '<?= $type ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <label for="reference_no">Reference No. </label>
                                            <input type="text" class="form-control form-control-sm" name="reference_no" id="reference_no" placeholder="Enter Reference No.">
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="">Loan / Advance Amount <span class="text-danger fw-bold">*</span></label>
                                            <input type="text" class="form-control form-control-sm" name="loan_adv_amt" id="loan_adv_amt" placeholder="Enter Amount" value="<?= $loan_adv_amt; ?>" onkeyup="calculateTotalAmount()" onkeypress="numberOnly(event)">
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="interest_amount">Interest Amt.</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $interest_amount ?>" name="interest_amount" id="interest_amount" placeholder="Enter Interest Amt." onkeyup="calculateTotalAmount();" onkeypress="numberOnly(event)">
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="">Start Month </label>
                                            <div class="d-flex">
                                                <select name="start_month" id="start_month" class="form-select chosen-select">
                                                    <option value="">Select Month</option>
                                                    <option value="1">January</option>
                                                    <option value="2">February</option>
                                                    <option value="3">March</option>
                                                    <option value="4">April</option>
                                                    <option value="5">May</option>
                                                    <option value="6">June</option>
                                                    <option value="7">July</option>
                                                    <option value="8">August</option>
                                                    <option value="9">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>


                                                <select class="form-select chosen-select" name="start_year" id="start_year">
                                                    <option value="">Select</option>
                                                    <?php
                                                    $startYear = 2025;
                                                    $endYear = 2100;
                                                    for ($year1 = $startYear; $year1 <= $endYear; $year1++) {
                                                        echo "<option value=\"$year1\">$year1</option>";
                                                    } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('start_month').value = '<?php echo $start_month ?>'
                                                    document.getElementById('start_year').value = '<?php echo $start_year ?>'
                                                </script>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="no_of_inst">No of Inst.<span class="text-danger fw-bold">*</span></label>
                                            <div class="d-flex">
                                                <input type="text" class="form-control form-control-sm" value="<?= $no_of_inst; ?>" name="no_of_inst" id="no_of_inst">
                                                <a href="javascript:void(0)"
                                                    class="btn btn-sm btn-primary ms-2"
                                                    onclick="generateInstallments('count')" onkeypress="numberOnly(event)" onkeypress="numberOnly(event)">
                                                    Calculate
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="v">Or Inst. Amount<span class="text-danger fw-bold">*</span></label>
                                            <div class="d-flex">
                                                <input type="text" class="form-control form-control-sm" value="<?= $inst_amount; ?>" name="inst_amount" id="inst_amount">
                                                <a href="javascript:void(0)"
                                                    class="btn btn-sm btn-primary ms-2"
                                                    onclick="generateInstallments('amount')">
                                                    Calculate
                                                </a>
                                            </div>
                                        </div>


                                        <div class="col-lg-12 mb-4 mt-4">
                                            <h5>Installment</h5>
                                            <table class="table table-bordered table-striped text-center shadow-lg">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>Sr No.</th>
                                                        <th>Month-Year</th>
                                                        <th>Amount</th>
                                                        <th>Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="installment_body">
                                                    <?php
                                                    $installments = $obj->executequery("select * from loan_advance_details where loan_advance_id='$keyvalue' order by loan_details_id asc");
                                                    if (!empty($installments)) {
                                                        $sr = 1;
                                                        foreach ($installments as $row) {
                                                            $monthName = date("F", mktime(0, 0, 0, $row['month'], 1));
                                                    ?>
                                                            <tr>
                                                                <td><?= $sr++ ?></td>
                                                                <td>
                                                                    <?= $monthName ?> - <?= $row['year'] ?>
                                                                    <input type="hidden" name="inst_month[]" value="<?= $row['month'] ?>">
                                                                    <input type="hidden" name="inst_year[]" value="<?= $row['year'] ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="number"
                                                                        name="installment_amount[]"
                                                                        class="form-control form-control-sm text-center inst_amt"
                                                                        value="<?= $row['amount'] ?>"
                                                                        onkeyup="updateTotalFromInstallments()">
                                                                </td>
                                                                <td>
                                                                    <textarea name="installment_remark[]"
                                                                        class="form-control form-control-sm"><?= $row['remark'] ?></textarea>
                                                                </td>
                                                            </tr>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label for="total_amount">Total Amount<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $total_amount; ?>" name="total_amount" id="total_amount" readonly>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="remark">Remarks</label>
                                            <textarea name="remark" id="remark" class="form-control form-control-sm" rows="1"><?= $remark ?></textarea>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">
                                                Attached File <span class="text-danger fw-bold"> </span>
                                            </label>

                                            <input type="file" class="form-control form-control-sm"
                                                name="attach_file" id="attach_file" value="<?= $attach_file ?>">

                                            <?php if (!empty($attach_file)) {
                                                $ext = strtolower(pathinfo($attach_file, PATHINFO_EXTENSION));
                                            ?>
                                                <div class="mt-2">
                                                    <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) { ?>
                                                        <img src="<?= $imgpath1 . $attach_file ?>"
                                                            style="height:50px;border:1px solid #ccc;">
                                                    <?php } else { ?>
                                                        <a href="<?= $imgpath1 . $attach_file ?>"
                                                            target="_blank" class="btn btn-sm btn-secondary">
                                                            View Uploaded <?= strtoupper($ext) ?>
                                                        </a>
                                                    <?php } ?>
                                                </div>

                                                <input type="hidden" name="old_attachment"
                                                    value="<?= $attach_file ?>">
                                            <?php } ?>
                                        </div>
                                        <div class="col-lg-3 mb-3" id="loan_type_div" style="display: none;">
                                            <label for="">Loan Type</label>
                                            <select name="loan_type_id" id="loan_type_id" class="form-select form-select-sm chosen-select">
                                                <option value="">Select Loan Type</option>
                                                <?php
                                                $res = $obj->executequery("Select * from loan_type order by loan_type_id asc");

                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['loan_type_id']; ?>">
                                                        <?= ucfirst($key['loan_type'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('loan_type_id').value = '<?php echo $loan_type_id ?>'
                                            </script>
                                        </div>

                                        <div class="col-lg-3 mb-3" id="guarantor_name1_div" style="display: none;">
                                            <label for="">Guarantor (1) Name</label>
                                            <input type="text" class="form-control form-control-sm" name="guarantor_name1" id="guarantor_name1" value="<?= $guarantor_name1 ?>">
                                        </div>
                                        <div class="col-lg-3 mb-3" id="guarantor_name2_div" style="display: none;">
                                            <label for="">Guarantor (2) Name</label>
                                            <input type="text" class="form-control form-control-sm" name="guarantor_name2" id="guarantor_name2" value="<?= $guarantor_name2 ?>">
                                        </div>
                                        <div class="col-lg-3 mb-3" id="purpose_of_loan_adv_div" style="display: none;">
                                            <label for="">Purpose Of Loan/Advance</label>
                                            <input type="text" class="form-control form-control-sm" value="<?= $purpose_of_loan_adv; ?>" name="purpose_of_loan_adv" id="purpose_of_loan_adv">
                                        </div>



                                        <?php $chkadd = $obj->check_addBtn($pagename, $loginid);
                                        if ($chkadd == 1) {  ?>
                                            <div class="col-lg-12 text-center mt-4">
                                                <input type="hidden" id="installment_generated" value="<?=$genrate_installment?>">
                                                <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                                <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> " onClick="return validateInstallmentGenerate()">
                                                <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                            </div>
                                        <?php } ?>
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

    <div class="modal fade" id="loanAdvanceModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Employee Loan / Advance Details
                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="loanAdvanceModalBody">
                Loading...
            </div>

        </div>
    </div>
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

        function calculateTotalAmount() {
            let loan = parseFloat(document.getElementById('loan_adv_amt').value) || 0;
            let interest = parseFloat(document.getElementById('interest_amount').value) || 0;

            let total = loan + interest;

            document.getElementById('total_amount').value = total.toFixed(2);
        }

        function generateInstallments(byType) {
            let totalAmount = parseInt(document.getElementById('total_amount').value) || 0;
            let startMonth = parseInt(document.getElementById('start_month').value);
            let startYear = parseInt(document.getElementById('start_year').value);

            if (!startMonth || !startYear) {
                alert("Select Start Month & Year");
                return;
            }

            let noOfInst = parseInt(document.getElementById('no_of_inst').value);
            let instAmount = parseInt(document.getElementById('inst_amount').value);

            let tbody = document.getElementById('installment_body');
            tbody.innerHTML = "";

            let months = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            let totalInst = 0;

            if (byType === 'count') {

                if (!noOfInst || noOfInst <= 0) {
                    alert("Enter No of Installment");
                    return;
                }

                totalInst = noOfInst;

                // Integer division
                instAmount = Math.floor(totalAmount / totalInst);
                document.getElementById('inst_amount').value = instAmount;

            } else if (byType === 'amount') {

                if (!instAmount || instAmount <= 0) {
                    alert("Enter Installment Amount");
                    return;
                }

                totalInst = Math.ceil(totalAmount / instAmount);
                document.getElementById('no_of_inst').value = totalInst;
            }

            let remaining = totalAmount;

            for (let i = 0; i < totalInst; i++) {

                let monthIndex = (startMonth - 1 + i) % 12;
                let year = startYear + Math.floor((startMonth - 1 + i) / 12);

                let amount;

                if (i === totalInst - 1) {
                    amount = remaining; // Last installment adjustment
                } else {
                    amount = instAmount;
                }

                remaining -= amount;
                let row = `
<tr>
    <td>${i + 1}</td>
    <td>${months[monthIndex]} - ${year}
       <input type="hidden" name="inst_month[]" value="${monthIndex + 1}">
        <input type="hidden" name="inst_year[]" value="${year}">
    </td>
    <td>
        <input type="number" 
            name="installment_amount[]" 
            class="form-control form-control-sm text-center inst_amt" 
            value="${amount}"
            onkeyup="updateTotalFromInstallments()">
    </td>
    <td>
        <textarea name="installment_remark[]" 
            class="form-control form-control-sm"></textarea>
    </td>
</tr>
`;
                tbody.innerHTML += row;
                document.getElementById('installment_generated').value = 1;
            }
        }

        function updateTotalFromInstallments() {

            let inputs = document.querySelectorAll('.inst_amt');
            let interest_amount = document.getElementById('interest_amount').value || 0;
            let total = 0;

            inputs.forEach(function(input) {
                let val = parseInt(input.value) || 0;
                total += val;
            });

            let loan_adv_amt = total - interest_amount;
            document.getElementById('total_amount').value = total;

            document.getElementById('loan_adv_amt').value = loan_adv_amt;
        }

        function show_details(type) {
            if (type == 'Loan') {
                $("#loan_type_div").show();
                $("#guarantor_name1_div").show();
                $("#guarantor_name2_div").show();
                $("#purpose_of_loan_adv_div").show();
            } else {
                $("#loan_type_div").hide();
                $("#guarantor_name1_div").hide();
                $("#guarantor_name2_div").hide();
                $("#purpose_of_loan_adv_div").hide();
            }

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

        function validateInstallmentGenerate() {

    let generated = $('#installment_generated').val();

    if (generated != 1) {

        Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: 'Please Calculate Installment First'
        });

        return false;
    }

    return checkinputmaster('emp_id,loan_date,loan_adv_amt,no_of_inst,inst_amount,total_amount');
}

   function showLoanAdvanceDetails() {
        let emp_id = $("#emp_id").val();
        if (emp_id == '') {
            alert("Please Select Employee First");
            $("#emp_id").focus();
            return false;
        }

        $.ajax({
            url: "get_employee_loan_advance_details.php",
            type: "POST",
            data: {
                emp_id: emp_id
            },
            success: function(response) {

                $("#loanAdvanceModalBody").html(response);

                $("#loanAdvanceModal").modal('show');
            }
        });
    }
    </script>
</body>

</html>