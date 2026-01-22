<?php if ($emp_id > 0) { ?>
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

    <div class="row mt-4">
        <h5>Employee Wise Attendance List</h5>

        <?php foreach ($days_array as $key) {
            $current_date = date('Y-m-d');
            $attendance_id = $obj->getvalfield("attendance_entry", "attendance_id", "emp_id='$emp_id' and attendance_date='$key[fulldate]'");

            $emp_name = $obj->getvalfield("employee_master", "first_name", "emp_id='$emp_id'");

            $is_holiday = false;
            $holiday_title  = $obj->getvalfield("holiday_entry", "holiday_tittle", "unit_id='$unit_id' and date='{$key['fulldate']}'");

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
        ?>

            <div class="col-lg-12 mt-1">
                <div class="card card-body border rounded-5 p-0" style="<?= $holiday_bg ?>">
                    <span class="water-mark"></span>
                    <?php
                    $intime = $obj->getvalfield("attendance_entry", "intime", "emp_id='$emp_id' and attendance_date='$key[fulldate]' and month='$currentMonth' and year='$currentYear'");

                    $attendance_date = $obj->getvalfield("attendance_entry", "attendance_date", "emp_id='$emp_id'  and month='$currentMonth' and year='$currentYear'");

                    $outtime = $obj->getvalfield("attendance_entry", "outtime", "emp_id='$emp_id' and attendance_date='$key[fulldate]' and month='$currentMonth' and year='$currentYear'");

                    $attheadid = $obj->getvalfield("attendance_entry", "attheadid", "emp_id='$emp_id' and attendance_date='$key[fulldate]' and month='$currentMonth' and year='$currentYear'");

                    $working_hours = $obj->getvalfield("attendance_entry", "working_hours", "emp_id='$emp_id' and attendance_date='$key[fulldate]' and month='$currentMonth' and year='$currentYear'");

                    if ($intime != '' &&  $intime != '00:00:00') {
                        $readonly = "1";
                        $color = "";
                    } else {
                        $readonly = "0";
                        $color = "background: aliceblue;";
                    }

                    if ($doj <= $key['fulldate']) {

                        if ($intime != '' &  $intime != '00:00:00') {
                            $intime_new = $intime;
                        } else {
                            $intime_new = $shift_in_time;
                        }
                        if ($outtime != '' &  $outtime != '00:00:00') {
                            $outtime_new = $outtime;
                        } else {
                            $outtime_new = $shift_out_time;
                        }
                        if ($date_as_new == "" || $date_as_new == $key['fulldate']) {

                            if ($key['fulldate'] <= $current_date) {
                    ?>

                                <div class="row">
                                    <div class="col-lg-2 text-center pt-3">
                                        <small class="fw-semibold fs-15">
                                            <?php echo $key['day'] . " " . $key['month'] . " | " . $key['day_name'] ?>
                                        </small>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="radio-inputs">
                                            <label class="radio border rounded-3 ms-2" onclick="opentimepicker('intime','<?php echo $key['fulldate'] ?>','<?php echo  $intime_new  ?>');">
                                                <?php
                                                if ($intime != '' &  $intime != '00:00:00') {
                                                ?>
                                                    <span class="name bg-success" style="color: white;"><?php echo date("h:i:A", strtotime($intime)) ?></span>
                                                <?php } else { ?>
                                                    <span class="name">Punch In</span>
                                                <?php } ?>
                                            </label>
                                            <label class="radio border rounded-3 ms-2" <?php if ($readonly == 1) { ?> onclick="opentimepicker('outtime','<?php echo $key['fulldate'] ?>','<?php echo  $outtime_new  ?>');" <?php } ?> style="<?php echo $color ?>">

                                                <?php
                                                // echo $intime;
                                                if ($outtime != '' && $outtime != '00:00:00') {
                                                ?>
                                                    <span class="name bg-success" style="color: white;"><?php echo date("h:i:A", strtotime($outtime)) ?></span>
                                                <?php } else { ?>
                                                    <span class="name" style="<?php echo $readonly ?>">Punch Out</span>
                                                <?php } ?>
                                            </label>

                                            <?php if ($intime != '' &  $intime != '00:00:00') { ?>
                                                <a href="#0" class="btn btn-sm rounded-circle mt-2 text-white bg-danger h-100 ms-2 mt-1" onclick="funDel('<?php echo $attendance_id ?>')"><i class="ri ri-delete-bin-2-fill fs-15"></i></i></a>

                                            <?php } ?>
                                            <?php if ($is_holiday) { ?>
                                                <span class="badge text-success ms-2 mt-2 px-3 py-2 fs-16">
                                                    <?= htmlspecialchars($holiday_title) ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php if (!empty($working_hours)) { ?>
                                        <div class="col-lg-4 text-end pt-2 float-end fw-bold pt-4 pe-5"><?= $working_hours ?> HRS</div>
                                    <?php } ?>
                                </div>
                    <?php }
                        }
                    } ?>

                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>