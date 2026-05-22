<?php include("../action.php");
if (isset($_SESSION['emp_id']) && isset($_SESSION['emp_id']) != "") {
	$ipaddress = $obj->get_client_ip();
	$createdate = date('Y-m-d H:i:s');
	$loginid = $_SESSION['emp_id'];
	// $usertype = $_SESSION['emp_type'];

	// Auto detect device
	$userAgent = $_SERVER['HTTP_USER_AGENT'];
	$ios_user = (strpos($userAgent, 'iPhone') !== false) ? 1 : 0;

	// $ios_user = $obj->getvalfield("employee_master", "is_iphone", "emp_id='$loginid'");
} else {
	echo "<script>location='index.php?msg=invalid' </script>";
	die;
}
