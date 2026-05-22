<?php include("appsession.php");
$pagename = "loan_advance_list.php";
$title = "Loan Advance List";
$tblname = "loan_advance";
$tblpkey = "loan_advance_id";
$module = "Loan Advance";
$submodule = "Loan Advance List";
$btn_name = "Search";
$imgpath1 = 'uploaded/on_duty/';


$loginid = $_SESSION['emp_id'] ?? 0;

$unitid = $obj->getvalfield("employee_master", "unit_id", "emp_id='$loginid'");

$_SESSION['unit_id'] = $unit_id;
$crit = '';
if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    if ($emp_id != '') {
        $crit .= " and la.emp_id='$emp_id'";
    }
} else {
    $emp_id = "";
};
if (isset($_GET['appr_status'])) {
    $appr_status = $obj->test_input($_GET['appr_status']);
    if ($appr_status != '') {
        $crit .= " and la.appr_status='$appr_status'";
    }
} else {
    $appr_status = "";
};
if (isset($_GET['type'])) {
    $type = $obj->test_input($_GET['type']);
    if ($type != '') {
        $crit .= " and la.type='$type'";
    }
} else {
    $type = "";
};
$to_date = $_GET['to_date'] ?? date('Y-m-d');
$from_date = $_GET['from_date'] ?? date('Y-m-01');
if ($from_date != '' && $to_date != '') {
    $crit .= " AND la.loan_date BETWEEN '$from_date' AND '$to_date'";
} elseif ($from_date != '') {
    $crit .= " AND la.loan_date >= '$from_date'";
} elseif ($to_date != '') {
    $crit .= " AND la.loan_date <= '$to_date'";
};
$start_month = $_GET['start_month'] ?? '';
$start_year  = $_GET['start_year'] ?? '';
$last_month   = $_GET['last_month'] ?? '';
$last_year    = $_GET['last_year'] ?? '';

/* FROM Period */
if ($start_month != '' && $start_year != '') {

    $from_period = $start_year . str_pad($start_month, 2, "0", STR_PAD_LEFT);

    $crit .= " AND 
        (CONCAT(la.start_year, LPAD(la.start_month,2,'0'))) >= '$from_period'
    ";
}

/* TO Period */
if ($last_month != '' && $last_year != '') {

    $to_period = $last_year . str_pad($last_month, 2, "0", STR_PAD_LEFT);

    $crit .= " AND 
        (CONCAT(la.last_year, LPAD(la.last_month,2,'0'))) <= '$to_period'
    ";
}
?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?></title>
    <?php include("topmenu.php"); ?>
</head>
<style>
    .card-panel {
        padding: 15px;
        border-radius: 10px;
    }



    .donut-inner {
        position: absolute;
        bottom: 25%;
        left: 28%;
        text-align: center;
    }

    .card {
        border-radius: 10px;

    }

    .mt-20 {
        margin-top: 18px;
    }

    .card-body {
        padding: 10px;
    }

    .f6 {
        font-size: large;
    }

    .colora {
        font-weight: 600;
        color: #6b6b6b;
        font-size: 14px;
    }

    .card-box {
        border: 1px solid #002fb5;
        border-radius: 10px;
        padding: 5px;
        margin: 10px 0px;
    }

    .card-box::after {
        content: "";
        position: absolute;
        height: 10px;
        width: 10px;
        border-radius: 50%;
        right: 16px;
        top: 16px;
    }

    .card-box.first::after {
        background-color: #00e396;
    }

    .card-box.second::after {
        background-color: #008ffb;
    }

    .card-box.third::after {
        background-color: #feb019;
    }

    .card-box.fourth::after {
        background-color: #ff4560;
    }

    .card-box h5 {
        margin-bottom: 0px;
    }

    .card-box small {
        font-weight: 500;
    }

    .table-borderless tr td {
        border: 0px !important;
        padding-bottom: 0px;
    }
</style>

<?php include("head.php"); ?>

<nav>
    <!-- LEFT SIDENAV-->
    <?php include("leftmenu.php"); ?>
    <script src="package/dist/chart.min.js"></script>
    <script src="js/chartjs-plugin-datalabels@2.0.0.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- END LEFT SIDENAV-->
</nav>

<body>

    <!-- end auth-page-wrapper -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a href="loan_advance.php" class="float-end btn btn-sm btn-primary">Add</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">

                                        <div class="col-lg-3 mb-3">
                                            <label for="">Appr Status</label>
                                            <select name="appr_status" id="appr_status" class="form-select form-select-sm">
                                                <option value="">All</option>
                                                <option value="0">Pending</option>
                                                <option value="1">Approved</option>
                                                <option value="2">Rejected</option>
                                            </select>
                                            <script>
                                                document.getElementById('appr_status').value = '<?= $appr_status ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Application Date</label>
                                            <div class="d-flex">
                                                <input type="date" class="form-control form-control-sm me-2" name="from_date" id="from_date" value="<?= $from_date ?>">
                                                <span class="mt-1">-</span>
                                                <input type="date" class="form-control form-control-sm ms-2" name="to_date" id="to_date" value="<?= $to_date ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Type </label>
                                            <select name="type" id="type" class="form-select form-select-sm">
                                                <option value="">All</option>
                                                <option value="Loan">Loan</option>
                                                <option value="Advance">Advance</option>

                                            </select>
                                            <script>
                                                document.getElementById('type').value = '<?= $type ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label for="">Duration Month Year</label>
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

                                                <span class="m-1">-</span>
                                                <select class="form-select chosen-select" name="start_year" id="start_year">
                                                    <option value="">Select</option>
                                                    <?php
                                                    $startYear = 2025;
                                                    $endYear = 2100;
                                                    for ($year1 = $startYear; $year1 <= $endYear; $year1++) {
                                                        echo "<option value=\"$year1\">$year1</option>";
                                                    } ?>
                                                </select>


                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Duration Month Year To</label>
                                            <div class="d-flex">
                                                <select name="last_month" id="last_month" class="form-select chosen-select">
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

                                                <span class="m-1">-</span>
                                                <select class="form-select chosen-select" name="last_year" id="last_year">
                                                    <option value="">Select</option>
                                                    <?php
                                                    $startYear = 2025;
                                                    $endYear = 2100;
                                                    for ($year1 = $startYear; $year1 <= $endYear; $year1++) {
                                                        echo "<option value=\"$year1\">$year1</option>";
                                                    } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('start_year').value = '<?= $start_year ?>';
                                                    document.getElementById('start_month').value = '<?= $start_month ?>';
                                                    document.getElementById('last_month').value = '<?= $last_month ?>';
                                                    document.getElementById('last_year').value = '<?= $last_year ?>';
                                                </script>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 text-center mt-4">

                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> ">
                                            <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="buttons-datatables" class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SNo</th>
                                                <th>Date</th>
                                                <th>Emp Code</th>
                                                <th>Emp Name</th>
                                                <th>
                                                    Adv./Loan<br>
                                                    <small class="fw-normal">Amt. + Int</small>
                                                </th>
                                                <th>
                                                    No. Of<br>Installment
                                                </th>
                                                <th>Total<br>Amt.</th>
                                                <th>
                                                    Inst.<br>From
                                                </th>
                                                <th> Inst.<br>To</th>

                                                <th>Status</th>
                                                <th>
                                                    Attached File
                                                </th>

                                                <th>Edit</th>
                                                <th>Del.</th>
                                                <th>Aprv.</th>
                                                <th>Print</th>
                                                <!-- <th>
                                                    <input class="form-check-input" type="checkbox">
                                                </th> -->
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <?php
                                            $slno = 1;

                                            // $res = $obj->executequery(
                                            //     "SELECT la.*,em.first_name,em.last_name,em.emp_code  
                                            // FROM $tblname as la LEFT JOIN employee_master em 
                                            // ON la.emp_id=em.emp_id  
                                            // where la.unit_id='$unitid' $crit   
                                            // ORDER BY la.$tblpkey desc"
                                            // );
                                            $whereUnit = ($unitid != '') ? "la.unit_id='$unitid'" : "1=1";

                                            $res = $obj->executequery("
                                                SELECT la.*,em.first_name,em.last_name,em.emp_code  
                                                FROM $tblname as la 
                                                LEFT JOIN employee_master em ON la.emp_id=em.emp_id  
                                                WHERE $whereUnit $crit  
                                                ORDER BY la.$tblpkey desc
                                            ");

                                            foreach ($res as $row) {

                                            ?>
                                                <tr>
                                                    <td><?= $slno++; ?></td>
                                                    <td><?= $obj->dateformatindia($row['loan_date']) ?></td>
                                                    <td><?= $row['emp_code'] ?></td>
                                                    <td>
                                                        <?= $row['first_name'] . " " . $row['last_name']   ?>
                                                    </td>
                                                    <td> <?= $row['loan_adv_amt'] . " + " . $row['interest_amount'] ?> <br>(<?= $row['type'] ?>)</td>
                                                    <td><?= $row['no_of_inst']; ?></td>
                                                    <td><?= $row['total_amount']; ?></td>
                                                    <td>
                                                        <?= date("F", mktime(0, 0, 0, $row['start_month'], 1)) . " - " . $row['start_year'] ?>
                                                    </td>

                                                    <td>
                                                        <?= date("F", mktime(0, 0, 0, $row['last_month'], 1)) . " - " . $row['last_year'] ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($row['appr_status'] == "1") {
                                                            echo '<span class="badge bg-success text-white">Approved</span>';
                                                        } elseif ($row['appr_status'] == "2") {
                                                            echo '<span class="badge bg-danger text-white">Rejected</span>';
                                                        } else {
                                                            echo '<span class="badge bg-warning text-white">Pending</span>';
                                                        }
                                                        ?></td>

                                                    <td>
                                                        <?php if (!empty($row['attach_file'])) { ?>
                                                            <a href="<?= $imgpath1 . $row['attach_file'] ?>" target="_blank">
                                                                <i class="ri-attachment-2 cursor-pointer fs-4"></i>
                                                            </a>
                                                        <?php } else { ?>
                                                            <i class="ri-forbid-2-line cursor-pointer text-danger"></i>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $chkedit = $obj->check_editBtn($pagename, $loginid);
                                                        if ($chkedit == 0 || $row['appr_status'] == 1) { ?>
                                                            <i class="ri-forbid-2-line cursor-pointer text-danger"></i>
                                                        <?php } elseif ($chkedit == 1) { ?>
                                                            <a href="loan_advance.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>" class="edit-item-btn">
                                                                <i class="ri-edit-fill cursor-pointer text-success fs-4"></i>
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php $chkdel = $obj->check_delBtn($pagename, $loginid);
                                                        if ($chkdel == 0 || $row['appr_status'] == 1) { ?>
                                                            <i class="ri-forbid-2-line cursor-pointer text-danger"></i>
                                                        <?php } elseif ($chkdel == 1) { ?>
                                                            <a class="remove-item-btn" type="button" onclick="funDel('<?php echo $row[$tblpkey]; ?>','<?= $row['attach_file'] ?>');">
                                                                <i class="ri-delete-bin-fill cursor-pointer text-danger fs-4"></i>
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="cursor-pointer" onclick="openLoanStstusModal('<?= $row['loan_advance_id'] ?>','<?= $row['loan_date'] ?>','<?= $row['emp_id'] ?>','<?= $row['first_name'] ?>','<?= $row['last_name'] ?>','<?= $row['emp_code'] ?>','<?= $row['loan_adv_amt'] ?>','<?= $row['interest_amount'] ?>','<?= $row['no_of_inst'] ?>','<?= $row['appr_remark'] ?>','<?= $row['total_amount']; ?>','<?= $row['appr_status']; ?>','<?= $row['type'] ?>')">
                                                        <i class="ri-checkbox-fill text-success fs-5"></i>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $chkprint = $obj->check_printBtn($pagename, $loginid);
                                                        if ($chkprint == 1) {  ?>
                                                            <a href="loan_adv_summarypdf.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>" class="edit-item-btn" target="_blank">
                                                                <i class="ri-printer-fill cursor-pointer text-primary fs-4"></i>
                                                            </a>
                                                        <?php }  ?>
                                                    </td>
                                                </tr>
                                            <?php }  ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
    </div><!-- Loan / Advance Approval Modal -->
    <div class="modal fade" id="loanStatusModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Loan / Advance Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- Top Details -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Date :</label>
                            <input type="text" class="form-control form-control-sm" id="modal_loan_date" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Employee Name :</label>
                            <input type="text" class="form-control form-control-sm" id="modal_emp_name" readonly>
                            <input type="hidden" class="form-control form-control-sm" id="modal_emp_id">
                            <input type="hidden" class="form-control form-control-sm" id="modal_loan_advance_id">
                        </div>

                        <div class="col-md-3">
                            <label>Advance/Loan Amount :</label>
                            <input type="number" class="form-control form-control-sm" id="modal_loan_amt" readonly>
                        </div>

                        <div class="col-md-3">
                            <label>Interest Amount :</label>
                            <input type="number" class="form-control form-control-sm" id="modal_interest_amt" readonly>
                        </div>
                        <div class="col-md-3 mt-2">
                            <label>No of Inst :</label>
                            <input type="number" class="form-control form-control-sm" id="modal_no_of_inst" readonly>
                        </div>
                        <div class="col-md-3 mt-2">
                            <label>Type :</label>
                            <input type="texy" class="form-control form-control-sm" id="modal_loan_type" readonly>
                        </div>
                    </div>

                    <!-- Installment Table -->

                    <div id="installment_body">

                    </div>
                    <div class="mb-2">
                        <button type="button" class="btn btn-sm btn-danger" onclick="addNewInstallmentRow()">
                            Add New
                        </button>
                    </div>

                    <!-- Final Remarks -->
                    <div class="col-md-3">
                        <label>Total Amount :</label>
                        <input type="number" class="form-control form-control-sm" id="modal_total_amt" readonly>
                        <input type="hidden" id="modal_actual_total_amt">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Remarks</label>
                        <textarea class="form-control" rows="2" id="modal_apr_remark"></textarea>
                    </div>

                </div>
                <?php
                $chkapr = $obj->check_aprBtn($pagename, $loginid);
                if ($chkapr == 1) {  ?>
                    <div class="modal-footer text-center">
                        <button type="button" id="btnApprove" class="btn btn-success loanActionBtn" onclick="approveLoan('1')">Approve</button>
                        <button type="button" id="btnReject" class="btn btn-danger loanActionBtn" onclick="approveLoan('2')">Reject</button>
                        <button type="button" id="btnPending" class="btn btn-warning loanActionBtn" onclick="approveLoan('0')">Pending</button>
                        <button type="button" id="btnInstalment" class="btn btn-primary loanActionBtn" onclick="approveLoan('1')">Instalment Update</button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/materialize.min.js"></script>
    <!-- Owl carousel -->
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <!-- Magnific Popup core JS file -->
    <script src="lib/Magnific-Popup-master/dist/jquery.magnific-popup.js"></script>
    <!-- Slick JS -->
    <script src="lib/slick/slick/slick.min.js"></script>
    <!-- Custom script -->
    <script src="js/custom.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> #000

    <script>
        $(document).ready(function() {
            $('select').material_select();
        });

        function openLoanStstusModal(loan_advance_id, loan_date, emp_id, first_name, last_name, emp_code, loan_adv_amt, interest_amount, no_of_inst, appr_remark, total_amount, appr_status, loan_type) {
            $("#btnApprove, #btnReject, #btnPending").hide();
            $('#modal_loan_date').val(loan_date);
            $('#modal_loan_type').val(loan_type);
            $('#modal_total_amt').val(total_amount);
            $('#modal_actual_total_amt').val(total_amount);
            $('#modal_emp_id').val(emp_id);
            $('#modal_apr_remark').val(appr_remark);
            $('#modal_emp_name').val(first_name + last_name + " - " + emp_code);
            $('#modal_loan_amt').val(loan_adv_amt);
            $('#modal_interest_amt').val(interest_amount);
            $('#modal_no_of_inst').val(no_of_inst);
            $('#modal_no_of_inst').val(no_of_inst);
            $('#modal_loan_advance_id').val(loan_advance_id);
            if (appr_status == 0) {
                $("#btnApprove").show();
                $("#btnReject").show();
                $("#btnInstalment").hide();
            } else if (appr_status == 1) {
                // Approved already → show reject + pending
                $("#btnReject").show();
                $("#btnPending").show();
                $("#btnInstalment").show();
            } else if (appr_status == 2) {
                // Rejected already → show approve + pending
                $("#btnApprove").show();
                $("#btnPending").show();
                $("#btnInstalment").show();
            }
            $.ajax({
                url: "get_loan_adv_details.php",
                type: "POST",
                data: {
                    loan_advance_id: loan_advance_id,
                    emp_id: emp_id
                },
                success: function(response) {
                    $("#installment_body").html(response);
                    $("#loanStatusModal").modal('show');
                }
            });
        }

        function addNewInstallmentRow() {

            let tbody = $("#modal_installment_tbody");
            let rowCount = tbody.find("tr").length;

            if (rowCount == 0) {
                alert("No previous installment found.");
                return;
            }

            let lastRow = tbody.find("tr:last");

            let lastMonth = parseInt(lastRow.find("select[name='modal_month[]']").val());
            let lastYear = parseInt(lastRow.find("select[name='modal_year[]']").val());

            if (!lastMonth || !lastYear) {
                alert("Last installment month/year not selected.");
                return;
            }

            if (lastMonth == 12) {
                lastMonth = 1;
                lastYear += 1;
            } else {
                lastMonth += 1;
            }

            let monthOptions = '';
            for (let i = 1; i <= 12; i++) {
                let selected = (i == lastMonth) ? 'selected' : '';
                monthOptions += `<option value="${i}" ${selected}>${getMonthName(i)}</option>`;
            }

            let yearOptions = '';
            for (let y = 2025; y <= 2100; y++) {
                let selected = (y == lastYear) ? 'selected' : '';
                yearOptions += `<option value="${y}" ${selected}>${y}</option>`;
            }

            let newRow = `
        <tr>
            <td class="text-center">${rowCount + 1}</td>
            <td class="d-flex">
                <select name="modal_month[]" class="form-select form-select-sm">
                    ${monthOptions}
                </select>
                <span class="m-1">-</span>
                <select name="modal_year[]" class="form-select form-select-sm">
                    ${yearOptions}
                </select>
            </td>
            <td>
                <input type="number"
                    name="installment_amount[]"
                    class="form-control form-control-sm text-center inst_amt"
                    value="0"  onkeyup="updateInstallmentTotal()"
onchange="updateInstallmentTotal()">
            </td>
            <td>
                <textarea name="installment_remark[]"
                    class="form-control form-control-sm"></textarea>
            </td>
        </tr>
    `;

            tbody.append(newRow);
        }

        function getMonthName(month) {
            const months = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            return months[month - 1];
        }

        function updateInstallmentTotal() {

            let total = 0;

            $(".inst_amt").each(function() {
                let value = parseFloat($(this).val());
                if (!isNaN(value)) {
                    total += value;
                }
            });

            $("#modal_total_amt").val(total.toFixed(2));


        }

        function approveLoan(status) {
            if ($(".loanActionBtn").prop("disabled")) {
                return;
            }
            let actualTotal = parseFloat($("#modal_actual_total_amt").val()) || 0;
            let enteredTotal = parseFloat($("#modal_total_amt").val()) || 0;

            if (enteredTotal > actualTotal) {
                alert("Installment total cannot be GREATER than actual total amount.");
                return false;
            }

            if (enteredTotal < actualTotal) {
                alert("Installment total cannot be LESS than actual total amount.");
                return false;
            }

            let loan_advance_id = $('#modal_loan_advance_id').val();

            let months = [];
            let years = [];
            let amounts = [];
            let remarks = [];

            $("select[name='modal_month[]']").each(function() {
                months.push($(this).val());
            });

            $("select[name='modal_year[]']").each(function() {
                years.push($(this).val());
            });

            $("input[name='installment_amount[]']").each(function() {
                amounts.push($(this).val());
            });

            $("input[name='installment_remark[]']").each(function() {
                remarks.push($(this).val());
            });
            $(".loanActionBtn").prop("disabled", true);
            let clickedBtn = event.target;
            let originalText = $(clickedBtn).html();
            $(clickedBtn).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            $.ajax({
                url: "ajax_approve_loan.php",
                type: "POST",
                data: {
                    loan_advance_id: loan_advance_id,
                    status: status,
                    months: months,
                    years: years,
                    amounts: amounts,
                    remarks: remarks,
                    approval_remark: $('#modal_apr_remark').val()
                },
                success: function(response) {
                    console.log(response)
                    if (response.trim() == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Record Saved Successfully',
                            confirmButtonColor: '#3085d6'
                        }).then(() => {
                            $("#loanStatusModal").modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error while approving',
                            confirmButtonColor: '#d33'
                        });
                        $(".loanActionBtn").prop("disabled", false);
                        $(clickedBtn).html(originalText);
                    }
                },
                error: function() {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong',
                        confirmButtonColor: '#d33'
                    });

                    $(".loanActionBtn").prop("disabled", false);
                    $(clickedBtn).html(originalText);
                }
            });
        }
    </script>
</body>

</html>