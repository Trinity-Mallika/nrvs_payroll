<?php
include("appsession.php");
// print_r($_SESSION);
// die;
$pagename = 'leave_apply.php';
$title = 'Earn Leave Application';
$tblname = "on_duty_master";
$tblpkey = "on_duty_id";

$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
//$imgpath1 = './admin/uploaded/on_duty/';
$imgpath1 = '../admin/uploaded/on_duty/';
$emp_id = $_SESSION['emp_id'];
$emp_data = $obj->select_record("employee_master", ["emp_id" => $emp_id]);

$emp_name = $emp_data['first_name'] ?? '';
$mobile_no = $emp_data['mobile_no'] ?? '';
$reporting_manager = $emp_data['reporting_manager'] ?? '';
$is_esic = $emp_data['is_esic'] ?? '0';



$current_date = date('Y-m-d');

$app_month = date('m', strtotime($current_date));
$app_year  = date('Y', strtotime($current_date));

$setting_type = ($is_esic == 1) ? 'ESIC' : 'Non ESIC';

//$three_month_leave = $obj->getLeave($emp_id, $app_month, $app_year);
$total_earning_leave = $obj->getEarningLeave($emp_id, $sessionid);
//$total_earning_leave = 0.5;
$c_off_balance = $obj->getEmpCoffLeave($emp_id, $sessionid,$app_month, $app_year);
 
$extra_off = $obj->getExtraOffBalance($emp_id, $app_month, $app_year);

$sql = "
SELECT 
    leave_type,
    IFNULL(SUM(
        CASE
            WHEN leave_day IN ('FD','SL') THEN 1
            WHEN leave_day IN ('FHD','SHD') THEN 0.5
            ELSE 0
        END
    ),0) AS availed_leave
FROM leave_apply_detail
WHERE emp_id='$emp_id'
AND status='0'
AND unit_id='$unitid'
GROUP BY leave_type
";

$res = $obj->executequery($sql);

$availed_EL = 0;
$availed_EO = 0;
$availed_CO = 0;

foreach($res as $row){

    if($row['leave_type']=='EL'){
        $availed_EL = $row['availed_leave'];
    }

    if($row['leave_type']=='EO'){
        $availed_EO = $row['availed_leave'];
    }

    if($row['leave_type']=='CO'){
        $availed_CO = $row['availed_leave'];
    }
} 

$remaining_EL = $total_earning_leave - $availed_EL;
$remaining_EO = $extra_off['balance'] - $availed_EO;
$remaining_CO = $c_off_balance - $availed_CO;
//$opening_leave_balance = $obj->get_opening_leave_balance($emp_id, $sessionid);

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $detailCount = $obj->getvalfield(
        "leave_apply_detail",
        "SUM(
        CASE 
            WHEN leave_day = 'FD' THEN 1
            WHEN leave_day = 'SL' THEN 1
            WHEN leave_day IN ('FHD','SHD') THEN 0.5
            ELSE 0
            END
        )",
        "on_duty_id='$keyvalue' and unit_id='$unitid' and emp_id='$emp_id' and leave_type='EL'"
    );

    if ($detailCount <= 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Please enter at least one Leave record."
        ]);
        exit;
    }

    $application_date = date('Y-m-d', strtotime($_POST['application_date']));
    $reason = $obj->test_input($_POST['reason']);
    $leave_address = $obj->test_input($_POST['leave_address']);
    $contact_no = $obj->test_input($_POST['contact_no']); 
    $substitute_emp_id = isset($_POST['substitute_emp_id']) ? $obj->test_input($_POST['substitute_emp_id']) : ''; 
    $total_day = isset($_POST['total_day']) ? $obj->test_input($_POST['total_day']) : ''; 
    $doc_file = $_FILES["doc_file"] ?? ''; 
    $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'jfif', 'xlsx'];
    $imageName = $_FILES["doc_file"]['name'];
    $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

    $form_data = array(
        "emp_id" => $emp_id,
        "reporting_manager" => $reporting_manager,
        "type" => 'leave',
       // "opening_leave_balance" => $opening_leave_balance,
        "extra_off" => $extra_off['balance'],
        "earn_leave" => $total_earning_leave,
        "application_date" => $application_date,
        "reason" => $reason,
        "leave_address" => $leave_address,
        "contact_no" => $contact_no,
        "substitute_emp_id" => $substitute_emp_id,
        "total_day" => $total_day,
        "entry_by" => 'emp',
        "unit_id" => $unitid,
        "createdby" => $emp_id,
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
            'emp_id' => $emp_id,
            'leave_type' => 'EL'
        ), array("on_duty_id" => $lastid));

        $form_data1 = array(
            "primary_id" => $lastid,
            "flag" => $title,
            "activity_type" => 'Inserted',
            "createdby" => $emp_id,
            "pagename" => $pagename,
            "created_date" => $createdate,
            "created_time" => date('H:i:s'),
            "unit_id" => $unitid,
            'ipaddress' => $ipaddress,
            "sessionid" => $sessionid
        );
        $logactivity = $obj->insert_record("logactivity_master", $form_data1);


        $process = "insert";
        echo json_encode([
            "status" => "success",
            "id" => $lastid
        ]);
        exit;
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

        $where = array($tblpkey => $keyvalue);
        $obj->update_record($tblname, $where, $form_data);
        $form_data1 = array(
            "primary_id" => $keyvalue,
            "flag" => $title,
            "activity_type" => 'Updated',
            "createdby" => $emp_id,
            "pagename" => $pagename,
            "created_date" => $createdate,
            "created_time" => date('H:i:s'),
            "unit_id" => $unitid,
            'ipaddress' => $ipaddress,
            "sessionid" => $sessionid
        );
        $logactivity = $obj->insert_record("logactivity_master", $form_data1);

        $process = "updated";
        echo json_encode([
            "status" => "updated",
            "id" => $keyvalue,
            "redirect" => "leave_apply.php?on_duty_id=" . $keyvalue
        ]);
        exit;
    }
    exit;
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
    $doc_file = $sqledit['doc_file'];
    $reporting_manager = $sqledit['reporting_manager'];
    $img = "";
} else {
    $application_date = date('Y-m-d');
    $contact_no = "";
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
    "on_duty_id='$keyvalue' and unit_id='$unitid' and emp_id='$emp_id' and leave_type='EL'"
);


$report_emp_data = $obj->select_record("employee_master", ["emp_id" => $reporting_manager]);

$report_man_name = $report_emp_data['first_name'] ?? '';
$report_man_code = $report_emp_data['emp_code'] ?? "";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title><?= $title ?></title>
    <!-- css links  files -->

    <?php include("inc/css-file.php"); ?>

    <style>
    body.dashboard {
        padding-bottom: calc(80px + env(safe-area-inset-bottom));
    }

    .fixed-save-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #fff;
        padding: 10px 15px;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        z-index: 999;
        /* Important for mobile devices */
        padding-bottom: calc(10px + env(safe-area-inset-bottom));
    }

    .fixed-save-bar .btn {
        height: 45px;
        font-size: 16px;
        font-weight: 600;
    }
    </style>
</head>

<body class="dashboard">
    <section class="top-sec ">
        <?php include("inc/header.php"); ?>

        <div class="container pb-5">

            <form method="POST" id="gatepassForm" enctype="multipart/form-data">
                <div class="card border-0 shadow-lg mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"> </h5>
                        <a href="leave_apply_list.php" class="btn btn-sm btn-primary">
                            <i class="fa fa-list"></i> List
                        </a>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Reporting Manager</label>
                        <input type="text" class="form-control shadow-sm" id="reporting_manager_name"
                            name="reporting_manager_name" value="<?= $report_man_code . '-' . $report_man_name ?>"
                            readonly>

                        <input type="hidden" class="form-control shadow-sm" id="reporting_manager"
                            name="reporting_manager" value="<?= $reporting_manager ?>">

                    </div>

                    <div class="mb-3">
                        <label class="form-label">Application Date</label>
                        <input type="text" class="form-control shadow-sm"
                            value="<?= !empty($application_date) ? date('d-m-Y', strtotime($application_date)) : '' ?>"
                            readonly id="application_date" name="application_date">
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Employee Name</label>
                        <input type="text" class="form-control shadow-sm" id="emp_name" name="emp_name"
                            value="<?= $emp_name ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Mobile No</label>
                        <input type="text" class="form-control shadow-sm" id="contact_no" name="contact_no"
                            value="<?= $mobile_no ?>" onkeypress="numberOnly(event);" maxlength="10">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Leave Address</label>
                        <input type="text" id="leave_address" name="leave_address" class="form-control form-control-sm"
                            placeholder="Enter Leave Address" value="<?= $leave_address ?>" autocomplete="off" />
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Reason</label>
                        <input type="text" id="reason" name="reason" class="form-control form-control-sm"
                            placeholder="Enter Reason" value="<?= $reason ?>" autocomplete="off" />
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Substitute Employee Name</label>
                        <select class="form-control chosen-select" name="substitute_emp_id" id="substitute_emp_id">
                            <option value="">Select Substitute Employee</option>
                            <?php
                            //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                            $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                            foreach ($res as $key) { ?>
                            <option value="<?= $key['emp_id']; ?>">
                                <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?>
                                <?= ucfirst($key['last_name'] ?? ''); ?></option>
                            <?php } ?>
                        </select>
                        <script>
                        document.getElementById('substitute_emp_id').value = '<?= $substitute_emp_id; ?>';
                        </script>
                    </div>
                    <div class="mb-3 col-12">
                        <label for="total_day" class="form-label">Total Days</label>
                        <input type="number" name="total_day" id="total_day" class="form-control shadow-sm"
                            value="<?= $total_day; ?>" readonly />

                    </div>
                    <div class="mb-3 col-12">
                        <label class="form-label">
                            Attached File <span class="text-danger fw-bold"> </span>
                        </label>

                        <input type="file" class="form-control form-control-sm" name="doc_file" id="doc_file"
                            value="<?= $doc_file ?>">

                        <?php if (!empty($doc_file)) {
                            $ext = strtolower(pathinfo($doc_file, PATHINFO_EXTENSION));
                        ?>
                        <div class="mt-2">
                            <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) { ?>
                            <img src="<?= $imgpath1 . $doc_file ?>" style="height:50px;border:1px solid #ccc;">
                            <?php } else { ?>
                            <a href="<?= $imgpath1 . $doc_file ?>" target="_blank" class="btn btn-sm btn-secondary">
                                View Uploaded <?= strtoupper($ext) ?>
                            </a>
                            <?php } ?>
                        </div>

                        <input type="hidden" name="old_attachment" value="<?= $doc_file ?>">
                        <?php } ?>
                    </div>

                    <div class="row text-center">

                        <!-- Earn Leave -->
                        <div class="col-md-4 mb-3">
                            <div class="card shadow border-0">
                                <div class="card-body">
                                    <h6 class="text-primary">Earn Leave</h6>

                                    <div>
                                        Total :
                                        <b id="total_EL"><?= $total_earning_leave ?></b>
                                    </div>

                                    <div>
                                        Availed :
                                        <b class="text-danger" id="availed_EL">
                                            <?= $availed_EL ?>
                                        </b>
                                    </div>

                                    <div>
                                        Remaining :
                                        <b class="text-success" id="remaining_EL">
                                            <?= $remaining_EL ?>
                                        </b>
                                    </div> 
                                </div>
                            </div>
                        </div> 
                    </div> 
                </div>
                <div class="card border-0 shadow-lg mb-3">
                    <div class="row">
                        <div class="mb-3 col-6">
                            <label for="" class="form-label">Date</label>
                            <input type="text" class="form-control shadow-sm datepicker" id="date" name="date"
                                value="<?= date('d-m-Y'); ?>" autocomplete="off">
                        </div>
                        <div class="mb-3 col-6">
                            <label for="" class="form-label">No. Of Days</label>
                            <input type="number" class="form-control shadow-sm" id="no_of_days" name="no_of_days"
                                value="1">
                        </div>
                        <div class="mb-3 col-6">
                            <label for="" class="form-label">Day</label>
                            <select class="form-control" id="leave_day" onchange="checkLeaveBalance()">
                                <option value="">Select Please</option>
                                <option value="FD">Full Day</option>
                                <option value="FHD">Half Day</option>
                                <!-- <option value="SHD">Second Half Day</option>   -->
                                <!-- <option value="SL">Sick Leave</option> -->
                            </select>
                        </div>
                        <div class="mb-3 col-6">
                            <label for="" class="form-label">Leave Type</label>
                            <select class="form-control" id="leave_type" onchange="checkLeaveBalance()">
                                <option value="EL">EARNED LEAVE</option>
                                <!-- <option value="WL">WEEKLY LEAVE</option> -->
                                <!-- <option value="EO">EXTRA OFF</option>
                                <option value="CO">C Off</option> -->
                                <!-- <option value="L">OPENING LEAVE</option>-->
                                <!-- <option value="LWP">LEAVE WITHOUT PAY</option> -->
                            </select>
                        </div>

                        <div class="mb-3 col-12">
                            <label for="" class="form-label">Remark</label>
                            <textarea name="remark" id="remark" class="form-control shadow-sm"></textarea>
                            <input type="hidden" class="form-control shadow-sm" id="leave_details_id"
                                name="leave_details_id" value="0">
                        </div>
                    </div>
                    <div class="d-grid mt-3 col-2 ">
                        <a class="btn btn-primary" onclick="save_leave_details();">
                            <span id="btnTextDetails"><?= "Add" ?></span>
                        </a>
                    </div>
                </div>


                <input type="hidden" id="edit_row_id">
                <div id="fetch_leave_details" class="mb-4"></div>
                <input type="hidden" name="<?= $tblpkey ?>" value="<?= $keyvalue ?>" class="mb-4">
                <div class="fixed-save-bar">
                    <button type="button" id="saveBtn" class="btn btn-primary w-100" onclick="saveForm()">
                        <span id="btnText"><?= $keyvalue ? "Update" : "Apply" ?></span>
                        <span id="btnLoader" style="display:none;">
                            <i class="fa fa-spinner fa-spin"></i> Saving...
                        </span>
                    </button>
                </div>


            </form>
        </div>


    </section>
    <!-- js script files -->
    <?php include("inc/js-file.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js">
    </script>
</body>


<script>
$(document).ready(function() {

    // $('#example').DataTable();
    $(".chosen-select").chosen({
        width: '100%',
        search_contains: true
    });
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });
    fetch_leave_details()
    fetchLeaveBalance();
});


function saveForm() {
    // e.preventDefault();
    let form = document.getElementById("gatepassForm");
    let formData = new FormData(form);
    let application_date = $("#application_date").val();
    let contact_no = $("#contact_no").val();
    let leave_address = $("#leave_address").val();
    let reason = $("#reason").val();
    let total_day = $("#total_day").val();
    let substitute_emp_id = $("#substitute_emp_id").val();
    let type = 'EL'; 
    const reporting_manager = $('#reporting_manager').val();

    if (reporting_manager == "" || reporting_manager == 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Required',
            text: 'Reporting Manager Not Allotted ! Contact to HR'
        });
        return;
    }

    if (application_date == '') {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Field',
            text: 'Please select application date'
        });
        return false;
    } 

    // if (total_day == '') {
    //     Swal.fire({
    //         icon: 'warning',
    //         title: 'Invalid Input',
    //         text: 'Enter valid total days'
    //     });
    //     return false;
    // }

    let file = $("#doc_file")[0].files[0];
    if (file) {
        let allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'xlsx'];
        let ext = file.name.split('.').pop().toLowerCase();

        if (!allowed.includes(ext)) {
            alert("Invalid file type");
            return false;
        }
    }
    $("#saveBtn").attr("disabled", true);
    $("#btnText").hide();
    $("#btnLoader").show();
    $.ajax({
        url: '',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',

        success: function(res) {
            if (res.status == 'error') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation',
                    text: res.message
                }).then(() => {
                    $('#saveBtn').prop("disabled", false).text("Create");
                });

                return false;
            }
            if (res.status == 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Created!',
                    text: 'Record Saved Successfully!',
                    timer: 2000
                }).then(() => {
                    window.location.href = 'leave_apply_list.php?type=EL';
                });
            } else if (res.status == 'updated') {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Leave Updated Successfully!',
                    timer: 2000
                }).then(() => {
                    window.location.href = 'leave_apply_list.php?type=EL';
                });
            } else {
                alert(res.message || "Something went wrong");
            }

            $("#saveBtn").attr("disabled", false);
            $("#btnText").show();
            $("#btnLoader").hide();
        },

        error: function(xhr) {
            console.log(xhr.responseText);
            alert("Server Error");
            $("#saveBtn").attr("disabled", false);
            $("#btnText").show();
            $("#btnLoader").hide();
        }
    });
}

function save_leave_details() {
    const date = $('#date').val(); 
    const leave_day = $('#leave_day').val();
    const leave_type = $('#leave_type').val();
    const remark = $('#remark').val();
    const no_of_days = $('#no_of_days').val();
    const keyvalue = '<?= $keyvalue; ?>';
    const leave_details_id = $('#leave_details_id').val();
    const reporting_manager = $('#reporting_manager').val();
    if (reporting_manager == "" || reporting_manager == 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Required',
            text: 'Reporting Manager Not Allotted ! Contact to HR'
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
    if (remark == "") {
        Swal.fire({
            icon: 'warning',
            title: 'Required',
            text: 'Please enter Remark'
        });
        return;
    }

    $.ajax({
        url: 'ajax_leave_save.php',
        type: 'POST',
        data: {
            date: date,
            keyvalue: keyvalue,
            reporting_manager: reporting_manager,
            leave_day: leave_day,
            leave_type: leave_type,
            no_of_days: no_of_days,
            remark: remark,
            leave_details_id: leave_details_id,
            type: 'EL'
        },
        beforeSend: function() {
            $('#btnTextDetails').prop("disabled", true).text("Saving...");
        },
        success: function(response) {
            console.log(response);
            let res = JSON.parse(response);
            if (res.status === "success") {
                Swal.fire('Added', 'Details added successfully', 'success');

            } else if (res.status === "updated") {
                Swal.fire('Updated', 'Details updated successfully', 'success');

            } else if (res.status === "duplicate") {

                let msg = '';

                if (res.duplicates.length > 0) {

                    res.duplicates.forEach(function(item) {
                        msg += item.date + " : " + item.reason + "<br>";
                    });

                } else {
                    msg = res.message;
                }

                Swal.fire({
                    icon: 'warning',
                    title: 'Cannot Apply Leave',
                    html: msg
                });

                return;
            } else if (res.status === "error") {

                Swal.fire({
                    icon: "warning",
                    title: "Cannot Apply Leave",
                    text: res.message
                });

                return;
            }
            fetch_leave_details();
            fetchLeaveBalance();
            $('#total_day').val(res.total_days);

            $('#date').val('');
            $('#remark').val('');
            $('#leave_type').val('LWP');
            $('#no_of_days').val(1);
            $('#leave_details_id').val(0);

            $('#btnTextDetails')
                .text("Add")
                .removeClass('btn-primary')
                .addClass('btn-success');
        },
        error: function() {
            Swal.fire("Error", "Error while uploading. Try again.");
        },
        complete: function() {
            $('#btnTextDetails').prop("disabled", false).text("Add");
        }
    });
}

function editLeave(leave_details_id, date, leave_day, leave_type, remark) {
    $('#leave_details_id').val(leave_details_id);
    let parts = date.split('-');
    if (parts.length === 3) {
        let formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0]; 
     
        $('#date').val(formattedDate); 
        // Datepicker ko bhi update karo
        $('#date').datepicker('update', formattedDate);
    }
    $('#leave_day').val(leave_day);
    $('#leave_type').val(leave_type);
    $('#remark').val(remark).focus();
    $('#btnTextDetails')
        .prop("disabled", false)
        .text("Update") // <-- change here
        .removeClass('btn-success')
        .addClass('btn-primary');
}

function fetch_leave_details() {
    let keyvalue = '<?= $keyvalue; ?>';
    jQuery.ajax({
        type: 'POST',
        url: 'ajax_leave_fetch.php',
        data: 'keyvalue=' + keyvalue + '&type=EL',
        dataType: 'html',
        success: function(data) {
            document.getElementById('fetch_leave_details').innerHTML = data;
        }
    }); //ajax close
}

function funDel(id) {
    let tblname = 'leave_apply_detail';
    let tblpkey = 'leave_details_id';
    let pagename = '<?php echo $pagename; ?>';
    let keyvalue = '<?php echo $keyvalue; ?>';

    Swal.fire({
        title: "Are you sure?",
        text: "This record will be deleted permanently!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                type: 'POST',
                url: 'delete_master.php',
                data: {
                    id: id,
                    tblname: tblname,
                    tblpkey: tblpkey,
                    pagename: pagename
                },
                success: function(response) {
                    fetch_leave_details();
                    fetchLeaveBalance();
                    $.ajax({
                        type: 'POST',
                        url: 'get_total_day.php', // same page
                        data: {
                            on_duty_details_idd: keyvalue,
                            action: 'leave',
                        },
                        success: function(res) {
                            $('#total_day').val(res);
                        }
                    });

                    Swal.fire({
                        icon: "success",
                        title: "Deleted!",
                        text: "Record deleted successfully",
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Something went wrong"
                    });
                }
            });

        }
    });
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

function checkLeaveBalance() {
    let earningLeaveBalance = <?= (float)$remaining_EL ?>;
    let cOffBalance = <?= (float)$remaining_CO ?>;
    //let extraOffBalance = <(float)$extra_off['balance'] ?>;
    let extraOffBalance = 1;

    let day = $("#leave_day").val();
    let leaveType = $("#leave_type").val();

    let requiredBalance = (day === 'FD') ? 1 : 0.5;

    let availableBalance = 0;

    switch (leaveType) {
        case 'EL':
            availableBalance = parseFloat(earningLeaveBalance);
            break;

        case 'EO':
            availableBalance = parseFloat(extraOffBalance);
            break;

        case 'CO':
            availableBalance = parseFloat(cOffBalance);
            break;

        case 'LWP':
            return true; // LWP always allowed
    }

    if (availableBalance < requiredBalance) {

        Swal.fire({
            icon: 'warning',
            title: 'Insufficient Balance',
            text: 'Selected leave balance is not available.'
        });

        $("#leave_type").val("LWP");

        return false;
    }

    return true;
}

function fetchLeaveBalance() {
    $.ajax({
        url: 'ajax_leave_balance.php',
        dataType: 'json',
        success: function(res) {
            $("#remaining_EL").html(res.remaining_EL);
            $("#remaining_EO").html(res.remaining_EO);
            $("#remaining_CO").html(res.remaining_CO);

            $("#availed_EL").html(res.availed_EL);
            $("#availed_EO").html(res.availed_EO);
            $("#availed_CO").html(res.availed_CO);
        }
    });

}
</script>


</html>