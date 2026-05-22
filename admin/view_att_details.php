<?php include("../adminsession.php");
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

$is_allow_c_off = $obj->getvalfield("department_master", "c_off_check", "department_id='$department_id'");
$is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$emp_data[unit_id]'");
$is_esic = $emp_data['is_esic'];

$setting_type = ($is_esic  == 1) ? 'ESIC' : 'Non ESIC';
$dateforas = date('Y-m-d', strtotime("$currentYear-$currentMonth")) ?? '';

$totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
$salary_generate_count = $obj->getvalfield("salary_structure", "count(*)", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear' AND unit_id='$unitid'");

$res = $obj->executequery("
    SELECT 
        SUM(CASE 
            WHEN attendance_status = 'Present' THEN 1 
            ELSE 0 
        END) AS total_present1,

        SUM(CASE 
            WHEN attendance_status = 'Half Day' THEN 1 
            ELSE 0 
        END) AS total_half1,

        SUM(CASE 
            WHEN attendance_status IN ('Present','Weekly Leave','Earning Leave','C Off','Extra Off','Leave') THEN 1 
            ELSE 0 
        END) AS total_present,

        SUM(CASE 
            WHEN attendance_status IN ('Half Day','Half Weekly Leave','Half Earning Leave','Half C Off','Half Extra Off','Half Leave') THEN 1 
            ELSE 0 
        END) AS total_half

    FROM attendance_entry
    WHERE emp_id = '$emp_id' 
    AND month = '$currentMonth' 
    AND year = '$currentYear' AND unit_id='$unitid'
");

$row = $res[0] ?? [];

$total_present1 = $row['total_present1'] ?? 0;
$total_half1    = $row['total_half1'] ?? 0;

$total_present  = $row['total_present'] ?? 0;
$total_half     = $row['total_half'] ?? 0;

$real_total_attandence = $total_present1 + ($total_half1 / 2);
$total_attandence      = $total_present + ($total_half / 2);


// echo $total_half;
// die;
// $total_att_leave = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear' and attendance_status='Leave'");


$emp_shift_ids = $obj->getvalfield("attendance_entry", "shift_id", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear' AND unit_id='$unitid' order by attendance_id desc limit 1") ?? '';

// echo $salary_generate_count;
// die;
$extra_off =$obj->getExtraOffBalance($emp_id, $currentMonth, $currentYear);
$opening_leave_balance =$obj->get_opening_leave_balance($emp_id, $sessionid);

// $three_month_leave = $obj->getLeave($emp_id, $currentMonth, $currentYear);
// echo $three_month_leave;
// die;
$chkedit = $obj->check_editBtn($pagename, $loginid);
$week_leave = $obj->totalWeeklyLeave($unitid, $real_total_attandence, $allow_weekly_off);
$earn_leave_present =  $real_total_attandence+$week_leave;
$monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $earn_leave_present, $unitid);
 
$total_earning_leave = $obj->getEarningLeave($emp_id, $sessionid);
$total_curr_week_leave = $obj->getCurrentWeekLeave($emp_id, $currentMonth, $currentYear);

$holidayData = $obj->getHolidayCountWithSandwichRule(
    $emp_id,
    $unitid,
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
//$total_payable_days += $three_month_leave;
if ($is_allow_c_off == 1) {
    $total_payable_days = min($total_payable_days, $totalDaysInMonth);
}

?>
<div class="row mt-4">
    <div class="col-lg-8 offset-lg-2">
        <div class=" card-body border-0 shadow-sm d-flex justify-content-between rounded-5 bg-white p-2">
            <a href="?prev&currentYear=<?php echo $currentYear ?>&currentMonth=<?php echo $currentMonth; ?>&emp_id=<?php echo $emp_id ?>"> <?php if ($doj <= $dateforas) { ?><i class="ri ri-arrow-left-s-fill fs-3"></i> <?php } ?></a>
            <span class="mt-2">
                <h6 class="mb-0">
                    <?php echo date('M', strtotime("$currentYear-$currentMonth")); ?>
                    <?php echo date('Y', strtotime("$currentYear-$currentMonth")); ?>
                </h6>
            </span>
            <a href="?next&currentYear=<?php echo $currentYear ?>&currentMonth=<?php echo $currentMonth; ?>&emp_id=<?php echo $emp_id ?>"><i class="ri ri-arrow-right-s-fill fs-3"></i></a>
        </div>
    </div>
</div>
<?php if ($salary_generate_count > 0) { ?>
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="alert alert-success text-center rounded-4 shadow-sm">
                <i class="ri ri-checkbox-circle-fill fs-4 me-2"></i>
                <strong>
                    Salary has already been generated for this month.
                    Please delete the generated salary first, then edit the attendance.
                </strong>

                <div class="mt-3">
                    <a href="salary_generate_report.php?emp_id=<?= $emp_id ?>&month=<?= $currentMonth ?>&year=<?= $currentYear ?>&submit=Search" class="btn btn-primary btn-sm">
                        <i class="ri-money-rupee-circle-line me-1"></i>
                        Redirect to Salary Report
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>

    <div class="row mt-4">


        <div class="row mt-4 align-items-center">
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
            <?php if ($chkedit == 1) { ?>
                <div class="col-md-2 text-end">
                    <a class="float-end btn btn-primary btn-sm mx-2" onclick="add_all_att('<?= $emp_shift_hrs; ?>','<?= $emp_shift_ids; ?>','<?= $extra_off['balance']; ?>','<?= $opening_leave_balance; ?>','<?= $total_earning_leave ?>');">Add All Attendence</a>
                </div>
            <?php } ?>
        </div>

        <h6 class="bg-body-secondary border-bottom-outset fs-15 mt-3 p-3 rounded-2 shadow-lg d-flex justify-content-between align-items-center"
            style="background: linear-gradient(90deg, #edf2b7, #ffffff); border-color:#7696ff;">

            <span>Extra Off : <?= $extra_off['balance'] ?></span>

            <span>Opening Leave Balance : <?= $opening_leave_balance ?></span>

            <span>Pending Earn leave : <?= $total_earning_leave ?></span>

        </h6>

        <?php
        $has_valid_day = false;
        foreach ($days_array as $key) {
            $current_date = date('Y-m-d');
            $attendance_id = $obj->getvalfield("attendance_entry", "attendance_id", "emp_id='$emp_id' and attendance_date='$key[fulldate]' AND unit_id='$unitid'");

            $is_holiday = false;
            $holiday_title  = $obj->getvalfield("holiday_entry", "holiday_tittle", "FIND_IN_SET('$unitid', unit_id) and date='{$key['fulldate']}'");

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

            $attendance_data = $obj->select_record('attendance_entry', ['emp_id' => $emp_id, 'attendance_date' => $key['fulldate'], 'month' => $currentMonth, 'year' => $currentYear, 'unit_id' => $unitid]);
            $intime = $attendance_data['intime'] ?? '';
            $emp_shift_id = $attendance_data['shift_id'] ?? $emp_shift_ids;
            $att_in_remark = $attendance_data['in_remark'] ?? '';
            $att_out_remark = $attendance_data['out_remark'] ?? '';
            $outtime = $attendance_data['outtime'] ?? '';
            $attheadid = $attendance_data['attheadid'] ?? '';
            $working_hours = $attendance_data['working_hours'] ?? '';
            $entry_type = $attendance_data['entry_type'] ?? '';

            $attendance_date = $obj->getvalfield("attendance_entry", "attendance_date", "emp_id='$emp_id'  and month='$currentMonth' and year='$currentYear' AND unit_id='$unitid'");

            $attendance_status = $obj->getvalfield("attendance_entry", "attendance_status", "emp_id='$emp_id'  and month='$currentMonth' and year='$currentYear' and attendance_id='$attendance_id' AND unit_id='$unitid'");

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
                            <div class="card card-body border rounded-5 p-0 mb-1" style="<?= $holiday_bg ?>">
                                <span class="water-mark"></span>
                                <div class="row">
                                    <div class="col-lg-2 text-center pt-3">
                                        <small class="fw-semibold fs-15">
                                            <?php echo $key['day'] . " " . $key['month'] . " | " . $key['day_name'] ?>
                                        </small>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="radio-inputs">
                                            <?php if (
                                                $attendance_status != 'Weekly Leave' &&
                                                $attendance_status != 'Earning Leave' &&
                                                $attendance_status != 'Half Earning Leave' &&
                                                $attendance_status != 'Half Weekly Leave' &&
                                                $attendance_status != 'Half C Off' &&
                                                $attendance_status != 'C Off' &&
                                                $attendance_status != 'Half Extra Off' &&
                                                $attendance_status != 'Extra Off' &&
                                                $attendance_status != 'Half Leave' &&
                                                $attendance_status != 'Leave'  
                                            ) {
                                            ?>
                                                <label class="radio rounded-3 mt-2"
                                                    <?php if ($chkedit == 1) { ?>
                                                    onclick="opentimepicker('intime','<?php echo $key['fulldate'] ?>','<?php echo  $intime_new  ?>','<?= $att_in_remark; ?>','<?= $emp_shift_hrs; ?>','<?= $emp_shift_id; ?>');"
                                                    <?php } ?>>
                                                    <?php
                                                    if ($intime != '' &  $intime != '00:00:00') {
                                                    ?>
                                                        <span class="name bg-primary" style="color: white;width:140px; height:30px;"><?php echo date("h:i:A", strtotime($intime)) ?></span>
                                                    <?php } else { ?>
                                                        <span class="name bg-success pe-2 ps-2 radio rounded-3 text-white" style="color: white; width:140px; height:30px;">Punch In</span>
                                                    <?php } ?>
                                                </label>
                                                <label class="radio rounded-3 mt-2 ms-2" <?php if ($chkedit == 1 && $readonly == 1) { ?> onclick="opentimepicker('outtime','<?php echo $key['fulldate'] ?>','<?php echo  $outtime_new; ?>','<?= $att_out_remark ?>','<?= $emp_shift_hrs; ?>','<?= $emp_shift_id; ?>');" <?php } ?> style="<?php echo $color ?>">

                                                    <?php
                                                    // echo $intime;
                                                    if ($outtime != '') {
                                                    ?>
                                                        <span class="name bg-primary" style="color: white; width:140px; height:30px;"><?php echo date("h:i:A", strtotime($outtime)) ?></span>
                                                    <?php } else { ?>
                                                        <span class="name bg-danger pe-2 ps-2 radio rounded-3 text-white" style="<?php echo $readonly ?> color: white; width:140px; height:30px;">Punch Out</span>
                                                    <?php } ?>
                                                </label>

                                            <?php } else { ?>
                                                <span class="badge <?= $badgeClass ?> text-success ms-2 mt-2 px-3 py-2 fs-16">
                                                    <?= $attendance_status ?>
                                                </span>
                                            <?php } ?>

                                            <?php if ($outtime != '' &&  $outtime != '00:00:00') { ?>
                                                <span class="badge <?= $badgeClass ?> text-success ms-2 mt-2 px-3 py-2 fs-16">
                                                    <?= $attendance_status ?> (Entry Type : <?= $entry_type ?>)
                                                </span>

                                            <?php }   ?>

                                            <?php if ($is_holiday) { ?>
                                                <span class="badge ms-2 mt-2 px-3 py-2 fs-16 text-black">
                                                    <?= htmlspecialchars($holiday_title) ?>
                                                </span>
                                            <?php } ?>
                                        </div>

                                    </div>

                                    <div class="col-lg-2 text-end pt-3 float-end fw-bold pe-5" style="margin-top:5px;">
                                        <?php if (!empty($working_hours)) { ?>
                                            <?= $working_hours ?> HRS
                                        <?php } ?>
                                    </div>
                                    <div class="col-lg-1 text-end pt-3 float-end fw-bold pe-5" style="margin-top:5px;">
                                        <i class="ri-eye-fill text-primary" data-bs-toggle="modal" onclick="showDetails('<?php echo $key['fulldate'] ?>','<?php echo $emp_id ?>');"></i>
                                    </div>

                                    <?php
                                    if ($chkedit == 1) {  ?>
                                        <div class="col-lg-1 text-end pt-2 float-end fw-bold  pe-5"><i class="ri ri-add-circle-fill fs-2 text-success-emphasis" data-bs-toggle="modal" onclick="openPunchModal('<?php echo $key['fulldate'] ?>','<?php echo  $intime_new  ?>','<?= $att_in_remark; ?>','<?= $emp_shift_hrs; ?>','<?= $emp_shift_id; ?>','<?= $extra_off['balance']; ?>','<?= $opening_leave_balance; ?>','<?= $total_earning_leave ?>');"></i></div>
                                    <?php } ?>

                                    <?php if (!empty($att_in_remark || $att_out_remark)) { ?>
                                        <div class="col-lg-12 ms-4 border-top">
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
<?php } ?>