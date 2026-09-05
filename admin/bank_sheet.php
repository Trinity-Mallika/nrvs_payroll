<?php include("../adminsession.php");
$pagename = "bank_sheet.php";
$title = "Employee Bank Sheet";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Employee Bank Sheet";
$submodule = "Employee Bank Sheet";
$btn_name = "Save";
$crit = '';
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

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
 
if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    if ($emp_id != '') {
        $crit .= " and ss.emp_id='$emp_id'";
    }
} else {
    $emp_id = "";
};  

if (isset($_GET['bank_id'])) {
    $bank_id = $obj->test_input($_GET['bank_id']);
    if ($bank_id == 'other') { 
        $crit .= " AND bnk.bank_id IS NULL";
    } elseif ($bank_id != '') { 
        $crit .= " AND bnk.bank_id = '$bank_id'";
    }
} else {
    $bank_id = "";
}

$details = $obj->executequery("
        SELECT
            ss.*,
            ss.tds_deduction,
            bnk.account_no,
            bnk.acc_holder_name,
            bnk.ifsc_code,
            bm.bank_name,
            em.first_name,
            em.emp_code,
            em.mobile_no,
            em.resign_status,
            em.last_working_date,
            em.designation_id,
            em.department_id,
            dm.department_name,
            dem.designation
        FROM salary_structure AS ss
        INNER JOIN employee_master AS em
            ON ss.emp_id = em.emp_id
        LEFT JOIN emp_bank_details AS bnk
            ON ss.emp_id = bnk.emp_id
        LEFT JOIN bank_master AS bm
            ON bnk.bank_id = bm.bank_id
        LEFT JOIN department_master AS dm
            ON em.department_id = dm.department_id
        LEFT JOIN designation_master AS dem
            ON em.designation_id = dem.designation_id
        WHERE em.unit_id = '$unitid' 
            $crit
        ORDER BY em.emp_code ASC
");
 
 $totalEmployee = count($details);
 
$unitname = $obj->getvalfield("unit_master", "unit_name", "unit_id='$unitid'");
$bankname = $obj->getvalfield("bank_master", "bank_name", "bank_id='$bank_id'");
$monthName = date("F", mktime(0, 0, 0, $month, 1));
$excelTitle = "Bank Sheet - " . $monthName . " - " . $year;
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
                                <div class="row  align-items-center">
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
                                            <label for="emp_id" class="form-label">Employee Name<span
                                                    class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id"
                                                id="emp_id">
                                                <option value="">All</option>
                                                <?php 
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
                                            <label for="bank_id" class="form-label">Bank Name<span
                                                    class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select" name="bank_id"
                                                id="bank_id">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("Select * from bank_master where unit_id='$unitid' order by bank_id asc");
                                                        foreach ($res as $key) { ?>
                                                <option value="<?= $key['bank_id']; ?>">
                                                    <?= $key['bank_name']; ?> </option>
                                                <?php } ?>
                                                <option value="other">Other</option>
                                            </select>
                                            <script>
                                            document.getElementById('bank_id').value =
                                                '<?= $bank_id; ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label for="month" class="form-label">Month<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select  form-select-sm chosen-select" name="month"
                                                id="month">
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
                                            <select class="form-select  form-select-sm chosen-select" name="year"
                                                id="year">
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
                    <?php if (isset($_GET['submit'])) { ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> <a
                                                    href="bank_sheet.php"
                                                    class="float-end btn btn-primary btn-sm ms-4">Search Again</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="auto-scroll-wrapper">
                                    <div class="table-responsive">
                                        <div class="mb-2">
                                            <strong>Total Employees: <?= $totalEmployee; ?></strong>
                                        </div>
                                        <table id="buttons-datatables" class="display table table-sm table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>Sr No.</th>
                                                    <th>Employee Code</th>
                                                    <th>Employee Name</th>
                                                    <th>Net Salary</th>
                                                    <th>Bank Name</th>
                                                    <th>Account Holder Name</th>
                                                    <th>Account No.</th>
                                                    <th>IFSC Code</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $slno = 1;  
                                                    foreach ($details as $row) {
                                                        $total_net_salary = ($row['total_net_salary'] + $row['additional_payment']) - ($row['other_deduction'] + $row['loan_amt'] + $row['advance_amt'] + $row['tds_deduction']);
                                                    ?>
                                                <tr id="tr_<?= $row["emp_id"]; ?>">
                                                    <td><?php echo $slno++; ?></td>
                                                    <td><?= $row["emp_code"]; ?></td>
                                                    <td> <?= ucfirst($row['first_name'] ?? ''); ?> </td>
                                                    <td><?php echo $total_net_salary; ?></td>
                                                    <td><?php echo $row['bank_name']; ?></td>
                                                    <td><?php echo $row['acc_holder_name']; ?></td>
                                                    <td>AC : <?php echo $row['account_no']; ?></td>
                                                    <td><?php echo $row['ifsc_code']; ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
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
            var totalRow = $('#buttons-datatables tfoot tr').clone();
            $('#buttons-datatables thead').append(totalRow);

            const exportTitle = "BANK SHEET - " + new Date().toLocaleDateString('en-GB').replace(/\//g,
                '-');

            $('#buttons-datatables').DataTable({

                dom: "lBfrtip",
                buttons: [
                    "copy",
                    "csv",
                   {
                        extend: "excelHtml5",
                        footer: false,
                        title: "<?= strtoupper($unitname); ?>",
                        messageTop: "<?=strtoupper($bankname)?> <?= strtoupper(date('F', mktime(0, 0, 0, $month, 1))) . ' - ' . $year; ?>",
                        filename: "<?=strtoupper($bankname)?> <?= date('F', mktime(0, 0, 0, $month, 1)) . '-' . $year; ?>",
                        exportOptions: {
                            columns: ':not(.no-export)',
                            format: {
                                header: function(data, columnIdx) {
                                    return $('#buttons-datatables thead tr:first th')
                                        .eq(columnIdx)
                                        .text();
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
  
    
    </script>
</body>

</html>