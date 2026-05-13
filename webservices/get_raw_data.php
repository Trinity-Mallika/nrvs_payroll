<?php ini_set('max_execution_time', 300);
include("../action.php");

$sessionid = $obj->getvalfield("m_session", "sessionid", "status=1");

$inputJSON = file_get_contents('php://input');

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

$inputJSON = '{"EmployeeID":"4107","SerialNo":"6728322120001025","AttendanceDate":"2026-04-04","PunchTime":"2026-04-04T06:20:31"}';


$arr = json_decode($inputJSON, true);
$empId   = $arr['EmployeeID'];
$date    = $arr['AttendanceDate'];                 // 2026-01-28
$punch   = $arr['PunchTime'];                      // 2026-01-28T18:27:25
$mode    = '';
$device = !empty($arr['SerialNo']) ? $arr['SerialNo'] : (!empty($arr['DeviceID']) ? $arr['DeviceID'] : '');
$punchTime = date("Y-m-d H:i:s", strtotime($punch));  // convert T to space

$dataString = $empId . "#" . $punchTime . "#" . $device . "#" . $mode . "##0#0#";

$output = [
    "data" => [$dataString, ""]
];

if ($inputJSON != "") {

    $output_json = json_encode($output);
    $input = json_decode($output_json, true); //convert JSON into array
    $data = $input['data'];
    $createdate = date('Y-m-d h:i:s');
    $emp_id = 0;


    foreach ($data as $value) {


        $datstr = explode('#', $value);


        if (isset($datstr[0]))
            $machine_userid = $datstr[0];
        else
            $machine_userid = "";



        if (isset($datstr[1]))
            $attendance_stamp = $datstr[1];
        else
            $attendance_stamp = "";



        if (isset($datstr[2]))
            $deviceid = $datstr[2]; // DeviceID 
        else
            $deviceid = "";

        if (isset($datstr[3]))
            $verifyiedby = $datstr[3]; //
        else
            $verifyiedby = "";

        $emp_unit = $obj->getvalfield("att_machine_master", "unit_id", "machine_id='$deviceid'");
        if ($emp_unit > 0) {
            $emp_id = $obj->getvalfield("employee_master", "emp_id", "biomatric_id='$machine_userid' and unit_id='$emp_unit'");
        } else {
            $emp_id = $obj->getvalfield("employee_master", "emp_id", "biomatric_id='$machine_userid'");
        }
        if ($machine_userid != '') {
            $punch_date = date('Y-m-d', strtotime($attendance_stamp));

            $attendance_date = $punch_date;

            $attendance_time = date('H:i:s', strtotime($attendance_stamp));
            $createdate = date('Y-m-d');
            $year = date('Y', strtotime($attendance_stamp));
            $month = date('m', strtotime($attendance_stamp));

            $emp_data = $obj->select_record("employee_master", array('emp_id' => $emp_id));

            $department_id = $emp_data['department_id'];
            $unit_id = $emp_data['unit_id'];

            if ($emp_id > 0) {
                $form_data1 = array(
                    'emp_id' => $emp_id,
                    'department_id' => $department_id,
                    'machine_userid' => $machine_userid,
                    'punch_time' => $attendance_time,
                    'attendance_stamp' => $attendance_stamp,
                    'attendance_date' => $attendance_date,
                    'createdate' => $createdate,
                    'machineid' => $deviceid,
                    'entry_type' => 'machine',
                    'month' => $month,
                    'year' => $year,
                    'unit_id' => $unit_id,
                    'sessionid' => $sessionid,
                    'createtime' => $attendance_time,
                );

                $obj->insert_record("attendance_raw_data", $form_data1);
            }
        }
    }
}

 <?php ini_set('max_execution_time', 300);
include("../action.php");

$sessionid = $obj->getvalfield("m_session", "sessionid", "status=1");

$inputJSON = file_get_contents('php://input');

// 6728322120001025  in
// 6728322120001144 out

$inputJSON = '{"EmployeeID":"1304","SerialNo":"6728322120001144","AttendanceDate":"2026-04-03","PunchTime":"2026-04-03T08:12:31"}';

$arr = json_decode($inputJSON, true);
$empId   = $arr['EmployeeID'];
$date    = $arr['AttendanceDate'];                 // 2026-01-28 
$punch   = $arr['PunchTime'];                      // 2026-01-28T18:27:25 
$mode    = '';
$device = !empty($arr['SerialNo']) ? $arr['SerialNo'] : (!empty($arr['DeviceID']) ? $arr['DeviceID'] : '');
$punchTime = date("Y-m-d H:i:s", strtotime($punch));  // convert T to space

$dataString = $empId . "#" . $punchTime . "#" . $device . "#" . $mode . "##0#0#";

$output = [
	"data" => [$dataString, ""]
];

if ($inputJSON != "") {

	$output_json = json_encode($output);
	$input = json_decode($output_json, true); //convert JSON into array
	$data = $input['data'];
	$createdate = date('Y-m-d h:i:s');
	$emp_id = 0;


	foreach ($data as $value) {


		$datstr = explode('#', $value);


		if (isset($datstr[0]))
			$machine_userid = $datstr[0];
		else
			$machine_userid = "";



		if (isset($datstr[1]))
			$attendance_stamp = $datstr[1];
		else
			$attendance_stamp = "";



		if (isset($datstr[2]))
			$deviceid = $datstr[2]; // DeviceID 
		else
			$deviceid = "";

		if (isset($datstr[3]))
			$verifyiedby = $datstr[3]; //
		else
			$verifyiedby = "";

		$IN_MACHINE  = '6728322120001025';
		$OUT_MACHINE = '6728322120001144';

		if ($deviceid == $IN_MACHINE) {
			$mode = 'IN';
		} elseif ($deviceid == $OUT_MACHINE) {
			$mode = 'OUT';
		} else {
			$mode = 'AUTO'; // fallback
		}

		$emp_unit = $obj->getvalfield("att_machine_master", "unit_id", "machine_id='$deviceid'");
		if ($emp_unit > 0) {
			$emp_id = $obj->getvalfield("employee_master", "emp_id", "biomatric_id='$machine_userid' and unit_id='$emp_unit'");
		} else {
			$emp_id = $obj->getvalfield("employee_master", "emp_id", "biomatric_id='$machine_userid'");
		}


		if ($machine_userid != '') {
			$punch_date = date('Y-m-d', strtotime($attendance_stamp));
			$punch_time = date('H:i:s', strtotime($attendance_stamp));

			$attendance_date = $punch_date;

			$attendance_time = date('H:i:s', strtotime($attendance_stamp));
			$createdate = date('Y-m-d');
			$year = date('Y', strtotime($attendance_stamp));
			$month = date('m', strtotime($attendance_stamp));

			$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

			$emp_data = $obj->select_record("employee_master", array('emp_id' => $emp_id));

			$emp_salary = $emp_data['basic_salary'];
			$department_id = $emp_data['department_id'];
			$unit_id = $emp_data['unit_id'];
			$Office_working_hour = $emp_data['shift_id'];



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
			$is_cross_day     = $shift['is_cross_day'];
			$shift_in  = $shift['in_time'];
			$shift_out = $shift['out_time'];
			$in_marginn  = $shift['grace_time_in'];
			$out_marginn = $shift['grace_time_out'];
if ($emp_id > 0) {

    /* ================= GET LAST ATTENDANCE ================= */
    $last_attendance_id = $obj->getvalfield(
        "attendance_entry",
        "attendance_id",
        "emp_id='$emp_id' ORDER BY attendance_id DESC LIMIT 1"
    );

    $last_data = [];
    $last_date = "";
    $last_intime = "";
    $last_outtime = "";

    if ($last_attendance_id) {
        $last_data = $obj->select_record("attendance_entry", ['attendance_id' => $last_attendance_id]);
        $last_date = $last_data['attendance_date'];
        $last_intime = $last_data['intime'];
        $last_outtime = $last_data['outtime'];
    }

    /* ================= SHIFT ================= */
    $att_shift_id = $last_attendance_id
        ? $obj->getvalfield("attendance_entry", "shift_id", "attendance_id='$last_attendance_id'")
        : $shift_id;

    $shift_data = $obj->select_record("shift_master", ['shift_id' => $att_shift_id]);

    $shift_in     = $shift_data['in_time'];
    $shift_out    = $shift_data['out_time'];
    $in_margin    = $shift_data['grace_time_in'];
    $out_margin   = $shift_data['grace_time_out'];
    $shift_working_hrs = $shift_data['working_hour'];
    $shift_working_half_hrs = $shift_data['min_working_hrs'];

    $linked_attendance_id = 0;
    $is_cross_day_flag = 0;

    /* ================= IN LOGIC ================= */
    if ($mode == 'IN') {

        // 🚫 duplicate IN block (5 min)
        $duplicateCheck = $obj->getvalfield(
            "attendance_log",
            "count(*)",
            "emp_id='$emp_id' 
             AND in_status='IN' 
             AND TIMESTAMPDIFF(MINUTE, attendance_stamp, '$attendance_stamp') BETWEEN 0 AND 5"
        );

        if ($duplicateCheck > 0) {
            continue;
        }

        // 🔥 check same day record
        $existing_today_id = $obj->getvalfield(
            "attendance_entry",
            "attendance_id",
            "emp_id='$emp_id' AND attendance_date='$attendance_date' LIMIT 1"
        );

        if (!$existing_today_id) {

            $late_in = $obj->calculateLateIn($shift_in, $attendance_time, $in_margin);

            $form_data = [
                'emp_id' => $emp_id,
                'shift_id' => $shift_id,
                'intime' => $attendance_time,
                'attendance_date' => $attendance_date,
                'late_in' => $late_in,
                'machineid' => $deviceid,
                'entry_type' => 'machine',
                'in_status' => 'IN',
                'attendance_status' => 'Incomplete',
                'month' => $month,
                'year' => $year,
                'unit_id' => $unit_id
            ];

            $linked_attendance_id = $obj->insert_record("attendance_entry", $form_data);
        }

    }
    /* ================= OUT LOGIC ================= */
    elseif ($mode == 'OUT') {

        $outDateTime = strtotime($attendance_stamp);
        $maxHours = 26;
        $updated = false;

        /* ================= CASE 1: SAME DAY RECORD ================= */
        $same_day_att_id = $obj->getvalfield(
            "attendance_entry",
            "attendance_id",
            "emp_id='$emp_id' 
             AND attendance_date='$attendance_date'
             ORDER BY attendance_id ASC LIMIT 1"
        );

        if ($same_day_att_id) {

            $same_data = $obj->select_record("attendance_entry", ['attendance_id' => $same_day_att_id]);

            $inDateTime = strtotime($same_data['attendance_date'] . ' ' . $same_data['intime']);

            if ($outDateTime > $inDateTime) {

                $result = $obj->calculateWorkingHoursAndStatus(
                    $same_data['attendance_date'],
                    $same_data['intime'],
                    $attendance_stamp,
                    $shift_working_hrs,
                    $shift_working_half_hrs,
                    $in_margin,
                    $out_margin
                );

                $update_data = [
                    'outtime' => date('H:i:s', $outDateTime), // 🔥 overwrite OUT
                    'working_hours' => $result['working_hours'],
                    'attendance_status' => $result['attendance_status'],
                    'out_status' => 'OUT',
                    'machineid' => $deviceid,
                    'lastupdated' => date('Y-m-d H:i:s')
                ];

                $obj->update_record(
                    "attendance_entry",
                    ['attendance_id' => $same_day_att_id],
                    $update_data
                );

                $linked_attendance_id = $same_day_att_id;
                $updated = true;
            }
        }

        /* ================= CASE 2: PREVIOUS OPEN (CROSS DAY) ================= */
        if (!$updated) {

            $prev_att_id = $obj->getvalfield(
                "attendance_entry",
                "attendance_id",
                "emp_id='$emp_id' AND outtime IS NULL ORDER BY attendance_date DESC LIMIT 1"
            );

            if ($prev_att_id) {

                $prev_data = $obj->select_record("attendance_entry", ['attendance_id' => $prev_att_id]);

                $inDateTime = strtotime($prev_data['attendance_date'] . ' ' . $prev_data['intime']);

                if ($outDateTime > $inDateTime && ($outDateTime - $inDateTime) <= ($maxHours * 3600)) {

                    $result = $obj->calculateWorkingHoursAndStatus(
                        $prev_data['attendance_date'],
                        $prev_data['intime'],
                        $attendance_stamp,
                        $shift_working_hrs,
                        $shift_working_half_hrs,
                        $in_margin,
                        $out_margin
                    );

                    $update_data = [
                        'outtime' => date('H:i:s', $outDateTime),
                        'working_hours' => $result['working_hours'],
                        'attendance_status' => $result['attendance_status'],
                        'out_status' => 'OUT',
                        'machineid' => $deviceid,
                        'lastupdated' => date('Y-m-d H:i:s')
                    ];

                    $obj->update_record(
                        "attendance_entry",
                        ['attendance_id' => $prev_att_id],
                        $update_data
                    );

                    $linked_attendance_id = $prev_att_id;
                    $is_cross_day_flag = 1; // 🔥 important
                    $updated = true;
                }
            }
        }

        // ❌ CASE 3: no IN → सिर्फ log
    }

    /* ================= LOG INSERT ================= */
    $form_data_log = [
        'emp_id' => $emp_id,
        'department_id' => $department_id,
        'machine_userid' => $machine_userid,
        'intime' => $attendance_time,
        'attendance_stamp' => $attendance_stamp,
        'attendance_date' => $punch_date,
        'createdate' => $createdate,
        'in_status' => $mode,
        'machineid' => $deviceid,
        'entry_type' => 'machine',
        'month' => $month,
        'year' => $year,
        'unit_id' => $unit_id,
        'sessionid' => $sessionid,
        'createtime' => $attendance_time,

        // 🔥 NEW FIELDS
        'punch_pair_id' => $linked_attendance_id,
        'ref_attendance_id' => $is_cross_day_flag
    ];

    $obj->insert_record("attendance_log", $form_data_log);
}
		}
	}
}