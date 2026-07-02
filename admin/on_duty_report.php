<?php include("../adminsession.php");
$pagename = "on_duty_report.php";
$title = "On Duty Master Report";
$tblname = "on_duty_master";
$tblpkey = "on_duty_id";
$module = "On Duty Master";
$submodule = "On Duty Master Report";
$btn_name = "Search";
$imgpath1 = 'uploaded/on_duty/';
$crit = '';

$od_date_to = $_GET['od_date_to'] ?? date('Y-m-d');
$od_date_from = $_GET['od_date_from'] ?? date('Y-m-01');
if ($od_date_from != '' && $od_date_to != '') {
    $crit .= " AND od.application_date BETWEEN '$od_date_from' AND '$od_date_to'";
} elseif ($od_date_from != '') {
    $crit .= " AND od.application_date >= '$od_date_from'";
} elseif ($od_date_to != '') {
    $crit .= " AND od.application_date <= '$od_date_to'";
};

if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    if ($emp_id != '') {
        $crit .= " and od.emp_id='$emp_id'";
    }
} else {
    $emp_id = "";
};
if (isset($_GET['on_duty_type'])) {
    $on_duty_type = $obj->test_input($_GET['on_duty_type']);
    if ($on_duty_type != '') {
        $crit .= " and od.on_duty_type='$on_duty_type'";
    }
} else {
    $on_duty_type = "";
};


?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

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
                <?php //include('inc/alert.php'); 
                ?>
                <div class="row">
                    <?php if (!isset($_GET['submit'])) { ?>

                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="get">
                                        <div class="row">
                                            <div class="col-lg-4 mb-2">
                                                <label for="on_duty_type" class="form-label">On Duty Type<span
                                                        class="text-danger fw-bold"> </span></label>
                                                <select class="form-select form-select-sm chosen-select" name="on_duty_type"
                                                    id="on_duty_type">
                                                    <option value="">All</option>
                                                    <option value="On Duty">On Duty</option>
                                                    <option value="Missed Punch">Missed Punch</option>
                                                    <option value="Official Travel">Official Travel</option>
                                                </select>
                                                <script>
                                                    document.getElementById('on_duty_type').value = '<?= $on_duty_type; ?>';
                                                </script>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="">Employee Name</label>
                                                <select name="emp_id" id="emp_id"
                                                    class="form-select form-select-sm chosen-select">
                                                    <option value="">All</option>
                                                    <?php
                                                    $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                    foreach ($res as $key) { ?>
                                                        <option value="<?= $key['emp_id']; ?>">
                                                            <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?>
                                                            <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="">Application From</label>
                                                <div class="d-flex">
                                                    <input type="date" name="od_date_from" id="od_date_from"
                                                        class="form-control form-control-sm me-2"
                                                        value="<?= $od_date_from ?>"> <span class="mt-1 fw-bold"> To</span>
                                                    <input type="date" name="od_date_to" id="od_date_to"
                                                        class="form-control form-control-sm ms-2"
                                                        value="<?= $od_date_to ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-12 text-center mt-4">
                                                <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn"
                                                    value="<?php echo $btn_name ?> "
                                                    onClick="return checkinputmaster('application_date,emp_id,on_duty_type')">
                                                <a href=" <?php echo $pagename ?>" type="button"
                                                    class="btn btn-sm btn-danger add-btn">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($_GET['submit'])) { ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"><?php echo $submodule; ?> <a href="on_duty_report.php"
                                                        class="float-end btn btn-primary btn-sm ms-2">Search Again</a> </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <div class="table-responsive">
                                                <table id="buttons-datatables" class="table table-sm table-bordered">
                                                    <thead>
                                                        <tr class="table-primary">
                                                            <th>Sr No.</th>
                                                            <th>Date</th>
                                                            <th>Emp Code</th>
                                                            <th>Emp Name</th>
                                                            <th>On Duty Type </th>
                                                            <th>OD From </th>
                                                            <th>OD To </th>
                                                            <th>Days</th>
                                                            <th>Approve</th>
                                                            <th>Reject</th>
                                                            <th>Pending</th>
                                                            <th>Doc</th>
                                                            <th>Print</th>
                                                            <th>Status</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $slno = 1;
                                                        // $res = $obj->executequery("select od.*,em.first_name , em.last_name , em.emp_code from $tblname od left join employee_master as em on od.emp_id=em.emp_id left join on_duty_details odd on od.on_duty_id = odd.on_duty_id GROUP BY od.on_duty_id order by od.$tblpkey desc");

                                                        $res = $obj->executequery("
                                                        SELECT 
                                                            od.*,
                                                            em.first_name,
                                                            em.last_name,
                                                            em.emp_code,
                                                            MIN(odd.date) as from_date,
                                                            MAX(odd.date) as to_date,
                                                            COUNT(odd.on_duty_details_id) as total_day,
                                                            SUM(CASE WHEN odd.status = 1 THEN 1 ELSE 0 END) as approved_days,
                                                            SUM(CASE WHEN odd.status = 2 THEN 1 ELSE 0 END) as rejected_days,
                                                            SUM(CASE WHEN odd.status = 0 THEN 1 ELSE 0 END) as pending_days,
                                                            cu.fullname as created_name,
                                                            cu.username as created_username,
                                                            cu.mobile as created_mobile,

                                                            uu.fullname as updated_name,
                                                            uu.username as updated_username,
                                                            uu.mobile as updated_mobile
                                                        FROM on_duty_master od
                                                        LEFT JOIN employee_master em 
                                                            ON od.emp_id = em.emp_id
                                                            LEFT JOIN user cu 
                                                            ON od.createdby = cu.userid LEFT JOIN user uu ON od.updatedby = uu.userid
                                                        LEFT JOIN on_duty_details odd 
                                                            ON od.on_duty_id = odd.on_duty_id where od.unit_id='$unitid' and od.type='on_duty' $crit
                                                        GROUP BY od.on_duty_id
                                                        ORDER BY od.$tblpkey DESC
                                                    ");
                                                        foreach ($res as $row) {

                                                        ?>
                                                            <tr id="tr_<?= $row["on_duty_id"]; ?>" data-details="
                                                        <div style='background:#dafced; padding:4px;'>
                                                        <?php if (!empty($row['created_name'])): ?>
                                                        Added by (User: <?= $row['created_name'] ?>,
                                                        Username: <?= $row['created_username'] ?>,
                                                        Mobile: <?= $row['created_mobile'] ?>,
                                                        Date: <?= $row['createdate'] ?>,)<br>
                                                        <?php endif; ?>

                                                        <?php if (!empty($row['updated_name'])): ?>
                                                        Last Edited by (User: <?= $row['updated_name'] ?>,
                                                        Username: <?= $row['updated_username'] ?>,
                                                        Mobile: <?= $row['updated_mobile'] ?>,
                                                        Date: <?= $row['lastupdated'] ?>) 
                                                        <?php endif; ?>
                                                        </div>
                                                    ">
                                                                <td class="details-control text-center" style="cursor:pointer;">
                                                                    <?php echo $slno++; ?> <i
                                                                        class="ri-add-circle-fill text-primary"></i></td>
                                                                <td><?= $obj->dateformatindia($row['application_date']) ?></td>
                                                                <td><?= $row['emp_code'] ?></td>
                                                                <td>
                                                                    <b><?= $row['first_name'] . " " . $row['last_name']   ?>
                                                                    </b>
                                                                </td>
                                                                <td><?= $row['on_duty_type'] ?></td>
                                                                <td><?= $obj->dateformatindia($row['from_date']) ?></td>
                                                                <td><?= $obj->dateformatindia($row['to_date']) ?></td>
                                                                <td><?= $row['total_day'] ?></td>

                                                                <td class="cursor-pointer"
                                                                    onclick='openOnDutyModal("approved", <?= json_encode($row) ?>)'>
                                                                    <b><?= $row['approved_days'] ?></b>
                                                                </td>

                                                                <td class="cursor-pointer"
                                                                    onclick='openOnDutyModal("rejected", <?= json_encode($row) ?>)'>
                                                                    <?= $row['rejected_days'] ?>
                                                                </td>

                                                                <td class="cursor-pointer"
                                                                    onclick='openOnDutyModal("pending", <?= json_encode($row) ?>)'>
                                                                    <?= $row['pending_days'] ?>
                                                                </td>

                                                                <td>
                                                                    <?php if (!empty($row['doc_file'])) { ?>
                                                                        <a href="<?= $imgpath1 . $row['doc_file'] ?>"
                                                                            target="_blank">
                                                                            <i class="ri-attachment-2 cursor-pointer"></i>
                                                                        </a>
                                                                    <?php } else { ?>
                                                                        <i class="ri-forbid-2-line cursor-pointer text-danger"></i>
                                                                    <?php } ?>
                                                                </td>
                                                                <!-- Icons -->

                                                                <td>
                                                                    <?php
                                                                    $chkprint = $obj->check_printBtn($pagename, $loginid);
                                                                    if ($chkprint == 1) {  ?>
                                                                        <a href="on_duty_form.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>"
                                                                            class="edit-item-btn" target="_blank">
                                                                            <i class="ri-printer-line cursor-pointer"></i>
                                                                        </a>
                                                                    <?php } ?>
                                                                </td>
                                                                
                                                                <td class="cursor-pointer"
                                                                    onclick='openOnDutyModal("all", <?= json_encode($row) ?>)'>
                                                                    <i class="ri-checkbox-fill text-success fs-5"></i>
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
                        </div>
                    <?php } ?>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content shadow">
                <!-- Header -->
                <div class="modal-header bg-light p-0 p-3">
                    <h5 class="modal-title fw-bold">On Duty Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Body --> 
                <div class="modal-body">
                    <!-- Top Info -->
                    <div class="row mb-3"> 
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="fw-semibold">Application Date</td>
                                    <td id="modalApplicationDate"></td>


                                </tr>
                                <tr>
                                    <td class="fw-semibold">Total Days</td>
                                    <td id="modalTotalDays"></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">On Duty Type</td>
                                    <td id="modalOnDutyType"></td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="fw-semibold">Employee Name</td>
                                    <input type="hidden" id="modalEmpId">
                                    <td>
                                        <strong id="modalEmpName"> </strong>
                                        <span class="text-muted" id="modalEmpCode"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Attachment</td>
                                    <td id="modalAttachment"></td>
                                </tr>
                            </table>
                        </div>

                    </div>

                    <div id="modalBodyContent">
                        <!-- AJAX content yaha load hoga -->
                    </div>

 

                </div>

            </div>
        </div>
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
        });

        function openOnDutyModal(type, data) {
            let imgpath = '<?= $imgpath1 ?>';
            $("#modalApplicationDate").text(data.application_date);
            $("#modalTotalDays").text(data.total_day);
            $("#modalEmpId").val(data.emp_id);
            $("#modalOnDutyType").text(data.on_duty_type.toUpperCase());

            $("#modalEmpName").text(data.first_name + " " + data.last_name);
            $("#modalEmpCode").text("(" + data.emp_code + ")");
            if (data.doc_file && data.doc_file !== '') {
                $("#modalAttachment").html(
                    `<a href="${imgpath}${data.doc_file}" target="_blank">
                <i class="ri-attachment-2 text-primary fs-5"></i>
            </a>`
                );
            } else {
                $("#modalAttachment").html(
                    `<i class="ri-forbid-2-line text-danger fs-5"></i>`
                );
            }

            $.ajax({
                url: "get_on_duty_details.php",
                type: "POST",
                data: {
                    type: type,
                    on_duty_id: data.on_duty_id
                },
                success: function(response) {

                    $("#modalBodyContent").html(response);
                    $("#staticBackdrop").modal('show');


                }
            });

        }

        $(document).on('change', '.approve_chk', function() {
            let row = $(this).closest('tr');
            if ($(this).is(':checked')) {
                row.find('.reject_chk').prop('checked', false);
                row.find('.pending_chk').prop('checked', false);
            }
        });


        $(document).on('change', '.pending_chk', function() {
            let row = $(this).closest('tr');
            if ($(this).is(':checked')) {
                row.find('.approve_chk').prop('checked', false);
                row.find('.reject_chk').prop('checked', false);
            }
        });

        $(document).on('change', '.reject_chk', function() {
            let row = $(this).closest('tr');
            if ($(this).is(':checked')) {
                row.find('.approve_chk').prop('checked', false);
                row.find('.pending_chk').prop('checked', false);
            }
        });
        // Approve All
        $(document).on('change', '#appr_check', function() {
            if ($(this).is(':checked')) {
                $('#rej_check').prop('checked', false);
                $('#pen_check').prop('checked', false);

                $('.approve_chk:not(:disabled)').prop('checked', true);
                $('.reject_chk:not(:disabled)').prop('checked', false);
                $('.pending_chk:not(:disabled)').prop('checked', false);
            } else {
                $('.approve_chk:not(:disabled)').prop('checked', false);
            }
        });
        // Reject All
        $(document).on('change', '#rej_check', function() {
            if ($(this).is(':checked')) {

                $('#appr_check').prop('checked', false);
                $('#pen_check').prop('checked', false);

                $('.approve_chk').prop('checked', false);
                $('.pending_chk').prop('checked', false);

                $('.reject_chk:not(:disabled)').prop('checked', true);

            } else {
                $('.reject_chk:not(:disabled)').prop('checked', false);
            }
        });
        // pending All
        $(document).on('change', '#pen_check', function() {
            if ($(this).is(':checked')) {
                $('#appr_check').prop('checked', false);
                $('#rej_check').prop('checked', false);
                // Sabko uncheck karo
                $('.approve_chk').prop('checked', false);
                $('.reject_chk').prop('checked', false);
                // Sirf enabled pending ko check karo
                $('.pending_chk:not(:disabled)').prop('checked', true);
            } else {
                $('.pending_chk:not(:disabled)').prop('checked', false);
            }
        });


 

        function funDel(id, imgname) {
            $('#deleteRecordModal').modal('show');
            tblname = 'on_duty_master';
            tblpkey = 'on_duty_id';
            pagename = '<?php echo $pagename; ?>';
            submodule = '<?php echo $submodule; ?>';
            imgpath = '<?php echo $imgpath1; ?>';

            $('#delete-record').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'delete_on_duty.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&pagename=' +
                        pagename + '&imgname=' + imgname + '&imgpath=' + imgpath,
                    dataType: 'html',
                    success: function(data) {
                        $("#tr_" + id).hide();
                    }
                });
                $('#deleteRecordModal').modal('hide');
            });
        };

        $(document).ready(function() {

            var table = $('#buttons-datatables').DataTable();

            $('#buttons-datatables tbody').on('click', 'td.details-control', function() {

                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    var details = tr.data('details');
                    row.child(details).show();
                    tr.addClass('shown');
                }

            });

        });
    </script>
</body>

</html>