<?php
include("appsession.php");

$id = $_POST['id'];

// $data = $obj->executequery("
//     SELECT * FROM leave_apply_detail 
//     WHERE on_duty_id = '$id'
// ");

$data = $obj->executequery("
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
    WHERE odd.on_duty_id = '$id'
    ORDER BY odd.date
"); 

 
if (!empty($data)) {
    foreach ($data as $row) {
?>
<div class="col-12">
    <div class="card leave-list-card">
        <div class="d-flex justify-content-between">
            <p class="date-text">
                <i class="bi bi-calendar-check-fill me-1"></i>
                <?= date('d-m-Y', strtotime($row['date'])) ?>
            </p>
            <p class="date-text">
                <?= date('h:i A', strtotime($row['intime'])) ?>
                To
                <?= date('h:i A', strtotime($row['outtime'])) ?>
            </p>ṅ
        </div>

        <div class="card mb-1">
            <div class="row small">

                <div class="col-6">
                    <small>On Duty Type</small>
                    <h6 class="fw-semibold"><?= $row['on_duty_type'] ?></h6>
                </div>

                <div class="col-6">
                    <small>Place</small>
                    <h6 class="fw-semibold"><?= $row['place'] ?: '-' ?></h6>
                </div>

                <div class="col-6">
                    <small>With Employee</small>
                    <h6 class="fw-semibold"><?= ucfirst($row['with_employee']) ?: '-' ?></h6>
                </div>

                <div class="col-6">
                    <small>Remark</small>
                    <h6 class="fw-semibold"><?= $row['remark'] ?: '-' ?></h6>
                </div>

                <div class="col-6">
                    <small>HOD Approval Status</small>
                    <h6 class="fw-semibold">
                        <?php
                        if ($row['is_apr_hod'] == 1) {
                            echo "<span class='badge text-bg-success'>Approved</span>";
                        } elseif ($row['is_apr_hod'] == 2) {
                            echo "<span class='badge text-bg-danger'>Rejected</span>";
                        } else {
                            echo "<span class='badge text-bg-warning'>Pending</span>";
                        }

                        if ($row['is_apr_hod'] == 1 || $row['is_apr_hod'] == 2) {
                            echo "<br>";
                            echo $row['hod_code'] . ' - ' . $row['hod_name'];
                            echo "<br>Dt: " . $obj->dateformatindia($row['lastupdated_hod']);
                        }
                        ?>
                    </h6>
                </div>

                <div class="col-6">
                    <small>HR Approval Status</small>
                    <h6 class="fw-semibold">
                        <?php
                        if ($row['status'] == 1) {
                            echo "<span class='badge text-bg-success'>Approved</span>";
                        } elseif ($row['status'] == 2) {
                            echo "<span class='badge text-bg-danger'>Rejected</span>";
                        } else {
                            echo "<span class='badge text-bg-warning'>Pending</span>";
                        }

                        if ($row['status'] == 1 || $row['status'] == 2) {
                            echo "<br>";
                            echo $row['updated_by_name'];
                            echo "<br>Dt: " . $obj->dateformatindia($row['approved_date']);
                        }
                        ?>
                    </h6>
                </div>

            </div>

            <div class="col-12 mt-3 d-flex gap-2">
                <?php if ($row['is_apr_hod'] == 0) { ?>
                <button class="btn btn-success btn-sm w-100"
                    onclick="updateDutyStatus(<?= $row['on_duty_details_id'] ?>,1)">
                    <i class="fa fa-check"></i> Approve
                </button>
                <?php } ?>
            </div>

        </div>
    </div>
</div>

<?php
    }
} else {
    echo "<div class='text-danger'>No Data Found</div>";
}
?>