<?php
include("../adminsession.php");

$ids = $_POST['ids'] ?? [];
$status = $_POST['status'] ?? 0;

if(count($ids) == 0){
    echo "error";
    exit;
}

foreach($ids as $loan_advance_id){
    $loan_advance_id = $obj->test_input($loan_advance_id);
    // update main table
    $obj->update_record(
        "loan_advance",
        [
            'loan_advance_id' => $loan_advance_id,
            'unit_id' => $unitid
        ],
        [
            'appr_status' => $status,'updatedby'=>$loginid,'lastupdated'=>$createdate
        ]
    );
    $obj->update_record(
        "loan_advance_details",
        [
            'loan_advance_id' => $loan_advance_id,
            'unit_id' => $unitid
        ],
        [
            'status' => $status,'lastupdated'=>$createdate
            
        ]
    );

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
    $obj->insert_record("logactivity_master", $form_data1);
}
echo "success";
?>