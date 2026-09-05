<?php include("../adminsession.php");
$pagename = "employee_master.php";
$title = "Employee Information";
$btn_name = "Save";
$tblname = "employee_master";
$tblpkey = "emp_id";
$imgpath = '../uploaded/emp_documents/';
$imgpath1 = 'uploaded/emp_documents/';
$mode = (isset($_GET['mode'])) ? $obj->test_input($_GET['mode']) :'New';
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$created_time = date('H:i:s');

if (isset($_POST['submit'])) {

    $emp_code = $obj->test_input($_POST['emp_code']);
    $biomatric_id = $obj->test_input($_POST['biomatric_id']);
    $first_name = $obj->test_input($_POST['first_name']);
    //$last_name = $obj->test_input($_POST['last_name']);
    $father_name = $obj->test_input($_POST['father_name']);
    $gender = $obj->test_input($_POST['gender']);
    $dob = $obj->test_input($_POST['dob']);
    $age = $obj->test_input($_POST['age']);
    $blood_group = $obj->test_input($_POST['blood_group'] ?? '');
    $marital_status = $obj->test_input($_POST['marital_status']);
    $nationality = $obj->test_input($_POST['nationality']);
    $religion = $obj->test_input($_POST['religion']);
    $caste = $obj->test_input($_POST['caste'] ?? '');

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
    $driving_licence_cat_id = $obj->test_input($_POST['driving_licence_cat_id']??0);
    $is_mannual_att = $obj->test_input($_POST['is_mannual_att']??0);
    $driving_lic_expiry_date = $obj->test_input($_POST['driving_lic_expiry_date']);
    $passport_no = $obj->test_input($_POST['passport_no']);
    $identification_masks = $obj->test_input($_POST['identification_masks']);

    $department_id = $obj->test_input($_POST['department_id'] ?? '0');
    $designation_id = $obj->test_input($_POST['designation_id'] ?? '0');
    $grade_id = $obj->test_input($_POST['grade_id'] ?? '0');
    $date_of_joining = $obj->test_input($_POST['date_of_joining']);
    $job_location = $obj->test_input($_POST['job_location']);
    $shift_id = $obj->test_input($_POST['shift_id'] ?? '0');
    $employee_type = $obj->test_input($_POST['employee_type']);
    $reporting_manager = $obj->test_input($_POST['reporting_manager']??0);
    $anniversary_date = $obj->test_input($_POST['anniversary_date']);

    $employer_name = $obj->test_input($_POST['employer_name']);
    $employer_designation_id = $obj->test_input($_POST['employer_designation_id'] ?? '0');
    $service_from = $obj->test_input($_POST['service_from']);
    $service_to = $obj->test_input($_POST['service_to']);
    $reason = $obj->test_input($_POST['reason']);
    $job_responsibility = $obj->test_input($_POST['job_responsibility']);
    $last_salary = $obj->test_input($_POST['last_salary']);

    $basic_salary = $obj->test_input($_POST['basic_salary']??0);
    $emp_category = $obj->test_input($_POST['emp_category']??"");
    $opening_balance = $obj->test_input($_POST['opening_balance']??0);
    $coff = $obj->test_input($_POST['coff']??0);
    $ecoff = $obj->test_input($_POST['ecoff']??0);
    $opening_date = $obj->test_input($_POST['opening_date']??'');
 
    $is_pf = $obj->test_input($_POST['is_pf'] ?? 0);
    $is_esic = $obj->test_input($_POST['is_esic'] ?? 0);
    $is_rejoin = $obj->test_input($_POST['is_rejoin'] ?? 0);
    $allow_weekly_off = $obj->test_input($_POST['allow_weekly_off'] ?? 0);
    $allow_add_leave = $obj->test_input($_POST['allow_add_leave'] ?? 0);
    $is_perform_incen = $obj->test_input($_POST['is_perform_incen'] ?? 0);
    $is_active = $obj->test_input($_POST['is_active'] ?? 0);

    $pf_uan = $obj->test_input($_POST['pf_uan']);
    $uan_no = $obj->test_input($_POST['uan_no']);
    $esic_no = $obj->test_input($_POST['esic_no']);
    $pf_joining_date = $obj->test_input($_POST['pf_joining_date']);
    $esic_joining_date = $obj->test_input($_POST['esic_joining_date']);

    $is_form21_last_date = $obj->test_input($_POST['is_form21_last_date'] ?? 0);
    $form21_last_date = $obj->test_input($_POST['form21_last_date'] ?? '');

    $document_ids = '';
    if (isset($_POST['document_check_list']) && is_array($_POST['document_check_list'])) {
        $docArray = array_map('intval', $_POST['document_check_list']);
        $document_ids = implode(',', $docArray);
    }
    $profile_image =  $_FILES['profile_image'] ?? '';
    $emp_sign =  $_FILES['emp_sign'] ?? '';

    
    $form_data_emp_status = array( 
        'unit_id' => $unitid,     
        'is_active' => $is_active,
        'last_inactive_date' => $createdate,
        'last_inactive_month' => date('n'),
        "createdate" => $createdate,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid
    );

     $form_data_emp_week_status = array( 
        'unit_id' => $unitid,     
        'type' => 'allow_weekoff',     
        'is_allow' => $allow_weekly_off, 
        'last_inactive_month' => date('n'),
        'last_inactive_year' => date('Y'),
        "createdate" => $createdate,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid
    );
    


    $form_data = array(
        "first_name" => $first_name,
        // "last_name" => $last_name,
        "father_name" => $father_name,
        "gender" => $gender,
        "dob" => $dob,
        "age" => $age,
        "blood_group" => $blood_group,
        "marital_status" => $marital_status,
        "nationality" => $nationality,
        "religion" => $religion,
        "caste" => $caste,
        "anniversary_date" => $anniversary_date,

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
        "driving_licence_cat_id" => $driving_licence_cat_id,
        "is_mannual_att" => $is_mannual_att,
        "driving_lic_expiry_date" => $driving_lic_expiry_date,
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
        "form21_last_date" => $form21_last_date,
        "is_form21_last_date" => $is_form21_last_date,
        "basic_salary" => $basic_salary,
        "emp_category" => $emp_category,
        "opening_balance" => $opening_balance,
        "used_opening_balance" => $opening_balance,
        "ecoff" => $ecoff,
        "coff" => $coff, 
        "opening_date" => $opening_date,
        "is_pf" => $is_pf,
        "is_esic" => $is_esic,
        "allow_weekly_off" => $allow_weekly_off,
        "allow_add_leave" => $allow_add_leave,
        "is_perform_incen" => $is_perform_incen,
        "is_active" => $is_active,
        "last_active_date" => $createdate,
        "is_rejoin" => $is_rejoin,
        "pf_uan" => $pf_uan,
        "uan_no" => $uan_no,
        "esic_no" => $esic_no,
        "pf_joining_date" => $pf_joining_date,
        "esic_joining_date" => $esic_joining_date,
        "document_checked_ids" => $document_ids,
        "createdby"   => $loginid,
        "ipaddress"   => $ipaddress,
        "sessionid"   => $sessionid,
        "unit_id"   => $unitid
    );

    $form_data_unit_transfer = array(
        'joining_date' => $date_of_joining,
        'unit_id' => $unitid,     
        'department_id' => $department_id,
        'designation_id' => $designation_id,
        'basic_salary' => $basic_salary,
        'shift_hrs' => $shift_id,
        "createdate" => $createdate,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid
    );
    $form_data_emp_promotion = array(
        'promotion_date' => $date_of_joining,
        'unit_id' => $unitid,     
        'type' => 'promotion',     
        'is_initial' => '1',     
        'department_id' => $department_id,
        'designation_id' => $designation_id,
        'basic_salary' => $basic_salary,
        'status' => '1',
        "createdate" => $createdate,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid
    );

    $allowedTypes = ['jpg', 'jpeg', 'png'];
    $imageName = $_FILES["profile_image"]['name'] ?? '';
    $imageName2 = $_FILES["emp_sign"]['name'] ?? '';
    $imageFileType = !empty($imageName) ? strtolower(pathinfo($imageName, PATHINFO_EXTENSION)) : '';
    $imageFileType2 = !empty($imageName2) ? strtolower(pathinfo($imageName2, PATHINFO_EXTENSION)) : '';


    $count = $obj->getvalfield($tblname, "count(*)", "(biomatric_id = '$biomatric_id' OR emp_code = '$emp_code' OR mobile_no = '$mobile_no') and unit_id='$unitid' and $tblpkey!='$keyvalue'");

    $duplicate_field = "";

    // if (!empty($biomatric_id)) {
    //     $check = $obj->getvalfield($tblname, "count(*)", "biomatric_id='$biomatric_id' AND $tblpkey!='$keyvalue'");
    //     if ($check > 0) {
    //         $duplicate_field = "Biometric ID";
    //     }
    // }

    // if (empty($duplicate_field) && !empty($emp_code)) {
    //     $check = $obj->getvalfield($tblname, "count(*)", "emp_code='$emp_code' AND $tblpkey!='$keyvalue'");
    //     if ($check > 0) {
    //         $duplicate_field = "Employee Code";
    //     }
    // }

    if (empty($duplicate_field) && !empty($mobile_no)) {
        $check = $obj->getvalfield($tblname, "count(*)", "mobile_no='$mobile_no' AND unit_id='$unitid' AND $tblpkey!='$keyvalue'");
        if ($check > 0) {
            $duplicate_field = "Mobile Number";
        }
    }


    if (!empty($duplicate_field)) {
        $action = 5;
        $process = "duplicate";

        echo "<script>location='$pagename?action=$action&field=" . urlencode($duplicate_field) . "'</script>";
        exit;
    } else {
        if ($keyvalue == 0) {
            $if_exist = $obj->getvalfield("employee_master", "count(*)", "emp_code='$emp_code'");
            if ($if_exist > 0) {
                $emp_code =   $obj->getcode("employee_master", "emp_code",  "1=1");

                $biomatric_id =   $obj->getcode("employee_master", "emp_code",  "1=1");
            }

            if (isset($_FILES["profile_image"]) && !empty($_FILES["profile_image"]['name'])) {
                $imageFileType = strtolower(pathinfo($_FILES["profile_image"]['name'], PATHINFO_EXTENSION));
                if (in_array($imageFileType, $allowedTypes)) {
                    $profile_image = $obj->uploadImage($imgpath1, $_FILES["profile_image"]);
                    $form_data['profile_image'] = $profile_image;
                }
            }
            if (isset($_FILES["emp_sign"]) && !empty($_FILES["emp_sign"]['name'])) {
                $imageFileType2 = strtolower(pathinfo($_FILES["emp_sign"]['name'], PATHINFO_EXTENSION));
                if (in_array($imageFileType2, $allowedTypes)) {
                    $emp_sign = $obj->uploadImage($imgpath1, $_FILES["emp_sign"]);
                    $form_data['emp_sign'] = $emp_sign;
                }
            }
            $form_data["createdate"] = $createdate;
            $form_data["password"] = '123';
            $form_data["emp_code"] = $emp_code;
            $form_data["biomatric_id"] = $biomatric_id;

            $lastid = $obj->insert_record_lastid($tblname, $form_data);

            $obj->update_record("emp_family_details", array("emp_id" => 0, 'unit_id' => $unitid, "sessionid" => $sessionid, 'createdby' => $loginid), array("emp_id" => $lastid));

            $obj->update_record("emp_education", array("emp_id" => 0, 'unit_id' => $unitid, "sessionid" => $sessionid, 'createdby' => $loginid), array("emp_id" => $lastid));

            $obj->update_record("emp_document", array("emp_id" => 0, 'unit_id' => $unitid, "sessionid" => $sessionid, 'createdby' => $loginid), array("emp_id" => $lastid));

            $obj->update_record("emp_language", array("emp_id" => 0, 'unit_id' => $unitid, "sessionid" => $sessionid, 'createdby' => $loginid), array("emp_id" => $lastid));

            $obj->update_record("emp_bank_details", array("emp_id" => 0, 'unit_id' => $unitid, "sessionid" => $sessionid, 'createdby' => $loginid), array("emp_id" => $lastid));

            $form_data1 = array(
                "primary_id" => $lastid,
                "flag" => $title,
                "activity_type" => 'Inserted',
                "createdby" => $loginid,
                "pagename" => $pagename,
                "created_date" => $createdate,
                "created_time" => $created_time,
                "unit_id" => $unitid,
                'ipaddress' => $ipaddress,
                "sessionid" => $sessionid
            );

            $logactivity = $obj->insert_record("logactivity_master", $form_data1);

            $action = 1;
            $process = "insert";
           
            $form_data_unit_transfer["emp_id"] = $lastid;
            $form_data_emp_promotion["emp_id"] = $lastid;
            $obj->insert_record("emp_branch_transfer", $form_data_unit_transfer);
            $obj->insert_record("emp_promotion", $form_data_emp_promotion);
 
             
            $form_data_emp_status["emp_id"] = $lastid;
            $obj->insert_record("emp_active_status", $form_data_emp_status);

            $form_data_emp_week_status["emp_id"] = $lastid;
            $obj->insert_record("emp_allow_week_status", $form_data_emp_week_status);
           
        } else {
            if (!empty($imageName) && in_array($imageFileType, $allowedTypes)) {
                $old = $obj->getvalfield($tblname, "profile_image", "emp_id='$keyvalue'");
                if (!empty($old)) {
                    @unlink($imgpath1 . $old);
                }
                $filename = $obj->uploadImage($imgpath1, $_FILES["profile_image"]);
                $form_data['profile_image'] = $filename;
            }
            if (!empty($imageName2) && in_array($imageFileType2, $allowedTypes)) {
                $old = $obj->getvalfield($tblname, "emp_sign", "emp_id='$keyvalue'");
                if (!empty($old)) {
                    @unlink($imgpath1 . $old);
                }
                $filename = $obj->uploadImage($imgpath1, $_FILES["emp_sign"]);
                $form_data['emp_sign'] = $filename;
            }
            //print_r($form_data);

            $form_data["lastupdated"] = $createdate;
            $form_data["updatedby"] = $loginid;
            $form_data["emp_code"] = $emp_code;
            $form_data["biomatric_id"] = $biomatric_id;
            $where = array($tblpkey => $keyvalue);
            $obj->update_record($tblname, $where, $form_data);

            $where2 = array('emp_id' => $keyvalue,'unit_id'=>$unitid);
            $obj->update_record('emp_branch_transfer', $where2, $form_data_unit_transfer);
            $obj->update_record('emp_promotion', $where2, $form_data_emp_promotion);
 
            $form_data_emp_status["emp_id"] = $keyvalue;
            $obj->insert_record("emp_active_status", $form_data_emp_status); 
            
            $form_data_emp_week_status["emp_id"] = $keyvalue;
            $obj->insert_record("emp_allow_week_status", $form_data_emp_week_status);
           
            $where2 = array('emp_id' => $keyvalue,'unit_id'=>$unitid);

            $form_data1 = array(
                "primary_id" => $keyvalue,
                "flag" => $title,
                "activity_type" => 'Updated',
                "createdby" => $loginid,
                "pagename" => $pagename,
                "created_date" => $createdate,
                "created_time" => $created_time,
                "unit_id" => $unitid,
                'ipaddress' => $ipaddress,
                "sessionid" => $sessionid
            );
            $logactivity = $obj->insert_record("logactivity_master", $form_data1);
            // die;
            $action = 2;
            $process = "updated";
        }
    }
    //die;
    echo "<script>location='$pagename?action=$action'</script>";
}

if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $emp_code = $sqledit['emp_code'];
    $profile_image  = $sqledit['profile_image'] ?? '';
    $emp_sign  = $sqledit['emp_sign'] ?? '';

    $biomatric_id = $sqledit['biomatric_id'];
    $first_name = $sqledit['first_name'];
    $last_name = $sqledit['last_name'];
    $father_name = $sqledit['father_name'];
    $gender = $sqledit['gender'];
    $dob = $sqledit['dob'];
    $age = $sqledit['age'];
    $blood_group = $sqledit['blood_group'];
    $marital_status = $sqledit['marital_status'];
    $nationality = $sqledit['nationality'] ?? 'Indian';
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
    $driving_licence_cat_id = $sqledit['driving_licence_cat_id'];
    $driving_lic_expiry_date = $sqledit['driving_lic_expiry_date'];
    $passport_no = $sqledit['passport_no'];
    $identification_masks = $sqledit['identification_masks'];

    $department_id = $sqledit['department_id'];
    $designation_id = $sqledit['designation_id'];
    $grade_id = $sqledit['grade_id'];
    $date_of_joining = $sqledit['date_of_joining'];
    $job_location = $sqledit['job_location'] ?? 'Raigarh';
    $shift_id = $sqledit['shift_id'];
    $employee_type = $sqledit['employee_type'];
   $reporting_manager = ($sqledit['reporting_manager'] == 0) ? '' : $sqledit['reporting_manager'];

    $employer_name = $sqledit['employer_name'];
    $employer_designation_id = $sqledit['employer_designation_id'];
    $service_from = $sqledit['service_from'];
    $service_to = $sqledit['service_to'];
    $reason = $sqledit['reason'];
    $job_responsibility = $sqledit['job_responsibility'];
    $last_salary = $sqledit['last_salary'];

    $basic_salary = $sqledit['basic_salary'];
    $emp_category = $sqledit['emp_category'];
    $opening_balance = $sqledit['opening_balance'];
    $ecoff = $sqledit['ecoff'];
    $coff = $sqledit['coff'];
    $opening_date = $sqledit['opening_date'];
   
    $is_pf = $sqledit['is_pf'];
    $is_esic = $sqledit['is_esic'];
    $allow_weekly_off = $sqledit['allow_weekly_off'];
    $allow_add_leave = $sqledit['allow_add_leave'];
    $is_perform_incen = $sqledit['is_perform_incen'];
    $is_active = $sqledit['is_active'];
    $is_rejoin = $sqledit['is_rejoin'];
    $form21_last_date = $sqledit['form21_last_date'];
    $is_form21_last_date = $sqledit['is_form21_last_date'];
    $is_mannual_att = $sqledit['is_mannual_att'];
   
    $pf_uan = $sqledit['pf_uan'];
    $uan_no = $sqledit['uan_no'];
    $esic_no = $sqledit['esic_no'];
    $pf_joining_date = $sqledit['pf_joining_date'];
    $esic_joining_date = $sqledit['esic_joining_date'];
    $document_ids = $sqledit['document_checked_ids'];
    $checkedDocs = [];
    if (!empty($document_ids)) {
        $checkedDocs = explode(',', $document_ids);
    }
    // print_r($is_form21_last_date);
    // die;
    // echo $nationality;
    // die;
} else {
    $emp_code = $biomatric_id = $obj->getcode("employee_master", "emp_code",  "1='1'");
    $first_name = $last_name = $father_name  = $dob = $age = $blood_group = $marital_status =   $religion = $caste = $mobile_no = $alt_mobile_no = $email_id = $present_address = $permanent_address = $emer_contact_name = $emer_contact_relation = $emer_contact_no = $aadhar_no = $pan_no = $driving_license=$driving_licence_cat_id =$driving_lic_expiry_date= $passport_no = $identification_masks = $department_id = $designation_id = $grade_id = $date_of_joining =   $shift_id = $employee_type = $reporting_manager = $employer_name = $employer_designation_id = $service_from = $service_to = $reason = $job_responsibility = $last_salary = $profile_image = $emp_sign = $basic_salary =$emp_category= $opening_balance = $opening_date = $hra = $da = $conveyance = $medical_allowance = $special_allowance = $is_pf = $is_esic =$allow_weekly_off=$allow_add_leave= $is_perform_incen= $is_active=$is_rejoin = $is_pt = $is_lwf = $ctc = $gross_salary = $net_salary = $anniversary_date= $pf_uan = $uan_no = $esic_no = $pf_joining_date = $esic_joining_date = $document_ids = $form21_last_date =  $last_name = '';
    $is_form21_last_date = $ecoff=$coff =$is_mannual_att='0';
    $nationality = 'INDIAN';
    $job_location = 'Raigarh';
    $gender = 'Male';
}

?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?></title>
    <?php include('inc/css.php') ?>
    <link rel="stylesheet" href="assets/css/toogle.css">
    <link rel="stylesheet" href="https://unpkg.com/cropperjs@1.6.2/dist/cropper.min.css">
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

.nav-justified .nav-item {
    flex-basis: auto;
    border: 1px solid white;
}

table,
tr,
td {
    padding: 10px 10px 10px 10px !important;
    white-space: nowrap;
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
                                                <h5 class="card-title mb-0">Employee Information <a href="emp_list.php"
                                                        class="float-end btn btn-primary btn-sm">Emp List</a> </h5>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if ($keyvalue > 0) { ?>
                                    <div class="col-lg-12 text-center mb-4  ">
                                        <h5 class="card-title  text-primary">Employee Name :
                                            <?= $first_name . $last_name . " - " . $emp_code ?> </h5>
                                    </div>
                                    <?php } ?>
                                    <!-- ⭐ TABS BAR (10 Steps) -->
                                    <div class="step-arrow-nav">
                                        <ul class="nav nav-tabs nav-justified mb-4" id="stepTabs">
                                            <li class="nav-item cursor-pointer"><a class="nav-link p-1 active fw-bold"
                                                    data-step="0">EMPLOYEE INFORMATION</a></li>
                                            <!-- <li class="nav-item cursor-pointer"><a class="nav-link p-1 fw-bold"
                                                    data-step="1">BANK DETAILS</a></li> -->
                                            <li class="nav-item cursor-pointer"><a class="nav-link p-1 fw-bold"
                                                    data-step="1">CONTACT</a></li>
                                            <li class="nav-item cursor-pointer"><a class="nav-link p-1 fw-bold"
                                                    data-step="2">FAMILY/DOCUMENTS/LANGUAGE</a></li>
                                            <li class="nav-item cursor-pointer"><a class="nav-link p-1 fw-bold"
                                                    data-step="3">CHECK LIST</a></li>
                                        </ul>

                                        <!-- ⭐ FORM START -->
                                        <!-- <form id="multiStepForm"> -->

                                        <!-- ⭐ STEP 1 : BASIC EMPLOYEE INFO -->
                                        <div class="form-step form-step-active">

                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="form-label">Employee Code <span class="text-danger">
                                                            *</span> </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="emp_code" id="emp_code" placeholder="Enter Code"
                                                        value="<?= $emp_code ?>" onkeypress="numberOnly(event);"
                                                        onchange="checkDuplicateEmpCode(this.value)" readonly>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Biometric ID<span class="text-danger">
                                                            *</span> </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="biomatric_id" id="biomatric_id" placeholder="Enter Code"
                                                        value="<?= $biomatric_id ?>" onkeypress="numberOnly(event);"
                                                        readonly>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Emp Name<span class="text-danger">
                                                            *</span> </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="first_name" id="first_name" value="<?= $first_name ?>"
                                                        placeholder="Enter First Name">
                                                </div>

                                                <!-- <div class="col-md-3">
                                                    <label class="form-label">Last Name<span class="text-danger"> *</span> </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="last_name" id="last_name"   placeholder="Enter Last Name">
                                                </div> -->

                                                <div class="col-md-3">
                                                    <label class="form-label">Father’s Name <span class="text-danger">
                                                            *</span></label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="father_name" id="father_name" value="<?= $father_name ?>"
                                                        placeholder="Enter Father Name">
                                                </div>

                                                <div class="col-lg-3 ">
                                                    <label for="department_id" class="form-label">Department<span
                                                            class="text-danger fw-bold">*</span></label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="department_id" id="department_id"
                                                        onchange="get_designation(this.value);">
                                                        <option value="">Select</option>
                                                        <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_name asc");
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
                                                <div class="col-lg-3 ">
                                                    <label for="designation_id" class="form-label">Designation<span
                                                            class="text-danger fw-bold">*</span></label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="designation_id" id="designation_id">
                                                        <option value="">Please Select</option>

                                                    </select>

                                                </div>
                                                <div class="col-md-3">
                                                    <label>Date of Joining<span
                                                            class="text-danger fw-bold">*</span></label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        name="date_of_joining" id="date_of_joining"
                                                        value="<?= $date_of_joining  ?>" max="<?= date('Y-m-d') ?>">
                                                </div>

                                                <div class="col-md-3 mt-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="is_rejoin"
                                                            id="is_rejoin" value="1"
                                                            <?= ($is_rejoin == 1) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="is_rejoin">
                                                            Is Rejoin
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Present Salary<span
                                                            class="text-danger fw-bold">*</span></label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="basic_salary" id="basic_salary"
                                                        value="<?= $basic_salary  ?>" onkeypress="numberOnly(event);"
                                                        placeholder="Enter Basic Salary">
                                                </div>

                                                <div class="col-lg-2 ">
                                                    <label for="emp_category" class="form-label">Employee category<span
                                                            class="text-danger fw-bold"> </span></label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="emp_category" id="emp_category">
                                                        <option value="">Select</option>
                                                        <option value="Unskilled">Unskilled</option>
                                                        <option value="Semiskilled">Semiskilled</option>
                                                        <option value="Skilled">Skilled</option>
                                                        <option value="Highskilled">Highskilled</option>
                                                    </select>
                                                    <script>
                                                    document.getElementById('emp_category').value =
                                                        '<?= $emp_category; ?>';
                                                    </script>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Date of Birth <span
                                                            class="text-danger fw-bold">*</span></label>
                                                    <input type="date" class="form-control form-control-sm" name="dob"
                                                        id="dob" value="<?= $dob ?>" onkeyup="calculateAge()">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Age</label>
                                                    <input type="text" class="form-control form-control-sm" name="age"
                                                        id="age" value="<?= $age ?>" placeholder="Enter Age"
                                                        autocomplete="off" onkeypress="numberOnly(event);" maxlength="3"
                                                        readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Blood Group <span
                                                            class="text-danger fw-bold"></span></label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="blood_group" id="blood_group">
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
                                                    document.getElementById('blood_group').value = '<?= $blood_group ?>'
                                                    </script>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Mobile Number <span class="text-danger">
                                                            *</span> </label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="mobile_no" id="mobile_no" value="<?= $mobile_no ?>"
                                                        placeholder="Enter Number" autocomplete="off"
                                                        onkeypress="numberOnly(event);" maxlength="10"
                                                        onchange="checkDuplicateMobile(this.value)">
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
                                                    <label class="form-label">Aadhaar No <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="aadhar_no" id="aadhar_no" value="<?= $aadhar_no ?>"
                                                        placeholder="Enter Aadhaar Number"
                                                        onchange="checkDuplicateAadhar(this.value)"
                                                        onkeypress="numberOnly(event);" maxlength="12">
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">PAN No</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="pan_no" id="pan_no" value="<?= $pan_no ?>"
                                                        placeholder="Enter PAN Number"
                                                        onchange="checkDuplicatePanCard(this.value)" maxlength="10">
                                                </div>
                                                <div class="col-lg-3 ">
                                                    <label for="grade_id" class="form-label">Grade<span
                                                            class="text-danger fw-bold"></span></label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="grade_id" id="grade_id">
                                                        <option value="">Select</option>
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

                                                <div class="col-lg-2 ">
                                                    <label for="shift_id" class="form-label">Shift Code<span
                                                            class="text-danger fw-bold">*</span> </label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="shift_id" id="shift_id">
                                                        <option value="">Select</option>
                                                        <?php $res = $obj->executequery("Select * from shift_master where unit_id='$unitid' group by working_hour order by working_hour asc");
                                                        foreach ($res as $key) { ?>
                                                        <option value="<?= $key['working_hour']; ?>">
                                                            <?= $obj->getCustomCode($key['working_hour'])  ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <script>
                                                    document.getElementById('shift_id').value = '<?= $shift_id; ?>';
                                                    </script>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Is PF<span
                                                            class="text-danger fw-bold">*</span> </label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="is_pf" id="is_pf">
                                                        <option value="">Select</option>
                                                        <option value="1">YES</option>
                                                        <option value="0">NO</option>
                                                    </select>
                                                    <script>
                                                    document.getElementById('is_pf').value = '<?php echo $is_pf; ?>';
                                                    </script>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Is ESI<span
                                                            class="text-danger fw-bold">*</span> </label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="is_esic" id="is_esic">
                                                        <option value="">Select</option>
                                                        <option value="1">YES</option>
                                                        <option value="0">NO</option>
                                                    </select>
                                                    <script>
                                                    document.getElementById('is_esic').value =
                                                        '<?php echo $is_esic; ?>';
                                                    </script>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Allow Weekly Off<span
                                                            class="text-danger fw-bold">*</span> </label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="allow_weekly_off" id="allow_weekly_off">
                                                        <option value="">Select</option>
                                                        <option value="1">YES</option>
                                                        <option value="0">NO</option>
                                                    </select>
                                                    <script>
                                                    document.getElementById('allow_weekly_off').value =
                                                        '<?php echo $allow_weekly_off; ?>';
                                                    </script>
                                                </div>
                                                
                                                <div class="col-md-2">
                                                    <label class="form-label">Performance Incentive<span
                                                            class="text-danger fw-bold">*</span> </label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="is_perform_incen" id="is_perform_incen">
                                                        <option value="">Select</option>
                                                        <option value="1">YES</option>
                                                        <option value="0">NO</option>
                                                    </select>
                                                    <script>
                                                    document.getElementById('is_perform_incen').value =
                                                        '<?php echo $is_perform_incen; ?>';
                                                    </script>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Is Active<span
                                                            class="text-danger fw-bold">*</span> </label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="is_active" id="is_active">
                                                        <option value="">Select</option>
                                                        <option value="1">YES</option>
                                                        <option value="0">NO</option>
                                                    </select>
                                                    <script>
                                                    document.getElementById('is_active').value =
                                                        '<?php echo $is_active; ?>';
                                                    </script>
                                                </div>
                                                 <div class="col-md-2">
                                                    <label class="form-label">Add Leave on Week Offs<span
                                                            class="text-danger fw-bold">*</span> </label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="allow_add_leave" id="allow_add_leave">
                                                        <option value="">Select</option>
                                                        <option value="1">YES</option>
                                                        <option value="0">NO</option>
                                                    </select>
                                                    <script>
                                                    document.getElementById('allow_add_leave').value =
                                                        '<?php echo $allow_add_leave; ?>';
                                                    </script>
                                                </div>

                                                <div class="col-md-2"><label>PF NO.</label><input
                                                        class="form-control form-control-sm" placeholder="Enter PF NO."
                                                        name="pf_uan" id="pf_uan" value="<?= $pf_uan ?>"></div>
                                                <div class="col-md-2"><label>UAN NO.</label><input
                                                        class="form-control form-control-sm" placeholder="Enter UAN NO."
                                                        name="uan_no" id="uan_no" value="<?= $uan_no ?>" maxlength="12">
                                                </div>

                                                <div class="col-md-2"><label>ESIC Number</label><input
                                                        class="form-control form-control-sm"
                                                        placeholder="Enter ESIC Number" name="esic_no" id="esic_no"
                                                        value="<?= $esic_no ?>" maxlength="10"></div>

                                                <div class="col-md-3"><label>PF Joining Date</label><input type="date"
                                                        class="form-control form-control-sm"
                                                        placeholder="Enter PF Joining Date" name="pf_joining_date"
                                                        id="pf_joining_date" value="<?= $pf_joining_date ?>"></div>

                                                <div class="col-md-3"><label>ESIC Joining Date</label><input type="date"
                                                        class="form-control form-control-sm" name="esic_joining_date"
                                                        id="esic_joining_date" value="<?= $esic_joining_date ?>"></div>

                                                <div class="col-md-3">
                                                    <label>Reporting Manager<span
                                                            class="text-danger fw-bold">*</span></label>

                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="reporting_manager" id="reporting_manager">
                                                        <option value="">Select</option>
                                                        <?php
                                                        $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                        foreach ($res as $key) { ?>
                                                        <option value="<?= $key['emp_id']; ?>">
                                                            <?= $key['emp_code']; ?> -
                                                            <?= ucfirst($key['first_name'] ?? ''); ?>
                                                            <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <script>
                                                    document.getElementById('reporting_manager').value =
                                                        '<?= $reporting_manager; ?>';
                                                    </script>

                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Marital Status<span
                                                            class="text-danger fw-bold"></span></label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="marital_status" id="marital_status"
                                                        onchange="toggleAnniversary()">
                                                        <option value="">Select</option>
                                                        <option value="Unmarried">Unmarried</option>
                                                        <option value="Married">Married</option>
                                                        <option value="Divorced">Divorced</option>
                                                    </select>
                                                    <script>
                                                    document.getElementById('marital_status').value =
                                                        '<?php echo ucfirst(strtolower($marital_status)); ?>';
                                                    </script>
                                                </div>
                                                <div class="col-md-3" id="anniversary_div" style="display: none;">
                                                    <label class="form-label" for="anniversary_date">Anniversary
                                                        Date</label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        name="anniversary_date" id="anniversary_date"
                                                        value="<?= $anniversary_date ?>">
                                                </div>



                                                <div class="col-md-6">
                                                    <label class="form-label">Present Address <span
                                                            class="text-danger fw-bold">*</span> </label>
                                                    <textarea class="form-control form-control-sm" rows="1"
                                                        name="present_address"
                                                        id="present_address"><?= $present_address ?></textarea>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Permanent Address<span
                                                            class="text-danger fw-bold"> </span> </label>
                                                    <textarea class="form-control form-control-sm" rows="1"
                                                        name="permanent_address"
                                                        id="permanent_address"><?= $permanent_address ?></textarea>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Gender<span class="text-danger fw-bold">
                                                        </span> </label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="gender" id="gender">
                                                        <option value="">Select</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                    <script>
                                                    document.getElementById('gender').value =
                                                        '<?php echo ucfirst(strtolower($gender)); ?>';
                                                    </script>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Profile Image <span
                                                            class="text-danger fw-bold"> </span></label>
                                                    <input type="file" class="form-control form-control-sm"
                                                        name="profile_image" id="profile_image" accept="image/*">
                                                    <?php if ($profile_image != "") {
                                                    ?>
                                                    <img src="<?php echo $imgpath1 . $profile_image;  ?>"
                                                        style="height: 50px;" alt="">
                                                    <?php
                                                    } ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Employee Signature <span
                                                            class="text-danger fw-bold">(Please Remove Image background
                                                            then Upload)</span><a href="https://www.remove.bg/"
                                                            target="_blank"> click here</a></label>
                                                    <input type="file" class="d-none" name="emp_sign" id="emp_sign"
                                                        accept="image/jpeg,image/png">
                                                    <button type="button" class="btn btn-outline-primary btn-sm w-100"
                                                        onclick="document.getElementById('emp_sign').click();">
                                                        <i class="ri-upload-2-line"></i> Choose Signature
                                                    </button>
                                                    <div id="signPreview" class="mt-2 text-center">
                                                        <?php if ($emp_sign != "") { ?>
                                                        <img src="<?php echo $imgpath1 . $emp_sign; ?>"
                                                            style="max-height:60px; border:1px solid #ddd; padding:4px; border-radius:4px;"
                                                            alt="Current Signature">
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Is Manual Att.<span
                                                            class="text-danger fw-bold">*</span> </label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="is_mannual_att" id="is_mannual_att">
                                                        <option value="0">NO</option>
                                                        <option value="1">YES</option>
                                                    </select>
                                                    <script>
                                                    document.getElementById('is_mannual_att').value =
                                                        '<?php echo $is_mannual_att; ?>';
                                                    </script>
                                                </div>


                                                <div class="table-responsive" style="height:250px;">
                                                    <?php if ($keyvalue > 0) { ?>
                                                    <div class="mb-2">
                                                        <button onclick="exportTableToExcel('exportExcel')"
                                                            class="btn btn-sm btn-primary">Excel</button>
                                                    </div>
                                                    <?php } ?>
                                                    <table id="exportExcel" class="table table-bordered table-sm">
                                                        <thead class="table-light">
                                                            <tr class="table-warning">
                                                                <th>Bank Name<span class="text-danger">*</span></th>
                                                                <th>Branch Name<span class="text-danger"></span></th>
                                                                <th>Account Holder Name<span
                                                                        class="text-danger">*</span></th>
                                                                <th>Bank Account Number<span
                                                                        class="text-danger">*</span></th>
                                                                <th>IFSC Code<span class="text-danger">*</span></th>
                                                                <th>Is Active<span class="text-danger"></span></th>
                                                                <th>Action</th>
                                                            </tr>

                                                            <tr>
                                                                <td><select
                                                                        class="form-select form-select-sm chosen-select"
                                                                        name="bank_id" id="bank_id">
                                                                        <option value="">Select</option>
                                                                        <?php $res = $obj->executequery("Select * from bank_master where unit_id='$unitid' order by bank_name asc");
                                                                        foreach ($res as $key) { ?>
                                                                        <option value="<?= $key['bank_id']; ?>">
                                                                            <?= $key['bank_name']; ?></option>
                                                                        <?php } ?>
                                                                    </select></td>
                                                                <td>
                                                                    <input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="branch_name"
                                                                        placeholder="Enter Branch Name">
                                                                </td>
                                                                <td>
                                                                    <input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="acc_holder_name"
                                                                        placeholder="Enter Account Holder Name">
                                                                </td>
                                                                <td>
                                                                    <input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="account_no"
                                                                        placeholder="Enter Account Number"
                                                                        onkeypress="numberOnly(event);">
                                                                </td>
                                                                <td><input class="form-control form-control-sm"
                                                                        id="ifsc_code" placeholder="Enter IFSC Code">
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="d-flex align-items-center wt">
                                                                        <div class="flex-grow-1 ms-2 name">
                                                                            <label class="switch">
                                                                                <input type="checkbox" id="bank_active"
                                                                                    checked>
                                                                                <span class="slider round"> </span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" value="0" id="emp_bank_id">
                                                                     <?php if ($mode !='view') {  ?>
                                                                    <button type="button" class="btn btn-sm btn-success"
                                                                        onclick="save_bank_details();"
                                                                        id="bank_btn">Add</button>
                                                                        <?php } ?>
                                                                </td>
                                                            </tr>

                                                        </thead>

                                                        <tbody id="fetch_bank_details">

                                                        </tbody>
                                                    </table>
                                                </div>



                                            </div>

                                            <div class="mt-4 text-end">
                                                <button type="button" class="btn btn-primary btn-next"
                                                    data-validate="emp_code,biomatric_id,first_name,father_name,department_id,designation_id,date_of_joining,basic_salary,dob,mobile_no,aadhar_no,shift_id,is_pf,is_esic,allow_weekly_off,is_perform_incen,is_active,allow_add_leave,reporting_manager,present_address">
                                                    Next
                                                    →</button>
                                            </div>
                                        </div>
                                        <!-- data-validate="emp_code,biomatric_id,first_name,father_name,department_id,designation_id,date_of_joining,basic_salary,emp_category,dob,blood_group,mobile_no,aadhar_no,shift_id,is_pf,is_esic,allow_weekly_off,is_perform_incen,reporting_manager,marital_status,present_address,permanent_address,gender,profile_image" -->
                                        <!-- ⭐ STEP 2 : CONTACT DETAILS -->


                                        <div class="form-step">

                                            <div class="row g-3">


                                                <div class="col-md-3">
                                                    <label class="form-label">Email <span class="text-danger">
                                                        </span></label>
                                                    <input type="text" class="form-control form-control-sm"
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
                                                        placeholder="Enter Emergency Contact Number" autocomplete="off"
                                                        onkeypress="numberOnly(event);" maxlength="10">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Religion</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="religion" id="religion" value="<?= $religion ?>"
                                                        placeholder="Enter Religion">
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Caste</label>
                                                    <select class="form-select form-select-sm" name="caste" id="caste">
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

                                                <div class="col-md-3">
                                                    <label class="form-label">Driving License</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="driving_license" id="driving_license"
                                                        value="<?= $driving_license ?>"
                                                        placeholder="Enter Driving License">
                                                </div>
                                                <div class="col-lg-3 ">
                                                    <label for="driving_licence_cat_id" class="form-label">Driving
                                                        License Category<span class="text-danger fw-bold">
                                                        </span></label>
                                                    <select class="form-select form-select-sm chosen-select"
                                                        name="driving_licence_cat_id" id="driving_licence_cat_id">
                                                        <option value="">Select</option>
                                                        <?php $res = $obj->executequery("Select * from  driving_licence_cat order by driving_licence_cat_id asc");
                                                        foreach ($res as $key) { ?>
                                                        <option value="<?= $key['driving_licence_cat_id']; ?>">
                                                            <?= $key['short_name']; ?>- <?= $key['licence_cat_name']; ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                    <script>
                                                    document.getElementById('driving_licence_cat_id').value =
                                                        '<?= $driving_licence_cat_id; ?>';
                                                    </script>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Driving License Expiry Date</label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        name="driving_lic_expiry_date" id="driving_lic_expiry_date"
                                                        value="<?= $driving_lic_expiry_date ?>">
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Passport No</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="passport_no" id="passport_no" value="<?= $passport_no ?>"
                                                        placeholder="Enter Passport No">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Identification Marks</label>
                                                    <textarea class="form-control form-control-sm"
                                                        name="identification_masks" id="identification_masks"
                                                        rows="1"><?= $identification_masks ?></textarea>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Nationality</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="nationality" id="nationality" value="<?= $nationality ?>"
                                                        placeholder="Enter Nationality">
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
                                                        '<?= ucfirst(strtolower($employee_type)); ?>';
                                                    </script>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Job Location<span class="text-danger fw-bold">
                                                        </span></label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="job_location" id="job_location"
                                                        value="<?= $job_location  ?>" placeholder="Enter Job Location">
                                                </div>
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
                                                        <?php $res = $obj->executequery("Select * from designation_master where unit_id='$unitid' order by designation asc");
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
                                                        name="service_to" id="service_to" value="<?= $service_to ?>">
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
                                                <div class="col-md-6"><label>Job Responsibilities</label><textarea
                                                        class="form-control form-control-sm" rows="1"
                                                        name="job_responsibility"
                                                        id="job_responsibility"><?= $job_responsibility ?></textarea>
                                                </div>




                                                <h5>Enter qualifications below :</h5>
                                                <div class="table-responsive" style="height: 300px;">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="table-light">
                                                            <tr class="table-warning">
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
                                                                <td><input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="examination"
                                                                        placeholder="Enter Examination"></td>

                                                                <td><input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="university"
                                                                        placeholder="Enter Board/University"></td>

                                                                <td><input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="college"
                                                                        placeholder="Enter College/Institute"></td>

                                                                <td><input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="pass_year" placeholder="Enter Passing Year"
                                                                        oninput="formatPassingYear(this);"></td>

                                                                <td><input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="percentage" placeholder="Enter Percentage"
                                                                        onkeypress="numberOnly(event);"></td>

                                                                <td><input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="subject" placeholder="Enter Subject"></td>

                                                                <td>
                                                                    <input type="hidden" value="0" id="education_id">
                                                                     <?php if ($mode !='view') {  ?>
                                                                    <button type="button" class="btn btn-sm btn-success"
                                                                        onclick="save_emp_education();"
                                                                        id="education_btn">Add</button>
                                                                        <?php } ?>
                                                                </td>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="fetch_education_details">
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>

                                            <div class="mt-4 d-flex justify-content-between">
                                                <button type="button" class="btn btn-secondary btn-prev">←
                                                    Previous</button>
                                                <button type="button" class="btn btn-primary btn-next"
                                                    data-validate="">Next
                                                    →</button>
                                            </div>
                                        </div>




                                        <!-- ⭐ STEP 4 -->
                                        <div class="form-step">

                                            <div class="row g-3">
                                                <div class="table-responsive" style="height: 250px;">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="table-light">
                                                            <tr class="table-warning">
                                                                <th>Document Name <span class="text-danger">*</span>
                                                                </th>
                                                                <th>File<span class="text-danger">*</span></th>
                                                                <th>Expiry Date<span class="text-danger"> </span></th>
                                                                <th>Remark</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <select
                                                                        class="form-select form-select-sm chosen-select"
                                                                        name="doc_id" id="doc_id">
                                                                        <option value="">Select</option>
                                                                        <?php $res = $obj->executequery("Select * from document_master where unit_id='$unitid' order by doc_id asc");
                                                                        foreach ($res as $key) { ?>
                                                                        <option value="<?= $key['doc_id']; ?>">
                                                                            <?= $key['document_name']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </td>

                                                                <td>
                                                                    <input type="file"
                                                                        class="form-control form-control-sm"
                                                                        id="doc_file">
                                                                    <div id="doc_preview" style="margin-top:5px;"></div>
                                                                </td>
                                                                <input type="hidden" id="old_doc_file"
                                                                    name="old_doc_file">

                                                                <td>
                                                                    <input type="date"
                                                                        class="form-control form-control-sm"
                                                                        id="doc_expiry_date">
                                                                </td>
                                                                <td>
                                                                    <input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="doc_remark" placeholder="Enter Remark">
                                                                </td>

                                                                <td>
                                                                    <input type="hidden" value="0" id="emp_doc_id">
 <?php if ($mode !='view') {  ?>
                                                                    <button type="button" class="btn btn-sm btn-success"
                                                                        onclick="save_emp_document();"
                                                                        id="document_btn">Add</button>
                                                                        <?php } ?>
                                                                </td>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="fetch_document_details">
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="table-responsive" style="height: 250px;">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="table-light">
                                                            <tr class="table-warning">
                                                                <th>Languages <span class="text-danger">*</span></th>
                                                                <th>Speak</th>
                                                                <th>Read</th>
                                                                <th>Write</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="language_name"
                                                                        placeholder="Enter Language Name"></td>

                                                                <td>
                                                                    <div class="d-flex gap-3">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="language_speak"
                                                                                id="language_speak_yes" value="1">
                                                                            <label class="form-check-label"
                                                                                for="language_speak_yes">
                                                                                Yes
                                                                            </label>
                                                                        </div>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="language_speak"
                                                                                id="language_speak_no" value="0"
                                                                                checked>
                                                                            <label class="form-check-label"
                                                                                for="language_speak_no">
                                                                                No
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex gap-3">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="language_read"
                                                                                id="language_read_yes" value="1">
                                                                            <label class="form-check-label"
                                                                                for="language_read_yes">
                                                                                Yes
                                                                            </label>
                                                                        </div>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="language_read"
                                                                                id="language_read_no" value="0" checked>
                                                                            <label class="form-check-label"
                                                                                for="language_read_no">
                                                                                No
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <div class="d-flex gap-3">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="language_write"
                                                                                id="language_write_yes" value="1">
                                                                            <label class="form-check-label"
                                                                                for="language_write_yes">
                                                                                Yes
                                                                            </label>
                                                                        </div>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="language_write"
                                                                                id="language_write_no" value="0"
                                                                                checked>
                                                                            <label class="form-check-label"
                                                                                for="language_write_no">
                                                                                No
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <input type="hidden" value="0" id="emp_language_id">
                                                                     <?php if ($mode !='view') {  ?>
                                                                    <button type="button" class="btn btn-sm btn-success"
                                                                        onclick="save_emp_language();"
                                                                        id="language_btn">Add</button>
                                                                        <?php } ?>
                                                                </td>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="fetch_language_details">
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="table-responsive" style="height: 250px;">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="table-light">
                                                            <tr class="table-warning">
                                                                <th>Family Member Name<span class="text-danger">*</span>
                                                                </th>
                                                                <th>Relation<span class="text-danger">*</span></th>
                                                                <th>Date of Birth</th>
                                                                <th>Address<span class="text-danger">*</span></th>
                                                                <th>Gender<span class="text-danger">*</span></th>
                                                                <th>Aadhar Card<span class="text-danger">*</span></th>
                                                                <th>Is Nominee<span class="text-danger"></span></th>
                                                                <th>Action</th>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="member_name"
                                                                        placeholder="Enter Member Name"></td>
                                                                <td><input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="member_relation"
                                                                        placeholder="Enter Member Relation"></td>
                                                                <td><input type="date"
                                                                        class="form-control form-control-sm"
                                                                        id="member_dob"></td>
                                                                <td><input class="form-control form-control-sm"
                                                                        id="member_address"
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
                                                                <td>
                                                                    <input type="file"
                                                                        class="form-control form-control-sm"
                                                                        id="family_aadhar_card">
                                                                    <div id="family_aadhar_preview"
                                                                        style="margin-top:5px;"></div>
                                                                </td>

                                                                <td class="text-center">
                                                                    <div class="d-flex align-items-center wt">
                                                                        <div class="flex-grow-1 ms-2 name">
                                                                            <label class="switch">
                                                                                <input type="checkbox" id="is_nominee"
                                                                                    checked>
                                                                                <span class="slider round"> </span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td> 
                                                                    <input type="hidden" id="old_aadhar_card">
                                                                    <input type="hidden" id="family_detail_id"
                                                                        value="0">
                                                                    <?php if ($mode !='view') {  ?>
                                                                    <button type="button" class="btn btn-sm btn-success"
                                                                        onclick="save_family();"
                                                                        id="family_btn">Add</button>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="fetch_family_details">
                                                        </tbody>
                                                    </table>
                                                </div>


                                                <div class="col-md-3">
                                                    <label class="form-label d-block">Is Form 21</label>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            name="is_form21_last_date" id="form21_yes" value="1"
                                                            <?= $is_form21_last_date == '1' ? 'checked' : '' ?>
                                                            onclick="toggleForm21Date()">
                                                        <label class="form-check-label" for="form21_yes">Yes</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            name="is_form21_last_date" id="form21_no" value="0"
                                                            <?= $is_form21_last_date == '0' ? 'checked' : '' ?>
                                                            onclick="toggleForm21Date()">
                                                        <label class="form-check-label" for="form21_no">No</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-3" id="form21_date_div">
                                                    <label class="form-label">Form 21 Last Date</label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        name="form21_last_date" id="form21_last_date"
                                                        value="<?= $form21_last_date ?>">
                                                </div>

                                            </div>

                                            <div class="mt-4 d-flex justify-content-between">
                                                <button type="button" class="btn btn-secondary btn-prev">←
                                                    Previous</button>
                                                <button type="button" class="btn btn-primary btn-next"
                                                    data-validate="">Next
                                                    →</button>

                                            </div>
                                        </div>

                                        <!-- ⭐ STEP 5-->
                                        <div class="form-step">
                                            <div class="table-responsive">
                                                <table class="offset-3 table table-bordered table-sm w-50 table-light">
                                                    <thead>
                                                        <tr class="table-warning">
                                                            <th>Document Type <span class="text-danger">*</span></th>
                                                            <th>Check List <span class="text-danger">*</span><input
                                                                    type="checkbox" class="form-check-input"
                                                                    id="checkAllCheckList" /></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $doc_res = $obj->executequery("SELECT * FROM document_master ORDER BY doc_id ASC");
                                                        foreach ($doc_res as $row) {
                                                            if (isset($checkedDocs)) {
                                                                $isChecked = in_array($row['doc_id'], $checkedDocs) ? 'checked' : '';
                                                            } else {
                                                                $isChecked = '';
                                                            }

                                                        ?>
                                                        <tr>
                                                            <td> <b> <?= htmlspecialchars($row['document_name']); ?></b>
                                                            </td>

                                                            <td class="text-center">
                                                                <input class="form-check-input check_single"
                                                                    type="checkbox" name="document_check_list[]"
                                                                    value="<?= $row['doc_id']; ?>" <?= $isChecked ?>>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                </table>

                                            </div>
                                            <div class="mt-4 d-flex justify-content-between">
                                                <button type="button" class="btn btn-secondary btn-prev">←
                                                    Previous</button>
                                                <!-- <button type="submit" name="submit" class="btn btn-success" onClick="return validateForm();">Submit ✓</button> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $chkadd = $obj->check_addBtn($pagename, $loginid);
                            if ($chkadd == 1 && $mode !='view') {  ?>
                            <div class="col-lg-12 mb-3 d-flex justify-content-center gap-2">
                                <br>
                                <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn"
                                    value="<?=$btn_name?>" onClick="return validateForm();">
                                <a href=" <?php echo $pagename ?>" type="button"
                                    class="btn btn-sm btn-danger add-btn">Reset</a>
                            </div>
                            <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <!-- for export excel  -->
    <script>
    function exportTableToExcel(tableID, filename = 'employee_bank_detail') {

        var dataType = 'application/vnd.ms-excel';
        var originalTable = document.getElementById(tableID);

        var tableClone = originalTable.cloneNode(true);

        // ❌ Remove 2nd row (input row)
        var rows = tableClone.getElementsByTagName("tr");
        if (rows.length > 1) {
            rows[1].remove();
        }

        // ❌ Remove last column (Action)
        for (let i = 0; i < rows.length; i++) {
            let cells = rows[i].children;

            if (cells.length > 1) {
                cells[cells.length - 1].remove();
                cells[cells.length - 1].remove();
            }
        }
        // ✅ Apply border for Excel
        tableClone.setAttribute("border", "1");
        tableClone.style.borderCollapse = "collapse";

        let allCells = tableClone.querySelectorAll("th, td");
        allCells.forEach(cell => {
            cell.style.border = "1px solid black";
        });

        var tableHTML = tableClone.outerHTML.replace(/ /g, '%20');

        var filenameFinal = filename + '.xls';

        var downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);

        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filenameFinal;
        downloadLink.click();
    }
    </script>
    <script>
    $(document).ready(function() {
        $(".chosen-select").select2({
            width: '100%',
        });
        fetch_family_details();
        fetch_education_details();
        fetch_document_details();
        fetch_language_details();
        fetch_bank_details();
        toggleAnniversary();
        toggleForm21Date();
        let keyval = '<?= $keyvalue ?>'
        if (keyval > 0) {
            get_designation('<?= $department_id ?>', <?= $designation_id ?>);
        }

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
    nextBtns.forEach(btn => {
        btn.onclick = () => {

            // Check if validation is required
            const validateFields = btn.dataset.validate;

            if (validateFields) {
                // If validation fails → stop
                if (!checkinputmaster(validateFields)) {
                    return false;
                }
            }

            // Validation passed → go next
            showStep(currentStep + 1);
        };
    });


    // nextBtns.forEach(btn => btn.onclick = () => showStep(currentStep + 1));
    prevBtns.forEach(btn => btn.onclick = () => showStep(currentStep - 1));

    tabs.forEach(tab => {
        tab.onclick = () => {
            let step = parseInt(tab.dataset.step);
            showStep(step);
        };
    });

    showStep(0);

    function toggleForm21Date() {
        let isForm21 = $('input[name="is_form21_last_date"]:checked').val();

        if (isForm21 == '1') {
            $('#form21_date_div').show();
            $('#form21_last_date').prop('required', true);
        } else {
            $('#form21_date_div').hide();
            $('#form21_last_date').prop('required', false).val('');
        }
    }
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

    function toggleAnniversary() {
        const maritalStatus = document.getElementById('marital_status').value;
        const anniversaryDiv = document.getElementById('anniversary_div');

        if (maritalStatus === 'Married') {
            anniversaryDiv.style.display = 'block';
        } else {
            anniversaryDiv.style.display = 'none';
            document.getElementById('anniversary_date').value = '';
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

    function edit_family(id, name, relation, dob, address, gender, nominee, aadhar) {

        $('#family_detail_id').val(id);
        $('#old_aadhar_card').val(aadhar);
        $('#member_name').val(name);
        $('#member_relation').val(relation);
        $('#member_dob').val(dob);
        $('#member_address').val(address);
        $('#member_gender').val(gender).trigger('change');
        let imgPath = "./uploaded/emp_documents/" + aadhar;
        if (aadhar !== '') {
            $('#family_aadhar_preview').html(`
            <a href="${imgPath}" target="_blank">
                <img src="${imgPath}" width="80" height="80" 
                style="object-fit:cover; border-radius:5px; border:1px solid #ddd;">
            </a>
        `);
        } else {
            $('#family_aadhar_preview').html('');
        }
        $('#is_nominee').prop('checked', nominee == 1);
        $('#family_btn').text('Update').removeClass('btn-success').addClass('btn-primary');
        document.getElementById("member_name").focus();
    }

    function save_family() {
        let formData = new FormData();
        const family_aadhar_card = document.getElementById("family_aadhar_card").files[0];
        const is_nominee = $('#is_nominee').is(':checked') ? 1 : 0;
        const member_name = $('#member_name').val();
        let old_aadhar_card = document.getElementById("old_aadhar_card").value;
        const family_detail_id = $('#family_detail_id').val();
        const member_relation = $('#member_relation').val();
        const member_dob = $('#member_dob').val();
        const member_address = $('#member_address').val();
        const member_gender = $('#member_gender').val();
        const keyvalue = '<?= $keyvalue; ?>';
        if (member_name == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter Member Name'
            });
            return;
        }

        if (member_relation == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter Member Relation'
            });
            return;
        }

        if (member_address == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter Member Address'
            });
            return;
        }

        if (member_gender == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please select Member Gender'
            });
            return;
        }
        formData.append('member_name', member_name);
        formData.append('family_detail_id', family_detail_id);
        formData.append('member_relation', member_relation);
        formData.append('member_dob', member_dob);
        formData.append('member_address', member_address);
        formData.append('member_gender', member_gender);
        formData.append('is_nominee', is_nominee);
        formData.append('old_aadhar_card', old_aadhar_card);
        formData.append('keyvalue', keyvalue);
        formData.append('type', 'family');
        if (family_aadhar_card) {
            formData.append('aadhar_card', family_aadhar_card);
        }
        $.ajax({
            url: 'ajax/ajax_save_emp_family.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#family_btn').prop("disabled", true).text("Saving...");
            },
            success: function(response) {
                // alert(response);
                if (response.trim() === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Family member added successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        fetch_family_details();
                        $('#member_name').val('');
                        $('#family_detail_id').val('0');
                        $('#family_aadhar_card').val('');
                        $('#member_relation').val('');
                        $('#family_aadhar_preview').html('');
                        $('#member_dob').val('');
                        $('#member_address').val('');
                        $('#member_gender').val('').trigger("chosen:updated").trigger('change');
                    });

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
                $('#family_btn').prop("disabled", false).text("Add");
            }
        });
    }

    function fetch_family_details() {
        let keyvalue = '<?= $keyvalue; ?>';
        let mode = '<?= $mode; ?>';
        jQuery.ajax({
            type: 'POST',
            url: 'ajax/ajax_fetch_family_details.php',
            data: 'keyvalue=' + keyvalue + '&type=family'+ '&mode=' + mode,
            dataType: 'html',
            success: function(data) {
                //alert(data);
                document.getElementById('fetch_family_details').innerHTML = data;
            }
        }); //ajax close
    }


    function delete_family(id, imgname) {
        $('#deleteRecordModal').modal('show');
        var tblname = 'emp_family_details';
        var tblpkey = 'family_detail_id';
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
                    imgpath: imgpath
                },
                success: function(data) {
                    fetch_family_details();
                }
            });
            $('#deleteRecordModal').modal('hide');
        });
    }

    function save_emp_education() {
        const examination = $('#examination').val();
        const education_id = $('#education_id').val();
        const university = $('#university').val();
        const college = $('#college').val();
        const pass_year = $('#pass_year').val();
        const percentage = $('#percentage').val();
        const subject = $('#subject').val();
        const keyvalue = '<?= $keyvalue; ?>';
        if (examination === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter Examination'
            });
            return;
        }

        if (university === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter Board / University'
            });
            return;
        }

        if (college === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter College / Institute'
            });
            return;
        }

        if (pass_year === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter Passing Year'
            });
            return;
        }
        if (percentage === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter Percentage'
            });
            return;
        }

        if (subject === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter Subject'
            });
            return;
        }


        $.ajax({
            url: 'ajax/ajax_save_emp_family.php',
            type: 'POST',
            data: {
                examination: examination,
                education_id: education_id,
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

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Education Details added successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        fetch_education_details();
                        $('#examination').val('');
                        $('#education_id').val('0');
                        $('#university').val('');
                        $('#college').val('');
                        $('#percentage').val('');
                        $('#pass_year').val('');
                        $('#subject').val('');
                    })

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
                $('#education_btn').prop("disabled", false).text("Add");
            }
        });
    }

    function edit_education(id, exam, university, college, year, percentage, subject) {
        $('#education_id').val(id);
        $('#examination').val(exam);
        $('#university').val(university);
        $('#college').val(college);
        $('#pass_year').val(year);
        $('#percentage').val(percentage);
        $('#subject').val(subject);

        // button change
        $('#education_btn').text('Update')
            .removeClass('btn-success')
            .addClass('btn-primary');

        // focus
        $('#examination').focus();
    }

    function fetch_education_details() {
        let keyvalue = '<?= $keyvalue; ?>';
        let mode = '<?= $mode; ?>';
        jQuery.ajax({
            type: 'POST',
            url: 'ajax/ajax_fetch_family_details.php',
            data: 'keyvalue=' + keyvalue + '&type=education' + '&mode=' + mode,
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
        let old_doc_file = document.getElementById("old_doc_file").value;
        let emp_doc_id = document.getElementById("emp_doc_id").value;
        let doc_remark = document.getElementById("doc_remark").value;
        let doc_expiry_date = document.getElementById("doc_expiry_date").value;
        if (doc_id == "" || doc_id == "0") {
            Swal.fire({
                icon: 'warning',
                title: 'Required!',
                text: 'Please select Document Type.'
            });
            return false;
        }


        if (!doc_file && emp_doc_id == 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Required!',
                text: 'Please upload a document file.'
            });
            return false;
        }
        // if (!doc_expiry_date) {
        //     Swal.fire({
        //         icon: 'warning',
        //         title: 'Required!',
        //         text: 'Please Enter Expiry Date of Document.'
        //     });
        //     return false;
        // }
        const keyvalue = '<?= $keyvalue; ?>';
        formData.append("doc_file", doc_file);
        formData.append("doc_id", doc_id);
        formData.append("old_doc_file", old_doc_file);
        formData.append("emp_doc_id", emp_doc_id);
        formData.append("doc_remark", doc_remark);
        formData.append("doc_expiry_date", doc_expiry_date);
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
                        $('#emp_doc_id').val('0');
                        $('#doc_remark').val('');
                        $('#doc_expiry_date').val('');
                        $('#old_doc_file').val('');
                        $('#doc_preview').html('');
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

    function edit_document(id, doc_id, doc_expiry_date, doc_remark, doc_file) {
        $('#emp_doc_id').val(id);
        $('#doc_id').val(doc_id).trigger('change');
        $('#doc_expiry_date').val(doc_expiry_date);
        $('#doc_remark').val(doc_remark);
        // ❌ file input set nahi karna
        $('#doc_file').val('');

        // ✅ old file store
        $('#old_doc_file').val(doc_file);

        // ✅ preview show
        let imgPath = "./uploaded/emp_documents/" + doc_file;

        if (doc_file !== '') {
            $('#doc_preview').html(`
            <a href="${imgPath}" target="_blank">
                <img src="${imgPath}" width="80" height="80" style="object-fit:cover; border-radius:5px;">
            </a>
        `);
        } else {
            $('#doc_preview').html('');
        }
        document.getElementById("document_btn").innerText = "Update";
        document.getElementById("doc_remark").focus();
    }

    function fetch_document_details() {
        let keyvalue = '<?= $keyvalue; ?>';
        let mode = '<?= $mode; ?>';
        jQuery.ajax({
            type: 'POST',
            url: 'ajax/ajax_fetch_family_details.php',
            data: 'keyvalue=' + keyvalue + '&type=document' + '&mode=' + mode,
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


    function save_emp_language() {
        const language_name = $('#language_name').val();
        const emp_language_id = $('#emp_language_id').val();
        const language_speak = $('input[name="language_speak"]:checked').val();
        const language_read = $('input[name="language_read"]:checked').val();
        const language_write = $('input[name="language_write"]:checked').val();

        const keyvalue = '<?= $keyvalue; ?>';
        if (language_name === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please Enter Language Name'
            });
            return;
        }


        $.ajax({
            url: 'ajax/ajax_save_emp_family.php',
            type: 'POST',
            data: {
                language_name: language_name,
                emp_language_id: emp_language_id,
                keyvalue: keyvalue,
                language_speak: language_speak,
                language_read: language_read,
                language_write: language_write,
                type: 'language_known'
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Saving...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $('#language_btn').prop("disabled", true).text("Saving...");
            },
            success: function(response) {
                if (response.trim() === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved!',
                        text: 'Language details saved successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        fetch_language_details();
                        $('#language_name').val('');
                        $('#emp_language_id').val('0');
                        $('input[name="language_speak"][value="0"]').prop('checked', true);
                        $('input[name="language_read"][value="0"]').prop('checked', true);
                        $('input[name="language_write"][value="0"]').prop('checked', true);
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response
                    });
                }
            },
            error: function() {

                Swal.fire({
                    icon: 'error',
                    title: 'Something went wrong. Please try again.',
                    text: response
                });
            },
            complete: function() {
                $('#language_btn').prop("disabled", false).text("Add");
            }
        });
    }


    function fetch_language_details() {
        let keyvalue = '<?= $keyvalue; ?>';
        let mode = '<?= $mode; ?>';
        jQuery.ajax({
            type: 'POST',
            url: 'ajax/ajax_fetch_family_details.php',
            data: 'keyvalue=' + keyvalue + '&type=language_known' + '&mode=' + mode,
            dataType: 'html',
            success: function(data) {
                //alert(data);
                document.getElementById('fetch_language_details').innerHTML = data;
            }
        }); //ajax close
    };

    function delete_language(id) {
        $('#deleteRecordModal').modal('show');
        var tblname = 'emp_language';
        var tblpkey = 'emp_language_id';
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
                    fetch_language_details();
                }
            });
            $('#deleteRecordModal').modal('hide');
        });
    }

    function edit_language(id, language, speak, read, write) {
        document.getElementById("emp_language_id").value = id;
        document.getElementById("language_name").value = language;
        if (speak == 1 || speak == '1') {
            document.getElementById("language_speak_yes").checked = true;
        } else {
            document.getElementById("language_speak_no").checked = true;
        }
        if (read == 1 || read == '1') {
            document.getElementById("language_read_yes").checked = true;
        } else {
            document.getElementById("language_read_no").checked = true;
        }
        if (write == 1 || write == '1') {
            document.getElementById("language_write_yes").checked = true;
        } else {
            document.getElementById("language_write_no").checked = true;
        }
        $('#language_btn').text("Update");
        document.getElementById("language_name").focus();
    }

    function edit_bank(id, bank_name, acc_holder, acc_no, ifsc, status, branch_name) {
        $('#emp_bank_id').val(id);

        $('#bank_id').val(bank_name).trigger('change');
        $('#acc_holder_name').val(acc_holder);
        $('#branch_name').val(branch_name);
        $('#account_no').val(acc_no);
        $('#ifsc_code').val(ifsc);

        document.getElementById("bank_active").checked = (status == 1 || status == '1');

        document.getElementById("bank_btn").innerText = "Update";
        document.getElementById("acc_holder_name").focus();
    }

    function save_bank_details() {
        const bank_id = $('#bank_id').val();
        const emp_bank_id = $('#emp_bank_id').val();
        const acc_holder_name = $('#acc_holder_name').val();
        const branch_name = $('#branch_name').val();
        const account_no = $('#account_no').val();
        const ifsc_code = $('#ifsc_code').val();
        const bank_active = $('#bank_active').is(':checked') ? 1 : 0;
        const keyvalue = '<?= $keyvalue; ?>';
        if (bank_id == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter Bank Name'
            });
            return;
        }

        if (acc_holder_name == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter Account Holder Name'
            });
            return;
        }

        if (account_no == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please enter Account Number'
            });
            return;
        }

        if (ifsc_code == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Required',
                text: 'Please select IFCS Code'
            });
            return;
        }

        $.ajax({
            url: 'ajax/ajax_save_emp_family.php',
            type: 'POST',
            data: {
                bank_id: bank_id,
                emp_bank_id: emp_bank_id,
                keyvalue: keyvalue,
                acc_holder_name: acc_holder_name,
                branch_name: branch_name,
                account_no: account_no,
                ifsc_code: ifsc_code,
                bank_active: bank_active,
                type: 'bank_details'
            },
            beforeSend: function() {
                $('#bank_btn').prop("disabled", true).text("Saving...");
            },
            success: function(response) {
                if (response.trim() === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Family Bank Details Added successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        fetch_bank_details();
                        $('#acc_holder_name').val('');
                        $('#branch_name').val('');
                        $('#account_no').val('');
                        $('#ifsc_code').val('');
                        $('#emp_bank_id').val('0');
                        $('#bank_id').val('').trigger("chosen:updated").trigger('change');
                    });

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
                $('#bank_btn').prop("disabled", false).text("Add");
            }
        });
    }

    function fetch_bank_details() {
        let keyvalue = '<?= $keyvalue; ?>';
        let mode = '<?= $mode; ?>';
        jQuery.ajax({
            type: 'POST',
            url: 'ajax/ajax_fetch_family_details.php',
            data: 'keyvalue=' + keyvalue + '&type=bank_details' + '&mode=' + mode, 
            dataType: 'html',
            success: function(data) {
                //alert(data);
                document.getElementById('fetch_bank_details').innerHTML = data;
            }
        }); //ajax close
    }




    function getTableData() {
        let table = document.getElementById("exportTable1");

        let data = [];

        // ✅ Get headers
        let headers = [];
        let ths = table.querySelectorAll("thead tr:first-child th");

        ths.forEach((th, index) => {
            // skip last column (Action)
            if (index !== ths.length - 1) {
                headers.push(th.innerText.trim());
            }
        });

        data.push(headers); // add header as first row

        // ✅ Get body rows
        let rows = table.querySelectorAll("tbody tr");

        rows.forEach(row => {
            let cols = row.querySelectorAll("td");
            let rowData = [];

            cols.forEach((td, index) => {
                if (index !== cols.length - 1) {
                    rowData.push(td.innerText.trim());
                }
            });

            data.push(rowData);
        });

        return data;
    }

    function delete_bank_details(id) {
        $('#deleteRecordModal').modal('show');
        var tblname = 'emp_bank_details';
        var tblpkey = 'emp_bank_id';
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
                    fetch_bank_details();
                }
            });
            $('#deleteRecordModal').modal('hide');
        });
    }


    function checkDuplicateMobile(mobile) {
        if (mobile.length !== 10) return;
        $.ajax({
            url: "check_mobile_duplicate.php",
            type: "POST",
            data: {
                mobile_no: mobile,
                type: 'mobile'
            },
            success: function(res) {
                if (res.trim().startsWith('EXISTS')) {

                    let parts = res.split('|');
                    let unit_name = parts[1];
                    let mobile_no = parts[2];
                    let emp_code = parts[3];
                    let emp_name = parts[4];

                    Swal.fire({
                        icon: 'error',
                        title: 'Duplicate Mobile Number',
                        html: `
                            This Mobile No <b>${mobile_no}</b> belongs to
                            <b>${emp_name} (${emp_code})</b><br><br>
                            Existing in Unit: <b>${unit_name}</b>
                        `,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // $('#mobile_no').val('').focus();
                    });
                }
            }
        });
    }


    function checkDuplicatePanCard(pan_no) {

        $.ajax({
            url: "check_mobile_duplicate.php",
            type: "POST",
            data: {
                pan_no: pan_no,
                type: 'pan_no'
            },
            success: function(res) {
                let parts = res.split('|');
                let unit_name = parts[1];
                let pan_no = parts[2];
                let emp_code = parts[3] || '';
                let emp_name = parts[4] || '';
                let reason = parts[5] || '';

                if (parts[0] === 'BLACKLIST') {

                    Swal.fire({
                        icon: 'error',
                        title: 'Blacklisted Employee',
                        html: `
                This PAN No <b>${pan_no}</b> belongs to 
                <b>${emp_name} (${emp_code})</b><br><br>
                BLACKLISTED in Unit: <b>${unit_name}</b><br><br>
                <b>Reason:</b> ${reason ? reason : 'Not provided'}
            `
                    }).then(() => {
                        $('#pan_no').val('').focus();
                    });
                } else if (parts[0] === 'EXISTS') {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Duplicate PAN No',
                        html: `
                  This PAN No <b>${pan_no}</b> already exists and belongs to 
                <b>${emp_name} (${emp_code})</b><br><br>
                Unit: <b>${unit_name}</b>
            `
                    }).then(() => {
                        $('#pan_no').val('').focus();
                    });

                }

            }
        });
    }


    function checkDuplicateAadhar(aadhar_no) {

        $.ajax({
            url: "check_mobile_duplicate.php",
            type: "POST",
            data: {
                aadhar_no: aadhar_no,
                type: 'aadhar'
            },
            success: function(res) {
                let parts = res.split('|');
                let unit_name = parts[1];
                let aadhar_no = parts[2];
                let emp_code = parts[3] || '';
                let emp_name = parts[4] || '';
                let reason = parts[5] || '';


                if (parts[0] === 'BLACKLIST') {

                    Swal.fire({
                        icon: 'error',
                        title: 'Blacklisted Employee',
                        html: `
               This Aadhar <b>${aadhar_no}</b> belongs to 
                <b>${emp_name} (${emp_code})</b><br><br>
                BLACKLISTED in Unit: <b>${unit_name}</b><br><br>
                <b>Reason:</b> ${reason ? reason : 'Not provided'}
        `
                    }).then(() => {
                        $('#aadhar_no').val('').focus();
                    });

                } else if (parts[0] === 'EXISTS') {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Duplicate Aadhar',
                        html: `
            This Aadhar <b>${aadhar_no}</b> already exists and belongs to 
                <b>${emp_name} (${emp_code})</b><br><br>
                Unit: <b>${unit_name}</b>
        `
                    }).then(() => {
                        $('#aadhar_no').val('').focus();
                    });
                }

            }
        });
    }


    document.getElementById('checkAllCheckList').addEventListener('change', function() {
        const isChecked = this.checked;

        document.querySelectorAll('.check_single').forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });
    });


    function checkDuplicateEmpCode(emp_code) {
        $.ajax({
            url: "check_mobile_duplicate.php",
            type: "POST",
            data: {
                emp_code: emp_code,
                type: 'emp_code'
            },
            success: function(res) {
                let parts = res.split('|');
                let status = parts[0];
                let unit_name = parts[1];
                let emp_code_res = parts[2];
                let emp_name = parts[3] || '';

                if (status === 'EXISTS') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Duplicate Employee Code',
                        html: `
                         Employee Code <b>${emp_code_res}</b> already exists and belongs to 
                <b>${emp_name}</b><br><br>
                Unit: <b>${unit_name}</b>
                    `
                    }).then(() => {
                        $('#emp_code').val('').focus();
                    });
                }
            }
        });
    }
    </script>
    <script>
    function formatFieldName(id) {
        return id
            .replace(/_/g, ' ') // underscore remove
            .replace(/\b\w/g, c => c.toUpperCase()); // Capitalize words
    }


    function validateForm() {
        const steps = [{
                tab: 'EMPLOYEE INFORMATION',
                fields: ['emp_code', 'biomatric_id', 'first_name', 'father_name', 'department_id', 'designation_id',
                    'date_of_joining', 'basic_salary', 'dob', 'mobile_no', 'aadhar_no', 'shift_id', 'is_pf',
                    'is_esic', 'allow_weekly_off', 'allow_add_leave', 'is_perform_incen', 'is_active', 'reporting_manager',
                    'present_address'
                ]
            },
            // 'emp_code', 'biomatric_id', 'first_name', 'father_name', 'department_id', 'designation_id',
            // 'date_of_joining', 'basic_salary','emp_category', 'dob','blood_group', 'mobile_no', 'aadhar_no', 'shift_id', 'is_pf','is_esic', 'allow_weekly_off','is_perform_incen','reporting_manager','marital_status','present_address', 'permanent_address', 'gender','profile_image' 

            // {
            //     tab: 'CONTACT',
            //     fields: ['job_location']
            // },
        ];

        let emptyField = null;
        let tabName = '';
        let fieldName = '';

        for (let step of steps) {
            for (let id of step.fields) {

                let el = document.getElementById(id);
                if (!el) continue;

                if (el.value.trim() === "") {
                    emptyField = el;
                    tabName = step.tab;
                    fieldName = formatFieldName(id);

                    el.classList.add('is-invalid');
                    break;
                } else {
                    el.classList.remove('is-invalid');
                }
            }
            if (emptyField) break;
        }

        if (emptyField) {

            Swal.fire({
                icon: 'warning',
                title: 'Required Field',
                text: `${fieldName} is required in the "${tabName}" tab`,
                confirmButtonColor: '#3085d6'
            }).then(() => {
                emptyField.focus();
            });

            return false;
        }

        return true;
    }



    function change_status(emp_bank_id) {
        const keyvalue = '<?= $keyvalue; ?>';
        $.ajax({
            url: 'ajax/ajax_save_emp_family.php',
            type: 'POST',
            data: {
                emp_bank_id: emp_bank_id,
                emp_id: keyvalue,
                type: 'change_bank_status'
            },
            success: function(response) {
                if (response.trim() === "success") {
                    fetch_bank_details();
                } else {
                    Swal.fire("Error", response);
                }
            }
        });
    }


    function change_nominee(family_detail_id) {
        const keyvalue = '<?= $keyvalue; ?>';
        $.ajax({
            url: 'ajax/ajax_save_emp_family.php',
            type: 'POST',
            data: {
                family_detail_id: family_detail_id,
                emp_id: keyvalue,
                type: 'change_family_nominee'
            },
            success: function(response) {
                if (response.trim() === "success") {
                    fetch_family_details();
                } else {
                    Swal.fire("Error", response);
                }
            }
        });
    }

    function get_designation(department_id, designation_id = 0) {
        const keyvalue = '<?= $keyvalue; ?>';
        $.ajax({
            type: "POST",
            url: 'ajax/ajax_fetch_family_details.php',
            data: {
                department_id: department_id,
                designation_id: designation_id,
                keyvalue: keyvalue,
                type: 'get_designation'
            },

            success: function(data) {
                $('#designation_id').html(data).trigger("change.select2");
            }
        });

    }

    function calculateAge() {
        let dob = document.getElementById("dob").value;

        if (dob) {
            let dobDate = new Date(dob);
            let today = new Date();

            let age = today.getFullYear() - dobDate.getFullYear();

            let m = today.getMonth() - dobDate.getMonth();

            // Adjust age if birthday not yet occurred this year
            if (m < 0 || (m === 0 && today.getDate() < dobDate.getDate())) {
                age--;
            }

            document.getElementById("age").value = age;
        }
    }
    </script>

    <script src="https://unpkg.com/cropperjs@1.6.2/dist/cropper.min.js"></script>

    <!-- Crop Modal -->
    <div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:950px;">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title">Crop Signature</h6>
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-2 text-center" style="background:#f5f5f5;">
                    <img id="cropImage" style="max-width:100%; max-height:650px;">
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sm" id="cropConfirm">Apply Crop</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.querySelector('#emp_sign').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;

        var modalEl = document.getElementById('cropModal');
        var modal = new bootstrap.Modal(modalEl);
        var imgEl = document.getElementById('cropImage');
        var cropper = null;

        var reader = new FileReader();
        reader.onload = function(ev) {
            imgEl.src = ev.target.result;
            modal.show();
            cropper = new Cropper(imgEl, {
                aspectRatio: 3 / 1,
                viewMode: 1,
                autoCropArea: 0.95,
                responsive: true,
                guides: true
            });
        };
        reader.readAsDataURL(file);

        document.getElementById('cropConfirm').onclick = function() {
            if (!cropper) return;
            var canvas = cropper.getCroppedCanvas({
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });
            canvas.toBlob(function(blob) {
                cropper.destroy();
                cropper = null;
                modal.hide();
                var croppedFile = new File([blob], file.name, {
                    type: 'image/png'
                });
                var dt = new DataTransfer();
                dt.items.add(croppedFile);
                document.querySelector('#emp_sign').files = dt.files;
                document.getElementById('signPreview').innerHTML =
                    '<img src="' + URL.createObjectURL(blob) +
                    '" style="max-height:60px; border:1px solid #ddd; padding:4px; border-radius:4px;" alt="Signature">';
            }, 'image/png', 0.92);
        };

        modalEl.addEventListener('hidden.bs.modal', function handler() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            modalEl.removeEventListener('hidden.bs.modal', handler);
        });
    });
    </script>




</body>

</html>