<?php include("../adminsession.php");
$pagename = "unit_transfer.php";
$title = "Unit Transfer Master";
$tblname = "on_duty_master";
$tblpkey = "on_duty_id";
$module = "Unit Transfer Master";
$submodule = "Unit Transfer Master List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$emp_id = (isset($_GET['emp_id'])) ? $obj->test_input($_GET['emp_id']) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
if ($emp_id > 0) {
    $emp_data = $obj->select_record("employee_master", ['emp_id' => $emp_id]);
    $first_name = $emp_data['first_name'] ?? '';
    $last_name = $emp_data['last_name'] ?? '';
    $emp_code = $emp_data['emp_code'] ?? '';
    $date_of_joining = $emp_data['date_of_joining'] ?? '';
    $basic_salary = $emp_data['basic_salary'] ?? '';
    $shift_hrs = $emp_data['shift_id'] ?? '';
} else {
    $first_name = '';
    $last_name = '';
    $emp_code = '';
    $date_of_joining = '';
    $basic_salary = '';
    $shift_hrs = '';
}
 

if (isset($_POST['unit_iddd'])) {
    $unit_id = $obj->test_input($_POST['unit_iddd']);
    // Department
    $dept_html = '<option value="">Select Department</option>';
    $dept = $obj->executequery("SELECT * FROM department_master WHERE unit_id='$unit_id' order by department_name asc");
    foreach ($dept as $d) {
        $dept_html .= '<option value="' . $d['department_id'] . '">' . $d['department_name'] . '</option>';
    }
    $max_code = $obj->getvalfield(
        "employee_master",
        "MAX(emp_code)",
        "1=1"
    );
    $new_emp_code = ($max_code != "") ? $max_code + 1 : 1;

    echo json_encode([
        "department" => $dept_html,
        "new_emp_code" => $new_emp_code,
        "new_bio_id" => $new_emp_code,
        // "designation" => $des_html,
    ]);
    die;
}

if (isset($_POST['transfer_emp_id'])) {
    $emp_id = $obj->test_input($_POST['transfer_emp_id']);
    $unit_id = $obj->test_input($_POST['unit_id']);
    $department_id = $obj->test_input($_POST['department_id']);
    $designation_id = $obj->test_input($_POST['designation_id']);
    $new_join_date = $obj->test_input($_POST['last_work_date']);
    $last_work_date = date('Y-m-d', strtotime($new_join_date . ' -1 day'));
    $basic_salary = $obj->test_input($_POST['basic_salary']);
    $shift_hrs = $obj->test_input($_POST['shift_hrs']);
    $new_emp_code = $obj->test_input($_POST['new_emp_code']);
    $new_bio_id = $obj->test_input($_POST['new_bio_id']);
    $modal_emp_code = $obj->test_input($_POST['modal_emp_code']);
    $prev_month = date('n', strtotime($last_work_date));
    $prev_year = date('Y', strtotime($last_work_date));


    $if_exist = $obj->getvalfield("employee_master", "count(*)", "emp_code='$new_emp_code'");
    if ($if_exist > 0) {
        $new_emp_code =  $obj->getcode("employee_master", "emp_code",  "1=1");
        $new_bio_id = $obj->getcode("employee_master", "emp_code",  "1=1");
    }

    $salary_count = $obj->getvalfield("salary_structure","count(*)","emp_id='$emp_id' AND month='$prev_month' AND year='$prev_year' and unit_id='$unitid'");
    
    if ($salary_count == 0) {
        echo 'salary_pending';
        die;
    }

    $form_data = array(
        'emp_id' => $emp_id,
        'joining_date' => $new_join_date,
        'unit_id' => $unit_id,
        'prev_emp_code' => $modal_emp_code,
        'department_id' => $department_id,
        'designation_id' => $designation_id,
        'basic_salary' => $basic_salary,
        'shift_hrs' => $shift_hrs,
        "createdate" => $createdate,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid
    );
    if ($emp_id != '') {
        $last_record_id = $obj->getvalfield("emp_branch_transfer", "branch_transfer_id", "emp_id='$emp_id' order by branch_transfer_id desc limit 1");

        $obj->update_record("employee_master", array("emp_id" => $emp_id), array("unit_id" => $unit_id, "department_id" => $department_id, "designation_id" => $designation_id, "emp_code" => $new_emp_code, "biomatric_id" => $new_bio_id,'date_of_joining'=>$new_join_date));

         $obj->update_record("emp_bank_details ", array("emp_id" => $emp_id), array("unit_id" => $unit_id));
         $obj->update_record("emp_family_details ", array("emp_id" => $emp_id), array("unit_id" => $unit_id));
         $obj->update_record("emp_education ", array("emp_id" => $emp_id), array("unit_id" => $unit_id));
         $obj->update_record("emp_document ", array("emp_id" => $emp_id), array("unit_id" => $unit_id));
         $obj->update_record("emp_language ", array("emp_id" => $emp_id), array("unit_id" => $unit_id));

        $obj->update_record("emp_branch_transfer", array("branch_transfer_id" => $last_record_id), array("last_work_date" => $last_work_date));

        $lastid =  $obj->insert_record_lastid("emp_branch_transfer", $form_data);
        $form_data1 = array(
            "primary_id" => $lastid,
            "flag" => "Employee Branch Branch",
            "activity_type" => 'Inserted',
            "createdby" => $loginid,
            "ipaddress" => $ipaddress,
            "unit_id" => $unitid,
            "created_date" => $createdate,
            "created_time" => date("H:i:s"),
            "sessionid" => $sessionid
        );
        $logactivity = $obj->insert_record("logactivity_master", $form_data1);
        echo 'success';
    } else {
        echo 'error';
    }
    die;
}

if (isset($_POST['department_iddd'])) {
    $department_id = $_REQUEST['department_iddd'];
    //  $depart_unit = $obj->getvalfield("department_master", "unit_id", "department_id='$department_id'");
    $options = "<option value=''>Please Select</option>";
    $selected = "";
    if ($department_id != "" || $department_id > 0) {
        $res = $obj->executequery("Select * from designation_master where department_id='$department_id' order by designation asc");
        foreach ($res as $row) {
            $options .= "<option value='" . $row['designation_id'] . "' >" . $row['designation'] . "  </option>";
        }
    }

    echo $options;
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
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?> </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 mb-2">
                                            <label for="emp_id" class="form-label">Employee Name<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id" id="emp_id" onchange="get_url(this.value);">
                                                <option value="0">Select Employee</option>
                                                <?php
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?> <?= ucfirst($key['last_name'] ?? ''); ?> </option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('emp_id').value = '<?= $emp_id; ?>';
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (isset($_GET['emp_id'])) { ?>
                            <div class="col-lg-12">
                                <div class="card" id="customerList">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12 mb-3">
                                                <div class="table-responsive">
                                                    <div class="row mb-2">
                                                        <?php $chkedit = $obj->check_editBtn($pagename, $loginid);
                                                        if ($chkedit == 1) {  ?>
                                                            <div class="col-lg-12 text-end">
                                                                <button type="button" class="btn btn-sm btn-success" onclick="openTransfer();">
                                                                    <i class="ri-exchange-line"></i> Transfer
                                                                </button>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <table class="display table table-sm table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr class="table-primary">
                                                                <th>Sr No.</th>
                                                                <th>Employee Name</th>
                                                                <th>Prev Emp Code</th>
                                                                <th>Date From - To</th>
                                                                <th>Unit Name</th>
                                                                <th>Department / Designation </th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $sn = 1;
                                                            $res = $obj->executequery("SELECT bt.* , um.unit_name,dpt.department_name,des.designation,  em.first_name , em.last_name , em.emp_code from emp_branch_transfer as bt left join unit_master um on bt.unit_id=um.unit_id left join department_master dpt on bt.department_id=dpt.department_id left join designation_master des on bt.designation_id=des.designation_id left join employee_master em on bt.emp_id=em.emp_id where bt.emp_id='$emp_id' order by branch_transfer_id desc");

                                                            foreach ($res as $key) {
                                                                $last_work_date = ($key['last_work_date'] && $key['last_work_date'] !== '0000-00-00')
                                                                    ? $obj->dateformatindia($key['last_work_date'])
                                                                    : 'Till End';
                                                            ?>
                                                                <tr>
                                                                    <td><?= $sn++ ?></td>
                                                                    <td><?= $first_name . " " . $last_name . " - (" . $emp_code . ")" ?> </td>
                                                                    <td><?= $key['prev_emp_code'] ?></td>
                                                                    <td>
                                                                        <?= $obj->dateformatindia($key['joining_date']) . " - " . $last_work_date; ?><br>
                                                                        <small>Shift Hrs : <?= $key['shift_hrs']; ?> </small>
                                                                    </td>
                                                                    <td><?= $key['unit_name']; ?> </td>
                                                                    <td><b><?= $key['department_name']; ?></b><br>
                                                                        <small><?= $key['designation']; ?> </small>
                                                                    </td>

                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>

                                            <div class="col-lg-12 text-center">
                                                <br>
                                                <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                                <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> " onClick="return checkinputmaster('application_date,emp_id,on_duty_type')">
                                                <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        <?php } ?>

                    </form>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    </div>
    <div class="modal fade" id="transferModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title">Employee Unit Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="transferForm">
                        <div class="row">
                            <!-- Employee -->
                            <div class="col-md-4 mb-3">
                                <label>Employee</label>
                                <input type="text" name="emp_name" class="form-control form-control-sm" value="<?= $first_name . " " . $last_name . " - (" . $emp_code . ")" ?> " readonly>
                                <input type="hidden" id="modal_emp_id" value="<?= $emp_id ?>" class="form-control form-control-sm">
                                <input type="hidden" id="modal_emp_code" value="<?= $emp_code ?>" class="form-control form-control-sm">
                            </div>

                              <div class="col-md-4 mb-3">
                                <label>Joining Date</label>
                                <input type="date" name="emp_joining_date" class="form-control form-control-sm" 
                                value="<?= $date_of_joining?>" readonly>
                               
                            </div>
                            <!-- Department -->
                            <div class="col-md-4 mb-3">
                                <label>Transfer To Unit <span
                                        class="text-danger fw-bold">*</span> </label>
                                <select id="modal_unit_id" class="form-select form-select-sm chosen-select" onchange="getUnitWiseData(this.value)">
                                    <option value="">Select Unit</option>
                                    <?php
                                    $units = $obj->executequery("SELECT * FROM unit_master where unit_id!='$unitid' order by unit_id desc");
                                    foreach ($units as $unit) {
                                    ?>
                                        <option value="<?= $unit['unit_id'] ?>"><?= $unit['unit_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Department <span
                                        class="text-danger fw-bold">*</span> </label>
                                <select id="modal_department_id"
                                    class="form-select form-select-sm chosen-select" onchange="get_designation(this.value)">
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Designation <span
                                        class="text-danger fw-bold">*</span> </label>
                                <select id="modal_designation_id"
                                    class="form-select form-select-sm chosen-select">
                                    <option value="">Select Designation</option>
                                </select>
                            </div>
                            <!-- Last Working Date -->

                            <div class="col-md-4 mb-3">
                                <label>New Employee Code<span
                                        class="text-danger fw-bold">*</span> </label>
                                <input type="text" id="modal_new_emp_code" class="form-control form-control-sm" readonly placeholder="New Employee Code">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>New Biomatric ID<span
                                        class="text-danger fw-bold">*</span> </label>
                                <input type="text" id="modal_new_bio_id" class="form-control form-control-sm" readonly placeholder="New Biomatric ID">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Transfer Date<span
                                        class="text-danger fw-bold">*</span> </label>
                                <input type="date" id="modal_last_work_date" class="form-control form-control-sm" value="<?= date('Y-m-d'); ?>" max="<?= date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="saveTransfer()">Save Transfer</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
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

        function openTransfer() {
            const modal = $('#transferModal');
            modal.modal('show');
            modal.find('select').select2({
                dropdownParent: modal,
                width: '100%'
            });
        }

        function get_url(emp_id) {
            location = '<?= $pagename ?>?emp_id=' + emp_id;
        }

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

        function saveTransfer() {

            const emp_id = $('#modal_emp_id').val();
            const unit_id = $('#modal_unit_id').val();
            const unit_name = $('#modal_unit_id option:selected').text();
            const department_id = $('#modal_department_id').val();
            const designation_id = $('#modal_designation_id').val();
            const last_work_date = $('#modal_last_work_date').val();
            const modal_new_emp_code = $('#modal_new_emp_code').val();
            const modal_new_bio_id = $('#modal_new_bio_id').val();
            const modal_emp_code = $('#modal_emp_code').val();
            const basic_salary = '<?= $basic_salary ?>';
            const shift_hrs = '<?= $shift_hrs ?>';

            if (emp_id == "" || unit_id == "" || department_id == "" || last_work_date == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required',
                    text: 'All Fields are Mandatory'
                });
                return;
            }
            Swal.fire({
                title: 'Are you sure?',
                html: `
        Are you sure you want to transfer this employee to 
        <b>Unit - ${unit_name}</b> 
        with Employee Code <b>${modal_new_emp_code}</b> 
        and Biometric ID <b>${modal_new_bio_id}</b>?
    `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Transfer',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#405189',
                cancelButtonColor: '#f06548'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: '',
                        type: 'POST',
                        data: {
                            transfer_emp_id: emp_id,
                            unit_id: unit_id,
                            department_id: department_id,
                            designation_id: designation_id,
                            last_work_date: last_work_date,
                            basic_salary: basic_salary,
                            new_emp_code: modal_new_emp_code,
                            new_bio_id: modal_new_bio_id,
                            modal_emp_code: modal_emp_code,
                            shift_hrs: shift_hrs
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Transferring...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            if (response.trim() === "salary_pending") {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Previous Salary Pending',
                                    html: `
                                        Please generate previous month salary 
                                        and clear salary process first 
                                        before transferring employee.
                                    `
                                });
                                return;
                            }
                            if (response.trim() === "success") {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Transfer Completed',
                                    text: 'Employee transferred successfully.',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });

                            } else {
                                Swal.fire('Error', response, 'error');
                            }
                        }
                    });

                }

            });
        }

        function getUnitWiseData(unit_id) {

            if (unit_id == '') return;
            $.ajax({
                type: "POST",
                url: "",
                data: {
                    unit_iddd: unit_id
                },
                dataType: "json",
                success: function(response) {
                    $("#modal_department_id").html(response.department);
                    $("#modal_new_emp_code").val(response.new_emp_code);
                    $("#modal_new_bio_id").val(response.new_bio_id);
                    // $("#modal_designation_id").html(response.designation);
                    $(".chosen-select").trigger("chosen:updated");

                }
            });

        }

        function get_designation(department_id, designation_id = 0) {
            console.log(department_id);
            $.ajax({
                type: "POST",
                url: '',
                data: {
                    department_iddd: department_id,
                },
                success: function(data) {

                    $('#modal_designation_id').html(data).trigger("change.select2");
                }
            });
        }

        $('#modal_last_work_date').on('change', function() {
            let selectedDate = $(this).val();
            let today = new Date().toISOString().split('T')[0];

            if (selectedDate > today) {
                Swal.fire("Invalid Date", "Future date not allowed", "warning");
                $(this).val(today);
            }
        });
    </script>
</body>

</html>