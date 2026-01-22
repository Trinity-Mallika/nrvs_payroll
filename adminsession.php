<?php
include("action.php");
//print_r($_SESSION); die;

if (isset($_SESSION['usertype']) && $_SESSION['usertype'] != "" && isset($_SESSION['userid']) && $_SESSION['unitid'] != "") {

	$ipaddress = $obj->get_client_ip();
	$loginid = $_SESSION['userid'];
	$unitid = $_SESSION['unitid'];
	$usertype = $_SESSION['usertype'];
	$sessionid = $obj->getvalfield("m_session", "sessionid", "status=1");
	$_SESSION['sessionid'] = $sessionid;
	$createdate = date('Y-m-d H:i:s');
} else {
	echo "<script>location='../index.php?msg=invalid'</script>";
	die;
}
