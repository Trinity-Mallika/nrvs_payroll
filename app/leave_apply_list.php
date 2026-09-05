<?php
include("appsession.php");

$pagename = "leave_apply_list.php";
$title = "Leave List";

// $data = $obj->executequery("SELECT * FROM on_duty_master 
//     WHERE emp_id='$emp_id' AND unit_id='$unitid' and type='leave'
//     ORDER BY on_duty_id DESC");
$type = $obj->test_input($_GET['type']??'');

$data = $obj->executequery("
    SELECT m.*, 
    MAX(d.status) as status
    FROM on_duty_master m
    LEFT JOIN leave_apply_detail d 
    ON m.on_duty_id = d.on_duty_id
    WHERE m.emp_id='$emp_id' 
    AND m.unit_id='$unitid' 
    AND m.type='leave'
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
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css">
</head>

<body class="dashboard">
    <section class="top-sec ">
        <?php include("inc/header.php"); ?>

        <div class="container px-3 px-md-4">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5> </h5>
                <a href="leave_apply.php" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Add New
                </a>
            </div>

            <div class="row g-2 mb-3 align-items-end">
                <div class="card">
                    <div class="col-12">
                        <label class="form-label small">From Date</label>
                        <input type="text" id="from_date" class="form-control form-control-sm datepicker"
                            value="<?= date('01-m-Y'); ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label small">To Date</label>
                        <input type="text" id="to_date" class="form-control form-control-sm datepicker"
                            value="<?= date('d-m-Y'); ?>">
                    </div>
                    <div class="col-12">
                        <label for="" class="form-label">Leave Type</label>
                        <select class="form-control" id="leave_type">
                            <option value="">All</option> 
                            <option value="EL">EARNED LEAVE</option> 
                            <option value="EO">EXTRA OFF</option>
                            <option value="CO">C Off</option>  
                        </select>
                        <script> document.getElementById('leave_type').value = '<?= $type; ?>'; </script>
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
                        <h5 class="modal-title">Leave Apply Details</h5>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js">
</script>
<script>
$(document).ready(function() {
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });
    let from_date = $('#from_date').val();
    let to_date = $('#to_date').val();
    let leave_type = $('#leave_type').val();

    loadLeaveData(from_date, to_date, leave_type);
});

function filterByDate() {

    let from = $('#from_date').val();
    let to = $('#to_date').val();
    let leave_type = $('#leave_type').val();

    if (!from || !to) {
        alert("Please select both dates");
        return;
    }

    loadLeaveData(from, to, leave_type);
}

function loadLeaveData(from, to, leave_type) {

    Swal.fire({
        title: "Loading...",
        text: "Fetching leave data",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: "ajax_emp_leave_list.php",
        type: "POST",
        data: {
            from_date: from,
            to_date: to,
            leave_type: leave_type
        },
        success: function(res) {
            Swal.close();
            $("#leaveDataContainer").html(res);
        }
    });
}

function openDutyModal(id) {

    $("#dutyModal").modal('show');
    $("#modalContent").html("Loading...");

    $.ajax({
        url: 'ajax_leave_view.php',
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
function deleteLeave(id) {

    Swal.fire({
        title: "Are you sure?",
        text: "This will delete full leave record!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {

        if (result.isConfirmed) {

            // 🔴 STEP 1: Delete child table (leave_apply_detail)
            $.ajax({
                type: 'POST',
                url: 'delete_master.php',
                data: {
                    id: id,
                    tblname: 'leave_apply_detail',
                    tblpkey: 'on_duty_id',
                    pagename: 'leave_apply_list.php'
                },
                success: function() {

                    // 🔴 STEP 2: Delete main table (on_duty_master)
                    $.ajax({
                        type: 'POST',
                        url: 'delete_master.php',
                        data: {
                            id: id,
                            tblname: 'on_duty_master',
                            tblpkey: 'on_duty_id',
                            pagename: 'leave_apply_list.php'
                        },
                        success: function() {

                            Swal.fire({
                                icon: "success",
                                title: "Deleted!",
                                text: "Leave deleted successfully",
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