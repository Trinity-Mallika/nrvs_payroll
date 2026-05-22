<?php
include("action.php");

if (isset($_SESSION['userid']) && $_SESSION['userid'] != "" && isset($_SESSION['usertype']) && $_SESSION['usertype'] != "") {

	$ipaddress = $obj->get_client_ip();
	$loginid = $_SESSION['userid'];
	$usertype = $_SESSION['usertype'];
	$createdate = date('Y-m-d H:i:s');
} else {
	echo "<script>location='index.php?msg=invalid' </script>";
	die;
}
