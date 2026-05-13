<?php include("../adminsession.php");
// $date = $obj->test_input($_REQUEST['date']);
// $no_of_days = !empty($_REQUEST['no_of_days']) ? (int)$_REQUEST['no_of_days'] : 1;
// $leave_day = $obj->test_input($_REQUEST['leave_day']);
// $leave_type = $obj->test_input($_REQUEST['leave_type']);
// $remark = $obj->test_input($_REQUEST['remark']);
// $keyvalue = $obj->test_input($_REQUEST['keyvalue']);
// $emp_id = $obj->test_input($_REQUEST['emp_id']);
// $check = $obj->getvalfield(
//     "leave_apply_detail",
//     "count(*)",
//     "date='$date' AND emp_id='$emp_id' AND unit_id='$unitid' and status!=2"
// );
// if ($check > 0) {
//     echo json_encode([
//         "status" => "duplicate",
//         "message" => "This date is already added!"
//     ]);
//     exit;
// }
// $form_data = array(
//     'on_duty_id' => $keyvalue,
//     'date' => $date,
//     'emp_id' => $emp_id,
//     'leave_type' => $leave_type,
//     'leave_day' => $leave_day,
//     'remark' => $remark,
//     "createdate" => $createdate,
//     "createdby" => $loginid,
//     "ipaddress" => $ipaddress,
//     "sessionid" => $sessionid,
//     "unit_id" => $unitid
// );
// if ($date != '') {
//     $obj->insert_record("leave_apply_detail", $form_data);

//     $count = $obj->getvalfield(
//         "leave_apply_detail",
//         "SUM(
//         CASE 
//             WHEN leave_day = 'FD' THEN 1
//             WHEN leave_day = 'SL' THEN 1
//             WHEN leave_day IN ('FHD','SHD') THEN 0.5
//             ELSE 0
//         END
//     )",
//         "on_duty_id='$keyvalue' and unit_id='$unitid' and emp_id='$emp_id'"
//     );
//     echo json_encode([
//         "status" => "success",
//         "total_days" => $count
//     ]);
// } else {
//     echo json_encode([
//         "status" => "error",
//         "message" => "Something Went Wrong!!!"
//     ]);
// }


$date       = $obj->test_input($_REQUEST['date']);
$no_of_days = !empty($_REQUEST['no_of_days']) ? (int)$_REQUEST['no_of_days'] : 1;
$leave_day  = $obj->test_input($_REQUEST['leave_day']);
$leave_type = $obj->test_input($_REQUEST['leave_type']);
$remark     = $obj->test_input($_REQUEST['remark']);
$keyvalue   = $obj->test_input($_REQUEST['keyvalue']);
$emp_id     = $obj->test_input($_REQUEST['emp_id']);
$leave_details_id  = $obj->test_input($_REQUEST['leave_details_id'] ?? 0);

$inserted = 0;
$duplicate_dates = [];

for ($i = 0; $i < $no_of_days; $i++) {

    $current_date = date('Y-m-d', strtotime($date . " +$i days"));

    // 🔍 duplicate check


    $form_data = array(
        'on_duty_id' => $keyvalue,
        'date' => $current_date,
        'emp_id' => $emp_id,
        'leave_type' => $leave_type,
        'leave_day' => $leave_day,
        'remark' => $remark,
        "createdate" => $createdate,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid,
        "unit_id" => $unitid
    );

    if ($leave_details_id == 0) {

        $check = $obj->getvalfield(
            "leave_apply_detail",
            "count(*)",
            "date='$current_date' AND emp_id='$emp_id' AND unit_id='$unitid' and status!=2"
        );

        if ($check > 0) {
            $duplicate_dates[] = $current_date;
            continue; // skip duplicate
        }
        $obj->insert_record("leave_apply_detail", $form_data);
    } else {
        $where = ['leave_details_id' => $leave_details_id];
        $obj->update_record("leave_apply_detail", $where, $form_data);
    }


    $inserted++;
}

/* ===== TOTAL DAYS CALCULATION ===== */
$count = $obj->getvalfield(
    "leave_apply_detail",
    "SUM(
        CASE 
            WHEN leave_day = 'FD' THEN 1
            WHEN leave_day = 'SL' THEN 1
            WHEN leave_day IN ('FHD','SHD') THEN 0.5
            ELSE 0
        END
    )",
    "on_duty_id='$keyvalue' and unit_id='$unitid' and emp_id='$emp_id'"
);

/* ===== RESPONSE ===== */
if ($inserted > 0) {
    echo json_encode([
        "status" => "success",
        "total_days" => $count,
        "inserted" => $inserted,
        "duplicates" => $duplicate_dates
    ]);
} else {
    echo json_encode([
        "status" => "duplicate",
        "message" => "All selected dates already exist!",
        "duplicates" => $duplicate_dates
    ]);
}
