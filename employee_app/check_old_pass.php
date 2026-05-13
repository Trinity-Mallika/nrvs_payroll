<?php
include("../adminsession.php");
$oldpass = $_SERVER['QUERY_STRING'];
$loginid = $_SESSION['emp_id'];
// $sql =mysql_query("select password from m_employee where password = '$oldpass' and empid ='$loginid'");
// echo "select password from m_employee where password = '$oldpass' and empid ='$loginid'";
// die;
$where = array('password' => $oldpass, 'emp_id' => $loginid);
$cnt = $obj->count_method("employee_master", $where);
// $cnt = mysql_num_rows(result: $sql);
// echo $sql;
$idname = "";

if ($cnt != 0)
   $idname = "<span style='color:green'>Old passsword is correct</span>";
else
   $idname = "<span style='color:red'>Old password is wrong</span>";

echo $idname;
