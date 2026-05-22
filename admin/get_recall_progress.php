<?php include("../adminsession.php");

// $from_date = $_POST['from_date'];
// $to_date = $_POST['to_date'];
// $emp_ids = explode(',', $_POST['emp_ids']);


// $emp_codes = "'" . implode("','", $emp_ids) . "'";

// /* TOTAL RECORDS (same as API gave) */
// $total = $_POST['total'] ?? 0; // optional

// /* PROCESSED COUNT */

// $emp_id_list = $obj->getvalfield(
//     "employee_master",
//     "GROUP_CONCAT(emp_id)",
//     "emp_code IN ($emp_codes)"
// );
// $processed = 0;
// if (!empty($emp_id_list)) {

//     $row = $obj->executequery("
//         SELECT 
//             COUNT(intime) AS in_count,
//             COUNT(outtime) AS out_count
//         FROM attendance_log
//         WHERE emp_id IN ($emp_id_list)
//         AND unit_id='$unitid'
//         AND attendance_date BETWEEN '$from_date' AND '$to_date'
//     ");

//     $processed = $row[0]['in_count'] + $row[0]['out_count'];
// }

// /* If total not passed, calculate approx */
// if (!$total) {
//     $total = $processed; // fallback
// }

// echo json_encode([
//     "processed" => (int)$processed,
//     "total" => (int)$total
// ]);


$from_date = $_POST['from_date'];
$to_date   = $_POST['to_date'];
$emp_ids_raw = $_POST['emp_ids'] ?? '';
$total = $_POST['total'] ?? 0;
$department_id = $_POST['department_id'] ?? '';


$processed = 0;

if (!empty($emp_ids_raw)) {

    // ✅ Selected employees
    $emp_ids = explode(',', $emp_ids_raw);
    $emp_codes = "'" . implode("','", $emp_ids) . "'";

    if (!empty($department_id)) {
        $emp_id_list = $obj->getvalfield(
            "employee_master",
            "GROUP_CONCAT(emp_id)",
            "emp_code IN ($emp_codes) 
             AND department_id='$department_id'
             AND unit_id='$unitid'"
        );

        if (empty($emp_id_list)) {
            echo json_encode([
                "status" => "error",
                "msg" => "Selected employees not in selected department"
            ]);
            exit;
        }
    } else {
        // ✅ Only employee selected
        $emp_id_list = $obj->getvalfield(
            "employee_master",
            "GROUP_CONCAT(emp_id)",
            "emp_code IN ($emp_codes)
             AND unit_id='$unitid'"
        );
    }

    // $emp_id_list = $obj->getvalfield(
    //     "employee_master",
    //     "GROUP_CONCAT(emp_id)",
    //     "emp_code IN ($emp_codes)"
    // );

    if (!empty($emp_id_list)) {
        $deptCondition = !empty($department_id) ? "AND department_id='$department_id'" : "";
        $row = $obj->executequery("
            SELECT 
                COUNT(intime) AS in_count 
            FROM attendance_log
            WHERE emp_id IN ($emp_id_list)
            AND attendance_date BETWEEN '$from_date' AND '$to_date' $deptCondition
        ");

        $processed = $row[0]['in_count'];
    }
} else {
    $deptCondition = !empty($department_id) ? "AND department_id='$department_id'" : "";
    // ✅ ALL employees case
    $row = $obj->executequery("
        SELECT 
            COUNT(intime) AS in_count
        FROM attendance_log
        WHERE attendance_date BETWEEN '$from_date' AND '$to_date' and unit_id='$unitid'  $deptCondition 
    ");

    $processed = $row[0]['in_count'];
}

/* fallback */
if (!$total) {
    $total = $processed;
}

echo json_encode([
    "processed" => (int)$processed,
    "total" => (int)$total
]);