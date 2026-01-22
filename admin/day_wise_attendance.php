<?php include("../adminsession.php");
$title = "Day Wise Attendance";
$pagename = "day_wise_attendance.php";
$module = "Search Attendance";
$submodule = "Day Wise Attendance List";
$btn_name = "Search";
$keyvalue = 0;
$tblname = "attendance_entry";
$tblpkey = "attendance_id";
$branch_id = (isset($_GET['branch_id'])) ? $obj->test_input($_GET['branch_id']) : 0;
$department_id = (isset($_GET['department_id'])) ? $obj->test_input($_GET['department_id']) : 0;
$attendance_date = (isset($_GET['attendance_date'])) ? $_GET['attendance_date'] : date('Y-m-d');
// $attendance_date = date('Y-m-d');
$crit = "WHERE branch_id = '$branch_id'";
if ($department_id > 0) {
    $crit .= " and department_id = '$department_id'";
}


if (isset($_GET['action'])) {
    $action = addslashes(trim($_GET['action']));
} else {
    $action = "";
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

    tr.absent {
        background-color: #f8d7da;
        /* light red */
    }

    tr.missing-inout {
        background-color: #fff3cd;
        /* light yellow */
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
                <div class="row">
                    <div class="col-lg-12">
                        <fieldset class="mt-2">
                            <form action="<?php echo $pagename; ?>" method="get">
                                <div class="card">
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
                                            <div class="col-lg-3 mb-2">
                                                <label for="branch_id" class="form-label">Branch Name<span class="text-danger fw-bold">*</span></label>
                                                <select class="form-select chosen-select" name="branch_id" id="branch_id">
                                                    <option value="">Select</option>
                                                    <?php $res = $obj->executequery("Select * from branch_master where status='1'");
                                                    foreach ($res as $key) { ?>
                                                        <option value="<?= $key['branch_id']; ?>"><?= $key['branch_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('branch_id').value = '<?= $branch_id; ?>';
                                                </script>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="department_id" class="form-label">Department Name<span class="text-danger fw-bold"></span></label>
                                                <select class="form-select chosen-select" name="department_id" id="department_id">
                                                    <option value="">Select</option>
                                                    <?php $res = $obj->executequery("Select * from department_master order by department_name asc");
                                                    foreach ($res as $key) {
                                                        echo "<option value='" . $key['department_id'] . "'>" . $key['department_name'] . "</option>";
                                                    } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('department_id').value = '<?= $department_id; ?>';
                                                </script>
                                            </div>
                                            <div class="col-md-3 md-2 ">
                                                <strong><label for="date">Date<span class="text-danger fw-bold">*</span></label></strong></br>
                                                <input type="date" name="attendance_date" id="attendance_date" class="form-control " value="<?= $attendance_date; ?>">
                                            </div>
                                            <div class="col-md-3 mt-4 ">
                                                <input type="submit" class="btn btn-primary add-btn" onclick="return checkinputmaster('branch_id,attendance_date')" name="search" value="Search">
                                                <a href="<?php echo $pagename; ?>" class="btn btn-danger" name="reset" id="reset">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                </div>
                <?php if ($branch_id > 0) { ?>
                    <div class="row mt-4 mb-4">
                        <div class="col-lg-12">
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
                                    <?php $currentDate = date("Y-m-d");
                                    $employees = $obj->executequery("SELECT emp_id, emp_name FROM employee_master $crit order by emp_name asc");
                                    if (!$employees) {
                                        echo "<p class='text-danger'>No employees found for this branch.</p>";
                                        return;
                                    }
                                    if ($attendance_date > $currentDate) {
                                        echo "<div class='alert alert-warning'>Future date selected. Attendance are not marked yet.</div>";
                                        return;
                                    }

                                    $empIds = array_column($employees, 'emp_id');
                                    $empIdsStr = implode(",", $empIds);

                                    $attendances = $obj->executequery("SELECT * FROM $tblname WHERE emp_id IN ($empIdsStr) AND attendance_date = '$attendance_date'");

                                    $attendanceMap = [];
                                    foreach ($attendances as $att) {
                                        $attendanceMap[$att['emp_id']] = $att;
                                    }
                                    ?>

                                    <div class="table-responsive" id="example1">
                                        <table id="example" class="table table-bordered table-hover align-middle">
                                            <thead class="table-light text-center">
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Employee Name</th>
                                                    <th>In Time</th>
                                                    <th>Out Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;
                                                foreach ($employees as $emp) {
                                                    $empId = $emp['emp_id'];
                                                    $rowClass = "";

                                                    if (!isset($attendanceMap[$empId])) {
                                                        $rowClass = "absent";
                                                    } elseif (
                                                        empty($attendanceMap[$empId]['intime']) || $attendanceMap[$empId]['intime'] == '00:00:00' ||
                                                        empty($attendanceMap[$empId]['outtime']) || $attendanceMap[$empId]['outtime'] == '00:00:00'
                                                    ) {
                                                        $rowClass = "missing-inout";
                                                    }

                                                    echo "<tr>";
                                                    echo "<td class='text-center'>" . $slno++ . ".</td>";
                                                    echo "<td>{$emp['emp_name']}</td>";

                                                    if (isset($attendanceMap[$empId])) {
                                                        $att = $attendanceMap[$empId];

                                                        // IN Time
                                                        echo "<td class='text-center'>";
                                                        echo ($att['intime'] && $att['intime'] != '00:00:00')
                                                            ? "<span class='badge bg-success'>{$att['intime']}</span>"
                                                            : "<span class='badge bg-danger'>Missing</span>";
                                                        echo "</td>";

                                                        // OUT Time
                                                        echo "<td class='text-center'>";
                                                        echo ($att['outtime'] && $att['outtime'] != '00:00:00')
                                                            ? "<span class='badge bg-success'>{$att['outtime']}</span>"
                                                            : "<span class='badge bg-warning text-dark'>Missing</span>";
                                                        echo "</td>";
                                                    } else {
                                                        echo "<td class='text-center'><span class='badge bg-danger'>Absent</span></td>";
                                                        echo "<td class='text-center'><span class='badge bg-danger'>Absent</span></td>";
                                                    }

                                                    echo "</tr>";
                                                }

                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!-- Content close-->
        </div>
    </div>
    <!-- script tag -->
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <!-- script tag -->

    <script>
        $(document).ready(function() {
            $('#example').DataTable().destroy();
            $('#example').DataTable({
                lengthMenu: [
                    [50, 100, 500, 1000],
                    [50, 100, 500, 1000]
                ],
                pageLength: 50
            });

            $(".chosen-select").chosen();
        });
    </script>
</body>

</html>