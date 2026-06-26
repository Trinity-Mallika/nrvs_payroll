<?php include("../adminsession.php");
// print_r($_SESSION);
// die;
$pagename = "basic-employee-information.php";
$title = "Basic Employee Information";
$tblname = "employee_master";
$tblpkey = "emp_id";
$imgpath = '../uploaded/emp_documents/';
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
if (isset($_POST['submit'])) {
    $emp_code = $obj->test_input($_POST['emp_code']);
    $first_name = $obj->test_input($_POST['first_name']);
    $last_name = $obj->test_input($_POST['last_name']);
    $father_name = $obj->test_input($_POST['father_name']);
    $gender = $obj->test_input($_POST['gender']);
    $dob = $obj->test_input($_POST['dob']);
    $age = $obj->test_input($_POST['age']);
    $blood_group = $obj->test_input($_POST['blood_group']);
    $marital_status = $obj->test_input($_POST['marital_status']);
    $nationality = $obj->test_input($_POST['nationality']);
    $religion = $obj->test_input($_POST['religion']);
    $caste = $obj->test_input($_POST['caste']);

    $mobile_no = $obj->test_input($_POST['mobile_no']);
    $alt_mobile_no = $obj->test_input($_POST['alt_mobile_no']);
    $email_id = $obj->test_input($_POST['email_id']);
    $present_address = $obj->test_input($_POST['present_address']);
    $permanent_address = $obj->test_input($_POST['permanent_address']);
    $emer_contact_name = $obj->test_input($_POST['emer_contact_name']);
    $emer_contact_relation = $obj->test_input($_POST['emer_contact_relation']);
    $emer_contact_no = $obj->test_input($_POST['emer_contact_no']);

    $aadhar_no = $obj->test_input($_POST['aadhar_no']);
    $pan_no = $obj->test_input($_POST['pan_no']);
    $driving_license = $obj->test_input($_POST['driving_license']);
    $passport_no = $obj->test_input($_POST['passport_no']);
    $identification_masks = $obj->test_input($_POST['identification_masks']);

    $department_id = $obj->test_input($_POST['department_id']);
    $designation_id = $obj->test_input($_POST['designation_id']);
    $grade_id = $obj->test_input($_POST['grade_id']);
    $date_of_joining = $obj->test_input($_POST['date_of_joining']);
    $job_location = $obj->test_input($_POST['job_location']);
    $shift_id = $obj->test_input($_POST['shift_id']);
    $employee_type = $obj->test_input($_POST['employee_type']);
    $reporting_manager = $obj->test_input($_POST['reporting_manager']);

    $employer_name = $obj->test_input($_POST['employer_name']);
    $employer_designation_id = $obj->test_input($_POST['employer_designation_id']);
    $service_from = $obj->test_input($_POST['service_from']);
    $service_to = $obj->test_input($_POST['service_to']);
    $reason = $obj->test_input($_POST['reason']);
    $job_responsibility = $obj->test_input($_POST['job_responsibility']);
    $last_salary = $obj->test_input($_POST['last_salary']);

    $basic_salary = $obj->test_input($_POST['basic_salary']);
    $hra = $obj->test_input($_POST['hra']);
    $da = $obj->test_input($_POST['da']);
    $conveyance = $obj->test_input($_POST['conveyance']);
    $medical_allowance = $obj->test_input($_POST['medical_allowance']);
    $special_allowance = $obj->test_input($_POST['special_allowance']);
    $is_pf = $obj->test_input($_POST['is_pf'] ?? 0);
    $is_esic = $obj->test_input($_POST['is_esic'] ?? 0);
    // $is_pt = $obj->test_input($_POST['is_pt']);

    // $is_lwf = $obj->test_input($_POST['is_lwf']);
    $ctc = $obj->test_input($_POST['ctc']);
    $gross_salary = $obj->test_input($_POST['gross_salary']);
    $net_salary = $obj->test_input($_POST['net_salary']);
    $bank_name = $obj->test_input($_POST['bank_name']);
    $acc_holder_name = $obj->test_input($_POST['acc_holder_name']);
    $account_no = $obj->test_input($_POST['account_no']);
    $ifsc_code = $obj->test_input($_POST['ifsc_code']);
    $pf_uan = $obj->test_input($_POST['pf_uan']);
    $esic_no = $obj->test_input($_POST['esic_no']);
    $pf_joining_date = $obj->test_input($_POST['pf_joining_date']);
    $esic_joining_date = $obj->test_input($_POST['esic_joining_date']);
    // print_r($_POST);
    // die;

    $form_data = array(
        "emp_code" => $emp_code,
        "first_name" => $first_name,
        "last_name" => $last_name,
        "father_name" => $father_name,
        "gender" => $gender,
        "dob" => $dob,
        "age" => $age,
        "blood_group" => $blood_group,
        "marital_status" => $marital_status,
        "nationality" => $nationality,
        "religion" => $religion,
        "caste" => $caste,

        "mobile_no" => $mobile_no,
        "alt_mobile_no" => $alt_mobile_no,
        "email_id" => $email_id,
        "present_address" => $present_address,
        "permanent_address" => $permanent_address,
        "emer_contact_name" => $emer_contact_name,
        "emer_contact_relation" => $emer_contact_relation,
        "emer_contact_no" => $emer_contact_no,

        "aadhar_no" => $aadhar_no,
        "pan_no" => $pan_no,
        "driving_license" => $driving_license,
        "passport_no" => $passport_no,
        "identification_masks" => $identification_masks,

        "department_id" => $department_id,
        "designation_id" => $designation_id,
        "grade_id" => $grade_id,
        "date_of_joining" => $date_of_joining,
        "job_location" => $job_location,
        "shift_id" => $shift_id,
        "employee_type" => $employee_type,
        "reporting_manager" => $reporting_manager,

        "employer_name" => $employer_name,
        "employer_designation_id" => $employer_designation_id,
        "service_from" => $service_from,
        "service_to" => $service_to,
        "reason" => $reason,
        "job_responsibility" => $job_responsibility,
        "last_salary" => $last_salary,

        "basic_salary" => $basic_salary,
        "hra" => $hra,
        "da" => $da,
        "conveyance" => $conveyance,
        "medical_allowance" => $medical_allowance,
        "special_allowance" => $special_allowance,
        "is_pf" => $is_pf,
        "is_esic" => $is_esic,
        // "is_pt" => $is_pt,
        // "is_lwf" => $is_lwf,
        "ctc" => $ctc,
        "gross_salary" => $gross_salary,
        "net_salary" => $net_salary,
        "bank_name" => $bank_name,
        "acc_holder_name" => $acc_holder_name,
        "account_no" => $account_no,
        "ifsc_code" => $ifsc_code,
        "pf_uan" => $pf_uan,
        "esic_no" => $esic_no,
        "pf_joining_date" => $pf_joining_date,
        "esic_joining_date" => $esic_joining_date,
        "createdby"   => $loginid,
        "ipaddress"   => $ipaddress,
        "sessionid"   => $sessionid,
        "unit_id"   => $unitid
    );

    $count = $obj->getvalfield($tblname, "count(*)", " emp_code='$emp_code' and $tblpkey!='$keyvalue'");
    if ($count > 0) {
        $action = 4;
        $process = "duplicate";
    } else {
        if ($keyvalue == 0) {
            $form_data["createdate"] = $createdate;

            $lastid = $obj->insert_record_lastid($tblname, $form_data);
            $action = 1;
            $process = "insert";
        } else {
            $form_data["lastupdated"] = $createdate;
            $where = array($tblpkey => $keyvalue);
            $obj->update_record($tblname, $where, $form_data);
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
    $emp_code = $sqledit['emp_code'];
    $first_name = $sqledit['first_name'];
    $last_name = $sqledit['last_name'];
    $father_name = $sqledit['father_name'];
    $gender = $sqledit['gender'];
    $dob = $sqledit['dob'];
    $age = $sqledit['age'];
    $blood_group = $sqledit['blood_group'];
    $marital_status = $sqledit['marital_status'];
    $nationality = $sqledit['nationality'];
    $religion = $sqledit['religion'];
    $caste = $sqledit['caste'];

    $mobile_no = $sqledit['mobile_no'];
    $alt_mobile_no = $sqledit['alt_mobile_no'];
    $email_id = $sqledit['email_id'];
    $present_address = $sqledit['present_address'];
    $permanent_address = $sqledit['permanent_address'];
    $emer_contact_name = $sqledit['emer_contact_name'];
    $emer_contact_relation = $sqledit['emer_contact_relation'];
    $emer_contact_no = $sqledit['emer_contact_no'];

    $aadhar_no = $sqledit['aadhar_no'];
    $pan_no = $sqledit['pan_no'];
    $driving_license = $sqledit['driving_license'];
    $passport_no = $sqledit['passport_no'];
    $identification_masks = $sqledit['identification_masks'];

    $department_id = $sqledit['department_id'];
    $designation_id = $sqledit['designation_id'];
    $grade_id = $sqledit['grade_id'];
    $date_of_joining = $sqledit['date_of_joining'];
    $job_location = $sqledit['job_location'];
    $shift_id = $sqledit['shift_id'];
    $employee_type = $sqledit['employee_type'];
    $reporting_manager = $sqledit['reporting_manager'];

    $employer_name = $sqledit['employer_name'];
    $employer_designation_id = $sqledit['employer_designation_id'];
    $service_from = $sqledit['service_from'];
    $service_to = $sqledit['service_to'];
    $reason = $sqledit['reason'];
    $job_responsibility = $sqledit['job_responsibility'];
    $last_salary = $sqledit['last_salary'];

    $basic_salary = $sqledit['basic_salary'];
    $hra = $sqledit['hra'];
    $da = $sqledit['da'];
    $conveyance = $sqledit['conveyance'];
    $medical_allowance = $sqledit['medical_allowance'];
    $special_allowance = $sqledit['special_allowance'];
    $is_pf = $sqledit['is_pf'];
    $is_esic = $sqledit['is_esic'];
    $is_pt = $sqledit['is_pt'];

    $is_lwf = $sqledit['is_lwf'];
    $ctc = $sqledit['ctc'];
    $gross_salary = $sqledit['gross_salary'];
    $net_salary = $sqledit['net_salary'];
    $bank_name = $sqledit['bank_name'];
    $acc_holder_name = $sqledit['acc_holder_name'];
    $account_no = $sqledit['account_no'];
    $ifsc_code = $sqledit['ifsc_code'];
    $pf_uan = $sqledit['pf_uan'];
    $esic_no = $sqledit['esic_no'];
    $pf_joining_date = $sqledit['pf_joining_date'];
    $esic_joining_date = $sqledit['esic_joining_date'];
} else {

    $emp_code = "";
    $first_name = "";
    $last_name = "";
    $father_name = "";
    $gender = "";
    $dob = "";
    $age = "";
    $blood_group = "";
    $marital_status = "";
    $nationality = "";
    $religion = "";
    $caste = "";

    $mobile_no = "";
    $alt_mobile_no = "";
    $email_id = "";
    $present_address = "";
    $permanent_address = "";
    $emer_contact_name = "";
    $emer_contact_relation = "";
    $emer_contact_no = "";

    $aadhar_no = "";
    $pan_no = "";
    $driving_license = "";
    $passport_no = "";
    $identification_masks = "";

    $department_id = "";
    $designation_id = "";
    $grade_id = "";
    $date_of_joining = "";
    $job_location = "";
    $shift_id = "";
    $employee_type = "";
    $reporting_manager = "";

    $employer_name = "";
    $employer_designation_id = "";
    $service_from = "";
    $service_to = "";
    $reason = "";
    $job_responsibility = "";
    $last_salary = '';

    $basic_salary = "";
    $hra = "";
    $da = "";
    $conveyance = "";
    $medical_allowance = "";
    $special_allowance = "";
    $is_pf = "";
    $is_esic = "";
    $is_pt = "";

    $is_lwf = "";
    $ctc = "";
    $gross_salary = "";
    $net_salary = "";
    $bank_name = "";
    $acc_holder_name = "";
    $account_no = "";
    $ifsc_code = "";
    $pf_uan = "";
    $esic_no = "";
    $pf_joining_date = "";
    $esic_joining_date = "";
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

.form-step {
    display: none;
}

.form-step-active {
    display: block;
}

.step-arrow-nav .nav .nav-link.active {
    background-color: rgb(64 81 137);
    color: #ffffff;
}

.step-arrow-nav .nav .nav-link.active::before {
    border-left-color: rgb(64 81 137);
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
                <?php include('inc/alert.php');
                ?>

                <div class="row">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0">Employee Information</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- ⭐ TABS BAR (10 Steps) -->
                                    <div class="step-arrow-nav ">
                                        <ul class="nav nav-tabs nav-justified mb-4" id="stepTabs">
                                            <li class="nav-item curso"><a class="nav-link ps-1 pe-1 active fw-bold"
                                                    data-step="0">Basic Info</a></li>
                                            <li class="nav-item curso"><a class="nav-link ps-1 pe-1 fw-bold"
                                                    data-step="1">Contact</a></li>
                                            <li class="nav-item curso"><a class="nav-link ps-1 pe-1 fw-bold"
                                                    data-step="2">Identification</a></li>
                                            <li class="nav-item curso"><a class="nav-link ps-1 pe-1 fw-bold"
                                                    data-step="3">Job</a></li>
                                            <li class="nav-item curso"><a class="nav-link ps-1 pe-1 fw-bold"
                                                    data-step="4">Family</a></li>
                                            <li class="nav-item curso"><a class="nav-link ps-1 pe-1 fw-bold"
                                                    data-step="5">Education</a></li>
                                            <li class="nav-item curso"><a class="nav-link ps-1 pe-1 fw-bold"
                                                    data-step="6">Previous Job</a></li>
                                            <li class="nav-item curso"><a class="nav-link ps-1 pe-1 fw-bold"
                                                    data-step="7">Payroll</a></li>
                                            <li class="nav-item curso"><a class="nav-link ps-1 pe-1 fw-bold"
                                                    data-step="8">PF/ESIC</a></li>
                                            <li class="nav-item curso"><a class="nav-link ps-1 pe-1 fw-bold"
                                                    data-step="9">Documents</a></li>
                                        </ul>

                                        <!-- ⭐ FORM START -->
                                        <form id="multiStepForm">

                                            <!-- ⭐ STEP 1 : BASIC EMPLOYEE INFO -->
                                            <div class="form-step form-step-active">

                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Employee Code <span
                                                                class="text-danger"> *</span> </label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="emp_code" id="emp_code" placeholder="Enter Code"
                                                            value="<?= $emp_code ?>">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">First Name<span class="text-danger">
                                                                *</span> </label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="first_name" id="first_name" value="<?= $first_name ?>"
                                                            placeholder="Enter First Name">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Last Name<span class="text-danger">
                                                                *</span> </label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="last_name" id="last_name" value="<?= $last_name ?>"
                                                            placeholder="Enter Last Name">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Father’s Name</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="father_name" id="father_name"
                                                            value="<?= $father_name ?>" placeholder="Enter Father Name">
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

                                                    <div class="col-md-3">
                                                        <label class="form-label">Date of Birth</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="dob" id="dob" value="<?= $dob ?>">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Age</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="age" id="age" value="<?= $age ?>"
                                                            placeholder="Enter Age" autocomplete="off"
                                                            onkeypress="numberOnly(event);" maxlength="3">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Blood Group</label>
                                                        <select class="form-select form-select-sm" name="blood_group"
                                                            id="blood_group">
                                                            <option value="">Select Blood Group</option>
                                                            <option value="A+">A+</option>
                                                            <option value="A-">A-</option>
                                                            <option value="B+">B+</option>
                                                            <option value="B-">B-</option>
                                                            <option value="O+">O+</option>
                                                            <option value="O-">O-</option>
                                                            <option value="AB+">AB+</option>
                                                            <option value="AB-">AB-</option>
                                                        </select>
                                                        <script>
                                                        document.getElementById('blood_group').value =
                                                            '<?= $blood_group ?>'
                                                        </script>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Marital Status</label>
                                                        <select class="form-select form-select-sm" name="marital_status"
                                                            id="marital_status">
                                                            <option value="">Select</option>
                                                            <option value="Single">Single</option>
                                                            <option value="Married">Married</option>
                                                            <option value="Divorced">Divorced</option>
                                                        </select>
                                                        <script>
                                                        document.getElementById('marital_status').value =
                                                            '<?= $marital_status ?>'
                                                        </script>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Nationality</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="nationality" id="nationality"
                                                            value="<?= $nationality ?>" placeholder="Enter Nationality">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Religion</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="religion" id="religion" value="<?= $religion ?>"
                                                            placeholder="Enter Religion">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Caste</label>
                                                        <select class="form-select form-select-sm" name="caste"
                                                            id="caste">
                                                            <option value="">Select</option>
                                                            <option value="General">General</option>
                                                            <option value="OBC">OBC</option>
                                                            <option value="SC">SC</option>
                                                            <option value="ST">ST</option>
                                                        </select>
                                                        <script>
                                                        document.getElementById('caste').value = '<?= $caste ?>'
                                                        </script>
                                                    </div>
                                                </div>

                                                <div class="mt-4 text-end">
                                                    <button type="button" class="btn btn-primary btn-next">Next
                                                        →</button>
                                                </div>
                                            </div>

                                            <!-- ⭐ STEP 2 : CONTACT DETAILS -->
                                            <div class="form-step">

                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Mobile Number *</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="mobile_no" id="mobile_no" value="<?= $mobile_no ?>"
                                                            placeholder="Enter Number" autocomplete="off"
                                                            onkeypress="numberOnly(event);" maxlength="10">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Alternate Mobile</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="alt_mobile_no" id="alt_mobile_no"
                                                            value="<?= $alt_mobile_no ?>"
                                                            placeholder="Enter Alternate Mobile No." autocomplete="off"
                                                            onkeypress="numberOnly(event);" maxlength="10">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" class="form-control form-control-sm"
                                                            name="email_id" id="email_id" value="<?= $email_id ?>"
                                                            placeholder="Enter Email">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Emergency Contact Name</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="emer_contact_name" id="emer_contact_name"
                                                            value="<?= $emer_contact_name ?>"
                                                            placeholder="Enter Emergency Contact Name">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Emergency Contact Relation</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="emer_contact_relation" id="emer_contact_relation"
                                                            value="<?= $emer_contact_relation ?>"
                                                            placeholder="Enter Emergency Contact Relation">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Emergency Contact Number</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="emer_contact_no" id="emer_contact_no"
                                                            value="<?= $emer_contact_no ?>"
                                                            placeholder="Enter Emergency Contact Number"
                                                            autocomplete="off" onkeypress="numberOnly(event);"
                                                            maxlength="10">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Present Address</label>
                                                        <textarea class="form-control form-control-sm" rows="2"
                                                            name="present_address"
                                                            id="present_address"><?= $present_address ?></textarea>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Permanent Address</label>
                                                        <textarea class="form-control form-control-sm" rows="2"
                                                            name="permanent_address"
                                                            id="permanent_address"><?= $permanent_address ?></textarea>
                                                    </div>

                                                </div>

                                                <div class="mt-4 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary btn-prev">←
                                                        Previous</button>
                                                    <button type="button" class="btn btn-primary btn-next">Next
                                                        →</button>
                                                </div>
                                            </div>

                                            <!-- ⭐ STEP 3 -->
                                            <div class="form-step">

                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Aadhaar No</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="aadhar_no" id="aadhar_no" value="<?= $aadhar_no ?>"
                                                            placeholder="Enter Aadhaar Number">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">PAN No</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="pan_no" id="pan_no" value="<?= $pan_no ?>"
                                                            placeholder="Enter PAN Number">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Driving License</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="driving_license" id="driving_license"
                                                            value="<?= $driving_license ?>"
                                                            placeholder="Enter Driving License">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Passport No</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="passport_no" id="passport_no"
                                                            value="<?= $passport_no ?>" placeholder="Enter Passport No">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Identification Marks</label>
                                                        <textarea class="form-control form-control-sm"
                                                            name="identification_masks"
                                                            id="identification_masks"><?= $identification_masks ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="mt-4 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary btn-prev">←
                                                        Previous</button>
                                                    <button type="button" class="btn btn-primary btn-next">Next
                                                        →</button>
                                                </div>
                                            </div>

                                            <!-- ⭐ STEP 4 -->
                                            <div class="form-step">

                                                <div class="row g-3">

                                                    <div class="col-lg-3 mb-3">
                                                        <label for="department_id" class="form-label">Department<span
                                                                class="text-danger fw-bold">*</span></label>
                                                        <select class="form-select form-select-sm chosen-select"
                                                            name="department_id" id="department_id">
                                                            <option value="">Select</option>
                                                            <?php $res = $obj->executequery("Select * from department_master order by department_name asc");
                                                            foreach ($res as $key) { ?>
                                                            <option value="<?= $key['department_id']; ?>">
                                                                <?= $key['department_name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <script>
                                                        document.getElementById('department_id').value =
                                                            '<?= $department_id; ?>';
                                                        </script>
                                                    </div>
                                                    <div class="col-lg-3 mb-3">
                                                        <label for="designation_id" class="form-label">Designation<span
                                                                class="text-danger fw-bold"></span></label>
                                                        <select class="form-select form-select-sm chosen-select"
                                                            name="designation_id" id="designation_id">
                                                            <option value="">Select</option>
                                                            <?php $res = $obj->executequery("Select * from designation_master order by designation asc");
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
                                                                class="text-danger fw-bold"></span></label>
                                                        <select class="form-select form-select-sm chosen-select"
                                                            name="grade_id" id="grade_id">
                                                            <option value="">Select</option>
                                                            <?php $res = $obj->executequery("Select * from grade_master order by grade_name asc");
                                                            foreach ($res as $key) { ?>
                                                            <option value="<?= $key['grade_id']; ?>">
                                                                <?= $key['grade_name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <script>
                                                        document.getElementById('grade_id').value = '<?= $grade_id; ?>';
                                                        </script>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Date of Joining</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="date_of_joining" id="date_of_joining"
                                                            value="<?= $date_of_joining  ?>">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Job Location</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="job_location" id="job_location"
                                                            value="<?= $job_location  ?>"
                                                            placeholder="Enter Job Location">
                                                    </div>

                                                    <div class="col-lg-3 mb-3">
                                                        <label for="shift_id" class="form-label">Shift Type<span
                                                                class="text-danger fw-bold"></span></label>
                                                        <select class="form-select form-select-sm chosen-select"
                                                            name="shift_id" id="shift_id">
                                                            <option value="">Select</option>
                                                            <?php $res = $obj->executequery("Select * from shift_master order by shift_name asc");
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
                                                        <label>Reporting Manager</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="reporting_manager" id="reporting_manager"
                                                            value="<?= $reporting_manager ?>"
                                                            placeholder="Enter Reporting Manager Name">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Employment Type</label>
                                                        <select class="form-select form-select-sm" name="employee_type"
                                                            id="employee_type">
                                                            <option value="">Select</option>
                                                            <option value="Permanent">Permanent</option>
                                                            <option value="Contract">Contract</option>
                                                            <option value="Part-Time">Part-Time</option>
                                                            <option value="Trainee">Trainee</option>
                                                        </select>
                                                        <script>
                                                        document.getElementById('employee_type').value =
                                                            '<?= $employee_type; ?>';
                                                        </script>
                                                    </div>

                                                </div>

                                                <div class="mt-4 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary btn-prev">←
                                                        Previous</button>
                                                    <button type="button" class="btn btn-primary btn-next">Next
                                                        →</button>
                                                </div>
                                            </div>

                                            <!-- ⭐ STEP 5 -->
                                            <div class="form-step">

                                                <div class="alert alert-info">ESIC requires these details.</div>

                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="table-light">
                                                            <tr class="table-primary">
                                                                <th>Family Member Name<span class="text-danger">*</span>
                                                                </th>
                                                                <th>Relation<span class="text-danger">*</span></th>
                                                                <th>Date of Birth</th>
                                                                <th>Address<span class="text-danger">*</span></th>
                                                                <th>Gender<span class="text-danger">*</span></th>
                                                                <th>Action</th>
                                                            </tr>
                                                            <tr>

                                                                <td><input type="text" class="form-control"
                                                                        id="member_name"
                                                                        placeholder="Enter Member Name"></td>
                                                                <td><input type="text" class="form-control"
                                                                        id="member_relation"
                                                                        placeholder="Enter Member Relation"></td>
                                                                <td><input type="date" class="form-control"
                                                                        id="member_dob"></td>
                                                                <td><input class="form-control" id="member_address"
                                                                        placeholder="Enter Member Address"></td>
                                                                <td>
                                                                    <select class="form-select form-select-sm"
                                                                        id="member_gender">
                                                                        <option value="">Select</option>
                                                                        <option>Male</option>
                                                                        <option>Female</option>
                                                                        <option>Other</option>
                                                                    </select>
                                                                </td>
                                                                <td><button type="button" class="btn btn-sm btn-success"
                                                                        onclick="save_family();"
                                                                        id="family_btn">Add</button></td>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="fetch_family_details">
                                                        </tbody>
                                                    </table>
                                                </div>



                                                <div class="mt-4 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary btn-prev">←
                                                        Previous</button>
                                                    <button type="button" class="btn btn-primary btn-next">Next
                                                        →</button>
                                                </div>
                                            </div>

                                            <!-- ⭐ STEP 6 -->
                                            <div class="form-step">

                                                <div class="alert alert-info">Enter qualifications below.</div>

                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="table-light">
                                                            <tr class="table-primary">
                                                                <th>Examination <span class="text-danger">*</span></th>
                                                                <th>Board/University<span class="text-danger">*</span>
                                                                </th>
                                                                <th>College/Institute<span class="text-danger">*</span>
                                                                </th>
                                                                <th>Year<span class="text-danger">*</span></th>
                                                                <th>Percentage %<span class="text-danger">*</span></th>
                                                                <th>Subject<span class="text-danger">*</span></th>
                                                                <th>Action</th>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="text" class="form-control"
                                                                        id="examination"
                                                                        placeholder="Enter Examination"></td>

                                                                <td><input type="text" class="form-control"
                                                                        id="university"
                                                                        placeholder="Enter Board/University"></td>

                                                                <td><input type="text" class="form-control" id="college"
                                                                        placeholder="Enter College/Institute"></td>

                                                                <td><input type="text" class="form-control"
                                                                        id="pass_year" placeholder="Enter Passing Year"
                                                                        oninput="formatPassingYear(this);"></td>

                                                                <td><input type="text" class="form-control"
                                                                        id="percentage" placeholder="Enter Percentage"
                                                                        onkeypress="numberOnly(event);"></td>

                                                                <td><input type="text" class="form-control" id="subject"
                                                                        placeholder="Enter Subject"></td>

                                                                <td><button type="button" class="btn btn-sm btn-success"
                                                                        onclick="save_emp_ducation();"
                                                                        id="education_btn">Add</button></td>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="fetch_education_details">
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="mt-4 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary btn-prev">←
                                                        Previous</button>
                                                    <button type="button" class="btn btn-primary btn-next">Next
                                                        →</button>
                                                </div>
                                            </div>

                                            <!-- ⭐ STEP 7 -->
                                            <div class="form-step">
                                                <div class="row g-3">
                                                    <div class="col-md-3"><label>Employer Name</label>
                                                        <input class="form-control form-control-sm" name="employer_name"
                                                            id="employer_name" value="<?= $employer_name ?>"
                                                            placeholder="Enter Employer Name">
                                                    </div>


                                                    <div class="col-lg-3 mb-3">
                                                        <label for="employer_designation_id"
                                                            class="form-label">Designation<span
                                                                class="text-danger fw-bold"></span></label>
                                                        <select class="form-select form-select-sm chosen-select"
                                                            name="employer_designation_id" id="employer_designation_id">
                                                            <option value="">Select</option>
                                                            <?php $res = $obj->executequery("Select * from designation_master order by designation asc");
                                                            foreach ($res as $key) { ?>
                                                            <option value="<?= $key['designation_id']; ?>">
                                                                <?= $key['designation']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <script>
                                                        document.getElementById('employer_designation_id').value =
                                                            '<?= $employer_designation_id; ?>';
                                                        </script>
                                                    </div>
                                                    <div class="col-md-3"><label> Service Period From</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="service_from" id="service_from"
                                                            value="<?= $service_from ?>">
                                                    </div>
                                                    <div class="col-md-3"><label> Service Period To</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            name="service_to" id="service_to"
                                                            value="<?= $service_to ?>">
                                                    </div>
                                                    <div class="col-md-3"><label>Last Drawn Salary</label>
                                                        <input class="form-control form-control-sm" name="last_salary"
                                                            id="last_salary" value="<?= $last_salary ?>"
                                                            placeholder="Enter Last Drawn Salary">
                                                    </div>
                                                    <div class="col-md-3"><label>Reason for Leaving</label>
                                                        <input class="form-control form-control-sm" name="reason"
                                                            id="reason" value="<?= $reason ?>"
                                                            placeholder="Enter Reason for Leaving">
                                                    </div>
                                                    <div class="col-md-12"><label>Job Responsibilities</label><textarea
                                                            class="form-control form-control-sm" rows="3"
                                                            name="job_responsibility"
                                                            id="job_responsibility"><?= $job_responsibility ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="mt-4 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary btn-prev">←
                                                        Previous</button>
                                                    <button type="button" class="btn btn-primary btn-next">Next
                                                        →</button>
                                                </div>
                                            </div>

                                            <!-- ⭐ STEP 8 -->
                                            <div class="form-step">

                                                <div class="row g-3">
                                                    <div class="col-md-3"><label>Basic Salary</label>
                                                        <input class="form-control" placeholder="Enter Basic Salary"
                                                            name="basic_salary" id="basic_salary"
                                                            value="<?= $basic_salary ?>">
                                                    </div>
                                                    <div class="col-md-3"><label>HRA</label>
                                                        <input class="form-control" placeholder="Enter HRA" name="hra"
                                                            id="hra" value="<?= $hra ?>">
                                                    </div>
                                                    <div class="col-md-3"><label>DA</label><input class="form-control"
                                                            placeholder="Enter Dearness Allowance" name="da" id="da"
                                                            value="<?= $da ?>">
                                                    </div>
                                                    <div class="col-md-3"><label>Conveyance</label><input
                                                            class="form-control" placeholder="Enter Conveyance"
                                                            name="conveyance" id="conveyance"
                                                            value="<?= $conveyance ?>"></div>
                                                    <div class="col-md-3"><label>Medical Allowance</label>
                                                        <input class="form-control"
                                                            placeholder="Enter Medical Allowance"
                                                            name="medical_allowance" id="medical_allowance"
                                                            value="<?= $medical_allowance ?>">
                                                    </div>
                                                    <div class="col-md-3"><label>Special Allowance</label>
                                                        <input class="form-control"
                                                            placeholder="Enter Special Allowance"
                                                            name="special_allowance" id="special_allowance"
                                                            value="<?= $special_allowance ?>">
                                                    </div>

                                                    <div class="col-md-3"><label>PF?</label><select class="form-select"
                                                            name="is_pf" id="is_pf">
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>
                                                        </select>
                                                        <script>
                                                        document.getElementById('is_pf').value = '<?= $is_pf ?>'
                                                        </script>
                                                    </div>
                                                    <div class="col-md-3"><label>ESIC?</label><select
                                                            class="form-select" name="is_esic" id="is_esic">
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>
                                                        </select>
                                                        <script>
                                                        document.getElementById('is_esic').value = '<?= $is_esic ?>'
                                                        </script>
                                                    </div>

                                                    <div class="col-md-3"><label>CTC</label>
                                                        <input class="form-control" placeholder="Enter CTC" name="ctc"
                                                            id="ctc" value="<?= $ctc ?>">
                                                    </div>
                                                    <div class="col-md-3"><label>Gross Salary</label>
                                                        <input class="form-control" placeholder="Enter Gross Salary"
                                                            name="gross_salary" id="gross_salary"
                                                            value="<?= $gross_salary ?>">
                                                    </div>
                                                    <div class="col-md-3"><label>Net Salary</label>
                                                        <input class="form-control" placeholder="Enter Net Salary"
                                                            name="net_salary" id="net_salary"
                                                            value="<?= $net_salary ?>">
                                                    </div>
                                                </div>

                                                <h6 class="mt-4">Bank Details</h6>
                                                <div class="row g-3">
                                                    <div class="col-md-3"><label>Bank Name</label>
                                                        <input class="form-control" placeholder="Enter Bank Name"
                                                            name="bank_name" id="bank_name" value="<?= $bank_name ?>">
                                                    </div>
                                                    <div class="col-md-3"><label>Account Holder Name</label><input
                                                            class="form-control" placeholder="Enter Account Holder Name"
                                                            name="acc_holder_name" id="acc_holder_name"
                                                            value="<?= $acc_holder_name ?>"></div>
                                                    <div class="col-md-3"><label>Bank Account Number
                                                        </label><input class="form-control"
                                                            placeholder="Enter Bank Account Number" name="account_no"
                                                            id="account_no" value="<?= $account_no ?>"></div>
                                                    <div class="col-md-3"><label>IFSC Code</label><input
                                                            class="form-control" placeholder="Enter IFSC Code"
                                                            name="ifsc_code" id="ifsc_code" value="<?= $ifsc_code ?>">
                                                    </div>
                                                </div>

                                                <div class="mt-4 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary btn-prev">←
                                                        Previous</button>
                                                    <button type="button" class="btn btn-primary btn-next">Next
                                                        →</button>
                                                </div>
                                            </div>

                                            <!-- ⭐ STEP 9 -->
                                            <div class="form-step">
                                                <div class="row g-3">
                                                    <div class="col-md-6"><label>PF UAN</label><input
                                                            class="form-control" placeholder="Enter PF UAN"
                                                            name="pf_uan" id="pf_uan" value="<?= $pf_uan ?>"></div>

                                                    <div class="col-md-6"><label>ESIC Number</label><input
                                                            class="form-control" placeholder="Enter ESIC Number"
                                                            name="esic_no" id="esic_no" value="<?= $esic_no ?>"></div>

                                                    <div class="col-md-6"><label>PF Joining Date</label><input
                                                            type="date" class="form-control"
                                                            placeholder="Enter PF Joining Date" name="pf_joining_date"
                                                            id="pf_joining_date" value="<?= $pf_joining_date ?>"></div>

                                                    <div class="col-md-6"><label>ESIC Joining Date</label><input
                                                            type="date" class="form-control" name="esic_joining_date"
                                                            id="esic_joining_date" value="<?= $esic_joining_date ?>">
                                                    </div>
                                                </div>

                                                <div class="mt-4 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary btn-prev">←
                                                        Previous</button>
                                                    <button type="button" class="btn btn-primary btn-next">Next
                                                        →</button>
                                                </div>
                                            </div>

                                            <!-- ⭐ STEP 10 -->
                                            <div class="form-step">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="table-light">
                                                            <tr class="table-primary">
                                                                <th>Document Name <span class="text-danger">*</span>
                                                                </th>
                                                                <th>File<span class="text-danger">*</span></th>
                                                                <th>Remark</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <select
                                                                        class="form-select form-select-sm chosen-select"
                                                                        name="doc_id" id="doc_id">
                                                                        <option value="">Select</option>
                                                                        <?php $res = $obj->executequery("Select * from document_master order by doc_id asc");
                                                                        foreach ($res as $key) { ?>
                                                                        <option value="<?= $key['doc_id']; ?>">
                                                                            <?= $key['document_name']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </td>
                                                                <td><input type="file"
                                                                        class="form-control form-control-sm"
                                                                        id="doc_file"></td>
                                                                <td><input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="doc_remark" placeholder="Enter Remark"></td>
                                                                <td><button type="button" class="btn btn-sm btn-success"
                                                                        onclick="save_emp_document();"
                                                                        id="document_btn">Add</button></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="fetch_document_details">
                                                        </tbody>
                                                    </table>
                                                </div>


                                                <div class="mt-4 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary btn-prev">←
                                                        Previous</button>
                                                    <button type="submit" name="submit" class="btn btn-success">Submit
                                                        ✓</button>
                                                </div>
                                            </div>

                                        </form>
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
        $(".chosen-select").select2({
            width: '100%',
        });
        fetch_family_details();
        fetch_education_details();
        fetch_document_details();
    });


    const nextBtns = document.querySelectorAll(".btn-next");
    const prevBtns = document.querySelectorAll(".btn-prev");
    const steps = document.querySelectorAll(".form-step");
    const tabs = document.querySelectorAll("#stepTabs .nav-link");

    let currentStep = 0;

    function showStep(step) {
        steps.forEach(s => s.classList.remove("form-step-active"));
        tabs.forEach(t => t.classList.remove("active"));

        steps[step].classList.add("form-step-active");
        tabs[step].classList.add("active");

        currentStep = step;
        window.scrollTo(0, 0);
    }

    nextBtns.forEach(btn => btn.onclick = () => showStep(currentStep + 1));
    prevBtns.forEach(btn => btn.onclick = () => showStep(currentStep - 1));

    tabs.forEach(tab => {
        tab.onclick = () => {
            let step = parseInt(tab.dataset.step);
            showStep(step);
        };
    });

    showStep(0);
    </script>

    <script>
    function formatPassingYear(input) {
        let value = input.value.replace(/[^0-9]/g, ""); // allow digits only

        if (value.length >= 4) {
            input.value = value.substring(0, 4) + "-" + value.substring(4, 6);
        } else {
            input.value = value;
        }
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

    function save_family() {
        const member_name = $('#member_name').val();
        const member_relation = $('#member_relation').val();
        const member_dob = $('#member_dob').val();
        const member_address = $('#member_address').val();
        const member_gender = $('#member_gender').val();
        const keyvalue = '<?= $keyvalue; ?>';
        if (member_name === "") {
            alert("Please Enter Member Name");
            return;
        }
        if (member_relation === "") {
            alert("Please Enter Member Relation");
            return;
        }
        if (member_address === "") {
            alert("Please Enter Member Address");
            return;
        }
        if (member_gender === "") {
            alert("Please Enter Member Gender");
            return;
        }

        $.ajax({
            url: 'ajax/ajax_save_emp_family.php',
            type: 'POST',
            data: {
                member_name: member_name,
                keyvalue: keyvalue,
                member_relation: member_relation,
                member_dob: member_dob,
                member_address: member_address,
                member_gender: member_gender,
                type: 'family'
            },
            beforeSend: function() {
                $('#family_btn').prop("disabled", true).text("Saving...");
            },
            success: function(response) {
                if (response.trim() === "success") {
                    fetch_family_details();
                    $('#member_name').val('');
                    $('#member_relation').val('');
                    $('#member_dob').val('');
                    $('#member_address').val('');
                    $('#member_gender').val('').trigger("chosen:updated");
                } else {
                    alert("Error: " + response);
                }
            },
            error: function() {
                alert("Something went wrong. Please try again.");
            },
            complete: function() {
                $('#family_btn').prop("disabled", false).text("Add");
            }
        });
    }

    function fetch_family_details() {
        let keyvalue = '<?= $keyvalue; ?>';
        jQuery.ajax({
            type: 'POST',
            url: 'ajax/ajax_fetch_family_details.php',
            data: 'keyvalue=' + keyvalue + '&type=family',
            dataType: 'html',
            success: function(data) {
                //alert(data);
                document.getElementById('fetch_family_details').innerHTML = data;
            }
        }); //ajax close
    }


    function delete_family(id) {
        $('#deleteRecordModal').modal('show');
        var tblname = 'emp_family_details';
        var tblpkey = 'family_detail_id';
        var keyvalue = '<?= $keyvalue; ?>';
        $('#delete-record').click(function() {
            $.ajax({
                type: 'POST',
                url: 'ajax/delete_master.php',
                data: {
                    id: id,
                    tblname: tblname,
                    tblpkey: tblpkey,

                },
                success: function(data) {
                    fetch_family_details();
                }
            });
            $('#deleteRecordModal').modal('hide');
        });
    }



    function save_emp_ducation() {
        const examination = $('#examination').val();
        const university = $('#university').val();
        const college = $('#college').val();
        const pass_year = $('#pass_year').val();
        const percentage = $('#percentage').val();
        const subject = $('#subject').val();
        const keyvalue = '<?= $keyvalue; ?>';
        if (examination === "") {
            alert("Please Enter examination");
            return;
        }
        if (university === "") {
            alert("Please Enter Board / University");
            return;
        }
        if (college === "") {
            alert("Please Enter College/Institute");
            return;
        }
        if (pass_year === "") {
            alert("Please Enter Passing Year");
            return;
        }
        if (subject === "") {
            alert("Please Enter Subject");
            return;
        }

        $.ajax({
            url: 'ajax/ajax_save_emp_family.php',
            type: 'POST',
            data: {
                examination: examination,
                keyvalue: keyvalue,
                university: university,
                college: college,
                percentage: percentage,
                pass_year: pass_year,
                subject: subject,
                type: 'education'
            },
            beforeSend: function() {
                $('#education_btn').prop("disabled", true).text("Saving...");
            },
            success: function(response) {
                if (response.trim() === "success") {
                    fetch_education_details();
                    $('#examination').val('');
                    $('#university').val('');
                    $('#college').val('');
                    $('#percentage').val('');
                    $('#pass_year').val('');
                    $('#subject').val('');
                } else {
                    alert("Error: " + response);
                }
            },
            error: function() {
                alert("Something went wrong. Please try again.");
            },
            complete: function() {
                $('#education_btn').prop("disabled", false).text("Add");
            }
        });
    }

    function fetch_education_details() {
        let keyvalue = '<?= $keyvalue; ?>';
        jQuery.ajax({
            type: 'POST',
            url: 'ajax/ajax_fetch_family_details.php',
            data: 'keyvalue=' + keyvalue + '&type=education',
            dataType: 'html',
            success: function(data) {
                //alert(data);
                document.getElementById('fetch_education_details').innerHTML = data;
            }
        }); //ajax close
    }

    function delete_education(id) {
        $('#deleteRecordModal').modal('show');
        var tblname = 'emp_education';
        var tblpkey = 'education_id';
        var keyvalue = '<?= $keyvalue; ?>';
        $('#delete-record').click(function() {
            $.ajax({
                type: 'POST',
                url: 'ajax/delete_master.php',
                data: {
                    id: id,
                    tblname: tblname,
                    tblpkey: tblpkey,

                },
                success: function(data) {
                    fetch_education_details();
                }
            });
            $('#deleteRecordModal').modal('hide');
        });
    }


    function save_emp_document() {
        let formData = new FormData();
        let doc_file = document.getElementById("doc_file").files[0];
        let doc_id = document.getElementById("doc_id").value;
        let doc_remark = document.getElementById("doc_remark").value;
        if (doc_id == "" || doc_id == "0") {
            Swal.fire({
                icon: 'warning',
                title: 'Required!',
                text: 'Please select Document Type.'
            });
            return false;
        }

        if (!doc_file) {
            Swal.fire({
                icon: 'warning',
                title: 'Required!',
                text: 'Please upload a document file.'
            });
            return false;
        }
        const keyvalue = '<?= $keyvalue; ?>';
        formData.append("doc_file", doc_file);
        formData.append("doc_id", doc_id);
        formData.append("doc_remark", doc_remark);
        formData.append("type", "emp_document");
        formData.append("keyvalue", keyvalue);

        $.ajax({
            url: 'ajax/ajax_save_emp_family.php',
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            beforeSend: function() {
                $('#document_btn').prop("disabled", true).text("Saving...");
            },

            success: function(response) {
                if (response.trim() === "success") {
                    Swal.fire({
                        title: "Success!",
                        text: "File uploaded successfully!",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        fetch_document_details();
                        $('#doc_file').val('');
                        $('#doc_remark').val('');
                        $('#doc_id').val('').trigger("chosen:updated").trigger('change');
                    });
                } else {
                    Swal.fire("Error", "Upload failed: " + response, "error");
                }
            },
            error: function() {
                Swal.fire("Error", "Error while uploading. Try again.");

            },
            complete: function() {
                $('#document_btn').prop("disabled", false).text("Add");
            }
        });
    }

    function fetch_document_details() {
        let keyvalue = '<?= $keyvalue; ?>';
        jQuery.ajax({
            type: 'POST',
            url: 'ajax/ajax_fetch_family_details.php',
            data: 'keyvalue=' + keyvalue + '&type=document',
            dataType: 'html',
            success: function(data) {
                //alert(data);
                document.getElementById('fetch_document_details').innerHTML = data;
            }
        }); //ajax close
    }

    function delete_document(id, imgname) {
        $('#deleteRecordModal').modal('show');
        var tblname = 'emp_document';
        var tblpkey = 'emp_doc_id';
        var keyvalue = '<?= $keyvalue; ?>';
        var imgpath = '<?= $imgpath; ?>';
        $('#delete-record').click(function() {
            $.ajax({
                type: 'POST',
                url: 'ajax/delete_master_img.php',
                data: {
                    id: id,
                    tblname: tblname,
                    tblpkey: tblpkey,
                    imgname: imgname,
                    imgpath: imgpath,
                },
                success: function(data) {
                    fetch_document_details();
                }
            });
            $('#deleteRecordModal').modal('hide');
        });
    }
    </script>

</body>

</html>