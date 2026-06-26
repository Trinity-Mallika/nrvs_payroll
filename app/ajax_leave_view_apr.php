<?php
include("appsession.php");

$id = $_POST['id'];

// $data = $obj->executequery("
//     SELECT * FROM leave_apply_detail 
//     WHERE on_duty_id = '$id'
// ");

$data = $obj->executequery("
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
    WHERE on_duty_id = '$id' 
");

if (!empty($data)) {
    foreach ($data as $row) {
        // Day conversion
        if ($row['leave_day'] == 'FD') {
            $day = "Full Day";
        } elseif ($row['leave_day'] == 'FHD') {
            $day = "First Half";
        } elseif ($row['leave_day'] == 'SHD') {
            $day = "Second Half";
        } else {
            $day = "-";
        }

        // Leave Type conversion
        if ($row['leave_type'] == 'EL') {
            $type = "Earned Leave";
        } elseif ($row['leave_type'] == 'WL') {
            $type = "Weekly Leave";
        } elseif ($row['leave_type'] == 'CO') {
            $type = "C Off";
        } elseif ($row['leave_type'] == 'LWP') {
            $type = "Leave Without Pay";
        } elseif ($row['leave_type'] == 'EO') {
            $type = "Extra Off";
        } elseif ($row['leave_type'] == 'L') {
            $type = "Opening Leave";
        } else {
            $type = "-";
        }
?>
<div class="col-12">
    <div class="card leave-list-card">
        <div class="d-flex justify-content-between">
            <p class="date-text"><i
                    class="bi bi-calendar-check-fill me-1"></i><?= date('d-m-Y', strtotime($row['date'])) ?></p>
            <p class="date-text"><i class="bi bi-clock-history me-1"></i><?= $day ?> </p>
        </div>
        <div class="card mb-1">
            <div class="row small">
                <div class="col-12">
                    <small>Leave Type:</small>
                    <h6 class="fw-semibold">
                        <?= $type ?>
                    </h6>
                </div>
                <div class="col-12">
                    <small>HOD Approve Status:</small>


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
                        ?>
                        <br>
                        <?= $row['hod_code'] . '-' . $row['hod_name'] ?>
                        Dt: <?= $obj->dateformatindia($row['lastupdated_hod']) ?>
                        <?php } ?>
                    </h6>
                </div>
                <div class="col-12">
                    <small>HR Approve Status:</small>
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
                            ?>
                        <br>
                        <?= $row['updated_by_name'] ?>
                        Dt: <?= $obj->dateformatindia($row['approve_date']) ?>
                        <?php } ?>
                    </h6>
                </div>
                <div class="col-12">
                    <small>Remark</small>
                    <h6 class="fw-semibold mb-0"> <?= $row['remark'] ?: '-' ?></h6>
                </div>
            </div>
            <div class="col-12 mt-3 d-flex gap-2">
                <?php if ($row['is_apr_hod'] == 0) { ?>
                <button class="btn btn-success btn-sm w-100"
                    onclick="updateLeaveStatus(<?= $row['leave_details_id'] ?>, 1)">
                    <i class="fa fa-check"></i> Approve
                </button>
                <?php } ?>
                <!-- <button class="btn btn-danger btn-sm w-50" onclick="updateLeaveStatus(< $row['leave_details_id'] ?>, 2)">
                        <i class="fa fa-times"></i> Reject
                    </button> -->
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