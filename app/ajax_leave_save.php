<?php include("appsession.php");

$date = $obj->test_input($_REQUEST['date']);
$leave_day = $obj->test_input($_REQUEST['leave_day']);
$leave_type = $obj->test_input($_REQUEST['leave_type']);
$remark = $obj->test_input($_REQUEST['remark']);
$keyvalue = $obj->test_input($_REQUEST['keyvalue']);
$leave_details_id  = $obj->test_input($_REQUEST['leave_details_id'] ?? 0);
$no_of_days = !empty($_REQUEST['no_of_days']) ? (int)$_REQUEST['no_of_days'] : 1;

$duplicate_dates = [];
$inserted = 0;

/* ================= UPDATE ================= */
if ($leave_details_id != 0) {

    $form_data = [
        'date' => $date,
        'leave_type' => $leave_type,
        'leave_day' => $leave_day,
        'remark' => $remark
    ];

    $where = ['leave_details_id' => $leave_details_id];
    $obj->update_record("leave_apply_detail", $where, $form_data);

    $count = $obj->getvalfield(
        "leave_apply_detail",
        "SUM(
            CASE 
                WHEN leave_day IN ('FD','SL') THEN 1
                WHEN leave_day IN ('FHD','SHD') THEN 0.5
                ELSE 0
            END
        )",
        "on_duty_id='$keyvalue' and unit_id='$unitid' and emp_id='$emp_id'"
    );

    echo json_encode([
        "status" => "updated",
        "total_days" => $count
    ]);
    exit;
}

/* ================= INSERT ================= */

for ($i = 0; $i < $no_of_days; $i++) {

    $current_date = date('Y-m-d', strtotime($date . " +$i days"));

    $check = $obj->getvalfield(
        "leave_apply_detail",
        "count(*)",
        "date='$current_date' AND emp_id='$emp_id' AND unit_id='$unitid' AND status!=2"
    );

    if ($check > 0) {
        $duplicate_dates[] = $current_date;
        continue;
    }

    $form_data = [
        'on_duty_id' => $keyvalue,
        'date' => $current_date,
        'emp_id' => $emp_id,
        'leave_type' => $leave_type,
        'leave_day' => $leave_day,
        'remark' => $remark,
        "createdate" => $createdate,
        "createdby" => $emp_id,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid,
        "unit_id" => $unitid
    ];

    $obj->insert_record("leave_apply_detail", $form_data);
    $inserted++;
}

/* ================= TOTAL ================= */

$count = $obj->getvalfield(
    "leave_apply_detail",
    "SUM(
        CASE 
            WHEN leave_day IN ('FD','SL') THEN 1
            WHEN leave_day IN ('FHD','SHD') THEN 0.5
            ELSE 0
        END
    )",
    "on_duty_id='$keyvalue' and unit_id='$unitid' and emp_id='$emp_id'"
);

/* ================= RESPONSE ================= */

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
