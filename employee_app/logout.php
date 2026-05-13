<?php
session_start();
if (isset($_COOKIE['et_mobile_no'])) {
   unset($_COOKIE['et_mobile_no']);
   setcookie('et_mobile_no', null, -1, '/'); // empty value and old timestamp

}
if (isset($_COOKIE['et_password'])) {
   unset($_COOKIE['et_password']);
   setcookie('et_password', null, -1, '/'); // empty value and old timestamp

}

session_destroy();
header('Location: index.php?msg=logout');
