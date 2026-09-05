<?php include("../adminsession.php");
$attendance_date = $_POST['attendance_date'];
$from_status     = $_POST['from_status'];
$to_status       = $_POST['to_status'];
$remark       = $_POST['remark'];
$modal_outtime  = $_POST['modal_outtime'];
$emp_ids         = $_POST['emp_ids'];

if(empty($emp_ids)){
    echo json_encode([
        'status' => 'error',
        'message' => 'No employee selected'
    ]);
    exit;
}
$emp_ids = array_map('intval', $emp_ids);
$emp_ids_str = implode(',', $emp_ids);
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

    // ===== LEAVE VALIDATION =====
     
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