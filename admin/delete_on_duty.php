<?php include("../adminsession.php");

$id  = (int)$obj->test_input($_POST['id']);
$tblname  = $obj->test_input($_REQUEST['tblname']);
$tblpkey  = $obj->test_input($_REQUEST['tblpkey']);
$imgpath  = $obj->test_input($_REQUEST['imgpath']);
$imgname  = $obj->test_input($_REQUEST['imgname']);
if ($id > 0) {
    $where = array($tblpkey => $id);
    $obj->delete_record($tblname, $where);


    if (!empty($imgname)) {
        $filepath =  $imgpath . $imgname;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    $obj->delete_record('on_duty_details', $where);
    $obj->delete_record('leave_apply_detail', $where);

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
