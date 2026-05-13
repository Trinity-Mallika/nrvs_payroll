<?php include("../adminsession.php");
$pagename = "att_status_report.php";
$title = "Attendance Status Report";
$tblname = "attendance_entry";
$tblpkey = "attendance_id";
$module = "Attendance Status Report";
$submodule = "Attendance Status Report";
$btn_name = "Save";

$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = ' and 1=1';
$crit2 = '';

$datecurrent = date('Y-m-d');
$month = date('n');
$year = date('Y');
 
if (isset($_GET['month'])) {
    $month = $obj->test_input($_GET['month']);
    if ($month != '') {
          $crit .= " AND MONTH(ae.attendance_date) = '$month'";
    }
} else {
$month = date('n');
};
if (isset($_GET['year'])) {
    $year = $obj->test_input($_GET['year']);
    if ($year != '') {
        $crit .= " AND YEAR(ae.attendance_date) = '$year'";
    }
} else {
$year = date('Y');
};

  $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$having = "";

if (isset($_GET['only_absent']) && $_GET['only_absent'] == '1') {
    $having = " HAVING absent_count >= $totalDays ";
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
                                       <div class="col-lg-3 col-12">
                                            <label for="month" class="form-label">Month<span class="text-danger fw-bold">*</span></label>
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

                                        <!-- Year -->
                                        <div class="col-lg-3 col-12">
                                            <label for="year" class="form-label">Year<span class="text-danger fw-bold">*</span></label>
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
                                        <div class="col-lg-3 col-12 mt-4">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    name="only_absent"
                                                    id="only_absent"
                                                    value="1"
                                                    <?= isset($_GET['only_absent']) ? 'checked' : '' ?>>

                                                <label class="form-check-label" for="only_absent">
                                                    Show Only Full Month Absent Employees
                                                </label>
                                            </div>
                                        </div>

                                     

                                        <div class="col-lg-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary add-btn" value="Search" onClick="return checkinputmaster('month,year')">
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
                                                    <th style="text-align: center;">Employee Code </th>
                                                    <th style="text-align: center;">Employee Name </th> 
                                                    <th style="text-align: center;">Department</th>
                                                    <th style="text-align: center;">Designation</th>
                                                    <th style="text-align: center;">Month</th>
                                                    <th style="text-align: center;">Year</th>
                                                    <th style="text-align: center;">Present</th>
                                                    <th style="text-align: center;">Half Day</th>
                                                    <th style="text-align: center;">Leave</th>
                                                    <th style="text-align: center;">Absent</th> 
                                                    <th style="text-align: center;">Action</th> 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                               $lastDateOfMonth = date("Y-m-t", strtotime("$year-$month-01"));
                                                $slno = 1;
                                               $sql ="
                                                SELECT 
                                                    em.emp_id,
                                                    em.first_name,
                                                    em.emp_code,
                                                    dm.department_name,
                                                    dem.designation,

                                                    SUM(CASE WHEN ae.attendance_status = 'Present' THEN 1 ELSE 0 END) AS present_count,

                                                    (
                                                        SUM(CASE 
                                                            WHEN attendance_status IN ('Weekly Leave','Earning Leave','C Off') THEN 1 
                                                            ELSE 0 
                                                        END)
                                                        +
                                                        SUM(CASE 
                                                            WHEN attendance_status IN ('Half Weekly Leave','Half Earning Leave','Half C Off') THEN 0.5 
                                                            ELSE 0 
                                                        END)
                                                    ) AS total_leave_count,

                                                    SUM(CASE WHEN ae.attendance_status = 'Half Day' THEN 1 ELSE 0 END) AS halfday_count,

                                                    (
                                                        $totalDays 
                                                        - COUNT(ae.attendance_id)
                                                        + SUM(CASE WHEN ae.attendance_status = 'Absent' THEN 1 ELSE 0 END)
                                                    ) AS absent_count

                                                FROM employee_master em

                                                LEFT JOIN attendance_entry ae 
                                                    ON em.emp_id = ae.emp_id
                                                    AND MONTH(ae.attendance_date) = '$month'
                                                    AND YEAR(ae.attendance_date) = '$year'

                                                LEFT JOIN department_master dm 
                                                    ON em.department_id = dm.department_id

                                                LEFT JOIN designation_master dem 
                                                    ON em.designation_id = dem.designation_id

                                                WHERE em.unit_id = '$unitid' AND em.date_of_joining <= '$lastDateOfMonth'
                                                AND (
                                                    em.resign_status != '1' 
                                                    OR (
                                                        em.resign_status = '1' 
                                                        AND em.last_working_date >= CURDATE()
                                                    )
                                                )

                                                GROUP BY em.emp_id $having
                                                ORDER BY em.emp_id DESC
                                                ";
                    
                                         $res=$obj->executequery($sql);
                                          
                                                foreach ($res as $row) {
                                                ?>
                                                    <tr>
                                                        <td><?= $slno++; ?></td>

                                                        <td style="text-align:center;">
                                                            <?= $row['emp_code']; ?>
                                                        </td>

                                                        <td>
                                                            <?= ucfirst($row['first_name'] ?? ''); ?>
                                                        </td>

                                                        <td>
                                                            <?= $row['department_name']; ?>
                                                        </td>

                                                        <td>
                                                            <?= $row['designation']; ?>
                                                        </td>

                                                        <td style="text-align:center;">
                                                            <?= date('F', mktime(0, 0, 0, $month, 10)); ?>
                                                        </td>

                                                        <td style="text-align:center;">
                                                            <?= $year; ?>
                                                        </td>

                                                        <td style="text-align:center;">
                                                            <span class="badge bg-success">
                                                                <?= $row['present_count']; ?>
                                                            </span>
                                                        </td>
 
                                                        <td style="text-align:center;">
                                                            <span class="badge bg-warning text-dark">
                                                                <?= $row['halfday_count']; ?>
                                                            </span>
                                                        </td>

                                                        <td style="text-align:center;">
                                                            <span class="badge bg-info">
                                                                <?= $row['total_leave_count']; ?>
                                                            </span>
                                                        </td>

                                                        <td style="text-align:center;">
                                                            <span class="badge bg-danger">
                                                                <?= $row['absent_count']; ?>
                                                            </span>
                                                        </td>

                                                       <td style="text-align:center;">

                                                            <?php if ($row['absent_count'] >= $totalDays) { ?>

                                                                <a href="emp_separation.php?emp_id=<?= $row['emp_id']; ?>">
                                                                    <span class="badge bg-danger">
                                                                        Terminate
                                                                    </span>
                                                                </a>

                                                            <?php } ?>

                                                        </td>
                                                        
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
            // $('#example').DataTable();
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