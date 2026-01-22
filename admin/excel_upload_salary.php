<?php include("../adminsession.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pagename = "excel_upload.php";
$title = "Excel Upload Employee's ";
$module = "Excel Upload Employee's";
$submodule = "Employee's List";
$tblname = "employee_master";
$tblpkey = "emp_id";
$btn_name = "Save";
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
require_once __DIR__ . '/src/SimpleXLSX.php';

if (isset($_POST['submit'])) {
    $totalRecords = 0;
    $insertedCount = 0;
    $skippedCount = 0;
    $skippedEpicNumbers = array();

    if (isset($_FILES['upload_excel']['tmp_name']) && $_FILES['upload_excel']['error'] == UPLOAD_ERR_OK) {
        $fileType = pathinfo($_FILES['upload_excel']['name'], PATHINFO_EXTENSION);

        $firstRow = true;
        if ($fileType === 'xlsx') {
            if ($xlsx = SimpleXLSX::parse($_FILES['upload_excel']['tmp_name'])) {
                foreach ($xlsx->rows() as $k => $data) {
                    if ($firstRow) {
                        $firstRow = false;
                        continue;
                    }

                    list($emp_code, $biomatric_id, $first_name, $last_name, $father_name, $gender, $dob, $age, $blood_group, $marital_status, $nationality, $religion, $caste, $mobile_no, $alt_mobile_no, $email_id, $emer_contact_name, $emer_contact_relation, $emer_contact_no, $present_address, $permanent_address, $aadhar_no, $pan_no, $driving_license,  $passport_no,  $identification_masks, $basic_salary, $department,  $designation, $grade, $date_of_joining,  $job_location,   $shift,   $reporting_manager, $employee_type, $employer_name,  $employer_designation,    $service_from,   $service_to, $last_salary, $reason, $job_responsibility, $bank_name, $acc_holder_name, $account_no, $ifsc_code, $is_pf, $is_esic, $pf_uan, $esic_no, $pf_joining_date, $esic_joining_date) = $data;

                    if (empty($biomatric_id)) {
                        continue;
                    }

                    $totalRecords++;

                    $isDuplicate = $obj->getvalfield($tblname, "COUNT(*)", "biomatric_id = '$biomatric_id' and unit_id='$unitid'");

                    if ($isDuplicate > 0) {
                        $skippedEpicNumbers[] = $biomatric_id;
                        $skippedCount++;
                        continue;
                    }

                    $department_id = 0;
                    if (!empty(trim($department))) {
                        $department_id = $obj->getvalfield(
                            "department_master",
                            "department_id",
                            "department_name LIKE '%" . addslashes($department) . "%' 
         AND unit_id='$unitid'"
                        );

                        if ($department_id == 0) {
                            $department_id = $obj->insert_record_lastid(
                                "department_master",
                                [
                                    "department_name" => trim($department),
                                    "createdby"       => $loginid,
                                    "unit_id"         => $unitid,
                                    "ipaddress"       => $ipaddress
                                ]
                            );
                        }
                    }

                    $designation_id = 0;
                    if (!empty(trim($designation))) {
                        $designation_id = $obj->getvalfield(
                            "designation_master",
                            "designation_id",
                            "designation LIKE '%" . addslashes($designation) . "%'
         AND unit_id='$unitid'"
                        );

                        if ($designation_id == 0) {
                            $designation_id = $obj->insert_record_lastid(
                                "designation_master",
                                [
                                    "designation" => trim($designation),
                                    "createdby"   => $loginid,
                                    "unit_id"     => $unitid,
                                    "ipaddress"   => $ipaddress
                                ]
                            );
                        }
                    }

                    $employer_designation_id = 0;
                    if (!empty(trim($employer_designation))) {
                        $employer_designation_id = $obj->getvalfield(
                            "designation_master",
                            "designation_id",
                            "designation LIKE '%" . addslashes($employer_designation) . "%'
         AND unit_id='$unitid'"
                        );

                        if ($employer_designation_id == 0) {
                            $employer_designation_id = $obj->insert_record_lastid(
                                "designation_master",
                                [
                                    "designation" => trim($employer_designation),
                                    "createdby"   => $loginid,
                                    "unit_id"     => $unitid,
                                    "ipaddress"   => $ipaddress
                                ]
                            );
                        }
                    }

                    $shift_id = 0;
                    if (!empty(trim($shift))) {
                        $shift_id = $obj->getvalfield(
                            "shift_master",
                            "shift_id",
                            "shift_name LIKE '%" . addslashes($shift) . "%'
         AND unit_id='$unitid'"
                        );

                        if ($shift_id == 0) {
                            $shift_id = $obj->insert_record_lastid(
                                "shift_master",
                                [
                                    "shift_name" => trim($shift),
                                    "createdby" => $loginid,
                                    "unit_id"   => $unitid,
                                    "ipaddress" => $ipaddress
                                ]
                            );
                        }
                    }
                    $grade_id = 0;
                    if (!empty(trim($grade))) {
                        $grade_id = $obj->getvalfield(
                            "grade_master",
                            "grade_id",
                            "grade_name LIKE '%" . addslashes($grade) . "%'
         AND unit_id='$unitid'"
                        );

                        if ($grade_id == 0) {
                            $grade_id = $obj->insert_record_lastid(
                                "grade_master",
                                [
                                    "grade_name" => trim($grade),
                                    "createdby" => $loginid,
                                    "unit_id"   => $unitid,
                                    "ipaddress" => $ipaddress
                                ]
                            );
                        }
                    }

                    $form_data = array(
                        "emp_code" => $emp_code,
                        "biomatric_id" => $biomatric_id,
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
                        "is_pf" => $is_pf,
                        "is_esic" => $is_esic,
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
                    $obj->insert_record($tblname, $form_data);
                    $insertedCount++;
                }
            }
        }
    }

    echo "<script>location='$pagename?action=1&total=$totalRecords&inserted=$insertedCount&skipped=$skippedCount&skipped_epics=" . implode(",", $skippedEpicNumbers) . "'</script>";
}
?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?></title>
    <?php include('inc/css.php') ?>
</head>

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
                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="card-title mb-1">
                                                <?= $module; ?>
                                            </h5>
                                            <small class="text-danger fw-semibold">
                                                Note: <strong>Biometric ID is mandatory</strong> for data insertion. Rows without Biometric ID will be skipped.
                                            </small>
                                        </div>

                                        <div class="col-md-4 text-end">
                                            <a href="employee_excel.xlsx" class="btn btn-primary btn-sm">
                                                Download Sample File
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <strong><label for="">Upload File <span class="text-danger fw-bold">*</span></label></strong>
                                            <input type="file" name="upload_excel" id="upload_excel" class="form-control form-control-sm" accept=".xlsx">
                                        </div>
                                        <div class="col-lg-4 mb-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary btn-sm" value="<?php echo $btn_name ?> " onClick="return checkinputmaster('upload_excel')">
                                            <a href="<?php echo $pagename ?>" type="button" class="btn btn-danger btn-sm">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php if (isset($_GET['total'])): ?>
                    <div class="alert alert-info mt-3">
                        <strong>Data Processing Results:</strong>
                        <p>Total Records: <?php echo htmlspecialchars($_GET['total']); ?></p>
                        <p>Successfully Inserted: <?php echo htmlspecialchars($_GET['inserted']); ?></p>
                        <p>Skipped (Duplicates): <?php echo htmlspecialchars($_GET['skipped']); ?></p>

                        <?php if (isset($_GET['skipped_epics'])): ?>
                            <p>Skipped Biometric Numbers: <?php echo htmlspecialchars($_GET['skipped_epics']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <!-- Content close-->
</body>

</html>