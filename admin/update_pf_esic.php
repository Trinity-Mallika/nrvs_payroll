<?php
include("../adminsession.php");

$emp_id = (int)($_POST['emp_id'] ?? 0);
$type   = $_POST['type'] ?? '';
$value  = (int)($_POST['value'] ?? 0);
$allowedTypes = ['pf', 'esic', 'allow_weekly_off'];

if ($emp_id > 0 && in_array($type, $allowedTypes)) {

    if ($type === 'pf') {
        $field = 'is_pf';
    } elseif ($type === 'esic') {
        $field = 'is_esic';
    } elseif ($type === 'allow_weekly_off') {
        $form_data_emp_week_status = array( 
            'unit_id' => $unitid,     
            'emp_id' => $emp_id,     
            'type' => 'allow_weekoff',     
            'is_allow' => $allow_weekly_off, 
            'last_inactive_month' => date('n'),
            'last_inactive_year' => date('Y'),
            "createdate" => $createdate,
            "createdby" => $loginid,
            "ipaddress" => $ipaddress,
            "sessionid" => $sessionid
        ); 
        $field = 'allow_weekly_off'; 
        $obj->insert_record("emp_allow_week_status", $form_data_emp_week_status);
    }

    $obj->update_record("employee_master", array("emp_id" => $emp_id), array($field => $value));
    echo "success";
} else {
    echo "invalid";
}
