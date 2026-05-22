<?php
ini_set('max_execution_time', 300);
include("../action.php");

$sessionid = $obj->getvalfield("m_session", "sessionid", "status=1");

$inputJSON = file_get_contents('php://input');
$arr = json_decode($inputJSON, true);

$empId   = $arr['EmployeeID'];
$punch   = $arr['PunchTime'];
$device  = !empty($arr['SerialNo']) ? $arr['SerialNo'] : (!empty($arr['DeviceID']) ? $arr['DeviceID'] : '');

$punchTime = date("Y-m-d H:i:s", strtotime($punch));

if ($inputJSON != "") {

	$machine_userid   = $empId;
	$attendance_stamp = $punchTime;
	$attendance_time  = date('H:i:s', strtotime($attendance_stamp));
	$punch_date       = date('Y-m-d', strtotime($attendance_stamp));

	$createdate = date('Y-m-d');
	$year  = date('Y', strtotime($attendance_stamp));
	$month = date('m', strtotime($attendance_stamp));

	/* ================= EMPLOYEE FIND ================= */
	$emp_unit = $obj->getvalfield("att_machine_master", "unit_id", "machine_id='$device'");

	if ($emp_unit > 0) {
		$emp_id = $obj->getvalfield("employee_master", "emp_id", "biomatric_id='$machine_userid' and unit_id='$emp_unit'");
	} else {
		$emp_id = $obj->getvalfield("employee_master", "emp_id", "biomatric_id='$machine_userid'");
	}

	if ($emp_id > 0) {

		$emp_data = $obj->select_record("employee_master", ['emp_id' => $emp_id]);

		$department_id = $emp_data['department_id'];
		$unit_id       = $emp_data['unit_id'];
		$emp_salary    = $emp_data['basic_salary'];
		$shift_id_emp  = $emp_data['shift_id'];

		/* ================= SHIFT ================= */
		$shift = $obj->executequery("SELECT * FROM shift_master 
            WHERE unit_id='$unit_id' AND working_hour='$shift_id_emp' LIMIT 1")[0];

		$shift_id   = $shift['shift_id'];
		$shift_in   = $shift['in_time'];
		$shift_out  = $shift['out_time'];
		$in_margin  = $shift['grace_time_in'];
		$out_margin = $shift['grace_time_out'];
		$shift_working_hrs      = $shift['working_hour'];
		$shift_working_half_hrs = $shift['min_working_hrs'];

		/* ================= LAST OPEN RECORD ================= */
		$attendance_id = $obj->getvalfield(
			"attendance_entry",
			"attendance_id",
			"emp_id='$emp_id' AND outtime IS NULL ORDER BY attendance_id DESC LIMIT 1"
		);

		$attendance_date = $punch_date;

		if ($attendance_id != "") {

			$intime = $obj->getvalfield("attendance_entry", "intime", "attendance_id='$attendance_id'");
			$att_date = $obj->getvalfield("attendance_entry", "attendance_date", "attendance_id='$attendance_id'");

			$inDateTime   = strtotime($att_date . " " . $intime);
			$currentPunch = strtotime($attendance_stamp);

			/* ===== 25 HOUR WINDOW ===== */
			$maxOutDateTime = strtotime("+25 hours", $inDateTime);

			if ($currentPunch <= $maxOutDateTime) {

				/* ✅ ALWAYS OUT UPDATE (NO NEW IN) */
				$attendance_date = $att_date;

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

				$early_out = $obj->calculateEarlyOut($shift_out, $attendance_time, $out_margin);
				$overtime  = $obj->calculateOvertimeTime($working_hours, $shift_working_hrs);

				$update_data = array(
					'outtime' => $attendance_time,
					'machineid' => $device,
					'working_hours' => $working_hours,
					'early_out' => $early_out,
					'attendance_status' => $attendance_status,
					'out_status' => 'OUT',
					'overtime' => $overtime,
					'lastupdated' => date('Y-m-d'),
					'entry_type_out' => 'machine'
				);

				$obj->update_record("attendance_entry", ['attendance_id' => $attendance_id], $update_data);

				return; // 🔥 IMPORTANT: yahi stop karo (new IN na bane)
			}
		}

		/* ================= NEW IN ================= */
		$late_in = $obj->calculateLateIn($shift_in, $attendance_time, $in_margin);

		$form_data = array(
			'emp_id' => $emp_id,
			'shift_id' => $shift_id,
			'department_id' => $department_id,
			'machine_userid' => $machine_userid,
			'intime' => $attendance_time,
			'attendance_stamp' => $attendance_stamp,
			'attendance_date' => $punch_date,
			'late_in' => $late_in,
			'attendanceby' => '1',
			'verifyiedby' => '',
			'createdate' => $createdate,
			'machineid' => $device,
			'entry_type' => 'machine',
			'in_status' => 'IN',
			'month' => $month,
			'year' => $year,
			'unit_id' => $unit_id,
			'basic_salary' => $emp_salary,
			'sessionid' => $sessionid,
			'createtime' => $attendance_time,
			'attendance_status' => 'Incomplete'
		);

		$obj->insert_record("attendance_entry", $form_data);
	}
}
