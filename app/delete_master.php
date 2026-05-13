<?php include("appsession.php");

//print_r($_REQUEST); die;

$id  = $_POST['id'];

$tblname  = $_REQUEST['tblname'];

$tblpkey  = $_REQUEST['tblpkey'];

$pagename = $_REQUEST['pagename'];

if ($id > 0) {
	$where = array($tblpkey => $id);
	$keyvalue = $obj->delete_record($tblname, $where);
}
