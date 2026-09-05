<?php
include("../adminsession.php");

$on_duty_id = $obj->test_input($_POST['on_duty_id']);
$type = $obj->test_input($_POST['type']);
$detail_crit = "WHERE on_duty_id='$on_duty_id'";
if ($type == "approved") {
    $detail_crit .= " AND status=1";
} elseif ($type == "rejected") {
    $detail_crit .= " AND status=2";
} elseif ($type == "pending") {
    $detail_crit .= " AND status=0";
}
$sn = 1;
$emp_id = $obj->getvalfield("on_duty_master", "emp_id", "on_duty_id='$on_duty_id'");

$details = $obj->executequery("
    SELECT 
        odd.*, 
        em.first_name AS hod_name,
        em.emp_code AS hod_code,
        u.fullname AS updated_by_name
        
    FROM on_duty_details odd
    LEFT JOIN user u 
        ON odd.approve_by = u.userid
    LEFT JOIN employee_master em 
        ON em.emp_id = odd.hod_apr_id
    $detail_crit
    ORDER BY odd.date ASC
");


// $details = $obj->executequery("SELECT * FROM on_duty_details $detail_crit"); 
?>
<table class="table table-bordered table-sm">
    <thead class="table-light text-center">
        <tr>
            <th>SNo.</th>
            <th>Date </th>
            <th>In Out Time</th>
            <th>Place / With</th>
            <th>Remarks</th>
            <th>Appr <input type="checkbox" id="appr_check" class="form-check-input" /></th>
            <th>Rej <input type="checkbox" id="rej_check" class="form-check-input" /></th>
            <th>Pen <input type="checkbox" id="pen_check" class="form-check-input" /></th>
            <th>HOD Status</th>
            <th>HR Status</th>
            <th>Off Day</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($details as $row) { 
        ?>
            <tr class="detail_row" data-id="<?= $row['on_duty_details_id'] ?>">
                <td class="text-center"><?= $sn++; ?></td>

                <td><input type="hidden" id="modal_date_<?= $row['on_duty_details_id']; ?>" value="<?= $row['date']; ?>">
                    <?= $obj->dateformatindia($row['date']) ?></td>

                <td>
                    <input type="hidden" id="modal_intime_<?= $row['on_duty_details_id']; ?>" value="<?= $row['intime']; ?>">
                    <input type="hidden" id="modal_outtime_<?= $row['on_duty_details_id']; ?>" value="<?= $row['outtime']; ?>">
                    <input type="hidden" id="modal_on_duty_type_<?= $row['on_duty_details_id']; ?>" value="<?= $row['on_duty_type']; ?>">
                    <?= date('h:i A', strtotime($row['intime'])) ?>
                    -
                    <?= date('h:i A', strtotime($row['outtime'])) ?>
                </td>

                <td><?= $row['place'] ?></td>
                <td><?= $row['remark'] ?></td> 
               
                <td class="text-center">
                    <input type="checkbox" <?= $row['status'] == 1 ? 'checked' : '' ?> data-id="<?= $row['on_duty_details_id'] ?>" class="form-check-input approve_chk">
                </td>

                <td class="text-center">
                    <input type="checkbox" <?= $row['status'] == 2 ? 'checked' : '' ?> data-id="<?= $row['on_duty_details_id'] ?>" class="form-check-input reject_chk">
                </td>

                <td class="text-center">
                    <input type="checkbox" <?= $row['status'] == 0 ? 'checked' : '' ?> data-id="<?= $row['on_duty_details_id'] ?>" class="form-check-input pending_chk" >  
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
                    if($row['status'] == "1" || $row['status'] == "2"){
                    ?>
                    <br>
                    
                    <?=$row['updated_by_name']?> 
                        Dt: <?=$obj->dateformatindia($row['approved_date'])?>
                    <?php } ?>

                </td>
                <td></td>

            </tr>
            <tr>
                <td colspan="9">
                    <div class="d-flex align-items-center">
                        <label class="fw-semibold me-2">Remark :</label>
                        <input type="text"
                            class="form-control form-control-sm w-50 remark_input"
                            id="modal_appr_remark_<?= $row['on_duty_details_id'] ?>"
                            value="<?= $row['appr_remark'] ?>">
                    </div>
                </td>
            </tr>
        <?php } ?>

    </tbody>
</table>