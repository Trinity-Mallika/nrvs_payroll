<?php include("appsession.php");
$pagename = 'save_expense_data.php';
$tblname = 'expense_entry';
$tblpkey = 'expense_id';
$imgpath = '../adminpanel/uploaded/expence_img/';

$amount = isset($_REQUEST['amount']) ? $obj->test_input($_POST['amount']) : "";
$expense_date = isset($_REQUEST['expense_date']) ? $obj->test_input($_POST['expense_date']) : "";
$remark = isset($_REQUEST['remark']) ? $obj->test_input($_POST['remark']) : "";




$form_data = array(
    'exp_amount' => $amount,
    'expense_date' => $expense_date,
    'remark' => $remark,
    'exp_status' => '0', //0 for pending
    'ipaddress' => $ipaddress,
    'createdby' => $loginid,
    'createdate' => $createdate,
);

$last_id = $obj->insert_record_lastid($tblname, $form_data);

if ($last_id != "") {
    if (isset($_FILES["image"]) && $_FILES["image"]['error'] == 0) {
        $imageFileType = strtolower(pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION));
        if ($imageFileType == 'jpg' || $imageFileType == 'jpeg' || $imageFileType == 'pdf') {
            $filename = $obj->compressAndMoveImage($_FILES["image"]['tmp_name'], $imgpath, 20);
            $form_data1 = array("exp_img" => $filename);
            $where1 = array($tblpkey => $last_id);
            $obj->update_record($tblname, $where1, $form_data1);
        }
    }
    echo 1;
} else {
    echo 2;
}
