<?php include("../../adminsession.php");
$id  = $_REQUEST['id'];
$tblname  = $_REQUEST['tblname'];
$tblpkey  = $_REQUEST['tblpkey'];

$imgname = $_REQUEST['imgname'];

$imgpath = $_REQUEST['imgpath'];
$where = array($tblpkey => $id);
// $rowimg = $obj->select_record($tblname, $where);
// $oldimg = $rowimg["imgname"];


if ($imgname != "") {
    @unlink("../" . $imgpath . $imgname);
}
$res = $obj->delete_record($tblname, $where);
//$keyvalue = mysql_insert_id();
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


if ($res > 0) {
    //$cmn->InsertLog($pagename, $module, $submodule, $tblname, $tblpkey, $id, "deleted");

    echo "<script>location='$pagename?action=3';</script>";
}
