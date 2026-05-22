<?php
include("../adminsession.php");

$emp_id         = $_POST['emp_id'];
$currentYear    = $_POST['currentYear'];
$currentMonth   = $_POST['currentMonth'];
$punch_status   = $_POST['punch_status'];
$punch_remark   = $_POST['punch_remark'];
$punch_shift_id = $_POST['punch_shift_id'];

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


for ($day = 1; $day <= $endDay; $day++) {

    $attendance_date = date('Y-m-d', strtotime("$currentYear-$currentMonth-$day"));

    if ($punch_status == 'Absent') {
        $where = array(
            'emp_id' => $emp_id,
            'attendance_date'  => $attendance_date,
            'year'   => $currentYear,
            'month'   => $currentMonth,
            'unit_id'   => $unitid
        );

        $obj->delete_record('attendance_entry', $where);
        echo 3;
    } else {
        $count = $obj->getvalfield(
            "attendance_entry",
            "count(*)",
            "emp_id='$emp_id' AND attendance_date='$attendance_date'
         AND month='$currentMonth' AND year='$currentYear'"
        );

        /* ================= SHIFT TIME CALCULATION ================= */
        if ($count == 0) {
            $shiftStart = new DateTime("$attendance_date $office_in_time");
            $shiftEnd   = new DateTime("$attendance_date $office_out_time");

            $interval = $shiftStart->diff($shiftEnd);
            if ($shiftEnd <= $shiftStart) {
                $shiftEnd->modify('+1 day');
            }


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
                'unit_id' => $unitid,
                'sessionid' => $sessionid,
                'in_remark' => $punch_remark,
                'basic_salary' => $emp_salary
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
            }

            if ($punch_status == 'first_half') {
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
            }

            if ($punch_status == 'second_half') {
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
            }

            if ($punch_status == 'Leave') {
                $form_date['attendance_status'] = 'Leave';
            }

            /* ================= INSERT / UPDATE ================= */



            $obj->insert_record("attendance_entry", $form_date);
            echo 1;
        } else {
            $attendance_id = $obj->getvalfield(
                "attendance_entry",
                "attendance_id",
                "emp_id='$emp_id' AND attendance_date='$attendance_date'"
            );
            $form_date = array(
                'emp_id' => $emp_id,
                'attendance_date' => $attendance_date,
                'attendance_stamp' => $attendance_date . ' ' . $punchtime,
                'month' => $currentMonth,
                'year' => $currentYear,
                'in_remark' => $punch_remark,
                'shift_id' => $punch_shift_id,
                'entry_type' => 'manual',
                'entry_type_out' => 'manual',
                'unit_id' => $unitid,
                'sessionid' => $sessionid,
                'lastupdated' => date('Y-m-d'),
            );
            $shiftStart = new DateTime($attendance_date . ' ' . $office_in_time);
            $shiftEnd   = new DateTime($attendance_date . ' ' . $office_out_time);

            $interval = $shiftStart->diff($shiftEnd);
            if ($shiftEnd <= $shiftStart) {
                $shiftEnd->modify('+1 day');
            }


            $totalMinutes =
                ($interval->days * 24 * 60) +
                ($interval->h * 60) +
                $interval->i;

            $halfMinutes = $totalMinutes / 2;
            $firstHalfStart  = clone $shiftStart;
            $firstHalfEnd    = (clone $shiftStart)->modify("+{$halfMinutes} minutes");

            $secondHalfStart = clone $firstHalfEnd;
            $secondHalfEnd   = clone $shiftEnd;
            if ($punch_status == 'Present') {
                $form_date['intime'] = $shiftStart->format('H:i:s');
                $form_date['outtime'] = $shiftEnd->format('H:i:s');
                $form_date['attendance_status'] = 'Present';
                $form_date['attheadid'] = '1';
                // $form_data['working_hours'] =  $shift_wh;
                $form_date['working_hours'] = sprintf(
                    '%02d:%02d:%02d',
                    floor($totalMinutes / 60),
                    $totalMinutes % 60,
                    0
                );
            }
            if ($punch_status == 'first_half') {

                $form_date['intime'] = $firstHalfStart->format('H:i:s');
                $form_date['outtime'] = $firstHalfEnd->format('H:i:s');
                $form_date['attendance_status'] = 'Half Day';
                $form_date['attheadid'] = '3';
                //$form_data['working_hours'] = $obj->hoursToTime($shift_wh / 2);
                $form_date['working_hours'] = sprintf(
                    '%02d:%02d:%02d',
                    floor($halfMinutes / 60),
                    $halfMinutes % 60,
                    0
                );
            }
            if ($punch_status == 'second_half') {
                $form_date['intime'] = $secondHalfStart->format('H:i:s');
                $form_date['outtime'] = $secondHalfEnd->format('H:i:s');
                $form_date['attendance_status'] = 'Half Day';
                $form_date['attheadid'] = '3';
                // $form_data['working_hours'] = $obj->hoursToTime($shift_wh / 2);
                $form_date['working_hours'] = sprintf(
                    '%02d:%02d:%02d',
                    floor($halfMinutes / 60),
                    $halfMinutes % 60,
                    0
                );
            }
            if ($punch_status == 'Leave') {
                $form_date['attendance_status'] = 'Leave';
            }

            $where = ['attendance_id' => $attendance_id];
            $obj->update_record("attendance_entry", $where, $form_date);
            echo 2;
        }
    }
}



$punchtime = $_POST['punchtime'];
$emp_id = $_POST['emp_id'];
$attendance_date = $_POST['attdate'];
$currentYear = $_POST['currentYear'];
$currentMonth = $_POST['currentMonth'];
$punch_remark = $_POST['punch_remark'];
$punch_status = $_POST['punch_status'];
$punch_shift_id = $_POST['punch_shift_id'];
$current_time = date("H:i:s");

$total_days = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
$emp_salary =  $obj->getvalfield("employee_master", "basic_salary", "emp_id='$emp_id'");
$department_id =  $obj->getvalfield("employee_master", "department_id", "emp_id='$emp_id'");

$shift_data = $obj->select_record("shift_master", ['shift_id' => $punch_shift_id]);
$office_in_time = $shift_data['in_time'];
$office_out_time = $shift_data['out_time'];
$in_margin = $shift_data['grace_time_in'];
$out_margin = $shift_data['grace_time_out'];
$is_cross_day = $shift_data['is_cross_day'];


//$Office_working_hour = $obj->getvalfield("unit_master", "working_hours", "unit_id='$unit_id'");

if ($punch_status == 'Absent') {
    $where = array(
        'emp_id' => $emp_id,
        'attendance_date'  => $attendance_date,
        'year'   => $currentYear,
        'month'   => $currentMonth,
        'unit_id'   => $unitid
    );

    $obj->delete_record('attendance_entry', $where);
    echo 1;
} else {
    $count = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$currentYear' and month ='$currentMonth'");

    if ($count == 0) {
        $form_date = array(
            'emp_id' => $emp_id,
            'department_id' => $department_id,
            'attendance_date' => $attendance_date,
            'attendance_stamp' => $attendance_date . ' ' . $punchtime,
            'month' => $currentMonth,
            'year' => $currentYear,
            'createdate' => date('Y-m-d'),
            'createtime' => $current_time,
            'ipaddress' => $ipaddress,
            'basic_salary' => $emp_salary,
            'in_remark' => $punch_remark,
            'shift_id' => $punch_shift_id,
            'in_status' => 'IN',
            'out_status' => 'OUT',
            'entry_type' => 'manual',
            'unit_id' => $unitid,
            'sessionid' => $sessionid

        );

        $shiftStart = new DateTime($attendance_date . ' ' . $office_in_time);
        $shiftEnd   = new DateTime($attendance_date . ' ' . $office_out_time);

        $interval = $shiftStart->diff($shiftEnd);
        if ($shiftEnd <= $shiftStart) {
            $shiftEnd->modify('+1 day');
        }


        $totalMinutes =
            ($interval->days * 24 * 60) +
            ($interval->h * 60) +
            $interval->i;

        $halfMinutes = $totalMinutes / 2;
        $firstHalfStart  = clone $shiftStart;
        $firstHalfEnd    = (clone $shiftStart)->modify("+{$halfMinutes} minutes");

        $secondHalfStart = clone $firstHalfEnd;
        $secondHalfEnd   = clone $shiftEnd;
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
        } elseif ($punch_status == 'first_half') {

            $form_date['intime'] = $firstHalfStart->format('H:i:s');
            $form_date['outtime'] = $firstHalfEnd->format('H:i:s');
            $form_date['attendance_status'] = 'Half Day';
            $form_date['attheadid'] = '3';
            $form_date['working_hours'] = sprintf(
                '%02d:%02d:%02d',
                floor($halfMinutes / 60),
                $halfMinutes % 60,
                0
            );
        } elseif ($punch_status == 'second_half') {

            $form_date['intime'] = $secondHalfStart->format('H:i:s');
            $form_date['outtime'] = $secondHalfEnd->format('H:i:s');
            $form_date['attendance_status'] = 'Half Day';
            $form_date['attheadid'] = '3';
            $form_date['working_hours'] = sprintf(
                '%02d:%02d:%02d',
                floor($halfMinutes / 60),
                $halfMinutes % 60,
                0
            );
        } elseif ($punch_status == 'weekly_leave') {
            $form_date['attendance_status'] = 'Weekly Leave';
            $row = $obj->executequery("SELECT month_leave_id, remining_leave FROM emp_monthly_leave WHERE emp_id = '$emp_id' AND leave_type = 'weekly' AND remining_leave > 0 ORDER BY month_leave_id ASC, month DESC, month_leave_id DESC LIMIT 1");

            if (!empty($row)) {
                $row = $row[0];
                $newBalance = max(0, $row['remining_leave'] - 1);

                $obj->update_record(
                    "emp_monthly_leave",
                    ['month_leave_id' => $row['month_leave_id']],
                    ['remining_leave' => $newBalance]
                );
            }
        } elseif ($punch_status == 'earn_leave') {
            $form_date['attendance_status'] = 'Earning Leave';
            $row = $obj->executequery("SELECT month_leave_id, remining_leave FROM emp_monthly_leave WHERE emp_id = '$emp_id' AND leave_type = 'earning' AND remining_leave > 0 ORDER BY month_leave_id ASC, month DESC, month_leave_id DESC LIMIT 1");

            if (!empty($row)) {
                $row = $row[0];
                $newBalance = max(0, $row['remining_leave'] - 1);

                $obj->update_record(
                    "emp_monthly_leave",
                    ['month_leave_id' => $row['month_leave_id']],
                    ['remining_leave' => $newBalance]
                );
            }
        } // if ($punch_status == 'Leave') {
        //     $form_date['attendance_status'] = 'Leave';
        // }currentYear
        $obj->insert_record("attendance_entry", $form_date);
        echo 1;
    } else {

        $attendance_id = $obj->getvalfield("attendance_entry", "attendance_id", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$currentYear' and month ='$currentMonth' ");

        $form_date = array(
            'emp_id' => $emp_id,
            'attendance_date' => $attendance_date,
            'attendance_stamp' => $attendance_date . ' ' . $punchtime,
            'month' => $currentMonth,
            'year' => $currentYear,
            'in_remark' => $punch_remark,
            'shift_id' => $punch_shift_id,
            'entry_type' => 'manual',
            'entry_type_out' => 'manual',
            'unit_id' => $unitid,
            'sessionid' => $sessionid,
            'lastupdated' => date('Y-m-d'),
        );

        $shiftStart = new DateTime($attendance_date . ' ' . $office_in_time);
        $shiftEnd   = new DateTime($attendance_date . ' ' . $office_out_time);

        $interval = $shiftStart->diff($shiftEnd);
        if ($shiftEnd <= $shiftStart) {
            $shiftEnd->modify('+1 day');
        }


        $totalMinutes =
            ($interval->days * 24 * 60) +
            ($interval->h * 60) +
            $interval->i;

        $halfMinutes = $totalMinutes / 2;
        $firstHalfStart  = clone $shiftStart;
        $firstHalfEnd    = (clone $shiftStart)->modify("+{$halfMinutes} minutes");

        $secondHalfStart = clone $firstHalfEnd;
        $secondHalfEnd   = clone $shiftEnd;
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
        } elseif ($punch_status == 'first_half') {

            $form_date['intime'] = $firstHalfStart->format('H:i:s');
            $form_date['outtime'] = $firstHalfEnd->format('H:i:s');
            $form_date['attendance_status'] = 'Half Day';
            $form_date['attheadid'] = '3';
            $form_date['working_hours'] = sprintf(
                '%02d:%02d:%02d',
                floor($halfMinutes / 60),
                $halfMinutes % 60,
                0
            );
        } elseif ($punch_status == 'second_half') {

            $form_date['intime'] = $secondHalfStart->format('H:i:s');
            $form_date['outtime'] = $secondHalfEnd->format('H:i:s');
            $form_date['attendance_status'] = 'Half Day';
            $form_date['attheadid'] = '3';
            $form_date['working_hours'] = sprintf(
                '%02d:%02d:%02d',
                floor($halfMinutes / 60),
                $halfMinutes % 60,
                0
            );
        } elseif ($punch_status == 'weekly_leave') {
            $form_date['attendance_status'] = 'Weekly Leave';
            $row = $obj->executequery("SELECT month_leave_id, remining_leave FROM emp_monthly_leave WHERE emp_id = '$emp_id' AND leave_type = 'weekly' AND remining_leave > 0 ORDER BY month_leave_id ASC, month DESC, month_leave_id DESC LIMIT 1");

            if (!empty($row)) {
                $row = $row[0];
                $newBalance = max(0, $row['remining_leave'] - 1);

                $obj->update_record(
                    "emp_monthly_leave",
                    ['month_leave_id' => $row['month_leave_id']],
                    ['remining_leave' => $newBalance]
                );
            }
        } elseif ($punch_status == 'earn_leave') {
            $form_date['attendance_status'] = 'Earning Leave';
            $row = $obj->executequery("SELECT month_leave_id, remining_leave FROM emp_monthly_leave WHERE emp_id = '$emp_id' AND leave_type = 'earning' AND remining_leave > 0 ORDER BY month_leave_id ASC, month DESC, month_leave_id DESC LIMIT 1");

            if (!empty($row)) {
                $row = $row[0];
                $newBalance = max(0, $row['remining_leave'] - 1);

                $obj->update_record(
                    "emp_monthly_leave",
                    ['month_leave_id' => $row['month_leave_id']],
                    ['remining_leave' => $newBalance]
                );
            }
        } elseif ($punch_status == 'Leave') {
            $form_date['attendance_status'] = 'Leave';
        }

        $where = ['attendance_id' => $attendance_id];
        $obj->update_record("attendance_entry", $where, $form_date);
        echo 2;
    }
}
