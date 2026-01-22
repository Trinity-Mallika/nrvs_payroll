<?php include("../adminsession.php");

$type = $_POST['type'] ?? '';
if ($type == 'mobile') {
    $mobile = $_POST['mobile_no'] ?? '';
    $unit_id = $obj->getvalfield(
        "employee_master",
        "unit_id",
        "mobile_no='$mobile'"
    );
    $unit_name = $obj->getvalfield(
        "unit_master",
        "unit_name",
        "unit_id='$unit_id'"
    );

    if (!empty($unit_id)) {
        echo "EXISTS|" . $unit_name . "|" . $mobile;
    } else {
        echo "OK";
    }
} elseif ($type == 'aadhar') {
    $aadhar_no = $_POST['aadhar_no'] ?? '';
    $unit_id = $obj->getvalfield(
        "employee_master",
        "unit_id",
        "aadhar_no='$aadhar_no'"
    );
    $unit_name = $obj->getvalfield(
        "unit_master",
        "unit_name",
        "unit_id='$unit_id'"
    );

    if (!empty($unit_id)) {
        echo "EXISTS|" . $unit_name . "|" . $aadhar_no;
    } else {
        echo "OK";
    }
}
