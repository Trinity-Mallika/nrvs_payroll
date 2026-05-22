<?php include("../adminsession.php");
$pagename = "on_duty.php";
$title = "On Duty Master";
$tblname = "on_duty_master";
$tblpkey = "on_duty_id";
$module = "On Duty Master";
$submodule = "On Duty Master List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$imgpath1 = 'uploaded/on_duty/';
if (isset($_POST['submit'])) {
    $application_date  = $obj->test_input($_POST['application_date']);
    $emp_id = $obj->test_input($_POST['emp_id']);
    $on_duty_type = $obj->test_input($_POST['on_duty_type']);
    $total_day = $obj->test_input($_POST['total_day']);

    $doc_file = $_FILES["doc_file"] ?? '';

    $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'jfif', 'xlsx'];
    $imageName = $_FILES["doc_file"]['name'];
    $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

    $form_data = array(
        "emp_id" => $emp_id,
        "type" => 'on_duty',
        "application_date" => $application_date,
        "on_duty_type" => $on_duty_type,
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
        $obj->update_record("on_duty_details", array(
            "on_duty_id" => 0,
            'unit_id' => $unitid,
            "sessionid" => $sessionid,
            'createdby' => $loginid
        ), array("on_duty_id" => $lastid, "on_duty_type" => $on_duty_type));

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
    $on_duty_type = $sqledit['on_duty_type'];

    $doc_file = $sqledit['doc_file'];
    $img = "";
} else {
    $application_date = date('Y-m-d');
    $emp_id = "";
    $on_duty_type = "On Duty";

    $doc_file = "";
    $img = "doc_file";
}
$total_day  = $obj->getvalfield("on_duty_details", "count(*)", "on_duty_id='$keyvalue' and unit_id='$unitid' and createdby='$loginid'");

if (isset($_POST['on_duty_details_idd'])) {
    $on_duty_details_id = $_POST['on_duty_details_idd'];
    $total_day  = $obj->getvalfield("on_duty_details", "count(*)", "on_duty_id='$on_duty_details_id' and unit_id='$unitid' and createdby='$loginid'");

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
                                                <h5 class="card-title mb-0"> <?= $module; ?> <a href="on_duty_list.php" class="float-end btn btn-primary btn-sm">List</a></h5>
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
                                            <select class="form-select form-select-sm chosen-select" name="emp_id" id="emp_id">
                                                <option value="">Select Employee</option>
                                                <?php
                                                //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?> <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('emp_id').value = '<?= $emp_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="on_duty_type" class="form-label">On Duty Type<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="on_duty_type" id="on_duty_type">
                                                <option value="Outdoor Duty">Outdoor Duty</option>
                                                <option value="Missed Punch">Missed Punch</option>
                                                <option value="Official Travel">Official Travel</option>
                                            </select>
                                            <script>
                                                document.getElementById('on_duty_type').value = '<?= $on_duty_type; ?>';
                                            </script>
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
                                            <div class="table-responsive">
                                                <table class="display table table-sm table-bordered" style="width:100%">
                                                    <thead>
                                                        <tr class="table-primary">
                                                            <th>Sr No.</th>
                                                            <th>Date</th>
                                                            <th>In Time</th>
                                                            <th>Out Time</th>
                                                            <th>Place </th>
                                                            <th>With Employee </th>
                                                            <th>Remarks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <td>#.</td>
                                                        <td>
                                                            <input type="date" id="date" class="form-control form-control-sm">
                                                        </td>
                                                        <td>
                                                            <input type="time" id="intime" class="form-control form-control-sm">
                                                        </td>
                                                        <td>
                                                            <input type="time" id="outtime" class="form-control form-control-sm">
                                                        </td>
                                                        <td>
                                                            <input type="text" id="place" class="form-control form-control-sm">
                                                        </td>
                                                        <td>
                                                            <input type="text" id="with_employee" class="form-control form-control-sm">
                                                        </td>
                                                        <td>
                                                            <input type="text" id="remark" class="form-control form-control-sm">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-success" onclick="save_duty_details();" id="ajax_btn">Add</button>
                                                        </td>
                                                    </tbody>
                                                    <tbody id="fetch_duty_details">
                                                    </tbody>
                                                </table>
                                            </div>
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
            fetch_duty_details()
        });

        function funDel(id) {
            $('#deleteRecordModal').modal('show');
            tblname = 'on_duty_details';
            tblpkey = 'on_duty_details_id';
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
                                on_duty_details_idd: keyvalue
                            },
                            success: function(res) {
                                let data = JSON.parse(res);
                                $('#total_day').val(data.total_days);
                            }
                        });
                        fetch_duty_details()
                    }
                });
                $('#deleteRecordModal').modal('hide');
            });
        };

        function validateForm() {

            // 🔹 Step 1: basic required field validation
            if (!checkinputmaster('application_date,emp_id,on_duty_type')) {
                return false;
            }

            // 🔹 Step 2: total_day validation
            let totalDay = document.getElementById("total_day").value;

            if (totalDay === '' || parseInt(totalDay) <= 0) {
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

        function save_duty_details() {
            const date = $('#date').val();
            const intime = $('#intime').val();
            const outtime = $('#outtime').val();
            const place = $('#place').val();
            const with_employee = $('#with_employee').val();
            const remark = $('#remark').val();
            const emp_id = $('#emp_id').val();
            const keyvalue = '<?= $keyvalue; ?>';
            if (emp_id == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required',
                    text: 'Please Select Employee'
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
                    emp_id: emp_id,
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
    </script>
</body>

</html>