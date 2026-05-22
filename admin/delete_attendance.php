<?php
include("../adminsession.php");

$emp_ids   = $_POST['emp_ids']; // emp_code
$from_date = $_POST['from_date'];
$to_date   = $_POST['to_date'];
$department_id   = $_POST['department_id'] ?? '';

if (!empty($emp_ids)) {
    // Selected employees case
    $emp_codes = explode(',', $emp_ids);

    foreach ($emp_codes as $code) {

        if (!empty($department_id)) {
            $data = $obj->select_record(
                "employee_master",
                [
                    "emp_code" => $code,
                    "department_id" => $department_id,
                    "unit_id" => $unitid
                ]
            );

            if (!$data) {
                echo json_encode([
                    "status" => "error",
                    "msg" => "Employee ($code) not in selected department"
                ]);
                exit;
            }
        } else {
            $data = $obj->select_record(
                "employee_master",
                [
                    "emp_code" => $code,
                    "unit_id" => $unitid
                ]
            );

            if (!$data) {
                echo json_encode([
                    "status" => "error",
                    "msg" => "Invalid employee ($code)"
                ]);
                exit;
            }
        }
        $emp_id_list[] = $data['emp_id'];
    }
    $emp_ids_final = implode(',', $emp_id_list);

    $deptCondition = !empty($department_id) ? "AND department_id='$department_id'" : "";
    $obj->executenonquery("
        DELETE FROM attendance_entry 
        WHERE emp_id IN ($emp_ids_final)
        AND attendance_date BETWEEN '$from_date' AND '$to_date'
        AND unit_id='$unitid'
        $deptCondition
    ");

    $obj->executenonquery(
        "DELETE FROM attendance_log 
        WHERE emp_id IN ($emp_ids_final)
        AND attendance_date BETWEEN '$from_date' AND '$to_date'
        AND unit_id='$unitid'
        $deptCondition "
    );
} else {

    $deptCondition = !empty($department_id)
        ? "AND department_id='$department_id'"
        : "";
    // ✅ ALL employees case
    $obj->executenonquery("
        DELETE FROM attendance_entry 
        WHERE attendance_date BETWEEN '$from_date' AND '$to_date' AND unit_id='$unitid'  $deptCondition
    ");

    $obj->executenonquery("
        DELETE FROM attendance_log 
        WHERE attendance_date BETWEEN '$from_date' AND '$to_date' AND unit_id='$unitid'  $deptCondition
    ");
}

echo json_encode([
    "status" => "success",
    "msg" => "Attendance and logs deleted successfully"
]);
