<?php
include("../adminsession.php");

$from_date = $_POST['from_date'];
$to_date   = $_POST['to_date'];
$emp_ids   = $_POST['emp_ids'];
$url       = $_POST['url'];
$department_id = $_POST['department_id'] ?? '';

$emp_id_list = [];    // for DB
$emp_code_list = [];

/* ================= CASE HANDLING ================= */

if (!empty($department_id) && empty($emp_ids)) {
    // ✅ Case 1: Only Department selected

    $res = $obj->executequery("SELECT emp_id,emp_code FROM employee_master WHERE department_id='$department_id' AND unit_id='$unitid'");
    foreach ($res as $row) {
        $emp_code_list[] = $row['emp_code'];
        $emp_id_list[] = $row['emp_id'];
    }
} elseif (!empty($department_id) && !empty($emp_ids)) {
    // ✅ Case 2: Department + Employee

    $emp_codes = explode(',', $emp_ids);

    foreach ($emp_codes as $code) {

        $data = $obj->select_record(
            "employee_master",
            [
                "emp_code" => $code,
                "unit_id" => $unitid,
                "department_id" => $department_id
            ]
        );

        // ❌ If NOT found → error
        if (!$data) {
            echo json_encode([
                "status" => "error",
                "msg" => "Employee ($code) not in selected department"
            ]);
            exit;
        }


        $emp_id_list[] = $data['emp_id'];       // ✅
        $emp_code_list[] = $data['emp_code'];  // for API
    }
} elseif (empty($department_id) && !empty($emp_ids)) {
    // ✅ Case 3: Only Employee

    $emp_codes = explode(',', $emp_ids);

    foreach ($emp_codes as $code) {
        $id = $obj->getvalfield(
            "employee_master",
            "emp_id",
            "emp_code='$code' AND unit_id='$unitid'"
        );

        if ($id) {
            $emp_id_list[] = $id;
            $emp_code_list[] = $code;
        }
    }
} else {
    // ✅ Case 4: ALL employees
    $res = $obj->executequery("SELECT emp_id,emp_code  FROM employee_master WHERE unit_id='$unitid'");
    foreach ($res as $row) {
        $emp_id_list[] = $row['emp_id'];
        $emp_code_list[] = $row['emp_code'];
    }
}

/* ================= VALIDATION ================= */

if (empty($emp_id_list)) {
    echo json_encode(["status" => "error", "msg" => "No valid employees found"]);
    exit;
}

$emp_codes_final = implode(',', $emp_code_list);
$emp_ids_final = implode(',', $emp_id_list);

/* ================= ATTENDANCE CHECK ================= */
// if (!empty($emp_id_list)) {

//     // ✅ Case: Employee selected (or dept+emp)
//     $emp_ids_final = implode(',', $emp_id_list);

//     $check = $obj->getvalfield(
//         "attendance_entry",
//         "COUNT(*)",
//         "emp_id IN ($emp_ids_final)
//          AND attendance_date BETWEEN '$from_date' AND '$to_date'
//          AND unit_id='$unitid'"
//     );
// } elseif (!empty($department_id)) {

//     // ✅ Case: Only Department selected
//     $check = $obj->getvalfield(
//         "attendance_entry",
//         "COUNT(*)",
//         "department_id='$department_id'
//          AND attendance_date BETWEEN '$from_date' AND '$to_date'
//          AND unit_id='$unitid'"
//     );
// } else {

//     // ✅ Case: No employee, no department (ALL)
//     $check = $obj->getvalfield(
//         "attendance_entry",
//         "COUNT(*)",
//         "attendance_date BETWEEN '$from_date' AND '$to_date'
//          AND unit_id='$unitid'"
//     );
// }
$check = $obj->getvalfield(
    "attendance_entry",
    "COUNT(*)",
    "emp_id IN ($emp_ids_final)
     AND attendance_date BETWEEN '$from_date' AND '$to_date'
     AND unit_id='$unitid'"
);
if ($check > 0) {
    echo json_encode([
        "status" => "exists",
        "msg" => "Attendance already exists. Please delete attendance first."
    ]);
    exit;
}

/* ================= API CALL ================= */

$data = [
    "fromDate" => $from_date,
    "toDate" => $to_date,
    "employeeIds" => !empty($emp_codes_final) ? $emp_codes_final : ""
];


$payload = json_encode($data);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(["status" => "error", "msg" => curl_error($ch)]);
    exit;
}

curl_close($ch);

$responseData = json_decode($response, true);

echo json_encode([
    "status" => "success",
    "data" => $responseData
]);
