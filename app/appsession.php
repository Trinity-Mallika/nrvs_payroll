<?php include("../action.php");

if (isset($_SESSION['emp_id']) && $_SESSION['emp_id'] != "") {
    $ipaddress = $obj->get_client_ip();
    $emp_id = $_SESSION['emp_id'];
    $unitid = $obj->getvalfield("employee_master", "unit_id", "emp_id={$_SESSION['emp_id']}");
    $sessionid = $obj->getvalfield("m_session", "sessionid", "status=1");
    $createdate = date('Y-m-d H:i:s');
} else {
    echo "<script>location='index.php?msg=logout'</script>";
    die;
}
