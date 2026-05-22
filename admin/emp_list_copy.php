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
        $crit .= " and em.designation_id='$designation_id'";
    }
} else {
    $designation_id = "";
};
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and em.department_id='$department_id'";
    }
} else {
    $department_id = "";
};
if (isset($_GET['grade_id'])) {
    $grade_id = $obj->test_input($_GET['grade_id']);
    if ($grade_id != '') {
        $crit .= " and em.grade_id='$grade_id'";
    }
} else {
    $grade_id = "";
};
if (isset($_GET['shift_id'])) {
    $shift_id = $obj->test_input($_GET['shift_id']);
    if ($shift_id != '') {
        $crit .= " and em.shift_id='$shift_id'";
    }
} else {
    $shift_id = "";
};
if (isset($_GET['gender'])) {
    $gender = $obj->test_input($_GET['gender']);
    if ($gender != '') {
        $crit .= " and em.gender='$gender'";
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

$fieldMap = [
    // 1 => ['label' => 'Mobile Number',     'key' => 'mobile_no'],
    2 => ['label' => 'Gender',         'key' => 'aadhar_no'],
    3 => ['label' => 'Date of Birth',    'key' => 'basic_salary'],
    4 => ['label' => 'Age',             'key' => 'grade_name'],
    4 => ['label' => 'Blood Group',             'key' => 'grade_name'],
    5 => ['label' => 'Marital Status',        'key' => 'department_name'],
    6 => ['label' => 'Nationality',       'key' => 'designation'],
    7 => ['label' => 'Religion',   'key' => 'date_of_joining'],
    8 => ['label' => 'Caste',       'key' => 'job_location'],
    9 => ['label' => 'Mobile Number',        'key' => 'shift_hours'],
    10 => ['label' => 'Alternate Mobile',        'key' => 'shift_hours'],
    11 => ['label' => 'Email',        'key' => 'shift_hours'],
    12 => ['label' => 'Emergency Contact Name',        'key' => 'shift_hours'],
    13 => ['label' => 'Emergency Contact Relation',        'key' => 'shift_hours'],
    14 => ['label' => 'Emergency Contact Number',        'key' => 'shift_hours'],
    15 => ['label' => 'Present Address',        'key' => 'shift_hours'],
    16 => ['label' => 'Permanent Address',        'key' => 'shift_hours'],
    17 => ['label' => 'Aadhaar No',        'key' => 'shift_hours'],
    18 => ['label' => 'PAN No',        'key' => 'shift_hours'],
    19 => ['label' => 'Driving License',        'key' => 'shift_hours'],
    20 => ['label' => 'Passport No',        'key' => 'shift_hours'],
    21 => ['label' => 'Is Form 21',        'key' => 'shift_hours'],
    22 => ['label' => 'Identification Marks',        'key' => 'shift_hours'],
    23 => ['label' => 'Present Salary',        'key' => 'shift_hours'],
    24 => ['label' => 'Opening Leave',        'key' => 'shift_hours'],
    25 => ['label' => 'Opening Leave Date',        'key' => 'shift_hours'],
    26 => ['label' => 'Grade',        'key' => 'shift_hours'],
    27 => ['label' => 'Department',        'key' => 'shift_hours'],
    28 => ['label' => 'Designation',        'key' => 'shift_hours'],
    29 => ['label' => 'Date of Joining',        'key' => 'shift_hours'],
    30 => ['label' => 'Job Location',        'key' => 'shift_hours'],
    31 => ['label' => 'Shift Hours',        'key' => 'shift_hours'],
    32 => ['label' => 'Reporting Manager',        'key' => 'shift_hours'],
    33 => ['label' => 'Employment Type',        'key' => 'shift_hours'],
    34 => ['label' => 'Employer Name',        'key' => 'shift_hours'],
    35 => ['label' => 'Designation',        'key' => 'shift_hours'],
    36 => ['label' => 'Service Period From',        'key' => 'shift_hours'],
    37 => ['label' => 'Service Period To',        'key' => 'shift_hours'],
    38 => ['label' => 'Last Drawn Salary',        'key' => 'shift_hours'],
    39 => ['label' => 'Reason for Leaving',        'key' => 'shift_hours'],
    40 => ['label' => 'Job Responsibilities',        'key' => 'shift_hours'],
    41 => ['label' => 'Is PF',        'key' => 'shift_hours'],
    42 => ['label' => 'Is ESI',        'key' => 'shift_hours'],
    43 => ['label' => 'PF NO.',        'key' => 'shift_hours'],
    44 => ['label' => 'UAN NO.',        'key' => 'shift_hours'],
    45 => ['label' => 'ESIC Number',        'key' => 'shift_hours'],
    46 => ['label' => 'PF Joining Date',        'key' => 'shift_hours'],
    47 => ['label' => 'Emergency',        'key' => 'shift_hours'],
    48 => ['label' => 'Emergency',        'key' => 'shift_hours'],
    49 => ['label' => 'Emergency',        'key' => 'shift_hours'],
    50 => ['label' => 'Emergency',        'key' => 'shift_hours'],
    51 => ['label' => 'Emergency',        'key' => 'shift_hours'],
    52 => ['label' => 'Emergency',        'key' => 'shift_hours'],
];

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
                    <?php
                    if (isset($_GET['submit'])) {
                    ?>
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
                                                    <th>Actions</th>
                                                    <th>Employee Code</th>
                                                    <th>Employee Name</th>
                                                    <th>Father’s Name</th>
                                                    <th>Gender</th>
                                                    <th>Date of Birth</th>
                                                    <th>Blood Group</th>
                                                    <th>Email</th>
                                                    <th>Mobile No.</th>
                                                    <th>Department</th>
                                                    <th>Designation</th>
                                                    <th>Grade</th>
                                                    <th>Date of Joining</th>
                                                    <th>Opening Balance</th>
                                                    <th>Opening Date</th>
                                                    <th>Job Location</th>
                                                    <th>Shift Hours</th>
                                                    <th>Basic Salary</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;
                                                $res = $obj->executequery("SELECT em.*,dm.department_name,dem.designation,gm.grade_name FROM $tblname as em LEFT JOIN department_master dm ON em.department_id=dm.department_id LEFT JOIN designation_master dem ON em.designation_id=dem.designation_id LEFT JOIN grade_master gm ON em.grade_id=gm.grade_id where em.unit_id='$unitid' $crit ORDER BY em.emp_code asc ");
                                                foreach ($res as $row) {

                                                ?>
                                                    <tr id="tr_<?= $row["emp_id"]; ?>">
                                                        <td><?php echo $slno++; ?></td>

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
                                                        <td><?= $row["emp_code"]; ?></td>
                                                        <td> <?= ucfirst($row['first_name'] ?? ''); ?> <?= ucfirst($row['last_name'] ?? ''); ?></td>
                                                        <td><?php echo $row["father_name"]; ?></td>
                                                        <td><?php echo $row["gender"]; ?></td>
                                                        <td><?php echo $obj->dateformatindia($row["dob"]); ?></td>
                                                        <td><?php echo $row["blood_group"]; ?></td>
                                                        <td><?php echo $row["email_id"]; ?></td>
                                                        <td><?php echo $row["mobile_no"]; ?></td>
                                                        <td><?php echo $row['department_name']; ?></td>
                                                        <td><?php echo $row['designation']; ?></td>
                                                        <td><?php echo $row['grade_name']; ?></td>
                                                        <td><?php echo $obj->dateformatindia($row["date_of_joining"]); ?></td>
                                                        <td><?php echo $row["opening_balance"]; ?></td>
                                                        <td><?php echo $obj->dateformatindia($row["opening_date"]); ?></td>
                                                        <td><?php echo $row["job_location"]; ?></td>
                                                        <td><?php echo $row["shift_id"]; ?> Hrs</td>
                                                        <td><?php echo $row["basic_salary"]; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php } ?>
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