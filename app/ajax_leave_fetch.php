<?php include_once("appsession.php");

$keyvalue = $obj->test_input($_POST['keyvalue']);

$details = $obj->executequery("Select * from leave_apply_detail where on_duty_id='$keyvalue' and unit_id='$unitid' and createdby='$emp_id' order by leave_details_id desc");
$sno = 1;
$leaveDayArr = [
    'FD' => 'Full Day',
    'FHD' => 'First Half Day',
    'SHD' => 'Second Half Day',
    'SL' => 'Sick Leave'
];

$leaveTypeArr = [
    'EL' => 'Earned Leave',
    'WL' => 'Weekly Leave',
    'LWP' => 'Leave Without Pay'
];
foreach ($details as $row) {
    $statusArr = [
        0 => ['label' => 'Pending',  'class' => 'warning'],
        1 => ['label' => 'Approved', 'class' => 'success'],
        2 => ['label' => 'Rejected', 'class' => 'danger'],
    ];

    $status = $statusArr[$row['status']] ?? ['label' => 'Unknown', 'class' => 'secondary'];
?>
    <div class="card card-body p-2 mb-2 position-relative">
        <div class="position-absolute top-0 end-0 p-2 d-flex gap-1">

            <!-- Edit -->
            <?php if ($row['status'] == 0) {  ?>
                <a href="javascript:void(0)"
                    class="btn btn-sm btn-outline-primary"
                    title="Edit"
                    onclick="editLeave(
               '<?= $row['leave_details_id']; ?>',
               '<?= $row['date']; ?>',
               '<?= $row['leave_day']; ?>',
               '<?= $row['leave_type']; ?>',
               '<?= htmlspecialchars($row['remark'], ENT_QUOTES); ?>'
           );">
                    <i class="bi bi-pencil-fill"></i>
                </a>

                <!-- Delete -->
                <a href="javascript:void(0)"
                    class="btn btn-sm btn-outline-danger"
                    title="Delete"
                    onclick="funDel('<?= $row['leave_details_id'] ?>')">
                    <i class="bi bi-trash-fill"></i>
                </a>
            <?php } ?>
        </div>

        <table class="table table-borderless table-sm mb-0" style="font-size: 14px;">
            <tr>
                <td width="40%">Date</td>
                <td>: </td>
                <th class="text-primary-emphasis"><?= $obj->dateformatindia($row['date']); ?> </th>
            </tr>
            <tr>
                <td>Day</td>
                <td>: </td>
                <th class="text-primary-emphasis"><?= $leaveDayArr[$row['leave_day']] ?? $row['leave_day']; ?></th>
            </tr>
            <tr>
                <td>Leave type</td>
                <td>: </td>
                <th class="text-primary-emphasis"><?= $leaveTypeArr[$row['leave_type']] ?? $row['leave_type']; ?></th>
            </tr>

            <tr>
                <td>Remark</td>
                <td>: </td>
                <th class="text-primary-emphasis"><?= $row['remark']; ?> </th>
            </tr>
            <tr>
                <td>Status</td>
                <td>: </td>
                <th class="text-primary-emphasis"> <span class="badge bg-<?= $status['class']; ?>">
                        <?= $status['label']; ?>
                    </span></th>
            </tr>
        </table>
    </div>

<?php }  ?>