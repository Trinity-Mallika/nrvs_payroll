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
$crit = ' and 1=1';
if (isset($_GET['month'])) {
    $month = $obj->test_input($_GET['month']);
    if ($month != '') {
        $crit .= " and month='$month'";
    }
} else {
    $month = "";
};
if (isset($_GET['year'])) {
    $year = $obj->test_input($_GET['year']);
    if ($year != '') {
        $crit .= " and year='$year'";
    }
} else {
    $year = "";
};
if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    if ($emp_id != '') {
        $crit .= " and emp_id='$emp_id'";
    }
} else {
    $emp_id = "";
};

$fromdate = $_GET['fromdate'] ?? '';
$todate = $_GET['todate'] ?? '';

if ($fromdate != '' && $todate != '') {
    $crit .= " AND resignation_date BETWEEN '$fromdate' AND '$todate'";
} elseif ($fromdate != '') {
    $crit .= " AND resignation_date >= '$fromdate'";
} elseif ($todate != '') {
    $crit .= " AND resignation_date <= '$todate'";
}

if (isset($_REQUEST['ajstatus'])) {
    $status = $obj->test_input($_REQUEST['ajstatus']);
    $exit_idd = $obj->test_input($_REQUEST['exit_idd']);
    $emp_idd = $obj->test_input($_REQUEST['emp_id']);
    $reason_for_reject = $obj->test_input($_REQUEST['reason'] ?? '');
    $last_working_date = $obj->test_input($_REQUEST['last_working_date']);

    $obj->update_record("employee_exit", array("exit_id" => $exit_idd), array('is_approved' => $status, 'reason_for_reject' => $reason_for_reject));
    $obj->update_record("employee_master", array("emp_id" => $emp_idd), array('resign_status' => $status, 'last_working_date' => $last_working_date));
    echo 1;
    exit();
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
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a href="emp_separation.php" class="float-end btn btn-primary btn-sm">Add New</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">

                                        <div class="col-lg-3 mb-3">
                                            <label for="emp_id" class="form-label">Employee Name<span class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id" id="emp_id">
                                                <option value="">All</option>
                                                <?php
                                                //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND resign_status != '1' ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?> <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('emp_id').value =
                                                    '<?= $emp_id; ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-3 mb-2">
                                            <label for="" class="form-label">Resignation Date </label>
                                            <div class="input-group input-group-sm">
                                                <input type="date" class="form-control form-control-sm" name="fromdate" id="fromdate" placeholder='dd-mm-yyyy' value="<?php echo $fromdate; ?>">
                                                <span class="input-group-text">To</span>
                                                <input type="date" class="form-control form-control-sm" name="todate" id="todate" placeholder='dd-mm-yyyy' value="<?php echo $todate; ?>">
                                            </div>
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
                                                <th>Employee Name</th>
                                                <th>Exit Type</th>
                                                <th>Resignation Date</th>
                                                <th>Last Working Date</th>
                                                <th>Notice Period (Days)</th>
                                                <th>Reason for Leaving</th>
                                                <th>Approved Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $slno = 1;
                                            $res = $obj->executequery("SELECT * FROM $tblname where unit_id='$unitid' $crit ORDER BY $tblpkey desc ");
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
                                                    <td><?= $emp_code; ?> - <?= ucfirst($first_name ?? ''); ?> <?= ucfirst($last_name ?? ''); ?></td>
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
                                                        <?php } else { ?>
                                                            <span class="badge <?= $badgeClass; ?> me-2">
                                                                <?= $statusText; ?>
                                                            </span>
                                                            <p><?= $row["reason_for_reject"]; ?></p>
                                                        <?php } ?>

                                                    </td>

                                                    <td>
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <li class="list-inline-item " data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                                <a href="emp_separation.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>" class="edit-item-btn">
                                                                    <i class="ri-pencil-fill align-bottom text-success"></i>
                                                                </a>
                                                            </li>

                                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                                <a class="remove-item-btn" type="button" onclick="funDel('<?php echo $row[$tblpkey]; ?>');">
                                                                    <i class="ri-delete-bin-fill align-bottom text-danger"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
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


        function funDel(id) {
            $('#deleteRecordModal').modal('show');
            tblname = '<?php echo $tblname; ?>';
            tblpkey = '<?php echo $tblpkey; ?>';

            pagename = '<?php echo $pagename; ?>';
            submodule = '<?php echo $submodule; ?>';

            $('#delete-record').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/delete_master_emp.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' + submodule + '&pagename=' + pagename,
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
    </script>
</body>

</html>