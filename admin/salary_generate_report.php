<?php include("../adminsession.php");
$pagename = "salary_generate_report.php";
$title = "Salary Generate Report";
$tblname = "salary_structure";
$tblpkey = "salary_struc_id";
$module = "Salary Generate Report";
$submodule = "Salary Generate Report";
$btn_name = "Save";
$crit = " and 1=1";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$chkdel = $obj->check_delBtn($pagename, $loginid);
$chkprint = $obj->check_printBtn($pagename, $loginid);
$chkapr = $obj->check_aprBtn($pagename, $loginid);
$unitname = $obj->getvalfield("unit_master", "unit_name", "unit_id='$unitid'");

if (isset($_GET['month'])) {
    $month = $obj->test_input($_GET['month']);
    if ($month != '') {
        $crit .= " and ss.month='$month'";
    }
} else {
    $month = (int)date('m');
};

if (isset($_GET['year'])) {
    $year = $obj->test_input($_GET['year']);
    if ($year != '') {
        $crit .= " and ss.year='$year'";
    }
} else {
    $year = (int)date('Y');
};

if (isset($_GET['unit_id'])) {
    $unit_id = $obj->test_input($_GET['unit_id']);
    if ($unit_id != '') {
        $crit .= " and ss.unit_id='$unit_id'";
    }
} else {
    $unit_id = "";
};
if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    if ($emp_id != '') {
        $crit .= " and ss.emp_id='$emp_id'";
    }
} else {
    $emp_id = "";
};
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and em.department_id='$department_id'";
    }
} else {
    $department_id = "";
};


if (isset($_REQUEST['payment_statuss'])) {
    $status = $obj->test_input($_REQUEST['payment_statuss']);
    $salary_id = $obj->test_input($_REQUEST['salary_id']);
    $remark = $obj->test_input($_REQUEST['remark'] ?? '');
    if ($status == '1') {
        $pay_status = '0';
        $actionText = 'Hold';
    } elseif ($status == '0') {
        $pay_status = '1';
        $actionText = 'Release';
    } else {
        $pay_status = '2';
        $actionText = 'Lock';
    }
    $obj->update_record("salary_structure", array("salary_struc_id" => $salary_id), array('payment_status' => $pay_status, 'hold_remark' => $remark, 'hold_date' => $createdate, 'hold_by' => $loginid));
    $form_data1 = array(
        "primary_id" => $salary_id,
        "flag" => $actionText . ' Salary',
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

    if ($status == '2') {
        echo 2;
    } else {
        echo 1;
    }

    exit();
}


$total_net = 0;
$total_payable_salary = 0;
$total_working_days = 0;
$total_basic_da = 0;
$total_hra = 0;
$total_medical = 0;
$total_conveyance = 0;
$total_special = 0;
$total_pf = 0;
$total_esic = 0;
$total_pf_share = 0;
$total_esic_share = 0;
$total_loan_amt = 0;
$total_advance_amt = 0;
$total_add_amt = 0;
$total_other_amt = 0;
$total_tds_amt = 0;
$total_deduction = 0;
$total_month_gross = 0;

  $res = $obj->executequery(" SELECT ss.*,um.unit_name,uc.username as created_by_username,uc.usertype as created_by_usertype,uc.fullname as created_by_name,uu.username as update_by_username,uu.usertype as update_by_usertype,uu.fullname as update_by_name, em.first_name,em.department_id, em.emp_code, em.last_name FROM $tblname ss LEFT JOIN employee_master em ON ss.emp_id = em.emp_id LEFT JOIN unit_master um ON ss.unit_id = um.unit_id LEFT JOIN user uc on ss.createdby=uc.userid LEFT JOIN user uu on ss.updatedby=uu.userid WHERE ss.unit_id = '$unitid' AND ss.payment_status IN ('1','2') $crit ORDER BY em.emp_code ASC");
                                    $total_records = count($res);
$show_tds_column = false;
?>
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?></title>
    <?php include('inc/css.php') ?>
    <style>
        table#buttons-datatables tfoot,
        table.dataTable tfoot {
            display: none !important;
        }

        #buttons-datatables tfoot tr th,
        #buttons-datatables thead tr th {
            border-right: 1px solid black;
            border-bottom: 1px solid black;
        }

        #buttons-datatables thead tr th:first-child {
            border-left: 1px solid black;
        }

        #buttons-datatables thead tr:first-child th {
            border-top: 1px solid black;
        }
    </style>
</head>

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
                    <?php if (!isset($_GET['submit'])) { ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row  align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?><a href="salary_generate.php"
                                                        class="float-end btn btn-primary btn-sm mx-2">Generate Bulk</a><a
                                                        href="salary_generate_detail.php"
                                                        class="float-end btn btn-primary btn-sm mx-2">Generate Single</a><a
                                                        href="salary_hold_report.php"
                                                        class="float-end btn btn-primary btn-sm">Hold Report</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="get">
                                        <div class="row">
                                            <!-- Employee -->
                                            <div class="col-lg-3 mb-3">
                                                <label for="emp_id" class="form-label">Employee Name<span
                                                        class="text-danger fw-bold"></span></label>
                                                <select class="form-select form-select-sm chosen-select" name="emp_id"
                                                    id="emp_id">
                                                    <option value="">All</option>
                                                    <?php
                                                    //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                    $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                    foreach ($res as $key) { ?>
                                                        <option value="<?= $key['emp_id']; ?>">
                                                            <?= $key['emp_code']; ?> - <?= ucfirst($key['first_name'] ?? ''); ?>
                                                            <?= ucfirst($key['last_name'] ?? ''); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('emp_id').value =
                                                        '<?= $emp_id; ?>';
                                                </script>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="department_id" class="form-label">Department Name<span
                                                        class="text-danger fw-bold"></span></label>
                                                <select class="form-select form-select-sm chosen-select"
                                                    name="department_id" id="department_id">
                                                    <option value="">All</option>
                                                    <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_id asc");
                                                    foreach ($res as $key) { ?>
                                                        <option value="<?= $key['department_id']; ?>">
                                                            <?= $key['department_name']; ?> </option>
                                                    <?php } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('department_id').value =
                                                        '<?= $department_id; ?>';
                                                </script>
                                            </div>

                                            <div class="col-lg-3 mb-3">
                                                <label for="month" class="form-label">Month<span
                                                        class="text-danger fw-bold">*</span></label>
                                                <select class="form-select chosen-select" name="month" id="month">
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

                                            <!-- Year -->
                                            <div class="col-lg-3 mb-3">
                                                <label for="year" class="form-label">Year<span
                                                        class="text-danger fw-bold">*</span></label>
                                                <select class="form-select chosen-select" name="year" id="year">
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

                                            <div class="col-lg-3 mt-4">
                                                <input type="submit" name="submit" class="btn btn-primary add-btn"
                                                    value="Search" onclick="return checkinputmaster('month,year')">
                                                <a href="<?php echo $pagename ?>" class="btn btn-danger add-btn">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php $monthName = date("F", mktime(0, 0, 0, $month, 10));

                    if (isset($_GET['submit'])) { ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">


                                <div class="card-header border-bottom-dashed p-1">
                                    <div class="row align-items-center">

                                        <!-- Left -->
                                        <div class="col-sm-4">
                                            <h5 class="card-title mb-0">
                                                <?= $submodule; ?>
                                            </h5>
                                        </div>

                                        <!-- Center -->
                                        <div class="col-sm-4 text-center">
                                            <h5 class="mb-0 fw-bold text-primary">
                                                <?= $monthName . " - " . $year; ?>
                                            </h5>
                                        </div>

                                        <!-- Right -->
                                        <div class="col-sm-4 text-end">
                                            <a href="salary_generate_report.php" class="btn btn-primary btn-sm">
                                                Search Again
                                            </a>
                                        </div>

                                    </div>
                                </div>

                                <div class="card-body">
                                    <?php 
                                    if ($chkdel == 1) {  ?>
                                        <div class="mb-2 d-flex justify-content-between align-items-center">
                                            <div>
                                                <button class="btn btn-danger btn-sm" onclick="deleteSelected()">
                                                    Delete Selected
                                                </button>
                                                <button class="btn btn-primary btn-sm" onclick="lockAll(2)">
                                                    Lock All
                                                </button>
                                                </button> <button class="btn btn-primary btn-sm" onclick="lockAll(1)">
                                                    Un-Lock All
                                                </button>
                                            </div>

                                            <p class="mb-0 fw-bold">
                                                Total Record : <?= $total_records ?>
                                            </p>
                                        </div>
                                    <?php } ?>
                                    <div class="auto-scroll-wrapper">
                                        <div class="table-responsive">
                                            <table id="buttons-datatables"
                                                class="table table-bordered table-striped table-hover w-100 border-black table-sm">
                                                <thead class="table-light">
                                                    <tr>

                                                        <th> S No </th>
                                                        <th class="no-export">Actions <input type="checkbox" id="checkAll"
                                                                class="form-check-input" /></th>
                                                        <th class="no-export">Payment Status</th>
                                                        <th>Code</th>
                                                        <th>Name</th>
                                                        <th>Department</th>
                                                        <!-- <th>Generated Date</th> -->
                                                        <th class="text-end">Basic Salary</th>
                                                        <th class="text-end">Increment</th>
                                                        <th>Gross Salary</th>
                                                        <!-- <th>Basic PF Rate</th>
                                                        <th>Basic ESIC Rate</th>
                                                        <th>PF Paid Basic</th>
                                                        <th>ESIC Paid Basic</th> -->
                                                        <th>Working days</th>
                                                        <th>Earn Basic + DA</th>
                                                        <th>Earn HRA</th>
                                                        <th>Medical Allowance</th>
                                                        <th>Convey Allowance</th>
                                                        <th>Special Allowance</th>
                                                        <th>Monthly Gross Salary</th>
                                                        <!-- <th>PF Employer Share</th>
                                                        <th>ESIC Employer Share</th> -->
                                                        <th>Additional Payment</th>
                                                        <th>Total Monthly Gross Salary</th>
                                                        <th>PF Ded</th>
                                                        <th>ESIC Emp Share</th>
                                                        <th>Sal. Adv</th>
                                                        <th>Loan </th> 
                                                        <th>Other Deduction</th> 
                                                        <th>TDS Ded</th>
                                                        <th>Total Deduction</th>
                                                        <th>Net Salary</th>
                                                    </tr>


                                                </thead>
                                                <tbody>
                                                    <?php

                                                    $slno = 1;
                                                    


                                                    foreach ($res as $row) {
                                                        $department = $obj->getvalfield("department_master", "department_name", "department_id='$row[department_id]'");
                                                        $total_net += $row['total_salary'];
                                                        $total_net_salary = ($row['total_net_salary'] + $row['additional_payment']) - $row['other_deduction'] - $row['loan_amt'] - $row['advance_amt'] - $row['tds_deduction'];
                                                        $month_gross_tot = $row['total_salary'] + $row['additional_payment'];
                                                        //$total_net_salary = $row['total_pay_sal_after_ded'];
                                                        $total_payable_salary += $total_net_salary;
                                                        $total_working_days += $row['total_working_days'];
                                                        $total_basic_da += $row['basic_da'];
                                                        $total_hra += $row['hra'];
                                                        $total_medical += $row['medical'];
                                                        $total_conveyance += $row['conveyance'];
                                                        $total_special += $row['special_allow'];
                                                      
                                                        $total_pf += $row['pf_emp'];
                                                        $total_esic += $row['esic_emp'];
                                                        $total_pf_share += $row['pf_employer'];
                                                        $total_esic_share += $row['esic_employer'];
                                                        $total_loan_amt += $row['loan_amt'];
                                                        $total_advance_amt += $row['advance_amt'];
                                                        $total_add_amt += $row['additional_payment'];
                                                        $total_other_amt += $row['other_deduction'];
                                                        $total_tds_amt += $row['tds_deduction'];
                                                        $total_month_gross+=$month_gross_tot;
                                                        if ($row['tds_deduction'] > 0) {
                                                            $show_tds_column = true;
                                                        }

                                                        $deduction = $row['pf_emp'] + $row['esic_emp'] + $row['loan_amt'] + $row['advance_amt'] + $row['other_deduction'] + $row['tds_deduction'];
                                                        $total_deduction += $deduction;

                                                    ?>
                                                        <tr id="tr_<?= $row["salary_struc_id"]; ?>" data-details="
                                                                    <strong>Details:</strong><br>
                                                                    Added by (User: <?= $row['created_by_username']; ?>,
                                                                    Name: <?= $row['created_by_name']; ?>,
                                                                    Date: <?= $obj->dateformatindia($row['createdate']); ?>)<br>
                                                                    Updated by (User: <?= $row['update_by_username']; ?>,
                                                                    Name: <?= $row['update_by_name']; ?>,
                                                                    Date: <?= $obj->dateformatindia($row['lastupdated']); ?>)
                                                                ">
                                                            <td class="details-control text-center" style="cursor:pointer;">
                                                                <?= $slno++; ?>
                                                                <i class="ri-add-circle-fill text-primary"></i>
                                                            </td>

                                                            <td>

                                                                <ul class="list-inline hstack gap-2 mb-0">
                                                                    <input type="checkbox"
                                                                        class="delete_single form-check-input"
                                                                        value="<?= $row['salary_struc_id'] ?>"
                                                                        data-month="<?= $row['month'] ?>"
                                                                        data-year="<?= $row['year'] ?>"
                                                                        data-emp_id="<?= $row['emp_id'] ?>" />

                                                                    <?php
                                                                    $chkedit = $obj->check_editBtn($pagename, $loginid);
                                                                    if ($row['payment_status'] != 2) { ?>
                                                                        <?php if ($chkedit == 1) {  ?>
                                                                            <li class="list-inline-item " data-bs-toggle="tooltip"
                                                                                data-bs-trigger="hover" data-bs-placement="top"
                                                                                title="Edit">
                                                                                <a href="salary_generate_detail.php?emp_id=<?= $row['emp_id'] ?>&month=<?= $row['month'] ?>&year=<?= $row['year'] ?>&<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>&search=Search"
                                                                                    class="edit-item-btn" target="_blank"><i
                                                                                        class="ri-pencil-fill align-bottom text-success"></i></a>
                                                                            </li>
                                                                        <?php }
                                                                        if ($chkdel == 1) {  ?>
                                                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                                data-bs-trigger="hover" data-bs-placement="top"
                                                                                title="Delete">
                                                                                <a class="remove-item-btn" type="button"
                                                                                    onclick="funDel('<?php echo $row[$tblpkey]; ?>','<?= $row['month'] ?>','<?= $row['year'] ?>','<?= $row['salary_struc_id'] ?>','<?= $row['emp_id'] ?>');">
                                                                                    <i
                                                                                        class="ri-delete-bin-fill align-bottom text-danger"></i>
                                                                                </a>
                                                                            </li>
                                                                        <?php  }
                                                                    }
                                                                    if ($chkprint == 1) {  ?>
                                                                        <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                            data-bs-trigger="hover" data-bs-placement="top"
                                                                            title="Print Salary Slip">
                                                                            <a href="salary_slip_pdf.php?salary_struc_id=<?= $row['salary_struc_id'] ?>"
                                                                                target="_blank">
                                                                                <i
                                                                                    class="ri-printer-fill align-bottom text-primary"></i>
                                                                            </a>
                                                                        </li>
                                                                    <?php }
                                                                    if ($chkapr == 1) { ?>
                                                                        <button
                                                                            class="btn btn-sm pt-0 pb-0 <?= $row['payment_status'] == 2 ? 'btn-secondary' : 'btn-primary' ?>"
                                                                            <?= $row['payment_status'] == 2 ? 'disabled' : '' ?>
                                                                            onclick="lock_payment(this, '<?= $row['salary_struc_id'] ?>')">

                                                                            <?php if ($row['payment_status'] == 2) { ?>
                                                                                <i class="ri-lock-fill align-bottom"></i>
                                                                            <?php } else { ?>
                                                                                <i class="ri-lock-unlock-fill align-bottom"></i>
                                                                            <?php } ?>
                                                                        </button>
                                                                    <?php } ?>
                                                                </ul>
                                                            </td>
                                                            <td>
                                                                <?php if ($chkapr == 1) { ?>
                                                                    <button class="btn btn-success btn-sm pt-0 pb-0"
                                                                        id="update_payment_status_<?= $row['salary_struc_id'] ?>"
                                                                        onclick="openSalaryModal('<?= $row['payment_status'] ?>', '<?= $row['salary_struc_id'] ?>')"
                                                                        <?= $row['payment_status'] == 2 ? 'disabled' : '' ?>>
                                                                        <?php
                                                                        if ($row['payment_status'] == 1) {
                                                                            echo 'Release';
                                                                        } elseif ($row['payment_status'] == 0) {
                                                                            echo 'Hold';
                                                                        } else {
                                                                            echo 'Locked';
                                                                        }
                                                                        ?>
                                                                    </button>
                                                                <?php } ?>
                                                            </td>
                                                            <td><?= $row['emp_code']; ?></td>
                                                            <td style="text-wrap: nowrap;"><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                                                            <td style="text-wrap: nowrap;"><?= $department; ?></td>

                                                       

                                                            <td class="text-end"><?=$obj->formatAmount($row['basic_salary']); ?></td>
                                                            <td class="text-end"><?=$obj->formatAmount($row['increment']); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($row['revised_salary']); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($row['total_working_days']); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($row['basic_da']); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($row['hra']); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($row['medical']); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($row['conveyance']); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($row['special_allow']); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($row['total_salary']); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($row['additional_payment']); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($month_gross_tot); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($row['pf_emp']); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($row['esic_emp']); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($row['advance_amt']); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($row['loan_amt']); ?></td>
                                                            
                                                            <td class="text-end"><?= $obj->formatAmount($row['other_deduction']); ?></td>
                                                           
                                                                <td class="text-end"><?= $obj->formatAmount($row['tds_deduction']); ?></td>
                                                            
                                                            <td class="text-end"><?= $obj->formatAmount($deduction); ?></td>
                                                            <td class="text-end"><?= $obj->formatAmount($total_net_salary); ?></td>

                                                        </tr>
                                                    <?php } ?>
                                                </tbody>

                                                <tfoot class="table-light">
                                                    <tr>
                                                        <th colspan="9" class="text-end">Total</th>
                                                        <th class="text-end"><?= $total_working_days ?></th>
                                                        <th class="text-end"><?= $total_basic_da ?></th>
                                                        <th class="text-end"><?= $total_hra ?></th>
                                                        <th class="text-end"><?= $total_medical ?></th>
                                                        <th class="text-end"><?= $total_conveyance ?></th>
                                                        <th class="text-end"><?= $total_special ?></th>
                                                        <th class="text-end"><?= number_format($total_net, 2); ?></th>
                                                           <th class="text-end"><?= $total_add_amt ?></th>
                                                        <th class="text-end"><?= number_format($total_month_gross, 2); ?></th>
                                                        <th class="text-end"><?= $total_pf ?></th>
                                                        <th class="text-end"><?= $total_esic ?></th>
                                                        <th class="text-end"><?= $total_advance_amt ?></th>
                                                        <th class="text-end"><?= $total_loan_amt ?></th>
                                                      
                                                        <th class="text-end"><?= $total_other_amt ?></th>
                                                        
                                                            <th class="text-end"><?= $total_tds_amt ?></th>
                                                        
                                                        <th class="text-end"><?= $total_deduction ?></th>
                                                        <th colspan="" class="text-end"><?= number_format($total_payable_salary, 2) ?></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
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
    </div>
    <div class="modal fade" id="salaryModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Salary Hold</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="modal_salary_id">
                    <input type="hidden" id="modal_payment_status">

                    <div class="mb-2">
                        <label>Date <span class="text-danger fw-bold">*</span></label>
                        <input type="date" class="form-control form-control-sm" id="action_date" disabled>
                    </div>

                    <div class="mb-2">
                        <label>Remark <span class="text-danger fw-bold">*</span></label>
                        <textarea class="form-control form-control-sm" id="modal_remark"
                            placeholder="Enter remark"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm" id="hold_btn" onclick="updatePaymentStatus()">Submit</button>
                </div>

            </div>
        </div>
    </div>
    <?php include('inc/delete.php') ?>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <script>
        $(document).ready(function() {
            $('#buttons-datatables').DataTable().destroy();
            var totalRow = $('#buttons-datatables tfoot tr').clone();
            $('#buttons-datatables thead').append(totalRow);

            const exportTitle = "Salary Generate Report - " + new Date().toLocaleDateString('en-GB').replace(/\//g,
                '-');

            $('#buttons-datatables').DataTable({

                dom: "lBfrtip",
                buttons: [
                    "copy",
                    "csv",
                    {
                        extend: "excel",
                        footer: false,
                        title: "<?= strtoupper($unitname); ?>",
                        messageTop: "SALARY SHEET FOR THE MONTH <?= strtoupper(date('F', mktime(0, 0, 0, $month, 1))) . '-' . $year; ?>",

                        exportOptions: {
                            columns: ':not(.no-export)',
                            format: {
                                header: function(data, columnIdx) {
                                    return $('#buttons-datatables thead tr:first th')
                                        .eq(columnIdx)
                                        .text();
                                }
                            }
                        },

                        customizeData: function(data) {

                            data.body.unshift([
                                'Total', // S No

                                '', // Code
                                '', // Name
                                '', // Department
                                '', // Basic Salary
                                '', // Increment
                                '', // Gross Salary
                                '<?= $total_working_days   ?>',
                                '<?= $total_basic_da ?>',
                                '<?= $total_hra ?>',
                                '<?= $total_medical ?>',
                                '<?= $total_conveyance ?>',
                                '<?= $total_special ?>',
                                '<?= number_format($total_net, 2) ?>',
                                '',
                                '<?= $total_pf ?>',
                                '<?= $total_esic ?>',
                                '<?= $total_advance_amt ?>',
                                '<?= $total_loan_amt ?>',
                                '<?= $total_add_amt ?>',
                                '<?= $total_other_amt ?>',
                                <?php if ($show_tds_column) { ?> '<?= $total_tds_amt ?>',
                                <?php } ?> '<?= $total_deduction ?>',
                                '<?= number_format($total_payable_salary, 2) ?>'
                            ]);
                        }
                    },
                    // exportOptions: {
                    //     columns: ':not(.no-export)',
                    //     format: {
                    //         header: function(data, columnIdx) {
                    //             return $('#buttons-datatables thead tr:eq(0) th')
                    //                 .eq(columnIdx)
                    //                 .text();
                    //         }
                    //     }
                    // }

                    {
                        extend: "print",
                        pageSize: "LEGAL",
                        footer: true,
                        title: exportTitle,
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    },
                    {
                        extend: "pdf",
                        pageSize: "LEGAL",
                        footer: true,
                        title: exportTitle,
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    }
                ],
            });
            $(".chosen-select").select2({
                width: '100%',
            });
        });

        function openSalaryModal(status, salaryId) {

            document.getElementById("modal_salary_id").value = salaryId;
            document.getElementById("modal_payment_status").value = status;

            // current date
            let today = new Date().toISOString().split('T')[0];
            document.getElementById("action_date").value = today;

            $('#salaryModal').modal('show')
        }

        function funDel(id, month, year, salary_struc_id, emp_id) {
            tblname = '<?php echo $tblname; ?>';
            tblpkey = '<?php echo $tblpkey; ?>';
            pagename = '<?php echo $pagename; ?>';

            if (confirm("Are you sure! You want to delete this record.")) {
                jQuery.ajax({
                    type: 'POST',
                    url: 'ajax/delete_master_salary.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&pagename=' + pagename +
                        '&month=' + month + '&year=' + year + '&salary_struc_id=' + salary_struc_id + '&emp_id=' +
                        emp_id,
                    dataType: 'html',
                    success: function(data) {
                        $("#tr_" + id).hide();
                        // location.reload();
                    }
                }); //ajax close
            } //confirm close
        } //fun close

        function lock_payment(btn, salaryId, status = '2') {
            let actionText = 'Lock';
            let pay_btn = document.getElementById('update_payment_status_' + salaryId);
            Swal.fire({
                title: 'Are you sure?',
                text: `You want to ${actionText} this payment`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#405189',
                cancelButtonColor: '#d33',
                confirmButtonText: `Yes, ${actionText}`,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "",
                        data: {
                            payment_statuss: status,
                            salary_id: salaryId
                        },
                        success: function(response) {

                            if (response == '2') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Salary Report Locked Successfully',
                                    showConfirmButton: false,
                                    showCancelButton: false,
                                    timer: 2000,
                                    timerProgressBar: true
                                }).then(() => {
                                    $(btn)
                                        .prop('disabled', true)
                                        .removeClass('btn-primary')
                                        .addClass('btn-secondary')
                                        .html(
                                            '<i class="ri-lock-fill align-bottom"></i>')
                                        .off('click');

                                    if (pay_btn) {
                                        $(pay_btn)
                                            .prop('disabled', true)
                                            .removeClass('btn-success')
                                            .addClass('btn-success')
                                            .text('Locked')
                                            .off('click');
                                    }
                                    $(btn).closest('ul').find('li').fadeOut(200);
                                });
                            }

                        }
                    });

                }
            });
        }

        function updatePaymentStatus() {

            let salaryId = $("#modal_salary_id").val();
            let status = $("#modal_payment_status").val();
            let remark = $("#modal_remark").val();

            let actionText = (status == 1) ? 'Hold' : 'Release';
            if (remark == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Remark Required',
                    text: 'Please enter remark before submitting'
                });
                $("#modal_remark").focus();
                return false;
            }
            Swal.fire({
                title: 'Are you sure?',
                text: `You want to ${actionText} this payment`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#405189',
                cancelButtonColor: '#d33',
                confirmButtonText: `Yes, ${actionText}`,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#hold_btn").prop("disabled", true).text("Saving...");
                    $.ajax({
                        type: "POST",
                        url: "",
                        data: {
                            payment_statuss: status,
                            salary_id: salaryId,
                            remark: remark,
                        },
                        success: function(response) {
                            console.log(response);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Redirect to Hold Salary Report',
                                showCancelButton: true,
                                confirmButtonText: 'Yes ',
                                cancelButtonText: 'No ',
                                confirmButtonColor: '#0ab39c',
                                cancelButtonColor: '#6c757d'
                            }).then((result) => {
                                if (result.isConfirmed) {

                                    window.location.href = 'salary_hold_report.php';
                                } else {
                                    location.reload();
                                }

                            });
                        }
                    });

                }
            });
        }

        $("#checkAll").on("change", function() {
            $(".delete_single").prop("checked", $(this).prop("checked"));
        });

        // If any unchecked manually → uncheck header
        $(document).on("change", ".delete_single", function() {
            if (!$(this).prop("checked")) {
                $("#checkAll").prop("checked", false);
            } else if ($(".delete_single:checked").length === $(".delete_single").length) {
                $("#checkAll").prop("checked", true);
            }
        });

        function lockAll(status) {
            let selected = [];
            let text = (status == "2") ? "Lock" : "Un-Lock";

            $(".delete_single:checked").each(function() {
                selected.push({
                    id: $(this).val(),
                    month: $(this).data("month"),
                    year: $(this).data("year"),
                    emp_id: $(this).data("emp_id")
                });
            });

            if (selected.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Record Selected',
                    text: `Please select at least one salary record to ${text}.`
                });
                return;
            }

            Swal.fire({
                title: 'Lock Selected Salaries?',
                text: `Selected salary records will be ${text}ed .`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Yes, ${text} Now`,
                cancelButtonText: 'Cancel'
            }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Locking Salaries...',
                        text: `Please wait while records are being ${text}ed.`,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "ajax_lock_salary.php",
                        type: "POST",
                        data: {
                            records: selected,
                            status: status,
                        },
                        success: function(response) {
                            console.log('response', response);
                            if (response.trim() === "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Locked Successfully',
                                    text: `Selected salary records have been ${text}ed.`
                                }).then(() => {
                                    location.reload();
                                });

                            } else {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lock Failed',
                                    text: `Something went wrong while ${text}ing records.`
                                });

                            }
                        },
                        error: function() {

                            Swal.fire({
                                icon: 'error',
                                title: 'Server Error',
                                text: 'Unable to process request. Please try again.'
                            });

                        }
                    });
                }
            });
        }


        async function deleteSelected() {

            let selected = [];

            $(".delete_single:checked").each(function() {
                selected.push({
                    id: $(this).val(),
                    month: $(this).data("month"),
                    year: $(this).data("year"),
                    emp_id: $(this).data("emp_id")
                });
            });

            if (selected.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one record.'
                });
                return;
            }

            const result = await Swal.fire({
                title: 'Are you sure?',
                text: 'Selected records will be deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete'
            });

            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Deleting...',
                html: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const chunkSize = 200;
            let lockedFound = false;

            for (let i = 0; i < selected.length; i += chunkSize) {

                let chunk = selected.slice(i, i + chunkSize);

                try {

                    let response = await $.ajax({
                        url: "ajax_delete_salary.php",
                        type: "POST",
                        data: {
                            records: chunk
                        }
                    });

                    response = response.trim();

                    if (response === "all_locked") {
                        lockedFound = true;
                        continue;
                    }

                    if (response !== "success") {
                        Swal.close();

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Deletion failed.'
                        });

                        return;
                    }

                } catch (e) {

                    Swal.close();

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Deletion failed.'
                    });

                    return;
                }
            }

            Swal.close();

            if (lockedFound) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Completed',
                    text: 'Some selected salaries were locked and skipped. Remaining records deleted successfully.'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'Selected records deleted successfully.'
                }).then(() => {
                    location.reload();
                });
            }
        }

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
    <!-- <script>
        $(document).ready(function () {

    var footerRow = $('#buttons-datatables tfoot tr').clone();

    $('#buttons-datatables thead').append(footerRow);

});


    </script> -->
    <!-- <script>$(document).ready(function () {

    var totalRow = $('#buttons-datatables tfoot tr').clone();

    $('#buttons-datatables thead').append(totalRow);

    $('#buttons-datatables').DataTable({
        dom: "lBfrtip",
        buttons: [
            {
                extend: "excel",
                footer: true,
                exportOptions: {
                    columns: ':not(.no-export)'
                }
            }
        ]
    });

});</script> -->
</body>

</html>