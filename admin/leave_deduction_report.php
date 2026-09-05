<?php include("../adminsession.php");
$pagename = "leave_deduction_report.php";
$pagename2 = "designation_master.php";
$title = "Leave Deduction";
$tblname = "emp_monthly_leave";
$tblpkey = "month_leave_id";
$module = "Leave Deduction";
$submodule = "Leave Deduction Report";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = '';
if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    if ($emp_id != '') {
        $crit .= " and em.emp_id='$emp_id'";
    }
} else {
    $emp_id = '';
}
if (isset($_GET['month'])) {
    $month = (int)$obj->test_input($_GET['month']);
    if ($month != '') {
        $crit .= " and t.month='$month'";
    }
} else {
    $month = date('n');
}
if (isset($_GET['year'])) {
    $year = $obj->test_input($_GET['year']);
    if ($year != '') {
        $crit .= " and t.year='$year'";
    }
} else {
    $year = date('Y');
}
if (isset($_GET['leave_type'])) {
    $leave_type = $obj->test_input($_GET['leave_type']);
    if ($leave_type != '') {
        $crit .= " and t.leave_type='$leave_type'";
    }
} else {
    $leave_type = 'earning_ded';
}

 
$month_name = '';
if (!empty($month)) {
    $month_name = date('F', mktime(0, 0, 0, $month, 1));
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
                <?php include('inc/alert.php'); ?>
                <div class="row">
                    <?php if (!isset($_GET['submit'])) { ?>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?= $module ?>  </h5>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get" autocomplete="off">
                                    <div class="row">
                                        <div class="mb-3 col-12 col-lg-3">
                                            <label for="emp_id" class="form-label">Employee Name<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id"
                                                id="emp_id"  >
                                                <option value="">Select Employee</option>
                                                <?php

                                                        $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                        foreach ($res as $key) { ?>
                                                <option value="<?= $key['emp_id']; ?>">
                                                    <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?>
                                                    <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>


                                        <div class="col-lg-3 col-12">
                                            <label for="month" class="form-label">Month<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="month"
                                                id="month">
                                                <option value="">All</option>
                                                <?php
                                                        $months = [
                                                            1 => 'January',
                                                            2 => 'February',
                                                            3 => 'March',
                                                            4 => 'April',
                                                            5 => 'May',
                                                            6 => 'June',
                                                            7 => 'July',
                                                            8 => 'August',
                                                            9 => 'September',
                                                            10 => 'October',
                                                            11 => 'November',
                                                            12 => 'December'
                                                        ];
                                                        foreach ($months as $value => $name) {
                                                            echo "<option value=\"$value\">$name</option>";
                                                        }
                                                        ?>
                                            </select>
                                            <script>
                                            document.getElementById('month').value = '<?php echo $month ?>'
                                            </script>
                                        </div>

                                        <!-- Year -->
                                        <div class="col-lg-3 col-12">
                                            <label for="year" class="form-label">Year<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="year"
                                                id="year">
                                                <option value="">All</option>
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

                                        <div class="col-lg-3 col-12">
                                            <label for="leave_type" class="form-label">Type<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="leave_type"
                                                id="leave_type">
                                                <option value="earning_ded">Earning Leave</option>
                                                <option value="weekly_ded">C-Off</option>
                                            </select>
                                            <script>
                                            document.getElementById('leave_type').value = '<?php echo $leave_type ?>'
                                            </script>
                                        </div>

                                        <div class="col-12 col-lg-3 mt-4">
                                            <input type="submit" class="btn btn-sm btn-success" name="submit"
                                                value="Search" onClick="return checkinputmaster('')">
                                            <a href="<?php echo $pagename; ?>" type="button"
                                                class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php }  
                    if (isset($_GET['submit'])) { ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0">
                                                <?= $submodule; ?>

                                                <?php if (!empty($month_name) && !empty($year)) { ?>
                                                <span class="text-primary">
                                                    (<?= $month_name . " - " . $year; ?>)
                                                </span>
                                                <?php } ?>

                                                <a href="emp_leave_deduction.php"
                                                    class="float-end btn btn-primary btn-sm ms-2">
                                                    Deduct Leave
                                                </a>  <a href="leave_deduction_report.php"
                                                    class="float-end btn btn-primary btn-sm  ">
                                                    Search Again
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="buttons-datatables" class="display table table-sm table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>Sr No.</th>
                                                    <th>Emp Code</th>
                                                    <th>Emp Name</th>
                                                    <th>Department Name</th>
                                                    <th>Leave Type</th>
                                                    <th>Deducted Leave</th>
                                                    <th>Balance Leave</th>
                                                    <th>Remark</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $slno = 1; 
                                                    $earningUploadRows=$obj->earningUploadRows($sessionid,$month,$year);
                                                    $earningLeaveRows = $obj->earningLeaveRows($sessionid,$month,$year);
                                                    $usedEarnMap = [];
                                                    foreach($earningLeaveRows as $r){
                                                        $usedEarnMap[$r['emp_id']] = $r['used_leave'];
                                                    }

                                                    $earningUploadMap = [];
                                                    foreach($earningUploadRows as $r){
                                                        $earningUploadMap[$r['emp_id']] = $r['total_leave'];
                                                    }
    
                                                        $res = $obj->executequery("
                                                    SELECT 
                                                        t.*,
                                                        em.first_name,
                                                        em.department_id,
                                                        em.emp_code,
                                                        dm.department_name,
                                                        em.emp_code,
                                                        cu.fullname as created_name,
                                                        cu.username as created_username,
                                                        cu.mobile as created_mobile 
                                                    FROM $tblname t
                                                    LEFT JOIN user cu 
                                                        ON t.createdby = cu.userid 
                                                    LEFT JOIN employee_master em 
                                                        ON t.emp_id = em.emp_id 
                                                    LEFT JOIN department_master dm 
                                                        ON em.department_id = dm.department_id 
                                                   
                                                    WHERE t.unit_id = '$unitid' $crit

                                                    ORDER BY t.$tblpkey DESC
                                                ");
                                                        foreach ($res as $row) {  

                                                            $total_earning_leave =($earningUploadMap[$row['emp_id']] ?? 0) -($usedEarnMap[$row['emp_id']] ?? 0); 

                                                            $salary_count = $obj->getvalfield("salary_structure", "count(*)", "emp_id='$row[emp_id]' and month='$row[month]' and year='$row[year]'");
                                                        ?>
                                                <tr data-details="
                                                            <div style='background:#dafced; padding:4px;'>
                                                            <?php if (!empty($row['created_name'])): ?>
                                                            Added by (User: <?= $row['created_name'] ?>,
                                                            Username: <?= $row['created_username'] ?>,
                                                            Mobile: <?= $row['created_mobile'] ?>,
                                                            Date: <?= $row['createdate'] ?>,)<br>
                                                            <?php endif; ?> 
                                                            </div>
                                                        ">
                                                    <td class="details-control text-center" style="cursor:pointer;">
                                                        <?php echo $slno++; ?>
                                                        <i class="ri-add-circle-fill text-primary"></i>

                                                    </td>

                                                    <td><?php echo $row["emp_code"]; ?></td>
                                                    <td><?php echo $row["first_name"]; ?></td>
                                                    <td><?php echo $row["department_name"]; ?></td>
                                                  <td>
    <?= ($row["leave_type"] == 'earning_ded') ? 'Earning' : 'C Off'; ?>
</td>
                                                    <td><?php echo $row["total_leave"]; ?></td>
                                                    <td><?php echo $total_earning_leave; ?></td>
                                                    <td><?php echo $row["remark"]; ?></td>
                                                    <td>
                                                        <?php if ($salary_count == 0) { ?>
                                                        <ul class="list-inline hstack gap-2 mb-0"> 
                                                            <?php 
                                                            $chkdel = $obj->check_delBtn($pagename, $loginid);
                                                            if ($chkdel == 1) {
                                                            ?>
                                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                data-bs-trigger="hover" data-bs-placement="top"
                                                                title="Delete">
                                                                <a class="remove-item-btn" type="button"
                                                                    onclick="funDel('<?php echo $row[$tblpkey]; ?>');">
                                                                    <i
                                                                        class="ri-delete-bin-fill align-bottom text-danger"></i>
                                                                </a>
                                                            </li>
                                                            <?php
                                                            }
                                                            ?>
                                                        </ul>
                                                        <?php } else { ?>
                                                        <span class="badge bg-success">Salary Generated</span>
                                                        <?php } ?>
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
                    <?php } ?>
                    <!--end col-->
                </div>
                <!--end row-->
            </div>
            <!-- container-fluid -->
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

    });
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

    
    function funDel(id) {
        $('#deleteRecordModal').modal('show');
        tblname = '<?php echo $tblname; ?>';
        tblpkey = '<?php echo $tblpkey; ?>';

        pagename = '<?php echo $pagename; ?>';
        submodule = '<?php echo $submodule; ?>';

        $('#delete-record').click(function() {
            $.ajax({
                type: 'POST',
                url: 'ajax/delete_master.php',
                data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' +
                    submodule + '&pagename=' + pagename,
                dataType: 'html',
                success: function(data) {
                    // alert(data);
                   location.reload();
                }
            });
            $('#deleteRecordModal').modal('hide');
        });
    };
    </script>
</body>

</html>