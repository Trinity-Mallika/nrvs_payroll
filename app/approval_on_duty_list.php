<?php
include("appsession.php");

$pagename = "leave_apply_list.php";
$title = "Leave Approval List";

// $data = $obj->executequery("SELECT * FROM on_duty_master 
//     WHERE emp_id='$emp_id' AND unit_id='$unitid' and type='leave'
//     ORDER BY on_duty_id DESC");

 

$imgpath = "../admin/uploaded/on_duty/";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title><?= $title ?></title>
    <?php include("inc/css-file.php"); ?>
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css">

 </section>
</head>

<body class="dashboard">
    <section class="top-sec ">
        <?php include("inc/header.php"); ?>

        <div class="container px-3 px-md-4">
            <div class="row g-2 mb-3 align-items-end">
                <div class="card">
                    <div class="col-12">
                        <label class="form-label small">From Date</label>
                        <input type="text" id="from_date"
    class="form-control form-control-sm datepicker"
    value="<?= date('01-m-Y'); ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label small">To Date</label>
                       <input type="text" id="to_date"
                    class="form-control form-control-sm datepicker"
                    value="<?= date('d-m-Y'); ?>">
                    </div>

                    <div class="col-12 mt-2">
                        <button class="btn btn-primary btn-sm w-100" onclick="filterByDate()">
                            Filter
                        </button>
                    </div>
                </div>
            </div>
            
            <div id="leaveDataContainer"></div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="dutyModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title fs-6">Leave Apply Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mt-2">
                        <div class="d-flex gap-2">
                            <button class="btn btn-success btn-sm" onclick="approveRejectAll(1)">
                                Approve All
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="approveRejectAll(2)">
                                Reject All
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="modal_on_duty_id">

                     

                    <div class="modal-body" id="modalContent">
                        Loading...
                    </div>

                </div>
            </div>
        </div>
    </section>
    <?php include("inc/js-file.php"); ?>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
<script>
    $(document).ready(function() {
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true
});

        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        loadLeaveData(from_date, to_date);
    });


    function filterByDate() {

        let from = $('#from_date').val();
        let to = $('#to_date').val();

        if (!from || !to) {
            alert("Please select both dates");
            return;
        }

        loadLeaveData(from, to);
    }

    function loadLeaveData(from, to) { 
        Swal.fire({
            title: "Loading...",
            text: "Fetching leave data",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "ajax_on_duty_list.php",
            type: "POST",
            data: {
                from_date: from,
                to_date: to
            },
            success: function(res) {
                Swal.close();
                $("#leaveDataContainer").html(res);
            }
        });
    }

    function openDutyModal(id) {
        $("#dutyModal").modal('show');
        $("#modal_on_duty_id").val(id);
        $("#modalContent").html("Loading...");

        $.ajax({
            url: 'ajax_od_view_apr.php',
            type: 'POST',
            data: {
                id: id
            },
            success: function(data) {
                $("#modalContent").html(data);
            }
        });
    }
 
    function updateDutyStatus(id, status) {
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();

        let actionText = (status == 1) ? "Approve" : "Reject";

        Swal.fire({
            title: "Are you sure?",
            text: "You want to " + actionText + " this leave?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: status == 1 ? "#28a745" : "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "ajax_update_od_status.php",
                    type: "POST",
                    data: {
                        id: id,
                        status: status,
                        type: 'single',
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: "success",
                            title: actionText + "d!",
                            timer: 1200,
                            showConfirmButton: false
                        });
                        $("#dutyModal").modal('hide');
                        loadLeaveData(from_date, to_date);
                    },
                    error: function() {
                        Swal.fire("Error!", "Something went wrong", "error");
                    }
                });

            }
        });
    }

    function approveRejectAll(status) {
        let on_duty_id = $("#modal_on_duty_id").val();
        let actionText = (status == 1) ? "Approve All" : "Reject All";
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();


        Swal.fire({
            title: "Are you sure?",
            text: "You want to " + actionText + " leaves?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: status == 1 ? "#28a745" : "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "ajax_update_od_status.php",
                    type: "POST",
                    data: {
                        id: on_duty_id,
                        status: status,
                        type: "all"
                    },
                    success: function() {

                        Swal.fire({
                            icon: "success",
                            title: actionText + " Done!",
                            timer: 1200,
                            showConfirmButton: false
                        });
                        $("#dutyModal").modal('hide');
                        loadLeaveData(from_date, to_date);
                    }
                });

            }
        });
    }
</script>

</html>