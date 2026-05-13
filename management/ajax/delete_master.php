<?php include("../../adminsession.php");

$id  = (int)$obj->test_input($_POST['id']);
$tblname  = $obj->test_input($_REQUEST['tblname']);
$tblpkey  = $obj->test_input($_REQUEST['tblpkey']);
// $module = $obj->test_input($_REQUEST['module']);
// $submodule = $obj->test_input($_REQUEST['submodule']);
// $pagename = $obj->test_input($_REQUEST['pagename']);
// print_r($_REQUEST);
if ($id > 0) {
	$where = array($tblpkey => $id);
	// if ($tblname == 'emp_salary') {
	// 	$obj->delete_record("bank_transfer", $where);
	// }
	$obj->delete_record($tblname, $where);
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
