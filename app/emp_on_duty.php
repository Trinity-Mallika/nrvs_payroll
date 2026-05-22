<?php
include("appsession.php");
// print_r($_SESSION);
// die;
$pagename = 'emp_on_duty.php';
$title = 'On Duty';
$tblname = "on_duty_master";
$tblpkey = "on_duty_id";

$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
//$imgpath1 = './admin/uploaded/on_duty/';
$imgpath1 = '../admin/uploaded/on_duty/';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $application_date  = $obj->test_input($_POST['application_date']);
    $on_duty_type = $obj->test_input($_POST['on_duty_type']);
    $total_day = $obj->test_input($_POST['total_day']);
    $doc_file = $_FILES["doc_file"] ?? '';
    $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'jfif', 'xlsx'];
    $imageName = $_FILES["doc_file"]["name"] ?? '';
    $imageFileType = $imageName ? strtolower(pathinfo($imageName, PATHINFO_EXTENSION)) : '';

    $form_data = array(
        "emp_id" => $emp_id,
        "type" => 'on_duty',
        "application_date" => $application_date,
        "on_duty_type" => $on_duty_type,
        "total_day" => $total_day,
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

        $lastid = $obj->insert_record_lastid($tblname, $form_data);
        $obj->update_record("on_duty_details", array(
            "on_duty_id" => 0,
            'unit_id' => $unitid,
            "sessionid" => $sessionid,
            'createdby' => $emp_id
        ), array("on_duty_id" => $lastid, "on_duty_type" => $on_duty_type));

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

        $action = 1;
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
        $action = 2;
        $process = "updated";
        echo json_encode([
            "status" => "updated",
            "id" => $keyvalue,
            "redirect" => "emp_on_duty.php?on_duty_id=" . $keyvalue
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
    $on_duty_type = $sqledit['on_duty_type'];

    $doc_file = $sqledit['doc_file'];
    $img = "";
} else {
    $application_date = date('Y-m-d');
    $on_duty_type = "On Duty";

    $doc_file = "";
    $img = "doc_file";
}
$total_day  = $obj->getvalfield("on_duty_details", "count(*)", "on_duty_id='$keyvalue' and createdby='$emp_id'");


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
        .fixed-save-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #fff;
            padding: 10px 15px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 999;
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
                        <a href="emp_on_duty_list.php" class="btn btn-sm btn-primary">
                            <i class="fa fa-list"></i> List
                        </a>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Application Date</label>
                        <input type="date" class="form-control shadow-sm" id="application_date" name="application_date" value="<?= $application_date ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">On Duty Type</label>
                        <select class="form-control" name="on_duty_type" id="on_duty_type">
                            <option value="Outdoor Duty">Outdoor Duty</option>
                            <option value="Missed Punch">Missed Punch</option>
                            <option value="Official Travel">Official Travel</option>
                        </select>
                        <script>
                            document.getElementById('on_duty_type').value = '<?= $on_duty_type; ?>';
                        </script>
                    </div>
                </div>
                <div class="card border-0 shadow-lg mb-3">
                    <div class="row">
                        <div class="mb-3 col-6">
                            <label for="" class="form-label"> Date</label>
                            <input type="date" class="form-control shadow-sm" id="date" name="date">
                        </div>
                        <div class="mb-3 col-6">
                            <label for="" class="form-label">In Time</label>
                            <input type="time" class="form-control shadow-sm" id="intime" name="intime">
                        </div>
                        <div class="mb-3 col-6">
                            <label for="" class="form-label">Out Time</label>
                            <input type="time" class="form-control shadow-sm" id="outtime" name="outtime">
                        </div>
                        <div class="mb-3 col-6">
                            <label for="" class="form-label">Place</label>
                            <input type="text" class="form-control shadow-sm" id="place" name="place">
                        </div>
                        <div class="mb-3 col-12">
                            <label for="" class="form-label">With Employee</label>
                            <input type="text" class="form-control shadow-sm" id="with_employee" name="with_employee">
                        </div>
                        <div class="mb-3 col-12">
                            <label for="" class="form-label">Remark</label>
                            <textarea name="remark" id="remark" class="form-control shadow-sm"></textarea>
                        </div>
                    </div>
                    <div class="d-grid mt-3 col-2 ">
                        <a class="btn btn-primary" onclick="save_duty_details();">
                            <span id="btnText"><?= "Add" ?></span>
                        </a>
                    </div>

                </div>

                <div class="card border-0 shadow-lg mb-3">

                    <div class="mb-3 col-12">
                        <label for="total_day" class="form-label">Total Days</label>
                        <input type="number" name="total_day" id="total_day" class="form-control shadow-sm" value="<?= $total_day; ?>" readonly />

                    </div>
                    <div class="mb-3 col-12">
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
                </div>


                <div id="fetch_duty_details" class="mb-4">
                </div>
                <input type="hidden" name="<?= $tblpkey ?>" value="<?= $keyvalue ?>" class="mb-4">
                <div class="fixed-save-bar">
                    <button type="button" id="saveBtn" class="btn btn-primary w-100" onclick="saveForm()">
                        <span id="btnText"><?= $keyvalue ? "Update" : "Create" ?></span>
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
</body>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        fetch_duty_details();


    });

    function saveForm() {
        // e.preventDefault();
        let form = document.getElementById("gatepassForm");
        let formData = new FormData(form);
        let application_date = $("#application_date").val();
        let on_duty_type = $("#on_duty_type").val();
        let total_day = $("#total_day").val();


        if (application_date == '') {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Field',
                text: 'Please select application date'
            });
            return false;
        }

        if (on_duty_type == '') {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Field',
                text: 'Please select on duty type'
            });
            return false;
        }

        if (total_day == '' || total_day <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Input',
                text: 'Enter valid total days'
            });
            return false;
        }
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

                if (res.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Created!',
                        text: 'Record Saved Successfully!',
                        timer: 2000
                    }).then(() => location.reload());
                } else if (res.status == 'updated') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'On Duty Updated Successfully!',
                        timer: 2000
                    }).then(() => {
                        window.location.href = "emp_on_duty.php";
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

    function save_duty_details() {
        const date = $('#date').val();
        const intime = $('#intime').val();
        const outtime = $('#outtime').val();
        const place = $('#place').val();
        const with_employee = $('#with_employee').val();
        const remark = $('#remark').val();
        const keyvalue = '<?= $keyvalue; ?>';
        if (date === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter Date'
            });
            return;
        }

        if (intime === "" || outtime === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter IN /OUT Time'
            });
            return;
        }

        if (place === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter Place Name'
            });
            return;
        }

        $.ajax({
            url: 'ajax_on_duty_save.php',
            type: 'POST',
            data: {
                date: date,
                keyvalue: keyvalue,
                place: place,
                intime: intime,
                outtime: outtime,
                with_employee: with_employee,
                remark: remark
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
                        fetch_duty_details();
                        $('#total_day').val(res.total_days);
                        $('#date,#intime,#outtime,#place,#with_employee,#remark').val('');
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

    function fetch_duty_details() {

        let keyvalue = '<?= $keyvalue; ?>';
        jQuery.ajax({
            type: 'POST',
            url: 'ajax_on_duty_fetch.php',
            data: 'keyvalue=' + keyvalue,
            dataType: 'html',
            success: function(data) {
                document.getElementById('fetch_duty_details').innerHTML = data;
            }
        }); //ajax close
    }


    function funDel(id) {
        let tblname = 'on_duty_details';
        let tblpkey = 'on_duty_details_id';
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
                        $.ajax({
                            type: 'POST',
                            url: 'get_total_day.php',
                            data: {
                                on_duty_details_idd: keyvalue,
                                action: 'on_duty',
                            },
                            success: function(res) {
                                $('#total_day').val(res);
                            }
                        });
                        fetch_duty_details();
                        Swal.fire({
                            icon: "success",
                            title: "Deleted!",
                            text: "Record deleted successfully",
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // ✅ FULL PAGE REFRESH
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
</script>


</html>