<style>
.attendance-card{
    border-radius:16px;
    padding:14px 16px;
    margin-bottom:12px;
    box-shadow:2px 2px 10px rgba(0,0,0,0.06);
    border:1px solid #e5e7eb;
    background:#fff;
}

.attendance-date{
    font-size:14px;
    font-weight:700;
    color:#124671;
}

.status-badge{
    padding:7px 14px;
    border-radius:30px;
    font-size:12px;
    font-weight:700;
    display:inline-block;
    min-width:110px;
    text-align:center;
}

.status-present{
    background:#dcfce7;
    color:#166534;
    border:1px solid #86efac;
}

.status-absent{
    background:#fee2e2;
    color:#b91c1c;
    border:1px solid #fca5a5;
}

.status-incom{
    background:#fee2e2;
    color:#b91c1c;
    border:1px solid #fb6f6f;
}

.status-leave{
    background:#fef3c7;
    color:#92400e;
    border:1px solid #fcd34d;
}

.status-holiday{
    background:#ede9fe;
    color:#6d28d9;
    border:1px solid #c4b5fd;
}

.status-half{
    background:#dbeafe;
    color:#1d4ed8;
    border:1px solid #93c5fd;
}

.attendance-icon{
    width:45px;
    height:45px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#eef4ff;
    color:#124671;
    font-size:20px;
}

.attendance-main{
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.attendance-left{
    display:flex;
    align-items:center;
    gap:12px;
}

.attendance-day{
    font-size:12px;
    color:#6b7280;
    font-weight:500;
}

.legend-box{
    width:14px;
    height:14px;
    border-radius:4px;
    display:inline-block;
    margin-right:5px;
}

.legend-wrap{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:15px;
    padding:12px;
    border-radius:12px;
    background:#fff;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}
</style>

<?php include("appsession.php");

$title = 'Attendence Details';
$pagename = 'attendance_details.php';

$emp_id = $_SESSION['emp_id'];

$currentYear  = $_POST['currentYear'];
$currentMonth = $_POST['currentMonth'];
$date_as_new  = $_POST['date_as_new'];
$doj          = $_POST['doj'];

$current_date = date('Y-m-d');

$days_array = $obj->getDaysArray($currentMonth, $currentYear);

$has_valid_day = false;
?>

<!-- STATUS LEGEND -->

<div class="legend-wrap">

    <div>
        <span class="legend-box status-present"></span>
        <small>Present</small>
    </div>

    <div>
        <span class="legend-box status-incom"></span>
        <small>Incomplete</small>
    </div>

    <div>
        <span class="legend-box status-absent"></span>
        <small>Absent</small>
    </div>

    <div>
        <span class="legend-box status-leave"></span>
        <small>Leave</small>
    </div>

    <div>
        <span class="legend-box status-holiday"></span>
        <small>Holiday</small>
    </div>

    <div>
        <span class="legend-box status-half"></span>
        <small>Half Day</small>
    </div>

</div>

<?php

foreach ($days_array as $key) {

    if ($key['fulldate'] > $current_date) {
        continue;
    }

    if ($key['fulldate'] < $doj) {
        continue;
    }

    $attendance_data = $obj->select_record(
        'attendance_entry',
        [
            'emp_id' => $emp_id,
            'attendance_date' => $key['fulldate'],
            'month' => $currentMonth,
            'year' => $currentYear
        ]
    );

    $attendance_status = $attendance_data['attendance_status'] ?? '';

    /* ================= HOLIDAY ================= */

    $holiday_title = $obj->getvalfield(
        "holiday_entry",
        "holiday_tittle",
        "FIND_IN_SET('$unitid', unit_id)
        AND date='{$key['fulldate']}'"
    );

    if (!empty($holiday_title) && empty($attendance_status)) {
        $attendance_status = 'Holiday';
    }

    /* ================= ABSENT ================= */

    if (
        $key['fulldate'] < $current_date
        && empty($attendance_status)
    ) {
        $attendance_status = 'Absent';
    }

    /* ================= STATUS CLASS ================= */

    $statusClass = 'status-present';

    if ($attendance_status == 'Present') {

        $statusClass = 'status-present';

    } elseif ($attendance_status == 'Absent') {

        $statusClass = 'status-absent';

    } elseif ($attendance_status == 'Incomplete') {

        $statusClass = 'status-incom';

    } elseif (
        $attendance_status == 'Weekly Leave' ||
        $attendance_status == 'Earning Leave' ||
        $attendance_status == 'C Off' ||
        $attendance_status == 'Half Weekly Leave' ||
        $attendance_status == 'Half Earning Leave' ||
        $attendance_status == 'Half C Off'
    ) {

        $statusClass = 'status-leave';

    } elseif ($attendance_status == 'Holiday') {

        $statusClass = 'status-holiday';

    } elseif ($attendance_status == 'Half Day') {

        $statusClass = 'status-half';
    }

    if ($key['fulldate'] <= $current_date) {

        $has_valid_day = true;
?>

        <div class="attendance-card">

            <div class="attendance-main">

                <div class="attendance-left">
                    <div class="attendance-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <div class="attendance-date">
                            <?= date("d M Y", strtotime($key['fulldate'])) ?>
                        </div>
                        <div class="attendance-day">
                            <?= $key['day_name'] ?>
                        </div>
                    </div>
                </div>
                <?php if(!empty($attendance_status)){ ?>
                    <div>
                        <span class="status-badge <?= $statusClass ?>">
                            <?= $attendance_status ?>
                        </span>
                    </div>
                <?php } ?>
            </div>
        </div>
<?php
    }
}
?>

<?php if (!$has_valid_day) { ?>

    <div class="alert alert-warning text-center rounded-4 shadow-sm mt-3">
        <i class="ri ri-information-line fs-4 me-2"></i>
        Attendance is not available for this month.
    </div>

<?php } ?>