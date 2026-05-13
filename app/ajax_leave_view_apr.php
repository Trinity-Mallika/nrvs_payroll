<?php
include("appsession.php");

$id = $_POST['id'];

$data = $obj->executequery("
    SELECT * FROM leave_apply_detail 
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
        } elseif ($row['leave_type'] == 'LWP') {
            $type = "Leave Without Pay";
        } else {
            $type = "-";
        }
?>
<div class="card mb-2 p-2">

    <div class="row">

        <div class="col-6">
            <b>Date:</b><br>
            <?= date('d-m-Y', strtotime($row['date'])) ?>
        </div>

        <div class="col-6">
            <b>Day:</b><br>
            <?= $day ?>
        </div>

        <div class="col-6 mt-2">
            <b>Leave Type:</b><br>
            <?= $type ?>
        </div>

        <div class="col-6 mt-2">
            <b>Status:</b><br>
            <?php
                    if ($row['status'] == 1) {
                        echo "<span class='text-success'>Approved</span>";
                    } elseif ($row['status'] == 2) {
                        echo "<span class='text-danger'>Rejected</span>";
                    } else {
                        echo "<span class='text-warning'>Pending</span>";
                    }
                    ?>
        </div>

        <div class="col-12 mt-2">
            <b>Remark:</b><br>
            <?= $row['remark'] ?: '-' ?>
        </div>

        <div class="col-12 mt-3 d-flex gap-2">

            <?php if ($row['status'] == 0) { ?>

            <button class="btn btn-success btn-sm w-50" onclick="updateLeaveStatus(<?= $row['leave_details_id'] ?>, 1)">
                <i class="fa fa-check"></i> Approve
            </button>

            <button class="btn btn-danger btn-sm w-50" onclick="updateLeaveStatus(<?= $row['leave_details_id'] ?>, 2)">
                <i class="fa fa-times"></i> Reject
            </button>

            <?php } ?>

        </div>

    </div>
</div>
<?php
    }
} else {
    echo "<div class='text-danger'>No Data Found</div>";
}
?>