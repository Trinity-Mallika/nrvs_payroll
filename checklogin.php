<?php include("action.php");

if (isset($_POST['login'])) {

	$username = $obj->test_input($_POST['username']);
	$unit_id  = $obj->test_input($_POST['unit_id']);
	$password = $obj->test_input($_POST['password']);


	if ($username == "" || $password == "") {
		echo "<script>location='index.php?msg=blank'</script>";
		exit;
	}
	// Login check
	$count = $obj->login_method("user", $username, $password);


	if ($count >= 1) {
		$user_data = $obj->select_record("user", ['username' => $username, 'password' => $password]);

		if ($user_data['usertype'] == 'management' || $user_data['usertype'] == 'super_management') {
			$session_data = $obj->session_method_management("user", $username, $password);

			$_SESSION['userid']   = $session_data['userid'];
			$_SESSION['usertype'] = $session_data['usertype'];

			// jis unit se login kare wahi set
			$_SESSION['unitid'] = $unit_id;

			$_SESSION['sessionid'] = $session_data['session_id'];

			echo "<script>location='management/dashboard.php'</script>";
			exit;
		} else {
			// Get user data
			$session_data = $obj->session_method("user", $username, $password, $unit_id);
			// If user is NOT admin → check unit_id

			$unit_count = $obj->getvalfield("user", "count(*)", "username='$username' AND password='$password' AND FIND_IN_SET('$unit_id', unit_id)");

			if ($unit_count == 0) {
				echo "<script>location='index.php?msg=wrong_unit'</script>";
				exit;
			}

			// echo $session_data['unit_id'];s
			// die;
			// LOGIN SUCCESS
			$_SESSION['userid']   = $session_data['userid'];
			$_SESSION['usertype'] = $session_data['usertype'];
			$_SESSION['unitid'] = $unit_id;
			$_SESSION['sessionid'] = $session_data['session_id'];
			$obj->applyEmployeePromotion($unit_id);

			echo "<script>location='admin/dashboard.php'</script>";
			exit;
		}
	} else {
		echo "<script>location='index.php?msg=error'</script>";
		exit;
	}
}