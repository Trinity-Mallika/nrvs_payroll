<?php include("appsession.php");
$title = 'Attendence Details';
$pagename = 'attendance_details.php';
$emp_id = $_SESSION['emp_id'];
$currentYear = $_POST['currentYear'];
$currentMonth = $_POST['currentMonth'];
$date_as_new = $_POST['date_as_new'];
$doj = $_POST['doj'];
$current_date = date('Y-m-d');
$days_array = $obj->getDaysArray($currentMonth, $currentYear);
$has_valid_day = false;
foreach ($days_array as $key) {
    if ($key['fulldate'] > $current_date) {
        continue;
    }

    if ($key['fulldate'] < $doj) {
        continue;
    }

    $attendance_data = $obj->select_record('attendance_entry', ['emp_id' => $emp_id, 'attendance_date' => $key['fulldate'], 'month' => $currentMonth, 'year' => $currentYear]);
    $intime = $attendance_data['intime'] ?? '';
    $att_in_remark = $attendance_data['in_remark'] ?? '';
    $att_out_remark = $attendance_data['out_remark'] ?? '';
    $outtime = $attendance_data['outtime'] ?? '';
    $working_hours = $attendance_data['working_hours'] ?? '';
    $entry_type = $attendance_data['entry_type'] ?? '';
    $attendance_status = $attendance_data['attendance_status'] ?? '';

    $is_holiday = false;
    $holiday_title  = $obj->getvalfield("holiday_entry", "holiday_tittle", "FIND_IN_SET('$unitid', unit_id) and date='{$key['fulldate']}'");

    if (!empty($holiday_title)) {
        $is_holiday = true;
    }
    if ($is_holiday && empty($attendance_status)) {
        $attendance_status = 'Holiday';
    }
    if ($key['fulldate'] < $current_date &&  empty($intime) &&  empty($outtime) && empty($attendance_status)) {
        $attendance_status = 'Absent';
    }
    $cardClass = 'bg-light-blue border-card-blue';

    if ($attendance_status == 'Holiday') {
        $cardClass = 'bg-light-purple border-card-purple'; // ✅ NEW
    } elseif (
        $attendance_status == 'Weekly Leave' ||
        $attendance_status == 'Earning Leave' ||
        $attendance_status == 'C Off' ||
        $attendance_status == 'Half Weekly Leave' ||
        $attendance_status == 'Half Earning Leave' ||
        $attendance_status == 'Half C Off'
    ) {
        $cardClass = 'bg-light-yellow border-card-yellow';
    } elseif ($attendance_status == 'Absent') {
        $cardClass = 'bg-light-red border-card-red';
    }

    if ($key['fulldate'] <= $current_date) {
        $has_valid_day = true;
?>

        <div class="container">

            <div class="row mb-2">
                <div class="col-6">
                    <i class="bi bi-calendar-check text-blue"></i><small class="fw-bold text-blue"> <?php echo $key['day'] . " " . $key['month'] . " | " . $key['day_name'] ?> </small>
                </div>
                <div class="col-6 text-end">
                    <span class="fw-bold <?= ($attendance_status == 'Absent') ? 'text-danger ' : 'text-blue' ?>">
                        <?= $attendance_status ?>
                    </span>
                </div>
                <div class="col-12 mt-1">
                    <div class="card border-0 shadow-sm  p-2 mb-2  <?= $cardClass ?>">
                        <div class="row">
                            <div class="col-4 text-center">
                                <i class="bi bi-clock-history text-blue"></i>
                                <div class="fw-semibold">
                                    <?php if ($intime != '') { ?>
                                        <?php echo date("h:i:A", strtotime($intime)) ?>
                                    <?php } ?>
                                </div>
                                <small>Check In</small>
                            </div>
                            <div class="col-4 text-center">
                                <i class="bi bi-clock-history text-blue"></i>
                                <div class="fw-semibold">
                                    <?php if ($outtime != '') { ?>
                                        <?php echo date("h:i:A", strtotime($outtime)) ?>
                                    <?php } ?></div>
                                <small>Check Out</small>
                            </div>
                            <div class="col-4 text-center">
                                <i class="bi bi-clock-fill text-blue"></i>
                                <div class="fw-semibold"><?= $working_hours ?></div>
                                <small>Total Hrs</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
<?php }
} ?>
<?php if (!$has_valid_day) { ?>

    <div class="alert alert-warning text-center rounded-4 shadow-sm">
        <i class="ri ri-information-line fs-4 me-2"></i>
        Attendance is not available for this month.
    </div>

<?php } ?>