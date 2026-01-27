<?php include("../adminsession.php");
$title = "Month Wise Attendance Report";
$pagename = "month_wise_attendance_report.php";
$module = "Search Attendance";
$submodule = "Month Wise Attendance List";
$btn_name = "Search";
$keyvalue = 0;
$tblname = "attendance_entry";
$tblpkey = "attendance_id";

$crit2 = " and 1=1";

if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit2 .= " and department_id = '$department_id'";
    }
} else {
    $department_id = "";
};

if (isset($_GET['action'])) {
    $action = addslashes(trim($_GET['action']));
} else {
    $action = "";
}

$month = date('m');
$year = date('Y');
$year_month = "";
if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = $obj->test_input($_GET['month']);

    $year = $obj->test_input($_GET['year']);
}
$get_days = $obj->getDaysArray($month, $year);
$length = count($get_days);


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
            $options .= "<option value='" . $row['shift_id'] . "' $selected>" . $row['shift_name'] . " / " . $row['working_hour'] . " Hrs" . "</option>";
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

                                            <div class="col-lg-3 mb-3">
                                                <label for="department_id" class="form-label">Department Name<span class="text-danger fw-bold">*</span></label>
                                                <select class="form-select chosen-select" name="department_id" id="department_id">
                                                    <option value="">All</option>
                                                    <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_name asc");
                                                    foreach ($res as $key) {
                                                        echo "<option value='" . $key['department_id'] . "'>" . $key['department_name'] . "</option>";
                                                    } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('department_id').value = '<?= $department_id; ?>';
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

                                            <div class="col-md-3 mt-4 ">
                                                <input type="submit" class="btn btn-primary add-btn" onclick="return checkinputmaster('department_id,year,month')" name="search" value="Search">
                                                <a href="<?php echo $pagename; ?>" class="btn btn-danger" name="reset" id="reset">Reset</a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                </div>
                <?php

                if (isset($_GET['search'])) {



                ?>



                    <div class="row mt-4 mb-4">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title mb-0"> <?= $submodule; ?></h5>
                                                <a onclick="exportTableToExcel('example')" class="btn btn-primary btn-sm">Export Excel File</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $employees = $obj->executequery("SELECT emp_id,emp_code , first_name , last_name FROM employee_master where unit_id='$unitid' $crit2");
                                    // if (!$employees) {
                                    //     echo "<p class='text-danger'>No employees found for this Deaprtment.</p>";
                                    //     return;
                                    // }

                                    if (empty($employees)) {
                                        // No employees → no attendance
                                        $attendances = [];
                                    } else {
                                        $empIds = array_column($employees, 'emp_id');
                                        $empIdsStr = implode(",", $empIds);

                                        $attendances = $obj->executequery("SELECT emp_id, attendance_date, in_status,attendance_status FROM $tblname WHERE emp_id IN ($empIdsStr) AND MONTH(attendance_date) = '$month' AND YEAR(attendance_date) = '$year'");
                                    }

                                    $attendanceMap = [];
                                    foreach ($attendances as $att) {
                                        $attendanceMap[$att['emp_id']][$att['attendance_date']] = $att['attendance_status'];
                                    }
                                    $currentDate = date("Y-m-d");
                                    ?>

                                    <div class="table-responsive" id="example1">
                                        <table id="example" class="table table-bordered table-hover align-middle">
                                            <thead class="table-light text-center">
                                                <tr>
                                                    <th style="width: 50px;">S.No.</th>
                                                    <th style="min-width: 180px;">Employee Name</th>
                                                    <th>Present Days</th>
                                                    <th>Punch All</th>
                                                    <?php for ($i = 1; $i <= $length; $i++) { ?>
                                                        <th style="width: 35px;"><?php echo $i; ?></th>
                                                    <?php } ?>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;
                                                foreach ($employees as $emp) {

                                                    $salary_generate_count = $obj->getvalfield("salary_structure", "count(*)", "emp_id='$emp[emp_id]' and month='$month' and year='$year'");
                                                    $emp_shift_hrs =  $obj->getvalfield("employee_master", "shift_id", "emp_id='$emp[emp_id]'");
                                                    $emp_shift_ids = $obj->getvalfield("attendance_entry", "shift_id", "emp_id='$emp[emp_id]' and month='$month' and year='$year' order by attendance_id desc limit 1") ?? '';
                                                    $total_present = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp[emp_id]' and month='$month' and year='$year' and attendance_status='Present'");

                                                    $total_half = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp[emp_id]' and month='$month' and year='$year' and attendance_status='Half Day'");

                                                    $total_att_leave = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp[emp_id]' and month='$month' and year='$year' and attendance_status='Leave'");

                                                    $total_attandence = $total_present + ($total_half / 2) + $total_att_leave;
                                                    echo "<tr>";
                                                    echo "<td>" . $slno++ . "</td>";
                                                    echo "<td> {$emp['emp_code']} - {$emp['first_name']} {$emp['last_name']}</td>";
                                                    echo "<td style='font-weight:bold; color:#6f42c1; background:#f3e8ff; text-align:center;'>";
                                                    echo number_format($total_attandence, 1);
                                                    echo "</td>";
                                                    echo "<td style='font-weight:bold; color:#28a745;text-align:center;'>";
                                                    echo "<span  style='display:block;width:100%;height:100%; padding:8px; cursor:pointer;' onclick=\"add_all_att('$emp_shift_hrs','$emp_shift_ids','$month','$year','$emp[emp_id]',' $salary_generate_count')\"><i class='ri-add-line align-bottom text-primary' style='font-size:20px;'></i></span>";
                                                    echo "</td>";


                                                    for ($i = 1; $i <= $length; $i++) {
                                                        $date = sprintf('%02d', $i);
                                                        $crit = "$year-$month-$date";

                                                        $is_holiday = false;
                                                        $holiday_data  = $obj->getvalfield("holiday_entry", "holiday_tittle", "FIND_IN_SET('$unitid', unit_id) and date='$crit'");

                                                        if (!empty($holiday_data)) {
                                                            $is_holiday = true;
                                                        }
                                                        // Future date → "-"
                                                        // Future date
                                                        if ($crit > $currentDate) {
                                                            $attendance = "-";
                                                            $bg = "rgb(220,220,220)";
                                                        } else {
                                                            $status = $attendanceMap[$emp['emp_id']][$crit] ?? '';
                                                            // Holiday AND employee is present
                                                            if ($is_holiday && in_array($status, ['Present', 'Half Day'])) {

                                                                if ($status === "Present") {
                                                                    $attendance = "<b>P</b>";
                                                                    $bg = "rgb(173,233,179)";
                                                                } else {
                                                                    $attendance = "<b>HD</b>";
                                                                    $bg = "rgb(255,246,163)";
                                                                }
                                                            }
                                                            // Holiday but NO attendance
                                                            elseif ($is_holiday && $status == '') {
                                                                $attendance = "<b>PL</b>";
                                                                $bg = "rgb(180,210,255)";
                                                            }
                                                            // Normal day attendance
                                                            else {
                                                                $status = $status ?: 'A';

                                                                if ($status === "Present") {
                                                                    $attendance = "<b>P</b>";
                                                                    $bg = "rgb(173,233,179)";
                                                                } elseif ($status === "Half Day") {
                                                                    $attendance = "<b>HD</b>";
                                                                    $bg = "rgb(255,246,163)";
                                                                } elseif ($status === "Incomplete") {
                                                                    $attendance = "<b>I</b>";
                                                                    $bg = "rgb(233,61,61)";
                                                                } elseif ($status === "Leave") {
                                                                    $attendance = "<b>L</b>";
                                                                    $bg = "rgb(229,204,255)";
                                                                } else {
                                                                    $attendance = "<b>A</b>";
                                                                    $bg = "rgb(251,175,175)";
                                                                }
                                                            }
                                                        }
                                                        $intime = $obj->getvalfield("attendance_entry", "intime", "emp_id='$emp[emp_id]' and attendance_date='$crit' and month='$month' and year='$year'") ?? date('H:i');
                                                        $att_in_remark = $obj->getvalfield("attendance_entry", "in_remark", "emp_id='$emp[emp_id]' and attendance_date='$crit' and month='$month' and year='$year'") ?? '';


                                                        $emp_shift_id = $obj->getvalfield("attendance_entry", "shift_id", "emp_id='$emp[emp_id]' and attendance_date='$crit' and month='$month' and year='$year'") ?? $emp_shift_ids;

                                                        // echo "<td style='background:$bg; text-align:center;'>";

                                                        if ($crit <= $currentDate) {
                                                            echo "<td style='background:$bg; text-align:center; padding:0;'>";

                                                            // echo "<a href='employee_wise_attendance.php?emp_id={$emp['emp_id']}&currentYear=$year&currentMonth=$month&date=$crit'target='_blank' style='display:block;width:100%;height:100%; padding:8px; text-decoration:none; color:inherit;' >";
                                                            echo "<span  style='display:block;width:100%;height:100%; padding:8px; cursor:pointer;' onclick=\"openPunchModal('$crit','$intime','$att_in_remark','$emp_shift_hrs','$emp_shift_id','$month','$year','$emp[emp_id]',' $salary_generate_count')\"> $attendance </span>";
                                                            // echo $intime;
                                                            echo "</a>";
                                                            echo "</td>";
                                                        } else {
                                                            echo "<td style='background:$bg; text-align:center;'>";
                                                            echo $attendance;
                                                            echo "</td>";
                                                        }
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
                <?php
                }

                ?>
            </div>
            <!-- Content close-->
        </div>
    </div>
    <div class="modal fade" id="salaryGeneratedModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 rounded-4 shadow">

                <!-- Header -->
                <div class="modal-header border-0 justify-content-end pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body text-center px-4 pb-4">

                    <!-- Icon -->
                    <div class="mb-3">
                        <i class="ri-error-warning-fill fs-1 text-danger"></i>
                    </div>

                    <!-- Message -->
                    <h6 class="fw-bold text-danger mb-2">
                        Salary Already Generated
                    </h6>

                    <p class="small mb-3">
                        Salary for this month has already been generated.
                        Please delete the salary first before modifying attendance.
                    </p>

                    <!-- Action Button -->
                    <a href="#" id="salaryReportLink"
                        class="btn btn-primary btn-sm px-3">
                        <i class="ri-money-rupee-circle-line me-1"></i>
                        View Salary Report
                    </a>

                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 pt-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-4"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>



    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr class="mb-0 mt-2">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-12 mb-2">
                            <label for="">Attandance</label>
                            <select name="punch_status" id="punch_status" class="form-select form-select-sm">
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="first_half">Half Day (1st Half)</option>
                                <option value="second_half">Half Day (2nd Half)</option>
                                <option value="Leave">Leave</option>
                            </select>
                        </div>
                        <div class="col-lg-12 " id="punchShiftBox">
                            <label for="punch_att_shift_id" class="form-label ">Shift<span
                                    class="text-danger fw-bold"> </span></label>
                            <select class="form-select form-select-sm chosen-select"
                                name="punch_att_shift_id" id="punch_att_shift_id">
                                <option value="">Select</option>

                            </select>
                        </div>
                        <input type="hidden" id="punch_time">
                        <input type="hidden" id="punch_attdate">
                        <input type="hidden" id="current_month">
                        <input type="hidden" id="current_year">
                        <input type="hidden" id="employee_id">
                        <div class="col-lg-12 col-12">
                            <label for="">Remark</label>
                            <textarea name="punching_remark" id="punching_remark" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <hr class="mb-0 mt-2">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savebutton" onclick="savePunch()">Punch</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AllAttendenceModal" tabindex="-1" aria-labelledby="AllAttendenceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="AllAttendenceModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr class="mb-0 mt-2">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-12 mb-2">
                            <label for="">Attandance</label>
                            <select name="punch_all_status" id="punch_all_status" class="form-select form-select-sm">
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="first_half">Half Day (1st Half)</option>
                                <option value="second_half">Half Day (2nd Half)</option>
                                <option value="Leave">Leave</option>
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
                            <label for="all_att_shift_id" class="form-label ">Shift<span
                                    class="text-danger fw-bold"> </span></label>
                            <select class="form-select form-select-sm chosen-select"
                                name="all_att_shift_id" id="all_att_shift_id">
                                <option value="">Select</option>

                            </select>
                        </div>
                        <input type="hidden" id="punch_all_month">
                        <input type="hidden" id="punch_all_year">
                        <input type="hidden" id="punch_all_employee_id">
                        <div class="col-lg-12 col-12">
                            <label for="">Remark</label>
                            <textarea name="punching_all_remark" id="punching_all_remark" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <hr class="mb-0 mt-2">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveAllbutton" onclick="saveAllPunch()">Punch All</button>
                </div>
            </div>
        </div>
    </div>
    <!-- script tag -->
    <?php include('inc/delete.php') ?>
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

            $(".chosen-select").select2();
        });

        function exportTableToExcel(tableID, filename = '') {
            // alert(tableID);

            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');


            // Specify file name
            filename = filename ? filename + '.xls' : 'excel_data.xls';
            // Create download link element
            downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);


            if (navigator.msSaveOrOpenBlob) {

                var blob = new Blob(['\ufeff', tableHTML], {

                    type: dataType

                });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {

                // Create a link to the file
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

                // Setting the file name
                downloadLink.download = filename;

                //triggering the function

                downloadLink.click();

            }

        }


        function openPunchModal(attdate, time, remark, emp_shift_hrs, empp_shift_id, month, year, emp_id, salary_generate_count) {
            document.getElementById('punch_attdate').value = attdate;
            document.getElementById('punching_remark').value = remark;
            document.getElementById('current_month').value = month;
            document.getElementById('current_year').value = year;
            document.getElementById('employee_id').value = emp_id;
            if (salary_generate_count > 0) {
                $('#salaryReportLink').attr(
                    'href',
                    'salary_generate_report.php?emp_id=' + emp_id
                );
                $('#salaryGeneratedModal').modal('show');
            } else {
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
                $('#exampleModal').modal('show');
            }

        };

        function savePunch() {
            var btn = document.getElementById('savebutton');
            var punchtime = document.getElementById('punch_time').value;
            var attdate = document.getElementById('punch_attdate').value;
            var punch_remark = document.getElementById('punching_remark').value;
            var punch_status = document.getElementById('punch_status').value;
            var punch_shift_id = document.getElementById('punch_att_shift_id').value;
            var currentMonth = document.getElementById('current_month').value;
            var currentYear = document.getElementById('current_year').value;
            var emp_id = document.getElementById('employee_id').value;

            if (punch_shift_id == "") {
                alert("Please Select Shift Name");
                return false;
            }
            btn.disabled = true;
            btn.value = 'Saving...';
            jQuery.ajax({
                type: 'POST',
                url: 'ajax_att_save_punch.php',
                data: 'punchtime=' + punchtime + '&emp_id=' + emp_id + '&attdate=' + attdate + '&currentYear=' + currentYear + '&currentMonth=' + currentMonth + '&punch_remark=' + punch_remark + '&punch_status=' + punch_status + '&punch_shift_id=' + punch_shift_id,
                dataType: 'html',
                success: function(data) {
                    //alert(data);
                    // showatttype();
                    $('#exampleModal').modal('hide');
                    document.getElementById('punching_remark').value = '';
                    $('#punch_status').val('Present').trigger('chosen:updated').trigger('change');
                    btn.disabled = false;
                    btn.value = 'Save change';
                    // total(emp_id, currentMonth, currentYear);
                    location.reload();
                },
                error: function() {
                    btn.disabled = false;
                    btn.value = 'Punch In';
                    Swal.fire("Error", "Error while uploading. Try again.");
                }

            }); //ajax close

        }

        function add_all_att(emp_shift_hrs, empp_shift_id, month, year, emp_id, salary_generate_count) {
            document.getElementById('punch_all_month').value = month;
            document.getElementById('punch_all_year').value = year;
            document.getElementById('punch_all_employee_id').value = emp_id;
            if (salary_generate_count > 0) {
                $('#salaryReportLink').attr(
                    'href',
                    'salary_generate_report.php?emp_id=' + emp_id
                );
                $('#salaryGeneratedModal').modal('show');
            } else {


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
                $('#AllAttendenceModal').modal('show');
            }
        };


        function saveAllPunch() {
            var btn = document.getElementById('saveAllbutton');
            var punch_remark = document.getElementById('punching_all_remark').value;
            var punch_all_status = document.getElementById('punch_all_status').value;
            var punch_shift_id = document.getElementById('all_att_shift_id').value;
            var punchtime = ' <?= date("H:i:s"); ?>';
            var currentMonth = document.getElementById('punch_all_month').value;
            var currentYear = document.getElementById('punch_all_year').value;
            var emp_id = document.getElementById('punch_all_employee_id').value;
            var punch_all_type = document.getElementById('punch_all_type').value;

            if (punch_shift_id == "") {
                alert("Please Select Shift Name");
                return false;
            }

            btn.disabled = true;
            btn.value = 'Saving...';
            Swal.fire({
                title: 'Please wait...',
                text: 'Applying attendance for all days',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            jQuery.ajax({
                type: 'POST',
                url: 'ajax_att_save_all_punch.php',
                data: 'emp_id=' + emp_id + '&currentYear=' + currentYear + '&currentMonth=' + currentMonth + '&punch_remark=' + punch_remark + '&punch_status=' + punch_all_status + '&punch_shift_id=' + punch_shift_id + '&punchtime=' + punchtime + '&punch_all_type=' + punch_all_type,
                dataType: 'html',
                success: function(data) {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Attendance saved successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        $('#AllAttendenceModal').modal('hide');
                        document.getElementById('punching_all_remark').value = '';
                        $('#punch_all_status').val('Present').trigger('chosen:updated').trigger('change');
                        btn.disabled = false;
                        btn.value = 'Save change';
                        location.reload();
                    });



                },
                error: function() {
                    btn.disabled = false;
                    btn.value = 'Punch In';
                    Swal.fire("Error", "Error while uploading. Try again.");
                }

            }); //ajax close

        }
    </script>
</body>

</html>