<?php
include("appsession.php");

$id = $_POST['id'];

$data = $obj->executequery("
    SELECT * FROM on_duty_details 
    WHERE on_duty_id = '$id' order by date asc
");

if (!empty($data)) {
    foreach ($data as $row) {
        $updateby_name = $obj->getvalfield("user","fullname","userid='$row[approve_by]'");
?>

<div class="col-12">
    <div class="card leave-list-card">
        <div class="d-flex justify-content-between">
            <p class="date-text"><i
                    class="bi bi-calendar-check-fill me-1"></i> <?= date('d-m-Y', strtotime($row['date'])) ?></p>
            
        </div>
        <div class="card mb-1">
            <div class="row small">
                <div class="col-6">
                    <small>In Time:</small>
                    <h6 class="fw-semibold">
                        <?= !empty($row['intime']) ? date('h:i A', strtotime($row['intime'])) : '--:-- --' ?>
                    </h6>
                </div>
                 <div class="col-6">
                    <small>Out Time:</small>
                    <h6 class="fw-semibold">
                      <?= !empty($row['outtime']) ? date('h:i A', strtotime($row['outtime'])) : '--:-- --' ?>
                    </h6>
                </div>
                  <div class="col-6">
                    <small>Place:</small>
                    <h6 class="fw-semibold">
                         <?= $row['place'] ?: '-' ?>
                    </h6>
                </div>
                 <div class="col-6">
                    <small>Out Time:</small>
                    <h6 class="fw-semibold">
                      <?= !empty($row['outtime']) ? date('h:i A', strtotime($row['outtime'])) : '--:-- --' ?>
                    </h6>
                </div>
                <div class="col-6">
                    <small>With Employee:</small>
                    <h6 class="fw-semibold">
                       <?= $row['with_employee'] ?: '-' ?>
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
                        <?= $updateby_name?>
                        Dt: <?= $obj->dateformatindia($row['approved_date']) ?>
                        <?php } ?>
                    </h6>
                </div>
                <div class="col-12">
                    <small>Remark</small>
                    <h6 class="fw-semibold mb-0"> <?= $row['remark'] ?: '-' ?></h6>
                </div>
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