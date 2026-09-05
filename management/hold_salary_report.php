<?php include("../adminsession.php");
$pagename = "hold_salary_report.php";
$title = "Salary Hold Report";
$tblname = "salary_structure";
$tblpkey = "salary_struc_id";
$module = "Salary Hold Report";
$submodule = "Salary Hold Report List";
$btn_name = "Save";
$crit = " and 1=1";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

if (isset($_GET['month'])) {
    $month = (int)$obj->test_input($_GET['month']);
    if ($month != '') {
        $crit .= " and ss.month='$month'";
    }
} else {
    $month = date('m');
};
if (isset($_GET['year'])) {
    $year = (int)$obj->test_input($_GET['year']);
    if ($year != '') {
        $crit .= " and ss.year='$year'";
    }
} else {
    $year =  date('Y');
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

if (isset($_POST['department_idd'])) {
    $department_id = $_POST['department_idd'];
    $unit_id = $_POST['unit_id'];
    $options = "<option value=''>Please Select</option>";
    $selected = "";
    if ($unit_id != "" || $unit_id > 0) {

        $res = $obj->executequery("Select * from department_master where unit_id='$unit_id' order by department_name asc");

        foreach ($res as $row) {
            $selected = ($department_id == $row['department_id']) ? 'selected' : '';
            $options .= "<option value='" . $row['department_id'] . "' $selected>" . $row['department_name'] . "  </option>";
        }
    }

    echo $options;
    die;
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
                    <?php if (!isset($_GET['submit'])) { ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
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
                                    <form method="get">
                                        <div class="row">

                                            <div class="col-lg-3 mb-3">
                                                <label for="unit_id" class="form-label">Unit Name<span class="text-danger fw-bold"></span></label>
                                                <select class="form-select chosen-select" name="unit_id" id="unit_id" onchange="get_department(this.value);">
                                                    <option value="">All</option>
                                                    <?php $res = $obj->executequery("Select * from unit_master order by unit_name asc");
                                                    foreach ($res as $key) {
                                                        echo "<option value='" . $key['unit_id'] . "'>" . $key['unit_name'] . "</option>";
                                                    } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('unit_id').value = '<?= $unit_id; ?>';
                                                </script>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="department_id" class="form-label">Department Name<span class="text-danger fw-bold"></span></label>
                                                <select class="form-select chosen-select" name="department_id" id="department_id">
                                                    <option value="">All</option>

                                                </select>

                                            </div>



                                            <div class="col-md-3 md-2">
                                                <strong><label for="Month">Month<span class="text-danger fw-bold">* </span></label></strong></br>
                                                <select name="month" class="chosen-select form-control form-control" id="month">
                                                    <option value="">--Select Month--</option>
                                                    <?php for ($iM = 1; $iM <= 12; $iM++) {
                                                    ?>
                                                        <option value="<?php echo str_pad($iM, 2, '0', STR_PAD_LEFT); ?>"><?php echo date("F", strtotime(str_pad($iM, 2, '0', STR_PAD_LEFT) . "/12/10")); ?></option>

                                                    <?php
                                                    } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('month').value = '<?= str_pad($month, 2, "0", STR_PAD_LEFT); ?>';
                                                </script>
                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <label for="year" class="form-label">Year<span class="text-danger fw-bold">* </span></label>
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
                    <?php } ?>
                    <?php if (isset($_GET['submit'])) { ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title mb-0"><?php echo $submodule; ?> </h5>
                                                <h5 class="mb-0 fw-bold text-primary">
                                                    <?= strtoupper(date('F', mktime(0, 0, 0, $month, 1))) . " - " . $year; ?>
                                                </h5>

                                                <a href="<?php echo $pagename; ?>" class="btn btn-primary btn-sm">
                                                    Search Again
                                                </a>

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

                                                    <th>Emp Code</th>
                                                    <th>Emp Name</th>
                                                    <th>Department</th>
                                                    <th>Salary Month</th>
                                                    <th class="text-end">Net Payable Salary</th>
                                                    <th>Status</th>
                                                    <th>Hold Reason</th>
                                                    <th>Hold Date</th>
                                                    <th>Hold By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;
                                                $total_net = 0;
                                                $res = $obj->executequery("SELECT ss.*,um.unit_name, em.first_name,em.department_id, em.emp_code, em.last_name,dm.department_name,u.username,u.fullname,u.mobile, u.email FROM $tblname ss LEFT JOIN employee_master em ON ss.emp_id = em.emp_id LEFT JOIN department_master dm ON ss.department_id = dm.department_id  LEFT JOIN user u ON ss.hold_by = u.userid LEFT JOIN unit_master um ON ss.unit_id = um.unit_id WHERE ss.payment_status = '0' $crit ORDER BY ss.salary_struc_id DESC");



                                                foreach ($res as $row) {
                                                    $total = ($row['total_net_salary'] + $row['additional_payment']) - $row['other_deduction'] - $row['loan_amt'] - $row['advance_amt'] - $row['tds_deduction'];
                                                    $total_net += $total;
                                                ?>
                                                    <tr>
                                                        <td><?= $slno++; ?></td>
                                                        <td><?= $row['emp_code']; ?></td>
                                                        <td><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                                                        <td><?= $row['department_name']; ?></td>
                                                        <td><?= date("F", mktime(0, 0, 0, $row['month'], 10)); ?></td>
                                                        <td class="text-end"><?= $total; ?></td>
                                                        <td>
                                                            <?php if ($row['payment_status'] == 0) { ?>
                                                                <span class="badge bg-danger">Hold</span>
                                                            <?php } else { ?>
                                                                <span class="badge bg-success">Release</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td><?= $row['hold_remark']; ?></td>
                                                        <td><?= $obj->dateformatindia($row['hold_date']); ?></td>
                                                        <td>
                                                            <?php if (!empty($row['username'])) {
                                                                echo "
                                                        <strong>{$row['fullname']}</strong>
                                                        <div style='font-size:12px;color:#666'>
                                                            User: {$row['username']}<br>
                                                            Mobile: {$row['mobile']}<br>
                                                            Email: {$row['email']}
                                                        </div>
                                                   ";
                                                            } ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>

                                            <tfoot class="table-light">
                                                <tr>
                                                    <th colspan="5" class="text-end">Total Net Pay</th>
                                                    <th class="text-end"><?= number_format($total_net, 2); ?></th>
                                                    <th colspan="4"></th>
                                                </tr>
                                            </tfoot>
                                        </table>

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
            get_department('<?= $unit_id ?>', '<?= $department_id ?>');
        });

        function get_department(unit_id, department_id = 0) {
            $.ajax({
                type: "POST",
                url: '',
                data: {
                    department_idd: department_id,
                    unit_id: unit_id,
                },
                success: function(data) {
                    $('#department_id').html(data).trigger("change.select2");
                }
            });

        }
    </script>
</body>

</html>