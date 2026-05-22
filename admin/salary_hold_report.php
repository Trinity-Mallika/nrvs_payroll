<?php include("../adminsession.php");
$pagename = "salary_hold_report.php";
$title = "Salary Generate Report";
$tblname = "salary_structure";
$tblpkey = "salary_struc_id";
$module = "Salary Generate";
$submodule = "Salary Generate List";
$btn_name = "Save";
$crit = " and 1=1";

$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];
} else {
    $from_date = date("Y-m-01");
    $to_date = date("Y-m-d");
}

$crit .= " and ss.createdate between '$from_date' and '$to_date'";

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
    if ($status == '1') {
        $pay_status = '0';
        $actionText = 'Hold';
    } else {
        $pay_status = '1';
        $actionText = 'Release';
    }
    $obj->update_record("salary_structure", array("salary_struc_id" => $salary_id), array('payment_status' => $pay_status));
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
    echo 1;
    exit();
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

</style>

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
                                            <h5 class="card-title mb-0"> <?= $module; ?><a href="salary_generate.php" class="float-end btn btn-primary btn-sm mx-2">Generate Bulk</a><a href="salary_generate_detail.php" class="float-end btn btn-primary btn-sm mx-2">Generate Single</a><a href="salary_generate_report.php" class="float-end btn btn-primary btn-sm">Released Salary Report</a></h5>
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
                                            <label for="emp_id" class="form-label">From Date<span class="text-danger fw-bold">*</span></label>
                                            <input type="date" name="from_date" id="from_date" class="form-control form-control-sm" value="<?= $from_date ?>">
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="emp_id" class="form-label">To Date<span class="text-danger fw-bold">*</span></label>
                                            <input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="<?= $to_date ?>">
                                        </div>
                                        <div class="col-lg-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary add-btn" value="Search">
                                            <a href="<?php echo $pagename ?>" class="btn btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

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
                                <div class="table-responsive">
                                    <table id="buttons-datatables" class="table table-bordered table-striped table-hover w-100">
                                        <thead class="table-light">
                                            <tr>
                                                <th>S No</th>
                                                <th>Actions</th>
                                                <th>Payment Status</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Department</th>
                                                <th>Generated Date</th>
                                                <th class="text-end">Basic Salary</th>
                                                <th>Gross Salary</th>
                                                <th>Basic PF Rate</th>
                                                <th>Basic ESIC Rate</th>
                                                <th>PF Paid Basic</th>
                                                <th>ESIC Paid Basic</th>
                                                <th>Working days</th>
                                                <th>Basic + DA</th>
                                                <th>HRA</th>
                                                <th>Medical</th>
                                                <th>Conveyance</th>
                                                <th>Special</th>
                                                <th>Total Salary</th>
                                                <th>PF Emp Share</th>
                                                <th>ESIC Emp Share</th>
                                                <th>PF Employer Share</th>
                                                <th>ESIC Employer Share</th>
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
                                            $total_loan_amt = 0;
                                            $total_advance_amt = 0;
                                            $total_add_amt = 0;
                                            $total_other_amt = 0;
                                            $total_pay_salary = 0;
                                            $total_net_salary = 0;

                                            $total_basic_salary = 0;
                                            $total_gross_salary = 0;
                                            $total_pf_paid_basic = 0;
                                            $total_esic_paid_basic = 0;
                                            $total_working_days = 0;
                                            $total_basic_da = 0;
                                            $total_hra = 0;
                                            $total_medical = 0;
                                            $total_conveyance = 0;
                                            $total_special = 0;
                                            $total_salary_sum = 0;

                                            $total_pf_emp = 0;
                                            $total_esic_emp = 0;
                                            $total_pf_employer = 0;
                                            $total_esic_employer = 0;
                                            $res = $obj->executequery(" SELECT ss.*,um.unit_name, em.first_name,em.department_id, em.emp_code, em.last_name FROM $tblname ss LEFT JOIN employee_master em ON ss.emp_id = em.emp_id LEFT JOIN unit_master um ON ss.unit_id = um.unit_id WHERE ss.unit_id = '$unitid' and ss.payment_status = '0' $crit ORDER BY ss.salary_struc_id DESC");

                                            foreach ($res as $row) {
                                                $department = $obj->getvalfield("department_master", "department_name", "department_id='$row[department_id]'");
                                                $total_net += $row['total_salary'];
                                                $total_loan_amt += $row['loan_amt'];
                                                $total_advance_amt += $row['advance_amt'];
                                                $total_add_amt += $row['additional_payment'];
                                                $total_other_amt += $row['other_deduction'];
                                                $total_pay_salary += $row['total_pay_sal_after_ded'];
                                                $total_net_salary += $row['total_net_salary'];

                                                $total_basic_salary += $row['basic_salary'];
                                                $total_gross_salary += $row['revised_salary'];
                                                $total_pf_paid_basic += $row['pf_paid_basic'];
                                                $total_esic_paid_basic += $row['esic_paid_basic'];
                                                $total_working_days += $row['total_working_days'];
                                                $total_basic_da += $row['basic_da'];
                                                $total_hra += $row['hra'];
                                                $total_medical += $row['medical'];
                                                $total_conveyance += $row['conveyance'];
                                                $total_special += $row['special_allow'];
                                                $total_salary_sum += $row['total_salary'];

                                                $total_pf_emp += $row['pf_emp'];
                                                $total_esic_emp += $row['esic_emp'];
                                                $total_pf_employer += $row['pf_employer'];
                                                $total_esic_employer += $row['esic_employer'];
                                            ?>
                                                <tr>
                                                    <td><?= $slno++; ?></td>
                                                    <td>
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <?php
                                                            $chkedit = $obj->check_editBtn($pagename, $loginid);
                                                            if ($chkedit == 1) {
                                                            ?>
                                                                <li class="list-inline-item " data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                                    <a href="salary_generate_detail.php?emp_id=<?= $row['emp_id'] ?>&month=<?= $row['month'] ?>&year=<?= $row['year'] ?>&<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>" class="edit-item-btn"><i class="ri-pencil-fill align-bottom text-success"></i></a>
                                                                </li>
                                                            <?php }
                                                            $chkdel = $obj->check_delBtn($pagename, $loginid);
                                                            if ($chkdel == 1) {  ?>
                                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                                    <a class="remove-item-btn" type="button" onclick="funDel(<?php echo $row[$tblpkey]; ?>);">
                                                                        <i class="ri-delete-bin-fill align-bottom text-danger"></i>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $chkapr = $obj->check_aprBtn($pagename, $loginid);
                                                        if ($chkapr == 1) {
                                                        ?>
                                                            <button
                                                                class="btn btn-danger btn-sm"
                                                                onclick="updatePaymentStatus('<?= $row['payment_status'] ?>', '<?= $row['salary_struc_id'] ?>')">
                                                                <?= $row['payment_status'] == 1 ? 'Release' : 'Hold' ?>
                                                            </button>
                                                        <?php } ?>
                                                    </td>
                                                    <td><?= $row['emp_code']; ?></td>
                                                    <td><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                                                    <td><?= $department; ?></td>
                                                    <td><?= $obj->dateformatindia($row['createdate']); ?></td>
                                                    <td class="text-end"><?= $row['basic_salary']; ?></td>
                                                    <td class="text-end"><?= $row['revised_salary']; ?></td>
                                                    <td class="text-end"><?= $row['pf_rate']; ?></td>
                                                    <td class="text-end"><?= $row['esic_rate']; ?></td>
                                                    <td class="text-end"><?= $row['pf_paid_basic']; ?></td>
                                                    <td class="text-end"><?= $row['esic_paid_basic']; ?></td>
                                                    <td class="text-end"><?= $row['total_working_days']; ?></td>
                                                    <td class="text-end"><?= $row['basic_da']; ?></td>
                                                    <td class="text-end"><?= $row['hra']; ?></td>
                                                    <td class="text-end"><?= $row['medical']; ?></td>
                                                    <td class="text-end"><?= $row['conveyance']; ?></td>
                                                    <td class="text-end"><?= $row['special_allow']; ?></td>
                                                    <td class="text-end"><?= $row['total_salary']; ?></td>
                                                    <td class="text-end"><?= $row['pf_emp']; ?></td>
                                                    <td class="text-end"><?= $row['esic_emp']; ?></td>
                                                    <td class="text-end"><?= $row['pf_employer']; ?></td>
                                                    <td class="text-end"><?= $row['esic_employer']; ?></td>
                                                    <td class="text-end"><?= $row['total_net_salary']; ?></td>
                                                    <td class="text-end"><?= $row['loan_amt']; ?></td>
                                                    <td class="text-end"><?= $row['advance_amt']; ?></td>
                                                    <td class="text-end"><?= $row['additional_payment']; ?></td>
                                                    <td class="text-end"><?= $row['other_deduction']; ?></td>
                                                    <td class="text-end"><?= $row['total_pay_sal_after_ded']; ?></td>

                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>

                                        <tfoot class="table-light">
                                            <tr style="font-weight:bold;">

                                                <th colspan="7" class="text-end">Total</th>

                                                <th class="text-end"><?= number_format($total_basic_salary, 2) ?></th>
                                                <th class="text-end"><?= number_format($total_gross_salary, 2) ?></th>

                                                <th></th>
                                                <th></th>

                                                <th class="text-end"><?= number_format($total_pf_paid_basic, 2) ?></th>
                                                <th class="text-end"><?= number_format($total_esic_paid_basic, 2) ?></th>
                                                <th class="text-end"><?= number_format($total_working_days, 2) ?></th>

                                                <th class="text-end"><?= number_format($total_basic_da, 2) ?></th>
                                                <th class="text-end"><?= number_format($total_hra, 2) ?></th>
                                                <th class="text-end"><?= number_format($total_medical, 2) ?></th>
                                                <th class="text-end"><?= number_format($total_conveyance, 2) ?></th>
                                                <th class="text-end"><?= number_format($total_special, 2) ?></th>

                                                <th class="text-end"><?= number_format($total_salary_sum, 2) ?></th>

                                                <th class="text-end"><?= number_format($total_pf_emp, 2) ?></th>
                                                <th class="text-end"><?= number_format($total_esic_emp, 2) ?></th>
                                                <th class="text-end"><?= number_format($total_pf_employer, 2) ?></th>
                                                <th class="text-end"><?= number_format($total_esic_employer, 2) ?></th>

                                                <th class="text-end"><?= number_format($total_net_salary, 2) ?></th>

                                                <th class="text-end"><?= number_format($total_loan_amt, 2) ?></th>
                                                <th class="text-end"><?= number_format($total_advance_amt, 2) ?></th>
                                                <th class="text-end"><?= number_format($total_add_amt, 2) ?></th>
                                                <th class="text-end"><?= number_format($total_other_amt, 2) ?></th>

                                                <th class="text-end"><?= number_format($total_pay_salary, 2) ?></th>

                                            </tr>
                                        </tfoot>
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
    </div>
    <?php include('inc/delete.php') ?>
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
                    },
                    {
                        extend: "print",
                        pageSize: "LEGAL",
                        footer: true,
                        title: exportTitle,
                    },
                    {
                        extend: "pdf",
                        pageSize: "LEGAL",
                        footer: true,
                        title: exportTitle,
                    }
                ],
            });
            $(".chosen-select").select2({
                width: '100%',
            });
        });

        function funDel(id) {
            tblname = '<?php echo $tblname; ?>';
            tblpkey = '<?php echo $tblpkey; ?>';
            pagename = '<?php echo $pagename; ?>';
            submodule = '<?php echo $submodule; ?>';

            if (confirm("Are you sure! You want to delete this record.")) {
                jQuery.ajax({
                    type: 'POST',
                    url: 'ajax/delete_master.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' + submodule + '&pagename=' + pagename,
                    dataType: 'html',
                    success: function(data) {
                        location = '<?php echo $pagename . "?action=3"; ?>';
                    }
                }); //ajax close
            } //confirm close
        } //fun close

        function updatePaymentStatus(status, salaryId) {
            let actionText = (status == 1) ? 'Hold' : 'Release';

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
                            salary_id: salaryId,
                            actionText: actionText
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Redirect to Released Salary Report',
                                showCancelButton: true,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No',
                                confirmButtonColor: '#0ab39c',
                                cancelButtonColor: '#6c757d'
                            }).then((result) => {
                                if (result.isConfirmed) {

                                    window.location.href = 'salary_generate_report.php';
                                } else {
                                    location.reload();
                                }

                            });
                        }
                    });

                }
            });
        }
    </script>
</body>

</html>