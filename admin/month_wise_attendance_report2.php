<?php
include("../adminsession.php");
set_time_limit(600);   
ini_set('max_execution_time', 600);
$current_time = date('H:i:s');
if(isset($_POST['bulkApprove'])){
    $ids=$_POST['ids'];
    $status=$_POST['status'];
    $skipMessages = [];
    $approvedCount = 0;
    $rejectedCount = 0;
    $pendingCount  = 0;
    $totalLeaves  = 0;
    $totalApplication = count($ids); 

    // ---- Caches (per emp_id-month-year) so we hit the DB once instead of per leave-date row ----
    $attendanceMapCache   = []; // [cacheKey] => [ 'YYYY-MM-DD' => attendance_status, ... ]
    $attendanceAggCache   = []; // [cacheKey] => [ total_present1, total_half1, total_present, total_half ]
    $attendanceLogCache   = []; // [cacheKey] => [ 'YYYY-MM-DD' => true, ... ]
    $workingDaysCache     = []; // [cacheKey] => totalWorkingDays
    $debug     = []; // [cacheKey] => totalWorkingDays

    foreach($ids as $on_duty_id){
        $details=$obj->executequery("
            SELECT *
            FROM leave_apply_detail
            WHERE on_duty_id='$on_duty_id'
            ORDER BY date
        ");
        if(empty($details))
            continue;

        $emp_id=$obj->getvalfield(
            "on_duty_master",
            "emp_id",
            "on_duty_id='$on_duty_id'"
        );
        $emp_data = $obj->select_record("employee_master", ['emp_id' => $emp_id]);
        $department_id = $emp_data['department_id']??'';
        $shift_hrs = $emp_data['shift_id']??'';
        $unit_id = $emp_data['unit_id']??'';
        $basic_salary = $emp_data['basic_salary']??'';
        $date_of_joining = $emp_data['date_of_joining']??''; 
        $is_esic = $emp_data['is_esic']??'';
        $setting_type = ($is_esic  == 1) ? 'ESIC' : 'Non ESIC';
        $is_allow_c_off = $obj->getvalfield("department_master", "c_off_check", "department_id='$department_id'");
        $allow_earn_leave_carry = $obj->getvalfield("department_master", "earn_leave_check", "department_id='$department_id'");
        $is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$emp_data[unit_id]'");
        
        foreach($details as $row){
            $totalLeaves++;
            $id=$row['leave_details_id'];
            $date=$row['date'];
            $leave_day=$row['leave_day'];
            $leave_type=$row['leave_type'];
            $remark=$row['appr_remark'];
            $employee = $emp_data['emp_code']." - ".$emp_data['first_name'];
            $month = (int)date('m', strtotime($date));
            $year  = date('Y', strtotime($date));
            $currentMonth = (int)$month;
            $currentYear  = (int)$year;
            $totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
            $cacheKey = $emp_id.'-'.$currentMonth.'-'.$currentYear;

            if ($status == 0) {
                $already_appr = $row['status'];
                if($already_appr==0){
                    continue;
                }

                $attendance_id = $row['attendance_id'];
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
                $already_appr = $row['status'];
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
                $attendance_id = $row['attendance_id'];
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
              
                $already_appr = $row['status'];
                if($already_appr==1){
                    $skipMessages[] = [
                        "employee" => $employee,
                        "application" => $on_duty_id,
                        "date" => date('d-m-Y', strtotime($date)),
                        "reason" => "Already Approved"
                    ];
                    continue;
                }

                   if ($leave_type == 'LWP') {
                    $skipMessages[] = [
                        "employee" => $employee,
                        "application" => $on_duty_id,
                        "date" => date('d-m-Y', strtotime($date)),
                        "reason" => "LWP cannot be approved"
                    ];
                    continue;
                }

                // ---- Build (or reuse) this employee's month-level attendance data ONCE ----
                if (!isset($attendanceMapCache[$cacheKey])) {
                    $monthRows = $obj->executequery("
                        SELECT attendance_date, attendance_status
                        FROM attendance_entry
                        WHERE emp_id='$emp_id'
                        AND month='$currentMonth'
                        AND year='$currentYear'
                        AND unit_id='$unit_id'
                    ");

                    $map = [];
                    $total_present1 = 0;
                    $total_half1    = 0;
                    $total_present  = 0;
                    $total_half     = 0;

                    $fullPresentSet = ['Present','Weekly Leave','Earning Leave','C Off','Extra Off','Leave'];
                    $halfPresentSet = ['Half Day','Half Weekly Leave','Half Earning Leave','Half C Off','Half Extra Off','Half Leave'];

                    foreach ($monthRows as $mr) {
                        $mDate = $mr['attendance_date'];
                        $mStatus = $mr['attendance_status'];

                        // date -> status map, used later for per-date lookups
                        $map[$mDate] = $mStatus;

                        if ($mStatus === 'Present') $total_present1++;
                        if ($mStatus === 'Half Day') $total_half1++;
                        if (in_array($mStatus, $fullPresentSet)) $total_present++;
                        if (in_array($mStatus, $halfPresentSet)) $total_half++;
                    }

                    $attendanceMapCache[$cacheKey] = $map;
                    $attendanceAggCache[$cacheKey] = [
                        'total_present1' => $total_present1,
                        'total_half1'    => $total_half1,
                        'total_present'  => $total_present,
                        'total_half'     => $total_half,
                    ];

                    // Attendance log dates for the month, fetched once
                    $firstDay = "$currentYear-".str_pad($currentMonth,2,'0',STR_PAD_LEFT)."-01";
                    $logRows = $obj->executequery("
                        SELECT DISTINCT attendance_date
                        FROM attendance_log
                        WHERE emp_id='$emp_id'
                        AND unit_id='$unit_id'
                        AND attendance_date BETWEEN '$firstDay' AND LAST_DAY('$firstDay')
                    ");
                    $logSet = [];
                    foreach ($logRows as $lr) {
                        $logSet[$lr['attendance_date']] = true;
                    }
                    $attendanceLogCache[$cacheKey] = $logSet;
                }

                // ---- Compute working-days-used ONCE per employee/month, reuse after ----
                if (!isset($workingDaysCache[$cacheKey])) {
                    $agg = $attendanceAggCache[$cacheKey];
                    $real_total_attandence = $agg['total_present1'] + ($agg['total_half1'] / 2);
                    $totalAttendance       = $agg['total_present'] + ($agg['total_half'] / 2);

                    $week_leave = $obj->totalWeeklyLeave($unit_id, $real_total_attandence, $emp_id, $currentMonth, $currentYear);
                    $earn_leave_present = $real_total_attandence + $week_leave;
                    $monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $earn_leave_present, $unit_id);

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

                    $workingDaysCache[$cacheKey] = $result['total_working_days'] ?? 0;
                }

                $totalWorkingDays = $workingDaysCache[$cacheKey];

                  if ($leave_date < $date_of_joining) {
                    $skipMessages[] = [
                        "employee" => $employee,
                        "application" => $on_duty_id,
                        "date" => date('d-m-Y', strtotime($date)),
                        "reason" => "Leave cannot be approved before employee joining date (" . date('d-m-Y', strtotime($date_of_joining)) . ")"
                    ];
                    continue;
                }

                if ($totalDaysInMonth <= $totalWorkingDays) {
                    $skipMessages[] = [
                        "employee"    => $emp_data['emp_code']." - ".$emp_data['first_name'],
                        "date"        => date('d-m-Y', strtotime($date)),
                        "reason"      => "Leave cannot be approved because the employee has already completed all working days ($totalWorkingDays) for ".date('F Y', strtotime($date))
                    ];
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
                    $leave_balance = 0;
                } elseif ($leave_type == 'CO') {
                    $leave_balance = $obj->getEmpCoffLeave($emp_id, $sessionid,$month,$year);
                } 

                // ---- Read from cached arrays instead of querying per date ----
                $attendance_status  = $attendanceMapCache[$cacheKey][$date] ?? '';
                
                $fullLeaveStatuses = [
                    'Weekly Leave',
                    'Earning Leave',
                    'Leave',
                    'C Off',
                    'Extra Off'
                ];

                if (in_array(trim($attendance_status), $fullLeaveStatuses)) {
                    $skipMessages[] = [
                        "employee" => $employee,
                        "application" => $on_duty_id,
                        "date" => date('d-m-Y', strtotime($date)),
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
                            "employee" => $employee,
                            "application" => $on_duty_id,
                            "date" => date('d-m-Y', strtotime($date)),
                            "reason" => "Full Day leave not allowed because Half Day attendance already exists"
                        ];
                        continue;
                    } 
                }

               // $attendanceLogExists = isset($attendanceLogCache[$cacheKey][$date]) ? 1 : 0;
                // if ($attendanceLogExists > 0 && !$isHalfAttendance) {
                //     $skipMessages[] = [
                //         "employee" => $employee,
                //         "application" => $on_duty_id,
                //         "date" => date('d-m-Y', strtotime($date)),
                //         "reason" => "Attendance punch already exists for this date."
                //     ];
                //     continue;
                // }


                $required_balance = ($leave_day == 'FHD' || $leave_day == 'SHD') ? 0.5 : 1;

                if ($leave_balance <= 0) {
                    $skipMessages[] = [
                        "employee" => $employee,
                        "application" => $on_duty_id,
                        "date" => date('d-m-Y', strtotime($date)),
                        "reason" => "Insufficient Leave Balance"
                    ]; 
                    continue; 
                }

                if ($required_balance == 1 && $leave_balance < 1) {
                    $skipMessages[] = [
                        "employee" => $employee,
                        "application" => $on_duty_id,
                        "date" => date('d-m-Y', strtotime($date)),
                        "reason" => "Full day leave balance not available"
                    ];
                    continue;
                }

                if ($required_balance == 0.5 && $leave_balance < 0.5) {
                    $skipMessages[] = [
                        "employee" => $employee,
                        "application" => $on_duty_id,
                        "date" => date('d-m-Y', strtotime($date)),
                        "reason" => "Half day leave balance not available"
                    ];
                    continue;
                }
               

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

                    // ---- Keep the in-memory cache in sync so subsequent rows for the
                    //      same employee/month see this newly-approved date too ----
                    $attendanceMapCache[$cacheKey][$date] = $form_date['attendance_status'] ?? '';
                } else {
                    $skipMessages[] = [
                        "employee" => $employee,
                        "application" => $on_duty_id,
                        "date" => date('d-m-Y', strtotime($date)),
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
    } 

    $response = [
        "totalApplication" => $totalApplication,
        "totalLeaves"      => $totalLeaves,
        "approvedCount"    => $approvedCount,
        "rejectedCount"    => $rejectedCount,
        "pendingCount"     => $pendingCount,
        "failedCount"      => count($skipMessages),
        "errors"           => $skipMessages,
        "debug"           => $debug
    ];

   echo json_encode($response);
die;
}