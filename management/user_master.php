<?php include("../adminsession.php");
$pagename = "user_master.php";
$title = "USER MASTER";
$tblname = "user";
$tblpkey = "userid";
$module = "User Master";
$submodule = "User Master List";
$btn_name = "Save";
$type = (isset($_GET['type'])) ? $obj->test_input($_GET['type']) : '';

if (isset($_GET['userid'])) {
    $keyvalue = $_GET['userid'];
    $old_uid = $_GET['userid'];
} else {
    $keyvalue = 0;
    $old_uid = 0;
}
if (isset($_GET['action']))
    $action = addslashes(trim($_GET['action']));
else
    $action = "";

if ($type == 'copy') {
    $old_uid = 0;
}

if (isset($_POST['submit'])) {

    $username  = $obj->test_input($_POST['username']);
    $pri_userid  = $obj->test_input($_POST['pri_userid']);
    $unit_id = isset($_POST['unit_id']) ? implode(",", $_POST['unit_id']) : '';
    $password = $obj->test_input($_POST['password']);
    $usertype1 = $obj->test_input($_POST['usertype1']);
    $fullname = $obj->test_input($_POST['fullname']);
    $mobile = $obj->test_input($_POST['mobile']);
    $email = $obj->test_input($_POST['email']);
    $status1 = $obj->test_input($_POST['status1']);

    $count = $obj->getvalfield($tblname, "count(*)", "username='$username' and $tblpkey!='$old_uid' and mobile='$mobile'");

    $form_data = array(
        "unit_id" => $unit_id,
        "session_id"   => $sessionid,
        "username" => $username,
        "password" => $password,
        "usertype  " => $usertype1,
        "fullname" => $fullname,
        "mobile  " => $mobile,
        "email" => $email,
        "status" => $status1,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress
    );
    if ($count > 0) {
        $action = 4;
        $process = "duplicate";
    } else {
        if ($old_uid == 0) {
            $form_data["createdate"] = $createdate;
            // print_r($form_data);
            // die;
            $lastid = $obj->insert_record_lastid($tblname, $form_data);
            $action = 1;
            $process = "insert";

            if (!empty($pri_userid)) {

                // type decide karo
                if ($usertype1 == 'management' || $usertype1 == 'super_management') {
                    $type = 'mngmt';
                } else {
                    $type = 'hrms';
                }

                // old user ke privileges fetch karo
                $privileges = $obj->executequery("
                    SELECT * FROM privilage_setting 
                    WHERE userid = '$pri_userid'
                ");

                foreach ($privileges as $row) {

                    $priv_data = array(
                        "type" => $type,
                        "userid" => $lastid,
                        "page_id" => $row['page_id'],
                        "pagedit" => $row['pagedit'],
                        "pageview" => $row['pageview'],
                        "pagedel" => $row['pagedel'],
                        "page_add" => $row['page_add'],
                        "page_print" => $row['page_print'],
                        "page_approve" => $row['page_approve'],
                        "page_special" => $row['page_special'],
                        "privilage" => $row['privilage'],
                        "createdby" => $loginid,
                        "ipaddress" => $ipaddress,
                        "createdate" => $createdate
                    );

                    $obj->insert_record("privilage_setting", $priv_data);
                }
            }
        } else {
            $form_data["lastupdated"] = $createdate;
            $where = array($tblpkey => $old_uid);
            $obj->update_record($tblname, $where, $form_data);
            if (!empty($pri_userid)) {

                // type decide karo
                if ($usertype1 == 'management' || $usertype1 == 'super_management') {
                    $type = 'mngmt';
                } else {
                    $type = 'hrms';
                }

                $where = array('userid' => $old_uid);
                $obj->delete_record('privilage_setting', $where);

                // old user ke privileges fetch karo
                $privileges = $obj->executequery("
                    SELECT * FROM privilage_setting 
                    WHERE userid = '$pri_userid'
                ");

                foreach ($privileges as $row) {

                    $priv_data = array(
                        "type" => $type,
                        "userid" => $old_uid,
                        "page_id" => $row['page_id'],
                        "pagedit" => $row['pagedit'],
                        "pageview" => $row['pageview'],
                        "pagedel" => $row['pagedel'],
                        "page_add" => $row['page_add'],
                        "page_print" => $row['page_print'],
                        "page_approve" => $row['page_approve'],
                        "page_special" => $row['page_special'],
                        "privilage" => $row['privilage'],
                        "createdby" => $loginid,
                        "ipaddress" => $ipaddress,
                        "createdate" => $createdate
                    );

                    $obj->insert_record("privilage_setting", $priv_data);
                }
            }
            $action = 2;
            $process = "updated";
        }
    }

    echo "<script>location='$pagename?action=$action'</script>";
}


if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $username =  $sqledit['username'];
    $password  =  $sqledit['password'];
    $unit_id  =  $sqledit['unit_id'];
    $usertype1 =  $sqledit['usertype'];
    $fullname =  $sqledit['fullname'];
    $mobile =  $sqledit['mobile'];
    $status1 =  $sqledit['status'];
    $email =  $sqledit['email'];
} else {
    $username = "";
    $password  = "";
    $usertype1 =  "";
    $fullname = "";
    $mobile = "";
    $status1 = "";
    $email =  "";
    $unit_id = "";
}


if (isset($_POST['user_typee'])) {
    $user_type = $_POST['user_typee'];
    $options = "<option value=''>Select</option>";
    $selected = "";
    if ($user_type != "") {

        if ($user_type == 'management') {
            $res = $obj->executequery("Select * from user where usertype='management' order by username asc");
        } else {
            $res = $obj->executequery("Select * from user where usertype NOT IN ('management', 'super_management') order by username asc");
        }

        foreach ($res as $row) {
            $unit_name = $obj->getvalfield("unit_master", "unit_name", "unit_id='$row[unit_id]'");
            $options .= "<option value='" . $row['userid'] . "'>" . $row['username'] . " / " . $unit_name . "  </option>";
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
                    <form method="post" action="">
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> User Master</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-12 col-lg-3">
                                            <label for="bill_no" class="form-label"> User Type<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-control form-control-sm chosen-select" name="usertype1" id="usertype1" onchange="toggleUnitField();get_user_details(this.value);">
                                                <option value="">Select User Type</option>
                                                <option value="user">User</option>
                                                <option value="admin">Admin</option>
                                                <option value="management">Management</option>
                                                <option value="super_management">Super Management</option>
                                            </select>
                                            <script>
                                                document.getElementById('usertype1').value = '<?php echo $usertype1 ?>';
                                            </script>
                                        </div>
                                        <div class="mb-3 col-12 col-lg-3" id="unit_id_div">
                                            <label for="bill_no" class="form-label">Unit Name<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="unit_id[]" id="unit_id" multiple>
                                                <?php
                                                $slno = 1;
                                                $res = $obj->executequery("select * from unit_master order by unit_id desc");
                                                foreach ($res as $row) { ?>
                                                    <option value="<?php echo $row['unit_id'] ?>"><?php echo $row['unit_name'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php $unit_ids = explode(",", $unit_id); ?>
                                            <script>
                                                let selectedUnits = <?php echo json_encode($unit_ids); ?>;
                                                let select = document.getElementById('unit_id');

                                                for (let option of select.options) {
                                                    if (selectedUnits.includes(option.value)) {
                                                        option.selected = true;
                                                    }
                                                }
                                            </script>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="username" class="form-label">User Name<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="username" name="username" class="form-control form-control-sm" placeholder="Enter User Name" value="<?php echo $username ?>" autocomplete="off" />
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="password" class="form-label"> Password <span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="password" name="password" class="form-control form-control-sm" placeholder="Enter Password " value="<?php echo $password ?>" autocomplete="off" />
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label for="fullname" class="form-label">Full Name<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="fullname" name="fullname" class="form-control form-control-sm" placeholder="Enter Full Name" value="<?php echo $fullname ?>" autocomplete="off" />
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="mobile" class="form-label">Mobile No.<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="mobile" name="mobile" class="form-control form-control-sm" placeholder="Enter Mobile No." value="<?php echo $mobile ?>" onkeypress="numberOnly(event)" autocomplete="off" maxlength="10" />
                                        </div>
                                        <div class="mb-3 col-12 col-lg-3">
                                            <label for="enable" class="form-label">Status<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-control form-control-sm chosen-select" name="status1" id="status1">
                                                <option value="">Select Status</option>
                                                <option value="1">Enable</option>
                                                <option value="0">Disable</option>
                                            </select>
                                            <script>
                                                document.getElementById('status1').value = '<?php echo $status1 ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="email" class="form-label">Email<span class="text-danger fw-bold"></span></label>
                                            <input type="email" id="email" name="email" class="form-control form-control-sm" placeholder="Enter Email" value="<?php echo $email ?>" autocomplete="off" />
                                        </div>

                                         
                                            <div class="col-md-3" id="privilage_div">
                                                <strong> <label for="pri_userid">Privilege From User<span class="text-danger fw-bold"> </span></label></strong>
                                                <div class="input-group mb-3">
                                                    <select name="pri_userid" id="pri_userid" class="chosen-select form-control">
                                                        <option value="">Select</option>

                                                    </select>
                                                </div>
                                            </div>
                                       
                                        <div class="col-lg-3 mb-3 mt-2">
                                            <br>
                                            <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> " onClick="return validateForm()">
                                            <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> <span class="text-danger"></span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="buttons-datatables" class="display table table-sm table-bordered" style="width:100%">
                                        <thead>
                                            <tr class="table-primary">
                                                <th>Sr No.</th>
                                                <th>Unit Name</th>
                                                <th>User Name</th>
                                                <th>Password</th>
                                                <th>User Type</th>
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th>Email ID</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php
                                                $slno = 1;
                                                $res = $obj->executequery("select * from $tblname   order by $tblpkey desc");
                                                foreach ($res as $row) {
                                                    $unit_ids = $row['unit_id'];
                                                    $unit_name = '';
                                                    if (!empty($unit_ids)) {
                                                        $unit_name = $obj->getvalfield(
                                                            "unit_master",
                                                            "GROUP_CONCAT(unit_name SEPARATOR ', ')",
                                                            "unit_id IN ($unit_ids)"
                                                        );
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?php echo $slno++ ?></td>
                                                    <td><?php echo $unit_name; ?></td>
                                                    <td><?php echo $row["username"]; ?></td>
                                                    <td><?php echo $row["password"]; ?></td>
                                                    <td><?php echo ucfirst($row["usertype"]); ?></td>
                                                    <td><?php echo $row["fullname"]; ?></td>
                                                    <td><?php echo $row["mobile"]; ?></td>
                                                    <td><?php echo $row["email"]; ?></td>
                                                    <td><?php echo ($row["status"] == 1) ? "Enable" : "Disable"; ?></td>
                                                    <td>

                                                        <ul class="list-inline hstack gap-2 mb-0">

                                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Copy">


                                                                <a href="<?php echo $pagename ?>?<?php echo $tblpkey ?>=<?php echo $row['userid']; ?>&type=copy" class="edit-item-btn">
                                                                    <i class="ri-file-copy-fill align-bottom text-primary"></i>
                                                                </a>
                                                            </li>

                                                            <li class="list-inline-item " data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                                <a href="<?php echo $pagename ?>?<?php echo $tblpkey ?>=<?php echo $row['userid']; ?>" class="edit-item-btn"><i class="ri-pencil-fill align-bottom text-success"></i></a>
                                                            </li>


                                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                                <a class="remove-item-btn" type="button" onclick="funDel(<?php echo $row['userid']; ?>);">
                                                                    <i class="ri-delete-bin-fill align-bottom text-danger"></i>
                                                                </a>
                                                            </li>


                                                        </ul>

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
            });
            toggleUnitField();
            if ('<?= $keyvalue ?>' > 0) {
                get_user_details('<?= $usertype1 ?>');
            }

        });

        function funDel(id) {
            tblname = '<?php echo $tblname; ?>';
            tblpkey = '<?php echo $tblpkey; ?>';
            pagename = '<?php echo $pagename; ?>';
            submodule = '<?php echo $submodule; ?>';

            if (confirm("Are you sure! You want to delete this record.")) {
                jQuery.ajax({
                    type: 'POST',
                    url: 'ajax/delete_master.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' + submodule + '&pagename=' + pagename,
                    dataType: 'html',
                    success: function(data) {
                        location = '<?php echo $pagename . "?action=3"; ?>';
                    }
                }); //ajax close
            } //confirm close
        } //fun close

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

        function toggleUnitField() {
            let userType = document.getElementById('usertype1').value;
            let unitDiv = document.getElementById('unit_id_div');
            let unitSelect = document.getElementById('unit_id');
            if (userType === 'management' || userType === 'super_management') {
                unitDiv.style.display = 'none';

                for (let option of unitSelect.options) {
                    option.selected = false;
                }
            } else {
                unitDiv.style.display = 'block';
            }
            if (typeof $ !== "undefined") {
                $('#unit_id').trigger("chosen:updated");
            }

            if (userType === 'super_management') {
                document.getElementById('privilage_div').style.display = 'none';
            } else {
                document.getElementById('privilage_div').style.display = 'block';
            }
        }

        function validateForm() {
            let userType = document.getElementById('usertype1').value;
            let unitSelect = document.getElementById('unit_id');
            let isValid = checkinputmaster('usertype1,username,password,fullname,mobile,status1');
            if (!isValid) return false;
            if (userType === 'user' || userType === 'admin') {
                let selected = [];
                for (let option of unitSelect.options) {
                    if (option.selected && option.value !== "") {
                        selected.push(option.value);
                    }
                }
                if (selected.length === 0) {
                    alert("Please select at least one Unit");
                    return false;
                }
            }
            return true;
        }

        function get_user_details(user_type) {
            $.ajax({
                type: "POST",
                url: '',
                data: {
                    user_typee: user_type,
                },

                success: function(data) {
                    $('#pri_userid').html(data).trigger("change.select2");
                }
            });
        }
    </script>
</body>

</html>