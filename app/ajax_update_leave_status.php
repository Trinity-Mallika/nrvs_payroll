<?php
include("appsession.php");

// $id = $_POST['id'];
// $type = $_POST['type'];
// $status = $_POST['status'];
// if($type=='all'){
// $where = ['on_duty_id'=>$id , 'status'=>0];
// $obj->update_record("leave_apply_detail",$where,['status'=>$status]);

// echo "success";
// }else if($type=='single'){
    
// $where = ['leave_details_id'=>$id];
// $obj->update_record("leave_apply_detail",$where,['status'=>$status]);

// echo "success";
 
// }else{
//  echo 'error';

// } 
 
$id     = $_POST['id'];
$type   = $_POST['type'];
$status = $_POST['status'];

if ($type == 'all') {
    // GET ALL PENDING LEAVES
    $leaveData = $obj->executequery("
        SELECT lad.*, 
               la.emp_id,
               em.department_id,
               em.shift_id,
               em.unit_id,
               em.basic_salary
        FROM leave_apply_detail lad

        INNER JOIN on_duty_master la 
            ON la.on_duty_id = lad.on_duty_id

        INNER JOIN employee_master em 
            ON em.emp_id = la.emp_id

        WHERE lad.on_duty_id = '$id'
        AND lad.status = '0'
    ");

    foreach ($leaveData as $row) {

        $leave_details_id = $row['leave_details_id'];
 
        $obj->update_record(
            "leave_apply_detail",
            ['leave_details_id' => $leave_details_id],
            [
                'status'       => $status,
                'updatedby'    => $emp_id,
                'lastupdated'  => $createdate,
                'approve_date' => $createdate,
                'approve_by'   => $emp_id
            ]
        );

        // ONLY IF APPROVED
        if ($status == 1) {

            $emp_id         = $row['emp_id'];
            $department_id  = $row['department_id'];
            $shift_hrs      = $row['shift_id'];
            $unit_id        = $row['unit_id'];
            $basic_salary   = $row['basic_salary'];

            $leave_type = $row['leave_type'];
            $leave_day  = $row['leave_day'];
            $date       = $row['date'];

            $month = date('m', strtotime($date));
            $year  = date('Y', strtotime($date));

            // DELETE OLD ENTRY
            $where = array(
                'emp_id' => $emp_id,
                'attendance_date' => $date,
                'year' => $year,
                'month' => $month,
                'unit_id' => $unit_id
            );

            $obj->delete_record('attendance_entry', $where);
            $obj->delete_record('attendance_log', $where);

            // SKIP LWP
            if ($leave_type == 'LWP') {
                continue;
            }

            // PUNCH STATUS
            $punch_status = '';

            if ($leave_day == 'FD') {

                if ($leave_type == 'WL') {
                    $punch_status = 'Weekly Leave';
                } elseif ($leave_type == 'EL') {
                    $punch_status = 'Earning Leave';
                } elseif ($leave_type == 'EO') {
                    $punch_status = 'Extra Off';
                } elseif ($leave_type == 'L') {
                    $punch_status = 'Leave';
                }
            } else {
                if ($leave_type == 'WL') {
                    $punch_status = 'Half Weekly Leave';
                } elseif ($leave_type == 'EL') {
                    $punch_status = 'Half Earning Leave';
                } elseif ($leave_type == 'EO') {
                    $punch_status = 'Half Extra Off';
                } elseif ($leave_type == 'L') {
                    $punch_status = 'Half Leave';
                }
            }

            // SHIFT DETAILS
            $sql = "SELECT shift_id, in_time, out_time, working_hour
                    FROM shift_master
                    WHERE unit_id = '$unit_id'
                    AND working_hour = '$shift_hrs'
                    LIMIT 1";

            $res = $obj->executequery($sql);

            if (empty($res)) {
                continue;
            }

            $shift = $res[0];

            // INSERT ATTENDANCE
            $form_data = array(

                'emp_id' => $emp_id,
                'department_id' => $department_id,
                'attendance_date' => $date,
                'attendance_stamp' => $date,

                'month' => $month,
                'year' => $year,

                'shift_id' => $shift['shift_id'],

                'entry_type' => 'manual',
                'entry_type_out' => 'manual',

                'in_status' => 'IN',
                'out_status' => 'OUT',

                'unit_id' => $unit_id,
                'sessionid' => $sessionid,

                'attendance_status' => $punch_status,

                'basic_salary' => $basic_salary,

                'createdate' => date('Y-m-d'),
                'createtime' => date('H:i:s'),

                'createdby' => $loginid,
                'updateby' => $loginid,

                'lastupdated' => date('Y-m-d'),

                'ipaddress' => $ipaddress
            );

            $lastid = $obj->insert_record_lastid(
                "attendance_entry",
                $form_data
            );

            // LOG
            $logData = array(
                "primary_id" => $lastid,
                "flag" => 'Punch IN and OUT Attendance',
                "activity_type" => 'Attendance From Leave Approval',
                "createdby" => $loginid,
                "pagename" => $pagename,
                "created_date" => $createdate,
                "created_time" => date('H:i:s'),
                "unit_id" => $unitid,
                "ipaddress" => $ipaddress,
                "sessionid" => $sessionid
            );

            $obj->insert_record("logactivity_master", $logData);
        }
    }

    echo "success";

} elseif ($type == 'single') {

    // GET SINGLE LEAVE
    $leaveData = $obj->executequery("
        SELECT lad.*, 
               la.emp_id,
               em.department_id,
               em.shift_id,
               em.unit_id,
               em.basic_salary
        FROM leave_apply_detail lad

        INNER JOIN on_duty_master la 
            ON la.on_duty_id = lad.on_duty_id

        INNER JOIN employee_master em 
            ON em.emp_id = la.emp_id

        WHERE lad.leave_details_id = '$id'
        LIMIT 1
    ");

    if (!empty($leaveData)) {

        $row = $leaveData[0];

        // UPDATE STATUS
        $obj->update_record(
            "leave_apply_detail",
            ['leave_details_id' => $id],
            [
                'status'       => $status,
                'updatedby'    => $emp_id,
                'lastupdated'  => $createdate,
                'approve_date' => $createdate,
                'approve_by'   => $emp_id
            ]
        );

        // ONLY FOR APPROVE
        if ($status == 1) {

            $emp_id         = $row['emp_id'];
            $department_id  = $row['department_id'];
            $shift_hrs      = $row['shift_id'];
            $unit_id        = $row['unit_id'];
            $basic_salary   = $row['basic_salary'];

            $leave_type = $row['leave_type'];
            $leave_day  = $row['leave_day'];
            $date       = $row['date'];

            $month = date('m', strtotime($date));
            $year  = date('Y', strtotime($date));

            // DELETE OLD ATTENDANCE
            $where = array(
                'emp_id' => $emp_id,
                'attendance_date' => $date,
                'year' => $year,
                'month' => $month,
                'unit_id' => $unit_id
            );

            $obj->delete_record('attendance_entry', $where);
            $obj->delete_record('attendance_log', $where);

            // SKIP LWP
            if ($leave_type != 'LWP') {

                // ATTENDANCE STATUS
                $punch_status = '';

                if ($leave_day == 'FD') {

                    if ($leave_type == 'WL') {
                        $punch_status = 'Weekly Leave';
                    } elseif ($leave_type == 'EL') {
                        $punch_status = 'Earning Leave';
                    } elseif ($leave_type == 'EO') {
                        $punch_status = 'Extra Off';
                    } elseif ($leave_type == 'L') {
                        $punch_status = 'Leave';
                    }

                } else {

                    if ($leave_type == 'WL') {
                        $punch_status = 'Half Weekly Leave';
                    } elseif ($leave_type == 'EL') {
                        $punch_status = 'Half Earning Leave';
                    } elseif ($leave_type == 'EO') {
                        $punch_status = 'Half Extra Off';
                    } elseif ($leave_type == 'L') {
                        $punch_status = 'Half Leave';
                    }
                }

                // SHIFT DETAILS
                $sql = "SELECT shift_id, in_time, out_time, working_hour
                        FROM shift_master
                        WHERE unit_id = '$unit_id'
                        AND working_hour = '$shift_hrs'
                        LIMIT 1";

                $res = $obj->executequery($sql);

                if (!empty($res)) {

                    $shift = $res[0];

                    // INSERT ATTENDANCE
                    $form_data = array(

                        'emp_id' => $emp_id,
                        'department_id' => $department_id,
                        'attendance_date' => $date,
                        'attendance_stamp' => $date,

                        'month' => $month,
                        'year' => $year,

                        'shift_id' => $shift['shift_id'],

                        'entry_type' => 'manual',
                        'entry_type_out' => 'manual',

                        'in_status' => 'IN',
                        'out_status' => 'OUT',

                        'unit_id' => $unit_id,
                        'sessionid' => $sessionid,

                        'attendance_status' => $punch_status,

                        'basic_salary' => $basic_salary,

                        'createdate' => date('Y-m-d'),
                        'createtime' => date('H:i:s'),

                        'createdby' => $loginid,
                        'updateby' => $loginid,

                        'lastupdated' => date('Y-m-d'),

                        'ipaddress' => $ipaddress
                    );

                    $lastid = $obj->insert_record_lastid(
                        "attendance_entry",
                        $form_data
                    );

                    // LOG
                    $logData = array(
                        "primary_id" => $lastid,
                        "flag" => 'Punch IN and OUT Attendance',
                        "activity_type" => 'Attendance From Leave Approval',
                        "createdby" => $loginid,
                        "pagename" => $pagename,
                        "created_date" => $createdate,
                        "created_time" => date('H:i:s'),
                        "unit_id" => $unitid,
                        "ipaddress" => $ipaddress,
                        "sessionid" => $sessionid
                    );

                    $obj->insert_record("logactivity_master", $logData);
                }
            }
        }

        echo "success";

    } else {

        echo "error";
    }
}

?>