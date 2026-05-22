<?php
include("appsession.php");
$latitude = '0';
$longitude = '';
$address = $_REQUEST['inaddress'];
$emp_id = $_REQUEST['emp_id'];
$status_in = $_REQUEST['status_in'];
$status_out = $_REQUEST['status_out'];
$attendance_time = date('H:i:s');
$attendance_date = date('Y-m-d');
$createdate = date('Y-m-d h:i:s');
$month = date("m");
$year = date("Y");



$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);



$attendance_id = $obj->getvalfield("attendance_entry", "attendance_id", "emp_id='$emp_id' and attendance_date='$attendance_date'");
//$attendance_id = is_null($attendance_id) ? 0 : count($attendance_id);
$branch_id = $obj->getvalfield("employee_master", "branch_id", "emp_id='$emp_id'");
$branch_latitude = $obj->getvalfield("branch_master", "latitude", "branch_id='$branch_id'");
$branch_longitude = $obj->getvalfield("branch_master", "longitude", "branch_id='$branch_id'");

// $distance = $obj->calculateDistance($latitude, $longitude, $branch_latitude, $branch_longitude, 10);



$emp_salary =  $obj->getvalfield("employee_master", "salary", "emp_id='$emp_id'");
$branch_id =  $obj->getvalfield("employee_master", "branch_id", "emp_id='$emp_id'");

$Office_working_hour =  $obj->getvalfield("branch_master", "working_hour", "branch_id='$branch_id'");

$Office_working_hour = str_replace(':', '.', $Office_working_hour);




//if($distance == 'true')
//{
if ($attendance_id == 0) {
	//echo 'ji';die;
	if ($status_in == 'IN') {
		//$status = 'IN';
		$form_data1 = array('emp_id' => $emp_id, 'month' => $month, 'year' => $year, 'intime' => $attendance_time, 'attendance_status' => 'Incomplete', 'attendance_date' => $attendance_date, 'attendanceby' => $emp_id, 'createdate' => $createdate, 'in_status' => $status_in, 'latitude_in' => $latitude, 'longitude_in' => $longitude, 'in_address' => $address, 'attheadid' => '5');
		$obj->insert_record("attendance_entry", $form_data1);
		echo 1;
	}
} else {
	$intime = $obj->getvalfield("attendance_entry", "intime", "attendance_id='$attendance_id'");







	if ($intime && $attendance_time) {
		// Convert times to DateTime objects
		$intime_obj = new DateTime($intime);
		$outtime_obj = new DateTime($attendance_time);

		// Calculate the difference
		$interval = $intime_obj->diff($outtime_obj);

		// Format the difference as HH:MM:SS
		$formatted_difference = $interval->format('%H:%I:00');
	}
	if ($status_out == 'OUT') {




		$start_time = new DateTime($intime);
		$end_time = new DateTime($attendance_time);

		// Calculate the interval
		$interval = $start_time->diff($end_time);

		// Get the total hours between the two times
		$total_hours = ($interval->h) + ($interval->i / 60);


		$over_time =  $obj->calculateOvertimeSalary($emp_salary, $intime, $attendance_time, $Office_working_hour, $total_days, 1);


		if ($over_time > 0) {
			$fine_action = "overtime";
		} elseif ($over_time < 0) {
			$fine_action = "Fixed Amount";
		} else {
			$fine_action = "";
		}
		$status = 'OUT';
		$form_data1 = array('outtime' => $attendance_time, 'createdate' => $createdate, 'working_hours' => $formatted_difference, 'out_status' => $status, 'lastupdated' => $createdate, 'latitude_out' => $latitude, 'longitude_out' => $longitude, 'out_address' => $address, 'attheadid' => '1', 'attendance_status' => 'Present', 'fine_amt' => round($over_time, 2), 'fine_action' => $fine_action);
		$where = array('attendance_id' => $attendance_id);
		$obj->update_record("attendance_entry", $where, $form_data1);
		echo 1;
	}
}
