<?php include("../adminsession.php");
$pagename = "loan_advance_list.php";
$title = "Loan Advance List";
$tblname = "loan_advance";
$tblpkey = "loan_advance_id";
$module = "Loan Advance";
$submodule = "Loan Advance List";
$btn_name = "Search";
$imgpath1 = 'uploaded/on_duty/';
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
if (isset($_GET['application_month'])) {
    $application_month = $obj->test_input($_GET['application_month']);
   
} else {
    $application_month = "";
};

$to_date = $_GET['to_date'] ?? date('Y-m-d');
$from_date = $_GET['from_date'] ?? date('Y-m-01');
$is_application_month = $_GET['is_application_month'] ?? 0;

if ($is_application_month == 1) {
if ($from_date != '' && $to_date != '') {
    $crit .= " AND la.loan_date BETWEEN '$from_date' AND '$to_date'";
} elseif ($from_date != '') {
    $crit .= " AND la.loan_date >= '$from_date'";
} elseif ($to_date != '') {
    $crit .= " AND la.loan_date <= '$to_date'";
}};
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

.detail-row {
    display: none;
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
                <?php //include('inc/alert.php'); 
                ?>
                <div class="row">
                    <?php if(!isset($_GET['submit'])) { ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a href="loan_advance.php"
                                                    class="float-end btn btn-sm btn-primary ms-2">Add</a> <a
                                                    href="show_loan_details.php"
                                                    class="float-end btn btn-sm btn-primary">Show Loan/Advance
                                                    Details</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-lg-3 mb-2">
                                            <label for="emp_id" class="form-label">Employee Name<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id"
                                                id="emp_id">
                                                <option value="">Select Employee</option>
                                                <?php
                                                    $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                    foreach ($res as $key) { ?>
                                                <option value="<?= $key['emp_id']; ?>">
                                                    <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?>
                                                    <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById('emp_id').value = '<?= $emp_id; ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-2 mb-3">
                                            <label for="">Appr Status</label>
                                            <select name="appr_status" id="appr_status"
                                                class="form-select form-select-sm">
                                                <option value="">All</option>
                                                <option value="0">Pending</option>
                                                <option value="1">Approved</option>
                                                <option value="2">Rejected</option>
                                            </select>
                                            <script>
                                            document.getElementById('appr_status').value = '<?= $appr_status ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-2 mb-3">
                                            <label for="">Type </label>
                                            <select name="type" id="type"
                                                class="form-select form-select-sm form-select-sm">
                                                <option value="">All</option>
                                                <option value="Loan">Loan</option>
                                                <option value="Advance">Advance</option>

                                            </select>
                                            <script>
                                            document.getElementById('type').value = '<?= $type ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-2 mb-3">
                                            <label for="">Application Month <input type="checkbox"
                                                    class="form-check-input" name="is_application_month"
                                                    id="is_application_month" value="1"
                                                    <?= ($is_application_month == 1) ? 'checked' : '' ?>></label>
                                            <select name="application_month" id="application_month"
                                                class="form-select form-select-sm chosen-select">
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
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Application Date</label>
                                            <div class="d-flex">
                                                <input type="date" class="form-control form-control-sm me-2"
                                                    name="from_date" id="from_date" value="<?= $from_date ?>">
                                                <span class="mt-1">-</span>
                                                <input type="date" class="form-control form-control-sm ms-2"
                                                    name="to_date" id="to_date" value="<?= $to_date ?>">
                                            </div>
                                        </div>


                                        <div class="col-lg-3 mb-3">
                                            <label for="">Duration Month Year</label>
                                            <div class="d-flex">
                                                <select name="start_month" id="start_month"
                                                    class="form-select form-select-sm chosen-select">
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
                                                <select class="form-select form-select-sm chosen-select"
                                                    name="start_year" id="start_year">
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
                                                <select name="last_month" id="last_month"
                                                    class="form-select form-select-sm chosen-select">
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
                                                <select class="form-select form-select-sm chosen-select"
                                                    name="last_year" id="last_year">
                                                    <option value="">Select</option>
                                                    <?php
                                                        $startYear = 2025;
                                                        $endYear = 2100;
                                                        for ($year1 = $startYear; $year1 <= $endYear; $year1++) {
                                                            echo "<option value=\"$year1\">$year1</option>";
                                                        } ?>
                                                </select>
                                                <script>
                                                document.getElementById('application_month').value =
                                                    '<?= $application_month ?>';
                                                document.getElementById('start_year').value = '<?= $start_year ?>';
                                                document.getElementById('start_month').value = '<?= $start_month ?>';
                                                document.getElementById('last_month').value = '<?= $last_month ?>';
                                                document.getElementById('last_year').value = '<?= $last_year ?>';
                                                </script>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 text-center mt-4">

                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn"
                                                value="<?php echo $btn_name ?> ">
                                            <a href=" <?php echo $pagename ?>" type="button"
                                                class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if(isset($_GET['submit'])) { ?>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a href="loan_advance_list.php"
                                                    class="float-end btn btn-sm btn-primary ms-2">Search Again</a> <a
                                                    href="loan_advance.php"
                                                    class="float-end btn btn-sm btn-primary ms-2">Add</a> </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="auto-scroll-wrapper">
                                    <div class="table-responsive">
                                        <button type="button" class="btn btn-success btn-sm"
                                            onclick="bulkLoanAction('1')">
                                            <i class="ri-checkbox-circle-fill"></i> Approve Selected
                                        </button>
                                        <table id="buttons-datatables" class="display table table-sm table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>SNo</th>
                                                    <th>Date</th>
                                                    <th>Emp Code</th>
                                                    <th>Emp Name</th>
                                                    <th>Department</th>
                                                    <th>Desigantion</th>
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
                                                    <th>Paid<br>Loan/Adv.</th>

                                                    <th>Status <input type="checkbox" id="checkAll"></th>
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
                                                $res = $obj->executequery("
                                                        SELECT 
                                                            la.*,

                                                            em.first_name,
                                                            em.last_name,
                                                            em.emp_code,
                                                            depm.department_name,
                                                            desm.designation,

                                                            cu.fullname as created_name,
                                                            cu.username as created_username,
                                                            cu.mobile as created_mobile,

                                                            uu.fullname as updated_name,
                                                            uu.username as updated_username,
                                                            uu.mobile as updated_mobile,
                                                            (
                                                                SELECT IFNULL(SUM(ld.amount),0)
                                                                FROM loan_advance_details ld
                                                                WHERE ld.loan_advance_id = la.loan_advance_id
                                                                AND ld.is_paid = '1'
                                                            ) as total_paid_amount,

                                                          
                                                            (
                                                            la.total_amount -
                                                                (
                                                                    SELECT IFNULL(SUM(ld.amount),0)
                                                                    FROM loan_advance_details ld
                                                                    WHERE ld.loan_advance_id = la.loan_advance_id
                                                                    AND ld.is_paid='1'
                                                                )
                                                            ) AS pending_amount

                                                        FROM $tblname la

                                                        LEFT JOIN employee_master em 
                                                            ON la.emp_id = em.emp_id

                                                        LEFT JOIN user cu 
                                                            ON la.createdby = cu.userid
                                                        LEFT JOIN department_master depm 
                                                            ON depm.department_id = em.department_id

                                                        LEFT JOIN designation_master desm 
                                                            ON desm.designation_id = em.designation_id

                                                        LEFT JOIN user uu 
                                                            ON la.updatedby = uu.userid

                                                        WHERE la.unit_id = '$unitid' $crit 

                                                        ORDER BY la.$tblpkey DESC
                                                    ");
 
                                                foreach ($res as $row) {

                                                ?>
                                                <tr data-details="
                                        <div style='background:#dafced; padding:4px;'>
                                        <?php if (!empty($row['created_name'])): ?>
                                        Added by (User: <?= $row['created_name'] ?>,
                                        Username: <?= $row['created_username'] ?>,
                                        Mobile: <?= $row['created_mobile'] ?>,
                                        Date: <?= $row['createdate'] ?>,)<br>
                                        <?php endif; ?>

                                        <?php if (!empty($row['updated_name'])): ?>
                                        Last Edited by (User: <?= $row['updated_name'] ?>,
                                        Username: <?= $row['updated_username'] ?>,
                                        Mobile: <?= $row['updated_mobile'] ?>,
                                        Date: <?= $row['lastupdated'] ?>) 
                                        <?php endif; ?>
                                        </div>
                                    ">

                                                    <td class="details-control text-center" style="cursor:pointer;">
                                                        <?php echo $slno++; ?>
                                                        <i class="ri-add-circle-fill text-primary"></i>

                                                    </td>
                                                    <td><?= $obj->dateformatindia($row['loan_date']) ?></td>
                                                    <td><?= $row['emp_code'] ?></td>
                                                    <td>
                                                        <?= $row['first_name'] . " " . $row['last_name']   ?>
                                                    </td>
                                                    <td><?= $row['department_name'] ?></td>
                                                    <td><?= $row['designation'] ?></td>
                                                    <td> <?= $row['loan_adv_amt'] . " + " . $row['interest_amount'] ?>
                                                        <br>(<?= $row['type'] ?>)
                                                    </td>
                                                    <td><?= $obj->formatAmount($row['no_of_inst']); ?></td>
                                                    <td><?= $obj->formatAmount($row['total_amount']); ?></td>
                                                    <td>
                                                        <?= date("F", mktime(0, 0, 0, $row['start_month'], 1)) . " - " . $row['start_year'] ?>
                                                    </td>

                                                    <td>
                                                        <?= date("F", mktime(0, 0, 0, $row['last_month'], 1)) . " - " . $row['last_year'] ?>
                                                    </td>
                                                    <td><?= $obj->formatAmount($row['total_paid_amount']); ?></td>
                                                    <td>
                                                        <?php
                                                            if ($row['appr_status'] == "1") {
                                                                echo '<span class="badge bg-success text-white">Approved</span>';
                                                            } elseif ($row['appr_status'] == "2") {
                                                                echo '<span class="badge bg-danger text-white">Rejected</span>';
                                                            } else {
                                                                echo '<span class="badge bg-warning text-white">Pending</span>';
                                                            }
                                                            ?>

                                                        <?php if($row['appr_status'] == 0){ ?>
                                                        <input type="checkbox" class="loan_checkbox"
                                                            value="<?= $row['loan_advance_id'] ?>">
                                                        <?php } ?>

                                                    </td>
                                                    <td>
                                                        <?php if (!empty($row['attach_file'])) { ?>
                                                        <a href="<?= $imgpath1 . $row['attach_file'] ?>"
                                                            target="_blank">
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
                                                        <a href="loan_advance.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>"
                                                            class="edit-item-btn">
                                                            <i
                                                                class="ri-edit-fill cursor-pointer text-success fs-4"></i>
                                                        </a>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php $chkdel = $obj->check_delBtn($pagename, $loginid);
                                                            if ($chkdel == 0 || $row['appr_status'] == 1) { ?>
                                                        <i class="ri-forbid-2-line cursor-pointer text-danger"></i>
                                                        <?php } elseif ($chkdel == 1) { ?>
                                                        <a class="remove-item-btn" type="button"
                                                            onclick="funDel('<?php echo $row[$tblpkey]; ?>','<?= $row['attach_file'] ?>');">
                                                            <i
                                                                class="ri-delete-bin-fill cursor-pointer text-danger fs-4"></i>
                                                        </a>
                                                        <?php } ?>
                                                    </td>
                                                    <!-- <td class="cursor-pointer"
                                                        onclick="openLoanStstusModal('< $row['loan_advance_id'] ?>','< $row['loan_date'] ?>','< $row['emp_id'] ?>','< $row['first_name'] ?>','< $row['last_name'] ?>','< $row['emp_code'] ?>','< $row['loan_adv_amt'] ?>','< $row['interest_amount'] ?>','< $row['no_of_inst'] ?>','< $row['appr_remark'] ?>','< $row['total_amount']; ?>','< $row['appr_status']; ?>','< $row['type'] ?>','< $row['pending_amount'] ?>')"> -->

                                                    <td class="cursor-pointer" onclick='openLoanStstusModal(
<?= json_encode($row["loan_advance_id"]) ?>,
<?= json_encode($row["loan_date"]) ?>,
<?= json_encode($row["emp_id"]) ?>,
<?= json_encode($row["first_name"]) ?>,
<?= json_encode($row["last_name"]) ?>,
<?= json_encode($row["emp_code"]) ?>,
<?= json_encode($row["loan_adv_amt"]) ?>,
<?= json_encode($row["interest_amount"]) ?>,
<?= json_encode($row["no_of_inst"]) ?>,
<?= json_encode($row["appr_remark"]) ?>,
<?= json_encode($row["total_amount"]) ?>,
<?= json_encode($row["appr_status"]) ?>,
<?= json_encode($row["type"]) ?>,
<?= json_encode($row["pending_amount"]) ?>
)'>
                                                        <i class="ri-checkbox-fill text-success fs-5"></i>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $chkprint = $obj->check_printBtn($pagename, $loginid);
                                                            if ($chkprint == 1) {  ?>
                                                        <a href="loan_adv_summarypdf.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>"
                                                            class="edit-item-btn" target="_blank">
                                                            <i
                                                                class="ri-printer-fill cursor-pointer text-primary fs-4"></i>
                                                        </a>
                                                        <?php }  ?>
                                                    </td>
                                                </tr>
                                                <?php }  ?>
                                            </tbody>

                                        </table>


                                    </div>
                                </div>
                                <?php 

$application_month = !empty($application_month) 
    ? $application_month 
    : date('m');

                                        $deduction_summary = $obj->executequery("
                                            SELECT 
                                                SUM(
                                                    CASE 
                                                        WHEN lad.type='Advance' 
                                                            AND lad.status = 1
                                                        THEN lad.amount
                                                        ELSE 0
                                                    END
                                                ) as advance_deduction,

                                                SUM(
                                                    CASE 
                                                        WHEN lad.type='Loan' 
                                                            AND lad.status = 1
                                                        THEN lad.amount
                                                        ELSE 0
                                                    END
                                                ) as loan_deduction

                                            FROM loan_advance_details lad

                                            INNER JOIN loan_advance la 
                                                ON lad.loan_advance_id = la.loan_advance_id

                                            WHERE la.unit_id='$unitid'
                                            AND lad.month='$application_month'
                                            AND lad.year = YEAR(CURDATE()) 
                                        ");
                                        $sum = $deduction_summary[0];
                                        $monthName = date("F", mktime(0, 0, 0, $application_month, 1));
                                    ?>
                                <div class="row justify-content-end mt-3">
                                    <div class="col-lg-5">
                                        <div class="card shadow-sm border-0">

                                            <div class="card-header py-2">
                                                <h6 class="mb-0">
                                                    <i class="ri-bar-chart-box-line"></i>
                                                    Loan / Advance Summary (<?= $monthName ?> - <?= date('Y') ?>)
                                                </h6>
                                            </div>

                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="fw-semibold text-success">
                                                        Approved Advance
                                                    </span>
                                                    <span class="fw-bold">
                                                        ₹ <?= number_format($sum['advance_deduction'],2) ?>
                                                    </span>
                                                </div>

                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span class="fw-semibold text-success">
                                                        Approve Loan
                                                    </span>
                                                    <span class="fw-bold">
                                                        ₹ <?= number_format($sum['loan_deduction'],2) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
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
                    <button type="button" id="btnApprove" class="btn btn-success loanActionBtn"
                        onclick="approveLoan('1')">Approve</button>
                    <button type="button" id="btnReject" class="btn btn-danger loanActionBtn"
                        onclick="approveLoan('2')">Reject</button>
                    <button type="button" id="btnPending" class="btn btn-warning loanActionBtn"
                        onclick="approveLoan('0')">Pending</button>
                    <button type="button" id="btnInstalment" class="btn btn-primary loanActionBtn"
                        onclick="approveLoan('1')">Instalment Update</button>
                </div>
                <?php } ?>
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
    $("#checkAll").on("change", function() {
        $(".loan_checkbox").prop("checked", $(this).prop("checked"));
    });

    function openLoanStstusModal(loan_advance_id, loan_date, emp_id, first_name, last_name, emp_code, loan_adv_amt,
        interest_amount, no_of_inst, appr_remark, total_amount, appr_status, loan_type, pending_amount) {
        $("#btnApprove,#btnReject,#btnPending,#btnInstalment").hide();
        if (parseFloat(pending_amount) > 0) {
            if (appr_status == 0) {
                $("#btnApprove").show();
                $("#btnReject").show();
            } else if (appr_status == 1) {
                $("#btnReject").show();
                $("#btnPending").show();
                $("#btnInstalment").show();
            } else if (appr_status == 2) {
                $("#btnApprove").show();
                $("#btnPending").show();
                $("#btnInstalment").show();
            }
        }
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
        // if (appr_status == 0) {
        //     $("#btnApprove").show();
        //     $("#btnReject").show();
        //     $("#btnInstalment").hide();
        // } else if (appr_status == 1) {
        //     // Approved already → show reject + pending
        //     $("#btnReject").show();
        //     $("#btnPending").show();
        //     $("#btnInstalment").show();
        // } else if (appr_status == 2) {
        //     // Rejected already → show approve + pending
        //     $("#btnApprove").show();
        //     $("#btnPending").show();
        //     $("#btnInstalment").show();
        // }

        Swal.fire({
            title: 'Please wait...',
            text: 'Loading loan details...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        $.ajax({
            url: "get_loan_adv_details.php",
            type: "POST",
            data: {
                loan_advance_id: loan_advance_id,
                emp_id: emp_id
            },
            success: function(response) {
                Swal.close();
                $("#installment_body").html(response);
                $("#loanStatusModal").modal('show');
            },
            error: function(xhr, status, error) {
                Swal.close();

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Unable to load loan details.'
                });

                console.log(error);
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

    function bulkLoanAction(status) {

        let selected = [];

        $(".loan_checkbox:checked").each(function() {
            selected.push($(this).val());
        });

        if (selected.length == 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Please select at least one record'
            });
            return;
        }

        let statusText = '';

        if (status == 1) {
            statusText = 'Approve';
        } else if (status == 2) {
            statusText = 'Reject';
        } else {
            statusText = 'Pending';
        }

        Swal.fire({
            title: 'Are you sure?',
            text: `You want to ${statusText} selected records`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "ajax_bulk_approve_loan.php",
                    type: "POST",
                    data: {
                        ids: selected,
                        status: status
                    },
                    beforeSend: function() {
                        $(".btn").prop("disabled", true);
                    },
                    success: function(response) {
                        console.log('response', response);
                        $(".btn").prop("disabled", false);
                        if (response.trim() == "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Status Updated Successfully'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong'
                            });
                        }
                    }
                });
            }
        });
    }

    function funDel(id, imgname) {
        $('#deleteRecordModal').modal('show');
        tblname = 'loan_advance';
        tblpkey = 'loan_advance_id';
        pagename = '<?php echo $pagename; ?>';
        submodule = '<?php echo $submodule; ?>';
        imgpath = '<?php echo $imgpath1; ?>';

        $('#delete-record').click(function() {
            $.ajax({
                type: 'POST',
                url: 'delete_loan_advance.php',
                data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&pagename=' +
                    pagename + '&imgname=' + imgname + '&imgpath=' + imgpath,
                dataType: 'html',
                success: function(data) {
                    // $("#tr_" + id).hide();
                    location.reload();
                }
            });
            $('#deleteRecordModal').modal('hide');
        });
    };

    $(document).ready(function() {

        var table = $('#buttons-datatables').DataTable();

        $('#buttons-datatables tbody').on('click', 'td.details-control', function() {

            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                var details = tr.data('details');
                row.child(details).show();
                tr.addClass('shown');
            }

        });

    });
    </script>
    <script>
    $(document).ready(function() {

        $('#application_month').on('change', function() {

            let month = parseInt($(this).val());

            if (!month) return;

            // Current year
            let year = new Date().getFullYear();

            // First date of month
            let firstDate = new Date(year, month - 1, 1);

            // Last date of month
            let lastDate = new Date(year, month, 0);

            // Format YYYY-MM-DD
            let fromDate =
                firstDate.getFullYear() + '-' +
                String(firstDate.getMonth() + 1).padStart(2, '0') + '-' +
                String(firstDate.getDate()).padStart(2, '0');

            let toDate =
                lastDate.getFullYear() + '-' +
                String(lastDate.getMonth() + 1).padStart(2, '0') + '-' +
                String(lastDate.getDate()).padStart(2, '0');

            // Set values
            $('#from_date').val(fromDate);
            $('#to_date').val(toDate);

        });

    });
    </script>


    <script>
    function toggleApplicationMonth() {
        let checkbox = document.getElementById('is_application_month');
        let fromdate = document.getElementById('from_date');
        let todate = document.getElementById('to_date');
        let applicationMonth = document.getElementById('application_month');

        if (checkbox.checked) {
            fromdate.disabled = false;
            todate.disabled = false;
            applicationMonth.disabled = false;
        } else {
            fromdate.disabled = true;
            todate.disabled = true;
            applicationMonth.disabled = true;
            // Optional: month selection clear karna ho to
            applicationMonth.value = '';
            $('#application_month').trigger('chosen:updated');
        }
    }

    document.getElementById('is_application_month').addEventListener('change', function() {
        toggleApplicationMonth();
    });

    // Page load
    toggleApplicationMonth();
    </script>

</body>

</html>