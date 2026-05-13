<?php include("../adminsession.php");
$pagename = "late_in_report.php";
$title = "Late In Report";
$tblname = "attendance_entry";
$tblpkey = "attendance_id";
$module = "Late In Report";
$submodule = "Late In Report";
$btn_name = "Save";

$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = ' and 1=1';

$datecurrent = date('Y-m-d');
$month = date('m');
$year = date('Y');
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and em.department_id='$department_id'";
    }
} else {
    $department_id = "";
};
if (isset($_GET['attendance_date'])) {
    $attendance_date = $obj->test_input($_GET['attendance_date']);
    if ($attendance_date != '') {
        $crit .= " and attendance_date='$attendance_date'";
    }
} else {
    $attendance_date = date('Y-m-d');
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
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a href="employee_master.php" class="float-end btn btn-primary btn-sm">Add New</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">
                                            <label for="emp_id" class="form-label">Attendence Date<span class="text-danger fw-bold">*</span></label>
                                            <input type="date" name="attendance_date" id="attendance_date" class="form-control form-control-sm" value="<?= $attendance_date ?>">
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="department_id" class="form-label">Department<span class="text-danger fw-bold"></span></label>
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

                                        <div class="col-lg-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary add-btn" value="Search" onClick="return checkinputmaster('attendance_date')">
                                            <a href="<?php echo $pagename ?>" class="btn btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($_GET['attendance_date'])) { ?>
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
                                                    <th style="text-align: center;">Employee </th>
                                                    <th style="text-align: center;">Date</th>
                                                    <th style="text-align: center;">In Time</th>
                                                    <th style="text-align: center;">Out Time</th>
                                                    <th style="text-align: center;">Late In Time</th>
                                                    <th style="text-align: center;">Early Out Time</th>
                                                    <th style="text-align: center;">Action</th>
                                                    <th style="text-align: center;">Working Hours</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;
                                                //$res = $obj->executequery("SELECT ae.*, em.first_name, em.last_name FROM $tblname ae LEFT JOIN employee_master em ON ae.emp_id = em.emp_id WHERE ae.unit_id = '$unitid' and ae.attendance_status='Half Day' $crit ORDER BY ae.$tblpkey DESC");

                                                $res = $obj->executequery(" SELECT ae.*, em.first_name, em.last_name 
    FROM $tblname ae 
    LEFT JOIN employee_master em ON ae.emp_id = em.emp_id 
    WHERE ae.unit_id = '$unitid' 
   
    AND (
        (ae.late_in IS NOT NULL AND ae.late_in != '00:00:00')
        OR 
        (ae.early_out IS NOT NULL AND ae.early_out != '00:00:00')
    )
    $crit 
    ORDER BY ae.$tblpkey DESC
");
                                                foreach ($res as $row) {

                                                    $last_name = $obj->getvalfield("employee_master", "last_name", "emp_id='$row[emp_id]'");
                                                    $first_name = $obj->getvalfield("employee_master", "first_name", "emp_id='$row[emp_id]'");
                                                ?>
                                                    <tr>
                                                        <td><?php echo $slno++; ?></td>
                                                        <td><?= $first_name . " " . $last_name; ?></td>
                                                        <td><?php echo $obj->dateformatindia($row["attendance_date"]); ?></td>
                                                        <td>
                                                            <?= !empty($row["intime"]) ? date("h:i A", strtotime($row["intime"])) : "-" ?>
                                                        </td>
                                                        <td>
                                                            <?= !empty($row["outtime"]) ? date("h:i A", strtotime($row["outtime"])) : "-" ?>
                                                        </td>
                                                        <td><?= $row["late_in"]; ?></td>
                                                        <td><?= $row["early_out"]; ?></td>
                                                        <td>
                                                            <a href="employee_wise_attendance.php?emp_id=<?php echo $row['emp_id'] ?>&currentYear=<?php echo $year ?>&currentMonth=<?php echo $month ?>&date=<?php echo $row['attendance_date']; ?>" target="_blank"> <?php echo $row['attendance_status']; ?>
                                                        </td>
                                                        <td><?= !empty($row["working_hours"]) ? $row["working_hours"] . " Hrs" : "0 Hrs"; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
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
            $('#example').DataTable();
            $(".chosen-select").select2({
                width: '100%',
                search_contains: true
            });

        });

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