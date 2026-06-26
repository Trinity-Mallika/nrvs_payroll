<?php include("../adminsession.php");
$pagename = "leave_apply.php";
$title = "Leave Applcation";
$tblname = "on_duty_master";
$tblpkey = "on_duty_id";
$module = "Leave Applcation";
$submodule = "Leave Applcation List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$emp_id = (isset($_GET['emp_id'])) ? $obj->test_input($_GET['emp_id']) : '';
$imgpath1 = 'uploaded/on_duty/';
$current_date = date('Y-m-d');

$app_month = date('n', strtotime($current_date));
$app_year  = date('Y', strtotime($current_date));


if($emp_id > 0){
    $extra_off_data = $obj->getExtraOffBalance($emp_id,$app_month,$app_year);
    $extra_off = $extra_off_data['balance'] ?? 0;
    //$opening_leave_balance = $obj->get_opening_leave_balance($emp_id,$sessionid,$app_month,$app_year);
    $opening_leave_balance = 0;
    $earn_leave = $obj->getEarningLeave($emp_id,$sessionid,$app_month,$app_year );
    $c_off = $obj->getEmpCoffLeave($emp_id, $sessionid, $app_month,$app_year);
 
}else{

    $extra_off = 0;
    $opening_leave_balance = 0;
    $earn_leave = 0;
    $c_off = 0;
}
 
if (isset($_POST['submit'])) {
    $application_date  = $obj->test_input($_POST['application_date']);
    $emp_id = $obj->test_input($_POST['emp_id']);
    $reason = $obj->test_input($_POST['reason']);
    $leave_address = $obj->test_input($_POST['leave_address']);
    $contact_no = $obj->test_input($_POST['contact_no']);
    $substitute_emp_id = $obj->test_input($_POST['substitute_emp_id'] ?? 0);
    $total_day = $obj->test_input($_POST['total_day']);
    $extra_off = $obj->test_input($_POST['extra_off']);
    $earn_leave = $obj->test_input($_POST['earn_leave']);
   
    $doc_file = $_FILES["doc_file"] ?? '';

    $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'jfif', 'xlsx'];
    $imageName = $_FILES["doc_file"]['name'];
    $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

    $form_data = array(
        "emp_id" => $emp_id,
        "type" => 'leave',
        "application_date" => $application_date,
        "opening_leave_balance" => $opening_leave_balance,
        "extra_off" => $extra_off,
        "earn_leave" => $earn_leave,
        "reason" => $reason,
        "leave_address" => $leave_address,
        "contact_no" => $contact_no,
        "substitute_emp_id" => $substitute_emp_id,
        "entry_by" => 'hr',
        "total_day" => $total_day,
        "unit_id" => $unitid,
        "createdby" => $loginid,
        "sessionid" => $sessionid,
        "ipaddress" => $ipaddress
    );

    if ($keyvalue == 0) {
        if (isset($_FILES["doc_file"]) && !empty($_FILES["doc_file"]['name'])) {
            $imageFileType = strtolower(pathinfo($_FILES["doc_file"]['name'], PATHINFO_EXTENSION));
            if (in_array($imageFileType, $allowedTypes)) {
                $doc_file = $obj->uploadImage($imgpath1, $_FILES["doc_file"]);
                $form_data['doc_file'] = $doc_file;
            }
        }
        $form_data["createdate"] = $createdate;
        // print_r($form_data);
        // die;
        $lastid = $obj->insert_record_lastid($tblname, $form_data);
        $obj->update_record("leave_apply_detail", array(
            "on_duty_id" => 0,
            'unit_id' => $unitid,
            "sessionid" => $sessionid,
            'emp_id' => $emp_id
        ), array("on_duty_id" => $lastid));

        $form_data1 = array(
            "primary_id" => $lastid,
            "flag" => $title,
            "activity_type" => 'Inserted',
            "createdby" => $loginid,
            "pagename" => $pagename,
            "created_date" => $createdate,
            "created_time" => date('H:i:s'),
            "unit_id" => $unitid,
            'ipaddress' => $ipaddress,
            "sessionid" => $sessionid
        );
        $logactivity = $obj->insert_record("logactivity_master", $form_data1);

        $action = 1;
        $process = "insert";
    } else {
        if (!empty($imageName) && in_array($imageFileType, $allowedTypes)) {
            $old = $obj->getvalfield($tblname, "doc_file", "on_duty_id='$keyvalue'");
            if (!empty($old)) {
                @unlink($imgpath1 . $old);
            }
            $filename = $obj->uploadImage($imgpath1, $_FILES["doc_file"]);
            $form_data['doc_file'] = $filename;
        }
        $form_data["lastupdated"] = $createdate;
        $form_data["updatedby"] = $loginid;

        $where = array($tblpkey => $keyvalue);
        $obj->update_record($tblname, $where, $form_data);
        $form_data1 = array(
            "primary_id" => $keyvalue,
            "flag" => $title,
            "activity_type" => 'Updated',
            "createdby" => $loginid,
            "pagename" => $pagename,
            "created_date" => $createdate,
            "created_time" => date('H:i:s'),
            "unit_id" => $unitid,
            'ipaddress' => $ipaddress,
            "sessionid" => $sessionid
        );
        $logactivity = $obj->insert_record("logactivity_master", $form_data1);
        $action = 2;
        $process = "updated";
    }

    echo "<script>location='$pagename?action=$action'</script>";
}


if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $application_date =  $sqledit['application_date'];
    $emp_id = $sqledit['emp_id'];
    $contact_no = $sqledit['contact_no'];
    $leave_address = $sqledit['leave_address'];
    $reason = $sqledit['reason'];
    $substitute_emp_id = $sqledit['substitute_emp_id'];
    $earn_leave = $sqledit['earn_leave'];
    $extra_off = $sqledit['extra_off'];
    $opening_leave_balance = $sqledit['opening_leave_balance'];
    $doc_file = $sqledit['doc_file'];
    $img = "";

    
} else {
    $application_date = date('Y-m-d');

    $contact_no = $obj->getvalfield("employee_master", "mobile_no", "emp_id='$emp_id'");;
    $leave_address = "";
    $substitute_emp_id = "";
    $reason = "";
    
    $doc_file = "";
    $img = "doc_file";
     
}
$total_day  = $obj->getvalfield(
    "leave_apply_detail",
    "SUM(
        CASE 
            WHEN leave_day = 'FD' THEN 1
            WHEN leave_day = 'SL' THEN 1
            WHEN leave_day IN ('FHD','SHD') THEN 0.5
            ELSE 0
        END
    )",
    "on_duty_id='$keyvalue' and unit_id='$unitid' and emp_id='$emp_id'"
);

if (isset($_POST['leave_apply_id'])) {
    $leave_apply_id = $_POST['leave_apply_id'];
    $total_day  = $obj->getvalfield(
        "leave_apply_detail",
        "SUM(
        CASE 
            WHEN leave_day = 'FD' THEN 1
            WHEN leave_day = 'SL' THEN 1
            WHEN leave_day IN ('FHD','SHD') THEN 0.5
            ELSE 0
        END
    )",
        "on_duty_id='$leave_apply_id' and unit_id='$unitid' and emp_id='$emp_id'"
    );
    echo json_encode([
        "total_days" => $total_day ? $total_day : 0
    ]);
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
                                                <h5 class="card-title mb-0"> <?= $module; ?> <a href="leave_apply_list.php" class="float-end btn btn-primary btn-sm">List</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 mb-2">
                                            <label for="application_date" class="form-label">Application Date<span class="text-danger fw-bold">*</span></label>
                                            <input type="date" id="application_date" name="application_date" class="form-control form-control-sm" value="<?= $application_date ?>" autocomplete="off" readonly />
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="emp_id" class="form-label">Employee Name<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id" id="emp_id" onchange="get_url(this.value)">
                                                <option value="">Select Employee</option>
                                                <?php
                                                //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>" data-mobile="<?= $key['mobile_no']; ?>">
                                                        <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?> <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('emp_id').value = '<?= $emp_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="contact_no" class="form-label">Contact No<span class="text-danger fw-bold"> </span></label>
                                            <input type="text" id="contact_no" name="contact_no" class="form-control form-control-sm" placeholder="Enter Contact No" value="<?= $contact_no ?>" autocomplete="off" onkeypress="numberOnly(event)" />
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="leave_address" class="form-label">Leave Address<span class="text-danger fw-bold"> </span></label>
                                            <input type="text" id="leave_address" name="leave_address" class="form-control form-control-sm" placeholder="Enter Leave Address" value="<?= $leave_address ?>" autocomplete="off" />
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="reason" class="form-label">Reason<span class="text-danger fw-bold"> </span></label>
                                            <input type="text" id="reason" name="reason" class="form-control form-control-sm" placeholder="Enter Reason" value="<?= $reason ?>" autocomplete="off" />
                                        </div>

                                        <div class="col-lg-4 mb-2">
                                            <label for="substitute_emp_id" class="form-label">Substitute Employee Name<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select" name="substitute_emp_id" id="substitute_emp_id">
                                                <option value="">Select Substitute Employee</option>
                                                <?php
                                                //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?> <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('substitute_emp_id').value = '<?= $substitute_emp_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <div class="p-2 border rounded bg-light text-center">
                                                <small class="text-muted">Earning Leave</small><br>
                                                <input type="text" name="earn_leave" id="earning_leave" value="<?=$earn_leave?>"
                                                    class="form-control form-control-sm text-center fw-bold border-0 bg-light"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <div class="p-2 border rounded bg-light text-center">
                                                <small class="text-muted">Extra Off</small><br>
                                                <input type="text" name="extra_off" id="extra_off"   value="<?=$extra_off?>"
                                                    class="form-control form-control-sm text-center fw-bold border-0 bg-light"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 mb-2">
                                            <div class="p-2 border rounded bg-light text-center">
                                                <small class="text-muted">C Off</small><br>
                                                <input type="text" name="c_off" id="c_off" value="<?=$c_off?>"
                                                    class="form-control form-control-sm text-center fw-bold border-0 bg-light"
                                                    readonly>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-sm btn-danger mb-2" onclick="toggleMultiDay()">
                                Show Multi Day
                            </button>
                            <div id="multiDaySection" style="display:none;">
                                <div class="card" id="customerList">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- TOP BAR -->
                                            <div class="table-responsive">
                                                <table class="display table table-sm table-bordered" style="width:100%">
                                                    <thead>
                                                        <tr class="table-primary">
                                                            <th>Sr No.</th>
                                                            <th>Date</th>
                                                            <th>Number Of Day</th>
                                                            <th>Day</th>
                                                            <th>Leave Type</th>
                                                            <th>Remarks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <td>#.</td>
                                                        <td>
                                                            <input type="date" id="multi_date" class="form-control form-control-sm" value="<?= date("Y-m-d") ?>">
                                                        </td>
                                                        <td>
                                                            <input type="number" id="no_of_days" class="form-control form-control-sm" style="width:70px;" value="1">
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm chosen-select"            id="multi_leave_day">
                                                                <option value="FD">Full Day</option>
                                                                <option value="FHD">First Half Day</option>
                                                                <option value="SHD">Second Half Day</option>  
                                                                <!-- <option value="SL">Sick Leave</option> -->
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm chosen-select" id="multi_leave_type">
                                                                <option value="EL">EARNED LEAVE</option>
                                                                 <!-- <option value="WL">WEEKLY LEAVE</option>   -->
                                                                <option value="EO">EXTRA OFF</option>
                                                                <option value="CO">C-OFF</option>
                                                                <!-- <option value="L">OPENING LEAVE</option> -->
                                                                <!-- <option value="LWP">LEAVE WITHOUT PAY</option>  -->
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" id="multi_remark" class="form-control form-control-sm">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-success" onclick="save_multi_leave_details();" id="ajax_multi_btn">Add</button>
                                                        </td>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <!-- TOP BAR --> 
                                            <div class="table-responsive">
                                                <table class="display table table-sm table-bordered" style="width:100%">
                                                    <thead>
                                                        <tr class="table-primary">
                                                            <th>Sr No.</th>
                                                            <th>Date</th>
                                                            <th>Day</th>
                                                            <th>Leave Type</th>
                                                            <th>Remarks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <td>#.</td>
                                                        <td>
                                                            <input type="date" id="date" class="form-control form-control-sm" value="<?= date("Y-m-d") ?>">
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm chosen-select" id="leave_day">
                                                                <option value="FD">Full Day</option>
                                                                <option value="FHD">First Half Day</option>
                                                                <option value="SHD">Second Half Day</option>  
                                                                <!-- <option value="SL">Sick Leave</option> -->
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm chosen-select" id="leave_type">
                                                                <option value="EL">EARNED LEAVE</option>
                                                                <option value="EO">EXTRA OFF</option>
                                                                <option value="CO">C-OFF</option>
                                                                <!-- <option value="L">OPENING LEAVE</option>
                                                                <option value="LWP">LEAVE WITHOUT PAY</option> -->
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" id="remark" class="form-control form-control-sm">
                                                            <input type="hidden" id="leave_details_id" class="form-control form-control-sm" value="0">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-success" onclick="save_leave_details();" id="ajax_btn">Add</button>
                                                        </td>
                                                    </tbody>
                                                    <tbody id="fetch_leave_details">
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-lg-4 mb-2">
                                                <label for="total_day" class="form-label"> Total Days<span class="text-danger fw-bold"> </span></label>
                                                <input type="text" id="total_day" name="total_day" class="form-control form-control-sm" placeholder="Enter Total Days" value="<?= $total_day ?>" autocomplete="off" onkeypress="numberOnly(event)" readonly />
                                            </div>


                                            <div class="col-md-3">
                                                <label class="form-label">
                                                    Attached File <span class="text-danger fw-bold"> </span>
                                                </label>

                                                <input type="file" class="form-control form-control-sm"
                                                    name="doc_file" id="doc_file" value="<?= $doc_file ?>">

                                                <?php if (!empty($doc_file)) {
                                                    $ext = strtolower(pathinfo($doc_file, PATHINFO_EXTENSION));
                                                ?>
                                                    <div class="mt-2">
                                                        <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) { ?>
                                                            <img src="<?= $imgpath1 . $doc_file ?>"
                                                                style="height:50px;border:1px solid #ccc;">
                                                        <?php } else { ?>
                                                            <a href="<?= $imgpath1 . $doc_file ?>"
                                                                target="_blank" class="btn btn-sm btn-secondary">
                                                                View Uploaded <?= strtoupper($ext) ?>
                                                            </a>
                                                        <?php } ?>
                                                    </div>

                                                    <input type="hidden" name="old_attachment"
                                                        value="<?= $doc_file ?>">
                                                <?php } ?>
                                            </div>
                                            <?php $chkadd = $obj->check_addBtn($pagename, $loginid);
                                            if ($chkadd == 1) {  ?>
                                                <div class="col-lg-12 text-center">
                                                    <br>
                                                    <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                                    <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> " onClick="return validateForm()">
                                                    <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
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
            fetch_leave_details()
            handleEmployeeChange()
        });

        function get_url(emp_id) {
            window.location.href = "leave_apply.php?emp_id=" + emp_id;

        }

        function toggleMultiDay() {
            let section = document.getElementById("multiDaySection");

            if (section.style.display === "none") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }

        function funDel(id) {
            $('#deleteRecordModal').modal('show');
            tblname = 'leave_apply_detail';
            tblpkey = 'leave_details_id';
            pagename = '<?php echo $pagename; ?>';
            submodule = '<?php echo $submodule; ?>';
            let keyvalue = '<?= $keyvalue ?>';
            $('#delete-record').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/delete_master.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' + submodule + '&pagename=' + pagename,
                    dataType: 'html',
                    success: function(data) {
                        $.ajax({
                            type: 'POST',
                            url: '', // same page
                            data: {
                                leave_apply_id: keyvalue
                            },
                            success: function(res) {
                                let data = JSON.parse(res);
                                $('#total_day').val(data.total_days);
                            }
                        });
                        fetch_leave_details();
                    }
                });
                $('#deleteRecordModal').modal('hide');
            });
        };

        function validateForm() {
            // 🔹 Step 1: basic required field validation
            if (!checkinputmaster('application_date,emp_id')) {
                return false;
            }

            // 🔹 Step 2: total_day validation
            let totalDay = document.getElementById("total_day").value;

            if (totalDay === '') {
                alert("Total Day must be greater than 0");
                document.getElementById("total_day").focus();
                return false;
            }

            return true;
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

        function save_leave_details() {
            const date = $('#date').val();
            const leave_day = $('#leave_day').val();
            const no_of_days = 1;
            const leave_type = $('#leave_type').val();
            const remark = $('#remark').val();
            const emp_id = $('#emp_id').val();
            const leave_details_id = $('#leave_details_id').val();
            const keyvalue = '<?= $keyvalue; ?>';
            if (emp_id == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required',
                    text: 'Please enter Employee First'
                });
                return;
            }

            if (date === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required',
                    text: 'Please enter Date'
                });
                return;
            }

            if (leave_day == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required',
                    text: 'Please enter Leave Day'
                });
                return;
            }
            if (leave_type == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required',
                    text: 'Please enter Leave Type'
                });
                return;
            }

            $.ajax({
                url: 'ajax_leave_save.php',
                type: 'POST',
                data: {
                    date: date,
                    keyvalue: keyvalue,
                    no_of_days: no_of_days,
                    leave_day: leave_day,
                    leave_type: leave_type,
                    remark: remark,
                    leave_details_id: leave_details_id,
                    emp_id: emp_id
                },
                beforeSend: function() {
                    $('#ajax_btn').prop("disabled", true).text("Saving...");
                },
                success: function(response) {
                    let res = JSON.parse(response);
                   
                    if (res.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Details added successfully',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            fetch_leave_details();
                            $('#total_day').val(res.total_days);
                            $('#date,#remark').val('');
                            $('#leave_details_id').val('0');
                        })
                    } else if (res.status === "duplicate") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Duplicate Entry',
                            text: res.message
                        });
                        return;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response
                        });
                    }
                },
                error: function() {
                    Swal.fire("Error", "Error while uploading. Try again.");
                },
                complete: function() {
                    $('#ajax_btn').prop("disabled", false).text("Add");
                }
            });
        }


        function save_multi_leave_details() {
            const date = $('#multi_date').val();
            const leave_day = $('#multi_leave_day').val();
            const no_of_days = $('#no_of_days').val();
            const leave_type = $('#multi_leave_type').val();
            const remark = $('#multi_remark').val();
            const emp_id = $('#emp_id').val();
            const leave_details_id = $('#leave_details_id').val();
            const keyvalue = '<?= $keyvalue; ?>';
            if (emp_id == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required',
                    text: 'Please enter Employee First'
                });
                return;
            }

            if (date === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required',
                    text: 'Please enter Date'
                });
                return;
            }

            if (leave_day == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required',
                    text: 'Please enter Leave Day'
                });
                return;
            }
            if (leave_type == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required',
                    text: 'Please enter Leave Type'
                });
                return;
            }

            $.ajax({
                url: 'ajax_leave_save.php',
                type: 'POST',
                data: {
                    date: date,
                    keyvalue: keyvalue,
                    no_of_days: no_of_days,
                    leave_day: leave_day,
                    leave_type: leave_type,
                    remark: remark,
                    leave_details_id: leave_details_id,
                    emp_id: emp_id
                },
                beforeSend: function() {
                    $('#ajax_multi_btn').prop("disabled", true).text("Saving...");
                },
                success: function(response) {
                    let res = JSON.parse(response); 
                    if (res.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Details added successfully',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            fetch_leave_details();
                            $('#total_day').val(res.total_days);
                            $('#date,#remark').val('');
                            $('#leave_details_id').val('0');
                        })
                    } else if (res.status === "duplicate") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Duplicate Entry',
                            text: res.message
                        });
                        return;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response
                        });
                    }
                },
                error: function() {
                    Swal.fire("Error", "Error while uploading. Try again.");
                },
                complete: function() {
                    $('#ajax_multi_btn').prop("disabled", false).text("Add");
                }
            });
        }


        function fetch_leave_details() {
            let keyvalue = '<?= $keyvalue; ?>';
            let emp_id = '<?= $emp_id; ?>';

            jQuery.ajax({
                type: 'POST',
                url: 'ajax_leave_fetch.php',
                data: 'keyvalue=' + keyvalue + '&emp_id=' + emp_id,
                dataType: 'html',
                success: function(data) {
                    document.getElementById('fetch_leave_details').innerHTML = data;
                }
            }); //ajax close
        }

        function editLeave(leave_details_id, date, leave_day, leave_type, remark) {
            $('#leave_details_id').val(leave_details_id);
            $('#date').val(date);
            $('#leave_day').val(leave_day).trigger('change');
            $('#leave_type').val(leave_type).trigger('change');
            $('#remark').val(remark).focus();
            $('#ajax_btn')
                .prop("disabled", false)
                .text("Update") // <-- change here
                .removeClass('btn-success')
                .addClass('btn-primary');
        }


        function handleEmployeeChange() {
            let emp_id = '<?= $emp_id ?>';
            $.ajax({
                url: "get_employee_leave_details.php",
                type: "POST",
                data: {
                    emp_id: emp_id,
                },
                success: function(res) {
                    let data = JSON.parse(res);

                    if (data.status === "success") {
                        $("#extra_off").val(data.extra_off);
                        $("#earning_leave").val(data.earning_leave);
                        $("#opening_leave_balance").val(data.opening_leave_balance);


                    }
                }
            });
        }
    </script>
</body>

</html>