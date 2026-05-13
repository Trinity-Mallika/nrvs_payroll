<?php include("../adminsession.php");
$pagename = "leave_apply_list.php";
$title = "Leave Application List";
$tblname = "on_duty_master";
$tblpkey = "on_duty_id";
$module = "Leave Application List";
$submodule = "Leave Application List";
$btn_name = "Search";
$imgpath1 = 'uploaded/on_duty/';
$crit = '';
$current_time = date('H:i:s');
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
if (isset($_GET['status1'])) {
    $status1 = $obj->test_input($_GET['status1']);
    if ($status1 != '') {
        $crit .= " and lpd.status='$status1'";
    }
} else {
    $status1 = "";
};

if (isset($_POST['updatess'])) {
    $updates = $_POST['updatess'];
    $emp_id = $obj->test_input($_POST['emp_id']);
    $emp_data = $obj->select_record("employee_master", ['emp_id' => $emp_id]);
    $department_id = $emp_data['department_id'];
    $shift_hrs = $emp_data['shift_id'];
    $unit_id = $emp_data['unit_id'];
    $basic_salary = $emp_data['basic_salary'];



    foreach ($updates as $row) {
        $id = $row['id'];
        $status = $row['status'];
        $remark = $row['remark'];
        $leave_type = $row['leave_type'];
        $leave_day = $row['leave_day'];
        $date = $row['date'];

        $obj->update_record("leave_apply_detail", ['leave_details_id' => $id], ['status' => $status, 'updatedby' => $loginid, 'appr_remark' => $remark, 'lastupdated' => $createdate, 'approve_date' => $createdate, 'approve_by' => $loginid]);
        if ($status == 1) {
            $month = date('m', strtotime($date));
            $year  = date('Y', strtotime($date));
            $where = array(
                'emp_id' => $emp_id,
                'attendance_date'  => $date,
                'year'   => $year,
                'month'   => $month,
                'unit_id'   => $unit_id
            );
            $obj->delete_record('attendance_entry', $where);

            $obj->delete_record('attendance_log', $where);
            if ($leave_type == 'LWP') {
                continue;
            }
            $leave_data = array(
                "emp_id" => $emp_id,
                "department_id" => $department_id,
                "month" => (int)$month,
                "year" => (int)$year,
                "basic_salary" => $basic_salary,
                "unit_id" => $unit_id,
                "createdby" => $loginid,
                "ipaddress" => $ipaddress,
                "sessionid" => $sessionid,
                "createdate" => date("Y-m-d H:i:s")
            );


            $punch_status = '';
            if ($leave_day == 'FD' || $leave_day == 'SL') {

                if ($leave_type == 'WL') {
                    $punch_status = 'weekly_leave';
                } elseif ($leave_type == 'EL') {
                    $punch_status = 'earn_leave';
                }
            } elseif ($leave_day == 'FHD' || $leave_day == 'SHD') {

                if ($leave_type == 'WL') {
                    $punch_status = 'half_weekly_leave';
                } elseif ($leave_type == 'EL') {
                    $punch_status = 'half_earn_leave';
                }
            }

            $sql = "SELECT shift_id, in_time, out_time,working_hour, is_cross_day,grace_time_in,grace_time_out FROM shift_master WHERE unit_id = '$unit_id' AND working_hour = '$shift_hrs' ORDER BY shift_id ASC LIMIT 1";

            $res = $obj->executequery($sql);
            if (empty($res)) continue;
            $shift = $res[0];
            $office_in_time = $shift['in_time'];
            $office_out_time = $shift['out_time'];
            $shift_id     = $shift['shift_id'];
            $office_working_hour = $shift['working_hour'];
            $in_margin = $shift['grace_time_in'];
            $out_margin = $shift['grace_time_out'];



            $form_date = [
                'emp_id' => $emp_id,
                'department_id' => $department_id,
                'attendance_date' => $date,
                'attendance_stamp' => $date,
                "month" => (int)$month,
                "year" => (int)$year,
                'shift_id' => $shift_id,
                'entry_type' => 'manual',
                'entry_type_out' => 'manual',
                'in_status' => 'IN',
                'out_status' => 'OUT',
                'unit_id' => $unit_id,
                'sessionid' => $sessionid,
                'in_remark' => $remark,
                'basic_salary' => $basic_salary,
                'createdate' => date('Y-m-d'),
                'createtime' => $current_time,
                'createdby' => $loginid,
                'updateby' => $loginid,
                'lastupdated' => date('Y-m-d'),
                'ipaddress' => $ipaddress,
            ];

            if ($punch_status == 'weekly_leave') {
                $form_date['attendance_status'] = 'Weekly Leave';
                $obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $date]);

                $leave_data['total_leave'] = '-1';
                $leave_data['remining_leave'] = '-1';
                $leave_data['leave_type'] = 'weekly';
                $leave_data['leave_date'] = $date;
                $obj->insert_record("emp_monthly_leave", $leave_data);
            } elseif ($punch_status == 'earn_leave') {
                $obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $date]);
                $form_date['attendance_status'] = 'Earning Leave';
                $leave_data['total_leave'] = '-1';
                $leave_data['remining_leave'] = '-1';
                $leave_data['leave_type'] = 'earning';
                $leave_data['leave_date'] = $date;
                $obj->insert_record("emp_monthly_leave", $leave_data);
            } elseif ($punch_status == 'half_weekly_leave') {
                $obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $date]);

                $form_date['attendance_status'] = 'Half Weekly Leave';
                $leave_data['total_leave'] = '-0.5';
                $leave_data['remining_leave'] = '-0.5';
                $leave_data['leave_type'] = 'weekly';
                $leave_data['leave_date'] = $date;
                $obj->insert_record("emp_monthly_leave", $leave_data);
            } elseif ($punch_status == 'half_earn_leave') {
                $obj->delete_record('emp_monthly_leave', ['emp_id' => $emp_id, 'leave_date' => $date]);
                $form_date['attendance_status'] = 'Half Earning Leave';
                $leave_data['total_leave'] = '-0.5';
                $leave_data['remining_leave'] = '-0.5';
                $leave_data['leave_type'] = 'earning';
                $leave_data['leave_date'] = $date;
                $obj->insert_record("emp_monthly_leave", $leave_data);
            }

            $lastid = $obj->insert_record_lastid("attendance_entry", $form_date);



            $form_data1 = array(
                "primary_id" => $lastid,
                "flag" => 'Punch IN and OUT Attendence',
                "activity_type" => 'Attendence IN/OUT From Leave',
                "createdby" => $loginid,
                "pagename" => $pagename,
                "created_date" => $createdate,
                "created_time" => date('H:i:s'),
                "unit_id" => $unitid,
                'ipaddress' => $ipaddress,
                "sessionid" => $sessionid
            );
            $logactivity = $obj->insert_record("logactivity_master", $form_data1);
        }
    }
    echo "success";
    die;
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

    .detail-row {
        display: none;
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

                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?><a href="leave_apply.php" class="float-end btn btn-primary btn-sm">Add</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label for="">Application From</label>
                                            <div class="d-flex">
                                                <input type="date" name="od_date_from" id="od_date_from" class="form-control form-control-sm me-2" value="<?= $od_date_from ?>"> <span class="mt-1 fw-bold"> To</span>
                                                <input type="date" name="od_date_to" id="od_date_to" class="form-control form-control-sm ms-2" value="<?= $od_date_to ?>">
                                            </div>
                                        </div>

                                        <div class="col-lg-3 mb-2">
                                            <label for="status1" class="form-label">Status<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="status1" id="status1">
                                                <option value="">All</option>
                                                <option value="0">Pending</option>
                                                <option value="1">Approved</option>
                                                <option value="2">Reject</option>
                                            </select>
                                            <script>
                                                document.getElementById('status1').value = '<?= $status1; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">Employee Name</label>
                                            <select name="emp_id" id="emp_id" class="form-select form-select-sm chosen-select">
                                                <option value="">All</option>
                                                <?php
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?> <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> " onClick="return checkinputmaster('application_date,emp_id,on_duty_type')">
                                            <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card" id="customerList">
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
                                                        <th>Substitute Employee</th>
                                                        <th>Leave From </th>
                                                        <th>Leave To </th>
                                                        <th>Days</th>
                                                        <th>Balance Leave</th>
                                                        <th>Used Leave</th>
                                                        <th>Approve</th>
                                                        <th>Reject</th>
                                                        <th>Pending</th>
                                                        <th>Doc</th>
                                                        <th>Print</th>
                                                        <th>Edit</th>
                                                        <th>Delete</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $slno = 1;
                                                    $res = $obj->executequery("SELECT 
                                                        od.*,
                                                        em.first_name,
                                                        sem.first_name as sub_emp_name,
                                                        em.last_name,
                                                        em.is_esic,
                                                        em.emp_code,
                                                        MIN(lpd.date) as from_date,
                                                        MAX(lpd.date) as to_date,
                                                        SUM(
                                                        CASE 
                                                            WHEN lpd.leave_day = 'FD' THEN 1
                                                            WHEN lpd.leave_day = 'SL' THEN 1
                                                            WHEN lpd.leave_day IN ('FHD','SHD') THEN 0.5
                                                            ELSE 0
                                                        END
                                                    ) as total_day,
                                                    SUM(
                                                    CASE 
                                                        WHEN lpd.status = 1 THEN 
                                                            CASE 
                                                                WHEN lpd.leave_day IN ('FD','SL') THEN 1
                                                                WHEN lpd.leave_day IN ('FHD','SHD') THEN 0.5
                                                                ELSE 0
                                                            END
                                                        ELSE 0
                                                    END
                                                ) as approved_days,
                                                    SUM(
                                                    CASE 
                                                        WHEN lpd.status = 2 THEN 
                                                            CASE 
                                                                WHEN lpd.leave_day IN ('FD','SL') THEN 1
                                                                WHEN lpd.leave_day IN ('FHD','SHD') THEN 0.5
                                                                ELSE 0
                                                            END
                                                        ELSE 0
                                                    END
                                                ) as rejected_days,

                                                SUM(
                                                    CASE 
                                                        WHEN lpd.status = 0 THEN 
                                                            CASE 
                                                                WHEN lpd.leave_day IN ('FD','SL') THEN 1
                                                                WHEN lpd.leave_day IN ('FHD','SHD') THEN 0.5
                                                                ELSE 0
                                                            END
                                                        ELSE 0
                                                    END
                                                ) as pending_days,
                                                SUM(
                                                    CASE 
                                                        WHEN lpd.status = 1 THEN
                                                            CASE 
                                                                WHEN lpd.leave_day IN ('FD','SL') THEN 1
                                                                WHEN lpd.leave_day IN ('FHD','SHD') THEN 0.5
                                                                ELSE 0
                                                            END
                                                        ELSE 0
                                                    END
                                                ) as used_leave,
                                                cu.fullname AS created_name,
                                                cu.username AS created_username,
                                                cu.mobile AS created_mobile,

                                                uu.fullname AS updated_name,
                                                uu.username AS updated_username,
                                                uu.mobile AS updated_mobile
                                                
                                                    FROM on_duty_master od
                                                    LEFT JOIN employee_master em 
                                                        ON od.emp_id = em.emp_id
                                                    LEFT JOIN employee_master sem 
                                                        ON od.substitute_emp_id = sem.emp_id
                                                    LEFT JOIN leave_apply_detail lpd 
                                                        ON od.on_duty_id = lpd.on_duty_id 

                                                    LEFT JOIN user cu 
                                                        ON od.createdby = cu.userid

                                                    LEFT JOIN user uu 
                                                        ON od.updatedby = uu.userid

                                                    where od.unit_id='$unitid' and od.type='leave' $crit
                                                    
                                                    GROUP BY od.on_duty_id
                                                    ORDER BY od.$tblpkey DESC
                                                ");
                                                    foreach ($res as $row) {
                                                        $weekly_leave = $row['weekly_leave'];
                                                        $earn_leave = $row['earn_leave'];
                                                        $leave_balance = $weekly_leave + $earn_leave;
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
                                                                <?php echo $slno++; ?>
                                                                <i class="ri-add-circle-fill text-primary"></i>

                                                            </td>
                                                            <td><?= $obj->dateformatindia($row['application_date']) ?></td>
                                                            <td><?= $row['emp_code'] ?></td>
                                                            <td>
                                                                <b><?= $row['first_name'] . " " . $row['last_name']   ?> </b>
                                                            </td>
                                                            <td><?= $row['sub_emp_name'] ?></td>
                                                            <td><?= $obj->dateformatindia($row['from_date']) ?></td>
                                                            <td><?= $obj->dateformatindia($row['to_date']) ?></td>
                                                            <td><?= $row['total_day'] ?></td>
                                                            <td><?= $leave_balance ?></td>
                                                            <td><?= $row['used_leave'] ?></td>
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
                                                                    <a href="<?= $imgpath1 . $row['doc_file'] ?>" target="_blank">
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
                                                                    <a href="leave_application_pdf.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>" class="edit-item-btn" target="_blank">
                                                                        <i class="ri-printer-line cursor-pointer"></i>
                                                                    </a>
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $chkedit = $obj->check_editBtn($pagename, $loginid);
                                                                if ($chkedit == 1 && $row['pending_days'] > 0) { ?>
                                                                    <a href="leave_apply.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>" class="edit-item-btn">
                                                                        <i class="ri-edit-fill cursor-pointer text-success"></i>
                                                                    </a>
                                                                <?php } else { ?>
                                                                    <i class="ri-forbid-2-line cursor-pointer text-danger"></i>
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $chkdel = $obj->check_delBtn($pagename, $loginid);
                                                                if ($chkdel == 1 && $row['pending_days'] > 0) { ?>
                                                                    <a class="remove-item-btn" type="button" onclick="funDel('<?php echo $row[$tblpkey]; ?>','<?= $row['doc_file'] ?>');">
                                                                        <i class="ri-delete-bin-fill cursor-pointer text-danger"></i>
                                                                    </a>
                                                                <?php } else { ?>
                                                                    <i class="ri-forbid-2-line cursor-pointer text-danger"></i>
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
                    <h5 class="modal-title fw-bold">Leave Application Approval</h5>
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
                                    <td class="fw-semibold">Weekly Leave</td>
                                    <td id="modalWeeklyLeave"></td>
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
                                <tr>
                                    <td class="fw-semibold">Earning Leave</td>
                                    <td id="modalEarnLeave"></td>
                                </tr>
                            </table>
                        </div>

                    </div>

                    <div id="modalBodyContent">
                        <!-- AJAX content yaha load hoga -->
                    </div>


                    <!-- Update Button -->
                    <?php
                    $chkapr = $obj->check_aprBtn($pagename, $loginid);
                    if ($chkapr == 1) {  ?>
                        <div class="text-center mt-4">
                            <button id="updateOnDutyBtn" class="btn btn-danger px-4" onclick="updateLeaveStatus();">
                                Update
                            </button>
                        </div>
                    <?php } ?>

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
            $("#modalWeeklyLeave").text(data.weekly_leave);
            $("#modalEarnLeave").text(data.earn_leave);

            // $("#modalOnDutyType").text(data.on_duty_type.toUpperCase());
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
                url: "get_leave_details.php",
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
            }
        });

        $(document).on('change', '.reject_chk', function() {
            let row = $(this).closest('tr');
            if ($(this).is(':checked')) {
                row.find('.approve_chk').prop('checked', false);
            }
        }); // Approve All
        $(document).on('change', '#appr_check', function() {

            if ($(this).is(':checked')) {

                $('#rej_check').prop('checked', false);

                $('.approve_chk:not(:disabled)').prop('checked', true);
                $('.reject_chk:not(:disabled)').prop('checked', false);

            } else {

                $('.approve_chk:not(:disabled)').prop('checked', false);

            }

        });
        // Reject All
        $(document).on('change', '#rej_check', function() {

            if ($(this).is(':checked')) {

                $('#appr_check').prop('checked', false);

                $('.reject_chk:not(:disabled)').prop('checked', true);
                $('.approve_chk:not(:disabled)').prop('checked', false);

            } else {

                $('.reject_chk:not(:disabled)').prop('checked', false);

            }

        });


        function updateLeaveStatus() {
            let btn = $("#updateOnDutyBtn");
            let updates = [];
            $('.detail_row').each(function() {
                let id = $(this).data('id');

                if ($(this).find('.approve_chk').is(':disabled')) {
                    return;
                }

                let approveChecked = $(this).find('.approve_chk').is(':checked');
                let rejectChecked = $(this).find('.reject_chk').is(':checked');
                let status = 0;

                if (approveChecked) status = 1;
                else if (rejectChecked) status = 2;
                else return;

                let remark = $('#modal_appr_remark_' + id).val();
                let leave_type = $('#modal_leave_type_' + id).val();
                let leave_day = $('#modal_leave_day_' + id).val();

                let date = $('#modal_date_' + id).val();

                updates.push({
                    id: id,
                    status: status,
                    leave_type: leave_type,
                    leave_day: leave_day,
                    date: date,
                    remark: remark
                });

            });
            let emp_id = $("#modalEmpId").val();
            if (updates.length === 0) {
                alert("Please select at least one record");
                return;
            }
            btn.prop("disabled", true);
            btn.html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: "",
                type: "POST",
                data: {
                    updatess: updates,
                    emp_id: emp_id
                },
                success: function(response) {
                    console.log('response', response);
                    Swal.fire({
                        title: "Success!",
                        text: "Updated Successfully",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload();
                    });

                },
                error: function() {
                    Swal.fire({
                        title: "Error!",
                        text: "Something went wrong",
                        icon: "error",
                        confirmButtonColor: "#d33",
                        confirmButtonText: "OK"
                    });
                    btn.prop("disabled", false);
                    btn.html("Update");
                }
            });
        }



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
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&pagename=' + pagename + '&imgname=' + imgname + '&imgpath=' + imgpath,
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