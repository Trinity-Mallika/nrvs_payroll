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


    $office_in_time = $obj->getvalfield("shift_master", "in_time", "shift_id='$punch_shift_id'");
    $office_out_time = $obj->getvalfield("shift_master", "out_time", "shift_id='$punch_shift_id'");
    $in_margin = $obj->getvalfield("shift_master", "grace_time_in", "shift_id='$punch_shift_id'");
    $out_margin = $obj->getvalfield("shift_master", "grace_time_out", "shift_id='$punch_shift_id'");
    $is_cross_day = $obj->getvalfield("shift_master", "is_cross_day", "shift_id='$punch_shift_id'");



    $count = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$currentYear' and month ='$currentMonth'");

    if ($count == 0) {
        $form_date = array(
            $punchtype => $punchtime,
            'emp_id' => $emp_id,
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
            'unit_id' => $unitid,
            'sessionid' => $sessionid

        );
        $obj->insert_record("attendance_entry", $form_date);
        echo 1;
    } else {
        $attendance_id = $obj->getvalfield("attendance_entry", "attendance_id", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$currentYear' and month ='$currentMonth' ");
        $shift_id = $obj->getvalfield("attendance_entry", "shift_id", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$currentYear' and month ='$currentMonth' ");

        $intime = $obj->getvalfield("attendance_entry", "intime", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$currentYear' and month ='$currentMonth' ");

        $office_working_hour = $obj->getvalfield("shift_master", "working_hour", "shift_id='$shift_id'");

        $existing_outtime = $obj->getvalfield(
            "attendance_entry",
            "outtime",
            "attendance_id='$attendance_id'"
        );


        $shiftStart = new DateTime($attendance_date . ' ' . $office_in_time);
        $shiftEnd = new DateTime($attendance_date . ' ' . $office_out_time);

        $actualIn = new DateTime($attendance_date . ' ' . $intime);
        $actualOut = new DateTime($attendance_date . ' ' . $punchtime);

        // $allowedIn  = clone $shiftStart;
        // $allowedIn->modify("+{$in_margin} minutes");

        // $allowedOut = clone $shiftEnd;
        // $allowedOut->modify("-{$out_margin} minutes");

        // if ($actualOut < $actualIn) {
        //     $actualOut->modify('+1 day');
        // }
        $workedSeconds = $actualOut->getTimestamp() - $actualIn->getTimestamp();
        $workedMinutes = floor($workedSeconds / 60);

        list($hours, $minutes, $seconds) = explode(':', $office_working_hour);
        $officeWorkingMinutes = ($hours * 60) + $minutes;
        $totalMarginMinutes = $in_margin + $out_margin;

        $minimumRequiredMinutes = $officeWorkingMinutes - $totalMarginMinutes;

        /* Night shift adjustment */
        // if ($actualIn < $shiftStart) {
        //     $actualIn->modify('+1 day');
        // }

        // if ($actualOut < $shiftEnd) {
        //     $actualOut->modify('+1 day');
        // }


        // $inHalf  = ($actualIn > $allowedIn);
        // $outHalf = ($actualOut < $allowedOut);
        // $punchtime_ts      = strtotime($punchtime);
        // $office_out_time_ts = strtotime($office_out_time);

        // if ($actualIn > $allowedIn || $punchtime_ts < $office_out_time_ts) {
        //     $attendance_status = "Half Day";
        //     $attheadid = 3;
        // } else {
        //     $attendance_status = "Present";
        //     $attheadid = 1;
        // }
        print_r($workedMinutes);
        print_r('hii');
        print_r($minimumRequiredMinutes);

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

        if ($punchtype == 'intime') {
            $existing_intime = $obj->getvalfield(
                "attendance_entry",
                "intime",
                "attendance_id='$attendance_id'"
            );

            $form_date = array(
                $punchtype => $punchtime,
                'previous_in_time' => $existing_intime,
                'in_remark' => $punch_remark,
                'ipaddress' => $ipaddress,
                'lastupdated' => date('Y-m-d'),
                'attheadid' => $attheadid,
                'attendance_status' => $attendance_status,
                'out_status' => 'OUT',
            );
            $where = ['attendance_id' => $attendance_id];
            $obj->update_record("attendance_entry", $where, $form_date);
        } else {
            $where = array('attendance_id' => $attendance_id);
            $form_date = array(
                $punchtype => $punchtime,
                'working_hours' => $working_hours,
                'ipaddress' => $ipaddress,
                'lastupdated' => date('Y-m-d'),
                'entry_type_out' => 'manual',
                'basic_salary' => $emp_salary,
                'out_remark' => $punch_remark,
                'attheadid' => $attheadid,
                'shift_id' => $shift_id,
                'attendance_status' => $attendance_status,
                'out_status' => 'OUT',
            );
            if (!empty($existing_outtime) && $existing_outtime != '00:00:00') {
                $form_date['previous_out_time'] = $existing_outtime;
            }
            print_r($form_date);

            $obj->update_record("attendance_entry", $where, $form_date);
        }
    }
}
