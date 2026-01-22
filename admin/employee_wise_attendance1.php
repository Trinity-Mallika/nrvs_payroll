<?php
include("../adminsession.php");

$title = "Employee Wise Attendance";
$pagename = "employee_wise_attendance1.php";
$module = "Employee Wise Attendance";
$submodule = "Employee Wise Attendance";
$userid = $_SESSION['userid'];
$currentMonth = date('m');
$currentYear = date('Y');
$emp_id = isset($_GET['emp_id']) ? $_GET['emp_id'] : 0;

// Get employee details if emp_id is set
$empDetails = [];
if ($emp_id > 0) {
    $empDetails = $obj->executequery("SELECT first_name FROM employee_master WHERE emp_id='$emp_id'");
    $first_name = $empDetails[0]['first_name'];
    // $basic_salary = $empDetails[0]['salary'];
    // $sal_type = $empDetails[0]['sal_type'];
}
?>
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title><?= $title ?></title>
    <?php include('inc/css.php') ?>
    <style>
        .table-borderless tr td {
            border: 0 !important;
            padding-bottom: 0;
        }
    </style>
</head>

<body>
    <?php include('inc/header.php') ?>
    <?php include('inc/sidebar.php') ?>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <?php include('inc/bredcrum.php') ?>

                <!-- Employee Selection -->
                <div class="row">
                    <div class="col-lg-12">
                        <fieldset class="mt-2">
                            <form method="GET" action="">
                                <div class="card">
                                    <div class="card-header border-bottom-dashed">
                                        <h5 class="card-title mb-0"><?= $module ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-3 mb-3">
                                                <label for="emp_id" class="form-label">Employee<span
                                                        class="text-danger fw-bold">*</span></label>
                                                <select class="form-select form-select-sm chosen-select"
                                                    name="emp_id" id="emp_id">
                                                    <option value="">Select</option>
                                                    <?php $res = $obj->executequery("Select * from employee_master order by emp_id asc");
                                                    foreach ($res as $key) { ?>
                                                        <option value="<?= $key['emp_id']; ?>">
                                                            <?= $key['first_name']; ?> <?= $key['last_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('emp_id').value =
                                                        '<?= $emp_id; ?>';
                                                </script>
                                            </div>


                                            <?php if ($emp_id > 0) {
                                                $length = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
                                                $attendances = $obj->executequery("SELECT attendance_date, in_status FROM attendance_entry WHERE emp_id='$emp_id' AND MONTH(attendance_date)='$currentMonth' AND YEAR(attendance_date)='$currentYear'");
                                                $attendanceMap = [];
                                                foreach ($attendances as $att) {
                                                    $attendanceMap[$att['attendance_date']] = $att['in_status'];
                                                }

                                                $presentCount = 0;
                                                $absentCount = 0;
                                                $today = date("Y-m-d");

                                                for ($i = 1; $i <= $length; $i++) {
                                                    $date = sprintf('%02d', $i);
                                                    $crit = "$currentYear-$currentMonth-$date";
                                                    if ($crit <= $today) {
                                                        $status = $attendanceMap[$crit] ?? 'A';
                                                        if ($status == 'IN') $presentCount++;
                                                        else $absentCount++;
                                                    }
                                                } ?>
                                                <div class="col-lg-6 offset-lg-1">
                                                    <strong><label>Employee Details</label></strong>
                                                    <div class="d-flex justify-content-between border-top pt-1">
                                                        <span><b>Basic Salary:</b> 00000</span>
                                                        <span><b>Salary Type:</b> 000000</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between border-top pt-1 mt-1">
                                                        <span style="color: #089108ff;"><b>Total Present:</b> <?= $presentCount ?></span>
                                                        <span style="color: #8f0a17;"><b>Total Absent:</b> <?= $absentCount ?></span>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                </div>

                <!-- Attendance Summary -->
                <?php if ($emp_id > 0) :
                ?>
                    <div class="col-lg-12 mt-1">
                        <div class="card">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title mb-0"> <?= $submodule; ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle text-center">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            for ($i = 1; $i <= $length; $i++) {
                                                $date = sprintf('%02d', $i);
                                                $crit = "$currentYear-$currentMonth-$date";

                                                $status = "-";
                                                $bg = "rgb(220,220,220)"; // future default

                                                if ($crit <= $today) {
                                                    $statusVal = $attendanceMap[$crit] ?? 'A';
                                                    if ($statusVal == 'IN') {
                                                        $status = "<b>P</b>";
                                                        $bg = "rgb(173,233,179)";
                                                    } else {
                                                        $status = "<b>A</b>";
                                                        $bg = "rgb(251,175,175)";
                                                    }
                                                }
                                            ?>
                                                <tr>
                                                    <td><?= $obj->dateformatindia($crit) ?></td>
                                                    <td style="background: <?= $bg ?>;">
                                                        <?php if ($crit <= $today) { ?>
                                                            <a href="employee_wise_attendance1.php?emp_id=<?= $emp_id ?>&currentYear=<?= $currentYear ?>&currentMonth=<?= $currentMonth ?>&date=<?= $crit ?>" target="_blank" style="text-decoration:none;color:inherit;">
                                                                <?= $status ?>
                                                            </a>
                                                        <?php } else {
                                                            echo $status;
                                                        } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>

    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>

    <script>
        $(".chosen-select").chosen();

        if (!$.fn.dataTable.isDataTable('#example')) {
            $('#example').DataTable({
                lengthMenu: [
                    [50, 100, 500, 1000],
                    [50, 100, 500, 1000]
                ],
                pageLength: 50
            });
        }

        function getUrl(id) {
            var currentYear = '<?= $currentYear ?>';
            var currentMonth = '<?= $currentMonth ?>';
            location = "employee_wise_attendance1.php?emp_id=" + id + "&currentYear=" + currentYear + "&currentMonth=" + currentMonth;
        }
    </script>
</body>

</html>