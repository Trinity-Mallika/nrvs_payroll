<?php include("../adminsession.php");

$punchtime = $_POST['punchtime'];
$emp_id = $_POST['emp_id'];
$attendance_date = $_POST['attdate'];
$currentYear = $_POST['currentYear'];
$currentMonth = $_POST['currentMonth'];
$punch_remark = $_POST['punch_remark'];
$punch_status = $_POST['punch_status'];
$punch_shift_id = $_POST['punch_shift_id'];
$punch_txt = $_POST['punch_txt']??'';
$current_time = date("H:i:s");

$prevDate = date('Y-m', strtotime("$currentYear-$currentMonth-01 -1 month"));

$prevYear  = date('Y', strtotime($prevDate));
$prevMonth = date('n', strtotime($prevDate));

$total_days = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

$emp_data = $obj->select_record("employee_master", ['emp_id' => $emp_id]);
$department_id =$emp_data['department_id'] ??'';
$emp_salary =$emp_data['basic_salary'] ??'';
$date_of_joining =$emp_data['date_of_joining'] ??''; 

if (!empty($date_of_joining) && strtotime($attendance_date) < strtotime($date_of_joining)) {
    echo "joining_date_error";
    exit;
}

$shift_data = $obj->select_record("shift_master", ['shift_id' => $punch_shift_id]);
$office_in_time = $shift_data['in_time']??'';
$office_out_time = $shift_data['out_time']??'';
$in_margin = $shift_data['grace_time_in']??'';
$out_margin = $shift_data['grace_time_out']??'';
$is_cross_day = $shift_data['is_cross_day']??'';


//$Office_working_hour = $obj->getvalfield("unit_master", "working_hours", "unit_id='$unit_id'");
$leave_data = array(
    "emp_id" => $emp_id,
    "department_id" => $department_id,
    "month" => (int)$currentMonth,
    "year" =>  (int)$currentYear,
    "basic_salary" => $emp_salary,
    "unit_id" => $unitid,
    "createdby" => $loginid,
    "ipaddress" => $ipaddress,
    "sessionid" => $sessionid,
    "createdate" => date("Y-m-d H:i:s")
);

if ($punch_status == 'Absent') {
    $attendance_id = $obj->getvalfield("attendance_entry", "attendance_id", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$currentYear' and month ='$currentMonth' ");

    //$previous_att_status = $obj->getvalfield("attendance_entry", "attendance_status", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$currentYear' and month ='$currentMonth'");
    $where = array(
        'emp_id' => $emp_id,
        'attendance_date'  => $attendance_date,
        'year'   => $currentYear,
        'month'   => $currentMonth,
        'unit_id'   => $unitid
    );

    $obj->delete_record('attendance_entry', $where);

    if($punch_txt=='M'){
        $form_date_ab = array(
            'emp_id' => $emp_id,
            'department_id' => $department_id,
            'attendance_date' => $attendance_date,
            'attendance_stamp' => $attendance_date . ' ' . $punchtime,
            'month' => $currentMonth,
            'year' => $currentYear,
            'attendance_status' => 'Absent',
            'createdate' => date('Y-m-d'),
            'createtime' => $current_time,
            'ipaddress' => $ipaddress,
            'basic_salary' => $emp_salary,  
            'in_status' => 'IN',
            'out_status' => 'OUT',
            'entry_type' => 'manual',
            'entry_type_out' => 'manual',
            'unit_id' => $unitid,
            'updateby' => $loginid,
            'lastupdated' => date('Y-m-d'),
            'createdby' => $loginid,
            'sessionid' => $sessionid
        );

        $lastid = $obj->insert_record_lastid("attendance_entry", $form_date_ab);
        $form_data1 = array(
            "primary_id" => $lastid,
            "flag" => 'Punch IN Attendence',
            "activity_type" => 'Attendence IN',
            "createdby" => $loginid,
            "pagename" => 'Month_wise_attendence_report.php',
            "created_date" => $createdate,
            "created_time" => date('H:i:s'),
            "unit_id" => $unitid,
            'ipaddress' => $ipaddress,
            "sessionid" => $sessionid
        );
        $logactivity = $obj->insert_record("logactivity_master", $form_data1);
    }

    //$obj->delete_record('attendance_log', $where);

    //$obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $attendance_date]);
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
            'entry_type_out' => 'manual',
            'unit_id' => $unitid,
            'updateby' => $loginid,
            'lastupdated' => date('Y-m-d'),
            'createdby' => $loginid,
            'sessionid' => $sessionid

        );

        $shiftStart = new DateTime($attendance_date . ' ' . $office_in_time);
        $shiftEnd   = new DateTime($attendance_date . ' ' . $office_out_time);

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
        } elseif ($punch_status == 'c_off') {
            $form_date['attendance_status'] = 'C Off';
        } elseif ($punch_status == 'half_c_off') {
            $form_date['attendance_status'] = 'Half C Off';
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
        } elseif ($punch_status == 'eoff') {
            $form_date['attendance_status'] = 'Extra Off'; 
        }elseif ($punch_status == 'half_eoff') {
            $form_date['attendance_status'] = 'Half Extra Off'; 
        }elseif ($punch_status == 'leave') {
            $form_date['attendance_status'] = 'Leave'; 
        }elseif ($punch_status == 'half_leave') {
            $form_date['attendance_status'] = 'Half Leave'; 
        }elseif ($punch_status == 'weekly_leave') {
            $form_date['attendance_status'] = 'Weekly Leave'; 
        } elseif ($punch_status == 'earn_leave') {
            $form_date['attendance_status'] = 'Earning Leave'; 
        } elseif ($punch_status == 'half_weekly_leave') {
            $form_date['attendance_status'] = 'Half Weekly Leave'; 
        } elseif ($punch_status == 'half_earn_leave') {
            $form_date['attendance_status'] = 'Half Earning Leave'; 
        } elseif ($punch_status == 'public_holiday') {
            $form_date['attendance_status'] = 'Public Holiday'; 
        }
       
        $lastid = $obj->insert_record_lastid("attendance_entry", $form_date);

        $form_data1 = array(
            "primary_id" => $lastid,
            "flag" => 'Punch IN Attendence',
            "activity_type" => 'Attendence IN',
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
    } else {
        $attendance_id = $obj->getvalfield("attendance_entry", "attendance_id", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$currentYear' and month ='$currentMonth' ");

        //$previous_att_status = $obj->getvalfield("attendance_entry", "attendance_status", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$currentYear' and month ='$currentMonth'");
        //$obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $attendance_date]);

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
            'updateby' => $loginid,
            'lastupdated' => date('Y-m-d'),
        );

        $shiftStart = new DateTime($attendance_date . ' ' . $office_in_time);
        $shiftEnd   = new DateTime($attendance_date . ' ' . $office_out_time);


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
        } elseif ($punch_status == 'earn_leave') {
            $form_date['attendance_status'] = 'Earning Leave'; 
        } elseif ($punch_status == 'half_weekly_leave') {
            $form_date['attendance_status'] = 'Half Weekly Leave'; 
        } elseif ($punch_status == 'half_earn_leave') {
            $form_date['attendance_status'] = 'Half Earning Leave'; 
        } elseif ($punch_status == 'c_off') {
            $form_date['attendance_status'] = 'C Off';
        } elseif ($punch_status == 'half_c_off') {
            $form_date['attendance_status'] = 'Half C Off';
        }elseif ($punch_status == 'eoff') {
            $form_date['attendance_status'] = 'Extra Off'; 
        }elseif ($punch_status == 'half_eoff') {
            $form_date['attendance_status'] = 'Half Extra Off'; 
        }elseif ($punch_status == 'leave') {
            $form_date['attendance_status'] = 'Leave'; 
        }elseif ($punch_status == 'half_leave') {
            $form_date['attendance_status'] = 'Half Leave'; 
        }elseif ($punch_status == 'public_holiday') {
            $form_date['attendance_status'] = 'Public Holiday'; 
        } 

        $where = ['attendance_id' => $attendance_id];
        $obj->update_record("attendance_entry", $where, $form_date);
        $form_data1 = array(
            "primary_id" => $attendance_id,
            "flag" => 'Punch OUT Attendence',
            "activity_type" => 'Attendence OUT',
            "createdby" => $loginid,
            "pagename" => 'employee_wise_attendance.php',
            "created_date" => $createdate,
            "created_time" => date('H:i:s'),
            "unit_id" => $unitid,
            'ipaddress' => $ipaddress,
            "sessionid" => $sessionid
        );
        $logactivity = $obj->insert_record("logactivity_master", $form_data1);
        echo 2;
    }
}