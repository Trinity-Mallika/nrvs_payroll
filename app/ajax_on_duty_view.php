<?php
include("appsession.php");

$id = $_POST['id'];

$data = $obj->executequery("
    SELECT * FROM on_duty_details 
    WHERE on_duty_id = '$id'
");

if (!empty($data)) {

    foreach ($data as $row) {
?>
        <div class="card mb-2 p-2">
            <div class="row">

                <div class="col-6">
                    <b>Date:</b><br>
                    <?= date('d-m-Y', strtotime($row['date'])) ?>
                </div>

                <div class="col-6">
                    <b>In Time:</b><br>
                    <?= !empty($row['intime']) ? date('h:i A', strtotime($row['intime'])) : '--:-- --' ?>
                </div>

                <div class="col-6 mt-2">
                    <b>Out Time:</b><br>
                    <?= !empty($row['outtime']) ? date('h:i A', strtotime($row['outtime'])) : '--:-- --' ?>
                </div>

                <div class="col-6 mt-2">
                    <b>Place:</b><br>
                    <?= $row['place'] ?: '-' ?>
                </div>

                <div class="col-6 mt-2">
                    <b>With Employee:</b><br>
                    <?= $row['with_employee'] ?: '-' ?>
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


            </div>
        </div>
<?php
    }
} else {
    echo "<div class='text-danger'>No Data Found</div>";
}
?>