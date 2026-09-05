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
if (isset($_GET['attendance_date'])) {
    $attendance_date = $obj->test_input($_GET['attendance_date']);
    if ($attendance_date != '') {
        $crit .= " and ae.attendance_date='$attendance_date'";
    }
} else {
    $attendance_date = date('Y-m-d');
};

if (isset($_GET['shift_id'])) {
    $shift_id = $obj->test_input($_GET['shift_id']);
    if ($shift_id != '') {
        $crit .= " and ae.shift_id='$shift_id'";
    }
} else {
    $shift_id ="";
};
 
if (isset($_GET['att_action'])) {
    $att_action = $obj->test_input($_GET['att_action']);

    if ($att_action == 'Half Day' || $att_action == 'Weekly Leave' || $att_action == 'Earning Leave' || $att_action == 'Incomplete') {
        $crit .= " AND ae.attendance_status='$att_action'";
    } elseif ($att_action == 'Present') {
        $crit .= " AND attendance_status ='Present'";
    } elseif ($att_action == 'Absent') {
        $crit2 .= " AND (ae.attendance_status='Absent' OR ae.emp_id IS NULL)";
    }
} else {
    $att_action = "";
} 
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
                <?php //include('inc/alert.php'); ?>
                <div class="row">
                    <?php if (!isset($_GET['attendance_date'])) { ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a href="employee_master.php"
                                                    class="float-end btn btn-primary btn-sm">Add New</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get" id="attendanceForm">
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">
                                            <label for="emp_id" class="form-label">Attendence Date<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <input type="date" name="attendance_date" id="attendance_date"
                                                class="form-control form-control-sm" value="<?= $attendance_date ?>">
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label for="department_id" class="form-label">Department<span
                                                    class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="department_id" id="department_id">
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
                                            <label for="shift_id" class="form-label">Shift<span
                                                    class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="shift_id" id="shift_id">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("Select * from shift_master where unit_id='$unitid' order by shift_name asc");
                                                    foreach ($res as $key) { ?>
                                                <option value="<?= $key['shift_id']; ?>">
                                                    <?= $key['shift_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById('shift_id').value =
                                                '<?= $shift_id; ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label for="att_action" class="form-label">Action<span
                                                    class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select" name="att_action"
                                                id="att_action">
                                                <option value="">All</option>
                                                <option value="Incomplete">Incomplete</option>
                                                <option value="misspunch">Misspunch</option>
                                                <option value="Present">Present</option>
                                                <option value="Earning Leave">Earning Leave</option>
                                                <option value="Weekly Leave">Weekly Leave</option>
                                                <option value="Half Day">Half Day</option>

                                            </select>
                                            <script>
                                            document.getElementById('att_action').value =
                                                '<?= $att_action; ?>';
                                            </script>
                                        </div>


                                        <div class="col-lg-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary add-btn"
                                                value="Search" onClick="return checkinputmaster('attendance_date')">
                                            <a href="<?php echo $pagename ?>" class="btn btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if (isset($_GET['attendance_date'])) { ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> <a
                                                    href="<?=$pagename?>"
                                                    class="float-end btn btn-primary btn-sm ms-2">Search Again</a></h5>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>


                            <div class="card-body">
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary mb-2"
                                    onclick="openPunchModal()">
                                    Save Punch
                                </a>
                                <div class="table-responsive">
                                    <table id="buttons-datatables" class="display table table-sm table-bordered"
                                        style="width:100%">
                                        <thead>
                                            <tr class="table-primary">
                                                <th>Sr No.</th>
                                                <th style="text-align:center;">
                                                    <input type="checkbox" id="check_all" class="form-check-input">
                                                    Emp Code
                                                </th>
                                                <th style="text-align: center;">Emp Name </th>
                                                <th style="text-align: center;">Date</th>
                                                <th style="text-align: center;">In Time</th>
                                                <th style="text-align: center;">Out Time</th>
                                                <th style="text-align: center;">Machine Id</th>
                                                <th style="text-align: center;">Shift</th>
                                                <th style="text-align: center;">Department</th>
                                                <th style="text-align: center;">Designation</th>
                                                <th style="text-align: center;">Action</th>
                                                <th style="text-align: center;">Working Hours</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $slno = 1; 

                                                if ($att_action == 'Absent') {
                                                    $res = $obj->executequery("SELECT em.*, ae.*,sm.shift_name,dem.designation,dm.department_name FROM employee_master em LEFT JOIN attendance_entry ae ON em.emp_id = ae.emp_id AND ae.attendance_date = '$attendance_date' LEFT JOIN shift_master sm ON ae.shift_id=sm.shift_id LEFT JOIN department_master dm ON em.department_id=dm.department_id LEFT JOIN designation_master dem ON em.designation_id=dem.designation_id WHERE em.unit_id = '$unitid' $crit2 AND (em.resign_status != '1' OR (em.resign_status = '1' AND em.last_working_date >= CURDATE())) ORDER BY em.emp_id DESC");
                                                } else {
                                                    $res = $obj->executequery("SELECT ae.*,sm.shift_name,dm.department_name, em.first_name, em.last_name, em.designation_id,dem.designation,em.emp_code ,em.resign_status,em.last_working_date FROM $tblname ae LEFT JOIN employee_master em ON ae.emp_id = em.emp_id LEFT JOIN shift_master sm ON ae.shift_id=sm.shift_id LEFT JOIN department_master dm ON ae.department_id=dm.department_id LEFT JOIN designation_master dem ON em.designation_id=dem.designation_id WHERE ae.unit_id = '$unitid' $crit AND (em.resign_status != '1' OR (em.resign_status = '1' AND em.last_working_date >= CURDATE())) ORDER BY ae.$tblpkey DESC");
                                                }
 
                                                foreach ($res as $row) {

                                                ?>
                                            <tr>
                                                <td><?php echo $slno++; ?></td>
                                                <td>
                                                    <input type="checkbox" class="emp_checkbox form-check-input"
                                                        value="<?= $row['emp_id']; ?>">
                                                    <?= $row['emp_code']; ?>
                                                </td>
                                                <td> <?= ucfirst($row['first_name'] ?? ''); ?>
                                                    <?= ucfirst($row['last_name'] ?? ''); ?> </td>
                                                <td> <?php
                                                                echo !empty($row["attendance_date"])
                                                                    ? $obj->dateformatindia($row["attendance_date"])
                                                                    : $obj->dateformatindia($attendance_date);
                                                                ?></td>
                                                <td>

                                                    <?= !empty($row["intime"]) ? date("h:i:s A", strtotime($row["intime"])) : "-" ?>
                                                </td>
                                                <td>
                                                    <?= !empty($row["outtime"]) ? date("h:i:s A", strtotime($row["outtime"])) : "-" ?>
                                                </td>
                                                <td>
                                                    <?= $row['machineid']; ?>
                                                </td>
                                                <td>
                                                    <?= $row['shift_name']; ?>
                                                </td>
                                                <td>
                                                    <?= $row['department_name']; ?>
                                                </td>
                                                <td>
                                                    <?= $row['designation']; ?>
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
                                                <td><?= !empty($row["working_hours"]) ? $row["working_hours"] . " Hrs" : "0 Hrs"; ?>
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

    <div class="modal fade" id="punchModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Bulk Punch Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>From Status <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm chosen-select" id="from_status_modal">
                            <option value="">Select</option>
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Incomplete">Incomplete</option>
                            <option value="first_half">Half Day</option>
                            <option value="earn_leave">Leave</option>
                            <option value="half_earn_leave">Half Leave</option>
                            <option value="c_off">C-Off</option>
                            <option value="half_c_off">Half C-Off</option>
                            <option value="eoff">Extra Off</option>
                            <option value="half_eoff">Half Extra Off</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>To Status <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm chosen-select" id="to_status_modal">
                            <option value="">Select</option>
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="first_half">Half Day</option>
                            <option value="earn_leave">Leave</option>
                            <option value="half_earn_leave">Half Leave</option>
                            <option value="c_off">C-Off</option>
                            <option value="half_c_off">Half C-Off</option>
                            <option value="eoff">Extra Off</option>
                            <option value="half_eoff">Half Extra Off</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Outtime <span class="text-danger">*</span></label>
                        <input type="time" name="modal_outtime" id="modal_outtime" class="form-control form-control-sm" placeholder="Enter Outtime">
                    </div>

                    <div class="mb-3">
                        <label>Remark <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="remark" rows="3"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="save_punch_status()">
                        Save
                    </button>
                </div>

            </div>
        </div>
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

    function openPunchModal() {
        let selected = $('.emp_checkbox:checked').length;
        if (selected === 0) {
            Swal.fire('Warning',
                'Please select at least one employee',
                'warning');
            return;
        }
        $('#punchModal').modal('show');
    }

    $('#check_all').on('change', function() {
        $('.emp_checkbox').prop('checked', $(this).prop('checked'));
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

    function save_punch_status() {

       var attendance_date = '<?= isset($_GET['attendance_date']) ? $_GET['attendance_date'] : "" ?>';
        var from_status = $('#from_status_modal').val();
        var to_status = $('#to_status_modal').val();
        var modal_outtime = $('#modal_outtime').val(); 
        var remark = $('#remark').val().trim();
 var noShiftRequired = [
            'earn_leave',
            'Absent',
            'half_earn_leave',
            'c_off',
            'half_c_off',
            'eoff',
            'half_eoff',
            'leave',
            'half_leave'
        ];

        let emp_ids = [];

        $('.emp_checkbox:checked').each(function() {
            emp_ids.push($(this).val());
        });

        // Employee validation
        if (emp_ids.length === 0) {
            Swal.fire(
                'Warning',
                'Please select at least one employee',
                'warning'
            );
            return;
        }

        // From Status validation
        if (from_status === '') {
            Swal.fire(
                'Warning',
                'Please Select From Status',
                'warning'
            );
            return;
        }

        // To Status validation
        if (to_status === '') {
            Swal.fire(
                'Warning',
                'Please Select To Status',
                'warning'
            );
            return;
        }

        // Same status validation
        if (from_status === to_status) {
            Swal.fire(
                'Warning',
                'From Status and To Status cannot be the same',
                'warning'
            );
            return;
        }
        if (!noShiftRequired.includes(to_status) && modal_outtime == "") { 
            Swal.fire(
                'Warning',
                'Please Enter Outtime',
                'warning'
            );
            return;
        }

        // Remark validation
        if (remark == '') {
            Swal.fire(
                'Warning',
                'Please Enter Remark',
                'warning'
            );
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            html: `
            <b>Selected Employees :</b> ${emp_ids.length}<br>
            <b>From Status :</b> ${from_status}<br>
            <b>To Status :</b> ${to_status}<br> 
            <b>Remark :</b> ${remark}
        `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Convert',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33'
        }).then((result) => {

            if (!result.isConfirmed) {
                return;
            }

            // Loader
            Swal.fire({
                title: 'Please wait...',
                text: 'Updating attendance status...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                type: "POST",
                url: "ajax_bulk_status_convert.php",
                dataType: "json",
                data: {
                    attendance_date: attendance_date,
                    emp_ids: emp_ids,
                    from_status: from_status,
                    to_status: to_status,
                    modal_outtime: modal_outtime,
                    remark: remark
                },
                success: function(res) {
                    Swal.close();
                    // console.log('Response:', res);
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message || 'Something went wrong'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    console.log(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Server error. Please try again.'
                    });
                }
            });

        });
    }

    $('#attendanceForm').on('submit', function(e) {

    var att_action = $('#att_action').val();

    if (att_action === 'misspunch') {

        e.preventDefault();

        var attendance_date = $('#attendance_date').val();
        var department_id   = $('#department_id').val();
        var shift_id        = $('#shift_id').val();

        var url = 'misspunch_att.php'
            + '?attendance_date=' + encodeURIComponent(attendance_date)
            + '&department_id=' + encodeURIComponent(department_id)
            + '&shift_id=' + encodeURIComponent(shift_id)
            + '&att_action=' + encodeURIComponent(att_action)
            + '&search=' + 'Search';

        window.location.href = url;
    }

});
    </script>
</body>

</html>