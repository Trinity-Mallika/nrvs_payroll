<?php include("appsession.php");
$pagename = "employee_wise_attendance.php";
$currentYear = $_POST['currentYear'];
$currentMonth = $_POST['currentMonth'];
$date_as_new = $_POST['date_as_new'];
$emp_id = $_POST['emp_id'];
$emp_data =  $obj->select_record("employee_master", array("emp_id" => $emp_id));
$days_array = $obj->getDaysArray($currentMonth, $currentYear);
$doj = $emp_data['date_of_joining'] ?? '';
$emp_shift_hrs = $emp_data['shift_id'] ?? '';
$unit_id = $emp_data['unit_id'] ?? '';
$department_id = $emp_data['department_id'];
$allow_weekly_off = $emp_data['allow_weekly_off'];
$sessionid = $emp_data['sessionid'];

$is_allow_c_off = $obj->getvalfield("department_master", "c_off_check", "department_id='$department_id'");
$is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$emp_data[unit_id]'");
$is_esic = $emp_data['is_esic'];

$setting_type = ($is_esic  == 1) ? 'ESIC' : 'Non ESIC';
$dateforas = date('Y-m-d', strtotime("$currentYear-$currentMonth")) ?? '';

$totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
$salary_generate_count = $obj->getvalfield("salary_structure", "count(*)", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear'");

$total_present1 = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear' and attendance_status='Present'");

$total_half1 = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear' and attendance_status='Half Day'");

$total_present = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear' and attendance_status IN('Present','Weekly Leave','Earning Leave','C Off')");

$total_half = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear' and attendance_status IN('Half Day','Half Weekly Leave','Half Earning Leave','Half C Off')");
// echo $total_half;
// die;
$total_att_leave = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear' and attendance_status='Leave'");
// echo $total_att_leave;
// die;
$total_attandence = $total_present + ($total_half / 2);

$real_total_attandence = $total_present1 + ($total_half1 / 2);

$emp_shift_ids = $obj->getvalfield("attendance_entry", "shift_id", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear' order by attendance_id desc limit 1") ?? '';

// echo $salary_generate_count;
// die;

$three_month_leave = $obj->getLeave($emp_id, $currentMonth, $currentYear);
// echo $three_month_leave;
// die;

$monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $real_total_attandence, $unit_id);
$week_leave = $obj->totalWeeklyLeave($unit_id, $real_total_attandence, $allow_weekly_off);
$total_earning_leave = $obj->getEarningLeave($emp_id, $sessionid);
$total_curr_week_leave = $obj->getCurrentWeekLeave($emp_id, $currentMonth, $currentYear);

$holidayData = $obj->getHolidayCountWithSandwichRule(
    $emp_id,
    $unit_id,
    $currentMonth,
    $currentYear
);

$holiday_total     = $holidayData['total'] ?? 0;
$holiday_national  = $holidayData['national'] ?? 0;
$holiday_religious = $holidayData['religious'] ?? 0;
$holiday_seasonal  = $holidayData['seasonal'] ?? 0;

$total_payable_days = $total_attandence;
$total_payable_days += $week_leave;
if ($is_all_leave_add == 1) {
    $total_payable_days += $monthly_leave;
}
$total_payable_days += $three_month_leave;
if ($is_allow_c_off == 1) {
    $total_payable_days = min($total_payable_days, $totalDaysInMonth);
}

?>
<div class="row mt-4">
    <div class="col-lg-8 offset-lg-2">
        <div class=" card-body border-0 shadow-sm d-flex justify-content-between rounded-5 bg-white p-2">
            <a href="?prev&currentYear=<?php echo $currentYear ?>&currentMonth=<?php echo $currentMonth; ?>&emp_id=<?php echo $emp_id ?>"> <?php if ($doj <= $dateforas) { ?><<i class="ri ri-arrow-left-s-fill fs-3"></i> <?php } ?></a>
            <span class="mt-2">
                <h6 class="mb-0">
                    <?php echo date('M', strtotime("$currentYear-$currentMonth")); ?>
                    <?php echo date('Y', strtotime("$currentYear-$currentMonth")); ?>
                </h6>
            </span>
            <a href="?next&currentYear=<?php echo $currentYear ?>&currentMonth=<?php echo $currentMonth; ?>&emp_id=<?php echo $emp_id ?>"><i class="ri ri-arrow-right-s-fill fs-3"></i>></a>
        </div>
    </div>
</div>


<div class="row mt-4">


    <div class="col-lg-12 mt-4 align-items-center">
        <h5>Employee Wise Attendance List </h5>
        <div class="col-md-2 text-primary">
            Total Present: <b><?= $real_total_attandence; ?></b>
        </div>
        <div class="col-md-2 text-primary">
            Total Attendance With Leave: <b><?= $total_attandence; ?></b>
        </div>

        <div class="col-md-2 text-primary">
            Weekly Off : <b><?= $week_leave; ?></b>
        </div>

        <div class="col-md-2 text-primary">
            Earn Leave : <b><?= $monthly_leave; ?></b>
        </div>

        <div class="col-md-2 text-primary">
            Total Payable Days : <b><?= $total_payable_days ?></b>
        </div>

        <div class="col-md-2 text-primary">
            Holiday : <b><?= $holiday_total; ?></b>
            <div style="font-size:12px" class="text-muted">
                NH : <?= $holiday_national; ?> |
                RH : <?= $holiday_religious; ?> |
                SH : <?= $holiday_seasonal; ?>
            </div>
        </div>

    </div>

    <h6 class="bg-body-secondary border-bottom-outset fs-15 mt-3 p-3 rounded-2 shadow-lg d-flex justify-content-between align-items-center"
        style="background: linear-gradient(90deg, #edf2b7, #ffffff); border-color:#7696ff;">

        <span>Last three month pending Week Off : <?= $three_month_leave ?></span>

        <span>Advance Week Off : <?= $total_curr_week_leave ?></span>

        <span>Pending Earn leave : <?= $total_earning_leave ?></span>

    </h6>

    <?php
    $has_valid_day = false;
    foreach ($days_array as $key) {
        $current_date = date('Y-m-d');
        $attendance_id = $obj->getvalfield("attendance_entry", "attendance_id", "emp_id='$emp_id' and attendance_date='$key[fulldate]'");

        $is_holiday = false;
        $holiday_title  = $obj->getvalfield("holiday_entry", "holiday_tittle", "FIND_IN_SET('$unit_id', unit_id) and date='{$key['fulldate']}'");

        if (!empty($holiday_title)) {
            $is_holiday = true;
        }
        $holiday_bg = $is_holiday ? "background:#FFF3CD;border:1px solid #FFECB5;" : "";


        if ($key['fulldate'] > $current_date) {
            continue;
        }

        if ($key['fulldate'] < $doj) {
            continue;
        }

        $attendance_data = $obj->select_record('attendance_entry', ['emp_id' => $emp_id, 'attendance_date' => $key['fulldate'], 'month' => $currentMonth, 'year' => $currentYear]);
        $intime = $attendance_data['intime'] ?? '';
        $emp_shift_id = $attendance_data['shift_id'] ?? $emp_shift_ids;
        $att_in_remark = $attendance_data['in_remark'] ?? '';
        $att_out_remark = $attendance_data['out_remark'] ?? '';
        $outtime = $attendance_data['outtime'] ?? '';
        $attheadid = $attendance_data['attheadid'] ?? '';
        $working_hours = $attendance_data['working_hours'] ?? '';
        $entry_type = $attendance_data['entry_type'] ?? '';

        $attendance_date = $obj->getvalfield("attendance_entry", "attendance_date", "emp_id='$emp_id'  and month='$currentMonth' and year='$currentYear'");

        $attendance_status = $obj->getvalfield("attendance_entry", "attendance_status", "emp_id='$emp_id'  and month='$currentMonth' and year='$currentYear' and attendance_id='$attendance_id'");

        if ($intime != '' &&  $intime != '00:00:00') {
            $readonly = "1";
            $color = "";
        } else {
            $readonly = "0";
            // $color = "background: aliceblue;";
        }
        $badgeClass = '';
        if ($attendance_status == 'Present') {
            $badgeClass = 'text-success';
        } elseif ($attendance_status == 'Half Day') {
            $badgeClass = 'text-warning';
        } elseif ($attendance_status == 'Absent') {
            $badgeClass = 'text-danger';
        } elseif ($attendance_status == 'Leave') {
            $badgeClass = 'text-danger';
        }

        if ($doj <= $key['fulldate']) {

            if ($intime != '' &&  $intime != '00:00:00') {
                $intime_new = $intime;
            } else {
                $intime_new = date('H:i');
            }
            if ($outtime != '' &&  $outtime != '00:00:00') {
                $outtime_new = $outtime;
            } else {
                $outtime_new = date('H:i');
            }
            if ($date_as_new == "" || $date_as_new == $key['fulldate']) {

                if ($key['fulldate'] <= $current_date) {
                    $has_valid_day = true;
    ?>
                    <div class="col-lg-12 mt-1">
                        <div class="card card-body border rounded-5 p-0" style="<?= $holiday_bg ?>">
                            <span class="water-mark"></span>
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="day-card">
                                        <div class="day-header">
                                            <small class="fw-semibold fs-15">
                                                <?php echo $key['day'] . " " . $key['month'] . " | " . $key['day_name'] ?>
                                            </small>
                                        </div>

                                        <div class="row-box">
                                            <div>
                                                <div class="icon">⏰</div>
                                                <div><?php echo date("h:i:A", strtotime($intime)) ?></div>
                                                <div class="label">Check In</div>
                                            </div>

                                            <div>
                                                <div class="icon">⏰</div>
                                                <div><?php echo date("h:i:A", strtotime($outtime)) ?></div>
                                                <div class="label">Check Out</div>
                                            </div>
                                            <?php if (!empty($working_hours)) { ?>
                                                <div>
                                                    <div class="icon">✔</div>
                                                    <div> <?= $working_hours ?></div>
                                                    <div class="label">Total Hrs</div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($is_holiday) { ?>
                                                <span class="badge ms-2 mt-2 px-3 py-2 fs-16 text-black">
                                                    <?= htmlspecialchars($holiday_title) ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-1 text-end pt-3 float-end fw-bold pe-5" style="margin-top:5px;">
                                    <i class="ri-eye-fill text-primary" data-bs-toggle="modal" onclick="showDetails('<?php echo $key['fulldate'] ?>','<?php echo $emp_id ?>');"></i>
                                </div>



                                <?php if (!empty($att_in_remark || $att_out_remark)) { ?>
                                    <div class="col-lg-12 ">
                                        <h6 class="pt-2 pb-2">
                                            <?php if (!empty($att_in_remark)) { ?>
                                                <b>Remark</b> : <?= htmlspecialchars($att_in_remark, ENT_QUOTES, 'UTF-8'); ?>
                                            <?php } ?>
                                            <br>
                                            <?php if (!empty($att_out_remark)) { ?>
                                                <b>Remark</b> : <?= htmlspecialchars($att_out_remark, ENT_QUOTES, 'UTF-8'); ?>
                                            <?php } ?>
                                        </h6>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
    <?php  }
            }
        }
    } ?>


    <?php if (!$has_valid_day) { ?>
        <div class="col-lg-12">
            <div class="alert alert-warning text-center rounded-4 shadow-sm">
                <i class="ri ri-information-line fs-4 me-2"></i>
                <strong>Punch In / Punch Out not allowed</strong><br>
                Attendance is not available for this month.
            </div>
        </div>
    <?php } ?>

</div>