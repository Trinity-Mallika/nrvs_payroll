<?php ini_set('max_execution_time', 300);
include("../action.php");

$sessionid = $obj->getvalfield("m_session", "sessionid", "status=1");

$inputJSON = file_get_contents('php://input');

if ($inputJSON != "") {
	$myfile = fopen("testfile.txt", "w");
	fwrite($myfile, $inputJSON);
	fclose($myfile);
	//die;
}


//$inputJSON = '{"EmployeeID":"2213","SerialNo":"6728322120001144","AttendanceDate":"2026-04-01","PunchTime":"2026-04-01T07:08:18"}';

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

			//$Office_working_hour = '12:00:00';
			$previousDate = date('Y-m-d', strtotime($attendance_date . ' -1 day'));
			$prev_attendance_id = $obj->getvalfield(
				"attendance_entry",
				"attendance_id",
				"emp_id='$emp_id' ORDER BY attendance_id DESC limit 1"
			);

			//$prev_att_data = $obj->select_record("attendance_entry", ['attendance_id' => $prev_attendance_id]);
			$prev_att_data = $obj->select_record(
				"attendance_entry",
				[
					'emp_id' => $emp_id,
					'attendance_date' => $previousDate
				]
			);
			$forceOutPunch = false;

			if ($prev_att_data) {

				$prev_shift_id = $prev_att_data['shift_id'];
				$prev_outtime  = $prev_att_data['outtime'];

				$prev_shift = $obj->select_record(
					"shift_master",
					['shift_id' => $prev_shift_id]
				);

				if ($prev_shift && $prev_shift['is_cross_day'] == 1 && empty($prev_outtime)) {

					$prev_shift_out = $prev_shift['out_time'];
					$allowedOutEnd = date(
						'H:i:s',
						strtotime($prev_shift_out . ' +2 hours')
					);

					if ($attendance_time <= $allowedOutEnd) {
						$forceOutPunch = true;
						$attendance_date = $previousDate;
					}
				}
			}

			//$sql = "SELECT shift_id, in_time, out_time, is_cross_day,grace_time_in,grace_time_out,ABS(TIME_TO_SEC(TIMEDIFF(in_time, '$attendance_time'))) AS time_diff FROM shift_master WHERE unit_id = '$unit_id' AND working_hour = '$Office_working_hour' ORDER BY time_diff ASC LIMIT 1";

			// 			$sql = "SELECT *
			// FROM (
			//     SELECT 
			//         shift_id,
			//         in_time,
			//         out_time,
			//         is_cross_day,
			// 		grace_time_in,
			// 		grace_time_out,min_working_hrs,

			//         ADDTIME(in_time, min_working_hrs) AS min_end_time,
			//         ADDTIME(ADDTIME(in_time, min_working_hrs), '02:00:00') AS max_allowed_time,

			//         CASE 
			//             WHEN '$attendance_time' BETWEEN in_time 
			//                  AND ADDTIME(ADDTIME(in_time, min_working_hrs), '02:00:00')
			//             THEN 0   

			//             WHEN in_time > '$attendance_time'
			//             THEN 1  

			//             ELSE 2  
			//         END AS priority,

			//         ABS(TIME_TO_SEC(TIMEDIFF(in_time, '$attendance_time'))) AS time_diff

			//     FROM shift_master
			//     WHERE unit_id = '1'
			//     AND working_hour = '$Office_working_hour'

			// ) AS shifts

			// ORDER BY 
			//     priority ASC,    
			//     in_time ASC,   
			//     time_diff ASC  

			// LIMIT 1;";

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
    WHERE unit_id = '1'
    AND working_hour = '$Office_working_hour'

) AS shifts

ORDER BY 
    priority ASC,    
    in_time ASC,   
    time_diff ASC  

LIMIT 1;";

			$res = $obj->executequery($sql);
			// if (empty($res)) {
			// 	return;
			// }
			$shift = $res[0];

			$shift_id     = $shift['shift_id'];

			$is_cross_day     = $shift['is_cross_day'];
			$shift_in  = $shift['in_time'];
			$shift_out = $shift['out_time'];
			$in_marginn  = $shift['grace_time_in'];
			$out_marginn = $shift['grace_time_out'];

			if ($emp_id > 0) {
				$last_log_id = $obj->getvalfield(
					"attendance_log",
					"attendance_log_id",
					"emp_id='$emp_id' ORDER BY attendance_log_id DESC LIMIT 1"
				);

				$last_log_data = $obj->select_record(
					"attendance_log",
					['attendance_log_id' => $last_log_id]
				);

				$last_outtime   = $last_log_data['outtime'] ?? '';
				$last_intime   = $last_log_data['intime'] ?? '';
				$last_att_date  = $last_log_data['attendance_date'] ?? '';
				$last_shift_id  = $last_log_data['shift_id'] ?? '';

				$last_shift_data = $obj->select_record(
					"shift_master",
					['shift_id' => $last_shift_id]
				);

				$last_shift_out   = $last_shift_data['out_time'] ?? '';
				$last_is_cross_day = $last_shift_data['is_cross_day'] ?? 0;

				/* SHIFT OUT TIMESTAMP */
				$shiftOut = strtotime($last_att_date . " " . $last_shift_out);

				if ($last_is_cross_day == 1) {
					$shiftOut = strtotime($last_att_date . " " . $last_shift_out . " +1 day");
				}

				$currentPunch = strtotime($attendance_stamp);
				$diffMinutes  = ($currentPunch - $shiftOut) / 60;


				/* CHECK OPEN LOG */
				$open_log_id = $obj->getvalfield(
					"attendance_log",
					"attendance_log_id",
					"emp_id='$emp_id' AND outtime IS NULL ORDER BY attendance_log_id DESC"
				);


				/* ===== VALIDATE OLD OPEN LOG ===== */
				if ($open_log_id != "") {

					$open_log_data = $obj->select_record(
						"attendance_log",
						['attendance_log_id' => $open_log_id]
					);

					$open_log_date = $open_log_data['attendance_date'];
					$open_shift_id = $open_log_data['shift_id'];

					$open_shift = $obj->select_record(
						"shift_master",
						['shift_id' => $open_shift_id]
					);

					if ($open_shift && $open_shift['is_cross_day'] == 1) {

						$open_shift_out = $open_shift['out_time'];

						$allowedOutEnd = date(
							'H:i:s',
							strtotime($open_shift_out . ' +2 hours')
						);

						$nextDay = date('Y-m-d', strtotime($open_log_date . ' +1 day'));

						if (
							!($punch_date == $nextDay && $attendance_time <= $allowedOutEnd)
						) {
							/* OLD OPEN LOG IGNORE */
							$open_log_id = "";
						} else {
							$attendance_date = $open_log_date;
						}
					} else {

						/* if normal shift older than 1 day ignore */
						$dateDiff = (strtotime($punch_date) - strtotime($open_log_date)) / 86400;

						if ($dateDiff > 1) {
							$open_log_id = "";
						}
					}
				}
				/* ================= IN PUNCH ================= */

				//$count = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$year' and month ='$month'");
				$attendance_id = $obj->getvalfield(
					"attendance_entry",
					"attendance_id",
					"emp_id='$emp_id' and attendance_date='$attendance_date' and year='$year' and month='$month'"
				);
				// echo $attendance_id;
				// die;
				$att_shift_id = $obj->getvalfield("attendance_entry", "shift_id", "attendance_id='$attendance_id'") ?? $shift_id;

				/* LAST ATTENDANCE ENTRY */
				$last_entry_id = $obj->getvalfield(
					"attendance_entry",
					"attendance_id",
					"emp_id='$emp_id' ORDER BY attendance_id DESC LIMIT 1"
				);

				$shift_data = $obj->select_record("shift_master", array('shift_id' => $att_shift_id));
				$is_cross_day = $shift_data['is_cross_day'];
				$shift_in     = $shift_data['in_time'];
				$shift_out    = $shift_data['out_time'];
				$in_margin    = $shift_data['grace_time_in'];
				$out_margin    = $shift_data['grace_time_out'];
				//$shift_working_hrs = $shift_data['total_working_hour'];
				$shift_working_hrs = $shift_data['working_hour'];
				$shift_working_half_hrs = $shift_data['min_working_hrs'];

				//$attendance_id = $obj->getvalfield("attendance_entry", "attendance_id", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$year' and month ='$month' ");
				$intime = $obj->getvalfield(
					"attendance_entry",
					"intime",
					"attendance_id='$attendance_id'"
				);

				$result = $obj->calculateWorkingHoursAndStatus(
					$attendance_date,
					$intime,
					$attendance_stamp,
					$shift_working_hrs,
					$shift_working_half_hrs,
					$in_margin,
					$out_margin
				);

				$working_hours = $result['working_hours'];
				$attendance_status = $result['attendance_status'];
				$attheadid = $result['attheadid'];
				//echo "working_hours: " . $working_hours . "<br>";
				// list($wh, $wm, $ws) = explode(':', $shift_working_hrs);
				// $officeWorkingMinutes = ($wh * 60) + $wm;

				// $start = new DateTime($attendance_date . ' ' . $intime);
				// $end   = new DateTime($attendance_stamp);
				// if ($end < $start) {
				// 	$end->modify('+1 day');
				// }
				// $interval = $start->diff($end);
				// $totalSeconds =
				// 	($interval->days * 24 * 60 * 60) +
				// 	($interval->h * 60 * 60) +
				// 	($interval->i * 60) +
				// 	$interval->s;

				// $working_hours = gmdate('H:i:s', $totalSeconds);
				// // Get the total hours between the two times

				// $total_hours = ($interval->h) + ($interval->i / 60);
				// $total_sal = $emp_salary * $total_hours;


				// $workedMinutes = ($interval->h * 60) + $interval->i;

				// $totalMarginMinutes = $in_margin + $out_margin;
				// $minimumRequiredMinutes = $officeWorkingMinutes - $totalMarginMinutes;

				// if ($workedMinutes < $minimumRequiredMinutes) {
				// 	$attendance_status = "Half Day";
				// 	$attheadid = 3;
				// } else {
				// 	$attendance_status = "Present";
				// 	$attheadid = 1;
				// }

				if ($forceOutPunch) {
					$count = 1;
					$attendance_date = $previousDate;
				}
				// if ($count == 0) {
				//if ($open_log_id == "") {

				if ($attendance_id == "") {
					if ($last_outtime != "" && $diffMinutes < 30) {
						// Treat as OUT of previous record
						$early_out = $obj->calculateEarlyOut($shift_out, $attendance_time, $out_margin);
						$overtimeMinutes = $obj->calculateOvertimeTime($working_hours, $shift_working_hrs);
						$update_log = array(
							'outtime' => $attendance_time,
							'early_out' => $early_out,
							'working_hours' => $working_hours,
							'overtime' => $overtimeMinutes
						);
						$where_log = array('attendance_id' => $last_entry_id);
						$obj->update_record("attendance_entry", $where_log, $update_log);
					} else {
						$late_in = $obj->calculateLateIn($shift_in, $attendance_time, $in_margin);
						$status = 'IN';
						$form_data1 = array(
							'emp_id' => $emp_id,
							'shift_id' => $shift_id,
							'department_id' => $department_id,
							'machine_userid' => $machine_userid,
							'intime' => $attendance_time,
							'attendance_stamp' => $attendance_stamp,
							'attendance_date' => $attendance_date,
							'late_in' => $late_in,
							'attendanceby' => '1',
							'verifyiedby' => $verifyiedby,
							'createdate' => $createdate,
							'machineid' => $deviceid,
							'entry_type' => 'machine',
							'in_status' => $status,
							'month' => $month,
							'year' => $year,
							'unit_id' => $unit_id,
							'basic_salary' => $emp_salary,
							'sessionid' => $sessionid,
							'createtime' => $attendance_time,
							'attendance_status' => 'Incomplete',
							'prev_attendance_status' => 'Incomplete'
						);
						$obj->insert_record("attendance_entry", $form_data1);
					}
				} else {
					$status = 'OUT';
					$early_out = $obj->calculateEarlyOut($shift_out, $attendance_time, $out_margin);
					//$overtimeMinutes = $obj->calculateOvertimeMinutes($intime, $attendance_time,$working_hours, $Office_working_hour);
					$overtimeMinutes = $obj->calculateOvertimeTime($working_hours, $shift_working_hrs);
					$form_data1 = array(
						'outtime' => $attendance_time,
						'sessionid' => $sessionid,
						'machineid' => $deviceid,
						'department_id' => $department_id,
						'working_hours' => $working_hours,
						'early_out' => $early_out,
						'lastupdated' => date('Y-m-d'),
						'entry_type_out' => 'machine',
						//'basic_salary' => $total_sal,
						'basic_salary' => $emp_salary,
						'attendance_status' => $attendance_status,
						'out_status' => $status,
						'overtime' => $overtimeMinutes,
						'machine_userid' => $machine_userid
					);
					$where = array('attendance_id' => $attendance_id);
					$obj->update_record("attendance_entry", $where, $form_data1);
				}


				//log report

				if ($open_log_id == "") {
					$late_in = $obj->calculateLateIn($shift_in, $attendance_time, $in_marginn);
					if (empty($shift)) {
						continue;
					}
					$log_data = array(
						'emp_id' => $emp_id,
						'shift_id' => $shift_id,
						'department_id' => $department_id,
						'machine_userid' => $machine_userid,
						'intime' => $attendance_time,
						'attendance_stamp' => $attendance_stamp,
						'attendance_date' => $attendance_date,
						'late_in' => $late_in,
						'attendance_status' => 'Incomplete',
						'prev_attendance_status' => 'Incomplete',
						'attendanceby' => '1',
						'verifyiedby' => $verifyiedby,
						'createdate' => $createdate,
						'machineid' => $deviceid,
						'entry_type' => 'machine',
						'month' => $month,
						'year' => $year,
						'unit_id' => $unit_id,
						'sessionid' => $sessionid
					);

					$obj->insert_record("attendance_log", $log_data);
					//}
				} else {
					/* ================= OUT PUNCH ================= */

					$intime_log = $obj->getvalfield(
						"attendance_log",
						"intime",
						"attendance_log_id='$open_log_id'"
					);

					$log_shift_id = $obj->getvalfield(
						"attendance_log",
						"shift_id",
						"attendance_log_id='$open_log_id'"
					) ?? $shift_id;

					$shift_data = $obj->select_record(
						"shift_master",
						['shift_id' => $log_shift_id]
					);

					$in_margin  = $shift_data['grace_time_in'];
					$out_margin = $shift_data['grace_time_out'];
					$Office_working_hour_log = $shift_data['working_hour'];
					$shift_out    = $shift_data['out_time'];
					$shift_working_half_hrs_log = $shift_data['min_working_hrs'];

					$early_out = $obj->calculateEarlyOut($shift_out, $attendance_time, $out_margin);
					// $start = new DateTime($attendance_date . ' ' . $intime_log);
					// $end   = new DateTime($attendance_stamp);

					$result_log = $obj->calculateWorkingHoursAndStatus(
						$attendance_date,
						$intime_log,
						$attendance_stamp,
						$Office_working_hour_log,
						$shift_working_half_hrs_log,
						$in_margin,
						$out_margin
					);

					$working_hours_log = $result_log['working_hours'];
					$attendance_status_log = $result_log['attendance_status'];
					$attheadid = $result_log['attheadid'];

					// if ($end < $start) {
					// 	$end->modify('+1 day');
					// }

					// $interval = $start->diff($end);

					// $totalSeconds =
					// 	($interval->days * 86400) +
					// 	($interval->h * 3600) +
					// 	($interval->i * 60) +
					// 	$interval->s;

					// $working_hours_log = gmdate('H:i:s', $totalSeconds);

					// $workedMinutes = ($interval->h * 60) + $interval->i;

					// list($wh, $wm, $ws) = explode(':', $Office_working_hour);
					// $officeWorkingMinutes = ($wh * 60) + $wm;

					// $minimumRequiredMinutes =
					// 	$officeWorkingMinutes - ($in_margin + $out_margin);

					// if ($workedMinutes < $minimumRequiredMinutes) {
					// 	$attendance_status_log = "Half Day";
					// } else {
					// 	$attendance_status_log = "Present";
					// }

					$update_log = array(
						'outtime' => $attendance_time,
						'machineid' => $deviceid,
						'early_out' => $early_out,
						'working_hours' => $working_hours_log,
						'attendance_status' => $attendance_status_log,
						'lastupdated' => date('Y-m-d')
					);
					// print_r($update_log);
					// die;
					$where_log = array(
						'attendance_log_id' => $open_log_id
					);
					$obj->update_record("attendance_log", $where_log, $update_log);
				}
			}
		}
	}
}
