<?php include_once("../adminsession.php");

$date = $obj->test_input($_REQUEST['date']);
$intime = $obj->test_input($_REQUEST['intime']);
$outtime = $obj->test_input($_REQUEST['outtime']);
$place = $obj->test_input($_REQUEST['place']);
$with_employee = $obj->test_input($_REQUEST['with_employee']);
$remark = $obj->test_input($_REQUEST['remark']);
$keyvalue = $obj->test_input($_REQUEST['keyvalue']);
$emp_id = $obj->test_input($_REQUEST['emp_id']);

$check = $obj->getvalfield(
    "on_duty_details",
    "count(*)",
    "emp_id='$emp_id' AND date='$date' AND unit_id='$unitid' and status!=2"
);
if ($check > 0) {
    echo json_encode([
        "status" => "duplicate",
        "message" => "This date is already added!"
    ]);
    exit;
}

$form_data = array(
    'on_duty_id' => $keyvalue,
    'date' => $date,
    'emp_id' => $emp_id,
    'intime' => $intime,
    'outtime' => $outtime,
    'place' => $place,
    'with_employee' => $with_employee,
    'remark' => $remark,
    "createdate" => $createdate,
    "createdby" => $loginid,
    "ipaddress" => $ipaddress,
    "sessionid" => $sessionid,
    "unit_id" => $unitid
);
if ($intime != '') {
    $obj->insert_record("on_duty_details", $form_data);

    $count = $obj->getvalfield("on_duty_details", "count(*)", "on_duty_id='$keyvalue' and unit_id='$unitid' and createdby='$loginid'");

    echo json_encode([
        "status" => "success",
        "total_days" => $count
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Something Went Wrong!!!"
    ]);
}
