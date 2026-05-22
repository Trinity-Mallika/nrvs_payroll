<?php
include("../adminsession.php");

$emp_id         = $_POST['emp_id'];
$currentYear    = $_POST['currentYear'];
$currentMonth   = $_POST['currentMonth'];
$punch_status   = $_POST['punch_status'];
$punch_remark   = $_POST['punch_remark'];
$punch_shift_id = $_POST['punch_shift_id'];
$punch_all_type = $_POST['punch_all_type'];
$current_time = date('H:i:s');

// $prevDate = date('Y-m', timestamp: strtotime("$currentYear-$currentMonth-01 -1 month"));

// $prevYear  = date('Y', strtotime($prevDate));
// $prevMonth = date('n', strtotime($prevDate));

$total_days = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

// Default → full month
$endDay = $total_days;
if ($currentYear == date('Y') && $currentMonth == date('m')) {
    $endDay = date('d'); // today date (15)
}

$shift_data = $obj->select_record("shift_master", ['shift_id' => $punch_shift_id]);
$office_in_time = $shift_data['in_time'];
$office_out_time = $shift_data['out_time'];
$is_cross_day = $shift_data['is_cross_day'];
$shift_wh = $shift_data['working_hour'];


$department_id = $obj->getvalfield("employee_master", "department_id", "emp_id='$emp_id'");
$emp_salary    = $obj->getvalfield("employee_master", "basic_salary", "emp_id='$emp_id'");

$where1 = array(
    'emp_id' => $emp_id,
    'year'   => $currentYear,
    'month'   => $currentMonth,
    'unit_id'   => $unitid
);

$obj->delete_record('attendance_entry', $where1);
//$obj->delete_record('attendance_log', $where1);

$leave_data = array(
    "emp_id" => $emp_id,
    "department_id" => $department_id,
    "month" => (int)$currentMonth,
    "year" => (int)$currentYear,
    "basic_salary" => $emp_salary,
    "unit_id" => $unitid,
    "createdby" => $loginid,
    "ipaddress" => $ipaddress,
    "sessionid" => $sessionid,
    "createdate" => date("Y-m-d H:i:s")
);

$holidayRows = $obj->executequery("SELECT date FROM holiday_entry WHERE is_deleted = 0 AND FIND_IN_SET('$unitid', unit_id) AND YEAR(date) = '$currentYear' AND MONTH(date) = '$currentMonth'");
$holidayDates = [];
foreach ($holidayRows as $h) {
    $holidayDates[] = $h['date'];
}

for ($day = 1; $day <= $endDay; $day++) {
    $attendance_date = date('Y-m-d', strtotime("$currentYear-$currentMonth-$day"));

    if ($punch_all_type == 0) {
        $dayName = date('l', strtotime($attendance_date));
        $isHoliday = in_array($attendance_date, $holidayDates);

        if ($dayName == 'Sunday' || $isHoliday) {
            continue;
        }
    }

    if ($punch_status == 'Absent') {
        $where = array(
            'emp_id' => $emp_id,
            'attendance_date'  => $attendance_date,
            'year'   => $currentYear,
            'month'   => $currentMonth,
            'unit_id'   => $unitid
        );
        $obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $attendance_date]);
        $obj->delete_record('attendance_entry', $where);
       // $obj->delete_record('attendance_log', $where);
        echo 3;
    } else {
        $shiftStart = new DateTime("$attendance_date $office_in_time");
        $shiftEnd   = new DateTime("$attendance_date $office_out_time");


        if ($shiftEnd <= $shiftStart) {
            $shiftEnd->modify('+1 day');
        }
        $interval = $shiftStart->diff($shiftEnd);

        $totalMinutes =
            ($interval->days * 24 * 60) +
            ($interval->h * 60) +
            $interval->i;

        $halfMinutes = $totalMinutes / 2;
        $firstHalfStart  = clone $shiftStart;
        $firstHalfEnd    = (clone $shiftStart)->modify("+{$halfMinutes} minutes");

        $secondHalfStart = clone $firstHalfEnd;
        $secondHalfEnd   = clone $shiftEnd;
        /* ================= COMMON DATA ================= */

        $form_date = [
            'emp_id' => $emp_id,
            'department_id' => $department_id,
            'attendance_date' => $attendance_date,
            'attendance_stamp' => $attendance_date . ' ' . $office_in_time,
            'month' => $currentMonth,
            'year' => $currentYear,
            'shift_id' => $punch_shift_id,
            'entry_type' => 'manual',
            'entry_type_out' => 'manual',
            'in_status' => 'IN',
            'out_status' => 'OUT',
            'unit_id' => $unitid,
            'sessionid' => $sessionid,
            'in_remark' => $punch_remark,
            'basic_salary' => $emp_salary,
            'createdate' => date('Y-m-d'),
            'createtime' => $current_time,
            'createdby' => $loginid,
            'updateby' => $loginid,
            'lastupdated' => date('Y-m-d'),
            'ipaddress' => $ipaddress,
        ];

        /* ================= STATUS WISE LOGIC ================= */

        if ($punch_status == 'Present') {
            $form_date['intime'] = $shiftStart->format('H:i:s');
            $form_date['outtime'] = $shiftEnd->format('H:i:s');
            $form_date['attendance_status'] = 'Present';
            $form_date['attheadid'] = '1';

            $form_date['working_hours'] = sprintf(
                '%02d:%02d:%02d',
                floor($totalMinutes / 60),
                $totalMinutes % 60,
                0
            );
            $obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $attendance_date]);
        } elseif ($punch_status == 'first_half') {
            $form_date['intime'] = $firstHalfStart->format('H:i:s');
            $form_date['outtime'] = $firstHalfEnd->format('H:i:s');
            $form_date['attendance_status'] = 'Half Day';
            $form_date['attheadid'] = '3';
            // $form_data['working_hours'] = $obj->hoursToTime($shift_wh / 2);
            $form_date['working_hours'] = sprintf(
                '%02d:%02d:%02d',
                floor($halfMinutes / 60),
                $halfMinutes % 60,
                0
            );
            $obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $attendance_date]);
        } elseif ($punch_status == 'second_half') {
            $form_date['intime'] = $secondHalfStart->format('H:i:s');
            $form_date['outtime'] = $secondHalfEnd->format('H:i:s');
            $form_date['attendance_status'] = 'Half Day';
            $form_date['attheadid'] = '3';
            //$form_data['working_hours'] = $obj->hoursToTime($shift_wh / 2);
            $form_date['working_hours'] = sprintf(
                '%02d:%02d:%02d',
                floor($halfMinutes / 60),
                $halfMinutes % 60,
                0
            );
            $obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $attendance_date]);
        } elseif ($punch_status == 'weekly_leave') {
            $form_date['attendance_status'] = 'Weekly Leave';
            $obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $attendance_date]);

            $leave_data['total_leave'] = '-1';
            $leave_data['remining_leave'] = '-1';
            $leave_data['leave_type'] = 'weekly';
            $leave_data['leave_date'] = $attendance_date;
            $obj->insert_record("emp_monthly_leave", $leave_data);
        } elseif ($punch_status == 'earn_leave') {
            $obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $attendance_date]);
            $form_date['attendance_status'] = 'Earning Leave';
            $leave_data['total_leave'] = '-1';
            $leave_data['remining_leave'] = '-1';
            $leave_data['leave_type'] = 'earning';
            $leave_data['leave_date'] = $attendance_date;
            $obj->insert_record("emp_monthly_leave", $leave_data);
        } elseif ($punch_status == 'half_weekly_leave') {
            $obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $attendance_date]);

            $form_date['attendance_status'] = 'Half Weekly Leave';
            $leave_data['total_leave'] = '-0.5';
            $leave_data['remining_leave'] = '-0.5';
            $leave_data['leave_type'] = 'weekly';
            $leave_data['leave_date'] = $attendance_date;
            $obj->insert_record("emp_monthly_leave", $leave_data);
        } elseif ($punch_status == 'half_earn_leave') {
            $obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $attendance_date]);

            $form_date['attendance_status'] = 'Half Earning Leave';
            $leave_data['total_leave'] = '-0.5';
            $leave_data['remining_leave'] = '-0.5';
            $leave_data['leave_type'] = 'earning';
            $leave_data['leave_date'] = $attendance_date;
            $obj->insert_record("emp_monthly_leave", $leave_data);
        } elseif ($punch_status == 'c_off') {
            $obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $attendance_date]);
            $form_date['attendance_status'] = 'C Off';
        } elseif ($punch_status == 'half_c_off') {
            $obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $attendance_date]);
            $form_date['attendance_status'] = 'Half C Off';
        }

        $lastid = $obj->insert_record_lastid("attendance_entry", $form_date);

        $form_data1 = array(
            "primary_id" => $lastid,
            "flag" => 'Punch IN and OUT Attendence',
            "activity_type" => 'Attendence IN/OUT',
            "createdby" => $loginid,
            "pagename" => 'employee_wise_attendance.php',
            "created_date" => $createdate,
            "created_time" => date('H:i:s'),
            "unit_id" => $unitid,
            'ipaddress' => $ipaddress,
            "sessionid" => $sessionid
        );
        $logactivity = $obj->insert_record("logactivity_master", $form_data1);
        echo 1;
    }
}
