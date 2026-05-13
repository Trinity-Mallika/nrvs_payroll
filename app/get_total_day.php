<?php include_once("appsession.php");
$action = $_POST['action'];
$on_duty_details_id = $_POST['on_duty_details_idd'];
$total_day = '';
if ($action == 'on_duty') {
    $total_day  = $obj->getvalfield("on_duty_details", "count(*)", "on_duty_id='$on_duty_details_id' and createdby='$emp_id'");
} elseif ($action == 'leave') {
    $total_day  = $obj->getvalfield(
        "leave_apply_detail",
        "SUM(
        CASE 
            WHEN leave_day = 'FD' THEN 1
            WHEN leave_day = 'SL' THEN 1
            WHEN leave_day IN ('FHD','SHD') THEN 0.5
            ELSE 0
        END
    )",
        "on_duty_id='$on_duty_details_idd' and unit_id='$unitid' and emp_id='$emp_id'"
    );
}
echo $total_day;
