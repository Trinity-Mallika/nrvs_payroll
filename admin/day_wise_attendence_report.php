<?php include("../adminsession.php");
$pagename = "day_wise_attendence_report.php";
$title = "Days Wise Attendance Report";
$tblname = "attendance_entry";
$tblpkey = "attendance_id";
$module = "Days Wise Attendance Report";
$submodule = "Days Wise Attendance Report";
$btn_name = "Save";

$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = ' and 1=1';
$crit2 = '';

$datecurrent = date('Y-m-d');
$month = date('m');
$year = date('Y');
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and em.department_id='$department_id'";
        $crit2 .= " and em.department_id='$department_id'";
    }
} else {
    $department_id = "";
};
// if (isset($_GET['att_action'])) {
//     $att_action = $obj->test_input($_GET['att_action']);
//     if ($att_action != '') {
//         $crit .= " and ae.attendance_status='$att_action'";
//     }
// } else {
//     $att_action = "";
// };
if (isset($_GET['attendance_date'])) {
    $attendance_date = $obj->test_input($_GET['attendance_date']);
    if ($attendance_date != '') {
        $crit .= " and ae.attendance_date='$attendance_date'";
    }
} else {
    $attendance_date = date('Y-m-d');
};

if (isset($_GET['att_action'])) {
    $att_action = $obj->test_input($_GET['att_action']);

    if ($att_action == 'Half Day' || $att_action == 'Leave' || $att_action == 'Incomplete') {
        $crit .= " AND ae.attendance_status='$att_action'";
    } elseif ($att_action == 'Present') {
        $crit .= " AND attendance_status IN ('Present', 'Incomplete','Half Day')";
    } elseif ($att_action == 'Absent') {
        $crit2 .= " AND ae.emp_id IS NULL";
    }
} else {
    $att_action = "";
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

                                        <div class="col-lg-3 mb-3">
                                            <label for="att_action" class="form-label">Action<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select" name="att_action" id="att_action">
                                                <option value="">All</option>
                                                <option value="Incomplete">Incomplete</option>
                                                <option value="Present">Present</option>
                                                <option value="Leave">Leave</option>
                                                <option value="Half Day">Half Day</option>

                                            </select>
                                            <script>
                                                document.getElementById('att_action').value =
                                                    '<?= $att_action; ?>';
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
                                                    <th style="text-align: center;">Action</th>
                                                    <th style="text-align: center;">Working Hours</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;

                                                if ($att_action == 'Absent') {
                                                    $res = $obj->executequery("SELECT em.*, ae.*FROM employee_master em LEFT JOIN attendance_entry ae ON em.emp_id = ae.emp_id AND ae.attendance_date = '$attendance_date' WHERE em.unit_id = '$unitid' $crit2 ORDER BY em.emp_id DESC");
                                                } else {
                                                    $res = $obj->executequery("SELECT ae.*, em.first_name, em.last_name,em.emp_code FROM $tblname ae LEFT JOIN employee_master em ON ae.emp_id = em.emp_id WHERE ae.unit_id = '$unitid' $crit ORDER BY ae.$tblpkey DESC");
                                                }


                                                foreach ($res as $row) {

                                                ?>
                                                    <tr>
                                                        <td><?php echo $slno++; ?></td>
                                                        <td> <?= $row['emp_code']; ?>-<?= ucfirst($row['first_name'] ?? ''); ?> <?= ucfirst($row['last_name'] ?? ''); ?> </td>
                                                        <td> <?php
                                                                echo !empty($row["attendance_date"])
                                                                    ? $obj->dateformatindia($row["attendance_date"])
                                                                    : $obj->dateformatindia($attendance_date);
                                                                ?></td>
                                                        <td>
                                                            <?= !empty($row["intime"]) ? date("h:i A", strtotime($row["intime"])) : "-" ?>
                                                        </td>
                                                        <td>
                                                            <?= !empty($row["outtime"]) ? date("h:i A", strtotime($row["outtime"])) : "-" ?>
                                                        </td>
                                                        <td>
                                                            <?php if (!empty($row['attendance_status'])) { ?>
                                                                <a href="employee_wise_attendance.php?emp_id=<?= $row['emp_id'] ?>&currentYear=<?= $year ?>&currentMonth=<?= $month ?>&date=<?= $row['attendance_date']; ?>"
                                                                    target="_blank">
                                                                    <?= $row['attendance_status']; ?>
                                                                </a>
                                                            <?php } else { ?>
                                                                <span class="text-danger">Absent</span>
                                                            <?php } ?>
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