<?php include("../adminsession.php");

$id  = (int)$obj->test_input($_POST['id']);
$tblname  = $obj->test_input($_REQUEST['tblname']);
$tblpkey  = $obj->test_input($_REQUEST['tblpkey']);
$imgpath  = $obj->test_input($_REQUEST['imgpath']);
$imgname  = $obj->test_input($_REQUEST['imgname']);

if ($id > 0) {

    $where = array($tblpkey => $id);

    /* 🔹 DELETE MAIN RECORD */
    $obj->delete_record($tblname, $where);

    /* 🔹 DELETE IMAGE */
    if (!empty($imgname)) {
        $filepath =  $imgpath . $imgname;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    /* 🔹 DELETE CHILD TABLE DATA (CORRECT KEY) */

 


    // for loan advance
    $obj->delete_record('loan_advance_details', array("loan_advance_id" => $id));

    /* 🔹 LOG ACTIVITY (SAME STYLE) */
    $form_data1 = array(
        "primary_id" => $id,
        "flag" => 'Record Deleted Successfully',
        "activity_type" => 'Deleted',
        "createdby" => $loginid,
        "pagename" => $tblname,
        "created_date" => $createdate,
        "created_time" => date('H:i:s'),
        "unit_id" => $unitid,
        'ipaddress' => $ipaddress,
        "sessionid" => $sessionid
    );

    $logactivity = $obj->insert_record("logactivity_master", $form_data1);
}