<?php
include("../adminsession.php");

$emp_id = $_POST['emp_id'];
$application_date = date('Y-m-d');

if (!$emp_id || !$application_date) {
    echo json_encode(["status" => "error"]);
    exit;
}

$app_month = date('m', strtotime($application_date));
$app_year  = date('Y', strtotime($application_date));

$is_esic = $obj->getvalfield("employee_master", "is_esic", "emp_id='$emp_id'");
$setting_type = ($is_esic == 1) ? 'ESIC' : 'Non ESIC';

//$three_month_leave = $obj->getLeave($emp_id, $app_month, $app_year);
$total_earning_leave = $obj->getEarningLeave($emp_id, $sessionid);
$extra_off =$obj->getExtraOffBalance($emp_id, $app_month, $app_year);
$opening_leave_balance =$obj->get_opening_leave_balance($emp_id, $sessionid);

$total_leave_taken = $obj->getvalfield(
    "attendance_entry",
    "SUM(
        CASE 
            WHEN attendance_status IN ('Weekly Leave','Earning Leave','C Off') THEN 1
            WHEN attendance_status IN ('Half Weekly Leave','Half Earning Leave','Half C Off') THEN 0.5
            ELSE 0
        END
    )",
    "month='$app_month' 
    AND year='$app_year' 
    AND emp_id='$emp_id'"
) ?? 0;

echo json_encode([
    "status" => "success",
    "extra_off" => $extra_off['balance'],
    "earning_leave" => $total_earning_leave,
    "opening_leave_balance" => $opening_leave_balance

]);
