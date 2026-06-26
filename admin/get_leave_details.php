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
$appication_datee = $obj->getvalfield("on_duty_master", "application_date", "on_duty_id='$on_duty_id'");
$applyMonth = (int)date("m", strtotime($appication_datee))??date('n');
$applyYear  = (int)date("Y", strtotime($appication_datee))??date('Y');
//$details = $obj->executequery("SELECT * FROM leave_apply_detail $detail_crit");
$details = $obj->executequery("
    SELECT 
        lad.*, 
        em.first_name AS hod_name,
        em.emp_code AS hod_code,
        u.fullname AS updated_by_name
        
    FROM leave_apply_detail lad
    LEFT JOIN user u 
        ON lad.approve_by = u.userid
    LEFT JOIN employee_master em 
        ON em.emp_id = lad.hod_apr_id
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
    'CO' => 'C Off',
    'LWP' => 'Leave Without Pay'
];

 
$opening_leave_balance =
    $obj->get_opening_leave_balance(
        $emp_id,
        $sessionid,
        $applyMonth,
        $applyYear
    );

$total_earning_leave =
    $obj->getEarningLeave(
        $emp_id,
        $sessionid, 
        $applyMonth,
        $applyYear
    );

$extra_off =
    $obj->getExtraOffBalance(
        $emp_id,
        $applyMonth,
        $applyYear
    );

    $total_coff = $obj->getEmpCoffLeave(
    $emp_id,
    $sessionid,
    $applyMonth,
    $applyYear
);
 $applyMonthName = date("F", mktime(0,0,0,$applyMonth,1,$applyYear));
?>Leave Balance As On <?=$applyMonthName?>
<div class="row mb-3"> 
    <!-- <div class="col-md-3">
        <div class="alert alert-primary py-2 mb-2">
            <strong>Opening Leave :</strong>
            < number_format($opening_leave_balance,1) ?>
        </div>
    </div> -->

    <div class="col-md-4">
        <div class="alert alert-primary py-2 mb-2">
            <strong>Earn Leave :</strong>
            <?= number_format($total_earning_leave,1) ?>
        </div>
    </div>

    <div class="col-md-4">
        <div class="alert alert-primary py-2 mb-2">
            <strong>Extra Off :</strong>
            <?= number_format($extra_off['balance'],1) ?>
        </div>
    </div>
     <div class="col-md-4">
        <div class="alert alert-primary py-2 mb-2">
            <strong>C-Off :</strong>
             <?= number_format($total_coff,1) ?>
        </div>
    </div>

</div>
 
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
            <th>Pen <input type="checkbox" id="pen_check" class="form-check-input" /></th>
            <th>HOD Status</th>
            <th>HR Status</th>

        </tr>
    </thead>
    <tbody>

        <?php foreach ($details as $row) {
        ?>
            <tr class="detail_row" data-id="<?= $row['leave_details_id'] ?>" data-odid="<?= $row['on_duty_id'] ?>">
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
                        <option value="EO" <?= ($row['leave_type'] == 'CO') ? 'selected' : '' ?>>C-OFF</option> 
                        <!-- <option value="L" < ($row['leave_type'] == 'L') ? 'selected' : '' ?>>OPENING LEAVE</option>
                        <option value="LWP" < ($row['leave_type'] == 'LWP') ? 'selected' : '' ?>>LEAVE WITHOUT PAY</option> -->
                    </select>
                </td>
                <td><?= $row['remark'] ?></td>

                <td class="text-center">
                    <!-- <input type="checkbox" < $row['status'] == 1 ? 'checked' : '' ?> data-id="< $row['leave_details_id'] ?>" class="form-check-input approve_chk" < ($row['status'] == 1 || $row['status'] == 2) ? 'disabled' : '' ?>> -->

                    <input 
                        type="checkbox"
                        <?= $row['status'] == 1 ? 'checked' : '' ?>
                        data-id="<?= $row['leave_details_id'] ?>"
                        class="form-check-input approve_chk" 
                    >

                </td>

                <td class="text-center">
                    <input type="checkbox" <?= $row['status'] == 2 ? 'checked' : '' ?> data-id="<?= $row['leave_details_id'] ?>" class="form-check-input reject_chk"  >  
                </td>

                <td class="text-center">
                    <input type="checkbox" <?= $row['status'] == 0 ? 'checked' : '' ?> data-id="<?= $row['leave_details_id'] ?>" class="form-check-input pending_chk">  
                </td>

                <td class="text-center">
                    <?php
                    if ($row['is_apr_hod'] == "1") {
                        echo '<span class="badge bg-success text-white">Approved</span>';
                    } elseif ($row['is_apr_hod'] == "2") {
                        echo '<span class="badge bg-danger text-white">Rejected</span>';
                    } else {
                        echo '<span class="badge bg-warning text-white">Pending</span>';
                    }
                     if ($row['is_apr_hod'] == 1 || $row['is_apr_hod'] == 2) {
                    ?>
                        <br>
                        <?=$row['hod_code'].'-'.$row['hod_name']?> 
                        Dt: <?=$obj->dateformatindia($row['lastupdated_hod'])?>

                    <?php } ?>
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
                        Dt: <?=$obj->dateformatindia($row['approve_date'])?>

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