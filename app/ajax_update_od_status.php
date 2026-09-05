<?php
include("appsession.php"); 
$id     = $_POST['id'];
$type   = $_POST['type'];
$status = $_POST['status'];

if ($type == 'all') {
    // GET ALL PENDING LEAVES
    $onDutyData = $obj->executequery("
    SELECT odd.*
    FROM on_duty_details odd
    WHERE odd.on_duty_id = '$id'
    AND odd.is_apr_hod = '0'
");

foreach ($onDutyData as $row) {
    $on_duty_details_id = $row['on_duty_details_id'];
    $obj->update_record(
        "on_duty_details",
        ['on_duty_details_id' => $on_duty_details_id],
        [
            'is_apr_hod'      => $status,
            'hod_apr_id'      => $emp_id,
            'lastupdated_hod' => $createdate
        ]
    );
}
echo "success";
} elseif ($type == 'single') {

$onDutyData = $obj->executequery("
    SELECT *
    FROM on_duty_details
    WHERE on_duty_details_id = '$id'
    LIMIT 1
");

if (!empty($onDutyData)) {

    $obj->update_record(
        "on_duty_details",
        ['on_duty_details_id' => $id],
        [
            'is_apr_hod'      => $status,
            'hod_apr_id'      => $emp_id,
            'lastupdated_hod' => $createdate
        ]
    );
    echo "success";
} else {
    echo "error";
} 
}
?>