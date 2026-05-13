<?php include("../adminsession.php");
$pagename = "emp_separation_list.php";
$title = "Employee Separation Master";
$tblname = "employee_exit";
$tblpkey = "exit_id";
$module = "Employee Separation Master";
$submodule = "Employee Separation Master List";
$btn_name = "Search";

$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = 'where 1=1';
if (isset($_GET['unit_id'])) {
    $unit_id = $obj->test_input($_GET['unit_id']);
    if ($unit_id != '') {
        $crit .= " and unit_id='$unit_id'";
    }
} else {
    $unit_id = "";
};



if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = $obj->test_input($_GET['month']);
    $year  = $obj->test_input($_GET['year']);

    if ($month != '' && $year != '') {
        $crit .= " AND MONTH(resignation_date)='$month' 
                   AND YEAR(resignation_date)='$year'";
    }
} else {
    $month = "";
    $year = "";
}

?>


<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?></title>
    <?php include('inc/css.php') ?>
</head>
<style>
    .table-borderless tr td {
        border: 0px !important;
        padding-bottom: 0px;
    }
</style>

<body>
    <?php include('inc/header.php') ?>
    <?php include('inc/sidebar.php') ?>
    <!-- end auth-page-wrapper -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <?php include('inc/bredcrum.php') ?>
                <?php include('inc/alert.php'); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a href="emp_turnover_report.php" class="float-end btn btn-primary btn-sm">Back</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">

                                        <div class="col-lg-3 mb-3">
                                            <label for="unit_id" class="form-label">Unit Name<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select chosen-select" name="unit_id" id="unit_id">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("Select * from unit_master order by unit_name asc");
                                                foreach ($res as $key) {
                                                    echo "<option value='" . $key['unit_id'] . "'>" . $key['unit_name'] . "</option>";
                                                } ?>
                                            </select>
                                            <script>
                                                document.getElementById('unit_id').value = '<?= $unit_id; ?>';
                                            </script>
                                        </div>

                                        <div class="col-md-3 md-2">
                                            <strong><label for="Month">Month<span class="text-danger fw-bold"> </span></label></strong></br>
                                            <select name="month" class="chosen-select form-control form-control" id="month">
                                                <option value="">--Select Month--</option>
                                                <?php for ($iM = 1; $iM <= 12; $iM++) {
                                                ?>
                                                    <option value="<?php echo str_pad($iM, 2, '0', STR_PAD_LEFT); ?>"><?php echo date("F", strtotime(str_pad($iM, 2, '0', STR_PAD_LEFT) . "/12/10")); ?></option>

                                                <?php
                                                } ?>
                                            </select>
                                            <script>
                                                document.getElementById('month').value = '<?php echo $month; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <label for="year" class="form-label">Year<span class="text-danger fw-bold"> </span></label>
                                            <select class="form-select chosen-select" name="year" id="year">
                                                <option value="">Select</option>
                                                <?php
                                                $startYear = 2025;
                                                $endYear = 2100;
                                                for ($year1 = $startYear; $year1 <= $endYear; $year1++) {
                                                    echo "<option value=\"$year1\">$year1</option>";
                                                } ?>
                                            </select>
                                            <script>
                                                document.getElementById('year').value = '<?php echo $year ?>'
                                            </script>
                                        </div>


                                        <div class="col-lg-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="Search">
                                            <a href="<?php echo $pagename ?>" class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="buttons-datatables" class="display table table-sm table-bordered" style="width:100%">
                                        <thead>
                                            <tr class="table-primary">
                                                <th>Sr No.</th>
                                                <th>Emp Code</th>
                                                <th>Employee Name</th>
                                                <th>Exit Type</th>
                                                <th>Resignation Date</th>
                                                <th>Last Working Date</th>
                                                <th>Notice Period (Days)</th>
                                                <th>Reason for Leaving</th>
                                                <th>Approved Status </th>
                                                <th>Rejoin Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $slno = 1;
                                            $res = $obj->executequery("SELECT * FROM $tblname   $crit ORDER BY $tblpkey desc ");
                                            foreach ($res as $row) {
                                                $emp_code = $obj->getvalfield("employee_master", "emp_code", "emp_id='$row[emp_id]'");
                                                $first_name = $obj->getvalfield("employee_master", "first_name", "emp_id='$row[emp_id]'");
                                                $last_name = $obj->getvalfield("employee_master", "last_name", "emp_id='$row[emp_id]'");

                                                if ($row['is_approved'] == 0) {
                                                    $statusText = 'Pending';
                                                    $badgeClass = 'bg-warning';
                                                } elseif ($row['is_approved'] == 1) {
                                                    $statusText = 'Approved';
                                                    $badgeClass = 'bg-success';
                                                } else {
                                                    $statusText = 'Rejected';
                                                    $badgeClass = 'bg-danger';
                                                }
                                            ?>
                                                <tr>
                                                    <td><?php echo $slno++; ?></td>
                                                    <td><?= $emp_code; ?> </td>
                                                    <td> <?= ucfirst($first_name ?? ''); ?> <?= ucfirst($last_name ?? ''); ?></td>
                                                    <td><?php echo $row["exit_type"]; ?></td>
                                                    <td><?= $obj->dateformatindia($row["resignation_date"]); ?></td>
                                                    <td><?= $obj->dateformatindia($row["last_working_date"]); ?></td>
                                                    <td><?php echo $row["notice_period"]; ?></td>
                                                    <td><?php echo $row["reason_for_leaving"]; ?></td>
                                                    <td class="text-center">
                                                        <?php if ($row['is_approved'] == 0) { ?>
                                                            <a href="javascript:void(0)"
                                                                title="Change Status"
                                                                onclick="openStatusModal('<?= $row['is_approved']; ?>','<?= $row['exit_id']; ?>','<?= $row['emp_id']; ?>','<?= $row['last_working_date']; ?>')">
                                                                <span class="badge <?= $badgeClass; ?> me-2">
                                                                    <?= $statusText; ?>
                                                                </span>
                                                            </a>
                                                            <input type="checkbox"
                                                                class="appr_single form-check-input"
                                                                value="<?= $row['exit_id'] ?>" />
                                                        <?php } else { ?>
                                                            <span class="badge <?= $badgeClass; ?> me-2">
                                                                <?= $statusText; ?>
                                                            </span>
                                                            <p><?= $row["reason_for_reject"]; ?></p>
                                                        <?php } ?>

                                                    </td>

                                                    <td>
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <?php if ($row['is_approved'] == 1 && $row['is_rejoined'] == 0) { ?>
                                                                <span class="badge bg-primary me-2 cursor-pointer" onclick="openRejoinModal('<?php echo $row[$tblpkey]; ?>','<?php echo $row['emp_id']; ?>');">
                                                                    Rejoin
                                                                </span>
                                                            <?php } ?>
                                                            <?php if ($row['is_rejoined'] == 1) { ?>
                                                                <div>
                                                                    <span class="badge bg-success mb-1 d-inline-block ">
                                                                        Re-joined
                                                                    </span><br>

                                                                    <small>
                                                                        <?= $row["rejoin_remark"]; ?>
                                                                    </small>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span>
                                                                    -
                                                                </span><br>
                                                            <?php } ?>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <div class="col-lg-12 mt-4 text-end">
                                        <input type="submit" name="appr_status" class="btn btn-sm btn-primary add-btn" value="Approve All" onclick="updateStatus();">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!-- container-fluid -->
    </div>
    <div class="modal fade" id="rejoinModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header text-white">
                    <h5 class="modal-title">Employee Rejoin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="rejoin_exit_id">
                    <input type="hidden" id="rejoin_emp_id">

                    <div class="mb-3">
                        <label class="form-label">Rejoining Date</label>
                        <input type="date" class="form-control" id="rejoin_date">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remark</label>
                        <textarea class="form-control" id="rejoin_remark" rows="3"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary"
                        onclick="saveRejoin()">Save</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade mt-4" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Approval Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="modal_exit_id">
                    <input type="hidden" id="modal_emp_id">
                    <input type="hidden" id="modal_lwd">

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="modal_status" onchange="toggleReasonField()">
                            <option value="0">Pending</option>
                            <option value="1">Approved</option>
                            <option value="2">Rejected</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason / Remarks</label>
                        <textarea class="form-control" id="modal_reason"
                            placeholder="Enter reason"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="confirm_btn" onclick="confirmStatusChange()">Confirm</button>
                </div>

            </div>
        </div>
    </div>

    <!-- End Page-content -->
    </div>
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            $(".chosen-select").select2({
                width: '100%',
                search_contains: true
            });
            toggleReasonField();
        });



        function openStatusModal(status, exit_id, emp_id, lwd) {
            $('#modal_status').val(status);
            $('#modal_exit_id').val(exit_id);
            $('#modal_emp_id').val(emp_id);
            $('#modal_lwd').val(lwd);
            $('#modal_reason').val('');
            $('#statusModal').modal('show');
        }

        function toggleReasonField() {
            let status = $('#modal_status').val();

            if (status == '2') {
                $('#modal_reason').closest('.mb-3').show();
                $('#modal_reason').prop('required', true);
            } else {
                $('#modal_reason').closest('.mb-3').hide();
                $('#modal_reason').prop('required', false).val('');
            }
        }


        function funDel(id, emp_id) {
            $('#deleteRecordModal').modal('show');
            tblname = '<?php echo $tblname; ?>';
            tblpkey = '<?php echo $tblpkey; ?>';

            pagename = '<?php echo $pagename; ?>';
            submodule = '<?php echo $submodule; ?>';

            $('#delete-record').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/delete_master_separation.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' + submodule + '&emp_id=' + emp_id + '&pagename=' + pagename,
                    dataType: 'html',
                    success: function(data) {
                        // alert(data);
                        location = '<?php echo $pagename; ?>';
                    }
                });
                $('#deleteRecordModal').modal('hide');
            });
        };

        function numberOnly(evt) {
            var theEvent = evt || window.event;

            // Handle paste
            if (theEvent.type === 'paste') {
                key = event.clipboardData.getData('text/plain');
            } else {
                // Handle key press
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode(key);
            }
            var regex = /[0-9]|\.|\s/;
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        }


        function confirmStatusChange() {
            let status = $('#modal_status').val();
            let reason = $('#modal_reason').val().trim();
            let modal_exit_id = $('#modal_exit_id').val();
            let modal_emp_id = $('#modal_emp_id').val();
            let modal_lwd = $('#modal_lwd').val();
            let modal_reason = $('#modal_reason').val();
            if (status == '2' && reason === '') {
                alert('Reason is required for rejection');
                return;
            }
            $('#confirm_btn').prop('disabled', true).text('Saving...');

            $.ajax({
                type: "POST",
                url: "",
                data: {
                    ajstatus: status,
                    exit_idd: modal_exit_id,
                    emp_id: modal_emp_id,
                    last_working_date: modal_lwd,
                    reason: reason
                },
                dataType: "json",
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated',
                        text: 'Approval status has been updated successfully.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    alert('Something went wrong. Please try again.');

                    $('#confirm_btn').prop('disabled', false).text('Confirm');
                }
            });
        }

        $("#checkAll").on("change", function() {
            $(".appr_single").prop("checked", $(this).prop("checked"));
        });

        // If any unchecked manually → uncheck header
        $(document).on("change", ".appr_single", function() {
            if (!$(this).prop("checked")) {
                $("#checkAll").prop("checked", false);
            } else if ($(".appr_single:checked").length === $(".appr_single").length) {
                $("#checkAll").prop("checked", true);
            }
        });

        function updateStatus() {

            let selected = [];

            $('.appr_single:checked').each(function() {
                selected.push($(this).val());
            });

            if (selected.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one record.'
                });
                return;
            }

            Swal.fire({
                title: 'Approve Selected?',
                text: "You are about to approve selected resignations.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Approve'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        type: "POST",
                        url: "", // same page
                        data: {
                            bulk_approve: 1,
                            ids: selected
                        },
                        dataType: "json",
                        success: function(response) {
                            console.log(response);
                            Swal.fire({
                                icon: 'success',
                                title: 'Approved',
                                text: 'Selected records approved successfully.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });

                        },
                        error: function(err) {
                            console.log(err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong.'
                            });
                        }
                    });

                }

            });
        }

        function openRejoinModal(exit_id, emp_id) {
            $('#rejoin_exit_id').val(exit_id);
            $('#rejoin_emp_id').val(emp_id);
            $('#rejoin_date').val('<?= date('Y-m-d') ?>');
            $('#rejoin_remark').val('');
            $('#rejoinModal').modal('show');
        }

        function saveRejoin() {

            let exit_id = $('#rejoin_exit_id').val();
            let rejoin_date = $('#rejoin_date').val();
            let rejoin_emp_id = $('#rejoin_emp_id').val();
            let remark = $('#rejoin_remark').val().trim();

            if (rejoin_date === '') {
                alert('Rejoining date is required');
                return;
            }

            $.ajax({
                type: "POST",
                url: "",
                data: {
                    rejoin_action: 1,
                    exit_id: exit_id,
                    rejoin_emp_id: rejoin_emp_id,
                    rejoin_date: rejoin_date,
                    remark: remark
                },
                dataType: "json",
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Rejoined Successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    alert('Something went wrong');
                }
            });
        }
    </script>
</body>

</html>