<?php
include("../adminsession.php");
$records = $_POST['records'] ?? [];
$status = $_POST['status'];

if (!empty($records)) {
    foreach ($records as $rec) {
        $salary_id = $rec['id'];
        $obj->update_record(
            "salary_structure",
            array("salary_struc_id" => $salary_id),
            array(
                'apr_from_mgm' => $status, 
                'lastupdated' => $createdate, 
                'updatedby' => $loginid, 
            )
        ); 
        $form_data1 = array(
            "primary_id"   => $salary_id,
            "flag"         => 'Approve Salary',
            "activity_type"=> 'Updated',
            "createdby"    => $loginid,
            "pagename"     => 'salary_apr.php',
            "created_date" => $createdate,
            "created_time" => date('H:i:s'),
            "unit_id"      => $unitid,
            "ipaddress"    => $ipaddress,
            "sessionid"    => $sessionid
        );
        $obj->insert_record("logactivity_master", $form_data1);
    }
    echo "success";
} else {
    echo "error";
}
?>