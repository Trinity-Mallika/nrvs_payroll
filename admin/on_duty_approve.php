<?php
include("../adminsession.php");
if (isset($_POST['updatess'])) {
    $updates = $_POST['updatess'];
    $emp_id = $obj->test_input($_POST['emp_id']);
    $emp_data = $obj->select_record("employee_master", ['emp_id' => $emp_id]);
    $department_id = $emp_data['department_id'];
    $shift_hrs = $emp_data['shift_id'];
    $unit_id = $emp_data['unit_id'];
    $basic_salary = $emp_data['basic_salary'];
    $date_of_joining = $emp_data['date_of_joining'];
    $skipMessages = [];
    $approvedCount = 0;
    $rejectedCount = 0;
    $pendingCount  = 0;
    foreach ($updates as $row) {
        $id = $row['id'];
        $status = $row['status'];
        $remark = $row['remark'];
        $intime = $row['intime'];
        $outtime = $row['outtime'];
        $date = $row['date'];
        $on_duty_type = $row['on_duty_type'];

        if ($status == 0) {
            $already_appr =$obj->getvalfield("on_duty_details","status","on_duty_details_id='$id'");
            if($already_appr==0){
                continue;
            }

            $attendance_id = $obj->getvalfield("on_duty_details","attendance_id","on_duty_details_id='$id'");

            $obj->update_record(
                "on_duty_details",
                ['on_duty_details_id' => $id],
                [
                    'status' => 0,
                    'updatedby' => $loginid,
                    'appr_remark' => $remark,
                    'lastupdated' => $createdate,
                    'approved_date' => null,
                    'approve_by' => 0
                ]
            );

            // Remove attendance if leave was previously approved
            $obj->delete_record("attendance_entry", [
                'emp_id' => $emp_id,
                'attendance_id' => $attendance_id,
                'unit_id' => $unit_id
            ]);

            $pendingCount++;
            continue;
        }


        
        if ($status == 2) { 
            $already_appr =$obj->getvalfield("on_duty_details","status","on_duty_details_id='$id'");
            if($already_appr==2){
                continue;
            }

            $obj->update_record(
                "on_duty_details",
                ['on_duty_details_id' => $id],
                [
                    'status' => 2,
                    'updatedby' => $loginid,
                    'appr_remark' => $remark,
                    'lastupdated' => $createdate,
                    'approved_date' => $createdate,
                    'approve_by' => $loginid
                ]
            );
            $attendance_id = $obj->getvalfield("on_duty_details","attendance_id","on_duty_details_id='$id'");
            $obj->delete_record("attendance_entry", [
                'emp_id' => $emp_id,
                'attendance_id' => $attendance_id,
                'unit_id' => $unit_id
            ]);

            $rejectedCount++;
            continue;
        }

    if ($status ==1) { 
        $date_of_joining = date('Y-m-d', strtotime($emp_data['date_of_joining']));
        $leave_date = date('Y-m-d', strtotime($date));
        if ($leave_date < $date_of_joining) {
            $skipMessages[] = [
                "date" => $date,
                "reason" => $on_duty_type. " cannot be approved before employee joining date (" . date('d-m-Y', strtotime($date_of_joining)) . ")"
            ];
            continue;
        }

        $already_appr =$obj->getvalfield("on_duty_details","status","on_duty_details_id='$id'");
        if($already_appr==1){
            continue;
        } 
        
       
            $month = date('m', strtotime($date));
            $year  = date('Y', strtotime($date));
            $where = array(
                'emp_id' => $emp_id,
                'attendance_date'  => $date,
                'year'   => $year,
                'month'   => $month,
                'unit_id'   => $unit_id
            );

            $obj->delete_record('attendance_entry', $where);
            $sql = "SELECT shift_id, in_time, out_time,working_hour, is_cross_day,grace_time_in,grace_time_out,ABS(TIME_TO_SEC(TIMEDIFF(in_time, '$intime'))) AS time_diff FROM shift_master WHERE unit_id = '$unit_id' AND working_hour = '$shift_hrs' ORDER BY time_diff ASC LIMIT 1";
           
            $res = $obj->executequery($sql);
            if (empty($res)) continue;
            $shift = $res[0];
            $office_in_time = $shift['in_time'];
            $office_out_time = $shift['out_time'];
            $shift_id     = $shift['shift_id'];
            $office_working_hour = $shift['working_hour'];
            $in_margin = $shift['grace_time_in'];
            $out_margin = $shift['grace_time_out'];

            $actualIn  = new DateTime("$date $intime");
            $actualOut = new DateTime("$date $outtime");

            if ($actualOut <= $actualIn) {
                $actualOut->modify('+1 day');
            }

            $workedMinutes = floor(
                ($actualOut->getTimestamp() - $actualIn->getTimestamp()) / 60
            );

            $timeParts = explode(':', $shift['working_hour']);
            $officeMinutes =
                ((int)($timeParts[0] ?? 0) * 60) +
                ((int)($timeParts[1] ?? 0));

            $minimumRequired = $officeMinutes - ($in_margin + $out_margin);

            $attendance_status = ($workedMinutes < $minimumRequired)
                ? "Half Day"
                : "Present";

            $working_hours = gmdate("H:i:s", $workedMinutes * 60);


            $form_date = [
                'emp_id' => $emp_id,
                'department_id' => $department_id,
                'attendance_date' => $date,
                'attendance_stamp' => $date . ' ' . $intime,
                'month' => $month,
                'working_hours' => $working_hours,
                'year' => $year,
                'attendance_status' => $attendance_status,
                'shift_id' => $shift_id,
                'intime' => $intime,
                'outtime' => $outtime,
                'entry_type' => $on_duty_type,
                'entry_type_out' =>  $on_duty_type,
                'in_status' => 'IN',
                'out_status' => 'OUT',
                'unit_id' => $unit_id,
                'sessionid' => $sessionid,
                'basic_salary' => $basic_salary,
                'createdate' => date('Y-m-d'),
                'createtime' =>  date('H:i:s'),
                'ipaddress' => $ipaddress,
            ];
            $lastid = $obj->insert_record_lastid("attendance_entry", $form_date);
            if ($lastid > 0) {
                $obj->update_record("on_duty_details", ['on_duty_details_id' => $id], ['status' => $status, 'updatedby' => $loginid, 'appr_remark' => $remark, 'lastupdated' => $createdate, 'approved_date' => $createdate, 'approve_by' => $loginid , 'attendance_id' => $lastid]);
                $approvedCount++;
            } else {
                $skipMessages[] = [
                    "date" => $date,
                    "reason" => "Attendance insertion failed"
                ];
            }
            $form_data1 = array(
                "primary_id" => $lastid,
                "flag" => 'Punch IN and OUT Attendence',
                "activity_type" => 'Attendence IN/OUT From On Duty',
                "createdby" => $loginid,
                "pagename" => 'on_duty_approve.php',
                "created_date" => $createdate,
                "created_time" => date('H:i:s'),
                "unit_id" => $unitid,
                'ipaddress' => $ipaddress,
                "sessionid" => $sessionid
            );
            $logactivity = $obj->insert_record("logactivity_master", $form_data1);
        
        }
    }
    $response = [
        "approvedCount" => $approvedCount,
        "rejectedCount" => $rejectedCount,
        "pendingCount"  => $pendingCount,
        "errors" => $skipMessages
];
   echo json_encode($response);
die;
}