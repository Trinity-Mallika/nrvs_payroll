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
    'CO' => 'C Off',
    'LWP' => 'Leave Without Pay'
];
foreach ($details as $row) {
?>
<tr>
    <!-- Sr No -->
    <td>
        <?= $sno++ . ")"; ?>

        <!-- Hidden ID -->
        <input type="hidden" class="leave_details_id" value="<?= $row['leave_details_id']; ?>">
    </td>

    <!-- Date -->
    <td>
        <input type="date" class="form-control form-control-sm leave_date" value="<?= $row['date']; ?>"
            onchange="saveLeaveRow(this);">
    </td>

    <!-- Leave Day -->
    <td>
        <select class="form-select form-select-sm leave_day" onchange="saveLeaveRow(this);">

            <option value="FD" <?= $row['leave_day'] == 'FD' ? 'selected' : ''; ?>>
                Full Day
            </option>

            <option value="FHD" <?= $row['leave_day'] == 'FHD' ? 'selected' : ''; ?>>
                First Half Day
            </option>

            <option value="SHD" <?= $row['leave_day'] == 'SHD' ? 'selected' : ''; ?>>
                Second Half Day
            </option>

        </select>
    </td>

    <!-- Leave Type -->
    <td>
        <select class="form-select form-select-sm leave_type" onchange="saveLeaveRow(this);">

            <option value="EL" <?= $row['leave_type'] == 'EL' ? 'selected' : ''; ?>>
                EARNED LEAVE
            </option>

            <option value="EO" <?= $row['leave_type'] == 'EO' ? 'selected' : ''; ?>>
                EXTRA OFF
            </option>

            <option value="CO" <?= $row['leave_type'] == 'CO' ? 'selected' : ''; ?>>
                C-OFF
            </option>

        </select>
    </td> 
    <!-- Remark -->
    <td>
       <input type="text"
       class="form-control form-control-sm leave_remark"
       value="<?= htmlspecialchars($row['remark'] ?? '', ENT_QUOTES); ?>"
       onchange="saveLeaveRow(this);"
           onkeydown="if(event.key === 'Enter') { event.preventDefault(); saveLeaveRow(this); }">
    </td> 
    <!-- Action -->
    <td>
        <?php if ($row['status'] == 0) { ?>

        <button type="button" class="btn btn-danger btn-sm" onclick="funDel('<?= $row['leave_details_id']; ?>');">
            <i class="ri-delete-bin-5-line label-icon align-middle fs-14"></i>
            Del
        </button>

        

        <?php } elseif ($row['status'] == 1) { ?>

        <span class="badge bg-success text-white">
            Approved
        </span>

        <?php } elseif ($row['status'] == 2) { ?>

        <span class="badge bg-danger text-white">
            Rejected
        </span>

        <?php } else { ?>

        <span class="badge bg-warning text-white">
            Pending
        </span>

        <?php } ?>
    </td>
</tr>
<?php }  ?>