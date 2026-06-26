<?php include("../adminsession.php");
$pagename = "emp_separation_list.php";
$title = "Employee Separation Master";
$tblname = "employee_exit";
$tblpkey = "exit_id";
$module = "Employee Separation Master";
$submodule = "Employee Separation Master List";
$btn_name = "Search";
$new_emp_code = $obj->getcode("employee_master", "emp_code",  "1='1'");
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = ' and 1=1';
if (isset($_GET['month'])) {
    $month = $obj->test_input($_GET['month']);
    if ($month != '') {
        $crit .= " and t.month='$month'";
    }
} else {
    $month = "";
};

if (isset($_GET['year'])) {
    $year = $obj->test_input($_GET['year']);
    if ($year != '') {
        $crit .= " and t.year='$year'";
    }
} else {
    $year = "";
};
if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    if ($emp_id != '') {
        $crit .= " and t.emp_id='$emp_id'";
    }
} else {
    $emp_id = "";
};

$fromdate = $_GET['fromdate'] ?? '';
$todate = $_GET['todate'] ?? '';

if ($fromdate != '' && $todate != '') {
    $crit .= " AND t.resignation_date BETWEEN '$fromdate' AND '$todate'";
} elseif ($fromdate != '') {
    $crit .= " AND t.resignation_date >= '$fromdate'";
} elseif ($todate != '') {
    $crit .= " AND t.resignation_date <= '$todate'";
}

if (isset($_REQUEST['ajstatus'])) {
    $status = $obj->test_input($_REQUEST['ajstatus']);
    $exit_idd = $obj->test_input($_REQUEST['exit_idd']);
    $emp_idd = $obj->test_input($_REQUEST['emp_id']);
    $reason_for_reject = $obj->test_input($_REQUEST['reason'] ?? '');
    $last_working_date = $obj->test_input($_REQUEST['last_working_date']);

    $obj->update_record("employee_exit", array("exit_id" => $exit_idd), array('is_approved' => $status, 'reason_for_reject' => $reason_for_reject, 'approved_date' => $createdate));
    $obj->update_record("employee_master", array("emp_id" => $emp_idd), array('resign_status' => $status, 'last_working_date' => $last_working_date, 'resign_approve_date' => $createdate));

    if ($status == 0) {
        $activity_status = "Pending";
    } elseif ($status == 1) {
        $activity_status = "Approved";
    } elseif ($status == 2) {
        $activity_status = "Rejected";
    } else {
        $activity_status = "Unknown";
    }

    $form_data1 = array(
        "primary_id" => $exit_idd,
        "flag" => $title,
        "activity_type" => $activity_status,
        "createdby" => $loginid,
        "pagename" => $pagename,
        "created_date" => $createdate,
        "created_time" => date('H:i:s'),
        "unit_id" => $unitid,
        "sessionid" => $sessionid
    );
    $logactivity = $obj->insert_record("logactivity_master", $form_data1);

    echo 1;
    exit();
}


if (isset($_POST['bulk_approve']) && $_POST['bulk_approve'] == 1) {

    $ids = $_POST['ids'];

    if (!empty($ids)) {

        $id_list = implode(",", array_map('intval', $ids));

        $records = $obj->executequery(
            "SELECT exit_id, emp_id, last_working_date 
             FROM employee_exit 
             WHERE exit_id IN ($id_list)"
        );

        foreach ($records as $row) {
            $exit_id = $row['exit_id'];
            $emp_id = $row['emp_id'];
            $last_working_date = $row['last_working_date'];

            // Update emp_separation
            $obj->update_record(
                "employee_exit",
                ['exit_id' => $exit_id],
                [
                    'is_approved' => 1,
                    'approved_date' => $createdate
                ]
            );

            // Update employee_master
            if ($last_working_date != '') {
                $obj->update_record(
                    "employee_master",
                    ['emp_id' => $emp_id],
                    [
                        'resign_status' => 1,
                        'last_working_date' => $last_working_date,
                        'resign_approve_date' => $createdate
                    ]
                );
            }
        }

        echo json_encode(["status" => "success"]);
        exit;
    }
}

if (isset($_POST['rejoin_action']) && $_POST['rejoin_action'] == 1) {

    $exit_id = $obj->test_input($_POST['exit_id']) ?? 0;
    $rejoin_emp_id = $obj->test_input($_POST['rejoin_emp_id']) ?? 0;
    $actual_date_of_joining =$obj->getvalfield("employee_master","date_of_joining","emp_id='$rejoin_emp_id'");
    $rejoin_date = $obj->test_input($_POST['rejoin_date']) ?? "";
    $remark =  $obj->test_input($_POST['remark']) ?? '';
    $prev_join_date = $obj->getvalfield("employee_master", "date_of_joining", "emp_id='$rejoin_emp_id'");
    $rejoin_with_new_id = $obj->test_input($_POST['new_emp_checkbox']) ?? 0;
    $new_join_checkbox = $obj->test_input($_POST['new_join_checkbox']) ?? 0;
    $new_emp_code = $obj->test_input($_POST['new_emp_code']) ?? '';
    $biometric_id = $obj->test_input($_POST['biometric_id']) ?? '';
    $show_emp_code = $obj->test_input($_POST['show_emp_code']) ?? '';

    $if_exist = $obj->getvalfield("employee_master", "count(*)", "emp_code='$new_emp_code'");
    if ($if_exist > 0) {
        $new_emp_code = $obj->getcode("employee_master", "emp_code",  "1=1");
        $biometric_id = $obj->getcode("employee_master", "emp_code",  "1=1");
    }

    if ($rejoin_with_new_id != 1) {
        $new_emp_code = $show_emp_code;
        $biometric_id = $obj->getvalfield(
            "employee_master",
            "biomatric_id",
            "emp_id='$rejoin_emp_id'"
        );
    }

    if($new_join_checkbox == 0){
        $rejoin_date=$actual_date_of_joining;
    }

    // Update emp_separation
    $obj->update_record(
        "employee_exit",
        ['exit_id' => $exit_id],
        [
            'is_rejoined' => 1,
            'rejoin_date' => $rejoin_date,
            'prev_joining_date' => $prev_join_date,
            'rejoin_remark' => $remark,
            'rejoin_with_new_id' => $rejoin_with_new_id,
            'new_emp_code' => $new_emp_code,
            'biometric_id' => $biometric_id,
            'prev_emp_code' => $show_emp_code,
        ]
    );
    // Update employee_master
    $obj->update_record(
        "employee_master",
        ['emp_id' => $rejoin_emp_id],
        [
            'resign_status' => 0,
            'emp_code' => $new_emp_code,
            'biomatric_id' => $biometric_id,
            'date_of_joining' => $rejoin_date,
            'last_working_date' => NULL
        ]
    );

    echo json_encode(["status" => "success"]);
    exit;
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
                    <?php if (!isset($_GET['submit'])) { ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?> <a href="emp_separation.php"
                                                        class="float-end btn btn-primary btn-sm">Add New</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="get">
                                        <div class="row">

                                            <div class="col-lg-3 mb-3">
                                                <label for="emp_id" class="form-label">Employee Name<span
                                                        class="text-danger fw-bold"> </span></label>
                                                <select class="form-select form-select-sm chosen-select" name="emp_id"
                                                    id="emp_id">
                                                    <option value="">All</option>
                                                    <?php
                                                    //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                    $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' ORDER BY first_name ASC");
                                                    foreach ($res as $key) { ?>
                                                        <option value="<?= $key['emp_id']; ?>">
                                                            <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?>
                                                            <?= ucfirst($key['last_name'] ?? ''); ?></option>
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
                                                    <input type="date" class="form-control form-control-sm" name="fromdate"
                                                        id="fromdate" placeholder='dd-mm-yyyy'
                                                        value="<?php echo $fromdate; ?>">
                                                    <span class="input-group-text">To</span>
                                                    <input type="date" class="form-control form-control-sm" name="todate"
                                                        id="todate" placeholder='dd-mm-yyyy' value="<?php echo $todate; ?>">
                                                </div>
                                            </div>

                                            <div class="col-lg-3 mt-4">
                                                <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn"
                                                    value="Search">
                                                <a href="<?php echo $pagename ?>"
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
                                                <h5 class="card-title mb-0"><?php echo $submodule; ?> <a href="emp_separation_list.php"
                                                        class="float-end btn btn-primary btn-sm">Search Again</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body auto-scroll-wrapper">
                                    <div class="table-responsive">
                                        <table id="buttons-datatables" class="display table table-sm table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>Sr No.</th>
                                                    <th>Emp Code</th>
                                                    <th>Employee Name</th>
                                                    <th>Department</th>
                                                    <th>Designation</th>
                                                    <th>Basic Salary</th>
                                                    <th>Aadhar No</th>
                                                    <th>Mobile No</th>
                                                    <th>Exit Type</th>
                                                    <th>Joining Date</th>
                                                    <th>Resignation Date</th>
                                                    <th>Last Working Date</th>
                                                    <th>Total Working Days</th>
                                                    <th>Notice Period (Days)</th>
                                                    <th>Reason</th>
                                                    <th>Approved Status <input type="checkbox" id="checkAll"
                                                            class="form-check-input" /></th>

                                                    <th>Actions </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;
                                                // $res = $obj->executequery("SELECT * FROM $tblname where unit_id='$unitid' $crit ORDER BY $tblpkey desc ");

                                                $res = $obj->executequery("SELECT 
                                                        t.*, 
                                                        em.date_of_joining,
                                                        em.emp_code,
                                                        em.basic_salary,
                                                        em.first_name,
                                                        em.last_name,
                                                        dm.department_name,
                                                        em.mobile_no,
                                                        em.aadhar_no,
                                                        desi.designation,
                                                        cu.fullname as created_name,
                                                        cu.username as created_username,
                                                        cu.mobile as created_mobile,

                                                        uu.fullname as updated_name,
                                                        uu.username as updated_username,
                                                        uu.mobile as updated_mobile
                                                    FROM $tblname t
                                                    LEFT JOIN employee_master em ON em.emp_id = t.emp_id
                                                    LEFT JOIN department_master dm ON dm.department_id = em.department_id
                                                    LEFT JOIN designation_master desi ON desi.designation_id = em.designation_id
                                                    LEFT JOIN user cu ON t.createdby = cu.userid
                                                    LEFT JOIN user uu ON t.updatedby = uu.userid
                                                    WHERE t.unit_id = '$unitid' $crit
                                                    ORDER BY t.$tblpkey DESC
                                                ");

                                                foreach ($res as $row) {


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
                                                    <tr data-details="
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
                                                        <td><?= $row['emp_code']; ?> </td>
                                                        <td> <?= ucfirst($row['first_name'] ?? ''); ?>
                                                            <?= ucfirst($row['last_name'] ?? ''); ?></td>
                                                        <td><?php echo $row["department_name"]; ?></td>
                                                        <td><?php echo $row["designation"]; ?></td>
                                                        <td><?php echo $row["basic_salary"]; ?></td>
                                                        <td><?php echo $row["aadhar_no"]; ?></td>
                                                        <td><?php echo $row["mobile_no"]; ?></td>
                                                        <td><?php echo $row["exit_type"]; ?></td>
                                                        <td><?php echo $obj->dateformatindia($row["date_of_joining"]); ?></td>
                                                        <td><?= $obj->dateformatindia($row["resignation_date"]); ?></td>
                                                        <td><?= $obj->dateformatindia($row["last_working_date"]); ?></td>
                                                        <td>
                                                            <?php
                                                            echo $obj->getWorkingDuration($row["date_of_joining"], $row["last_working_date"]);
                                                            ?>
                                                        </td>
                                                        <td><?php echo $row["notice_period"]; ?></td>
                                                        <td><?php echo $row["reason_for_leaving"]; ?></td>
                                                        <td class="text-center">
                                                            <?php $chkapr = $obj->check_aprBtn($pagename, $loginid);
                                                            if ($chkapr == 1 && $row['is_approved'] == 0) { ?>
                                                                <a href="javascript:void(0)" title="Change Status"
                                                                    onclick="openStatusModal('<?= $row['is_approved']; ?>','<?= $row['exit_id']; ?>','<?= $row['emp_id']; ?>','<?= $row['last_working_date']; ?>')">
                                                                    <span class="badge <?= $badgeClass; ?> me-2">
                                                                        <?= $statusText; ?>
                                                                    </span>
                                                                </a>
                                                                <input type="checkbox" class="appr_single form-check-input"
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
                                                                <?php
                                                                $chkdel = $obj->check_delBtn($pagename, $loginid);
                                                                if ($chkdel == 1 && ($row['is_approved'] == 0 || $row['is_approved'] == 2)) { ?>
                                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                                        title="Delete">
                                                                        <a class="remove-item-btn" type="button"
                                                                            onclick="funDel('<?php echo $row[$tblpkey]; ?>','<?= $row['emp_id'] ?>');">
                                                                            <i
                                                                                class="ri-delete-bin-fill align-bottom text-danger"></i>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                                <?php $chkedit = $obj->check_editBtn($pagename, $loginid);
                                                                if ($chkedit == 1 && $row['is_approved'] == 0) {  ?>

                                                                    <li class="list-inline-item " data-bs-toggle="tooltip"
                                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                                        title="Edit">
                                                                        <a href="emp_separation.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>"
                                                                            class="edit-item-btn">
                                                                            <i class="ri-pencil-fill align-bottom text-success"></i>
                                                                        </a>
                                                                    </li>
                                                                <?php  }

                                                                if ($row['is_approved'] == 1 && $row['is_rejoined'] == 0 && $row['exit_type'] != 'Blacklist') { ?>
                                                                    <span class="badge bg-primary me-2 cursor-pointer"
                                                                        onclick="openRejoinModal('<?php echo $row[$tblpkey]; ?>','<?php echo $row['emp_id']; ?>','<?= ucfirst($row['first_name'] ?? ''); ?>','<?= $row['emp_code']; ?>');">
                                                                        Rejoin
                                                                    </span>
                                                                <?php }
                                                                if ($row['is_rejoined'] == 1) { ?>
                                                                    <div style="cursor:pointer;" onclick="openRejoinDetailsModal(
                                                                '<?= ucfirst($row['first_name'] . ' ' . $row['last_name']); ?>',
                                                                '<?= $row['rejoin_with_new_id']; ?>',
                                                                '<?= $row['prev_emp_code']; ?>',
                                                                '<?= $row['new_emp_code']; ?>',
                                                                '<?= $row['biometric_id']; ?>',
                                                                '<?= $row['emp_code']; ?>',
                                                            
                                                                '<?= addslashes($row['rejoin_remark']); ?>'
                                                            )">

                                                                        <span class="badge bg-success mb-1 d-inline-block">
                                                                            Re-joined
                                                                        </span>

                                                                    </div>
                                                                <?php } ?>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <div class="col-lg-12 mt-4 text-end">
                                            <input type="submit" name="appr_status" class="btn btn-sm btn-primary add-btn"
                                                value="Approve All" onclick="updateStatus();">
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

    <!-- Rejoin Details Modal -->
    <div class="modal fade" id="rejoinDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title">Employee Rejoin Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>


                <div class="modal-body">

                    <div class="mb-2">
                        <b>Employee :</b>
                        <span id="detail_emp_name"></span>
                    </div>

                    <div class="mb-2">
                        <b>Rejoin Type :</b>
                        <span id="detail_rejoin_type"></span>
                    </div>

                    <div class="mb-2">
                        <b>Previous Code :</b>
                        <span id="detail_prev_code"></span>
                    </div>

                    <div class="mb-2">
                        <b>New Code :</b>
                        <span id="detail_new_code"></span>
                    </div>

                    <div class="mb-2" id="bio_div">
                        <b>Biometric ID :</b>
                        <span id="detail_biometric"></span>
                    </div>

                    <div class="mb-2">
                        <b>Remark :</b><br>
                        <span id="detail_remark"></span>
                    </div>

                </div>

            </div>
        </div>
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
                    <input type="hidden" id="prevv_emp_code">
                    <div class="mb-3">
                        <b>Employee :</b>
                        <b> <span id="show_emp_name"></span>
                            (<span id="show_emp_code"></span>)</b>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rejoining Date</label>
                        <input type="date" class="form-control form-control-sm" id="rejoin_date">
                    </div>
                    <!-- Checkbox -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="new_emp_checkbox"
                                onchange="toggleNewEmpFields()">

                            <label class="form-check-label" for="new_emp_checkbox">
                                Rejoin With New Emp Code
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="new_join_checkbox">
                            <label class="form-check-label" for="new_join_checkbox">
                                Rejoin With New Joining Date
                            </label>
                        </div>
                    </div>

                    <div id="new_emp_fields" style="display:none;">

                        <div class="mb-3">
                            <label class="form-label">New Employee Code</label>
                            <input type="text" class="form-control form-control-sm" id="new_emp_code"
                                placeholder="Enter New Employee Code" value="<?= $new_emp_code; ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Biometric ID</label>
                            <input type="text" class="form-control form-control-sm" id="biometric_id" placeholder="Enter Biometric ID"
                                value="<?= $new_emp_code; ?>" readonly>
                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remark</label>
                        <textarea class="form-control form-control-sm" id="rejoin_remark" rows="3"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveRejoin()">Save</button>
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
                        <textarea class="form-control" id="modal_reason" placeholder="Enter reason"></textarea>
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

        function toggleNewEmpFields() {

            let checkbox = document.getElementById('new_emp_checkbox');
            let fields = document.getElementById('new_emp_fields');

            if (checkbox.checked) {
                fields.style.display = 'block';
            } else {
                fields.style.display = 'none';

            }
        }

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

        function openRejoinDetailsModal(emp_name, rejoin_type, prev_code, new_code, biometric_id, emp_code, remark) {
            $('#detail_emp_name').text(emp_name);
            if (rejoin_type == 1) {
                $('#detail_rejoin_type').html(
                    '<span class="badge bg-info text-dark">With New Employee Code</span>'
                );
                $('#detail_prev_code').text(prev_code);
                $('#detail_new_code').text(new_code);
            } else {
                $('#detail_rejoin_type').html(
                    '<span class="badge bg-secondary">With Same Employee Code</span>'
                );
                // same code case
                $('#detail_prev_code').text(emp_code);
                $('#detail_new_code').text(emp_code);
            }
            // biometric
            if (biometric_id != '') {
                $('#bio_div').show();
                $('#detail_biometric').text(biometric_id);
            } else {
                $('#bio_div').hide();
            }
            // remark
            $('#detail_remark').text(remark);
            // open modal
            $('#rejoinDetailsModal').modal('show');
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
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' +
                        submodule + '&emp_id=' + emp_id + '&pagename=' + pagename,
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

        function openRejoinModal(exit_id, emp_id, emp_name, emp_code) {
            $('#rejoin_exit_id').val(exit_id);
            $('#rejoin_emp_id').val(emp_id);
            $('#rejoin_date').val('<?= date('Y-m-d') ?>');
            $('#rejoin_remark').val('');
            $('#show_emp_name').text(emp_name);
            $('#show_emp_code').text(emp_code);
            $('#prevv_emp_code').val(emp_code);
            $('#rejoinModal').modal('show');
        }

        function saveRejoin() {

            let exit_id = $('#rejoin_exit_id').val();
            let rejoin_date = $('#rejoin_date').val();
            let rejoin_emp_id = $('#rejoin_emp_id').val();
            let remark = $('#rejoin_remark').val().trim();
            let new_emp_checkbox = $('#new_emp_checkbox').is(':checked') ? 1 : 0;
            let new_join_checkbox = $('#new_join_checkbox').is(':checked') ? 1 : 0;
            let new_emp_code = $('#new_emp_code').val().trim();
            let biometric_id = $('#biometric_id').val().trim();
            let show_emp_code = $('#prevv_emp_code').val().trim();

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
                    remark: remark,
                    new_emp_checkbox: new_emp_checkbox,
                    new_join_checkbox: new_join_checkbox,
                    new_emp_code: new_emp_code,
                    biometric_id: biometric_id,
                    show_emp_code: show_emp_code
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