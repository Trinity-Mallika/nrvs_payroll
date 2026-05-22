<?php include("../adminsession.php");
$pagename = "monthly_salary_cost_details.php";
$title = "Monthly Salary Cost Details";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Monthly Salary Cost Details";
$submodule = "Monthly Salary Cost Details";
$btn_name = "Save";
$imgpath = "uploaded/emp_documents/";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$month = (isset($_GET['month'])) ? $obj->test_input($_GET['month']) : date('m');
$year = (isset($_GET['year'])) ? $obj->test_input($_GET['year']) : date('Y');
$crit = "";
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and ss.department_id = '$department_id'";
    }
} else {
    $department_id = "";
};

if (isset($_GET['unit_id'])) {
    $unit_id = $obj->test_input($_GET['unit_id']);
    if ($unit_id != '') {
        $crit .= " and ss.unit_id = '$unit_id'";
    }
} else {
    $unit_id = $unitid;
};

if (isset($_POST['department_idd'])) {
    $department_id = $_POST['department_idd'];
    $unit_id = $_POST['unit_id'];
    $options = "<option value=''>All</option>";
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
                        <fieldset class="mt-2">
                            <form action="<?php echo $pagename; ?>" method="get">
                                <div class="card">
                                    <div class="card-header border-bottom-dashed">
                                        <div class="row g-4 align-items-center">
                                            <div class="col-sm">
                                                <div>
                                                    <h5 class="card-title mb-0"> <?= $module; ?><a href="monthly_salary_cost.php" class="float-end btn btn-primary btn-sm">Back</a></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-lg-3 mb-3">
                                                <label for="unit_id" class="form-label">Unit Name<span class="text-danger fw-bold">*</span></label>
                                                <select class="form-select chosen-select" name="unit_id" id="unit_id">

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
                                                <strong><label for="Month">Month<span class="text-danger fw-bold">*</span></label></strong></br>
                                                <select name="month" class="chosen-select form-control form-control" id="month">
                                                    <option value="">--Select Month--</option>
                                                    <?php for ($iM = 1; $iM <= 12; $iM++) {
                                                    ?>
                                                        <option value="<?php echo str_pad($iM, 2, '0', STR_PAD_LEFT); ?>"><?php echo date("F", strtotime(str_pad($iM, 2, '0', STR_PAD_LEFT) . "/12/10")); ?></option>
                                                    <?php
                                                    } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('month').value = '<?php echo $month; ?>';
                                                </script>
                                            </div>
                                            <div class="col-lg-3 col-12">
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


                                            <div class="col-md-3 mt-4 ">
                                                <input type="submit" class="btn btn-primary add-btn" onclick="return checkinputmaster('unit_id,year,month')" name="search" value="Search">
                                                <a href="<?php echo $pagename; ?>" class="btn btn-danger" name="reset" id="reset">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
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
                                                <th>Father’s Name</th>
                                                <th>Mobile No</th>
                                                <th>Department</th>
                                                <th>Basic Salary</th>
                                                <th>Basic DA</th>
                                                <th>Medical Allowances</th>
                                                <th>HRA</th>
                                                <th>Special Allowances</th>
                                                <th>Total Allowances</th>
                                                <th>Overtime Cost</th>
                                                <th>Bonus </th>
                                                <th>PF</th>
                                                <th>ESIC</th>
                                                <th>Employer PF</th>
                                                <th>Employer ESIC</th>
                                                <th>Net Salary</th>
                                                <th>Additional Pay</th>
                                                <th>Other Deduction</th>
                                                <th>Total Payable</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $slno = 1;
                                            $total_basic = 0;
                                            $total_da = 0;
                                            $total_medical = 0;
                                            $total_hra = 0;
                                            $total_special = 0;
                                            $total_allowance_sum = 0;
                                            $total_pf_emp = 0;
                                            $total_esic_emp = 0;
                                            $total_pf_employer = 0;
                                            $total_esic_employer = 0;
                                            $total_net_salary = 0;
                                            $total_additional = 0;
                                            $total_deduction = 0;
                                            $total_final_salary = 0;
                                            $res = $obj->executequery("SELECT dm.department_name, ss.*,em.first_name,em.last_name,em.father_name,em.mobile_no,em.emp_code FROM salary_structure as ss 

                                                    LEFT JOIN employee_master em 
                                                    ON ss.emp_id = em.emp_id

                                                    LEFT JOIN department_master dm 
                                                    ON ss.department_id = dm.department_id

                                                    WHERE ss.month='$month'
                                                    AND ss.year='$year' $crit                                                  
                                                    ORDER BY ss.emp_id ASC
                                                    ");
                                            foreach ($res as $row) {
                                                $total_allowance = $row['medical'] + $row['hra'] + $row['special_allow'];

                                                $total_basic += $row['basic_salary'];
                                                $total_da += $row['basic_da'];
                                                $total_medical += $row['medical'];
                                                $total_hra += $row['hra'];
                                                $total_special += $row['special_allow'];
                                                $total_allowance_sum += $total_allowance;

                                                $total_pf_emp += $row['pf_emp'];
                                                $total_esic_emp += $row['esic_emp'];
                                                $total_pf_employer += $row['pf_employer'];
                                                $total_esic_employer += $row['esic_employer'];

                                                $total_net_salary += $row['total_net_salary'];
                                                $total_additional += $row['additional_payment'];
                                                $total_deduction += $row['other_deduction'];
                                                $total_final_salary += $row['total_pay_sal_after_ded'];
                                            ?>
                                                <tr>
                                                    <td><?php echo $slno++; ?></td>

                                                    <td><?= $row["emp_code"]; ?></td>
                                                    <td> <?= ucfirst($row['first_name'] ?? ''); ?> <?= ucfirst($row['last_name'] ?? ''); ?></td>
                                                    <td><?php echo $row["father_name"]; ?></td>
                                                    <td><?php echo $row['mobile_no']; ?></td>
                                                    <td><?php echo $row['department_name']; ?></td>
                                                    <td><?php echo $row['basic_salary']; ?></td>
                                                    <td><?php echo $row['basic_da']; ?></td>
                                                    <td><?php echo $row['medical']; ?></td>
                                                    <td><?php echo $row['hra']; ?></td>
                                                    <td><?php echo $row['special_allow']; ?></td>
                                                    <td><?php echo $total_allowance; ?></td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td><?php echo $row['pf_emp']; ?></td>
                                                    <td><?php echo $row['esic_emp']; ?></td>
                                                    <td><?php echo $row['pf_employer']; ?></td>
                                                    <td><?php echo $row['esic_employer']; ?></td>
                                                    <td><?php echo $row['total_net_salary']; ?></td>
                                                    <td><?php echo $row['additional_payment']; ?></td>
                                                    <td><?php echo $row['other_deduction']; ?></td>
                                                    <td><?php echo $row['total_pay_sal_after_ded']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr style="font-weight:bold; background:#f5f5f5;">
                                                <td colspan="6" class="text-end">TOTAL</td>

                                                <td><?= number_format($total_basic, 2) ?></td>
                                                <td><?= number_format($total_da, 2) ?></td>
                                                <td><?= number_format($total_medical, 2) ?></td>
                                                <td><?= number_format($total_hra, 2) ?></td>
                                                <td><?= number_format($total_special, 2) ?></td>
                                                <td><?= number_format($total_allowance_sum, 2) ?></td>

                                                <td></td>
                                                <td></td>

                                                <td><?= number_format($total_pf_emp, 2) ?></td>
                                                <td><?= number_format($total_esic_emp, 2) ?></td>
                                                <td><?= number_format($total_pf_employer, 2) ?></td>
                                                <td><?= number_format($total_esic_employer, 2) ?></td>

                                                <td><?= number_format($total_net_salary, 2) ?></td>
                                                <td><?= number_format($total_additional, 2) ?></td>
                                                <td><?= number_format($total_deduction, 2) ?></td>
                                                <td><?= number_format($total_final_salary, 2) ?></td>
                                            </tr>
                                        </tfoot>
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
            get_department('<?= $unit_id ?>', '<?= $department_id ?>');
        });

        function funDel(id) {
            $('#deleteRecordModal').modal('show');
            tblname = '<?php echo $tblname; ?>';
            tblpkey = '<?php echo $tblpkey; ?>';
            imgpath = '<?php echo $imgpath; ?>';
            pagename = '<?php echo $pagename; ?>';
            submodule = '<?php echo $submodule; ?>';
            $('#delete-record').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/delete_master_emp.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&imgpath=' + imgpath + '&submodule=' + submodule + '&pagename=' + pagename,
                    dataType: 'html',
                    success: function(data) {
                        $("#tr_" + id).hide();
                        // alert(data);
                        // location.reload();
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