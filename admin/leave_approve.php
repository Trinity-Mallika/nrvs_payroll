<?php
include("../adminsession.php");
$current_time = date('H:i:s');
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
        $leave_type = $row['leave_type'];
        $leave_day = $row['leave_day'];
        $date = $row['date'];

        //$obj->update_record("leave_apply_detail", ['leave_details_id' => $id], ['status' => $status, 'updatedby' => $loginid, 'appr_remark' => $remark, 'lastupdated' => $createdate, 'approve_date' => $createdate, 'approve_by' => $loginid]);
        if ($status == 0) {
            $already_appr =$obj->getvalfield("leave_apply_detail","status","leave_details_id='$id'");
            if($already_appr==0){
                continue;
            }

            $attendance_id = $obj->getvalfield("leave_apply_detail","attendance_id","leave_details_id='$id'");

            $obj->update_record(
                "leave_apply_detail",
                ['leave_details_id' => $id],
                [
                    'status' => 0,
                    'updatedby' => $loginid,
                    'appr_remark' => $remark,
                    'lastupdated' => $createdate,
                    'approve_date' => null,
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
            $already_appr =$obj->getvalfield("leave_apply_detail","status","leave_details_id='$id'");
            if($already_appr==2){
                continue;
            }

            $obj->update_record(
                "leave_apply_detail",
                ['leave_details_id' => $id],
                [
                    'status' => 2,
                    'updatedby' => $loginid,
                    'appr_remark' => $remark,
                    'lastupdated' => $createdate,
                    'approve_date' => $createdate,
                    'approve_by' => $loginid
                ]
            );
            $attendance_id = $obj->getvalfield("leave_apply_detail","attendance_id","leave_details_id='$id'");
            $obj->delete_record("attendance_entry", [
                'emp_id' => $emp_id,
                'attendance_id' => $attendance_id,
                'unit_id' => $unit_id
            ]);


            $rejectedCount++;
            continue;
        }


        $leaveApproved = false;
        if ($status == 1) {
            $date_of_joining = date('Y-m-d', strtotime($emp_data['date_of_joining']));
            $leave_date = date('Y-m-d', strtotime($date));

            if ($leave_date < $date_of_joining) {
                $skipMessages[] = [
                    "date" => $date,
                    "reason" => "Leave cannot be approved before employee joining date (" . date('d-m-Y', strtotime($date_of_joining)) . ")"
                ];
                continue;
            }


            $already_appr =$obj->getvalfield("leave_apply_detail","status","leave_details_id='$id'");
            if($already_appr==1){
                continue;
            } 
            $month = (int)date('m', strtotime($date));
            $year  = date('Y', strtotime($date));
            $leave_balance = 0;
            if ($leave_type == 'EL') {
                $leave_balance = $obj->getEarningLeave($emp_id,$sessionid,$month,$year);
            } elseif ($leave_type == 'EO') {
                $extra_off = $obj->getExtraOffBalance($emp_id,$month,$year);
                $leave_balance = $extra_off['balance'] ?? 0;
            } elseif ($leave_type == 'L') {
                //$leave_balance = $obj->get_opening_leave_balance($emp_id,$sessionid,$month, $year);
                $leave_balance = 0;
            } elseif ($leave_type == 'CO') {
                $leave_balance = $obj->getEmpCoffLeave($emp_id, $sessionid,$month,$year);
            }

            $attendance_row = $obj->select_record("attendance_entry", [
                "emp_id" => $emp_id,
                "attendance_date" => $date,
                "unit_id" => $unit_id
            ]);

            $attendance_status =$attendance_row['attendance_status'] ?? '';

            if (in_array($attendance_status, ['Present'])) {
                $skipMessages[] = [
                    "date" => $date,
                    "reason" => "Already marked Present"
                ];
                continue;
            }

            $fullLeaveStatuses = [
                'Weekly Leave',
                'Earning Leave',
                'Leave',
                'C Off',
                'Extra Off'
            ];

            if (in_array(trim($attendance_status), $fullLeaveStatuses)) {
                $skipMessages[] = [
                    "date" => $date,
                    "reason" => "Attendance already exists ($attendance_status)"
                ];
                continue;
            }

          $halfStatuses = [
            'Half Day',
            'Half Extra Off',
            'Half Weekly Leave',
            'Half Earning Leave',
            'Half Leave',
            'Half C Off'
        ];
        $isHalfAttendance = in_array(trim($attendance_status), $halfStatuses);
            if ($isHalfAttendance) { 
                if ($leave_day == 'FD') {
                    $skipMessages[] = [
                        "date" => $date,
                        "reason" => "Full Day leave not allowed because Half Day attendance already exists"
                    ];
                    continue;
                } 
            }

            // $where = array(
            //     'emp_id' => $emp_id,
            //     'attendance_date'  => $date,
            //     'year'   => $year,
            //     'month'   => $month,
            //     'unit_id'   => $unit_id
            // );
            // $obj->delete_record('attendance_entry', $where);
            // $obj->delete_record('attendance_log', $where);

            $required_balance = ($leave_day == 'FHD' || $leave_day == 'SHD') ? 0.5 : 1;

            if ($leave_balance <= 0) {
                $skipMessages[] = [
                    "date" => $date,
                    "reason" => "Insufficient leave balance"
                ];
                continue; 
            }

            if ($required_balance == 1 && $leave_balance < 1) {
                $skipMessages[] = [
                    "date" => $date,
                    "reason" => "Full day leave balance not available"
                ];
                continue;
            }

            if ($required_balance == 0.5 && $leave_balance < 0.5) {
                $skipMessages[] = [
                    "date" => $date,
                    "reason" => "Half day leave balance not available"
                ];
                continue;
            }
            if ($leave_type == 'LWP') {
                $skipMessages[] = [
                    "date" => $date,
                    "reason" => "LWP cannot be approved"
                ];
                continue;
            }
            $leave_data = array(
                "emp_id" => $emp_id,
                "department_id" => $department_id,
                "month" => (int)$month,
                "year" => (int)$year,
                "basic_salary" => $basic_salary,
                "unit_id" => $unit_id,
                "createdby" => $loginid,
                "ipaddress" => $ipaddress,
                "sessionid" => $sessionid,
                "createdate" => date("Y-m-d H:i:s")
            );

            $punch_status = '';
            if ($leave_day == 'FD' || $leave_day == 'SL') {
                if ($leave_type == 'WL') {
                    $punch_status = 'weekly_leave';
                } elseif ($leave_type == 'EL') {
                    $punch_status = 'earn_leave';
                }elseif ($leave_type == 'CO') {
                    $punch_status = 'c_off';
                } elseif ($leave_type == 'EO') {
                    $punch_status = 'extra_off';
                } elseif ($leave_type == 'L') {
                    $punch_status = 'leave';
                }
            } elseif ($leave_day == 'FHD' || $leave_day == 'SHD') {

                if ($leave_type == 'WL') {
                    $punch_status = 'half_weekly_leave';
                } elseif ($leave_type == 'EL') {
                    $punch_status = 'half_earn_leave';
                } elseif ($leave_type == 'EO') {
                    $punch_status = 'half_extra_off';
                } elseif ($leave_type == 'L') {
                    $punch_status = 'half_leave';
                }elseif ($leave_type == 'CO') {
                    $punch_status = 'half_c_off';
                }
            }

            $sql = "SELECT shift_id, in_time, out_time,working_hour, is_cross_day,grace_time_in,grace_time_out FROM shift_master WHERE unit_id = '$unit_id' AND working_hour = '$shift_hrs' ORDER BY shift_id ASC LIMIT 1";

            $res = $obj->executequery($sql);
            if (empty($res)) continue;
            $shift = $res[0];
            $office_in_time = $shift['in_time'];
            $office_out_time = $shift['out_time'];
            $shift_id     = $shift['shift_id'];
            $office_working_hour = $shift['working_hour'];
            $in_margin = $shift['grace_time_in'];
            $out_margin = $shift['grace_time_out'];



            $form_date = [
                'emp_id' => $emp_id,
                'department_id' => $department_id,
                'attendance_date' => $date,
                'attendance_stamp' => $date,
                "month" => (int)$month,
                "year" => (int)$year,
                'shift_id' => $shift_id,
                'entry_type' => 'manual',
                'entry_type_out' => 'manual',
                'in_status' => 'IN',
                'out_status' => 'OUT',
                'unit_id' => $unit_id,
                'sessionid' => $sessionid,
                'in_remark' => $remark,
                'basic_salary' => $basic_salary,
                'createdate' => date('Y-m-d'),
                'createtime' => $current_time,
                'createdby' => $loginid,
                'updateby' => $loginid,
                'lastupdated' => date('Y-m-d'),
                'ipaddress' => $ipaddress,
            ];

            if ($punch_status == 'weekly_leave') {
                $form_date['attendance_status'] = 'Weekly Leave'; 
            } elseif ($punch_status == 'earn_leave') { 
                $form_date['attendance_status'] = 'Earning Leave'; 
            } elseif ($punch_status == 'half_weekly_leave') { 
                $form_date['attendance_status'] = 'Half Weekly Leave'; 
            } elseif ($punch_status == 'half_earn_leave') { 
                $form_date['attendance_status'] = 'Half Earning Leave'; 
            }elseif ($punch_status == 'half_extra_off') { 
                $form_date['attendance_status'] = 'Half Extra Off'; 
            } elseif ($punch_status == 'extra_off') { 
                $form_date['attendance_status'] = 'Extra Off'; 
            }elseif ($punch_status == 'leave') { 
                $form_date['attendance_status'] = 'Leave'; 
            } elseif ($punch_status == 'half_leave') { 
                $form_date['attendance_status'] = 'Half Leave'; 
            }elseif ($punch_status == 'c_off') { 
                $form_date['attendance_status'] = 'C Off'; 
            } elseif ($punch_status == 'half_c_off') { 
                $form_date['attendance_status'] = 'Half C Off'; 
            }

            $lastid = $obj->insert_record_lastid("attendance_entry", $form_date);

            if ($lastid > 0) {
                $obj->update_record(
                    "leave_apply_detail",
                    ['leave_details_id' => $id],
                    [
                        'status' => $status,
                        'updatedby' => $loginid,
                        'attendance_id' => $lastid,
                        'appr_remark' => $remark,
                        'lastupdated' => $createdate,
                        'approve_date' => $createdate,
                        'approve_by' => $loginid
                    ]
                );
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
                "activity_type" => 'Attendence IN/OUT From Leave',
                "createdby" => $loginid,
                "pagename" => 'leave_approve.php',
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