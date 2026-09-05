<?php
include("../adminsession.php");

$emp_id = $_POST['emp_id'];
$month  = $_POST['month'];
$year   = $_POST['year'];

// $opening_leave =
//     $obj->get_opening_leave_balance(
//         $emp_id,
//         $sessionid,
//         $month,
//         $year
//     );

$earning_leave =
    $obj->getEarningLeave(
        $emp_id,
        $sessionid,
        $month,
        $year
    );

$extra_off =
    $obj->getExtraOffBalance(
        $emp_id,
        $month,
        $year
    );

$coff_leave =
    $obj->getEmpCoffLeave(
        $emp_id,
        $sessionid,
        $month,
        $year
    );

$total_balance = 
    $earning_leave +
    ($extra_off['balance'] ?? 0) +
    $coff_leave;

$month_name = date(
    "F Y",
    strtotime("$year-$month-01")
);
?>

<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <tr>
            <th colspan="2" class="text-center bg-primary text-white">
                Leave Balance As On <?= $month_name ?>
            </th>
        </tr>

        <!-- <tr>
            <th>Opening Leave</th>
            <td><number_format($opening_leave,1) ?></td>
        </tr> -->

        <tr>
            <th>Earn Leave</th>
            <td><?= number_format($earning_leave,1) ?></td>
        </tr>

        <tr>
            <th>Extra Off</th>
            <td><?= number_format($extra_off['balance'] ?? 0,1) ?></td>
        </tr>

        <tr>
            <th>C-Off</th>
            <td><?= number_format($coff_leave,1) ?></td>
        </tr>

        <tr class="table-success">
            <th>Total Balance</th>
            <th><?= number_format($total_balance,1) ?></th>
        </tr>
    </table>
</div>