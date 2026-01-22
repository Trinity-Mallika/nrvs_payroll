<?php include("../adminsession.php");
$title = "Manual Attendance";
$pagename = "manual_attendance.php";
$module = "Search Attendance";
$submodule = "Manual Attendance List";
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

if (isset($_POST['ajax_save']) && $_POST['ajax_save'] == 1) {
    $emp_id = $_POST['emp_id'];
    $attendance_date = $_POST['attendance_date'];
    $type = $_POST['type'];
    $time = $_POST['time'];
    $month = date('m', strtotime($attendance_date));
    $year = date('Y', strtotime($attendance_date));

    $record_id = $obj->getvalfield("attendance_entry", "attendance_id", "emp_id='$emp_id' AND attendance_date='$attendance_date'");

    if (!$record_id) {
        // No record exists → insert only if IN
        if ($type == 'in') {
            $form_data = [
                "emp_id" => $emp_id,
                "attendance_date" => $attendance_date,
                "month" => $month,
                "year" => $year,
                "intime" => $time,
                "manual_in" => 1,
                "in_status" => 'IN',
                "type" => 'manual',
                "attendance_stamp" => $createdate,
                "entry_time" => date("H:i:s"),
                "createtime" => date("H:i:s"),
                "ipaddress" => $ipaddress,
                "createdate" => $createdate
            ];
            $obj->insert_record("attendance_entry", $form_data);
        } else {
            // OUT cannot be saved without IN
            echo 'no_record';
            exit;
        }
    } else {
        // Record exists → update IN or OUT
        $form_data = [];
        if ($type == 'in') {
            $form_data['intime'] = $time;
            $form_data['manual_in'] = 1;
            $form_data['in_status'] = 'IN';
        } else {
            $form_data['outtime'] = $time;
            $form_data['manual_out'] = 1;
            $form_data['out_status'] = 'OUT';
        }
        $form_data['lastupdated'] = $createdate;

        $where = ["attendance_id" => $record_id];
        $obj->update_record("attendance_entry", $where, $form_data);
    }

    echo 'success';
    exit;
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
                                    <?php
                                    $employees = $obj->executequery("SELECT emp_id, emp_name FROM employee_master $crit order by emp_name asc");
                                    if (!$employees) {
                                        echo "<p class='text-danger'>No employees found for this branch.</p>";
                                        return;
                                    }

                                    $empIds = array_column($employees, 'emp_id');
                                    $empIdsStr = implode(",", $empIds);

                                    $attendances = $obj->executequery("SELECT * FROM $tblname WHERE emp_id IN ($empIdsStr) AND attendance_date = '$attendance_date'");

                                    $attendanceMap = [];
                                    foreach ($attendances as $att) {
                                        $attendanceMap[$att['emp_id']] = $att;  // map emp_id → full row
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
                                                    echo "<tr data-empid='$empId'>";
                                                    echo "<td class='text-center'>" . $slno++ . ".</td>";
                                                    echo "<td>{$emp['emp_name']}</td>";

                                                    if (isset($attendanceMap[$empId])) {
                                                        $att = $attendanceMap[$empId];

                                                        // IN Time
                                                        echo "<td>";
                                                        if ($att['intime']) {
                                                            echo $att['intime'];
                                                            if ($att['manual_in'] == '1') {
                                                                echo "&nbsp;&nbsp;<a class='remove-item-btn' type='button' onclick=\"deleteAttendance({$att['attendance_id']},'in',$empId)\">
                                                                    <i class='ri-delete-bin-fill align-bottom text-danger'></i>
                                                                </a>";
                                                            }
                                                        } else {
                                                            echo "<input type='time' name='in_time[$empId]' class='form-control form-control-sm' style='width:130px;' onchange=\"saveAttendance($empId, 'in', this.value)\">";
                                                        }
                                                        echo "</td>";

                                                        // OUT Time
                                                        echo "<td>";
                                                        if ($att['outtime'] != '00:00:00') {
                                                            echo $att['outtime'];
                                                            if ($att['manual_out'] == '1') {
                                                                echo "&nbsp;&nbsp;<a class='remove-item-btn' type='button' onclick=\"deleteAttendance({$att['attendance_id']},'out',$empId)\">
                                                                    <i class='ri-delete-bin-fill align-bottom text-danger'></i>
                                                                </a>";
                                                            }
                                                        } else {
                                                            // OUT is empty → show input field
                                                            echo "<input type='time' name='out_time[$empId]' class='form-control form-control-sm' style='width:130px;' onchange=\"saveAttendance($empId, 'out', this.value)\">";
                                                        }
                                                        echo "</td>";
                                                    } else {
                                                        // No record → show input fields for both
                                                        echo "<td>
    <input type='time' 
           name='in_time[$empId]'
           class='form-control form-control-sm' 
           style='width:130px;' 
           onchange=\"saveAttendance($empId, 'in', this.value)\">
</td>";

                                                        echo "<td>
    <input type='time' 
           name='out_time[$empId]' 
           class='form-control form-control-sm' 
           style='width:130px;' 
           onchange=\"saveAttendance($empId, 'out', this.value)\">
</td>";
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
    <script>
        function saveAttendance(empId, type, value) {
            let attendanceDate = $('#attendance_date').val();

            $.ajax({
                url: '', // same page
                type: 'POST',
                data: {
                    emp_id: empId,
                    attendance_date: attendanceDate,
                    type: type,
                    time: value,
                    ajax_save: 1
                },
                success: function(response) {
                    if (response.trim() == 'success') {
                        console.log(empId + ' ' + type + ' saved.');
                    } else if (response.trim() == 'no_record') {
                        alert('Cannot save OUT-time because employee has not punched IN yet.');
                    } else {
                        alert('Error saving attendance: ' + response);
                    }
                }
            });
        }

        function deleteAttendance(attendanceId, type, empId) {
            var tblname = '<?= $tblname ?>';
            var tblpkey = '<?= $tblpkey ?>';

            if (confirm("Are you sure to delete this attendance?")) {
                $.ajax({
                    url: "ajax/delete_attendance.php",
                    type: "POST",
                    data: {
                        attendance_id: attendanceId,
                        type: type,
                        tblname: tblname,
                        tblpkey: tblpkey,
                    },
                    success: function(res) {
                        if (res.trim() == 'success') {
                            var row = $("tr[data-empid='" + empId + "']");
                            if (type == 'in') {
                                row.find("td").eq(2).html(
                                    `<input type='time' 
                                name='in_time[${empId}]' 
                                class='form-control form-control-sm' 
                                style='width:130px;' 
                                onchange="saveAttendance(${empId}, 'in', this.value)">`
                                );
                                row.find("td").eq(3).html(
                                    `<input type='time' 
                                name='out_time[${empId}]' 
                                class='form-control form-control-sm' 
                                style='width:130px;' 
                                onchange="saveAttendance(${empId}, 'out', this.value)">`
                                );
                            } else if (type == 'out') {
                                row.find("td").eq(3).html(
                                    `<input type='time' 
                                name='out_time[${empId}]' 
                                class='form-control form-control-sm' 
                                style='width:130px;' 
                                onchange="saveAttendance(${empId}, 'out', this.value)">`
                                );
                            }
                        } else {
                            alert('Error deleting attendance!');
                        }
                    }
                });
            }
        }
    </script>

</body>

</html>