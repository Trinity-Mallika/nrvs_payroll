<?php include_once("../../adminsession.php");

$type = $obj->test_input($_REQUEST['type']);
$imgpath = '../uploaded/emp_documents/';

if ($type == 'family') {
    $member_name = $obj->test_input($_REQUEST['member_name']);
    $relation = $obj->test_input($_REQUEST['member_relation']);
    $dob = $obj->test_input($_REQUEST['member_dob']);
    $address = $obj->test_input($_REQUEST['member_address']);
    $gender = $obj->test_input($_REQUEST['member_gender']);
    $aadhar_card = $_FILES['aadhar_card']['name'];
    $is_nominee = $obj->test_input($_REQUEST['is_nominee'] ?? 0);
    $keyvalue = $obj->test_input($_REQUEST['keyvalue']);

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'doc', 'docx', 'pdf'];
    $imageFileType = strtolower(pathinfo($aadhar_card, PATHINFO_EXTENSION));
    if (!empty($aadhar_card) && in_array($imageFileType, $allowedTypes)) {
        $filename = $obj->uploadImage($imgpath, $_FILES["aadhar_card"]);
    }

    $form_data = array(
        'emp_id' => $keyvalue,
        'member_name' => $member_name,
        'relation' => $relation,
        'dob' => $dob,
        'aadhar_card' => $filename,
        'is_nominee' => $is_nominee,
        'address' => $address,
        'gender' => $gender,
        "createdate" => $createdate,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid,
        "unit_id" => $unitid
    );
    if ($is_nominee == 1) {
        $obj->update_record("emp_family_details", array("emp_id" => $keyvalue), array("is_nominee" => 0));
    }
    if ($member_name != '') {
        $obj->insert_record("emp_family_details", $form_data);
        echo "success";
    } else {
        echo "Something Went Wrong!!!";
    }
} else if ($type == 'education') {
    $examination = $obj->test_input($_REQUEST['examination']);
    $university = $obj->test_input($_REQUEST['university']);
    $college = $obj->test_input($_REQUEST['college']);
    $percentage = $obj->test_input($_REQUEST['percentage']);
    $pass_year = $obj->test_input($_REQUEST['pass_year']);
    $subject = $obj->test_input($_REQUEST['subject']);
    $keyvalue = $obj->test_input($_REQUEST['keyvalue']);

    $form_data = array(
        'emp_id' => $keyvalue,
        'examination' => $examination,
        'university' => $university,
        'college' => $college,
        'percentage' => $percentage,
        'pass_year' => $pass_year,
        'subject' => $subject,
        "createdate" => $createdate,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid,
        "unit_id" => $unitid
    );

    if ($examination != '') {
        $obj->insert_record("emp_education", $form_data);
        echo "success";
    } else {
        echo "Something Went Wrong!!!";
    }
} else if ($type == 'emp_document') {
    $doc_id = $obj->test_input($_REQUEST['doc_id']);
    $doc_remark = $obj->test_input($_REQUEST['doc_remark'] ?? '');
    $doc_expiry_date = $obj->test_input($_REQUEST['doc_expiry_date'] ?? '');
    $doc_file = $_FILES['doc_file']['name'];
    $keyvalue = $obj->test_input($_REQUEST['keyvalue']);

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'doc', 'docx', 'pdf'];
    $imageFileType = strtolower(pathinfo($doc_file, PATHINFO_EXTENSION));
    if (!empty($doc_file) && in_array($imageFileType, $allowedTypes)) {
        $filename = $obj->uploadImage($imgpath, $_FILES["doc_file"]);
    }

    $form_data = array(
        'emp_id' => $keyvalue,
        'doc_id' => $doc_id,
        'doc_remark' => $doc_remark,
        'doc_expiry_date' => $doc_expiry_date,
        'doc_file' => $filename,
        "createdate" => $createdate,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid,
        "unit_id" => $unitid
    );

    if ($doc_id != '') {
        $obj->insert_record("emp_document", $form_data);
        echo "success";
    } else {
        echo "Something Went Wrong!!!";
    }
} else if ($type == 'language_known') {
    $language_name = $obj->test_input($_REQUEST['language_name']);
    $language_speak = $obj->test_input($_REQUEST['language_speak'] ?? '0');
    $language_read = $obj->test_input($_REQUEST['language_read'] ?? '0');
    $language_write = $obj->test_input($_REQUEST['language_write'] ?? '0');
    $keyvalue = $obj->test_input($_REQUEST['keyvalue']);


    $form_data = array(
        'emp_id' => $keyvalue,
        'language_name' => $language_name,
        'is_speak' => $language_speak,
        'is_read' => $language_read,
        'is_write' => $language_write,
        "createdate" => $createdate,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid,
        "unit_id" => $unitid
    );

    if ($language_name != '') {
        $obj->insert_record("emp_language", $form_data);
        echo "success";
    } else {
        echo "Something Went Wrong!!!";
    }
} else if ($type == 'bank_details') {
    $bank_id = $obj->test_input($_REQUEST['bank_id'] ?? '0');
    $acc_holder_name = $obj->test_input($_REQUEST['acc_holder_name'] ?? '');
    $account_no = $obj->test_input($_REQUEST['account_no'] ?? '');
    $ifsc_code = $obj->test_input($_REQUEST['ifsc_code'] ?? '');
    $bank_active = $obj->test_input($_REQUEST['bank_active'] ?? '0');
    $keyvalue = $obj->test_input($_REQUEST['keyvalue']);

    $form_data = array(
        'emp_id' => $keyvalue,
        'bank_id' => $bank_id,
        'acc_holder_name' => $acc_holder_name,
        'account_no' => $account_no,
        'is_active' => $bank_active,
        'ifsc_code' => $ifsc_code,
        "createdate" => $createdate,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid,
        "unit_id" => $unitid
    );
    if ($bank_active == 1) {
        $obj->update_record("emp_bank_details", array("emp_id" => $keyvalue), array("is_active" => 0));
    }

    if ($bank_id != '') {
        $obj->insert_record("emp_bank_details", $form_data);
        echo "success";
    } else {
        echo "Something Went Wrong!!!";
    }
} else if ($type == 'change_bank_status') {
    $emp_bank_id = $obj->test_input($_REQUEST['emp_bank_id']);
    $emp_id = $obj->test_input($_REQUEST['emp_id']);

    $obj->update_record("emp_bank_details", array("emp_id" => $emp_id), array("is_active" => 0));

    $obj->update_record("emp_bank_details", array("emp_bank_id" => $emp_bank_id), array("is_active" => 1));



    echo "success";
} else if ($type == 'change_family_nominee') {
    $family_detail_id = $obj->test_input($_REQUEST['family_detail_id']);
    $emp_id = $obj->test_input($_REQUEST['emp_id']);

    $obj->update_record("emp_family_details", array("emp_id" => $emp_id), array("is_nominee" => 0));

    $obj->update_record("emp_family_details", array("family_detail_id" => $family_detail_id), array("is_nominee" => 1));

    echo "success";
} else {
    echo "Something Went Wrong!!!";
}
