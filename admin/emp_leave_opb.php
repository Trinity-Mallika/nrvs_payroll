<?php include("../adminsession.php");
$pagename = "emp_leave_opb.php";
$title = "Employee Leave Opening";
$module = "Employee Leave Opening";
$submodule = "Employee Leave Opening";
$btn_name = "Save";
$keyvalue = 0;
$tblname = "m_session";
$tblpkey = "sessionid";
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
}

if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and em.department_id='$department_id'";
    }
}

$session_name = $obj->getvalfield("m_session", "session_name", "sessionid='$sessionid'");

if (isset($_POST['save_all_leave'])) {
    $sessionid = $obj->test_input($_POST['sessionid']);
    $allRows = json_decode($_POST['allRows'], true);

    if (!empty($allRows)) {
        $insertRows = [];
        $monthlyLeaveRows = [];
        $empIds = [];
        foreach ($allRows as $row) {

            $month = date('n');
            $year  = date('Y');
            $emp_id = $row['emp_id'];
            $empIds[] = $emp_id; 
            $opening_leave = $row['opening_leave'];
            $coff = $row['coff']??0;
            $department_id = $obj->getvalfield(
                    "employee_master",
                    "department_id",
                    "emp_id='$emp_id'"
                );

            $insertRows[] = [
                "emp_id" => $emp_id, 
                "department_id" => $department_id, 
                "total_leave" => $opening_leave,
                "remining_leave" => $opening_leave,
                "leave_type" => 'earning',
                "month" => $month,
                "year" => $year,
                  'is_opb' =>'1',
                "unit_id" => $unitid,
                "sessionid" => $sessionid,
                "createdby" => $loginid,
                "ipaddress" => $ipaddress,
                "createdate" => $createdate
            ];


            if ($coff > 0) { 
                $monthlyLeaveRows[] = [
                    "emp_id"          => $emp_id,
                    "department_id"   => $department_id, 
                    "total_leave"     => $coff,
                    "remining_leave"  => $coff,
                    "month"           => $month,
                    "year"            => $year,
                    "leave_type"      => "weekly",
                      'is_opb' =>'1',
                    "salary_struc_id" => 0, 
                    "createdby"       => $loginid,
                    "ipaddress"       => $ipaddress,
                    "createdate"      => $createdate, 
                    "unit_id"         => $unitid,
                    "sessionid"       => $sessionid
                ];
            }
        }
      

        if (!empty($insertRows)) {
            $obj->bulk_delete('emp_monthly_leave', [
                'emp_id' => $empIds,
                'unit_id' => $unitid,
                'leave_type'=> 'earning',
                'is_opb' =>'1',
                'sessionid' => $sessionid
            ]);

            foreach (array_chunk($insertRows, 500) as $chunk) {
                $obj->bulk_insert('emp_monthly_leave', $chunk);
            }
        }

        if (!empty($monthlyLeaveRows)) {
            $obj->bulk_delete('emp_monthly_leave', [
                'emp_id'    => $empIds,
                'unit_id'   => $unitid,
                'sessionid' => $sessionid,
                'is_opb' =>'1',
                'leave_type'=> 'weekly'
            ]);

            foreach (array_chunk($monthlyLeaveRows, 500) as $chunk) {
                $obj->bulk_insert('emp_monthly_leave', $chunk);
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
                                            <h5 class="card-title mb-0"><?= $module ?></h5>
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

                                        <div class="mb-3 col-12 col-lg-3">
                                            <label for="department_id" class="form-label">Department Name<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="department_id" id="department_id">
                                                <option value="">Select</option>
                                                <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_id asc");
                                                    foreach ($res as $key) { ?>
                                                <option value="<?= $key['department_id']; ?>">
                                                    <?= $key['department_name']; ?> </option>
                                                <?php } ?>
                                            </select>
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
                            <div class="card-header">
                                <div class="row mt-2">
                                    <div class="col-md-2 col-3 text-end">
                                        Unit Name :
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <h6 class="text-dark"><?= $unit_name ?></h6>
                                    </div>

                                    <div class="col-md-2 col-3 text-end">
                                        Financial Year :
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <h6 class="text-dark"><?= $session_name ?></h6>
                                    </div>

                                    <div class="col-12 text-center">
                                        <small class="text-danger fw-bold">
                                            Note: Leave balances shown below are based on the uploaded leave balances
                                            for the selected financial year.
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="auto-scroll-wrapper">
                                    <div class="table-responsive">

                                        <table id="buttons-datatables" class="display table table-sm table-bordered"
                                            style="width:100%">
                                            <thead>

                                                <tr class="table-primary">
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>

                                                    <th></th>


                                                    

                                                    <th>
                                                        <input type="text"
                                                            class="form-control form-control-sm bulk-fill w-50"
                                                            data-target="opening_leave_input" placeholder="0"
                                                            onkeypress="numberOnly(event);" readonly>
                                                    </th>
                                                      <th>
                                                        <input type="text"
                                                            class="form-control form-control-sm bulk-fill w-50"
                                                            data-target="coff_input" placeholder="0"
                                                            onkeypress="numberOnly(event);" readonly>
                                                    </th>  
                                                    <th></th>
                                                </tr>
                                                <tr class="table-primary">
                                                    <th>Sr. No.</th>
                                                    <th>Code</th>
                                                    <th>Employee Name</th>
                                                    <th>Department</th>
                                                    <!-- <th>Earn Leave (April - 2026)</th> -->
                                                    
                                                    <th>Leave</th>
                                                      <th>C-Off</th> 
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $slno = 1; 
                                                    $sql = "SELECT 
                                                        em.emp_id,
                                                        em.is_esic,
                                                        em.allow_weekly_off,
                                                        em.emp_code,
                                                        em.first_name,
                                                        em.last_name,
                                                        em.date_of_joining,
                                                        em.department_id,
                                                        dm.department_name,

                                                        COALESCE(SUM(CASE 
                                                            WHEN ela.leave_type = 'earning' THEN ela.total_leave 
                                                            ELSE 0 
                                                        END),0) AS opening_leave,

                                                        COALESCE(SUM(CASE 
                                                            WHEN ela.leave_type = 'weekly' THEN ela.total_leave 
                                                            ELSE 0 
                                                        END),0) AS coff_leave

                                                    FROM employee_master em

                                                    LEFT JOIN department_master dm 
                                                        ON em.department_id = dm.department_id

                                                    LEFT JOIN emp_monthly_leave ela 
                                                        ON ela.emp_id = em.emp_id
                                                        AND ela.unit_id = '$unitid'
                                                        AND ela.is_opb = '1'
                                                        AND ela.sessionid = '$sessionid'

                                                    WHERE em.unit_id = '$unitid'
                                                    $crit
                                                    AND (
                                                        em.resign_status != '1'
                                                        OR (
                                                            em.resign_status = '1'
                                                            AND em.last_working_date >= CURDATE()
                                                        )
                                                    )

                                                    GROUP BY em.emp_id
                                                    ORDER BY em.emp_code ASC";
                                                    $res = $obj->executequery($sql);
                                                    // $res=$obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' $crit AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY emp_code ASC");        
                                                    foreach ($res as $row) {
                                                        $opening_leave = $row['opening_leave']; 
                                                        $coff = $row['coff_leave']; 
                                                        $total = $coff +$opening_leave;

                                                    ?>
                                                <tr>
                                                    <td><?= $slno++ ?></td>
                                                    <td><?= $row['emp_code'] ?></td>
                                                    <td><?php
                                                                echo $row['first_name'];
                                                                $join_date = new DateTime($row['date_of_joining']);
                                                                $today = new DateTime();
                                                                $diff = $join_date->diff($today);
                                                                echo "<br>";
                                                                echo $diff->y . " years, " .
                                                                    $diff->m . " months, " .
                                                                    $diff->d . " days";
                                                                ?>
                                                    </td>
                                                    <td><?= $row['department_name'] ?></td>

                                                    <td>
                                                        <input type="text" name="opening_leave[]"
                                                            id="opening_leave<?= $row['emp_id'] ?>"
                                                            class="form-control form-control-sm leave-input opening_leave_input w-50"
                                                            data-type="opening_leave" data-emp="<?= $row['emp_id'] ?>"
                                                            value="<?= $opening_leave ?>"
                                                            onkeypress="numberOnly(event);" readonly>
                                                    </td>

                                                    <td>
                                                        <input type="text" name="coff[]"
                                                            id="coff<?= $row['emp_id'] ?>"
                                                            class="form-control form-control-sm leave-input coff_input w-50"
                                                            data-type="coff" data-emp="<?= $row['emp_id'] ?>"
                                                            value="<?= $coff ?>"
                                                            onkeypress="numberOnly(event);" readonly >
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm total_input w-50"
                                                            readonly onkeypress="numberOnly(event);"
                                                            value="<?= $total ?>" readonly>
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

            //let earn_leave = parseFloat(row.find('.earn_leave_input').val()) || 0;
             let coff = parseFloat(row.find('.coff_input').val()) || 0;
            let opening_leave = parseFloat(row.find('.opening_leave_input').val()) || 0;
           

            let total =coff+ opening_leave;

            row.find('.total_input').val(total);

        });
    });

    function save_leave() {

        //let sessionid = $('#sessionid').val();
        let sessionid = <?= $sessionid ?>;

        if (sessionid == '') {
            alert('Please Select Financial Year');
            return false;
        }

        let allRows = [];

        $('tbody tr').each(function() {

            let emp_id = $(this).find('.opening_leave_input').data('emp');

            //let earn_leave = $('#earn_leave' + emp_id).val() || 0;

             let coff = $('#coff' + emp_id).val() || 0;

            let opening_leave = $('#opening_leave' + emp_id).val() || 0;

            let total =
                parseFloat(opening_leave)+ parseFloat(coff);

            allRows.push({
                emp_id: emp_id,
                 coff: coff,
                opening_leave: opening_leave,
                total_input: total
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
console.log('response',response);
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