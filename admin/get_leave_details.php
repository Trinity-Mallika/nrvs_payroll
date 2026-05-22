<?php
include("../adminsession.php");

$on_duty_id = $obj->test_input($_POST['on_duty_id']);
$type = $obj->test_input($_POST['type']);
$detail_crit = "WHERE on_duty_id='$on_duty_id'";
if ($type == "approved") {
    $detail_crit .= " AND lad.status=1";
} elseif ($type == "rejected") {
    $detail_crit .= " AND lad.status=2";
} elseif ($type == "pending") {
    $detail_crit .= " AND lad.status=0";
}
$sn = 1;
$emp_id = $obj->getvalfield("on_duty_master", "emp_id", "on_duty_id='$on_duty_id'");
//$details = $obj->executequery("SELECT * FROM leave_apply_detail $detail_crit");
$details = $obj->executequery("
    SELECT 
        lad.*, 
        u.fullname AS updated_by_name
    FROM leave_apply_detail lad
    LEFT JOIN user u 
        ON lad.updatedby = u.userid
    $detail_crit
    ORDER BY lad.date ASC
");

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
?>
<table class="table table-bordered table-sm">
    <thead class="table-light text-center">
        <tr>
            <th>SNo.</th>
            <th>Date </th>
            <th>Day</th>
            <th>Leave Type</th>

            <th>Remarks</th>
            <th>Appr <input type="checkbox" id="appr_check" class="form-check-input" /></th>
            <th>Rej <input type="checkbox" id="rej_check" class="form-check-input" /></th>
            <th>Status</th>

        </tr>
    </thead>
    <tbody>

        <?php foreach ($details as $row) {
        ?>
            <tr class="detail_row" data-id="<?= $row['leave_details_id'] ?>">
                <td class="text-center"><?= $sn++; ?></td>

                <td><input type="hidden" id="modal_date_<?= $row['leave_details_id']; ?>" value="<?= $row['date']; ?>">
                    <?= $obj->dateformatindia($row['date']) ?></td>

                <td>
                    <input type="hidden" id="modal_leave_day_<?= $row['leave_details_id'] ?>" value="<?= $row['leave_day'] ?>">
                    <?= $leaveDayArr[$row['leave_day']] ?? $row['leave_day']; ?>
                </td>
                <td>
                    <select class="form-select form-select-sm chosen-select" id="modal_leave_type_<?= $row['leave_details_id'] ?>">
                        <option value="EL" <?= ($row['leave_type'] == 'EL') ? 'selected' : '' ?>>EARNED LEAVE</option> 
                        <option value="EO" <?= ($row['leave_type'] == 'EO') ? 'selected' : '' ?>>EXTRA OFF</option>
                        <option value="L" <?= ($row['leave_type'] == 'L') ? 'selected' : '' ?>>OPENING LEAVE</option>
                        <option value="LWP" <?= ($row['leave_type'] == 'LWP') ? 'selected' : '' ?>>LEAVE WITHOUT PAY</option>
                    </select>

                </td>

                <td><?= $row['remark'] ?></td>

                <td class="text-center">
                    <input type="checkbox" <?= $row['status'] == 1 ? 'checked' : '' ?> data-id="<?= $row['leave_details_id'] ?>" class="form-check-input approve_chk" <?= ($row['status'] == 1 || $row['status'] == 2) ? 'disabled' : '' ?>>
                </td>

                <td class="text-center">
                    <input type="checkbox" <?= $row['status'] == 2 ? 'checked' : '' ?> data-id="<?= $row['leave_details_id'] ?>" class="form-check-input reject_chk" <?= ($row['status'] == 1 || $row['status'] == 2) ? 'disabled' : '' ?>>
                </td>

                  <td class="text-center">
                    <?php
                    if ($row['status'] == "1") {
                        echo '<span class="badge bg-success text-white">Approved</span>';
                    } elseif ($row['status'] == "2") {
                        echo '<span class="badge bg-danger text-white">Rejected</span>';
                    } else {
                        echo '<span class="badge bg-warning text-white">Pending</span>';
                    }
                     if ($row['status'] == 1 || $row['status'] == 2) {
                    ?>
                        <br>
                        <?=$row['updated_by_name']?> 
                        Dt: <?=$obj->dateformatindia($row['lastupdated'])?>

                    <?php } ?>
                </td>

            </tr>
            <tr>
                <td colspan="9">
                    <div class="d-flex align-items-center">
                        <label class="fw-semibold me-2">Remark :</label>
                        <input type="text"
                            class="form-control form-control-sm w-50 remark_input"
                            id="modal_appr_remark_<?= $row['leave_details_id'] ?>"
                            value="<?= $row['appr_remark'] ?>">
                    </div>
                </td>
            </tr>
        <?php } ?>

    </tbody>
</table>