<?php include("../adminsession.php");
$pagename = "emi_setting.php";
$title = "EMI Setting";
$tblname = "emi_setting";
$tblpkey = "emi_setting_id";
$module = "EMI Setting";
$submodule = "EMI Setting List";
$btn_name = "Save";


$filter_emp = 0;

if (isset($_GET['emp_id'])) {
    $filter_emp = $_GET['emp_id'];
    $emp_data = $obj->select_record("employee_master", array("emp_id" => $filter_emp));
    $basic_salary = $emp_data['basic_salary'];
    $department_id = $emp_data['department_id'];
    $mobile_no = $emp_data['mobile_no'];
    $department_name = $obj->getvalfield("department_master", "department_name", "department_id='$department_id'");
}

$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

if (isset($_POST['submit'])) {

    $emp_id  = $obj->test_input($_POST['emp_id']);
    $amount  = $obj->test_input($_POST['amount']);
    $emi_no  = $obj->test_input($_POST['emi_no']);
    $month  = $obj->test_input($_POST['month']);
    $year  = $obj->test_input($_POST['year']);

    $count = $obj->getvalfield($tblname, "count(*)", "emp_id='$emp_id' and unit_id='$unitid' and $tblpkey!='$keyvalue'");

    $form_data = array(
        "emp_id" => $emp_id,
        "amount" => $amount,
        "emi_no" => $emi_no,
        "month" => $month,
        "year" => $year,
        "createdby" => $loginid,
        "unit_id" => $unitid,
        "sessionid"   => $sessionid,
        "ipaddress" => $ipaddress
    );
    if ($count > 0) {
        $action = 4;
        $process = "duplicate";
    } else {
        if ($keyvalue == 0) {

            $form_data["createdate"] = $createdate;
            $obj->insert_record($tblname, $form_data);

            $emi_setting_id = $obj->getvalfield(
                "emi_setting",
                "MAX(emi_setting_id)",
                "emp_id='$emp_id'"
            );


            $monthly_amount = round($amount / $emi_no, 2);
            $start_month = (int)$month;
            $start_year  = (int)$year;

            for ($i = 0; $i < $emi_no; $i++) {

                $m = $start_month + $i;
                $y = $start_year;

                if ($m > 12) {
                    $m -= 12;
                    $y++;
                }

                $detail_data = array(
                    "emi_setting_id" => $emi_setting_id,
                    "emp_id"         => $emp_id,
                    "amount_detail"  => $monthly_amount,
                    "emi_no_detail"  => $i + 1,
                    "month_detail"   => $m,
                    "year_detail"    => $y,
                    "createdby"      => $loginid,
                    "ipaddress"      => $ipaddress,
                    "createdate"     => $createdate
                );

                $obj->insert_record("emi_setting_details", $detail_data);
            }

            $action = 1;
        } else {
            $form_data["lastupdated"] = $createdate;
            $where = array($tblpkey => $keyvalue);
            $obj->update_record($tblname, $where, $form_data);
            $action = 2;
            $process = "updated";
        }
    }
    // die;
    echo "<script>location='$pagename?emp_id=$emp_id&action=$action'</script>";
}


if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $emp_id =  $sqledit['emp_id'];
    $amount =  $sqledit['amount'];
    $emi_no =  $sqledit['emi_no'];
    $month =  $sqledit['month'];
    $year =  $sqledit['year'];
} else {
    $emp_id = "";
    $amount = "";
    $emi_no = "";
    $month = "";
    $year = "";
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
    .accordion-row td {
        animation: fadeSlide 0.25s ease-in-out;
    }

    @keyframes fadeSlide {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
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
                    <form method="post" action="">
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-2 mb-3">
                                            <label for="emp_id" class="form-label">Employee Name<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id" id="emp_id" onchange="get_url(this.value)">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>"
                                                        <?= ($filter_emp == $key['emp_id']) ? 'selected' : ''; ?>>
                                                        <?= $key['emp_code']; ?> -
                                                        <?= ucfirst($key['first_name']); ?>
                                                        <?= ucfirst($key['last_name']); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <!-- <script>
                                                document.getElementById('emp_id').value = '<?= $emp_id; ?>';
                                            </script> -->
                                        </div>
                                        <div class="col-lg-2 mb-3">
                                            <label for="">Amount<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" class="form-control form-control-sm" name="amount" id="amount"
                                                value="<?= $amount ?>" placeholder="Enter Amount">
                                        </div>
                                        <div class="col-lg-2 mb-3">
                                            <label for="">No. Of EMI<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" class="form-control form-control-sm" name="emi_no" id="emi_no"
                                                value="<?= $emi_no ?>" placeholder="Enter No. Of EMI">
                                        </div>
                                        <div class="col-lg-2 mb-3">
                                            <label for="">Start Month<span class="text-danger fw-bold">*</span></label>
                                            <select name="month" id="month" class="form-select  form-select-sm">
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
                                            <script>
                                                document.getElementById('month').value =
                                                    '<?= $month; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-2 mb-3">
                                            <label for="">Start Year<span class="text-danger fw-bold">*</span></label>
                                            <select name="year" id="year" class="form-select  form-select-sm">
                                                <option value="">Select Year</option>
                                                <option value="2024">2024</option>
                                                <option value="2025">2025</option>
                                                <option value="2026">2026</option>
                                                <option value="2027">2027</option>
                                                <option value="2028">2028</option>
                                            </select>
                                            <script>
                                                document.getElementById('year').value =
                                                    '<?= $year; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-2 mb-3 mt-2">
                                            <br>
                                            <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> " onClick="return checkinputmaster('emp_id,amount,emi_no,month,year')">
                                            <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>
                                        <?php if ($filter_emp > 0) {
                                        ?>
                                            <div class="col-lg-12 mb-2">
                                                <span class="text-primary">
                                                    <strong>Salary:</strong> ₹ <?= $basic_salary; ?>
                                                    &nbsp; | &nbsp;
                                                    <strong>Department:</strong> <?= $department_name; ?>
                                                    &nbsp; | &nbsp;
                                                    <strong>Mobile:</strong> <?= $mobile_no; ?>
                                                </span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                                    <table class="table table-sm table-bordered align-middle" style="width:100%">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>Sr No.</th>
                                                <th>Employee</th>
                                                <th>Amount</th>
                                                <th>No. Of EMI</th>
                                                <th>Start Month</th>
                                                <th>Start Year</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            if ($filter_emp > 0) {
                                                $res = $obj->executequery(
                                                    "SELECT * FROM emi_setting WHERE emp_id='$filter_emp' ORDER BY emi_setting_id DESC"
                                                );
                                            } else {
                                                $res = $obj->executequery(
                                                    "SELECT * FROM emi_setting ORDER BY emi_setting_id DESC"
                                                );
                                            }

                                            $slno = 1;

                                            foreach ($res as $row) {
                                                $emi_setting_id = $row['emi_setting_id'];
                                                $emp_info = $obj->getvalfield(
                                                    "employee_master",
                                                    "CONCAT(emp_code,' - ',first_name,' ',last_name)",
                                                    "emp_id='" . $row['emp_id'] . "'"
                                                );
                                            ?>
                                                <tr>
                                                    <td><?= $slno++; ?></td>
                                                    <td><?= $emp_info; ?></td>
                                                    <td><?= $row['amount']; ?></td>
                                                    <td><?= $row['emi_no']; ?></td>
                                                    <td><?= date("F", mktime(0, 0, 0, $row['month'], 1)); ?></td>
                                                    <td><?= $row['year']; ?></td>
                                                    <td class="text-center">
                                                        <a class="toggle-row">
                                                            <i class="ri-arrow-down-circle-fill fs-3"></i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                <tr class="accordion-row d-none">
                                                    <td colspan="7">
                                                        <div class="p-2">
                                                            <table class="table table-sm table-bordered mb-0">
                                                                <thead class="table-secondary">
                                                                    <tr>
                                                                        <th>EMI No</th>
                                                                        <th>Month</th>
                                                                        <th>Year</th>
                                                                        <th>Amount</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $details = $obj->executequery("SELECT * FROM emi_setting_details WHERE emi_setting_id='$emi_setting_id' ORDER BY emi_no_detail asc");


                                                                    foreach ($details as $d) {

                                                                    ?>
                                                                        <tr>
                                                                            <td><?= $d['emi_no_detail']; ?></td>

                                                                            <td><?= date("F", mktime(0, 0, 0, $d['month_detail'], 1)); ?></td>
                                                                            <td><?= $d['year_detail']; ?></td>
                                                                            <td><?= $d['amount_detail']; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>

                                    </table>
                                </div>
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
            $('#example').DataTable();
            $(".chosen-select").select2({
                width: '100%',
                search_contains: true
            });
        });
    </script>
    <script>
        function get_url(emp_id) {
            location = "emi_setting.php?emp_id=" + emp_id;

        }
        document.querySelectorAll(".toggle-row").forEach(btn => {
            btn.addEventListener("click", function() {

                const icon = this.querySelector("i");
                const currentRow = this.closest("tr");
                const accordionRow = currentRow.nextElementSibling;

                // Toggle row
                accordionRow.classList.toggle("d-none");

                // Rotate arrow
                icon.classList.toggle("bi-chevron-down");
                icon.classList.toggle("bi-chevron-up");
            });
        });
    </script>

</body>

</html>