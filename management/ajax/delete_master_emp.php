<?php include("../../adminsession.php");

$id  = (int)$obj->test_input($_POST['id']);
$tblname  = $obj->test_input($_REQUEST['tblname']);
$tblpkey  = $obj->test_input($_REQUEST['tblpkey']);
$imgpath  = $obj->test_input($_REQUEST['imgpath']);
if ($id > 0) {
    $where = array($tblpkey => $id);
    $obj->delete_record($tblname, $where);
    $obj->delete_record('emp_language', $where);

    $docs = $obj->executequery(
        "SELECT doc_file FROM emp_document WHERE $tblpkey = '$id'"
    );

    foreach ($docs as $row) {
        if (!empty($row['doc_file'])) {
            $filepath =  '../' . $imgpath . $row['doc_file'];
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
    }

    $obj->delete_record('emp_document', $where);
    $obj->delete_record('emp_education', $where);
    $obj->delete_record('emp_family_details', $where);
    $form_data1 = array(
        "primary_id" => $id,
        "flag" => 'Record Deleted Successfully',
        "activity_type" => 'Deleted',
        "createdby" => $loginid,
        "pagename" => $tblname,
        "created_date" => $createdate,
        "created_time" => date('H:i:s'),
        "unit_id" => $unitid,
        'ipaddress' => $ipaddress,
        "sessionid" => $sessionid
    );
    $logactivity = $obj->insert_record("logactivity_master", $form_data1);
}
