<?php include("../adminsession.php");
if (isset($_POST['status']) && $_POST['status'] == 'punchinout') {
    $punchtype = $_POST['punchtype'];
    $punchtime = $_POST['punchtime'];
    $emp_id = $_POST['emp_id'];
    $attendance_date = $_POST['attdate'];
    $currentYear = $_POST['currentYear'];
    $currentMonth = $_POST['currentMonth'];
    $punch_remark = $_POST['punch_remark'];
    $punch_shift_id = $_POST['punch_shift_id'];
    $current_time = date("H:i:s");

    $total_days = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
    $emp_salary =  $obj->getvalfield("employee_master", "basic_salary", "emp_id='$emp_id'");
    $department_id =  $obj->getvalfield("employee_master", "department_id", "emp_id='$emp_id'");


    $count = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$currentYear' and month ='$currentMonth'");

    if ($count == 0) {
        $form_date = array(
            $punchtype => $punchtime,
            'emp_id' => $emp_id,
            'department_id' => $department_id,
            'attendance_date' => $attendance_date,
            'attendance_stamp' => $attendance_date . ' ' . $punchtime,
            'month' => $currentMonth,
            'year' => $currentYear,
            'createdate' => date('Y-m-d'),
            'createtime' => $current_time,
            'attendance_status' => 'Incomplete',
            'ipaddress' => $ipaddress,
            'basic_salary' => $emp_salary,
            'in_remark' => $punch_remark,
            'shift_id' => $punch_shift_id,
            'in_status' => 'IN',
            'entry_type' => 'manual',
            'attheadid' => 5,
            'createdby' => $loginid,
            'unit_id' => $unitid,
            'sessionid' => $sessionid
        );
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
        $shift_id = $obj->getvalfield("attendance_entry", "shift_id", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$currentYear' and month ='$currentMonth' ");

        $shift_data = $obj->select_record("shift_master", ['shift_id' => $shift_id]);
        $in_margin = $shift_data['grace_time_in'];
        $out_margin = $shift_data['grace_time_out'];
        $office_in_time = $shift_data['in_time'];
        $office_out_time = $shift_data['out_time'];
        $is_cross_day = $shift_data['is_cross_day'];
        $office_working_hour = $shift_data['working_hour'];


        $intime = $obj->getvalfield("attendance_entry", "intime", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$currentYear' and month ='$currentMonth'");



        $existing_outtime = $obj->getvalfield(
            "attendance_entry",
            "outtime",
            "attendance_id='$attendance_id'"
        );


        $shiftStart = new DateTime($attendance_date . ' ' . $office_in_time);
        $shiftEnd = new DateTime($attendance_date . ' ' . $office_out_time);

        $actualIn = new DateTime($attendance_date . ' ' . $intime);
        $actualOut = new DateTime($attendance_date . ' ' . $punchtime);

        if ($actualOut <= $actualIn) {
            $actualOut->modify('+1 day');
        }
        $workedSeconds = $actualOut->getTimestamp() - $actualIn->getTimestamp();
        $workedMinutes = floor($workedSeconds / 60);

        list($hours, $minutes, $seconds) = explode(':', $office_working_hour);
        $officeWorkingMinutes = ($hours * 60) + $minutes;

        $totalMarginMinutes = $in_margin + $out_margin;
        $minimumRequiredMinutes = $officeWorkingMinutes - $totalMarginMinutes;

        if ($workedMinutes < $minimumRequiredMinutes) {
            $attendance_status = "Half Day";
            $attheadid = 3;
        } else {
            $attendance_status = "Present";
            $attheadid = 1;
        }

        $start_time = new DateTime($intime);
        $end_time   = new DateTime($punchtime);

        if ($end_time < $start_time) {
            $end_time->modify('+1 day');
        }

        $interval = $start_time->diff($end_time);

        $working_hours = sprintf(
            '%02d:%02d:%02d',
            ($interval->days * 24) + $interval->h,
            $interval->i,
            $interval->s
        );
        $total_hours = ($interval->days * 24) + $interval->h + ($interval->i / 60);

        $overtimeMinutes = $obj->calculateOvertimeTime($working_hours, $office_working_hour);


        if ($punchtype == 'intime') {
            $existing_intime = $obj->getvalfield(
                "attendance_entry",
                "intime",
                "attendance_id='$attendance_id'"
            );

            $form_date = array(
                $punchtype => $punchtime,
                'department_id' => $department_id,
                'previous_in_time' => $existing_intime,
                'in_remark' => $punch_remark,
                'ipaddress' => $ipaddress,
                'lastupdated' => date('Y-m-d'),
                'attheadid' => $attheadid,
                'updateby' => $loginid,
                'attendance_status' => $attendance_status,
                'out_status' => 'OUT',
            );
            $where = ['attendance_id' => $attendance_id];
            $obj->update_record("attendance_entry", $where, $form_date);
        } else {
            $where = array('attendance_id' => $attendance_id);
            $form_date = array(
                $punchtype => $punchtime,
                'department_id' => $department_id,
                'working_hours' => $working_hours,
                'ipaddress' => $ipaddress,
                'lastupdated' => date('Y-m-d'),
                'entry_type_out' => 'manual',
                'basic_salary' => $emp_salary,
                'out_remark' => $punch_remark,
                'attheadid' => $attheadid,
                'shift_id' => $shift_id,
                'updateby' => $loginid,
                'attendance_status' => $attendance_status,
                'overtime' => $overtimeMinutes,
                'out_status' => 'OUT',
            );
            if (!empty($existing_outtime) && $existing_outtime != '00:00:00') {
                $form_date['previous_out_time'] = $existing_outtime;
            }
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
                "ipaddress" => $ipaddress,
                "sessionid" => $sessionid
            );
            $logactivity = $obj->insert_record("logactivity_master", $form_data1);
        }
    }
}
