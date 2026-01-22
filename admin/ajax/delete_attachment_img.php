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



if ($res > 0) {
    //$cmn->InsertLog($pagename, $module, $submodule, $tblname, $tblpkey, $id, "deleted");

    echo "<script>location='$pagename?action=3';</script>";
}
