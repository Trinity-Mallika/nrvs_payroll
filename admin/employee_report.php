<?php include("../adminsession.php");
$pagename = "employee_report.php";
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
    $designation_name = $obj->getvalfield(
        "designation_master",
        "designation",
        "designation_id='$designation_id'"
    );
} else {
    $designation_id = "";
    $designation_name = "All";
};
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and em.department_id='$department_id'";
    }
    $department_name = $obj->getvalfield(
        "department_master",
        "department_name",
        "department_id='$department_id'"
    );
} else {
    $department_id = "";
    $department_name = "All";
};
if (isset($_GET['grade_id'])) {
    $grade_id = $obj->test_input($_GET['grade_id']);
    if ($grade_id != '') {
        $crit .= " and em.grade_id='$grade_id'";
    }
    $grade_name = $obj->getvalfield(
        "grade_master",
        "grade_name",
        "grade_id='$grade_id'"
    );
} else {
    $grade_id = "";
    $grade_name = "All";
};
if (isset($_GET['shift_id'])) {
    $shift_id = $obj->test_input($_GET['shift_id']);
    if ($shift_id != '') {
        $crit .= " and em.shift_id='$shift_id'";
    }

    $shift_name = $obj->getvalfield(
        "shift_master",
        "shift_name",
        "shift_id='$shift_id'"
    );
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

if (isset($_GET['resign_status'])) {
    $resign_status = $obj->test_input($_GET['resign_status']);
    if ($resign_status == '0') {
        $crit .= " and (em.resign_status != '1' OR (em.resign_status = '1' AND em.last_working_date >= CURDATE()))";
    } else {
        $crit .= " and em.resign_status = '1'";
    }
} else {
    $resign_status = "0";
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
    1 => ['label' => 'Gender',         'key' => 'gender'],
    2 => ['label' => 'Date of Birth',    'key' => 'dob'],
    3 => ['label' => 'Age',             'key' => 'age'],
    4 => ['label' => 'Blood Group',             'key' => 'blood_group'],
    5 => ['label' => 'Marital Status',        'key' => 'marital_status'],
    6 => ['label' => 'Nationality',       'key' => 'nationality'],
    7 => ['label' => 'Religion',   'key' => 'religion'],
    8 => ['label' => 'Caste',       'key' => 'caste'],
    9 => ['label' => 'Mobile Number',        'key' => 'mobile_no'],
    10 => ['label' => 'Alternate Mobile',        'key' => 'alt_mobile_no'],
    11 => ['label' => 'Email',        'key' => 'email_id'],
    12 => ['label' => 'Emergency Contact Name',        'key' => 'emer_contact_name'],
    13 => ['label' => 'Emergency Contact Relation',        'key' => 'emer_contact_relation'],
    14 => ['label' => 'Emergency Contact Number',        'key' => 'emer_contact_no'],
    15 => ['label' => 'Present Address',        'key' => 'present_address'],
    16 => ['label' => 'Permanent Address',        'key' => 'permanent_address'],
    17 => ['label' => 'Aadhaar No',        'key' => 'aadhar_no'],
    18 => ['label' => 'PAN No',        'key' => 'pan_no'],
    19 => ['label' => 'Driving License',        'key' => 'driving_license'],
    20 => ['label' => 'Passport No',        'key' => 'passport_no'],
    21 => ['label' => 'Is Form 21',        'key' => 'shift_hours'],
    22 => ['label' => 'Identification Marks',        'key' => 'identification_masks'],
    23 => ['label' => 'Present Salary',        'key' => 'basic_salary'],
    24 => ['label' => 'Opening Leave',        'key' => 'opening_balance'],
    25 => ['label' => 'Extra Off',        'key' => 'ecoff'],
    26 => ['label' => 'C-Off',        'key' => 'coff'],
    27 => ['label' => 'Opening Leave Date',        'key' => 'opening_date'],
    28 => ['label' => 'Grade',        'key' => 'grade_id'],
    29 => ['label' => 'Department',        'key' => 'department_id'],
    30 => ['label' => 'Designation',        'key' => 'designation_id'],
    31 => ['label' => 'Date of Joining',        'key' => 'date_of_joining'],
    32 => ['label' => 'Job Location',        'key' => 'job_location'],
    33 => ['label' => 'Shift Code',        'key' => 'shift_id'],
    34 => ['label' => 'Reporting Manager',        'key' => 'reporting_manager'],
    35 => ['label' => 'Employment Type',        'key' => 'employee_type'],
    36 => ['label' => 'Employer Name',        'key' => 'employer_name'],
    37 => ['label' => 'Employer Designation',        'key' => 'employer_designation_id'],
    38 => ['label' => 'Service Period From',        'key' => 'service_from'],
    39 => ['label' => 'Service Period To',        'key' => 'service_to'],
    40 => ['label' => 'Last Drawn Salary',        'key' => 'last_salary'],
    41 => ['label' => 'Reason for Leaving',        'key' => 'reason'],
    42 => ['label' => 'Job Responsibilities',        'key' => 'job_responsibility'],
    43 => ['label' => 'Is PF',        'key' => 'is_pf'],
    44 => ['label' => 'Is ESI',        'key' => 'is_esic'],
    45 => ['label' => 'PF NO.',        'key' => 'pf_uan'],
    46 => ['label' => 'UAN NO.',        'key' => 'uan_no'],
    47 => ['label' => 'ESIC Number',        'key' => 'esic_no'],
    48 => ['label' => 'PF Joining Date',        'key' => 'pf_joining_date'],
    49 => ['label' => 'ESIC Joining Date',        'key' => 'esic_joining_date'],
    50 => ['label' => 'Bank Name',        'key' => 'bank_name'],
    51 => ['label' => 'Acc Holder Name',  'key' => 'acc_holder_name'],
    52 => ['label' => 'Account No.',      'key' => 'account_no'],
    53 => ['label' => 'IFSC Code',        'key' => 'ifsc_code'],

];

$showFields = isset($_GET['show_field']) ? array_map('intval', $_GET['show_field']) : [];

// Table display ke liye
if (isset($_GET['submit'])) {
    if (!empty($showFields)) {
        $displayFields = $showFields;
    } else {
        // Submit hua but kuch select nahi → sab fields
        $displayFields = array_keys($fieldMap);
    }
} else {
    // Page first load → default sab fields
    $displayFields = array_keys($fieldMap);
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
                <?php include('inc/alert.php'); ?>
                <div class="row">
                    <?php if (!isset($_GET['submit'])) { ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?><a href="emp_bank_details.php"
                                                    class="float-end btn btn-primary btn-sm ms-2">Export Bank Excel</a>
                                                <a href="employee_master.php"
                                                    class="float-end btn btn-primary btn-sm">Add New</a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">
                                            <label for="department_id" class="form-label">Department Name<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="department_id" id="department_id">
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
                                            <select class="form-select form-select-sm chosen-select" name="grade_id"
                                                id="grade_id">
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
                                            <select class="form-select form-select-sm chosen-select" name="shift_id"
                                                id="shift_id">
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
                                            <select class="form-select form-select-sm" name="gender" id="gender">
                                                <option value="">Select</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <script>
                                            document.getElementById('gender').value = '<?= $gender ?>'
                                            </script>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Status</label>
                                            <select class="form-select form-select-sm" name="resign_status"
                                                id="resign_status">
                                                <option value="0">Active</option>
                                                <option value="1">Inactive (Resigned)</option>
                                            </select>
                                            <script>
                                            document.getElementById('resign_status').value = '<?= $resign_status ?>'
                                            </script>
                                        </div>
                                        <div class="col-md-3 md-2">
                                            <strong><label for="Fields">Fields<span
                                                        class="text-danger fw-bold"></span></label></strong>
                                            <select id="show_field" name="show_field[]" class="form-control" multiple>
                                                <?php
                                                    foreach ($fieldMap as $fid => $field) {
                                                        $selected = (in_array($fid, $showFields)) ? "selected" : "";
                                                        echo "<option value='$fid' $selected>{$field['label']}</option>";
                                                    }
                                                    ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary add-btn"
                                                value="Search">
                                            <a href="<?php echo $pagename ?>" class="btn btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php
                    if (isset($_GET['submit'])) {
                    ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                            <h5 class="card-title mb-0">
                                                <?= $submodule; ?>
                                            </h5>
                                            <div class="ms-2 text-muted">
                                                <?php if (!empty($_GET['year'])) { ?>
                                                <b>Year:</b> <?= $_GET['year']; ?>
                                                <?php } ?>
                                                <?php if (!empty($month_name)) { ?>
                                                | <b>Month:</b> <?= $month_name; ?>
                                                <?php } ?>

                                                <?php if (!empty($department_name)) { ?>
                                                | <b>Dept:</b> <?= $department_name; ?>
                                                <?php } ?>
                                            </div>
                                            <div class="mb-3">
                                                <a href="<?php echo $pagename; ?>" class="btn btn-primary btn-sm">
                                                    Search Again
                                                </a>
                                            </div>
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
                                                    <!-- <th>Actions</th> -->
                                                    <th>Employee Code</th>
                                                    <th>Employee Name</th>
                                                    <th>Father’s Name</th>
                                                    <?php
                                                        foreach ($displayFields as $fid) {
                                                            if (!isset($fieldMap[$fid])) continue;
                                                            echo "<th>{$fieldMap[$fid]['label']}</th>";
                                                        }
                                                        ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $slno = 1;
                                                    $res = $obj->executequery("
                                                                        SELECT 
                                                                            em.*,
                                                                            dm.department_name,
                                                                            bnk.bank_name,
                                                                            ebd.bank_id,
                                                                            ebd.acc_holder_name,
                                                                            ebd.account_no,
                                                                            ebd.ifsc_code,
                                                                            ebd.is_active,
                                                                            dem.designation AS current_designation,
                                                                            dem2.designation AS previous_designation,
                                                                            gm.grade_name,
                                                                            erpt.first_name as reporting_manager_name
                                                                        FROM $tblname AS em
                                                                        LEFT JOIN department_master dm 
                                                                            ON em.department_id = dm.department_id
                                                                        LEFT JOIN emp_bank_details ebd 
                                                                            ON em.emp_id = ebd.emp_id and ebd.is_active = 1
                                                                        LEFT JOIN bank_master bnk 
                                                                            ON ebd.bank_id = bnk.bank_id
                                                                        LEFT JOIN designation_master dem 
                                                                            ON em.designation_id = dem.designation_id
                                                                        LEFT JOIN designation_master dem2 
                                                                            ON em.employer_designation_id = dem2.designation_id
                                                                              LEFT JOIN employee_master erpt 
                                                                            ON em.emp_id = erpt.reporting_manager
                                                                        LEFT JOIN grade_master gm 
                                                                            ON em.grade_id = gm.grade_id
                                                                        WHERE em.unit_id = '$unitid' $crit
                                                                        ORDER BY em.emp_code ASC
                                                                        ");
                                                                       
                                                    foreach ($res as $row) {
                                                    ?>
                                                <tr id="tr_<?= $row["emp_id"]; ?>">
                                                    <td><?php echo $slno++; ?></td>
                                                    <td><?= $row["emp_code"]; ?></td>
                                                    <td> <?= ucfirst($row['first_name'] ?? ''); ?>
                                                        <?= ucfirst($row['last_name'] ?? ''); ?></td>
                                                    <td><?php echo $row["father_name"]; ?></td>
                                                    <?php
                                                            foreach ($displayFields as $fid) {
                                                                if (!isset($fieldMap[$fid])) continue;

                                                                $key = $fieldMap[$fid]['key'];
                                                                if ($key == 'department_id') {
                                                                    $value = $row['department_name'] ?? '-';
                                                                } elseif ($key == 'designation_id') {
                                                                    $value = $row['current_designation'] ?? '-';
                                                                } elseif ($key == 'grade_id') {
                                                                    $value = $row['grade_name'] ?? '-';
                                                                } elseif ($key == 'reporting_manager') {
                                                                    $value = $row['reporting_manager_name'] ?? '-';
                                                                }elseif ($key == 'shift_id') {
                                                                    $value = $obj->getCustomCode($row['shift_id']) ?? '-';
                                                                } elseif ($key == 'employer_designation_id') {
                                                                    $value = $row['previous_designation'] ?? '-';
                                                                } elseif ($key == 'is_pf') {
                                                                    $value = ($row['is_pf'] == '1') ? 'Yes' : 'No';
                                                                } elseif ($key == 'is_esic') {
                                                                    $value = ($row['is_esic'] == '1') ? 'Yes' : 'No';
                                                                } elseif ($key == 'age') {
                                                                    if (!empty($row['dob'])) {
                                                                        $dob = new DateTime($row['dob']);
                                                                        $today = new DateTime();
                                                                        $value = $today->diff($dob)->y; // age in years
                                                                    } else {
                                                                        $value = '-';
                                                                    }
                                                                } else {
                                                                    $value = $row[$key] ?? '-';
                                                                }
                                                                // Date format example
                                                                if (in_array($key, ['dob', 'date_of_joining', 'opening_date', 'pf_joining_date', 'esic_joining_date']) && !empty($value)) {
                                                                    $value = $obj->dateformatindia1($value);
                                                                }
                                                            ?>
                                                    <td><?= $value ?></td>
                                                    <?php } ?>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
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
        $(".chosen-select").select2({
            width: '100%',
            search_contains: true
        });

        $('#show_field').select2({
            width: '100%'
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
    </script>
</body>

</html>