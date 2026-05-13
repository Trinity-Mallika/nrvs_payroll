<?php
ini_set('max_execution_time', 600); // 10 minutes
set_time_limit(600);
include("../adminsession.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// $time = date('H:i:s', strtotime('8:00:00'));
// echo $time;
// die;

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
    $MAX_INSERT = 2500;

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

                    //list($emp_code, $biomatric_id, $first_name, $father_name, $gender, $dob, $age, $blood_group, $marital_status, $nationality, $religion, $caste, $mobile_no, $alt_mobile_no, $email_id, $emer_contact_name, $emer_contact_relation, $emer_contact_no, $present_address, $permanent_address, $aadhar_no, $pan_no, $driving_license,  $passport_no, $identification_masks, $basic_salary, $department,  $designation, $grade, $date_of_joining,  $job_location, $shift, $reporting_manager, $employee_type, $employer_name,  $employer_designation, $service_from, $service_to, $last_salary, $reason, $job_responsibility, $bank_name, $acc_holder_name, $account_no, $ifsc_code, $is_pf, $is_esic, $pf_uan, $esic_no, $pf_joining_date, $esic_joining_date, $opening_leave, $opening_date, $uan_no, $is_form21_last_date, $form21_last_date, $anniversary_date) = $data;

                    list($emp_code,$biomatric_id,$first_name,$father_name,$department,$designation,$date_of_joining,$dob,$aadhar_no,$pan_no,$driving_license,$passport_no,$present_address,$permanent_address,$nationality,$religion,$caste,$gender,$age,$blood_group,$grade,$marital_status,$anniversary_date,$email_id,$mobile_no,$alt_mobile_no,$identification_masks,$basic_salary,$shift,$job_location,$is_pf,$is_esic,$pf_uan,$esic_no,$pf_joining_date,$esic_joining_date,$acc_holder_name,$account_no,$ifsc_code,$bank_name,$emer_contact_name,$emer_contact_relation,$emer_contact_no,$reporting_manager,$employee_type,$employer_name,$employer_designation,$service_from,$service_to,$last_salary,$reason,$job_responsibility,$opening_leave,$opening_date,$is_form21_last_date,$form21_last_date,) = $data;
               
                    //$time = date('H:i:s', strtotime($shift));
                    $time = '';

                    if($shift=='H'){
                        $time='08:00:00';
                    }elseif($shift=='AB'){
                        $time='12:00:00';
                    }
                  
                    if (empty($biomatric_id)) {
                        continue;
                    }

                    $totalRecords++;

                    // $isDuplicate = $obj->getvalfield($tblname, "COUNT(*)", "(biomatric_id = '$biomatric_id' OR emp_code = '$emp_code' OR mobile_no = '$mobile_no') and unit_id='$unitid'");

                    $conditions = [];

                    if ($biomatric_id != '') {
                        $conditions[] = "biomatric_id = '" . addslashes(trim($biomatric_id)) . "'";
                    }
                    if ($emp_code != '') {
                        $conditions[] = "emp_code = '" . addslashes(trim($emp_code)) . "'";
                    }
                    // if ($mobile_no != '') {
                    //     $conditions[] = "mobile_no = '" . addslashes(trim($mobile_no)) . "'";
                    // }

                    $where = implode(" OR ", $conditions);

                    // $isDuplicate = 0;
                    // if (!empty($where)) {
                    //     $isDuplicate = $obj->getvalfield(
                    //         $tblname,
                    //         "COUNT(*)",
                    //         "($where) AND unit_id='$unitid'"
                    //     );
                    // }



                 $existing_emp_id = 0;

if (!empty($where)) {

    $existing_emp_id = $obj->getvalfield(
        $tblname,
        "emp_id",
        "($where) AND unit_id='$unitid'"
    );
}

                    if ($insertedCount >= $MAX_INSERT) {
                        $skippedEpicNumbers[] = [
                            'row_no'        => $k + 1,
                            'emp_code'      => $emp_code,
                            'biomatric_id'  => $biomatric_id,
                            'mobile_no'     => $mobile_no,
                            'reason'        => 'Upload limit exceeded (2500)'
                        ];
                        $skippedCount++;
                        continue;
                    }

                    $department_id = 0;
                    if (!empty(trim($department))) {
                        $department = trim($department);
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

                    print_r($department_id); 

                   $designation_id = 0;

                    if (!empty(trim($designation))) {

                        $designation = trim($designation);

                        $designation_id = $obj->getvalfield(
                            "designation_master",
                            "designation_id",
                            "designation LIKE '%" . addslashes($designation) . "%'
                            AND unit_id='$unitid'"
                        );

                        // IF DESIGNATION EXISTS
                        if ($designation_id > 0) {

                            // UPDATE DEPARTMENT ID
                            $obj->update_record(
                                "designation_master",
                                ["designation_id" => $designation_id],
                                [
                                    "department_id" => $department_id,
                                    "updatedby"     => $loginid,
                                    "lastupdated"   => $createdate
                                ]
                            );

                        } else {

                            // INSERT NEW DESIGNATION
                            $designation_id = $obj->insert_record_lastid(
                                "designation_master",
                                [
                                    "designation"   => trim($designation),
                                    "department_id" => $department_id,
                                    "createdby"     => $loginid,
                                    "unit_id"       => $unitid,
                                    "ipaddress"     => $ipaddress
                                ]
                            );
                        }
                    }

                    $employer_designation_id = 0;
                    if (!empty(trim($employer_designation))) {
                        $employer_designation = trim($employer_designation);
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
                                    "department_id" => $department_id,
                                    "createdby"   => $loginid,
                                    "unit_id"     => $unitid,
                                    "ipaddress"   => $ipaddress
                                ]
                            );
                        }
                    }

                    $bank_id = 0;
                    if (!empty(trim($bank_name))) {
                        $bank_name = trim($bank_name);

                        $bank_id = $obj->getvalfield(
                            "bank_master",
                            "bank_id",
                            "bank_name LIKE '%" . addslashes($bank_name) . "%'
                     AND unit_id='$unitid'"
                        );

                        if ($bank_id == 0) {
                            $bank_id = $obj->insert_record_lastid(
                                "bank_master",
                                [
                                    "bank_name" => trim($bank_name),
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

                    if ($is_pf == 'Yes') {
                        $is_pf = '1';
                    } else {
                        $is_pf = '0';
                    }
                    if ($is_esic == 'Yes') {
                        $is_esic = '1';
                    } else {
                        $is_esic = '0';
                    }
                    if ($is_form21_last_date == 'Yes') {
                        $is_form21_last_date = '1';
                        $last_date = $form21_last_date;
                    } else {
                        $is_form21_last_date = '0';
                        $last_date = '';
                    }



                    $pf_joining_date   = (!empty($pf_joining_date))
                        ? date('Y-m-d', strtotime($pf_joining_date))
                        : '0000-00-00';

                    $esic_joining_date = (!empty($esic_joining_date))
                        ? date('Y-m-d', strtotime($esic_joining_date))
                        : null;

                    $date_of_joining = (!empty($date_of_joining))
                        ? date('Y-m-d', strtotime($date_of_joining))
                        : null;

                    $date_of_joining = (!empty($date_of_joining))
                        ? date('Y-m-d', strtotime($date_of_joining))
                        : null;
                    $anniversary_date = (!empty($anniversary_date))
                        ? date('Y-m-d', strtotime($anniversary_date))
                        : null;

                    $marital_status = ucfirst($marital_status);
                    if (empty($opening_date)) {
                        $opening_date = date('Y-m-01');
                    }
                    $aadhar_no = str_replace("'", "", $aadhar_no);
                    $form_data = array(
                        "emp_code" => $emp_code,
                        "biomatric_id" => $biomatric_id,
                        "first_name" => $first_name,
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
                        "anniversary_date" => $anniversary_date,
                        "identification_masks" => $identification_masks,
                        "department_id" => $department_id,
                        "designation_id" => $designation_id,
                        "grade_id" => $grade_id,
                        "date_of_joining" => $date_of_joining,
                        "job_location" => $job_location,
                        "shift_id" => $time,
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
                        "bank_id" => $bank_id,
                        "acc_holder_name" => $acc_holder_name,
                        "account_no" => $account_no,
                        "ifsc_code" => $ifsc_code,
                        "pf_uan" => $pf_uan,
                        "esic_no" => $esic_no,
                        "pf_joining_date" => $pf_joining_date,
                        "esic_joining_date" => $esic_joining_date,
                        "opening_balance" => $opening_leave,
                        "used_opening_balance" => $opening_leave,
                        "opening_date" => $opening_date,
                        "form21_last_date" => $last_date,
                        "is_form21_last_date" => $is_form21_last_date,
                        //"uan_no" => $uan_no,
                        "createdby"   => $loginid,
                        "ipaddress"   => $ipaddress,
                        "sessionid"   => $sessionid,
                        "createdate"   => $createdate,
                        "unit_id"   => $unitid
                    );

                        if ($existing_emp_id > 0) {

                            $obj->update_record(
                                $tblname,
                                ["emp_id" => $existing_emp_id],
                                $form_data
                            );

                        } else {

                            $obj->insert_record($tblname, $form_data);
                        }

                    $insertedCount++;
                }
            }
        }
    }
    //die;
    $skippedData = urlencode(json_encode($skippedEpicNumbers));
    echo "<script>
location = '$pagename?action=1&total=$totalRecords&inserted=$insertedCount&skipped=$skippedCount&skipped_epics=$skippedData';
</script>";
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
                                                <b> Note:</b> <br>
                                                • Biometric ID is compulsory.<br>
                                                • Biometric ID, Employee Code, and Mobile Number must be unique.<br>
                                                • Rows with missing or duplicate values will be skipped during upload.
                                            </small>
                                        </div>

                                        <div class="col-md-4 text-end">
                                            <a href="employee_excel_new.xlsx" class="btn btn-primary btn-sm">
                                                Download Sample File
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <strong><label for="">Upload File <span
                                                        class="text-danger fw-bold">*</span></label></strong>
                                            <input type="file" name="upload_excel" id="upload_excel"
                                                class="form-control form-control-sm" accept=".xlsx">
                                        </div>
                                        <div class="col-lg-4 mb-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary btn-sm"
                                                value="<?php echo $btn_name ?> "
                                                onClick="return checkinputmaster('upload_excel')">
                                            <a href="<?php echo $pagename ?>" type="button"
                                                class="btn btn-danger btn-sm">Reset</a>
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
                    <?php
                        if (!empty($_GET['skipped_epics'])) {
                            $skippedEpicNumbers = json_decode(urldecode($_GET['skipped_epics']), true);

                            echo "<table   cellpadding='5'>
                            <tr>
                                <th>Row Number</th>
                                <th>Emp Code</th>
                                <th>Biometric ID</th>
                                
                                <th>Mobile No</th>
                                 <th>Reason</th>
                            </tr>";

                            foreach ($skippedEpicNumbers as $row) {
                                echo "<tr>
                               <td>{$row['row_no']}</td>
        <td>{$row['emp_code']}</td>
        <td>{$row['biomatric_id']}</td>
        <td>{$row['mobile_no']}</td>
        <td>{$row['reason']}</td>
                            </tr>";
                            }

                            echo "
                        </table>";
                        }
                        ?>
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