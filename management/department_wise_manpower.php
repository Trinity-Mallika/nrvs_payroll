<?php include("../adminsession.php");
$pagename = "department_wise_manpower.php";
$title = "Department Wise Manpower";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Department Wise Manpower";
$submodule = "Department Wise Manpower List";
$btn_name = "Save";
$imgpath = "uploaded/emp_documents/";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = 'where 1=1';
if (isset($_GET['unit_id'])) {
    $unit_id = $obj->test_input($_GET['unit_id']);
    if ($unit_id != '') {
        $crit .= " and dm.unit_id='$unit_id'";
    }
} else {
    $unit_id = $unitid;
};
 
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and em.department_id='$department_id'";
    }
} else {
    $department_id = "";
};
  

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
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?> <a href="depart_manpower_details.php" class="float-end btn btn-primary btn-sm">View Details</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="get">
                                        <div class="row">
                                            <div class="col-lg-3 mb-3">
                                                <label for="unit_id" class="form-label">Unit Name<span class="text-danger fw-bold">*</span></label>
                                                <select class="form-select chosen-select" name="unit_id" id="unit_id" onchange="get_department(this.value);">

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
                                                <label for="department_id" class="form-label">Department Name<span class="text-danger fw-bold"> </span></label>
                                                <select class="form-select form-select-sm chosen-select" name="department_id" id="department_id">
                                                    <option value="">Select</option>

                                                </select>
                                                <script>
                                                    document.getElementById('department_id').value =
                                                        '<?= $department_id; ?>';
                                                </script>
                                            </div>

                                            <div class="col-lg-3 mt-4">
                                                <input type="submit" name="submit" class="btn btn-primary add-btn" value="Search" onclick="return checkinputmaster('unit_id')">
                                                <a href="<?php echo $pagename ?>" class="btn btn-danger add-btn">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                    if (isset($_GET['submit'])) {

                        $res = $obj->executequery("
                            SELECT
                                dm.department_name,
                                dm.department_id,

                                COUNT(em.emp_id) AS actual_employees,

                                SUM(CASE WHEN em.employee_type='Permanent' THEN 1 ELSE 0 END)
                                AS permanent_employees,

                                SUM(CASE WHEN em.employee_type='Contract' THEN 1 ELSE 0 END)
                                AS contract_employees,

                                 SUM(CASE WHEN em.employee_type='Part-Time' THEN 1 ELSE 0 END)
                                AS part_time_employees,

                                SUM(CASE WHEN em.employee_type='Trainee' THEN 1 ELSE 0 END)
                                AS trainee_employees

                            FROM employee_master em

                            LEFT JOIN department_master dm
                            ON em.department_id = dm.department_id

                            $crit 
                            and (em.resign_status != '1' OR (em.resign_status = '1' AND em.last_working_date >= CURDATE()))
                            GROUP BY dm.department_id

                            ORDER BY dm.department_name
                        ");


                    ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title mb-0"><?php echo $submodule; ?> </h5>
                                                <a href="<?php echo $pagename; ?>" class="btn btn-primary btn-sm">
                                                    Search Again
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <div class="table-responsive">

                                        <table id="buttons-datatables" class="display table table-sm table-bordered" style="width:100%">
                                            <thead>
                                                <th>Sr No</th>
                                                <th class="text-center">Department</th>
                                                <th class="text-center">Sanctioned Strength</th>
                                                <th class="text-center">Actual Employees</th>
                                                <th class="text-center">Vacancy</th>
                                                <th class="text-center">Contract Employees</th>
                                                <th class="text-center">Permanent Employees</th>
                                                <th class="text-center">Part Time Employees</th>
                                                <th class="text-center">Trainee Employees</th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;
                                                $total_actual = 0;
                                                $total_contract = 0;
                                                $total_permanent = 0;
                                                $total_part_time = 0;
                                                $total_trainee = 0;

                                                foreach ($res as $row) {

                                                    $actual = $row['actual_employees'];
                                                    $contract = $row['contract_employees'];
                                                    $permanent = $row['permanent_employees'];
                                                    $part_time = $row['part_time_employees'];
                                                    $trainee = $row['trainee_employees']; 
                                                    $total_actual += $actual;
                                                    $total_contract += $contract;
                                                    $total_permanent += $permanent;
                                                    $total_part_time += $part_time;
                                                    $total_trainee += $trainee;
                                                ?>
                                                    <tr>
                                                        <td><?= $slno++ ?></td>
                                                        <td class="text-center"><?= $row['department_name'] ?>  </td>
                                                        <td class="text-center"></td>
                                                        <td class="text-center"><?= $actual ?></td>
                                                        <td class="text-center"></td>
                                                        <td class="text-center"><?= $contract ?></td>
                                                        <td class="text-center"><?= $permanent ?></td>
                                                        <td class="text-center"><?= $part_time ?></td>
                                                        <td class="text-center"><?= $trainee ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot class="table-info">
                                                <tr>
                                                    <th colspan="3" style="text-align:right;">Total</th>
                                                    <th class="text-center"><?= $total_actual ?></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"><?= $total_contract ?></th>
                                                    <th class="text-center"><?= $total_permanent ?></th>
                                                    <th class="text-center"><?= $total_part_time ?></th>
                                                    <th class="text-center"><?= $total_trainee ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
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
            $(".chosen-select").select2({
                width: '100%',
                search_contains: true
            });

            $('#show_field').select2({
                width: '100%'
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