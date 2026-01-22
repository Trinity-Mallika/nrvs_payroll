<?php include("../adminsession.php");
$pagename = "emp_list.php";
$title = "Employee Report";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Employee Report";
$submodule = "Employee Report";
$btn_name = "Save";
$imgpath = "uploaded/emp_documents/";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = ' and 1=1';
if (isset($_GET['designation_id'])) {
    $designation_id = $obj->test_input($_GET['designation_id']);
    if ($designation_id != '') {
        $crit .= " and designation_id='$designation_id'";
    }
} else {
    $designation_id = "";
};
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and department_id='$department_id'";
    }
} else {
    $department_id = "";
};
if (isset($_GET['grade_id'])) {
    $grade_id = $obj->test_input($_GET['grade_id']);
    if ($grade_id != '') {
        $crit .= " and grade_id='$grade_id'";
    }
} else {
    $grade_id = "";
};
if (isset($_GET['shift_id'])) {
    $shift_id = $obj->test_input($_GET['shift_id']);
    if ($shift_id != '') {
        $crit .= " and shift_id='$shift_id'";
    }
} else {
    $shift_id = "";
};
if (isset($_GET['gender'])) {
    $gender = $obj->test_input($_GET['gender']);
    if ($gender != '') {
        $crit .= " and gender='$gender'";
    }
} else {
    $gender = "";
};

$dob = $_GET['dob'] ?? '';
if ($dob != '') {
    $day   = date('d', strtotime($dob));
    $month = date('m', strtotime($dob));

    $crit .= " AND DAY(dob) = '$day' AND MONTH(dob) = '$month'";
}

$anniversary_date = $_GET['anniversary_date'] ?? '';
if ($anniversary_date != '') {
    $day   = date('d', strtotime($anniversary_date));
    $month = date('m', strtotime($anniversary_date));

    $crit .= " AND DAY(anniversary_date) = '$day' AND MONTH(anniversary_date) = '$month'";
}

$work_anniversary = $_GET['work_anniversary'] ?? '';
if ($work_anniversary != '') {
    $day   = date('d', strtotime($work_anniversary));
    $month = date('m', strtotime($work_anniversary));
    $crit .= " AND DAY(date_of_joining) = '$day' AND MONTH(date_of_joining) = '$month'";
}

$sixty_plus_age = $_GET['sixty_plus_age'] ?? '0';
if ($sixty_plus_age == 1) {
    $crit .= " AND TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= 60";
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
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a href="employee_master.php" class="float-end btn btn-primary btn-sm">Add New</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">


                                        <div class="col-lg-3 mb-3">
                                            <label for="department_id" class="form-label">Department Name<span class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="department_id" id="department_id">
                                                <option value="">Select</option>
                                                <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_id asc");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['department_id']; ?>">
                                                        <?= $key['department_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('department_id').value =
                                                    '<?= $department_id; ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label for="designation_id" class="form-label">Designation<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="designation_id" id="designation_id">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("Select * from designation_master where unit_id='$unitid' order by designation asc");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['designation_id']; ?>">
                                                        <?= $key['designation']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('designation_id').value =
                                                    '<?= $designation_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="grade_id" class="form-label">Grade<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="grade_id" id="grade_id">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("Select * from grade_master where unit_id='$unitid' order by grade_name asc");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['grade_id']; ?>">
                                                        <?= $key['grade_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('grade_id').value = '<?= $grade_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="shift_id" class="form-label">Shift Type<span
                                                    class="text-danger fw-bold"> </span><span
                                                    class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="shift_id" id="shift_id">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("Select * from shift_master where unit_id='$unitid' order by shift_name asc");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['shift_id']; ?>">
                                                        <?= $key['shift_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('shift_id').value = '<?= $shift_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Gender</label>
                                            <select class="form-select form-select-sm" name="gender"
                                                id="gender">
                                                <option value="">Select</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <script>
                                                document.getElementById('gender').value = '<?= $gender ?>'
                                            </script>
                                        </div>



                                        <div class="col-lg-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary add-btn" value="Search">
                                            <a href="<?php echo $pagename ?>" class="btn btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> </h5>
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
                                                <th>Employee Code</th>
                                                <th>Employee Name</th>
                                                <th>Father’s Name</th>
                                                <th>Gender</th>
                                                <th>Date of Birth</th>
                                                <th>Blood Group</th>
                                                <th>Email</th>
                                                <th>Department</th>
                                                <th>Designation</th>
                                                <th>Grade</th>
                                                <th>Date of Joining</th>
                                                <th>Opening Balance</th>
                                                <th>Opening Date</th>
                                                <th>Job Location</th>
                                                <th>Shift Hours</th>
                                                <th>Basic Salary</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $slno = 1;
                                            $res = $obj->executequery("SELECT * FROM $tblname where unit_id='$unitid' $crit ORDER BY $tblpkey desc ");
                                            foreach ($res as $row) {
                                                $designation = $obj->getvalfield("designation_master", "designation", "designation_id='$row[designation_id]'");

                                                $department_name = $obj->getvalfield("department_master", "department_name", "department_id='$row[department_id]'");

                                                $grade_name = $obj->getvalfield("grade_master", "grade_name", "grade_id='$row[grade_id]'");
                                            ?>
                                                <tr>
                                                    <td><?php echo $slno++; ?></td>
                                                    <td><?= $row["emp_code"]; ?></td>
                                                    <td> <?= ucfirst($row['first_name'] ?? ''); ?> <?= ucfirst($row['last_name'] ?? ''); ?></td>
                                                    <td><?php echo $row["father_name"]; ?></td>
                                                    <td><?php echo $row["gender"]; ?></td>
                                                    <td><?php echo $obj->dateformatindia($row["dob"]); ?></td>
                                                    <td><?php echo $row["blood_group"]; ?></td>
                                                    <td><?php echo $row["email_id"]; ?></td>
                                                    <td><?php echo $department_name; ?></td>
                                                    <td><?php echo $designation; ?></td>
                                                    <td><?php echo $grade_name; ?></td>
                                                    <td><?php echo $obj->dateformatindia($row["date_of_joining"]); ?></td>
                                                    <td><?php echo $row["opening_balance"]; ?></td>
                                                    <td><?php echo $obj->dateformatindia($row["opening_date"]); ?></td>
                                                    <td><?php echo $row["job_location"]; ?></td>
                                                    <td><?php echo $row["shift_id"]; ?> Hrs</td>
                                                    <td><?php echo $row["basic_salary"]; ?></td>

                                                    <td>
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <li class="list-inline-item " data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                                <a href="employee_master.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>" class="edit-item-btn">
                                                                    <i class="ri-pencil-fill align-bottom text-success"></i>
                                                                </a>
                                                            </li>
                                                            <li class="list-inline-item " data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Print">
                                                                <a href="employee_pdf.php?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>" class="edit-item-btn" target="_blank">
                                                                    <i class="ri-printer-fill align-bottom text-primary" title="Print"></i>
                                                                </a>
                                                            </li>
                                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                                <a class="remove-item-btn" type="button" onclick="funDel('<?php echo $row[$tblpkey]; ?>');">
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
                        // alert(data);
                        location = '<?php echo $pagename; ?>';
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