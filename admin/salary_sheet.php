<?php include("../adminsession.php");
$pagename = "salary_sheet.php";
$title = "Salary Sheet List";
$tblname = "salary_structure";
$tblpkey = "salary_struc_id";
$module = "Salary Sheet";
$submodule = "Salary Sheet List";
$btn_name = "Save";

$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = ' and 1=1';
if (isset($_GET['month'])) {
    $month = $obj->test_input($_GET['month']);
    if ($month != '') {
        $crit .= " and month='$month'";
    }
} else {
    $month = "";
};
if (isset($_GET['year'])) {
    $year = $obj->test_input($_GET['year']);
    if ($year != '') {
        $crit .= " and year='$year'";
    }
} else {
    $year = "";
};
if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    if ($emp_id != '') {
        $crit .= " and emp_id='$emp_id'";
    }
} else {
    $emp_id = "";
};

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
                <?php include('inc/alert.php'); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a href="emp_overtime.php" class="float-end btn btn-primary btn-sm">Add New</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">

                                        <div class="col-lg-3 mb-3">
                                            <label for="emp_id" class="form-label">Employee Name<span class="text-danger fw-bold"> </span></label>
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


                                        <div class="col-lg-3 mb-3">
                                            <label for="month" class="form-label">Month<span class="text-danger fw-bold"> </span></label>
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
                                        <div class="col-lg-3 mb-3">
                                            <label for="year" class="form-label">Year<span class="text-danger fw-bold"> </span></label>
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

                                        <div class="col-lg-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="Search">
                                            <a href="<?php echo $pagename ?>" class="btn btn-sm btn-danger add-btn">Reset</a>
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
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="buttons-datatables" class="display table table-sm table-bordered" style="width:100%">
                                        <thead>
                                            <tr class="table-primary">
                                                <th>Sr No.</th>
                                                <th>Employee Code</th>
                                                <th>Employee Name</th>
                                                <th>Department</th>
                                                <th>Overall Salary</th>
                                                <th>Present Salary</th>
                                                <th>Increment</th>
                                                <th>Total Working Days</th>
                                                <th>Deducted Working Days</th>
                                                <th>Total Salary</th>
                                                <th>Basic</th>
                                                <th>PF</th>
                                                <th>ESI</th>
                                                <th>Bank</th>
                                                <th>Balance</th>
                                                <th>Loan Advance</th>
                                                <th>TDS</th>
                                                <th>Accommodation</th>
                                                <!-- <th>Shoes Ded</th> -->
                                                <th>Other Ded</th>
                                                <th>Total Deduction</th>
                                                <th>MOB Add</th>
                                                <th>ADU</th>
                                                <th>Paid</th>
                                                <th>Signature</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $slno = 1;
                                            $res = $obj->executequery("SELECT * FROM $tblname where unit_id='$unitid' $crit ORDER BY $tblpkey desc ");
                                            foreach ($res as $row) {
                                                $emp_code = $obj->getvalfield("employee_master", "emp_code", "emp_id='$row[emp_id]'");
                                                $first_name = $obj->getvalfield("employee_master", "first_name", "emp_id='$row[emp_id]'");
                                                $last_name = $obj->getvalfield("employee_master", "last_name", "emp_id='$row[emp_id]'");
                                                $department_name = $obj->getvalfield("department_master", "department_name", "department_id='$row[department_id]'");

                                                $lpg_ded = $obj->getvalfield("emp_deduction", "lpg_ded", "emp_id='$row[emp_id]' and month='$row[month]' and year='$row[year]'") ?? 0;
                                                $shoes_ded = $obj->getvalfield("emp_deduction", "shoes_ded", "emp_id='$row[emp_id]' and month='$row[month]' and year='$row[year]'") ?? 0;
                                                $other_ded = $obj->getvalfield("emp_deduction", "other", "emp_id='$row[emp_id]' and month='$row[month]' and year='$row[year]'") ?? 0;

                                                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $row['month'], $row['year']) ?? 31;

                                                if ($row["is_loan_ded"] == '0') {
                                                    $loan = '0';
                                                } else {
                                                    $loan =  $obj->getvalfield("emi_setting_details", "amount_detail", "emp_id='$row[emp_id]' and month_detail='$row[month]' and year_detail='$row[year]'") ?? 0;
                                                }

                                                $result = $obj->generateSalaryStructure(
                                                    total_salary: $row["basic_salary"],
                                                    total_days_in_month: $daysInMonth,
                                                    working_days: $row["total_working_days"],
                                                    loan_amount: $loan,
                                                    lpg_ded: $lpg_ded,
                                                    shoes_ded: $shoes_ded,
                                                    other_ded: $other_ded,
                                                    pf_amt: $row["pf_emp"],
                                                    esic_amt: $row["esic_emp"]
                                                );

                                                $accomadation = (float)$lpg_ded + (float)$shoes_ded;


                                                // echo "<pre>";
                                                // print_r($result);

                                            ?>
                                                <tr>
                                                    <td><?php echo $slno++; ?></td>
                                                    <td><?= $emp_code; ?></td>
                                                    <td> <?= ucfirst($first_name ?? ''); ?> <?= ucfirst($last_name ?? ''); ?></td>
                                                    <td><?php echo $department_name; ?></td>
                                                    <td><?php echo $row["basic_salary"]; ?></td>
                                                    <td><?php echo $result["basic_salary"]; ?></td>
                                                    <td><?php echo $result["increment"]; ?></td>
                                                    <td><?php echo $row["total_working_days"]; ?></td>
                                                    <td><?php echo $result["deduction_days"]; ?></td>
                                                    <td><?php echo $result["net_salary"]; ?></td>
                                                    <td><?php echo $result["net_salary"]; ?></td>
                                                    <td><?php echo $row["pf_emp"]; ?></td>
                                                    <td><?php echo $row["esic_emp"]; ?></td>
                                                    <td><?php echo $result["bank_salary"]; ?> </td>
                                                    <td><?php echo $result["balance"]; ?> </td>
                                                    <td><?php echo $loan; ?></td>
                                                    <td> </td>
                                                    <td><?php echo $accomadation; ?></td>
                                                    <td><?php echo $other_ded; ?></td>
                                                    <td><?= $result['total_deduction'] ?> </td>
                                                    <td> </td>
                                                    <td><?php echo $result["adu"]; ?> </td>
                                                    <td> </td>
                                                    <td> </td>
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

        function funDel(id) {
            $('#deleteRecordModal').modal('show');
            tblname = '<?php echo $tblname; ?>';
            tblpkey = '<?php echo $tblpkey; ?>';

            pagename = '<?php echo $pagename; ?>';
            submodule = '<?php echo $submodule; ?>';

            $('#delete-record').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/delete_master_emp.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' + submodule + '&pagename=' + pagename,
                    dataType: 'html',
                    success: function(data) {
                        // alert(data);
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