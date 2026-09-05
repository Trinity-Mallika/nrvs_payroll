<?php
include("../adminsession.php");

$leave_details_id = $obj->test_input($_POST['leave_details_id']);
$date             = $obj->dateformatusa($_POST['date']);
$leave_day        = $obj->test_input($_POST['leave_day']);
$leave_type       = $obj->test_input($_POST['leave_type']);
$remark           = $obj->test_input($_POST['remark']);

// Fetch existing record details
$row = $obj->executequery("
    SELECT on_duty_id, emp_id, unit_id
    FROM leave_apply_detail
    WHERE leave_details_id='$leave_details_id'
");

if (empty($row)) {
    echo json_encode([
        "status" => "error",
        "message" => "Leave record not found."
    ]);
    exit;
}

$on_duty_id = $row[0]['on_duty_id'];
$emp_id     = $row[0]['emp_id'];
$unit_id    = $row[0]['unit_id'];

/* ==========================
   MONTH VALIDATION
========================== */

$firstDate = $obj->getvalfield(
    "leave_apply_detail",
    "MIN(date)",
    "on_duty_id='$on_duty_id'
     AND leave_details_id<>'$leave_details_id'"
);

if (!empty($firstDate)) {

    if (date('Y-m', strtotime($firstDate)) != date('Y-m', strtotime($date))) {

        echo json_encode([
            "status" => "error",
            "message" => "Leave dates must belong to the same month."
        ]);
        exit;
    }
}

/* ==========================
   DUPLICATE DATE VALIDATION
========================== */

$duplicate = $obj->getvalfield(
    "leave_apply_detail",
    "COUNT(*)",
    "emp_id='$emp_id'
    AND unit_id='$unit_id'
    AND date='$date'
    AND status!=2
    AND leave_details_id<>'$leave_details_id'"
);

if ($duplicate > 0) {

    echo json_encode([
        "status" => "duplicate",
        "message" => "Leave already applied on " . date('d-m-Y', strtotime($date))
    ]);
    exit;
}

/* ==========================
   UPDATE RECORD
========================== */

$form_data = [
    'date'         => $date,
    'leave_day'    => $leave_day,
    'leave_type'   => $leave_type,
    'remark'       => $remark,
    'lastupdated'  => $createdate,
    'updatedby'    => $loginid
];

$obj->update_record(
    "leave_apply_detail",
    ['leave_details_id' => $leave_details_id],
    $form_data
);

/* ==========================
   RECALCULATE TOTAL DAYS
========================== */

$total_days = $obj->getvalfield(
    "leave_apply_detail",
    "SUM(
        CASE
            WHEN leave_day IN('FD','SL') THEN 1
            WHEN leave_day IN('FHD','SHD') THEN 0.5
            ELSE 0
        END
    )",
    "on_duty_id='$on_duty_id'
    AND emp_id='$emp_id'
    AND unit_id='$unit_id'"
);

/* ==========================
   RESPONSE
========================== */

echo json_encode([
    "status"      => "success",
    "message"     => "Leave updated successfully.",
    "total_days"  => $total_days
]);