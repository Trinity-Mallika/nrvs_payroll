<?php
include("../adminsession.php");

$loan_advance_id = $obj->test_input($_POST['loan_advance_id']);
$status = $obj->test_input($_POST['status']);
$approval_remark = $obj->test_input($_POST['approval_remark']);
$months  = $_POST['months']  ?? [];
$years   = $_POST['years']   ?? [];
$amounts = $_POST['amounts'] ?? [];
$remarks = $_POST['remarks'] ?? [];
$type = $obj->getvalfield("loan_advance", "type", "loan_advance_id='$loan_advance_id'");
$emp_id = $obj->getvalfield("loan_advance", "emp_id", "loan_advance_id='$loan_advance_id'");
if (!$loan_advance_id) {
    echo "error";
    exit;
}
$obj->delete_record("loan_advance_details", array("loan_advance_id" => $loan_advance_id, "is_paid" => 0));
for ($i = 0; $i < count($months); $i++) {
    $month  = $months[$i]  ?? '';
    $year   = $years[$i]   ?? '';
    $amount = $amounts[$i] ?? 0;
    $remark = $remarks[$i] ?? '';
    $check_paid  = $obj->getvalfield("loan_advance_details", "is_paid", "loan_advance_id='$loan_advance_id' and month=$month and year=$year");

    if ($check_paid == 1) {
        continue; // Skip paid record
    }

    $details_data = array(
        "loan_advance_id" => $loan_advance_id,
        "status" => $status,
        "month"           => $month,
        "year"            => $year,
        "amount"          => $amount,
        "remark"          => $remark,
        "type"          => $type,
        "emp_id"          => $emp_id,
        "unit_id" => $unitid,
        "createdby" => $loginid,
        "sessionid" => $sessionid,
        "createdate" => $createdate,
        "ipaddress" => $ipaddress
    );
    $obj->insert_record("loan_advance_details", $details_data);
}


$obj->update_record("loan_advance", ['loan_advance_id' => $loan_advance_id, 'unit_id' => $unitid], ['appr_remark' => $approval_remark, 'appr_status' => $status,'updatedby'=>$loginid,'lastupdated'=>$createdate]);
$form_data1 = array(
    "primary_id" => $loan_advance_id,
    "flag" => "Loan Advance Approved",
    "activity_type" => 'Approved',
    "createdby" => $loginid,
    "pagename" => 'loan_advance_list.php',
    "created_date" => $createdate,
    "created_time" => date('H:i:s'),
    "unit_id" => $unitid,
    'ipaddress' => $ipaddress,
    "sessionid" => $sessionid
);
$logactivity = $obj->insert_record("logactivity_master", $form_data1);

echo "success";
