<?php
include("action.php");
$latitude = $_REQUEST['latitude'];
$longitude = $_REQUEST['longitude'];
$address = base64_decode($_REQUEST['address']);
$emp_id = $_REQUEST['empid'];
$deviceid = base64_decode($_REQUEST['deviceid']);
$status = $_REQUEST['status'];
//$status_out = $_REQUEST['status_out'];
$attendance_time = date('H:i:s');
$attendance_date = date('Y-m-d');
$createdate = date('Y-m-d h:i:s');
$month = date("m");
$year = date("Y");
$allow = 0;
$branch_id = $obj->getvalfield("employee_master","branch_id","emp_id='$emp_id'");
$branch_latitude = $obj->getvalfield("branch_master","latitude","branch_id='$branch_id'");
$branch_longitude = $obj->getvalfield("branch_master","longitude","branch_id='$branch_id'");

 $distance = $obj->calculateDistance($latitude, $longitude, $branch_latitude, $branch_longitude,10);

//get old device id
$old_deviceid = $obj->getvalfield("attendance_entry", "deviceid", "emp_id='$emp_id' and deviceid <> ''");

if($old_deviceid == '')
$allow = 1;
elseif($old_deviceid == $deviceid)
$allow = 1;
else
$allow = 0;


// echo "alllow: $allow";
// die;


$attendance_id = $obj->getvalfield("attendance_entry", "attendance_id", "emp_id='$emp_id' and attendance_date='$attendance_date'");

/*if($distance == 'true')
{*/



/*if($allow == 1)
{*/

	if ($attendance_id == 0)
				{
					//echo 'ji';die;
					if($status == 'IN')
					{
					$form_data1 = array('emp_id' => $emp_id,'month' => $month, 'year' => $year,'intime' => $attendance_time, 'attendance_date' => $attendance_date, 'attendanceby' => $emp_id, 'createdate' => $createdate,'in_status' => $status,'latitude_in'=>$latitude,'longitude_in'=>$longitude,'in_address'=>$address,'deviceid'=>$deviceid,'attendance_status' => 'Incomplete','attheadid'=>'5');
					   $obj->insert_record("attendance_entry", $form_data1);
					   echo json_encode(array("status" => 'true',"msg" => 'Successfully Submited'));
					}
				}
				else
					{

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
						if($status == 'OUT')
						{
						$status = 'OUT';
						$form_data1 = array('outtime' => $attendance_time, 'createdate' => $createdate, 'out_status' => $status,'lastupdated'=>$createdate,'latitude_out'=>$latitude,'longitude_out'=>$longitude,'out_address'=>$address,'working_hours' => $formatted_difference,'attheadid'=>'1','attendance_status' => 'Present');
						$where = array('attendance_id'=>$attendance_id,'deviceid'=>$deviceid);
						$obj->update_record("attendance_entry",$where, $form_data1);
                        echo json_encode(array("status" => 'true',"msg" => 'Successfully Submited'));
					
						}

						
					}


//}
/*else
{
	echo json_encode(array("status" => 'false',"msg" => 'Please Sign In Your Correct Device!'));
}*/
/*}
else
{
	echo json_encode(array("status" => 'false',"msg" => 'Your Branch Location Dose Not Matched!'));
}*/
?>

