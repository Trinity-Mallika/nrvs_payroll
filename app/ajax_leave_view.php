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
        }elseif ($row['leave_type'] == 'EO') {
            $type = "Extra Off";
        } elseif ($row['leave_type'] == 'L') {
            $type = "Opening Leave";
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
                    <b>HOD Apr Status:</b><br>
                    <?php
                    if ($row['is_apr_hod'] == 1) {
                        echo "<span class='text-success'>Approved</span>";
                    } elseif ($row['is_apr_hod'] == 2) {
                        echo "<span class='text-danger'>Rejected</span>";
                    } else {
                        echo "<span class='text-warning'>Pending</span>";
                    }
                    if ($row['is_apr_hod'] == 1 || $row['is_apr_hod'] == 2) {
                    ?>
                        <br>
                        <?=$row['hod_code'].'-'.$row['hod_name']?> 
                        Dt: <?=$obj->dateformatindia($row['lastupdated_hod'])?>

                    <?php } ?>
                </div>
                <div class="col-6 mt-2">
                    <b>Final Status:</b><br>
                    <?php
                    if ($row['status'] == 1) {
                        echo "<span class='text-success'>Approved</span>";
                    } elseif ($row['status'] == 2) {
                        echo "<span class='text-danger'>Rejected</span>";
                    } else {
                        echo "<span class='text-warning'>Pending</span>";
                    }
                    if ($row['status'] == 1 || $row['status'] == 2) {
                    ?>
                        <br>
                        <?=$row['updated_by_name'] ?> 
                        Dt: <?=$obj->dateformatindia($row['approve_date'])?>

                    <?php } ?>
                </div>

                <div class="col-12 mt-2">
                    <b>Remark:</b><br>
                    <?= $row['remark'] ?: '-' ?>
                </div>

                <div class="col-12 mt-2">
                    <b>Approve Remark:</b><br>
                    <?= $row['appr_remark'] ?: '-' ?>
                </div>
            
            </div>
        </div>
<?php
    }
} else {
    echo "<div class='text-danger'>No Data Found</div>";
}
?>