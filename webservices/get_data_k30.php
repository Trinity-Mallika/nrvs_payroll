<?php
ini_set('max_execution_time', 300);
include("../action.php");

$sessionid = $obj->getvalfield("m_session", "sessionid", "status=1");

$inputJSON = file_get_contents('php://input');
// 6728322120001025 out
// 6728322120001144 in
$inputJSON = '{"EmployeeID":"2213","SerialNo":"6728322120001144","AttendanceDate":"2026-05-03","PunchTime":"2026-05-03T06:00:01"}';
//$inputJSON = '{"EmployeeID":"1031","SerialNo":"6728422090000160","AttendanceDate":"2026-04-01","PunchTime":"2026-04-01T14:35:36"}';

// if ($inputJSON != "") {

// 	// Convert JSON to array
// 	$data = json_decode($inputJSON, true);

// 	// Pretty JSON format
// 	$formattedJSON = json_encode($data, JSON_PRETTY_PRINT);

// 	// Get current time with milliseconds
// 	$microtime = microtime(true);
// 	$datetime = date('Y-m-d H:i:s.') . sprintf("%03d", ($microtime - floor($microtime)) * 1000);

// 	// Open file in append mode
// 	$myfile = fopen("testfile.txt", "a");

// 	// Write data
// 	fwrite($myfile, "============================\n");
// 	fwrite($myfile, "Received At: " . $datetime . "\n");

// 	// Optional: PunchTime bhi show karo (important for debugging)
// 	if (isset($data['PunchTime'])) {
// 		fwrite($myfile, "PunchTime: " . $data['PunchTime'] . "\n");
// 	}
// 	fwrite($myfile, $formattedJSON . "\n\n");
// 	fclose($myfile);
// }
$arr = json_decode($inputJSON, true);

$device = !empty($arr['SerialNo']) ? $arr['SerialNo'] : $arr['DeviceID'];
$punchTime = date("Y-m-d H:i:s", strtotime($arr['PunchTime']));

// $IN_MACHINE  = '6728322120001025';
// $OUT_MACHINE = '6728322120001144';

// $mode = ($device == $IN_MACHINE) ? 'IN' : (($device == $OUT_MACHINE) ? 'OUT' : 'AUTO');

$machine_userid = $arr['EmployeeID'];
$attendance_stamp = $punchTime;
$createdate = date('Y-m-d');
$mode = $obj->getvalfield("att_machine_master", "type", "machine_id='$device'");

$emp_unit = $obj->getvalfield("att_machine_master", "unit_id", "machine_id='$device'");
if ($emp_unit > 0) {
    $emp_id = $obj->getvalfield("employee_master", "emp_id", "biomatric_id='$machine_userid' and unit_id='$emp_unit'");
} else {
    $emp_id = $obj->getvalfield("employee_master", "emp_id", "biomatric_id='$machine_userid'");
}

if ($emp_id > 0) {

    $attendance_date = date('Y-m-d', strtotime($attendance_stamp));
    $attendance_time = date('H:i:s', strtotime($attendance_stamp));
    $month = date('m', strtotime($attendance_stamp));
    $year  = date('Y', strtotime($attendance_stamp));

    $emp_data = $obj->select_record("employee_master", ['emp_id' => $emp_id]);

    $unit_id = $emp_data['unit_id'];
    $department_id = $emp_data['department_id'];
    $Office_working_hour  = $emp_data['shift_id'];

    $sql = $sql = "SELECT *
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
            WHEN '$attendance_time' BETWEEN in_time 
                 AND ADDTIME(in_time, min_working_hrs)
            THEN 0   

            WHEN in_time > '$attendance_time'
            THEN 1  

            ELSE 2  
        END AS priority,

        ABS(TIME_TO_SEC(TIMEDIFF(in_time, '$attendance_time'))) AS time_diff

    FROM shift_master
    WHERE unit_id = '$unit_id'
    AND working_hour = '$Office_working_hour'

) AS shifts

ORDER BY 
    priority ASC,    
    in_time ASC,   
    time_diff ASC  

LIMIT 1;";
    $res = $obj->executequery($sql);
    $shift = $res[0];
    $shift_id     = $shift['shift_id'];
    $shift_in  = $shift['in_time'];
    //$shift_out = $shift['out_time'];
    $in_margin  = $shift['grace_time_in'];
    //$out_margin = $shift['grace_time_out'];


    // $prev_att_id = $obj->getvalfield(
    //     "attendance_entry",
    //     "attendance_id",
    //     "emp_id='$emp_id' 
    //      ORDER BY attendance_id DESC LIMIT 1"
    // );


    // $prev_att_id = $obj->getvalfield( "attendance_entry", "attendance_id", "emp_id='$emp_id' AND attendance_date <= '$attendance_date' AND (outtime IS NULL OR outtime = '') ORDER BY attendance_date DESC, attendance_id DESC LIMIT 1" );

    $prev_att_id = $obj->getvalfield(
        "attendance_entry",
        "attendance_id",
        "emp_id='$emp_id' AND entry_type='machine'
     AND (attendance_date < '$attendance_date' 
          OR (attendance_date = '$attendance_date' AND intime <= '$attendance_time'))
     ORDER BY attendance_date DESC, intime DESC
     LIMIT 1"
    );

    /* ================= IN LOGIC ================= */

    if ($mode == 'IN') {

        $today_entry = $obj->getvalfield(
            "attendance_entry",
            "attendance_id",
            "emp_id='$emp_id' 
         AND attendance_date='$attendance_date'
         ORDER BY attendance_id ASC LIMIT 1"
        );

        if (!$today_entry) {
            $late_in = $obj->calculateLateIn($shift_in, $attendance_time, $in_margin);
            $form_data = [
                'emp_id' => $emp_id,
                'intime' => $attendance_time,
                'attendance_stamp' => $attendance_stamp,
                'machine_userid' => $machine_userid,
                'department_id' => $department_id,
                'attendance_date' => $attendance_date,
                'attendance_status' => 'Incomplete',
                'prev_attendance_status' => 'Incomplete',
                'machineid' => $device,
                'late_in' => $late_in,
                'shift_id' => $shift_id,
                'in_status' => 'IN',
                'entry_type' => 'machine',
                'month' => $month,
                'year' => $year,
                'unit_id' => $unit_id,
                'sessionid' => $sessionid,
                'createtime' => $attendance_time,
                'createdate' => $createdate
            ];

            $obj->insert_record("attendance_entry", $form_data);
        }
    }
    if ($mode == 'OUT') {

        $prev_data = $obj->select_record("attendance_entry", ['attendance_id' => $prev_att_id]);
        $prev_intime = $prev_data['intime'] ?? '';
        $prev_attendance_date = $prev_data['attendance_date'] ?? '';
        $prev_shift_id = $prev_data['shift_id'] ?? '';
        $inDateTime = strtotime($prev_attendance_date . ' ' . $prev_intime);
        //$inDateTime = strtotime($prev_data['attendance_date'] . ' ' . $prev_data['intime']);
        $outDateTime = strtotime($attendance_stamp);
        $emp_shift_data =   $obj->select_record("shift_master", ['shift_id' => $prev_shift_id]);
        $emp_in_margin = $emp_shift_data['grace_time_in'] ?? '';
        $emp_out_margin = $emp_shift_data['grace_time_out'] ?? '';
        $shift_working_half_hrs = $emp_shift_data['min_working_hrs'] ?? '';
        $emp_shift_out = $emp_shift_data['out_time'] ?? '';
        $shift_working_hrs = $emp_shift_data['max_working_hrs'] ?? '';


        // ❌ OUT must be after IN
        if ($outDateTime <= $inDateTime) {
            // only log
        } else {

            /* ================= MAX OUT TIME ================= */
            $base_date = date('Y-m-d', strtotime($prev_data['attendance_date'] . ' +1 day'));
            $shift_row = $obj->executequery("select in_time from shift_master where working_hour='$Office_working_hour' order by in_time asc limit 1");


            $morning_in = $shift_row[0]['in_time'] ?? '06:00:00';


            $morning_timestamp = strtotime($base_date . ' ' . $morning_in);
            // extra buffer (4 hrs)
            $extra_seconds = 4 * 3600;

            // ✅ FINAL MAX OUT TIME
            $max_out_time = $morning_timestamp + $extra_seconds;


            /* ================= CHECK WITHIN LIMIT ================= */

            if ($outDateTime <= $max_out_time) {

                // always keep LAST OUT
                $existing_out = $prev_data['outtime']
                    ? strtotime($prev_data['attendance_date'] . ' ' . $prev_data['outtime'])
                    : 0;

                if ($outDateTime > $existing_out) {

                    $working_hours = round(($outDateTime - $inDateTime) / 3600, 2);

                    $result = $obj->calculateWorkingHoursAndStatus(
                        $prev_data['attendance_date'],
                        $prev_data['intime'],
                        $attendance_stamp,
                        $shift_working_hrs,
                        $shift_working_half_hrs,
                        $emp_in_margin,
                        $emp_out_margin
                    );

                    $working_hours = $result['working_hours'];
                    $attendance_status = $result['attendance_status'];

                    $early_out = $obj->calculateEarlyOut($emp_shift_out, date('H:i:s', $outDateTime), $emp_out_margin);
                    $overtimeMinutes = $obj->calculateOvertimeTime($working_hours, $shift_working_hrs);



                    $update_data = [
                        'outtime' => date('H:i:s', $outDateTime),
                        'working_hours' => $working_hours,
                        'attendance_status' => $attendance_status,
                        'out_status' => 'OUT',
                        'machineid' => $device,
                        'overtime' => $overtimeMinutes,
                        'early_out' => $early_out,
                        'lastupdated' => date('Y-m-d H:i:s')
                    ];

                    $obj->update_record(
                        "attendance_entry",
                        ['attendance_id' => $prev_att_id],
                        $update_data
                    );
                }
            }
        }
    }

    /* ================= LOG INSERT ================= */
    $log_data = [
        'emp_id' => $emp_id,
        'department_id' => $department_id,
        'machine_userid' => $machine_userid,
        'attendance_stamp' => $attendance_stamp,
        'attendance_date' => $attendance_date,
        'intime' => $attendance_time,
        'in_status' => $mode,
        'machineid' => $device,
        'month' => $month,
        'year' => $year,
        'entry_type' => 'machine',
        'unit_id' => $unit_id,
        'sessionid' => $sessionid,
        'createtime' => $attendance_time,
        'createdate' => $createdate
    ];

    $obj->insert_record("attendance_log", $log_data);
}
