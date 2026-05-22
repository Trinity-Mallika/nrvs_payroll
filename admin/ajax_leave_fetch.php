<?php include_once("../adminsession.php");

$keyvalue = $obj->test_input($_POST['keyvalue']);
$emp_id = $obj->test_input($_POST['emp_id'] ?? 0);

$details = $obj->executequery("Select * from leave_apply_detail where on_duty_id='$keyvalue' and unit_id='$unitid' and emp_id='$emp_id' order by leave_details_id desc");

$sno = 1;
$leaveDayArr = [
    'FD' => 'Full Day',
    'FHD' => 'First Half Day',
    'SHD' => 'Second Half Day',
    'SL' => 'Sick Leave'
];

$leaveTypeArr = [
    'EL' => 'Earned Leave',
    'EO' => 'EXTRA OFF',
    'L' => 'OPENING LEAVE',
    'WL' => 'Weekly Leave',
    'LWP' => 'Leave Without Pay'
];
foreach ($details as $row) {

?>
    <tr>
        <td><?= $sno++ . ")"; ?> </td>
        <td><?= $obj->dateformatindia($row['date']); ?> </td>
        <td><?= $leaveDayArr[$row['leave_day']] ?? $row['leave_day']; ?></td>
        <td><?= $leaveTypeArr[$row['leave_type']] ?? $row['leave_type']; ?></td>
        <td><?= $row['remark']; ?> </td>

        <td>
            <?php if ($row['status'] == 0) {  ?>
                <button type="button" class="btn btn-danger btn-sm"
                    data-nexttab="pills-experience-tab" onclick="funDel('<?= $row['leave_details_id']; ?>');"><i
                        class="ri-delete-bin-5-line label-icon align-middle fs-14 "></i>Del</button>
                <button type="button" class="btn btn-primary btn-sm"
                    onclick="editLeave('<?= $row['leave_details_id']; ?>','<?= $row['date']; ?>','<?= $row['leave_day']; ?>','<?= $row['leave_type']; ?>','<?= $row['remark']; ?>');">Edit</button>
            <?php } ?>
        </td>
    </tr>
<?php }  ?>