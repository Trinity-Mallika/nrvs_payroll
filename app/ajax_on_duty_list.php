<?php
include("appsession.php");
$imgpath = "../admin/uploaded/on_duty/";
$from = $_POST['from_date'] ?? date('d-m-Y');
$to   = $_POST['to_date'] ?? date('d-m-Y');

$from = DateTime::createFromFormat('d-m-Y', $from)->format('Y-m-d');
$to   = DateTime::createFromFormat('d-m-Y', $to)->format('Y-m-d'); 

$data = $obj->executequery("
    SELECT 
        m.*, 
        em.first_name,
        em.mobile_no,
        COUNT(d.on_duty_details_id) AS total_days,
        SUM(CASE WHEN d.status = 1 THEN 1 ELSE 0 END) AS approved_days,
        SUM(CASE WHEN d.status = 2 THEN 1 ELSE 0 END) AS rejected_days,
        SUM(CASE WHEN d.status = 0 THEN 1 ELSE 0 END) AS pending_days,
        SUM(CASE WHEN d.is_apr_hod = 1 THEN 1 ELSE 0 END) AS approved_days_hod,
        SUM(CASE WHEN d.is_apr_hod = 2 THEN 1 ELSE 0 END) AS rejected_days_hod,
        SUM(CASE WHEN d.is_apr_hod = 0 THEN 1 ELSE 0 END) AS pending_days_hod
    FROM on_duty_master m

    LEFT JOIN on_duty_details d
    ON m.on_duty_id = d.on_duty_id

    LEFT JOIN employee_master em
        ON m.emp_id = em.emp_id

    WHERE  
        m.unit_id='$unitid' 
        AND em.reporting_manager='$emp_id'
        AND m.type='on_duty'
        AND DATE(m.application_date) BETWEEN '$from' AND '$to'

    GROUP BY m.on_duty_id
    ORDER BY m.on_duty_id DESC
");

?>
<div class="row">
    

    <?php if (!empty($data)) {
        foreach ($data as $row) {

    ?>

                <div class="col-12">
                    <div class="card leave-list-card">
                        <div class="d-flex justify-content-between mb-2">
                            <p class="date-text"><i class="bi bi-calendar-check-fill me-1"></i> <?= date('d M Y', strtotime($row['application_date'])) ?></p>
                            <div class="dropdown">
                                <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                     <?php if (!empty($row['doc_file'])) { ?>
                                    <li><a class="dropdown-item" href="<?= $imgpath . $row['doc_file'] ?>" target="_blank">View Attachment</a></li>
                                    <?php } ?>
                                   <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="openDutyModal(<?= $row['on_duty_id'] ?>)">
                                            View Details
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- <a href="#0" class="btn btn-sm fs-12">View Attachment</a> -->
                        </div>
                        <div class="card mb-1">
                            <div class="row small">
                                <div class="col-12">
                                    <small>Employee</small>
                                    <h6 class="fw-semibold">
                                       <?= $row['first_name']; ?>
                                    </h6>
                                </div>
                                <div class="col-6">
                                    <small>Mobile</small>
                                    <h6 class="fw-semibold"><?= $row['mobile_no'] ?></h6>
                                </div> 
                               <div class="col-6">
                                <small>On Duty Type</small>
                                    <h6 class="fw-semibold"><?= $row['on_duty_type'] ?></h6>
                                </div>

                                <div class="col-6">
                                    <small>Total Days</small>
                                    <h6 class="fw-semibold"><?= $row['total_days'] ?></h6>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6  border-end">
                                <p class="date-text"><i class="bi bi-check-circle-fill me-1"></i> Approve By HOD</p>
                            </div>
                            <div class="col-6">
                                <p class="date-text"><i class="bi bi-check-circle-fill me-1"></i> Approve By HR</p>
                            </div>
                            <div class="col-6 border-end pe-0">
                                <div class="approve-status">
                                    <div class="status-list">
                                        <p class="green"><?= $row['approved_days_hod'] ?? 0 ?> <small>Approved</small> </p>
                                    </div>
                                    <div class="status-list">
                                        <p class="red"> <?= $row['rejected_days_hod'] ?? 0 ?> <small>Rejected</small> </p>
                                    </div>
                                    <div class="status-list">
                                        <p class="yellow"><?= $row['pending_days_hod'] ?? 0 ?> <small>Pending</small> </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6  ps-0">
                                <div class="approve-status">
                                    <div class="status-list">
                                        <p class="green"> <?= $row['approved_days'] ?? 0 ?> <small>Approved</small> </p>
                                    </div>
                                    <div class="status-list">
                                        <p class="red"> <?= $row['rejected_days'] ?? 0 ?> <small>Rejected</small> </p>
                                    </div>
                                    <div class="status-list">
                                        <p class="yellow">  <?= $row['pending_days'] ?? 0 ?> <small>Pending</small> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
        <?php }
    } else { ?>
        <div class="col-12">
            <div class="alert alert-danger text-center rounded-3 shadow-sm">
                No Records Found
            </div>
        </div>
    <?php } ?>

</div>