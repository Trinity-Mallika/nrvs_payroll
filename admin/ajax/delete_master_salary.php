<?php include("../../adminsession.php");

$id  = (int)$obj->test_input($_POST['id']);
$tblname  = $obj->test_input($_REQUEST['tblname']);
$tblpkey  = $obj->test_input($_REQUEST['tblpkey']);
$month  = $obj->test_input($_REQUEST['month']);
$year  = $obj->test_input($_REQUEST['year']);
$emp_id  = $obj->test_input($_REQUEST['emp_id']);
$salary_struc_id  = $obj->test_input($_REQUEST['salary_struc_id']??0);

// print_r($_REQUEST);
// die;

if ($id > 0) {
   
    $where = array($tblpkey => $id);
    $where2 = array('emp_id' => $emp_id, 'month' => $month, 'year' => $year,'unit_id'=>$unitid,'leave_type'=>'earning','is_opb'=> 0);
    $where4 = array('emp_id' => $emp_id, 'month' => $month, 'year' => $year,'unit_id'=>$unitid,'leave_type'=>'weekly','is_opb'=>'0');
    $where3 = array($tblpkey => $id,"type"=>'increment');
    $obj->delete_record($tblname, $where);
    $obj->delete_record('emp_monthly_leave', $where2);
    $obj->delete_record('emp_monthly_leave', $where4);
    $obj->delete_record('emp_promotion', $where3);

    $obj->update_record("loan_advance_details", ['emp_id' => $emp_id, 'month' => $month, 'year' => $year, 'type' => 'Loan'], ['is_paid' => 0, 'paid_date' => null]);
    $obj->update_record("loan_advance_details", ['emp_id' => $emp_id, 'month' => $month, 'year' => $year, 'type' => 'Advance'], ['is_paid' => 0, 'paid_date' => null]);
    
    $basic_salary = $obj->getvalfield("emp_promotion","basic_salary","emp_id='$emp_id' order by emp_promotion_id desc");
    $obj->update_record("employee_master", ['emp_id' => $emp_id], ['basic_salary' =>$basic_salary]);

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
