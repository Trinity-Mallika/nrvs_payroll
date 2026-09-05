<?php
include("../adminsession.php");
$current_time = date('H:i:s');
if (isset($_POST['updatess'])) {
    $updates = $_POST['updatess'];
    $emp_id = $obj->test_input($_POST['emp_id']);
    $emp_data = $obj->select_record("employee_master", ['emp_id' => $emp_id]);
    $department_id = $emp_data['department_id'];
    $shift_hrs = $emp_data['shift_id']??0;
    $unit_id = $emp_data['unit_id']??0;
    $basic_salary = $emp_data['basic_salary']??0;
    $date_of_joining = $emp_data['date_of_joining']??'';
    $is_esic = $emp_data['is_esic']??0;
    $allow_add_leave = $emp_data['allow_add_leave']??0;
    $setting_type = ($is_esic  == 1) ? 'ESIC' : 'Non ESIC';
    $is_allow_c_off = $obj->getvalfield("department_master", "c_off_check", "department_id='$department_id'");
    $allow_earn_leave_carry = $obj->getvalfield("department_master", "earn_leave_check", "department_id='$department_id'");
    $is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$emp_data[unit_id]'");

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

            $month = (int)date('m', strtotime($date));
            $year  = date('Y', strtotime($date));
            $currentMonth = (int)$month;
            $currentYear  = (int)$year;

            $totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
            $res = $obj->executequery("
                SELECT 
                    SUM(CASE 
                        WHEN attendance_status = 'Present' THEN 1 
                        ELSE 0 
                    END) AS total_present1,

                    SUM(CASE 
                        WHEN attendance_status = 'Half Day' THEN 1 
                        ELSE 0 
                    END) AS total_half1,

                    SUM(CASE 
                        WHEN attendance_status IN ('Present','Weekly Leave','Earning Leave','C Off','Extra Off','Leave') THEN 1 
                        ELSE 0 
                    END) AS total_present,

                    SUM(CASE 
                        WHEN attendance_status IN ('Half Day','Half Weekly Leave','Half Earning Leave','Half C Off','Half Extra Off','Half Leave') THEN 1 
                        ELSE 0 
                    END) AS total_half

                FROM attendance_entry
                WHERE emp_id='$emp_id'
                AND month='$currentMonth'
                AND year='$currentYear'
                AND unit_id='$unitid'
            ");
            $attRow = $res[0] ?? [];
            $total_present1 = $attRow['total_present1'] ?? 0;
            $total_half1    = $attRow['total_half1'] ?? 0;

            $total_present  = $attRow['total_present'] ?? 0;
            $total_half     = $attRow['total_half'] ?? 0;

            $real_total_attandence = $total_present1 + ($total_half1 / 2);
            $total_attandence      = $total_present + ($total_half / 2);

            $totalAttendance = $total_present + ($total_half / 2); 
            $week_leave = $obj->totalWeeklyLeave($unitid, $real_total_attandence, $emp_id, $currentMonth, $currentYear);
            $earn_leave_present =  $real_total_attandence+$week_leave;
            $monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $earn_leave_present, $unitid);
            $result = $obj->calculateLeaveUsage(
                $totalDaysInMonth,
                $totalAttendance,
                $week_leave,
                $monthly_leave,
                $is_allow_c_off,
                $is_all_leave_add,
                $allow_earn_leave_carry,
                0,
                0,
                $date_of_joining,
                $currentMonth,
                $currentYear
            );
            // Total working days of month
            $totalWorkingDays = $result['total_working_days'] ?? 0; 
            
            if ($totalDaysInMonth <= $totalWorkingDays && $allow_add_leave == 0) {
                $skipMessages[] = [ 
                    "date"        => date('d-m-Y', strtotime($date)),
                    "reason"      => "Leave cannot be approved because the employee has already completed all working days ($totalWorkingDays) for ".date('F Y', strtotime($date))
                ];
                continue;
            }

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
 

            // if (in_array($attendance_status, ['Present'])) {
            //     $skipMessages[] = [
            //         "date" => $date,
            //         "reason" => "Already marked Present"
            //     ];
            //     continue;
            // }

        
            $fullLeaveStatuses = [
                'Weekly Leave',
                'Present',
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

            // $attendanceLogExists = $obj->getvalfield(
            //     "attendance_log",
            //     "COUNT(*)",
            //     "emp_id='$emp_id'
            //     AND attendance_date='$date'
            //     AND unit_id='$unit_id'"
            // );

            // if ($attendanceLogExists > 0 && !$isHalfAttendance) {
            //     $skipMessages[] = [
            //         "date" => date('d-m-Y', strtotime($date)),
            //         "reason" => "Attendance punch already exists for this date."
            //     ];
            //     continue;
            // }


            // $where = array(
            //     'emp_id' => $emp_id,
            //     'attendance_date'  => $date,
            //     'year'   => $year,
            //     'month'   => $month,
            //     'unit_id'   => $unit_id
            // );
            // $obj->delete_record('attendance_entry', $where);
            // $obj->delete_record('attendance_log', $where);

            if ($leave_type == 'LWP') {
                $skipMessages[] = [
                    "date" => $date,
                    "reason" => "LWP cannot be approved"
                ];
                continue;
            }

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

            $form_date = [
                'emp_id' => $emp_id,
                'department_id' => $department_id,
                'attendance_date' => $date,
                'attendance_stamp' => $date,
                "month" => (int)$month,
                "year" => (int)$year, 
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