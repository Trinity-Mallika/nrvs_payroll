<?php
include("../adminsession.php");

$emp_id = $_POST['emp_id'];
$month  = $_POST['month'];
$year   = $_POST['year'];

$earning_leave = $obj->getEarningLeave(
    $emp_id,
    $sessionid,
    $month,
    $year
);

$extra_off = $obj->getExtraOffBalance(
    $emp_id,
    $month,
    $year
);

$coff_leave = $obj->getEmpCoffLeave(
    $emp_id,
    $sessionid,
    $month,
    $year
);

$total_balance =
    $earning_leave +
    ($extra_off['balance'] ?? 0) +
    $coff_leave;

echo number_format($total_balance, 1);
?>