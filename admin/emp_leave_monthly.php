<?php include("../adminsession.php");
$pagename = "emp_leave_monthly.php";
$title = "Employee Leave Monthly";
$module = "Employee Leave Monthly";
$submodule = "Employee Leave Monthly";
$btn_name = "Save";
$keyvalue = 0;
$tblname = "emp_monthly_leave";
$tblpkey = "month_leave_id";
$unit_name = $obj->getvalfield("unit_master", "unit_name", "unit_id='$unitid'");
$crit = '';

if (isset($_GET['sessionid'])) {
    $sessionid = $obj->test_input($_GET['sessionid']);
}
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
} else {
    $month = date('n');
}
if (isset($_GET['year'])) {
    $year = $obj->test_input($_GET['year']);
} else {
    $year = date('Y');
}

if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and em.department_id='$department_id'";
    }
} else {
    $department_id = '';
}

$session_name = $obj->getvalfield("m_session", "session_name", "sessionid='$sessionid'");

if (isset($_POST['save_all_leave'])) {

    $sessionid = $obj->test_input($_POST['sessionid']);
    $allRows = json_decode($_POST['allRows'], true);

    if (!empty($allRows)) {
        $insertRows = [];
        $processedEmpIds = [];
        foreach ($allRows as $row) {
            $emp_id = $row['emp_id'];
            $department_id = $row['department_id'];
            $total_leave = $row['total_leave'];
            $processedEmpIds[$emp_id] = true;

            $insertRows[] = [
                "emp_id" => $emp_id,
                "department_id" => $department_id,
                "total_leave" => $total_leave,
                "remining_leave" => $total_leave,
                "leave_type" => 'eoff',
                "month" => $month,
                "year" => $year,
                "unit_id" => $unitid,
                "sessionid" => $sessionid,
                "createdby" => $loginid,
                "ipaddress" => $ipaddress,
                "createdate" => $createdate
            ];
        }
        if (!empty($processedEmpIds)) {
            $empIdStr = implode(',', array_keys($processedEmpIds));
            $obj->bulk_delete($tblname, [
                'emp_id'  => array_keys($processedEmpIds),
                'month'   => $month,
                'year'    => $year,
                'unit_id' => $unitid,
                'leave_type' => 'eoff',
            ]);
        }
        if (!empty($insertRows)) {
            foreach (array_chunk($insertRows, 500) as $chunk) {
                $obj->bulk_insert($tblname, $chunk);
            }
        }
    }

    echo json_encode([
        'status' => 'success'
    ]);

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
    <link rel="stylesheet" href="assets/css/toogle.css">
</head>
<style>
.cls-read {
    pointer-events: none;
    background-color: #f5f5f5;
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
                <!-- end page title -->
                <?php //include('inc/alert.php'); 
                ?>
                <div class="row">
                    <?php if (!isset($_GET['submit'])) { ?>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?= $module ?>
                                                <a href="emp_leave_deduction.php"
                                                    class="float-end btn btn-primary btn-sm ms-2">Leave Deduction
                                                    Entry</a>
                                                <a href="emp_coff_deduction.php"
                                                    class="float-end btn btn-primary btn-sm ms-2">C-off Deduction</a>

                                            </h5>
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
                                                id="emp_id" onchange="get_department(this.value)">
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

                                        <div class="mb-3 col-12 col-lg-3">
                                            <label for="department_id" class="form-label">Department Name<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="department_id" id="department_id">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_id asc");
                                                    foreach ($res as $key) { ?>
                                                <option value="<?= $key['department_id']; ?>">
                                                    <?= $key['department_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-3 col-12">
                                            <label for="month" class="form-label">Month<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select chosen-select" name="month" id="month">
                                                <option value="">Select</option>
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
                                                    class="text-danger fw-bold">*</span></label>
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

                                        <div class="mb-3 col-12 col-lg-3">
                                            <label for="sessionid" class="form-label">Leave Finyear<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="sessionid"
                                                id="sessionid1">
                                                <option value="">Select</option>
                                                <?php $res = $obj->executequery("Select * from m_session  order by sessionid asc");
                                                    foreach ($res as $key) { ?>
                                                <option value="<?= $key['sessionid']; ?>">
                                                    <?= $key['fromdate']; ?>-<?= $key['todate']; ?>
                                                    (<?= $key['session_name'] ?>) </option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById("sessionid1").value = '<?= $sessionid ?>';
                                            </script>
                                        </div>


                                        <div class="col-12 col-lg-3 mt-4">
                                            <input type="submit" class="btn btn-sm btn-success" name="submit"
                                                value="Search" onClick="return checkinputmaster('sessionid1')">
                                            <a href="<?php echo $pagename; ?>" type="button"
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
                        <div class="card">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?= $module ?> <a href="<?= $pagename ?>"
                                                    class="float-end btn btn-primary btn-sm">Search Again</a></h5>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card-header ">
                                <div class="row mt-2">

                                    <div class="col-md-2 col-3 ">
                                        Unit Name
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <h6 class="text-dark">: <?= $unit_name ?> </h6>
                                    </div>
                                    <div class="col-md-2 col-3 ">
                                        Financial Year
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <h6 class="text-dark">: <?= $session_name ?></h6>
                                    </div>
                                    <div class="col-md-2 col-3 ">
                                        Month - Year
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <h6 class="text-dark">
                                            : <?= date("F", mktime(0, 0, 0, $month, 1)) . ' - ' . $year ?>
                                        </h6>
                                    </div>
                                    <!-- <div class="col-md-2 col-3 ">
                                        <button class="btn btn-primary btn-sm">
                                            Show Details
                                        </button>
                                    </div> -->

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="auto-scroll-wrapper">
                                    <div class="table-responsive">
                                        <table id="buttons-datatables" class="display table table-sm table-bordered"
                                            style="width:100%">
                                            <thead>

                                                <tr class="table-primary">
                                                    <th>Sr. No.</th>
                                                    <th>Code</th>
                                                    <th>Employee Name</th>
                                                    <th>Department</th>
                                                    <th>Earn Leave Balance</th>
                                                    <th>C OFF Balance</th>
                                                    <th>Extra Off</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="table-primary">
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>
                                                        <input type="text"
                                                            class="form-control form-control-sm bulk-fill  w-50"
                                                            data-target="eoff_input" placeholder="0"
                                                            onkeypress="numberOnly(event);">
                                                    </th>
                                                </tr>
                                                <?php
                                                    $current_month = (int)date("m");
                                                    $current_year  = (int)date("Y");
                                                    $isCurrentMonthYear = ($month == $current_month && $year == $current_year);
                                                    $slno = 1;
                                                    $sql = "SELECT
                                                        em.emp_id,
                                                        em.emp_code,
                                                        em.first_name,
                                                        em.last_name,
                                                        em.date_of_joining,
                                                        em.department_id,
                                                        dm.department_name,
                                                        ela.month_leave_id,
                                                        COALESCE(ela.total_leave,0) AS total_leave

                                                    FROM employee_master em

                                                    LEFT JOIN department_master dm
                                                        ON em.department_id = dm.department_id

                                                    LEFT JOIN (
                                                        SELECT
                                                            emp_id,
                                                            MAX(month_leave_id) AS month_leave_id,
                                                            SUM(total_leave) AS total_leave
                                                        FROM emp_monthly_leave
                                                        WHERE month='$month'
                                                        AND year='$year'
                                                        AND leave_type='eoff'
                                                        GROUP BY emp_id
                                                    ) ela ON ela.emp_id = em.emp_id

                                                    WHERE em.unit_id = '$unitid'
                                                    $crit
                                                    AND (
                                                        em.resign_status != '1'
                                                        OR (
                                                            em.resign_status = '1'
                                                            AND em.last_working_date >= CURDATE()
                                                        )
                                                    )

                                                    ORDER BY em.emp_code ASC
                                                    ";
                                                    $res = $obj->executequery($sql);
                                                    if (empty($sql)) {
                                                        $sql = [];
                                                    }
                                                    $empIds = array_column($res, 'emp_id');

                                                    if (empty($empIds)) {
                                                        $empIdsStr = '0';
                                                    } else {
                                                        $empIdsStr = implode(',', $empIds);
                                                    }


                                                    $coffUploadRows = $obj->getEmpUploadedCoff(
                                                        $empIdsStr,
                                                        $sessionid,
                                                        $month,
                                                        $year
                                                    );

                                                    $coffUsedRows = $obj->getEmpUsedCoff(
                                                        $empIdsStr,
                                                        $sessionid,
                                                        $month,
                                                        $year
                                                    );

                                                    $usedCoffMap = [];

                                                    foreach ($coffUsedRows as $r) {

                                                        $usedCoffMap[$r['emp_id']] = $r['used_coff'];
                                                    }


                                                    $coffUploadMap = [];
                                                    foreach ($coffUploadRows as $r) {
                                                        $coffUploadMap[$r['emp_id']] = $r['total_leave'];
                                                    }

                                                    $earningLeaveRows = $obj->earningLeaveRows($sessionid, $month, $year);
                                                    // $earningLeaveRows = $obj->executequery("
                                                    //     SELECT 
                                                    //         emp_id,
                                                    //         SUM(
                                                    //             CASE
                                                    //                 WHEN attendance_status IN ('Earning Leave', 'Leave') THEN 1
                                                    //                 WHEN attendance_status IN ('Half Earning Leave', 'Half Leave') THEN 0.5
                                                    //                 ELSE 0
                                                    //             END
                                                    //         ) used_leave

                                                    //     FROM attendance_entry

                                                    //     WHERE sessionid='$sessionid'

                                                    //     AND (
                                                    //         year < '$year'
                                                    //         OR (year='$year' AND month <= '$month')
                                                    //     )

                                                    //     GROUP BY emp_id

                                                    // ");

                                                    $usedEarnMap = [];

                                                    foreach ($earningLeaveRows as $r) {

                                                        $usedEarnMap[$r['emp_id']] = $r['used_leave'];
                                                    }
                                                    $earningUploadRows = $obj->earningUploadRows($sessionid, $month, $year);
                                                    //     $earningUploadRows = $obj->executequery("
                                                    //     SELECT 
                                                    //         emp_id,

                                                    //         SUM(total_leave) total_leave

                                                    //     FROM emp_monthly_leave

                                                    //     WHERE leave_type='earning'
                                                    //     AND sessionid='$sessionid'

                                                    //     AND (
                                                    //         year < '$year'
                                                    //         OR (year='$year' AND month < '$month')
                                                    //     )
                                                    //     GROUP BY emp_id
                                                    // ");
                                                    $earningUploadMap = [];
                                                    foreach ($earningUploadRows as $r) {

                                                        $earningUploadMap[$r['emp_id']] = $r['total_leave'];
                                                    }

                                                    foreach ($res as $row) {
                                                        $total_leave = $row['total_leave'];
                                                        $prev_coff =  ($coffUploadMap[$row['emp_id']] ?? 0) -  ($usedCoffMap[$row['emp_id']] ?? 0);
                                                        $total_earning_leave = ($earningUploadMap[$row['emp_id']] ?? 0) - ($usedEarnMap[$row['emp_id']] ?? 0);


                                                    ?>
                                                <tr>
                                                    <td><?= $slno++ ?></td>
                                                    <td><?= $row['emp_code'] ?> </td>
                                                    <td><?php
                                                                echo $row['first_name'];
                                                                $join_date = new DateTime($row['date_of_joining']);
                                                                $today = new DateTime();
                                                                $diff = $join_date->diff($today);
                                                                echo "<br> &nbsp; (";
                                                                echo $diff->y . " years, " .
                                                                    $diff->m . " months, " .
                                                                    $diff->d . " days )";
                                                                ?>
                                                    </td>
                                                    <td><?= $row['department_name'] ?></td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm  w-50"
                                                            value="<?= $total_earning_leave ?>" readonly>
                                                        <span
                                                            class="d-none export-value"><?= $total_earning_leave ?></span>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm  w-50"
                                                            value="<?= $prev_coff ?>">
                                                        <span class="d-none export-value"><?= $prev_coff ?></span>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" id="month_leave_id"
                                                            value="<?= $row['month_leave_id'] ?>">
                                                        <input type="text" name="total_leave[]"
                                                            id="total_leave<?= $row['emp_id'] ?>"
                                                            class="form-control form-control-sm leave-input eoff_input w-50"
                                                            data-type="total_leave" data-emp="<?= $row['emp_id'] ?>"
                                                            data-depart="<?= $row['department_id'] ?>"
                                                            value="<?= $total_leave ?>" onkeypress="numberOnly(event);"
                                                            <?= !$isCurrentMonthYear ? '' : '' ?>>
                                                        <span class="d-none export-value"><?= $total_leave ?></span>
                                                    </td>

                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                                <div class="text-end mt-3">
                                    <button type="button" class="btn btn-success btn-sm" id="save_leave_btn"
                                        onclick="save_leave();">
                                        Save Leave Opening
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- Third col End -->
                </div>

                <!--end row-->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="leaveDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Employee Leave Details</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div id="leaveDetailsData">
                        <div class="text-center p-3">
                            Loading...
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <script>
    $('.leave-input').on('keyup change', function() {
        var value = $(this).val();
        $(this).closest('td').find('.export-value').text(value);
    });

    $(document).ready(function() {
        $('#example').DataTable();
        $(".chosen-select").select2({
            width: '100%',
            search_contains: true
        });

    });

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
    $(document).ready(function() {

        // bulk fill from header
        $('.bulk-fill').on('keyup change', function() {

            let value = $(this).val();
            let target = $(this).data('target');

            $('.' + target).val(value).trigger('keyup');

        });

        $(document).on('keyup change', '.leave-input', function() {
            let row = $(this).closest('tr');
            let eoff = parseFloat(row.find('.eoff_input').val()) || 0;

        });
    });

    function viewLeaveDetails(emp_id, month, year) {
        $.ajax({
            type: "POST",
            url: "get_emp_leave_balance_details.php",
            data: {
                emp_id: emp_id,
                month: month,
                year: year
            },

            success: function(data) {

                $("#leaveDetailsData").html(data);

                $("#leaveDetailsModal").modal('show');
            }
        });

    }

    function save_leave() {

        //let sessionid = $('#sessionid').val();
        let sessionid = <?= $sessionid ?>;

        if (sessionid == '') {
            alert('Please Select Financial Year');
            return false;
        }

        let allRows = [];

        $('tbody tr').each(function() {

            let emp_id = $(this).find('.eoff_input').data('emp');
            let department_id = $(this).find('.eoff_input').data('depart');
            let total_leave = $('#total_leave' + emp_id).val() || 0;
            // alert(emp_id);
            allRows.push({
                emp_id: emp_id,
                total_leave: total_leave,
                department_id: department_id,
            });

        });

        $.ajax({
            url: '',
            type: 'POST',
            data: {
                save_all_leave: 1,
                sessionid: sessionid,
                allRows: JSON.stringify(allRows)
            },
            beforeSend: function() {

                $('#save_leave_btn').html('Saving...');
                $('#save_leave_btn').prop('disabled', true);

            },
            success: function(response) {
                console.log('response', response);

                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Leave Opening Saved Successfully',
                    timer: 2000,
                    showConfirmButton: false
                });
                $('#save_leave_btn').html('Save Leave Opening');
                $('#save_leave_btn').prop('disabled', false);
            }
        });
    }

    function get_department(emp_id) {
        $.ajax({
            type: "POST",
            url: 'get_depart_data.php',
            data: {
                emp_idd: emp_id,
            },
            success: function(data) {
                $('#department_id').html(data).trigger("change.select2");
            }
        });

    }
    </script>

</body>

</html>