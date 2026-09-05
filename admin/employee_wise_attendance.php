<?php
include("../adminsession.php");

$title = "Employee Wise Attendance";
$pagename = "employee_wise_attendance.php";
$module = "Employee Wise Attendance";
$submodule = "Employee Wise Attendance";
$currentMonth = date('m');
$currentYear = date('Y');
// Check if the parameters are present in the URL
if (isset($_GET['currentYear']) && isset($_GET['currentMonth'])) {
    $currentYear = $_GET['currentYear'];
    $currentMonth = $_GET['currentMonth'];
}

if (isset($_GET['emp_id'])) {
    $emp_id = $_GET['emp_id'];
    $emp_data = $obj->select_record("employee_master", ['emp_id' => $emp_id]);
    $basic_salary = $emp_data['basic_salary'] ?? '';
    $mobile_no = $emp_data['mobile_no'] ?? '';
    $emp_shift_id = $emp_data['shift_id'] ?? '';
    $emp_depart_id = $emp_data['department_id'] ?? '';

    $department_name = $obj->getvalfield("department_master", "department_name", "department_id='$emp_depart_id'");


    $shift_data = $obj->select_record("shift_master", ['shift_id' => $emp_shift_id]);
    $shift_in_time = $shift_data['in_time'] ?? '';
    $shift_out_time = $shift_data['out_time'] ?? '';
    $is_cross_day = $shift_data['is_cross_day'] ?? '';
    $emp_shift_name = $shift_data['shift_name'] ?? '';

    $shift_in_time  = date("h:i A", strtotime($shift_in_time));
    $shift_out_time = date("h:i A", strtotime($shift_out_time));
} else {
    $emp_id = '0';
    $basic_salary = "";
    $mobile_no = "";
    $shift_in_time = '00:00:00';
    $shift_out_time = '00:00:00';
    $is_cross_day = '0';
}

if (isset($_GET['prev'])) {
    $currentMonth--;
    if ($currentMonth < 1) {
        $currentMonth = 12;
        $currentYear--;
    }
}
if (isset($_GET['next'])) {
    $currentMonth++;
    if ($currentMonth > 12) {
        $currentMonth = 1;
        $currentYear++;
    }
}
//$three_month_leave = $obj->getLeave($emp_id, $currentMonth, $currentYear) ?? 0;

//$days_array = $obj->getDaysArray($currentMonth, $currentYear);
if (isset($_GET['date'])) {
    $date_as_new = $_GET['date'];
} else {
    $date_as_new = "";
}




if (isset($_REQUEST['ajax_emp_shift_hrs'])) {
    $ajax_emp_shift_hrs = $_REQUEST['ajax_emp_shift_hrs'];
    $empp_shift_id = $_REQUEST['empp_shift_id'] ?? 0;

    $options = "<option value=''>Please Select</option>";
    // die;
    $selected = "";
    if ($ajax_emp_shift_hrs != "" || $ajax_emp_shift_hrs > 0) {
        $res = $obj->executequery("Select * from shift_master where unit_id='$unitid' AND HOUR(working_hour) = '$ajax_emp_shift_hrs' order by shift_id asc");

        foreach ($res as $row) {
            $selected = ($empp_shift_id == $row['shift_id']) ? 'selected' : '';
            $options .= "<option value='" . $row['shift_id'] . "' $selected>" . $row['shift_name'] . " / " . $obj->getCustomCode($row['working_hour']) .   "</option>";
        }
    }

    echo $options;
    die;
}
?>
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title><?= $title ?></title>
    <?php include('inc/css.php') ?>

</head>
<style>
.radio-inputs {
    position: relative;
    display: flex;
    flex-wrap: wrap;
    border-radius: 0.5rem;
    box-sizing: border-box;
    padding: 0.25rem;
    font-size: 14px;
}

.radio-inputs .radio {
    /* flex: 1 1 auto; */
    text-align: center;
}

.radio-inputs .radio .name {
    display: flex;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    border: none;
    padding: .5rem 0;
    transition: all .15s ease-in-out;
    font-weight: 500;
}
</style>

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
                            <form method="" action="">
                                <div class="card">
                                    <div class="card-header border-bottom-dashed">
                                        <h5 class="card-title mb-0"><?= $module ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="mb-3 col-12 col-lg-5">
                                                <label for="bill_no" class="form-label">Employee<span
                                                        class="text-danger fw-bold"></span></label>
                                                <select class="form-select form-select-sm chosen-select" name="emp_id"
                                                    id="emp_id" onchange="getUrl(this.value);">
                                                    <option value="0">Select</option>
                                                    <?php
                                                    //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                    $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                    foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['emp_code']; ?> -
                                                        <?= ucfirst($key['first_name'] ?? ''); ?>
                                                        <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                    <?php } ?>
                                                </select>
                                                <script>
                                                document.getElementById('emp_id').value =
                                                    '<?= $emp_id; ?>';
                                                </script>

                                            </div>
                                            <?php if ($emp_id > 0) {
                                            ?>
                                            <div class="col-lg-6 offset-lg-1">
                                                <strong><label for="fromdate">Employee Details</label></strong>
                                                <div class="d-flex justify-content-between border-top pt-1">
                                                    <span><b>Basic Salary : </b> <?= $basic_salary ?></span>
                                                    <span> <b>Contact Details : </b><?= $mobile_no ?></span>
                                                </div>
                                                <div class="d-flex justify-content-between border-top pt-1">

                                                    <span> <b>Shift Code : </b><?= $obj->getCustomCode($emp_shift_id) ?>
                                                    </span>
                                                    <span><b>Department : </b> <?= $department_name; ?></span>
                                                </div>
                                                <div class="d-flex justify-content-between border-top pt-1">
                                                    <!-- <span><b>Allow Cross Day : </b> <?= $is_cross_day == '1' ? 'Yes' : "No" ?></span> -->
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

                <div id="show_att_data">

                </div>

                <!-- Attendance Summary -->
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="timepicker" tabindex="-1" aria-labelledby="timepickerLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="timepickerLabel">Punch In / Punch Out</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
                        <div class="col-lg-12 mb-5 ">
                            <label class="m-0">
                                Attendance Time
                            </label>
                            <input type="hidden" id="punchtype">
                            <input type="hidden" id="attdate">
                            <input type="time" id="punchtime" value="<?php echo date('H:i'); ?>" class="form-control">
                            <div class="col-lg-12 mt-2" id="shiftBox">
                                <label for="punch_shift_id" class="form-label ">Shift<span class="text-danger fw-bold">
                                    </span></label>
                                <select class="form-select form-select-sm chosen-select" name="punch_shift_id"
                                    id="punch_shift_id">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <label class="form-label mt-2"> Remark</label>
                            <textarea name="punch_remark" id="punch_remark" class="form-control "> </textarea>
                        </div>
                        <div class="col-lg-12 ">
                            <center>
                                <input type="button" class="btn btn-success btn-sm" value="Punch In"
                                    onclick="punchinout()" id="punchinbutton">
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Punch Attandance</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr class="mb-0 mt-2">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-lg-12 col-12 mb-2">
                            <label for="">Attandance</label>
                            <select name="punch_status" id="punch_status"
                                class="form-select form-select-sm chosen-select">
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="first_half">Half Day (1st Half)</option>
                                <option value="second_half">Half Day (2nd Half)</option>
                                <!-- <option value="weekly_leave">Weekly Leave</option> -->
                                <option value="earn_leave" class="earnOption2">Leave</option>
                                <!-- <option value="half_weekly_leave">Half Weekly Leave</option> -->
                                <option value="half_earn_leave" class="earnOption2">Half Leave</option>
                                <option value="c_off">C-Off</option>
                                <option value="half_c_off">Half C-Off</option>
                                <option value="eoff" class="extraOffOption">Extra Off</option>
                                <option value="half_eoff" class="extraOffOption">Half Extra Off</option>

                                <!-- <option value="leave" class="leaveOption">Leave</option>
                                <option value="half_leave" class="leaveOption">Half Leave</option> -->

                            </select>
                        </div>
                        <div class="col-lg-12 " id="punchShiftBox">
                            <label for="punch_att_shift_id" class="form-label ">Shift Code<span
                                    class="text-danger fw-bold"> </span></label>
                            <select class="form-select form-select-sm chosen-select" name="punch_att_shift_id"
                                id="punch_att_shift_id">
                                <option value="">Select</option>

                            </select>
                        </div>
                        <input type="hidden" id="punch_time">
                        <input type="hidden" id="punch_txt">
                        <input type="hidden" id="punch_attdate">
                        <div class="col-lg-12 col-12">
                            <label for="">Remark</label>
                            <textarea name="punching_remark" id="punching_remark" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <hr class="mb-0 mt-2">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savebutton" onclick="savePunch()">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AllAttendenceModal" tabindex="-1" aria-labelledby="AllAttendenceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="AllAttendenceModalLabel">Punch All Attandance</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr class="mb-0 mt-2">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-12 mb-2">
                            <label for="">Attandance</label>
                            <select name="punch_all_status" id="punch_all_status"
                                class="form-select form-select-sm chosen-select">
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="first_half">Half Day (1st Half)</option>
                                <option value="second_half">Half Day (2nd Half)</option>
                                <!-- <option value="weekly_leave">Weekly Leave</option> -->
                                <option value="earn_leave" class="earnOption2">Leave</option>
                                <!-- <option value="half_weekly_leave">Half Weekly Leave</option> -->
                                <option value="half_earn_leave" class="earnOption2">Half Leave</option>
                                <option value="eoff" class="extraOffOption2">Extra Off</option>
                                <option value="half_eoff" class="extraOffOption2">Half Extra Off</option>
                                <option value="c_off">C-Off</option>
                                <option value="half_c_off">Half C-Off</option>
                                <!-- <option value="leave" class="leaveOption2">Leave</option>
                                <option value="half_leave" class="leaveOption2">Half Leave</option> -->
                            </select>
                        </div>
                        <div class="col-lg-12 col-12 mb-2">
                            <label for="">Attandance Type</label>
                            <select name="punch_all_type" id="punch_all_type" class="form-select form-select-sm">
                                <option value="1">With Weekly Off</option>
                                <option value="0">Without Weekly Off</option>
                            </select>
                        </div>
                        <div class="col-lg-12 " id="punchShiftBox">
                            <label for="all_att_shift_id" class="form-label ">Shift Code<span
                                    class="text-danger fw-bold"> </span></label>
                            <select class="form-select form-select-sm chosen-select" name="all_att_shift_id"
                                id="all_att_shift_id">
                                <option value="">Select</option>

                            </select>
                        </div>
                        <div class="col-lg-12 col-12">
                            <label for="">Remark</label>
                            <textarea name="punching_all_remark" id="punching_all_remark"
                                class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <hr class="mb-0 mt-2">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveAllbutton" onclick="saveAllPunch()">Punch
                        All</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailsModal" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attendance Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalDetailsBody">
                    <div class="text-center">
                        <div class="spinner-border text-primary"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Close Modal -->
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <script>
    $(document).ready(function() {
        $('#example').DataTable();
        $(".chosen-select").select2({
            width: '100%',
        });
        showatttype();
    });

    function getUrl(id) {
        var currentYear = '<?= $currentYear ?>';
        var currentMonth = '<?= $currentMonth ?>';
        location = "employee_wise_attendance.php?emp_id=" + id + "&currentYear=" + currentYear + "&currentMonth=" +
            currentMonth;
    };

    function showatttype() {
        var emp_id = '<?php echo $emp_id ?>';
        var currentMonth = '<?php echo $currentMonth ?>';
        var currentYear = '<?php echo $currentYear ?>';
        var date_as_new = '<?php echo $date_as_new ?>';
        if (emp_id > 0) {
            jQuery.ajax({
                type: 'POST',
                url: 'view_att_details.php',
                data: 'currentMonth=' + currentMonth + '&currentYear=' + currentYear + '&emp_id=' + emp_id +
                    '&date_as_new=' + date_as_new,
                dataType: 'html',
                beforeSend: function() {
                    Swal.fire({
                        title: 'Please wait',
                        text: 'Loading attendance details...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(data) {
                    Swal.close();
                    document.getElementById('show_att_data').innerHTML = data;
                    //total(emp_id, currentMonth, currentYear);
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Unable to load attendance data', 'error');
                }

            }); //ajax close
        }
    }

    function opentimepicker(punchtype, attdate, time, remark, emp_shift_hrs, empp_shift_id) {
        document.getElementById('punchtype').value = punchtype;
        document.getElementById('punchtime').value = time;
        //document.getElementById('punchtime').value = current_time;
        document.getElementById('attdate').value = attdate;
        document.getElementById('punch_remark').value = remark;
        if (punchtype === 'outtime') {
            document.getElementById('shiftBox').style.display = 'none';
            document.getElementById('punch_shift_id').value = '';
        } else {
            $.ajax({
                type: "POST",
                url: "",
                data: {
                    ajax_emp_shift_hrs: emp_shift_hrs,
                    empp_shift_id: empp_shift_id
                },
                success: function(data) {
                    $("#punch_shift_id").html(data).trigger("change.select2");
                }
            });
            document.getElementById('shiftBox').style.display = 'block';
        }

        $('#timepicker').modal('show');
        if (punchtype == "intime") {
            punchtype = "Punch In";
        } else {
            punchtype = "Punch out";
        }
        $('#punchinbutton').val(punchtype);
    };

    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('timepicker');
        const punchButton = document.getElementById('punchinbutton');

        modal.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                // Trigger button click
                punchButton.click();
                event.preventDefault(); // Prevent default Enter behavior (e.g., form submission)
            } else if (event.key === 'Tab') {
                // Allow default tab behavior
                return;
            }
        });
    });

    function punchinout() {
        let shift_in_time = '<?= $shift_in_time ?>';
        let shift_out_time = '<?= $shift_out_time ?>';
        let is_cross_day = '<?= $is_cross_day ?>' ?? '0';

        var btn = document.getElementById('punchinbutton');
        var punchtype = document.getElementById('punchtype').value;
        var punchtime = document.getElementById('punchtime').value;
        var attdate = document.getElementById('attdate').value;
        var punch_remark = document.getElementById('punch_remark').value;
        var punch_shift_id = document.getElementById('punch_shift_id').value;
        if (punchtype == 'intime' && punch_shift_id == "") {
            alert("Please Select Shift Name");
            return false;
        }
        if (punch_remark == '') {
            alert("Please Enter Remark");
            return false;
        }

        //alert(is_cross_day);

        // if (punchtype == 'intime' && is_cross_day == '0' && punchtime > shift_out_time) {
        //     Swal.fire(
        //         "Punch In Not Allowed",
        //         "Punch In time is later than your shift Start and Out time.",
        //         "warning"
        //     ).then(() => {
        //         $('#timepicker').modal('hide');
        //     });
        //     return
        // }
        var currentYear = '<?php echo $currentYear; ?>';
        var currentMonth = '<?php echo $currentMonth; ?>';
        var emp_id = '<?php echo $emp_id; ?>';
        btn.disabled = true;
        btn.value = 'Saving...';
        jQuery.ajax({
            type: 'POST',
            url: 'ajax_att_save.php',
            data: 'punchtime=' + punchtime + '&status=' + 'punchinout' + '&emp_id=' + emp_id + '&punchtype=' +
                punchtype + '&attdate=' + attdate + '&currentYear=' + currentYear + '&currentMonth=' +
                currentMonth + '&punch_remark=' + punch_remark + '&punch_shift_id=' + punch_shift_id,
            dataType: 'html',
            success: function(data) {
                console.log(data);
                showatttype();
                $('#timepicker').modal('hide');
                document.getElementById('punch_remark').value = '';
                $('#punch_shift_id').val('').trigger('chosen:updated').trigger('change');
                btn.disabled = false;
                btn.value = 'Punched In';
                // total(emp_id, currentMonth, currentYear);
            },
            error: function() {
                btn.disabled = false;
                btn.value = 'Punch In';
                Swal.fire("Error", "Error while uploading. Try again.");
            }

        }); //ajax close

    }

    function funDel(id) {
        tblname = 'attendance_entry';
        tblpkey = 'attendance_id';
        pagename = '';
        submodule = '';
        module = '';
        //alert(module);
        if (confirm("Are you sure! You want to delete this record.")) {
            jQuery.ajax({
                type: 'POST',
                url: 'ajax/delete_master.php',
                data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' + submodule +
                    '&pagename=' + pagename + '&module=' + module,
                dataType: 'html',
                success: function(data) {
                    // location.reload();
                    showatttype();
                }

            }); //ajax close

        } //confirm close

    } //fun close

    function openPunchModal(attdate, time, remark, emp_shift_hrs, empp_shift_id, extraOffBalance, total_earning_leave,
        coff_balance,punch_txt) {
        document.getElementById('punch_attdate').value = attdate;
        document.getElementById('punching_remark').value = remark;
        document.getElementById('punch_txt').value = punch_txt;
        $.ajax({
            type: "POST",
            url: "",
            data: {
                ajax_emp_shift_hrs: emp_shift_hrs,
                empp_shift_id: empp_shift_id
            },
            success: function(data) {
                $("#punch_att_shift_id").html(data).trigger("change.select2");
            }
        });

        extraOffBalance = parseFloat(extraOffBalance);

        if (extraOffBalance >= 1) {
            // Full + Half  enable
            $("option[value='eoff']").prop("disabled", false).show();
            $("option[value='half_eoff']").prop("disabled", false).show();

        } else if (extraOffBalance >= 0.5) {
            // only Half enable
            $("option[value='eoff']").prop("disabled", true).hide();
            $("option[value='half_eoff']").prop("disabled", false).show();

        } else {
            // both disable
            $("option[value='eoff']").prop("disabled", true).hide();
            $("option[value='half_eoff']").prop("disabled", true).hide();
        }


        /* ================= EARN LEAVE ================= */
        total_earning_leave = parseFloat(total_earning_leave);

        if (total_earning_leave >= 1) {

            $("option[value='earn_leave']").prop("disabled", false).show();
            $("option[value='half_earn_leave']").prop("disabled", false).show();

        } else if (total_earning_leave >= 0.5) {

            // Only Half Earn Leave enable
            $("option[value='earn_leave']").prop("disabled", true).hide();
            $("option[value='half_earn_leave']").prop("disabled", false).show();

        } else {

            $("option[value='earn_leave']").prop("disabled", true).hide();
            $("option[value='half_earn_leave']").prop("disabled", true).hide();
        }


        coff_balance = parseFloat(coff_balance);
        if (coff_balance >= 1) {
            $("option[value='c_off']").prop("disabled", false).show();
            $("option[value='half_c_off']").prop("disabled", false).show();
        } else if (coff_balance >= 0.5) {
            // Only Half Earn Leave enable
            $("option[value='c_off']").prop("disabled", true).hide();
            $("option[value='half_c_off']").prop("disabled", false).show();
        } else {
            $("option[value='c_off']").prop("disabled", true).hide();
            $("option[value='half_c_off']").prop("disabled", true).hide();
        }



        $("#punch_status").trigger("chosen:updated");
        $('#exampleModal').modal('show');
    };

    function add_all_att(emp_shift_hrs, empp_shift_id, extraOffBalance, total_earning_leave, coff_balance) {

        $.ajax({
            type: "POST",
            url: "",
            data: {
                ajax_emp_shift_hrs: emp_shift_hrs,
                empp_shift_id: empp_shift_id
            },
            success: function(data) {
                $("#all_att_shift_id").html(data).trigger("change.select2");
            }
        });

        extraOffBalance = parseFloat(extraOffBalance);

        if (extraOffBalance >= 1) {
            // Full + Half  enable
            $("option[value='eoff']").prop("disabled", false).show();
            $("option[value='half_eoff']").prop("disabled", false).show();

        } else if (extraOffBalance >= 0.5) {
            // only Half enable
            $("option[value='eoff']").prop("disabled", true).hide();
            $("option[value='half_eoff']").prop("disabled", false).show();

        } else {
            // both disable
            $("option[value='eoff']").prop("disabled", true).hide();
            $("option[value='half_eoff']").prop("disabled", true).hide();
        }
        /* ================= EARN LEAVE ================= */
        total_earning_leave = parseFloat(total_earning_leave);

        if (total_earning_leave >= 1) {

            $("option[value='earn_leave']").prop("disabled", false).show();
            $("option[value='half_earn_leave']").prop("disabled", false).show();

        } else if (total_earning_leave >= 0.5) {

            // सिर्फ Half Earn Leave enable
            $("option[value='earn_leave']").prop("disabled", true).hide();
            $("option[value='half_earn_leave']").prop("disabled", false).show();

        } else {

            $("option[value='earn_leave']").prop("disabled", true).hide();
            $("option[value='half_earn_leave']").prop("disabled", true).hide();
        }

        coff_balance = parseFloat(coff_balance);
        if (coff_balance >= 1) {
            $("option[value='c_off']").prop("disabled", false).show();
            $("option[value='half_c_off']").prop("disabled", false).show();
        } else if (coff_balance >= 0.5) {
            // Only Half Earn Leave enable
            $("option[value='c_off']").prop("disabled", true).hide();
            $("option[value='half_c_off']").prop("disabled", false).show();
        } else {
            $("option[value='c_off']").prop("disabled", true).hide();
            $("option[value='half_c_off']").prop("disabled", true).hide();
        }


        $("#punch_all_status").trigger("chosen:updated");
        $('#AllAttendenceModal').modal('show');
    };

    function savePunch() {
        var btn = document.getElementById('savebutton');
        var punchtime = document.getElementById('punch_time').value;
        var attdate = document.getElementById('punch_attdate').value;
        var punch_remark = document.getElementById('punching_remark').value;
        var punch_status = document.getElementById('punch_status').value;
        var punch_shift_id = document.getElementById('punch_att_shift_id').value;
        var punch_txt = document.getElementById('punch_txt').value;

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

        if (!noShiftRequired.includes(punch_status) && punch_shift_id == "") {
            alert("Please Select Shift Name");
            return false;
        }


        // if (punch_shift_id == "") {
        //     alert("Please Select Shift Name");
        //     return false;
        // }

        if (punch_remark == '' && punch_status != 'Absent') {
            alert("Please Enter Remark");
            return false;
        }
        var currentYear = '<?php echo $currentYear; ?>';
        var currentMonth = '<?php echo $currentMonth; ?>';
        var emp_id = '<?php echo $emp_id; ?>';
        btn.disabled = true;
        btn.value = 'Saving...';
        jQuery.ajax({
            type: 'POST',
            url: 'ajax_att_save_punch.php',
            data: 'punchtime=' + punchtime + '&emp_id=' + emp_id + '&attdate=' + attdate + '&currentYear=' +
                currentYear + '&currentMonth=' + currentMonth + '&punch_remark=' + punch_remark + 
                '&punch_status=' + punch_status + '&punch_shift_id=' + punch_shift_id  + '&punch_txt=' + punch_txt,
            dataType: 'html',
            success: function(data) {
                console.log(data);
                // alert(data);
                showatttype();
                $('#exampleModal').modal('hide');
                document.getElementById('punching_remark').value = '';
                $('#punch_status').val('Present').trigger('chosen:updated').trigger('change');
                btn.disabled = false;
                btn.value = 'Save change';
                // total(emp_id, currentMonth, currentYear);
            },
            error: function() {
                btn.disabled = false;
                btn.value = 'Punch In';
                Swal.fire("Error", "Error while uploading. Try again.");
            }

        }); //ajax close

    }
function checkAllAttendance() {
    $('.attendance-checkbox').prop('checked', true);
}

function uncheckAllAttendance() {
    $('.attendance-checkbox').prop('checked', false);
}
    function saveAllPunch() {

        var selectedDates = [];
        $(".attendance-checkbox:checked").each(function() {
            selectedDates.push($(this).val());
        });

        if (selectedDates.length == 0) {
            Swal.fire('Warning', 'Please select at least one date', 'warning');
            return false;
        }

        var btn = document.getElementById('saveAllbutton');
        var punch_remark = document.getElementById('punching_all_remark').value;
        var punch_all_status = document.getElementById('punch_all_status').value;
        var punch_shift_id = document.getElementById('all_att_shift_id').value;
        var punch_all_type = document.getElementById('punch_all_type').value;
        var punchtime = '<?= date("H:i:s"); ?>';

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

        if (!noShiftRequired.includes(punch_all_status) && punch_shift_id == "") {
            Swal.fire('Warning', 'Please Select Shift Name', 'warning');
            return false;
        }

        if (punch_remark == '' && punch_all_status != 'Absent') {
            Swal.fire('Warning', 'Please Enter Remark', 'warning');
            return false;
        }

        var currentYear = '<?= $currentYear; ?>';
        var currentMonth = '<?= $currentMonth; ?>';
        var emp_id = '<?= $emp_id; ?>';

        Swal.fire({
            title: 'Are you sure?',
            html: `
            <b>Status:</b> ${punch_all_status}<br>
            <b>Total Selected Dates:</b> ${selectedDates.length}<br><br>
            Attendance will be updated for all selected dates.
        `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Save',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33'
        }).then((result) => {

            if (!result.isConfirmed) {
                return;
            }

            btn.disabled = true;
            btn.value = 'Saving...';

            Swal.fire({
                title: 'Please wait...',
                text: 'Applying attendance for selected days',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                type: 'POST',
                url: 'ajax_att_save_all_punch_selected.php',
                data: {
                    emp_id: emp_id,
                    currentYear: currentYear,
                    currentMonth: currentMonth,
                    punch_remark: punch_remark,
                    punch_status: punch_all_status,
                    punch_shift_id: punch_shift_id,
                    punchtime: punchtime,
                    punch_all_type: punch_all_type,
                    selected_dates: selectedDates
                },
                dataType: 'json',

                success: function(res) {

                    Swal.close();

                    if (res.status === 'error') {

                        Swal.fire({
                            icon: 'error',
                            title: 'Leave Balance Exceeded',
                            text: res.message
                        });

                        btn.disabled = false;
                        btn.value = 'Save Change';
                        return;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    showatttype();
                    $('#AllAttendenceModal').modal('hide');

                    document.getElementById('punching_all_remark').value = '';

                    $('#punch_all_status')
                        .val('Present')
                        .trigger('chosen:updated')
                        .trigger('change');

                    btn.disabled = false;
                    btn.value = 'Save Change';
                },

                error: function(xhr, status, error) {

                    Swal.close();

                    btn.disabled = false;
                    btn.value = 'Save Change';

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseText || 'Something went wrong'
                    });
                }
            });

        });
    }

    function showDetails(date, emp_id) {

        $('#detailsModal').modal('show');

        $('#modalDetailsBody').html(
            '<div class="text-center"><div class="spinner-border text-primary"></div></div>'
        );

        $.ajax({
            url: "get_attendance_details.php",
            type: "POST",
            data: {
                fulldate: date,
                emp_id: emp_id
            },
            success: function(response) {
                $('#modalDetailsBody').html(response);
            },
            error: function() {
                $('#modalDetailsBody').html(
                    '<div class="text-danger text-center">Error loading details</div>'
                );
            }
        });
    }
    </script>
</body>

</html>