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
    $emp_data = $obj->select_record("employee_master", ["aadhar_no" => $aadhar_no]);

    if (!empty($emp_data)) {

        $unit_id = $emp_data['unit_id'];
        $emp_id = $emp_data['emp_id'];
        $emp_code = $emp_data['emp_code'];
        $emp_name = $emp_data['first_name'];
        $is_blacklist = $emp_data['is_blacklist'];
        $reason = $obj->getvalfield("employee_exit", "reason_for_leaving", "emp_id='$emp_id'");
        $unit_name = $obj->getvalfield("unit_master", "unit_name", "unit_id='$unit_id'");
        if ($is_blacklist == 1) {
            echo "BLACKLIST|" . $unit_name . "|" . $aadhar_no .  "|" . $emp_code . "|" . $emp_name . "|" . $reason;
        } else {
            echo "EXISTS|" . $unit_name . "|" . $aadhar_no . "|" . $emp_code . "|" . $emp_name;
        }
    } else {
        echo "OK";
    }
} elseif ($type == 'pan_no') {
    $pan_no = $_POST['pan_no'] ?? '';
    $emp_data = $obj->select_record("employee_master", ["pan_no" => $pan_no]);

    if (!empty($emp_data)) {
        $unit_id = $emp_data['unit_id'];
        $is_blacklist = $emp_data['is_blacklist'];
        $emp_id = $emp_data['emp_id'];
        $emp_code = $emp_data['emp_code'];
        $emp_name = $emp_data['first_name'];
        $reason = $obj->getvalfield("employee_exit", "reason_for_leaving", "emp_id='$emp_id'");
        $unit_name = $obj->getvalfield("unit_master", "unit_name", "unit_id='$unit_id'");
        if ($is_blacklist == 1) {
            echo "BLACKLIST|" . $unit_name . "|" . $pan_no .  "|" . $emp_code . "|" . $emp_name . "|" . $reason;
        } else {
            echo "EXISTS|" . $unit_name . "|" . $pan_no .  "|" . $emp_code . "|" . $emp_name;
        }
    } else {
        echo "OK";
    }
} elseif ($type == 'emp_code') {
    $emp_code = $_POST['emp_code'] ?? '';
    $emp_data = $obj->select_record("employee_master", ["emp_code" => $emp_code]);

    if (!empty($emp_data)) {
        $unit_id = $emp_data['unit_id'];
        $emp_name = $emp_data['first_name'];
        $unit_name = $obj->getvalfield("unit_master", "unit_name", "unit_id='$unit_id'");
        echo "EXISTS|" . $unit_name . "|" . $emp_code . "|" . $emp_name;
    } else {
        echo "OK";
    }
}
