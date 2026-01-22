<?php include("../adminsession.php");
$currentYear = $_POST['currentYear'];
$currentMonth = $_POST['currentMonth'];
$date_as_new = $_POST['date_as_new'];
$emp_id = $_POST['emp_id'];
$days_array = $obj->getDaysArray($currentMonth, $currentYear);
$doj = $obj->getvalfield("employee_master", "date_of_joining", "emp_id='$emp_id'");
$dateforas = date('Y-m-d', strtotime("$currentYear-$currentMonth")) ?? '';

$emp_shift_hrs =  $obj->getvalfield("employee_master", "shift_id", "emp_id='$emp_id'");

$unit_id =   $obj->getvalfield("employee_master", "unit_id", "emp_id='$emp_id'");

$salary_generate_count = $obj->getvalfield("salary_structure", "count(*)", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear'");

$total_present = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear' and attendance_status='Present'");

$total_half = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear' and attendance_status='Half Day'");

$total_att_leave = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear' and attendance_status='Leave'");

$total_attandence = $total_present + ($total_half / 2) + $total_att_leave;

$emp_shift_ids = $obj->getvalfield("attendance_entry", "shift_id", "emp_id='$emp_id' and month='$currentMonth' and year='$currentYear' order by attendance_id desc limit 1") ?? '';

// echo $salary_generate_count;
// die;


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
                    <a href="salary_generate_report.php?emp_id=<?= $emp_id ?>" class="btn btn-primary btn-sm">
                        <i class="ri-money-rupee-circle-line me-1"></i>
                        Redirect to Salary Report
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>

    <div class="row mt-4">
        <h5>Employee Wise Attendance List <a class="float-end btn btn-primary btn-sm">Total Attandence : <?= $total_attandence; ?></a> <a class="float-end btn btn-primary btn-sm mx-2" onclick="add_all_att('<?= $emp_shift_hrs; ?>','<?= $emp_shift_ids; ?>');">Add All Attendence</a></h5>

        <?php
        $has_valid_day = false;
        foreach ($days_array as $key) {
            $current_date = date('Y-m-d');
            $attendance_id = $obj->getvalfield("attendance_entry", "attendance_id", "emp_id='$emp_id' and attendance_date='$key[fulldate]'");

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

            $intime = $obj->getvalfield("attendance_entry", "intime", "emp_id='$emp_id' and attendance_date='$key[fulldate]' and month='$currentMonth' and year='$currentYear'");
            $att_in_remark = $obj->getvalfield("attendance_entry", "in_remark", "emp_id='$emp_id' and attendance_date='$key[fulldate]' and month='$currentMonth' and year='$currentYear'") ?? '';
            $emp_shift_id = $obj->getvalfield("attendance_entry", "shift_id", "emp_id='$emp_id' and attendance_date='$key[fulldate]' and month='$currentMonth' and year='$currentYear'") ?? $emp_shift_ids;

            $att_out_remark = $obj->getvalfield("attendance_entry", "out_remark", "emp_id='$emp_id' and attendance_date='$key[fulldate]' and month='$currentMonth' and year='$currentYear'") ?? '';

            $attendance_date = $obj->getvalfield("attendance_entry", "attendance_date", "emp_id='$emp_id'  and month='$currentMonth' and year='$currentYear'");

            $attendance_status = $obj->getvalfield("attendance_entry", "attendance_status", "emp_id='$emp_id'  and month='$currentMonth' and year='$currentYear' and attendance_id='$attendance_id'");

            $outtime = $obj->getvalfield("attendance_entry", "outtime", "emp_id='$emp_id' and attendance_date='$key[fulldate]' and month='$currentMonth' and year='$currentYear'");

            $attheadid = $obj->getvalfield("attendance_entry", "attheadid", "emp_id='$emp_id' and attendance_date='$key[fulldate]' and month='$currentMonth' and year='$currentYear'");

            $working_hours = $obj->getvalfield("attendance_entry", "working_hours", "emp_id='$emp_id' and attendance_date='$key[fulldate]' and month='$currentMonth' and year='$currentYear'");

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
                                    <div class="col-lg-2 text-center pt-3">
                                        <small class="fw-semibold fs-15">
                                            <?php echo $key['day'] . " " . $key['month'] . " | " . $key['day_name'] ?>
                                        </small>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="radio-inputs">
                                            <label class="radio rounded-3 mt-2" onclick="opentimepicker('intime','<?php echo $key['fulldate'] ?>','<?php echo  $intime_new  ?>','<?= $att_in_remark; ?>','<?= $emp_shift_hrs; ?>','<?= $emp_shift_id; ?>');">
                                                <?php
                                                if ($intime != '' &  $intime != '00:00:00') {
                                                ?>
                                                    <span class="name bg-primary" style="color: white;width:140px; height:30px;"><?php echo date("h:i:A", strtotime($intime)) ?></span>
                                                <?php } else { ?>
                                                    <span class="name bg-success pe-2 ps-2 radio rounded-3 text-white" style="color: white; width:140px; height:30px;">Punch In</span>
                                                <?php } ?>
                                            </label>
                                            <label class="radio rounded-3 mt-2 ms-2" <?php if ($readonly == 1) { ?> onclick="opentimepicker('outtime','<?php echo $key['fulldate'] ?>','<?php echo  $outtime_new; ?>','<?= $att_out_remark ?>','<?= $emp_shift_hrs; ?>','<?= $emp_shift_id; ?>');" <?php } ?> style="<?php echo $color ?>">

                                                <?php
                                                // echo $intime;
                                                if ($outtime != '') {
                                                ?>
                                                    <span class="name bg-primary" style="color: white; width:140px; height:30px;"><?php echo date("h:i:A", strtotime($outtime)) ?></span>
                                                <?php } else { ?>
                                                    <span class="name bg-danger pe-2 ps-2 radio rounded-3 text-white" style="<?php echo $readonly ?> color: white; width:140px; height:30px;">Punch Out</span>
                                                <?php } ?>
                                            </label>

                                            <!-- <?php //if ($intime != '' &  $intime != '00:00:00') { 
                                                    ?>
                                            <a href="#0" class="btn btn-sm rounded-circle mt-2 text-white bg-danger h-100 ms-2 mt-1" onclick="funDel('<?php echo $attendance_id ?>')"><i class="ri ri-delete-bin-2-fill fs-15"></i></i></a>
                                        <?php //} 
                                        ?> -->

                                            <?php if ($outtime != '' &&  $outtime != '00:00:00') { ?>
                                                <span class="badge <?= $badgeClass ?> text-success ms-2 mt-2 px-3 py-2 fs-16">
                                                    <?= $attendance_status ?>
                                                </span>

                                            <?php } ?>
                                            <?php if ($attendance_status == 'Leave') { ?>
                                                <span class="badge <?= $badgeClass ?> text-success ms-2 mt-2 px-3 py-2 fs-16">
                                                    <?= $attendance_status ?>
                                                </span>

                                            <?php } ?>

                                            <?php if ($is_holiday) { ?>
                                                <span class="badge ms-2 mt-2 px-3 py-2 fs-16 text-black">
                                                    <?= htmlspecialchars($holiday_title) ?>
                                                </span>
                                            <?php } ?>
                                        </div>

                                    </div>

                                    <div class="col-lg-3 text-end pt-3 float-end fw-bold pe-5" style="margin-top:5px;">
                                        <?php if (!empty($working_hours)) { ?>
                                            <?= $working_hours ?> HRS
                                        <?php } ?>
                                    </div>

                                    <div class="col-lg-1 text-end pt-2 float-end fw-bold  pe-5"><i class="ri ri-add-circle-fill fs-2 text-success-emphasis" data-bs-toggle="modal" onclick="openPunchModal('<?php echo $key['fulldate'] ?>','<?php echo  $intime_new  ?>','<?= $att_in_remark; ?>','<?= $emp_shift_hrs; ?>','<?= $emp_shift_id; ?>');"></i></div>
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