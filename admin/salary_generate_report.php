<?php include("../adminsession.php");
$pagename = "salary_generate_report.php";
$title = "Salary Generate Report";
$tblname = "salary_structure";
$tblpkey = "salary_struc_id";
$module = "Salary Generate";
$submodule = "Salary Generate List";
$btn_name = "Save";
$crit = " and 1=1";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$chkdel = $obj->check_delBtn($pagename, $loginid);
$chkprint = $obj->check_printBtn($pagename, $loginid);
$chkapr = $obj->check_aprBtn($pagename, $loginid);

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
    $remark = $obj->test_input($_REQUEST['remark']);
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
?>
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?></title>
    <?php include('inc/css.php') ?>
    <style>
        #buttons-datatables tbody tr td:last-child {
            padding-right: 20px;
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
                <?php include('inc/alert.php'); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?><a href="salary_generate.php" class="float-end btn btn-primary btn-sm mx-2">Generate Bulk</a><a href="salary_generate_detail.php" class="float-end btn btn-primary btn-sm mx-2">Generate Single</a><a href="salary_hold_report.php" class="float-end btn btn-primary btn-sm">Hold Report</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">
                                        <!-- Employee -->
                                        <div class="col-lg-3 mb-3">
                                            <label for="emp_id" class="form-label">Employee Name<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id" id="emp_id">
                                                <option value="">All</option>
                                                <?php
                                                //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['emp_code']; ?> - <?= ucfirst($key['first_name'] ?? ''); ?> <?= ucfirst($key['last_name'] ?? ''); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('emp_id').value =
                                                    '<?= $emp_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="department_id" class="form-label">Department Name<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select" name="department_id" id="department_id">
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
                                            <label for="month" class="form-label">Month<span class="text-danger fw-bold">*</span></label>
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
                                            <label for="year" class="form-label">Year<span class="text-danger fw-bold">*</span></label>
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
                                            <input type="submit" name="submit" class="btn btn-primary add-btn" value="Search" onclick="return checkinputmaster('month,year')">
                                            <a href="<?php echo $pagename ?>" class="btn btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($_GET['submit'])) { ?>

                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"><?php echo $submodule; ?> <span class="text-danger"></span></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php
                                    if ($chkdel == 1) {  ?>
                                        <div class="mb-2">
                                            <button class="btn btn-danger btn-sm" onclick="deleteSelected()">
                                                Delete Selected
                                            </button>
                                        </div>
                                    <?php } ?>
                                    <div class="auto-scroll-wrapper">
                                        <div class="table-responsive">
                                            <table id="buttons-datatables" class="table table-bordered table-striped table-hover w-100">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="no-export"></th>
                                                        <th> S No </th>
                                                        <th class="no-export">Actions <input type="checkbox" id="checkAll" class="form-check-input" /></th>
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
                                                        <th>Basic + DA</th>
                                                        <th>HRA</th>
                                                        <th>Medical</th>
                                                        <th>Conveyance</th>
                                                        <th>Special</th>
                                                        <th>Total Gross Salary</th>
                                                        <th>PF Emp Share</th>
                                                        <th>ESIC Emp Share</th>
                                                        <!-- <th>PF Employer Share</th>
                                                        <th>ESIC Employer Share</th> -->
                                                        <th>Net Salary</th>
                                                        <th>Loan Amt</th>
                                                        <th>Advance Amt</th>
                                                        <th>Additional Payment</th>
                                                        <th>Other Deduction</th>
                                                        <th>Total Salary</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    $slno = 1;
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
                                                    $res = $obj->executequery(" SELECT ss.*,um.unit_name,uc.username as created_by_username,uc.usertype as created_by_usertype,uc.fullname as created_by_name,uu.username as update_by_username,uu.usertype as update_by_usertype,uu.fullname as update_by_name, em.first_name,em.department_id, em.emp_code, em.last_name FROM $tblname ss LEFT JOIN employee_master em ON ss.emp_id = em.emp_id LEFT JOIN unit_master um ON ss.unit_id = um.unit_id LEFT JOIN user uc on ss.createdby=uc.userid LEFT JOIN user uu on ss.updatedby=uu.userid WHERE ss.unit_id = '$unitid' AND ss.payment_status IN ('1','2') $crit ORDER BY em.emp_code ASC");

                                                    foreach ($res as $row) {
                                                        $department = $obj->getvalfield("department_master", "department_name", "department_id='$row[department_id]'");
                                                        $total_net += $row['total_salary'];
                                                        // $total_net_salary = ($row['total_net_salary'] + $row['additional_payment']) - $row['other_deduction'] - $row['loan_amt'] - $row['advance_amt'];
                                                        $total_net_salary = $row['total_pay_sal_after_ded'];
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
                                                                <i class="ri-add-circle-fill text-primary"></i>
                                                            </td>
                                                            <td><?= $slno++; ?>

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
                                                                            <li class="list-inline-item " data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                                                <a href="salary_generate_detail.php?emp_id=<?= $row['emp_id'] ?>&month=<?= $row['month'] ?>&year=<?= $row['year'] ?>&<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>" class="edit-item-btn" target="_blank"><i class="ri-pencil-fill align-bottom text-success"></i></a>
                                                                            </li>
                                                                        <?php }
                                                                        if ($chkdel == 1) {  ?>
                                                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                                                <a class="remove-item-btn" type="button" onclick="funDel('<?php echo $row[$tblpkey]; ?>','<?= $row['month'] ?>','<?= $row['year'] ?>','<?= $row['salary_struc_id'] ?>','<?= $row['emp_id'] ?>');">
                                                                                    <i class="ri-delete-bin-fill align-bottom text-danger"></i>
                                                                                </a>
                                                                            </li>
                                                                        <?php  }
                                                                        if ($chkprint == 1) {  ?>
                                                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Print Salary Slip">
                                                                                <a href="salary_slip_pdf.php?salary_struc_id=<?= $row['salary_struc_id'] ?>" target="_blank">
                                                                                    <i class="ri-printer-fill align-bottom text-primary"></i>
                                                                                </a>
                                                                            </li>
                                                                        <?php }
                                                                    }
                                                                    if ($chkapr == 1) { ?>
                                                                        <button
                                                                            class="btn btn-sm <?= $row['payment_status'] == 2 ? 'btn-secondary' : 'btn-primary' ?>"
                                                                            <?= $row['payment_status'] == 2 ? 'disabled' : '' ?>
                                                                            onclick="lock_payment(this, '<?= $row['salary_struc_id'] ?>')">

                                                                            <?php if ($row['payment_status'] == 2) { ?>
                                                                                <i class="ri-lock-fill align-bottom"></i> Locked
                                                                            <?php } else { ?>
                                                                                <i class="ri-lock-unlock-fill align-bottom"></i> Lock
                                                                            <?php } ?>
                                                                        </button>
                                                                    <?php } ?>
                                                                </ul>

                                                            </td>
                                                            <td>
                                                                <?php if ($chkapr == 1) { ?>
                                                                    <button
                                                                        class="btn btn-success btn-sm" id="update_payment_status_<?= $row['salary_struc_id'] ?>"
                                                                        onclick="openSalaryModal('<?= $row['payment_status'] ?>', '<?= $row['salary_struc_id'] ?>')" <?= $row['payment_status'] == 2 ? 'disabled' : '' ?>>
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
                                                            <td><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                                                            <td><?= $department; ?></td>

                                                            <td class="text-end"><?= $row['basic_salary']; ?></td>
                                                            <td class="text-end"><?= $row['increment']; ?></td>
                                                            <td class="text-end"><?= $row['revised_salary']; ?></td>

                                                            <td class="text-end"><?= $row['total_working_days']; ?></td>
                                                            <td class="text-end"><?= $row['basic_da']; ?></td>
                                                            <td class="text-end"><?= $row['hra']; ?></td>
                                                            <td class="text-end"><?= $row['medical']; ?></td>
                                                            <td class="text-end"><?= $row['conveyance']; ?></td>
                                                            <td class="text-end"><?= $row['special_allow']; ?></td>
                                                            <td class="text-end"><?= $row['total_salary']; ?></td>
                                                            <td class="text-end"><?= $row['pf_emp']; ?></td>
                                                            <td class="text-end"><?= $row['esic_emp']; ?></td>

                                                            <td class="text-end"><?= $row['total_net_salary']; ?></td>
                                                            <td class="text-end"><?= $row['loan_amt']; ?></td>
                                                            <td class="text-end"><?= $row['advance_amt']; ?></td>
                                                            <td class="text-end"><?= $row['additional_payment']; ?></td>
                                                            <td class="text-end"><?= $row['other_deduction']; ?></td>
                                                            <td class="text-end"><?= $total_net_salary; ?></td>
                                                        </tr>

                                                    <?php

                                                    }
                                                    ?>
                                                </tbody>

                                                <tfoot class="table-light">
                                                    <tr>
                                                        <th>Total Net Pay</th>
                                                        <th colspan="9"></th>
                                                        <th class="text-end"><?= $total_working_days ?></th>
                                                        <th class="text-end"><?= $total_basic_da ?></th>
                                                        <th class="text-end"><?= $total_hra ?></th>
                                                        <th class="text-end"><?= $total_medical ?></th>
                                                        <th class="text-end"><?= $total_conveyance ?></th>
                                                        <th class="text-end"><?= $total_special ?></th>

                                                        <th class="text-end"><?= number_format($total_net, 2); ?></th>
                                                        <th class="text-end"><?= $total_pf ?></th>
                                                        <th class="text-end"><?= $total_esic ?></th>

                                                        <th></th>
                                                        <th class="text-end"><?= $total_loan_amt ?></th>
                                                        <th class="text-end"><?= $total_advance_amt ?></th>
                                                        <th class="text-end"><?= $total_add_amt ?></th>



                                                        <th class="text-end"><?= $total_other_amt ?></th>

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
                        <textarea class="form-control form-control-sm" id="modal_remark" placeholder="Enter remark"></textarea>
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
            const exportTitle = "Salary Generate Report - " + new Date().toLocaleDateString();

            $('#buttons-datatables').DataTable({
                dom: "lBfrtip",
                buttons: [
                    "copy",
                    "csv",
                    {
                        extend: "excel",
                        pageSize: "LEGAL",
                        footer: true,
                        title: exportTitle,
                        exportOptions: {
                            columns: ':not(.no-export)',
                            format: {
                                header: function(data, columnIdx) {
                                    return $('#buttons-datatables thead tr:eq(0) th').eq(columnIdx).text();
                                }
                            }
                        }
                    },
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
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&pagename=' + pagename + '&month=' + month + '&year=' + year + '&salary_struc_id=' + salary_struc_id + '&emp_id=' + emp_id,
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
                                        .html('<i class="ri-lock-fill align-bottom"></i> Locked')
                                        .off('click');

                                    if (pay_btn) {
                                        $(pay_btn)
                                            .prop('disabled', true)
                                            .removeClass('btn-success')
                                            .addClass('btn-secondary')
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

        function deleteSelected() {

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

            Swal.fire({
                title: 'Are you sure?',
                text: "Selected records will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete'
            }).then((result) => {

                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        url: "ajax_delete_salary.php",
                        type: "POST",
                        data: {
                            records: selected
                        },
                        success: function(response) {
                            // console.log(response);
                            if (response.trim() === "success") {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Selected records deleted successfully.'
                                }).then(() => {
                                    location.reload();
                                });

                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Something went wrong.'
                                });
                            }

                        }
                    });

                }

            });
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
</body>

</html>