<?php
include("appsession.php");

$pagename = "emp_on_duty_list.php";
$title = "On Duty List";

// $data = $obj->executequery("SELECT * FROM on_duty_master 
//     WHERE emp_id='$emp_id' AND unit_id='$unitid' and type='on_duty'
//     ORDER BY on_duty_id DESC");

$data = $obj->executequery("
    SELECT m.*, 
    MAX(d.status) as status
    FROM on_duty_master m
    LEFT JOIN on_duty_details d 
    ON m.on_duty_id = d.on_duty_id
    WHERE m.emp_id='$emp_id' 
    AND m.unit_id='$unitid' 
    AND m.type='on_duty'
    GROUP BY m.on_duty_id
    ORDER BY m.on_duty_id DESC
");

$imgpath = "../admin/uploaded/on_duty/";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title><?= $title ?></title>
    <?php include("inc/css-file.php"); ?>
</head>

<body class="dashboard">
    <section class="top-sec ">
        <?php include("inc/header.php"); ?>

        <div class="container px-3 px-md-4">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5> </h5>
                <a href="emp_on_duty.php" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Add New
                </a>
            </div>

            <!-- Table -->
            <!-- <div class="card border-0 shadow-lg mb-3"> -->
            <div class="row">
                <?php
                if (!empty($data)) {
                    foreach ($data as $row) {
                ?>
                        <div class="card border-0 shadow-lg mb-3">
                            <div class="card-body">
                                <!-- Top Row -->
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1 text-primary">
                                        <?= date('d-m-Y', strtotime($row['application_date'])) ?>
                                    </h6>

                                    <a href="javascript:void(0);"
                                        onclick="openDutyModal(<?= $row['on_duty_id'] ?>)"
                                        class="btn btn-sm btn-warning">
                                        <i class="fa fa-eye">View</i>
                                    </a>
                                </div>

                                <!-- <hr class="my-2"> -->

                                <!-- <hr class="my-2"> -->
                                <div class="row mb-2">

                                    <!-- On Duty Type -->
                                    <div class="col-5 fw-bold">
                                        On Duty Type
                                    </div>
                                    <div class="col-7">:
                                        <?= $row['on_duty_type'] ?>
                                    </div>

                                    <!-- On Duty Type -->
                                    <div class="col-5 fw-bold">
                                        Application Dt
                                    </div>
                                    <div class="col-7">:
                                        <?= $row['application_date'] ?>
                                    </div>


                                    <!-- Total Days -->
                                    <div class="col-5 fw-bold">
                                        Total Days
                                    </div>
                                    <div class="col-7">:
                                        <?= $row['total_day'] ?>
                                    </div>

                                    <!-- Attached File -->
                                    <div class="col-5 fw-bold">
                                        Attached File
                                    </div>
                                    <div class="col-7"> :
                                        <?php if (!empty($row['doc_file'])) { ?>
                                            <a href="<?= $imgpath . $row['doc_file'] ?>"
                                                target="_blank"
                                                class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i> View File
                                            </a>
                                        <?php } else { ?>
                                            <span class="text-muted">No File</span>
                                        <?php } ?>
                                    </div>

                                </div>

                                <div class="row mt-2">
                                    <?php if ($row['status'] != 1) { ?>
                                        <!-- Edit -->
                                        <div class="col-6">
                                            <a href="emp_on_duty.php?on_duty_id=<?= $row['on_duty_id'] ?>"
                                                class="btn btn-sm btn-green w-100">
                                                <i class="fa fa-edit">Edit</i>
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <!-- Delete -->
                                            <button onclick="deleteOnDuty(<?= $row['on_duty_id'] ?>)"
                                                class="btn btn-sm btn-red w-100">
                                                <i class="fa fa-trash">Delete</i>
                                            </button>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php }
                } else { ?>
                    <div class="col-12">
                        <div class="alert alert-danger text-center">
                            No Records Found
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!-- </div> -->

        </div>

        <!-- Modal -->
        <div class="modal fade" id="dutyModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">On Duty Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body" id="modalContent">
                        Loading...
                    </div>

                </div>
            </div>
        </div>
    </section>
    <?php include("inc/js-file.php"); ?>

</body>

<script>
    function openDutyModal(id) {

        $("#dutyModal").modal('show');
        $("#modalContent").html("Loading...");

        $.ajax({
            url: 'ajax_on_duty_view.php',
            type: 'POST',
            data: {
                id: id
            },
            success: function(data) {
                $("#modalContent").html(data);
            }
        });
    }
</script>
<script>
    function deleteOnDuty(id) {

        Swal.fire({
            title: "Are you sure?",
            text: "This will delete full on duty record!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {

            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'delete_master.php',
                    data: {
                        id: id,
                        tblname: 'on_duty_details',
                        tblpkey: 'on_duty_id',
                        pagename: 'emp_on_duty_list.php'
                    },
                    success: function() {
                        $.ajax({
                            type: 'POST',
                            url: 'delete_master.php',
                            data: {
                                id: id,
                                tblname: 'on_duty_master',
                                tblpkey: 'on_duty_id',
                                pagename: 'emp_on_duty_list.php'
                            },
                            success: function() {
                                Swal.fire({
                                    icon: "success",
                                    title: "Deleted!",
                                    text: "On Duty deleted successfully",
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            }
                        });

                    }
                });

            }
        });
    }
</script>

</html>