<?php include("../adminsession.php");
$pagename = "emp_list.php";
$title = "Employee List";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Employee List";
$submodule = "Employee List";
$btn_name = "Save";
$imgpath = "uploaded/emp_documents/";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';


if (isset($_POST['change_password_ajax'])) {
    $emp_id       = $_POST['emp_id'];
    $new_password = $_POST['new_password'];

    $update = $obj->update_record(
        "employee_master",
        ["emp_id" => $emp_id],
        ["password" => $new_password, "lastupdated" => $createdate, "updatedby" => $loginid]
    );

    echo 1;
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
                <!-- #region -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> <a
                                                    href="emp_bank_details.php"
                                                    class="float-end btn btn-primary btn-sm ms-2">Export Bank Excel</a>
                                                <a href="employee_master.php"
                                                    class="float-end btn btn-primary btn-sm ms-4">Add</a>
                                            </h5>
                                        </div>
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
                                                    <th>Sr No.</th>
                                                    <th>Actions</th>
                                                    <th>Employee Code</th>
                                                    <th>Employee Name</th>
                                                    <th>Father’s Name</th>
                                                    <th>Mobile No</th>
                                                    <th>Passward</th>
                                                    <th>Department</th>
                                                    <th>Designation</th>
                                                    <th>Date of Joining</th>
                                                    <th>Date of Birth</th>
                                                    <th>Is Active</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;
                                                $res = $obj->executequery("SELECT em.*,dm.department_name,dem.designation,gm.grade_name,cu.fullname as created_name,cu.username as created_username,cu.mobile as created_mobile, uu.fullname as updated_name, uu.username as updated_username,
                                                        uu.mobile as updated_mobile FROM $tblname as em LEFT JOIN department_master dm ON em.department_id=dm.department_id LEFT JOIN designation_master dem ON em.designation_id=dem.designation_id LEFT JOIN grade_master gm ON em.grade_id=gm.grade_id LEFT JOIN user cu ON em.createdby = cu.userid LEFT JOIN user uu ON em.updatedby = uu.userid where em.unit_id='$unitid' AND (em.resign_status != '1' OR (em.resign_status = '1' AND em.last_working_date >= CURDATE()))  ORDER BY em.emp_code asc ");
                                                foreach ($res as $row) {

                                                ?>
                                                    <tr id="tr_<?= $row["emp_id"]; ?>" data-details="
                                    <div style='background:#dafced; padding:4px;'>
                                    <?php if (!empty($row['created_name'])): ?>
                                    Added by (User: <?= $row['created_name'] ?>,
                                    Username: <?= $row['created_username'] ?>,
                                    Mobile: <?= $row['created_mobile'] ?>,
                                     Date: <?= $row['createdate'] ?>,)<br>
                                    <?php endif; ?>

                                    <?php if (!empty($row['updated_name'])): ?>
                                    Last Edited by (User: <?= $row['updated_name'] ?>,
                                    Username: <?= $row['updated_username'] ?>,
                                    Mobile: <?= $row['updated_mobile'] ?>,
                                    Date: <?= $row['lastupdated'] ?>) 
                                    <?php endif; ?>
                                     </div>
                                ">
                                                        <td class="details-control text-center" style="cursor:pointer;">
                                                            <?php echo $slno++; ?>
                                                            <i class="ri-add-circle-fill text-primary"></i>
                                                        </td>
                                                        <td>
                                                            <ul class="list-inline hstack gap-2 mb-0">
                                                                <?php $chkedit = $obj->check_editBtn($pagename, $loginid);
                                                                if ($chkedit == 1) {  ?>
                                                                    <li class="list-inline-item " data-bs-toggle="tooltip"
                                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                                        title="Edit">
                                                                        <a href="employee_master.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>"
                                                                            class="edit-item-btn">
                                                                            <i
                                                                                class="ri-pencil-fill align-bottom text-success"></i>
                                                                        </a>
                                                                    </li>
                                                                <?php }
                                                                $chkdel = $obj->check_delBtn($pagename, $loginid);
                                                                if ($chkdel == 1) {  ?>
                                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                                        title="Delete">
                                                                        <a class="remove-item-btn" type="button"
                                                                            onclick="funDel('<?php echo $row[$tblpkey]; ?>');">
                                                                            <i
                                                                                class="ri-delete-bin-fill align-bottom text-danger"></i>
                                                                        </a>
                                                                    </li>
                                                                <?php }
                                                                $chkprint = $obj->check_printBtn($pagename, $loginid);
                                                                if ($chkprint == 1) {  ?>
                                                                    <li class="list-inline-item " data-bs-toggle="tooltip"
                                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                                        title="Print">
                                                                        <a href="employee_pdf.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>"
                                                                            class="edit-item-btn" target="_blank">
                                                                            <i class="ri-printer-fill align-bottom text-primary"
                                                                                title="Print"></i>
                                                                        </a>
                                                                    </li>
                                                                    <!-- <li class="list-inline-item " data-bs-toggle="tooltip"
                                                                data-bs-trigger="hover" data-bs-placement="top"
                                                                title="Print">
                                                                <a href="employee_concern_pdf.php?emp_id=<?php echo $row[$tblpkey]; ?>"
                                                                    class="edit-item-btn" target="_blank">
                                                                    
                                                                    <i   class="ri-file-text-fill text-success"   title="Print employee concern"></i>
                                                                </a>
                                                            </li> -->

                                                                    <li class="list-inline-item " data-bs-toggle="tooltip"
                                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                                        title="Print">
                                                                        <a href="emp_idcard.php?emp_id=<?= $row[$tblpkey]; ?>"
                                                                            class="edit-item-btn" target="_blank">

                                                                            <i class="ri-file-text-fill text-success" title="Print employee concern"></i>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </td>
                                                        <td><?= $row["emp_code"]; ?></td>
                                                        <td> <?= ucfirst($row['first_name'] ?? ''); ?>
                                                            <?= ucfirst($row['last_name'] ?? ''); ?></td>
                                                        <td><?php echo $row["father_name"]; ?></td>
                                                        <td><?php echo $row['mobile_no']; ?></td>
                                                        <td>
                                                            <span id="pass_text_<?= $row['emp_id']; ?>">
                                                                <?php echo $row['password']; ?>
                                                            </span>



                                                            <!-- Update Icon -->
                                                            <a href="javascript:void(0)"
                                                                onclick="openPasswordModal(
                                                            '<?= $row['emp_id']; ?>',
                                                            '<?= $row['password']; ?>'
                                                        )"
                                                                title="Update Password">

                                                                <i class="ri-edit-2-fill text-success fs-16"></i>
                                                            </a>
                                                        </td>
                                                        <td><?php echo $row['department_name']; ?></td>
                                                        <td><?php echo $row['designation']; ?></td>
                                                        <td><?php echo $obj->dateformatindia($row["date_of_joining"]); ?>
                                                        </td>
                                                        <td><?php echo $obj->dateformatindia($row["dob"]); ?></td>
                                                        <td><?php echo $row['is_active'] == 1 ? 'Yes' : 'No'; ?></td>


                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
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
    <!-- Change Password Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Change Password
                    </h5>

                    <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden"
                        name="emp_id"
                        id="modal_emp_id">

                    <!-- Old Password -->
                    <div class="mb-3">
                        <label class="form-label">
                            Old Password
                        </label>

                        <input type="text"
                            class="form-control"
                            id="old_password"
                            readonly>
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label class="form-label">
                            New Password
                        </label>
                        <input type="text"
                            class="form-control"
                            id="new_password"
                            required>
                    </div>
                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label class="form-label">
                            Confirm Password
                        </label>
                        <input type="text"
                            class="form-control"
                            id="confirm_password"
                            required>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit"
                        name="change_password"
                        class="btn btn-success" onclick="change_pass();">

                        <i class="ri-save-line"></i>
                        Update Password
                    </button>

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

        function funDel(id) {
            $('#deleteRecordModal').modal('show');
            tblname = '<?php echo $tblname; ?>';
            tblpkey = '<?php echo $tblpkey; ?>';
            imgpath = '<?php echo $imgpath; ?>';
            pagename = '<?php echo $pagename; ?>';
            submodule = '<?php echo $submodule; ?>';
            $('#delete-record').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/delete_master_emp.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&imgpath=' +
                        imgpath + '&submodule=' + submodule + '&pagename=' + pagename,
                    dataType: 'html',
                    success: function(data) {
                        $("#tr_" + id).hide();
                        // alert(data);
                        // location.reload();
                    }
                });
                $('#deleteRecordModal').modal('hide');
            });
        };

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

        function openPasswordModal(emp_id, password) {
            $('#modal_emp_id').val(emp_id);
            $('#old_password').val(password);

            $('#new_password').val('');
            $('#confirm_password').val('');

            $('#passwordModal').modal('show');
        }

        function change_pass() {
            let emp_id = $('#modal_emp_id').val();
            let newPass = $('#new_password').val();
            let confirmPass = $('#confirm_password').val();

            // blank check
            if (newPass == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required',
                    text: 'Please Enter New Password'
                });
                $('#new_password').focus();
                return false;
            }

            // confirm blank check
            if (confirmPass == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required',
                    text: 'Please Enter Confirm Password'
                });
                $('#confirm_password').focus();
                return false;
            }

            if (newPass != confirmPass) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'New Password and Confirm Password must be same'
                });

                $('#confirm_password').focus();
                return false;
            }

            // AJAX
            $.ajax({
                url: '',
                type: 'POST',
                data: {
                    change_password_ajax: 1,
                    emp_id: emp_id,
                    new_password: newPass
                },

                beforeSend: function() {
                    Swal.fire({
                        title: 'Please Wait...',
                        text: 'Updating Password',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },

                success: function(response) {
                    Swal.close();
                    if (response == 1) {
                        $('#pass_text_' + emp_id).text(newPass);

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Password Updated Successfully'
                        });
                        $('#passwordModal').modal('hide');
                        $('#new_password').val('');
                        $('#confirm_password').val('');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Password Not Updated'
                        });
                    }
                }

            });

        }
    </script>
</body>

</html>