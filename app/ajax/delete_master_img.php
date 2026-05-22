<?php include("../../adminsession.php");
$id  = $_REQUEST['id'];
$tblname  = $_REQUEST['tblname'];
$tblpkey  = $_REQUEST['tblpkey'];
$module = $_REQUEST['module'];
$submodule = $_REQUEST['submodule'];
$imgname = $_REQUEST['imgname'];
$pagename = $_REQUEST['pagename'];
$imgpath = $_REQUEST['imgpath'];
$type = $_REQUEST['type'];
$where = array($tblpkey => $id, 'type' => $type);
// $rowimg = $obj->select_record($tblname, $where);
// $oldimg = $rowimg["imgname"];

if ($id > 0) {

	if ($imgname != "") {
		@unlink("../$imgpath" . $imgname);
	}
	$res = $obj->delete_record($tblname, $where);
}
