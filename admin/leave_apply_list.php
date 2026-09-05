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
    $crit .= " AND lpd.date BETWEEN '$od_date_from' AND '$od_date_to'";
} elseif ($od_date_from != '') {
    $crit .= " AND lpd.date >= '$od_date_from'";
} elseif ($od_date_to != '') {
    $crit .= " AND lpd.date <= '$od_date_to'";
};


if (isset($_GET['application_month'])) {
    $application_month = $obj->test_input($_GET['application_month']);
    if ($application_month != '') {
        $crit .= " and MONTH(lpd.date)='$application_month'";
    }
} else {
    $application_month = '';
};

if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    if ($emp_id != '') {
        $crit .= " and od.emp_id='$emp_id'";
    }
} else {
    $emp_id = "";
};
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and em.department_id='$department_id'";
    }
} else {
    $department_id = "";
};
if (isset($_GET['status1'])) {
    $status1 = $obj->test_input($_GET['status1']);
    if ($status1 != '') {
        $crit .= " and lpd.status='$status1'";
    }
} else {
    $status1 = "";
};

if (isset($_GET['leave_type'])) {
    $leave_type = $obj->test_input($_GET['leave_type']);
    if ($leave_type != '') {
        $crit .= " and lpd.leave_type='$leave_type'";
    }
} else {
    $leave_type = "";
};


$approval_type = $_GET['approval_type'] ?? '';

if ($approval_type == 'hod') {
    $crit .= " AND lpd.is_apr_hod != 0 AND lpd.status = 0";
}

if ($approval_type == 'hr') {
    $crit .= " AND lpd.status != 0";
}
if ($approval_type == 'emp') {
    $crit .= " AND od.entry_by ='emp'";
}
if ($approval_type == 'e_hr') {
    $crit .= " AND od.entry_by ='hr'";
}
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
                    <?php if (!isset($_GET['submit'])) { ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?><a href="leave_apply.php"
                                                    class="float-end btn btn-primary btn-sm">Add</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">
                                            <label for="">Application Month</label>
                                            <select name="application_month" id="application_month"
                                                class="form-select form-select-sm chosen-select">
                                                <option value="">All</option>
                                                <option value="1">January</option>
                                                <option value="2">February</option>
                                                <option value="3">March</option>
                                                <option value="4">April</option>
                                                <option value="5">May</option>
                                                <option value="6">June</option>
                                                <option value="7">July</option>
                                                <option value="8">August</option>
                                                <option value="9">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                            <script>
                                            document.getElementById('application_month').value =
                                                '<?= $application_month; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3">
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

                                        <div class="col-lg-3 mb-2">
                                            <label for="status1" class="form-label">Status<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="status1"
                                                id="status1">
                                                <option value="">All</option>
                                                <option value="0">Pending</option>
                                                <option value="1">Approved</option>
                                                <option value="2">Reject</option>
                                            </select>
                                            <script>
                                            document.getElementById('status1').value = '<?= $status1; ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-3 mb-2">
                                            <label for="leave_type" class="form-label">Leave Type<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="leave_type"
                                                id="leave_type">
                                                <option value="">All</option>
                                                <option value="EL">EARNED LEAVE</option>
                                                <option value="EO">EXTRA OFF</option>
                                                <option value="CO">C-OFF</option>
                                            </select>
                                            <script>
                                            document.getElementById('leave_type').value = '<?= $leave_type; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 mb-2">
                                            <label for="leave_type" class="form-label">Show List<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" id="approval_type"
                                                name="approval_type">
                                                <option value="">All Records</option>
                                                <option value="emp">Entry By Employee</option>
                                                <option value="e_hr">Entry By HR</option>
                                                <option value="hod">Show Only HOD Approved And HR Pending Data</option>
                                                <option value="hr">Show Only HR Approved And Reject Data</option>
                                            </select>
                                            <script>
                                            document.getElementById('leave_type').value = '<?= $leave_type; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="emp_id" class="form-label">Department<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="department_id" id="department_id"
                                                onchange="get_employee(this.value);">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_id asc");
                                                foreach ($res as $key) { ?>
                                                <option value="<?= $key['department_id']; ?>">
                                                    <?= $key['department_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById('department_id').value =
                                                '<?= $department_id; ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-3">
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
                                        <div class="col-lg-3">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn"
                                                value="<?php echo $btn_name ?> " onclick="return validateSearch()">
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
                                            <h5 class="card-title mb-0"> <?= $module; ?><a href="leave_apply_list.php"
                                                    class="float-end btn btn-primary btn-sm ms-2">Search Again</a> <a
                                                    href="leave_apply.php"
                                                    class="float-end btn btn-primary btn-sm">Add</a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <div class="auto-scroll-wrapper">
                                            <div class="table-responsive">
                                                <table id="buttons-datatables" class="table table-sm table-bordered">
                                                    <thead>
                                                        <tr class="table-primary">
                                                            <th>Sr No.</th>
                                                            <th>Date</th>
                                                            <th>Emp Code</th>
                                                            <th>Emp Name</th>
                                                            <th>Department</th>
                                                            <th>Designation</th>
                                                            <th>Substitute Employee</th>
                                                            <th>Leave From </th>
                                                            <th>Leave To </th>
                                                            <th>Days</th>
                                                            <th>Balance Leave</th>
                                                            <th>Earn Leave</th>
                                                            <th>Extra Off</th>
                                                            <th>C-Off</th>
                                                            <th>Approve</th>
                                                            <th>Reject</th>
                                                            <th>Pending</th>
                                                            <th>Doc</th>
                                                            <th>Print</th>
                                                            <th>Edit</th>
                                                            <th>Delete</th>
                                                            <th>Status <input type="checkbox" id="checkAllLeaves"
                                                                    class="form-check-input"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $slno = 1;
                                                            $sql = "SELECT 
                                                                od.*,
                                                                em.first_name,
                                                                em.department_id,
                                                                dm.department_name,
                                                                desi.designation,
                                                                sem.first_name as sub_emp_name,
                                                                em.last_name,
                                                                em.is_esic,
                                                                em.emp_code,
                                                                MIN(lpd.date) as from_date,
                                                                MAX(lpd.date) as to_date,
                                                                MONTH(MIN(lpd.date)) AS apply_month,
                                                                YEAR(MIN(lpd.date)) AS apply_year,
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
                                                        SUM(
                                                            CASE
                                                                WHEN lpd.leave_type = 'EL' THEN
                                                                    CASE
                                                                        WHEN lpd.leave_day IN ('FD','SL') THEN 1
                                                                        WHEN lpd.leave_day IN ('FHD','SHD') THEN 0.5
                                                                        ELSE 0
                                                                    END
                                                                ELSE 0
                                                            END
                                                        ) AS used_el,

                                                        SUM(
                                                            CASE
                                                                WHEN lpd.leave_type = 'EO' THEN
                                                                    CASE
                                                                        WHEN lpd.leave_day IN ('FD','SL') THEN 1
                                                                        WHEN lpd.leave_day IN ('FHD','SHD') THEN 0.5
                                                                        ELSE 0
                                                                    END
                                                                ELSE 0
                                                            END
                                                        ) AS used_eo,

                                                        SUM(
                                                            CASE
                                                                WHEN lpd.leave_type = 'CO' THEN
                                                                    CASE
                                                                        WHEN lpd.leave_day IN ('FD','SL') THEN 1
                                                                        WHEN lpd.leave_day IN ('FHD','SHD') THEN 0.5
                                                                        ELSE 0
                                                                    END
                                                                ELSE 0
                                                            END
                                                        ) AS used_co,

                                                        CASE 
                                                            WHEN od.entry_by = 'emp' THEN CONCAT(em.first_name,' ',em.last_name)
                                                            ELSE cu.fullname
                                                        END AS created_name,

                                                        CASE 
                                                            WHEN od.entry_by = 'emp' THEN em.emp_code
                                                            ELSE cu.username
                                                        END AS created_username,

                                                        CASE 
                                                            WHEN od.entry_by = 'emp' THEN em.mobile_no
                                                            ELSE cu.mobile
                                                        END AS created_mobile, 

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
                                                            LEFT JOIN department_master dm 
                                                                ON dm.department_id = em.department_id
                                                            LEFT JOIN designation_master desi 
                                                                ON desi.designation_id = em.designation_id 

                                                            LEFT JOIN user cu 
                                                                ON od.createdby = cu.userid

                                                            LEFT JOIN user uu 
                                                                ON od.updatedby = uu.userid

                                                            where od.unit_id='$unitid' and od.type='leave' $crit
                                                            
                                                            GROUP BY od.on_duty_id
                                                            ORDER BY od.$tblpkey DESC
                                                        "; 
 
                                                            $res = $obj->executequery($sql);
                                                            foreach ($res as $row) {
                                                                // $weekly_leave = $row['weekly_leave'];
                                                                // $earn_leave = $row['earn_leave'];
                                                                // $leave_balance = $weekly_leave + $earn_leave;
                                                                $empId = $row['emp_id'];

                                                                $month = (int)$row['apply_month'];
                                                                $year  = (int)$row['apply_year'];
                                                                
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
                                                            <td class="details-control text-center"
                                                                style="cursor:pointer;">
                                                                <?php echo $slno++; ?>
                                                                <i class="ri-add-circle-fill text-primary"></i>

                                                            </td>
                                                            <td><?= $obj->dateformatindia($row['application_date']) ?>
                                                            </td>
                                                            <td><?= $row['emp_code'] ?></td>
                                                            <td>
                                                                <b><?= $row['first_name'] ?> </b>
                                                            </td>
                                                            <td>
                                                                <b><?= $row['department_name'] ?> </b>
                                                            </td>
                                                            <td>
                                                                <b><?= $row['designation'] ?> </b>
                                                            </td>
                                                            <td><?= $row['sub_emp_name'] ?></td>
                                                            <td><?= $obj->dateformatindia($row['from_date']) ?></td>
                                                            <td><?= $obj->dateformatindia($row['to_date']) ?></td>
                                                            <td><?= $row['total_day'] ?></td>
                                                            <td>
                                                                <a href="javascript:void(0)" onclick="showLeaveBalance(
                                                                            <?= $row['emp_id'] ?>,
                                                                            <?= $row['apply_month'] ?>,
                                                                            <?= $row['apply_year'] ?>
                                                                    )">
                                                                    View Balance
                                                                </a>
                                                            </td>
                                                            <td><?= $row['used_el'] ?></td>
                                                            <td><?= $row['used_eo'] ?></td>
                                                            <td><?= $row['used_co'] ?></td>
                                                            <td class="cursor-pointer"
                                                                onclick='openOnDutyModal("approved", <?= json_encode($row) ?>,<?= $month ?>,<?= $year ?>)'>
                                                                <b><?= $row['approved_days'] ?></b>
                                                            </td>

                                                            <td class="cursor-pointer"
                                                                onclick='openOnDutyModal("rejected", <?= json_encode($row) ?>,<?= $month ?>,<?= $year ?>)'>
                                                                <?= $row['rejected_days'] ?>
                                                            </td>

                                                            <td class="cursor-pointer"
                                                                onclick='openOnDutyModal("pending", <?= json_encode($row) ?>,<?= $month ?>,<?= $year ?>)'>
                                                                <?= $row['pending_days'] ?>
                                                            </td>  

                                                            <td>
                                                                <?php if (!empty($row['doc_file'])) { ?>
                                                                <a href="<?= $imgpath1 . $row['doc_file'] ?>"
                                                                    target="_blank">
                                                                    <i class="ri-attachment-2 cursor-pointer"></i>
                                                                </a>
                                                                <?php } else { ?>
                                                                <i
                                                                    class="ri-forbid-2-line cursor-pointer text-danger"></i>
                                                                <?php } ?>
                                                            </td>
                                                            <!-- Icons -->

                                                            <td>
                                                                <?php
                                                                        $chkprint = $obj->check_printBtn($pagename, $loginid);
                                                                        if ($chkprint == 1) {  ?>
                                                                <a href="leave_application_pdf.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>"
                                                                    class="edit-item-btn" target="_blank">
                                                                    <i class="ri-printer-line cursor-pointer"></i>
                                                                </a>
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                        $chkedit = $obj->check_editBtn($pagename, $loginid);
                                                                        if ($chkedit == 1 && $row['pending_days'] > 0) { ?>
                                                                <a href="leave_apply.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>"
                                                                    class="edit-item-btn">
                                                                    <i
                                                                        class="ri-edit-fill cursor-pointer text-success"></i>
                                                                </a>
                                                                <?php } else { ?>
                                                                <i
                                                                    class="ri-forbid-2-line cursor-pointer text-danger"></i>
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                        $chkdel = $obj->check_delBtn($pagename, $loginid);
                                                                        if ($chkdel == 1 && $row['approved_days'] == 0) { ?>
                                                                <a class="remove-item-btn" type="button"
                                                                    onclick="funDel('<?php echo $row[$tblpkey]; ?>','<?= $row['doc_file'] ?>');">
                                                                    <i
                                                                        class="ri-delete-bin-fill cursor-pointer text-danger"></i>
                                                                </a>
                                                                <?php } else { ?>
                                                                <i
                                                                    class="ri-forbid-2-line cursor-pointer text-danger"></i>
                                                                <?php } ?>
                                                            </td>
                                                            <td class="cursor-pointer">
                                                                <i class="ri-checkbox-fill text-success fs-5"
                                                                    onclick='openOnDutyModal("all", <?= json_encode($row) ?> ,<?= $month ?>,<?= $year ?>)'></i>

                                                                <input type="checkbox"
                                                                    class="form-check-input bulk_leave_chk"
                                                                    value="<?= $row['on_duty_id'] ?>"
                                                                    data-department="<?= $row['department_id'] ?>">
                                                            </td>

                                                        </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="text-end mt-3">
                                                <button class="btn btn-sm btn-success"
                                                    onclick="approveSelectedLeaves(1)">
                                                    <i class="ri-check-double-line"></i>
                                                    Approve Selected
                                                </button>
                                                <button class="btn btn-sm btn-warning"
                                                    onclick="approveSelectedLeaves(0)">
                                                    <i class="ri-check-double-line"></i>
                                                    Pending Selected
                                                </button>
                                            </div>
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
                    <h5 class="modal-title fw-bold">Leave Application Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Body -->
                <div class="modal-body">
                    <!-- Top Info -->
                   

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

    function openOnDutyModal(type, data, month, year) {
        let imgpath = '<?= $imgpath1 ?>';
        let appDate = new Date(data.application_date);
 
        // $("#modalOnDutyType").text(data.on_duty_type.toUpperCase());
        $("#modalEmpName").text(data.first_name + " " + data.last_name);
        $("#modalEmpCode").text(data.emp_code);
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

        Swal.fire({
            title: 'Loading...',
            html: 'Please wait while fetching leave details.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "get_leave_details.php",
            type: "POST",
            data: {
                type: type,
                on_duty_id: data.on_duty_id,
                total_days: data.total_day, 
                month: month,
                year: year
            },
            success: function(response) {
                Swal.close();
                $("#modalBodyContent").html(response);
                $("#staticBackdrop").modal('show');
            },
            error: function() {
                Swal.close();
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Unable to load leave details."
                });
            }
        });

    }

    $(document).on("change", "#checkAllLeaves", function() {

        $(".bulk_leave_chk").prop(
            "checked",
            $(this).is(":checked")
        );

    });

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


    function updateLeaveStatus() {
        let btn = $("#updateOnDutyBtn");
        let updates = [];
        $('.detail_row').each(function() {
            let id = $(this).data('id');
            let od_id = $(this).data('odid');
            // if ($(this).find('.approve_chk').is(':disabled')) {
            //     return;
            // }
            // let approveChecked = $(this).find('.approve_chk').is(':checked');
            // let rejectChecked = $(this).find('.reject_chk').is(':checked');

            let approveBox = $(this).find('.approve_chk');
            let rejectBox = $(this).find('.reject_chk');
            let pendingBox = $(this).find('.pending_chk');

            let approveChecked = approveBox.is(':checked');
            let rejectChecked = rejectBox.is(':checked');
            let pendingChecked = pendingBox.is(':checked');

            //let approveDisabled = approveBox.is(':disabled');
            // let rejectDisabled = rejectBox.is(':disabled');
            // let status = 0;

            // if (approveDisabled && rejectDisabled) {
            //     return;
            // }

            let status = 0;
            if (approveChecked) {
                status = 1;
            } else if (rejectChecked) {
                status = 2;
            } else if (pendingChecked) {
                status = 0;
            } else {
                return;
            }

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
            url: "leave_approve.php",
            type: "POST",
            data: {
                updatess: updates,
                emp_id: emp_id
            },
            success: function(response) {
                let res = JSON.parse(response);
                let message = '';
                if (res.pendingCount > 0) {
                    message += res.pendingCount + ' leave(s) moved to pending successfully.<br>';
                }
                if (res.approvedCount > 0) {
                    message += res.approvedCount + ' leave(s) approved successfully.<br>';
                }
                if (res.rejectedCount > 0) {
                    message += res.rejectedCount + ' leave(s) rejected successfully.<br>';
                }
                if (res.errors.length > 0) {
                    message += '<br><b>Skipped Records:</b><br>';
                    res.errors.forEach(function(item) {
                        let formattedDate = item.date.split('-').reverse().join('-');
                        message += formattedDate + ' - ' + item.reason + '<br>';
                    });
                    Swal.fire({
                        title: "Completed With Warnings",
                        html: message,
                        icon: "warning",
                        width: 700
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: "Success",
                        html: message,
                        icon: "success"
                    }).then(() => {
                        location.reload();
                    });
                }
                btn.prop("disabled", false);
                btn.html("Update");
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
function approveSelectedLeaves(status) {

    let ids = [];
    let departments = [];

    $(".bulk_leave_chk:checked").each(function () {
        ids.push($(this).val());
        departments.push($(this).data("department"));
    });

    if (ids.length == 0) {
        Swal.fire("Please select at least one leave.");
        return;
    }

    let actionLabel = "";
    let processingText = "";

    if (status == 1) {
        actionLabel = "Approve";
        processingText = "Please wait while approving selected leaves.";
    } else if (status == 0) {
        actionLabel = "Pending";
        processingText = "Please wait while updating selected leaves.";
    } else {
        actionLabel = "Reject";
        processingText = "Please wait while rejecting selected leaves.";
    }

    Swal.fire({
        title: `${actionLabel} selected leaves?`,
        text: "This may take some time.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: `Yes, ${actionLabel}`
    }).then((result) => {

        if (!result.isConfirmed)
            return;

        Swal.fire({
            title: "Processing...",
            html: processingText,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        //-------------------------------------------------------
        // Batch Settings
        //-------------------------------------------------------

        const batchSize = 20;
        let currentBatch = 0;

        //-------------------------------------------------------
        // Summary Variables
        //-------------------------------------------------------

        let totalApplication = 0;
        let totalLeaves = 0;
        let approvedCount = 0;
        let pendingCount = 0;
        let rejectedCount = 0;
        let failedCount = 0;

        let allErrors = [];

        //-------------------------------------------------------
        // Process Batch
        //-------------------------------------------------------

        function processBatch() {

            let batchIds = ids.slice(currentBatch, currentBatch + batchSize);

            if (batchIds.length == 0) {

                let errorHtml = "";

                if (allErrors.length > 0) {

                    errorHtml = `
                    <br><br>
                    <div style="max-height:250px;overflow:auto">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>`;

                    $.each(allErrors, function (i, row) {

                        errorHtml += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${row.employee}</td>
                            <td>${row.date}</td>
                            <td>${row.reason}</td>
                        </tr>`;

                    });

                    errorHtml += `
                        </tbody>
                    </table>
                    </div>`;
                }

                let countLine = "";

                if (status == 1)
                    countLine = `<b class="text-success">Approved :</b> ${approvedCount}<br>`;

                if (status == 0)
                    countLine = `<b class="text-warning">Pending :</b> ${pendingCount}<br>`;

                if (status == 2)
                    countLine = `<b class="text-danger">Rejected :</b> ${rejectedCount}<br>`;

                Swal.fire({
                    icon: failedCount > 0 ? "warning" : "success",
                    title: `Bulk ${actionLabel} Completed`,
                    width: 900,
                    html: `
                    <div class="text-start">
                        <b>Total Applications :</b> ${totalApplication}<br>
                        <b>Total Leave Dates :</b> ${totalLeaves}<br>
                        ${countLine}
                        <b class="text-danger">Skipped :</b> ${failedCount}
                        ${errorHtml}
                    </div>`
                }).then(() => {
                    location.reload();
                });

                return;
            }

            //---------------------------------------------------
            // Update Progress
            //---------------------------------------------------

            Swal.update({
                html: `
                    ${processingText}
                    <br><br>
                    <b>Processing ${Math.min(currentBatch + batchIds.length, ids.length)} of ${ids.length}</b>
                `
            });

            //---------------------------------------------------
            // AJAX
            //---------------------------------------------------

            $.ajax({

                url: "leave_approve_bulk.php",
                type: "POST",
                dataType: "json",
                timeout: 600000,

                data: {
                    bulkApprove: 1,
                    status: status,
                    ids: batchIds
                },

                success: function (res) {

                    totalApplication += parseInt(res.totalApplication);
                    totalLeaves += parseInt(res.totalLeaves);

                    approvedCount += parseFloat(res.approvedCount);
                    pendingCount += parseFloat(res.pendingCount);
                    rejectedCount += parseFloat(res.rejectedCount);
                    failedCount += parseFloat(res.failedCount);

                    if (res.errors && res.errors.length > 0) {
                        allErrors.push(...res.errors);
                    }

                    currentBatch += batchSize;

                    processBatch();

                },

                error: function () {

                    Swal.fire(
                        "Error",
                        "One batch failed.",
                        "error"
                    );

                }

            });

        }

        processBatch();

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

    function showLeaveBalance(emp_id, month, year) {
        $.ajax({
            url: "get_leave_balance.php",
            type: "POST",
            data: {
                emp_id: emp_id,
                month: month,
                year: year
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

            },
            success: function(response) {
                Swal.fire({
                    title: 'Leave Balance',
                    html: response,
                    width: 700
                });

            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to fetch leave balance'
                });

            }
        });
    }

    $(document).ready(function() {

        $('#application_month').on('change', function() {

            let month = parseInt($(this).val());

            if (!month) return;

            // Current year
            let year = new Date().getFullYear();

            // First date of month
            let firstDate = new Date(year, month - 1, 1);

            // Last date of month
            let lastDate = new Date(year, month, 0);

            // Format YYYY-MM-DD
            let fromDate =
                firstDate.getFullYear() + '-' +
                String(firstDate.getMonth() + 1).padStart(2, '0') + '-' +
                String(firstDate.getDate()).padStart(2, '0');

            let toDate =
                lastDate.getFullYear() + '-' +
                String(lastDate.getMonth() + 1).padStart(2, '0') + '-' +
                String(lastDate.getDate()).padStart(2, '0');

            // Set values
            $('#od_date_from').val(fromDate);
            $('#od_date_to').val(toDate);

        });

    });

    function get_employee(department_id, emp_id = 0) {
        $.ajax({
            type: "POST",
            url: 'get_dep_wise_emp.php',
            data: {
                department_id: department_id,
                emp_id: emp_id
            },

            success: function(data) {
                $('#emp_id').html(data).trigger("change.select2");
            }
        });

    }

    function validateSearch() {

        let department = $('#department_id').val();
        let employee = $('#emp_id').val();
        let fromDate = $('#od_date_from').val();
        let toDate = $('#od_date_to').val();

        // if (department == '' && employee == '') {
        //     alert("Please select at least Department or Employee.");
        //     $('#department_id').focus();
        //     return false;
        // }

        if (fromDate != '' && toDate == '') {
            alert("Please select To Date.");
            $('#od_date_to').focus();
            return false;
        }

        if (fromDate == '' && toDate != '') {
            alert("Please select From Date.");
            $('#od_date_from').focus();
            return false;
        }

        if (fromDate != '' && toDate != '' && fromDate > toDate) {
            alert("From Date cannot be greater than To Date.");
            $('#od_date_from').focus();
            return false;
        }

        return true;
    }
    </script>
</body>

</html>