<?php
include("appsession.php");
$imgpath = "../admin/uploaded/on_duty/";
$from = $_POST['from_date'] ?? date('Y-m-d');
$to   = $_POST['to_date'] ?? date('Y-m-d');

// $data = $obj->executequery("
//     SELECT m.*, MAX(d.status) as status
//     FROM on_duty_master m
//     LEFT JOIN leave_apply_detail d 
//     ON m.on_duty_id = d.on_duty_id
//       LEFT JOIN employee_master em
//     ON m.emp_id = em.emp_id
//     WHERE  m.unit_id='$unitid' AND em.reporting_manager='$emp_id'
//     AND m.type='leave'
//     AND DATE(m.application_date) BETWEEN '$from' AND '$to'
//     GROUP BY m.on_duty_id
//     ORDER BY m.on_duty_id DESC
// ");
 
$data = $obj->executequery("
    SELECT 
        m.*, 
        em.first_name,
       
        MAX(d.status) as status,
        SUM(
            CASE 
                WHEN d.status = 1 THEN 
                    CASE 
                        WHEN d.leave_day IN ('FD','SL') THEN 1
                        WHEN d.leave_day IN ('FHD','SHD') THEN 0.5
                        ELSE 0
                    END
                ELSE 0
            END
        ) as approved_days,

        SUM(
            CASE 
                WHEN d.status = 2 THEN 
                    CASE 
                        WHEN d.leave_day IN ('FD','SL') THEN 1
                        WHEN d.leave_day IN ('FHD','SHD') THEN 0.5
                        ELSE 0
                    END
                ELSE 0
            END
        ) as rejected_days,

        SUM(
            CASE 
                WHEN d.status = 0 THEN 
                    CASE 
                        WHEN d.leave_day IN ('FD','SL') THEN 1
                        WHEN d.leave_day IN ('FHD','SHD') THEN 0.5
                        ELSE 0
                    END
                ELSE 0
            END
        ) as pending_days

    FROM on_duty_master m

    LEFT JOIN leave_apply_detail d 
        ON m.on_duty_id = d.on_duty_id

    LEFT JOIN employee_master em
        ON m.emp_id = em.emp_id
 
    
    WHERE  
        m.unit_id='$unitid' 
        AND m.emp_id='$emp_id'
        AND m.type='leave'
        AND DATE(m.application_date) BETWEEN '$from' AND '$to'

    GROUP BY m.on_duty_id
    ORDER BY m.on_duty_id DESC
");

?>
<div class="row">
    <?php
    $statusArr = [
        0 => ['label' => 'Pending', 'class' => 'warning'],
        1 => ['label' => 'Approved', 'class' => 'success'],
        2 => ['label' => 'Rejected', 'class' => 'danger'],
    ];
    ?>

    <div class="row g-3">
        <?php if (!empty($data)) {
            foreach ($data as $row) {
 
        ?>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">

                <!-- Header -->
                <div class="card-body pb-2">

                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <div>
                            <h6 class="mb-0 fw-bold text-primary">
                                <?= date('d M Y', strtotime($row['application_date'])) ?>
                            </h6>
                            <small class="text-muted">Application Date</small>
                        </div>


                    </div>

                    <hr class="my-2">

                    <!-- Info Grid -->
                    <div class="row g-2 small">
                        <div class="col-6">
                            <span class="text-muted">Employee</span><br>
                            <span class="fw-semibold">
                                <?= $row['first_name']; ?>
                            </span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">Mobile</span><br>
                            <span class="fw-semibold"><?= $row['contact_no'] ?></span>
                        </div>

                        <div class="col-6">
                            <span class="text-muted">Substitute</span><br>
                            <span class="fw-semibold">
                                <?= $obj->getvalfield("employee_master", "first_name", "emp_id='" . $row['substitute_emp_id'] . "'"); ?>
                            </span>
                        </div>

                        <div class="col-6">
                            <span class="text-muted">Leave Address</span><br>
                            <span class="fw-semibold text-truncate d-block">
                                <?= $row['leave_address'] ?>
                            </span>
                        </div>

                        <div class="col-12">
                            <span class="text-muted">Reason</span><br>
                            <span class="fw-semibold"><?= $row['reason'] ?></span>
                        </div>


                        <!-- File -->
                        <div class="col-12 mt-1">
                            <?php if (!empty($row['doc_file'])) { ?>
                            <a href="<?= $imgpath . $row['doc_file'] ?>" target="_blank"
                                class="btn btn-sm btn-light border w-100">
                                <i class="fa fa-file"></i> View Attachment
                            </a>
                            <?php } else { ?>
                            <span class="text-muted">No Attachment</span>
                            <?php } ?>
                        </div>

                        <div class="row mt-3 text-center">

                            <div class="col-4">
                                <div class=" rounded-3 bg-success bg-opacity-10">
                                    <div class="fw-bold text-success fs-6">
                                        <?= $row['approved_days'] ?? 0 ?>
                                    </div>
                                    <small class="text-muted">Approved</small>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class=" rounded-3 bg-danger bg-opacity-10">
                                    <div class="fw-bold text-danger fs-6">
                                        <?= $row['rejected_days'] ?? 0 ?>
                                    </div>
                                    <small class="text-muted">Rejected</small>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class=" rounded-3 bg-warning bg-opacity-10">
                                    <div class="fw-bold text-warning fs-6">
                                        <?= $row['pending_days'] ?? 0 ?>
                                    </div>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="row mt-3 g-2">

                        <div class="col-4">
                            <button onclick="openDutyModal(<?= $row['on_duty_id'] ?>)"
                                class="btn btn-warning btn-sm w-100">
                                <i class="fa fa-eye"></i> View
                            </button>
                        </div>

                        <?php if ($row['status'] != 1) { ?>
                        <div class="col-4">
                            <a href="leave_apply.php?on_duty_id=<?= $row['on_duty_id'] ?>"
                                class="btn btn-primary btn-sm w-100">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                        </div>

                        <div class="col-4">
                            <button onclick="deleteLeave(<?= $row['on_duty_id'] ?>)"
                                class="btn btn-danger btn-sm w-100">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </div>
                        <?php } ?>

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
</div>