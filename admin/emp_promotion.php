<?php include("../adminsession.php");
$pagename = "emp_promotion.php";
$title = "Employee Promotion";
$tblname = "emp_promotion";
$tblpkey = "emp_promotion_id";
$module = "Employee Promotion";
$submodule = "Employee Promotion List";
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


 
if (isset($_POST['promote_emp_id'])) {
    $emp_id = $obj->test_input($_POST['promote_emp_id']);
    $department_id = $obj->test_input($_POST['department_id']);
    $designation_id = $obj->test_input($_POST['designation_id']);
    $promotion_date = $obj->test_input($_POST['promotion_date']);
    $basic_salary = $obj->test_input($_POST['new_salary']);
    $modal_remark = $obj->test_input($_POST['modal_remark']);

    $form_data = array(
        'emp_id' => $emp_id,
        'unit_id' => $unitid,
        'remark' => $modal_remark,
        'department_id' => $department_id,
        'designation_id' => $designation_id,
        'basic_salary' => $basic_salary,
        'promotion_date' => $promotion_date,
        'type' => 'promotion',
        "createdate" => $createdate,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid
    );
    if ($emp_id != '') {
       // $obj->update_record("employee_master", array("emp_id" => $emp_id,"unit_id"=> $unitid), array("department_id" => $department_id, "designation_id" => $designation_id, "basic_salary" => $basic_salary));

        $lastid =  $obj->insert_record_lastid("emp_promotion", $form_data);
        $form_data1 = array(
            "primary_id" => $lastid,
            "flag" => "Employee Promotion",
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
                                                <h5 class="card-title mb-0"> <?= $module; ?> <a href="emp_promotion_list.php"
                                                    class="float-end btn btn-primary btn-sm ms-2">Pomotion Request List</a></h5>
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
                                                       
                                                            <div class="col-lg-12 text-end">
                                                                <button type="button" class="btn btn-sm btn-success" onclick="openTransfer();">
                                                                    <i class="ri-exchange-line"></i> Click to Promote
                                                                </button>
                                                            </div>
                                                       
                                                    </div>
                                                    <table class="display table table-sm table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr class="table-primary">
                                                                <th>Sr No.</th>
                                                                <th>Employee Name</th>
                                                                <th>Work Start Date</th>
                                                                <th>Unit Name</th>
                                                                <th>Department / Designation </th>
                                                                <th>Basic Salary</th> 
                                                                <th>Type</th>
                                                                <th>Remark</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $sn = 1;
                                                            $res = $obj->executequery("SELECT ep.* , um.unit_name,dpt.department_name,des.designation,  em.first_name , em.last_name , em.emp_code from emp_promotion as ep left join unit_master um on ep.unit_id=um.unit_id left join department_master dpt on ep.department_id=dpt.department_id left join designation_master des on ep.designation_id=des.designation_id left join employee_master em on ep.emp_id=em.emp_id where ep.emp_id='$emp_id' order by emp_promotion_id desc");

                                                            foreach ($res as $key) {
                                                              
                                                            ?>
                                                                <tr>
                                                                    <td><?= $sn++ ?></td>
                                                                    <td><?= $first_name . " " . $last_name . " - (" . $emp_code . ")" ?> </td>
                                                                   
                                                                    <td>
                                                                        <?= $obj->dateformatindia($key['promotion_date']) ?><br>
                                                                      
                                                                    </td>
                                                                    <td><?= $key['unit_name']; ?> </td>
                                                                    <td><b><?= $key['department_name']; ?></b><br>
                                                                        <small><?= $key['designation']; ?> </small>
                                                                    </td>   
                                                                     <td><?= $key['basic_salary']; ?> </td>
                                                                    
                                                                     <td><?=ucfirst($key['type']); ?> </td>
                                                                     <td><?= $key['remark']; ?> </td>
                                                                  <td>

                                                                   <span 
                                                                class="<?= $key['status'] == 1 ? 'badge bg-success' : ($key['status'] == 2 ? 'badge bg-danger' : 'badge bg-warning text-dark') ?>"
                                                                
                                                                <?= $key['status'] == 0 
                                                                    ? "style='cursor:pointer;' onclick=\"updatePromotionStatus('{$key['emp_promotion_id']}', '{$key['emp_id']}', '{$key['department_id']}', '{$key['designation_id']}', '{$key['basic_salary']}', '{$key['status']}')\"" 
                                                                    : "style='cursor:not-allowed;'" 
                                                                ?>
                                                            >
                                                                <?= $key['status'] == 1 ? 'Approved' : ($key['status'] == 2 ? 'Rejected' : 'Pending') ?>
                                                            </span>
                                                                        
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
                    <h5 class="modal-title">Employee Promotion</h5>
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
                                <label>New Department <span
                                        class="text-danger fw-bold">*</span> </label>
                                <select id="modal_department_id"
                                    class="form-select form-select-sm chosen-select" onchange="get_designation(this.value)">
                                    <option value="">Select Department</option>
                                      <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_name asc");
                                        foreach ($res as $key) { ?>
                                        <option value="<?= $key['department_id']; ?>">
                                            <?= $key['department_name']; ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>New Designation <span
                                        class="text-danger fw-bold">*</span> </label>
                                <select id="modal_designation_id"
                                    class="form-select form-select-sm chosen-select">
                                    <option value="">Select Designation</option>
                                </select>
                            </div>
                            <!-- Last Working Date -->
                            <div class="col-md-4 mb-3">
                                <label>Previous Salary<span
                                        class="text-danger fw-bold">*</span> </label>
                                <input type="text" id="modal_prev_salary" class="form-control form-control-sm" value="<?= $basic_salary ?>" readonly  onkeypress="numberOnly(event)" >
                            </div>
                             <div class="col-md-4 mb-3">
                                <label>New Salary<span
                                        class="text-danger fw-bold">*</span> </label>
                                <input type="text" id="modal_new_salary" class="form-control form-control-sm" value="<?= $basic_salary ?>" onkeypress="numberOnly(event)" >
                            </div>

                                
                           
                            <div class="col-md-4 mb-3">
                                <label>Promotion Date<span
                                        class="text-danger fw-bold">*</span> </label>
                                <input type="date" id="modal_promotion_date" class="form-control form-control-sm" value="<?= date('Y-m-d'); ?>" max="<?= date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Remark<span
                                        class="text-danger fw-bold"> </span> </label>
                                <textarea class="form-control form-control-sm"  id="modal_remark"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="saveTransfer()">Save</button>
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
            $('#transferModal').modal('show');
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
            const department_id = $('#modal_department_id').val();
            const designation_id = $('#modal_designation_id').val();
            const promotion_date = $('#modal_promotion_date').val();
            const new_salary = $('#modal_new_salary').val();
            const modal_remark = $('#modal_remark').val();
           const modal_department_name = $('#modal_department_id option:selected').text();
           const modal_designation_name = $('#modal_designation_id option:selected').text();

            if (emp_id == "" || department_id == "" || promotion_date == ""||new_salary=='') {
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
        Are you sure you want to Promote this employee to 
        <b>Department - ${modal_department_name}</b> 
        And Designation <b>${modal_designation_name}</b>    
    `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Promote',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#405189',
                cancelButtonColor: '#f06548'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: '',
                        type: 'POST',
                        data: {
                            promote_emp_id: emp_id,
                            department_id: department_id,
                            designation_id: designation_id,
                            promotion_date: promotion_date,
                            modal_remark: modal_remark,
                            new_salary: new_salary
                            
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Saving...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {

                            if (response.trim() === "success") {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Completed',
                                    text: 'Employee Promotion Record Add successfully.',
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

        $('#modal_promotion_date').on('change', function() {
            let selectedDate = $(this).val();
            let today = new Date().toISOString().split('T')[0];

            if (selectedDate > today) {
                Swal.fire("Invalid Date", "Future date not allowed", "warning");
                $(this).val(today);
            }
        });


        function updatePromotionStatus(promotion_id, emp_id, department_id, designation_id, basic_salary, status) {

            // already processed check
            if (status == 1) {
                Swal.fire('Info', 'Already Approved', 'info');
                return;
            }
            if (status == 2) {
                Swal.fire('Info', 'Already Rejected', 'info');
                return;
            }

            Swal.fire({
                title: 'Select Action',
                text: "Do you want to approve or reject this promotion?",
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: 'Approve',
                denyButtonText: 'Reject',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#28a745',
                denyButtonColor: '#d33'
            }).then((result) => {

                let action = '';

                if (result.isConfirmed) {
                    action = 'approve';
                } else if (result.isDenied) {
                    action = 'reject';
                } else {
                    return;
                }

                $.ajax({
                    url: 'approve_promotion.php',
                    type: 'POST',
                    data: {
                        promotion_idd: promotion_id,
                        emp_id: emp_id,
                        department_id: department_id,
                        designation_id: designation_id,
                        basic_salary: basic_salary,
                        action: action
                    },
                    success: function(res) {
                        if(res.trim() == 'success'){
                            Swal.fire('Success!', 'Action completed successfully.', 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Server error.', 'error');
                    }
                });

            });
        }
    </script>
</body>

</html>