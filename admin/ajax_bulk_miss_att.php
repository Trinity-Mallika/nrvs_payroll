<?php include("../adminsession.php");
$attendance_date = $_POST['attendance_date'];
$from_status     = $_POST['from_status'];
$to_status       = $_POST['to_status'];
$remark       = $_POST['remark'];
$modal_outtime  = $_POST['modal_outtime'];
$employees         = $_POST['employees'];

function getAttendanceStatus($status)
{
    switch($status){

        case 'earn_leave':
        case 'leave':
            return 'Earning Leave';
        case 'half_earn_leave':
        case 'half_leave':
            return 'Half Earning Leave';
        case 'c_off':
            return 'C Off';

        case 'half_c_off':
            return 'Half C Off';

        case 'eoff':
            return 'Extra Off';

        case 'half_eoff':
            return 'Half Extra Off';

        case 'first_half':
        case 'second_half':
            return 'Half Day';

        case 'Present':
            return 'Present';

        case 'Absent':
            return 'Absent'; 
        case 'Incomplete':
            return 'Incomplete';

        default:
            return $status;
    }
}

if(empty($employees)){
    echo json_encode([
        'status' => 'error',
        'message' => 'No employee selected'
    ]);
    exit;
}
 

if ($from_status == 'misspunch') {
    foreach ($employees as $emp) {
        $emp_id = (int)$emp['emp_id'];
        $firstPunch = $emp['first_punch'];
        $emp_ids[] = (int)$emp['emp_id'];
        $logIds = []; 
        if(!empty($emp['log_ids'])){
            $logIds = explode(',', $emp['log_ids']);
        } 
        
        $Office_working_hour = $obj->getvalfield(
            "employee_master",
            "shift_id",
            "emp_id='$emp_id'"
        );

        $sql = "SELECT *
        FROM (
            SELECT 
                shift_id,
                in_time,
                out_time,
                is_cross_day,
                grace_time_in,
                grace_time_out,
                min_working_hrs,

                ADDTIME(in_time, min_working_hrs) AS max_allowed_time,
        
                CASE 
                    WHEN '$firstPunch' BETWEEN in_time 
                        AND ADDTIME(in_time, min_working_hrs)
                    THEN 0   

                    WHEN in_time > '$firstPunch'
                    THEN 1   

                    ELSE 2  
                END AS priority,

                ABS(TIME_TO_SEC(TIMEDIFF(in_time, '$firstPunch'))) AS time_diff

            FROM shift_master
            WHERE unit_id = '$unitid'
            AND working_hour = '$Office_working_hour'

        ) AS shifts 
        ORDER BY 
            priority ASC,    
            in_time ASC,   
            time_diff ASC   
        LIMIT 1;";

        
        $shiftData = $obj->executequery($sql);
        $shift = $shiftData[0] ?? [];

        $shift_id = $shift['shift_id']??'';
        $shift_in = $shift['in_time']??'';
        $shift_out = $shift['out_time']??"";

        $log_status = getAttendanceStatus($to_status);
        $newRowLog = [];

        $newRowLog['emp_id']            = $emp_id;
        $newRowLog['attendance_date']   = $attendance_date;
        $newRowLog['attendance_status'] = $log_status;
        $newRowLog['shift_id']          = $shift_id;
        $newRowLog['in_remark']         = $remark;
        $newRowLog['createdate']        = date('Y-m-d');
        $newRowLog['createtime']        = date('H:i:s');
        $newRowLog['createdby']         = $loginid;
        $newRowLog['unit_id']           = $unitid;
        $newRowLog['sessionid']         = $sessionid;  
        $newRowLog['month']             = date('m', strtotime($attendance_date));
        $newRowLog['year']              = date('Y', strtotime($attendance_date));
        $newRowLog['attendance_stamp']  = $attendance_date . ' ' . $shift_in;

        if ($log_status == 'Present') {

        $newRowLog['intime'] = $shift['in_time']??'';
        $newRowLog['outtime'] = $shift['out_time']??'';
        $newRowLog['working_hours'] = $shift['max_working_hrs']??'';
        }elseif ($log_status == 'Half Day') {
            $inTime  = strtotime($attendance_date . ' ' . $shift_in);
            $outTime = strtotime($attendance_date . ' ' . $shift_out);

            if ($outTime < $inTime) {
                $outTime = strtotime('+1 day', $outTime);
            }

            $halfTime = $inTime + (($outTime - $inTime) / 2);

            // Intime bhi save karo
            $newRowLog['intime'] = $shift['in_time'];

            // Half day outtime
            $newRowLog['outtime'] = date('H:i:s', $halfTime);

            // Half day working hours
            $newRowLog['working_hours'] = gmdate('H:i:s', ($outTime - $inTime) / 2);

            $newRowLog['early_out'] = "00:00:00";
            $newRowLog['overtime']  = "00:00:00";
        } elseif ($log_status == 'Absent') {
           $newRowLog['intime']='';
           $newRowLog['outtime']='';
           $newRowLog['working_hours']='';
           $newRowLog['early_out']='';
           $newRowLog['overtime']='';
        }  

        $insertRows[] = $newRowLog;
    }


   if (!empty($logIds)) {
    $obj->bulk_update_with_arr(
        'attendance_log',
        [
            'is_deleted' => 1
        ],
        [
            'attendance_log_id' => $logIds
        ]
    );
}
    if(!empty($emp_ids)){
        $obj->bulk_delete(
            'attendance_entry',
            [
                'emp_id' => $emp_ids,
                'attendance_date' => $attendance_date,
            ]
        );
    }
    if (!empty($insertRows)) { 
        $obj->bulk_insert('attendance_entry', $insertRows); 
    } 
    echo json_encode([
        'status' => 'success',
        'message' => count($insertRows) . ' employee(s) updated successfully.'
    ]);
    exit;
}else{ 
    $emp_ids=[];
    foreach ($employees as $emp) {
        $emp_ids[] = (int)$emp['emp_id'];
    }
 
$emp_ids_str = implode(',', $emp_ids);
 

$status = getAttendanceStatus($from_status);

$rows = $obj->executequery("
    SELECT *
    FROM attendance_entry
    WHERE attendance_date='$attendance_date'
    AND attendance_status='$status'
    AND unit_id='$unitid'
    AND emp_id IN ($emp_ids_str)
");

$deleteIds = [];
$insertRows = [];
$skipEmployees = [];

foreach($rows as $row){

    $emp_id = $row['emp_id'];
    $month = $row['month'];
    $year = $row['year'];   
     
    if($to_status == 'earn_leave'){
        $el = $obj->getEarningLeave($emp_id,$sessionid, $month,$year);
       
        if($el < 1){
            $skipEmployees[] = $emp_id;
            continue;
        }
    }
 
    if($to_status == 'half_earn_leave'){
        $el = $obj->getEarningLeave($emp_id,$sessionid, $month,$year);
        if($el < 0.5){
            $skipEmployees[] = $emp_id;
            continue;
        }
    }

    if($to_status == 'c_off'){

        $coff = $obj->getEmpCoffLeave(
            $emp_id,
            $sessionid,
            date('m',strtotime($attendance_date)),
            date('Y',strtotime($attendance_date))
        );

        if($coff < 1){
            $skipEmployees[] = $emp_id;
            continue;
        }
    }

    if($to_status == 'half_c_off'){

        $coff = $obj->getEmpCoffLeave(
            $emp_id,
            $sessionid,
            date('m',strtotime($attendance_date)),
            date('Y',strtotime($attendance_date))
        );

        if($coff < 0.5){
            $skipEmployees[] = $emp_id;
            continue;
        }
    }

    if($to_status == 'eoff'){

        $extra = $obj->getExtraOffBalance(
            $emp_id,
            date('m',strtotime($attendance_date)),
            date('Y',strtotime($attendance_date))
        );

        if($extra['balance'] < 1){
            $skipEmployees[] = $emp_id;
            continue;
        }
    }

    if($to_status == 'half_eoff'){

        $extra = $obj->getExtraOffBalance(
            $emp_id,
            date('m',strtotime($attendance_date)),
            date('Y',strtotime($attendance_date))
        );

        if($extra['balance'] < 0.5){
            $skipEmployees[] = $emp_id;
            continue;
        }
    }

    $deleteIds[] = $row['attendance_id'];

    $newRow = $row;

    unset($newRow['attendance_id']);
    $newRow['attendance_status'] = getAttendanceStatus($to_status);

    $newRow['createdate'] = date('Y-m-d');
    $newRow['createtime'] = date('H:i:s');
    $newRow['createdby'] = $loginid;
    $newRow['in_remark'] = $remark;

    if( !empty($modal_outtime) && ($to_status == 'Present' || $to_status == 'Incomplete' || $to_status == 'Half Day')){

    $prev_shift_id = $row['shift_id'];

    $emp_shift_data = $obj->select_record(
        "shift_master",
        ['shift_id' => $prev_shift_id]
    );

    $emp_out_margin = $emp_shift_data['grace_time_out'] ?? 0;
    $emp_shift_out  = $emp_shift_data['out_time'] ?? '';
    $shift_working_hrs = $emp_shift_data['max_working_hrs'] ?? '';

    $newRow['outtime'] = $modal_outtime;

    $inDateTime  = strtotime($attendance_date.' '.$row['intime']);
    $outDateTime = strtotime($attendance_date.' '.$modal_outtime);

    if($outDateTime < $inDateTime){
        $outDateTime = strtotime('+1 day', $outDateTime);
    }

    $diff = $outDateTime - $inDateTime;

    $working_hours = gmdate('H:i:s', $diff);

    $newRow['working_hours'] = $working_hours;

      $early_out = $obj->calculateEarlyOut(
        $emp_shift_out,
        date('H:i:s',$outDateTime),
        $emp_out_margin
    );

    $newRow['early_out'] = $early_out;

        $overtimeMinutes = $obj->calculateOvertimeTime(
        $working_hours,
        $shift_working_hrs
    );

    $newRow['overtime'] = $overtimeMinutes;
    }
    
  if(empty($modal_outtime)){
    unset($newRow['outtime']);
    unset($newRow['working_hours']);
    unset($newRow['early_out']);
    unset($newRow['overtime']);
}
    $insertRows[] = $newRow;
}
if(!empty($deleteIds)){
    $obj->bulk_delete(
        'attendance_entry',
        [
            'attendance_id' => $deleteIds
        ]
    );
}
if(!empty($insertRows)){
    $obj->bulk_insert(
        'attendance_entry',
        $insertRows
    );
}
echo json_encode([
    'status' => 'success',
    'message' => count($insertRows) .
        ' employee(s) updated successfully. ' .
        count($skipEmployees) .
        ' employee(s) skipped due to insufficient balance.'
]); 
exit;
}