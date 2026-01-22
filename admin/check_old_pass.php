<?php
include("../adminsession.php");
$oldpass = $_SERVER['QUERY_STRING'];
$loginid = $_SESSION['userid'];

$cnt = $obj->getvalfield("user", "count(*)", "password='$oldpass' and userid='$loginid'");
$idname = "";

if ($cnt != 0)
   $idname = "<span style='color:green'>Old passsword is correct</span>";
else
   $idname = "<span style='color:red'>Old password is wrong</span>";

echo $idname;
