<?php include("appsession.php");
$apply_date = $_REQUEST['apply_date'];
$empid = $_SESSION['empid'];
$to_date = $_REQUEST['to_date'];
$leave_type = $obj->test_input($_REQUEST['leave_type']);
$message = $obj->test_input($_REQUEST['message']);
$full_half_day = $obj->test_input($_REQUEST['full_half_day']);


//print_r($_REQUEST);die;
$date1=date_create($apply_date);
$date2=date_create($to_date);
$diff=date_diff($date1,$date2);
$no_of_day =  $diff->format("%a") + 1;
if($full_half_day=='Half Day')
{
  
  $is_halfday = '0.5';
}
else
{
  $is_halfday = '';
}
$countpsl = $obj->getvalfield("leave_application","sum(no_of_day)","leave_type='$leave_type' and empid='$empid' and is_approve = 1");
$totpl = $obj->getvalfield("leave_setting","pl","empid='$empid'");
$totsl = $obj->getvalfield("leave_setting","sl","empid='$empid'");
if($leave_type == 'PL')
{
  $bal_psl = $totpl - $countpsl;
}
if($leave_type == 'SL'){

  $bal_psl = $totsl - $countpsl;
}

if($leave_type == 'CL'){

  $bal_psl = 1;
}

 if($bal_psl > 0)
 {
   
   if ($apply_date != "" && $message != "" && $leave_type !="") {
   $form_data = array('empid' => $loginid, 'apply_date' => $apply_date,'to_date' => $to_date, 'message' => $message, 'createdate' => $createdate, 'ipaddress' => $ipaddress,'leave_type'=>$leave_type,'no_of_day'=>$no_of_day,'full_half_day'=>$full_half_day,'is_halfday'=>$is_halfday);
   $obj->insert_record("leave_application", $form_data);

   $emp_name = $obj->getvalfield("m_employee","emp_name","empid='$loginid'");
   $apply_date = $obj->dateformatindia($apply_date);
   $to_date = $obj->dateformatindia($to_date);
   $msg = "$emp_name\n\nLeave Date : $apply_date - $to_date \nDescription : $message \nLeave Type: $leave_type";
      /*$obj->send_whatsapp("9901088885", $msg, "");//umashankar sir
      $obj->send_whatsapp("9980799996", $msg, "");//sridhar sir
      $obj->send_whatsapp("9900084926", $msg, "");//swetha madam*/
     // $obj->send_whatsapp("9926974435", $msg, "");//radhika
   echo '1';
} else {
   echo '2';
}
 }
 else
 {

   echo '3';
 }


