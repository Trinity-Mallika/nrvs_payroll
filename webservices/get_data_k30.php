<?php ini_set('max_execution_time', 300);
include("../action.php");

$sessionid = $obj->getvalfield("m_session", "sessionid", "status=1");

$inputJSON = file_get_contents('php://input');

$inputJSON = '{"data":["1#2026-01-05 08:00:00#5#1##0#0#",""],"SN":"CGCA201263139"}';

if ($inputJSON != "") {
	$myfile = fopen("testfile.txt", "w");
	fwrite($myfile, $inputJSON);
	fclose($myfile);
	//die;
}

if ($inputJSON != "") {

	echo "<pre>";
	$input = json_decode($inputJSON, TRUE); //convert JSON into array


	$data = $input['data'];
	//$table = $input['table'];
	$SN = $input['SN'];
	$machineid = $SN;
	$createdate = date('Y-m-d h:i:s');

	// print_r($machineid);
	// die;
	$emp_id = 0;


	foreach ($data as $value) {
		echo $value;
		$datstr = explode('#', $value);
		print_r($datstr);

		if (isset($datstr[0]))
			$machine_userid = $datstr[0];
		else
			$machine_userid = "";

		if (isset($datstr[1]))
			$attendance_stamp = $datstr[1];
		else
			$attendance_stamp = "";

		if (isset($datstr[2]))
			$data1 = $datstr[2];
		else
			$data1 = "";

		if (isset($datstr[3]))
			$verifyiedby = $datstr[3];
		else
			$verifyiedby = "";
		//sprintf($machine_userid);die;


		$emp_id = $obj->getvalfield("employee_master", "emp_id", "biomatric_id='$machine_userid'");


		if ($machine_userid != '') {
			$punch_date = date('Y-m-d', strtotime($attendance_stamp));
			$punch_time = date('H:i:s', strtotime($attendance_stamp));

			$attendance_date = $punch_date;
			// $attendance_date = date('Y-m-d', strtotime($attendance_stamp));

			$attendance_time = date('H:i:s', strtotime($attendance_stamp));
			$createdate = date('Y-m-d');
			$year = date('Y', strtotime($attendance_stamp));
			$month = date('m', strtotime($attendance_stamp));

			$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

			$emp_salary =  $obj->getvalfield("employee_master", "basic_salary", "emp_id='$emp_id'");
			$department_id =  $obj->getvalfield("employee_master", "department_id", "emp_id='$emp_id'");
			$unit_id =  $obj->getvalfield("employee_master", "unit_id", "emp_id='$emp_id'");

			//$Office_working_hour =  $obj->getvalfield("employee_master", "shift_id", "emp_id='$emp_id'");

			$Office_working_hour = '12:00:00';

			$sql = "SELECT shift_id, in_time, out_time, is_cross_day,grace_time_in,grace_time_out,ABS(TIME_TO_SEC(TIMEDIFF(in_time, '$attendance_time'))) AS time_diff FROM shift_master WHERE unit_id = '$unit_id' AND working_hour = '$Office_working_hour' ORDER BY time_diff ASC LIMIT 1";
			$res = $obj->executequery($sql);
			$shift = $res[0];

			$shift_id     = $shift['shift_id'];
			$is_cross_day     = $shift['is_cross_day'];
			$out_time     = $shift['out_time'];

			// echo  $shift_id;
			// die;

			if ($emp_id > 0) {


				if ($is_cross_day == 1 && $punch_time < $out_time) {
					// OUT punch belongs to previous day
					$attendance_date = date('Y-m-d', strtotime($punch_date . ' -1 day'));
				}

				$count = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$year' and month ='$month'");

				if ($count == 0) {
					// echo 'ji';
					// die;

					$status = 'IN';
					$form_data1 = array(
						'emp_id' => $emp_id,
						'shift_id' => $shift_id,
						'department_id' => $department_id,
						'machine_userid' => $machine_userid,
						'intime' => $attendance_time,
						'attendance_stamp' => $attendance_stamp,
						'attendance_date' => $attendance_date,
						'attendanceby' => '1',
						'verifyiedby' => $verifyiedby,
						'createdate' => $createdate,
						'machineid' => $SN,
						'entry_type' => 'machine',
						'in_status' => $status,
						'month' => $month,
						'year' => $year,
						'unit_id' => $unit_id,
						'basic_salary' => $emp_salary,
						'createtime' => $attendance_time,
						'attendance_status' => 'Incomplete'
					);
					$obj->insert_record("attendance_entry", $form_data1);
				} else {
					$status = 'OUT';

					$emp_shift_id = $obj->getvalfield(
						"attendance_entry",
						"shift_id",
						"emp_id='$emp_id' and year='$year' and month ='$month' order by attendance_id desc"
					);

					$shift_data = $obj->select_record("shift_master", array('shift_id' => $emp_shift_id));
					$is_cross_day = $shift_data['is_cross_day'];
					$shift_in     = $shift_data['in_time'];
					$shift_out    = $shift_data['out_time'];
					$in_margin    = $shift_data['grace_time_in'];
					$out_margin    = $shift_data['grace_time_out'];


					$attendance_id = $obj->getvalfield("attendance_entry", "attendance_id", "emp_id='$emp_id' and attendance_date='$attendance_date' and year='$year' and month ='$month' ");
					$intime = $obj->getvalfield(
						"attendance_entry",
						"intime",
						"attendance_id='$attendance_id'"
					);

					list($wh, $wm, $ws) = explode(':', $Office_working_hour);
					$officeWorkingMinutes = ($wh * 60) + $wm;

					$start = new DateTime($attendance_date . ' ' . $intime);
					$end   = new DateTime($attendance_stamp);
					if ($end < $start) {
						$end->modify('+1 day');
					}
					$interval = $start->diff($end);
					$totalSeconds =
						($interval->days * 24 * 60 * 60) +
						($interval->h * 60 * 60) +
						($interval->i * 60) +
						$interval->s;

					$working_hours = gmdate('H:i:s', $totalSeconds);
					// Get the total hours between the two times

					$total_hours = ($interval->h) + ($interval->i / 60);
					$total_sal = $emp_salary * $total_hours;


					$workedMinutes = ($interval->h * 60) + $interval->i;

					$totalMarginMinutes = $in_margin + $out_margin;
					$minimumRequiredMinutes = $officeWorkingMinutes - $totalMarginMinutes;

					if ($workedMinutes < $minimumRequiredMinutes) {
						$attendance_status = "Half Day";
						$attheadid = 3;
					} else {
						$attendance_status = "Present";
						$attheadid = 1;
					}

				      		 
        		//$overtimeMinutes = $obj->calculateOvertimeMinutes($intime, $attendance_time,$working_hours, $Office_working_hour);
        		$overtimeMinutes = $obj->calculateOvertimeTime($working_hours, $Office_working_hour);
				// echo $overtimeMinutes;
 
					//echo $attendance_status;
					$form_data1 = array(
						'outtime' => $attendance_time,
						'sessionid' => $sessionid,
						'department_id' => $department_id,
						'working_hours' => $working_hours,
						'lastupdated' => date('Y-m-d'),
						'entry_type_out' => 'machine',
						'basic_salary' => $total_sal,
						'attendance_status' => $attendance_status,
						'out_status' => $status,
						'overtime' => $overtimeMinutes,
						'machine_userid' => $machine_userid
					);
					//print_r($form_data1);
					$where = array('attendance_id' => $attendance_id);
					//print_r($where);
					$obj->update_record("attendance_entry", $where, $form_data1);
					//echo 'byy';die;
				}
			}
		}
	}
}
