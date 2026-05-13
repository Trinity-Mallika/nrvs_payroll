<?php include("../adminsession.php");
$pagename = "emp_bank_details.php";
$title = "Employee Bank Details";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Employee Bank Details";
$submodule = "Employee Bank Details";
$btn_name = "Save";

$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

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
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> <a href="employee_master.php" class="float-end btn btn-primary btn-sm ms-4">Add</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="auto-scroll-wrapper">
                                    <div class="table-responsive">

                                        <table id="buttons-datatables" class="display table table-sm table-bordered" style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>Sr No.</th>
                                                    <th>Employee Code</th>
                                                    <th>Employee Name</th>
                                                    <th>Mobile No</th>
                                                    <th>Department</th>
                                                    <th>Designation</th>
                                                    <th>Bank Name</th>
                                                    <th>Account Holder Name</th>
                                                    <th>Account No.</th>
                                                    <th>IFSC Code</th>
                                                    <th>Is Active</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;

                                                $res = $obj->executequery("SELECT bnk.* ,dm.department_name,dem.designation, em.first_name,em.resign_status,em.last_working_date, em.mobile_no,em.emp_code,em.designation_id,em.department_id,bm.bank_name from emp_bank_details as bnk left join employee_master em on bnk.emp_id=em.emp_id left join bank_master bm on bnk.bank_id=bm.bank_id LEFT JOIN department_master dm ON em.department_id=dm.department_id LEFT JOIN designation_master dem ON em.designation_id=dem.designation_id where em.unit_id='$unitid' and (em.resign_status != '1' OR (em.resign_status = '1' AND em.last_working_date >= CURDATE()))  ORDER BY em.emp_code asc");


                                                foreach ($res as $row) {

                                                ?>
                                                    <tr id="tr_<?= $row["emp_id"]; ?>">
                                                        <td><?php echo $slno++; ?></td>

                                                        <td><?= $row["emp_code"]; ?></td>
                                                        <td> <?= ucfirst($row['first_name'] ?? ''); ?> </td>
                                                        <td><?php echo $row['mobile_no']; ?></td>
                                                        <td><?php echo $row['department_name']; ?></td>
                                                        <td><?php echo $row['designation']; ?></td>
                                                        <td><?php echo $row['bank_name']; ?></td>
                                                        <td><?php echo $row['acc_holder_name']; ?></td>
                                                        <td><?php echo $row['account_no']; ?></td>
                                                        <td><?php echo $row['ifsc_code']; ?></td>
                                                        <td><?= $row['is_active'] == 1 ? 'Active' : 'Inactive'; ?></td>
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
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&imgpath=' + imgpath + '&submodule=' + submodule + '&pagename=' + pagename,
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
    </script>
</body>

</html>