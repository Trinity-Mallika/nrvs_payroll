<?php
include("appsession.php");

$id = $_POST['id'];
$type = $_POST['type'];
$status = $_POST['status'];
if($type=='all'){
$where = ['on_duty_id'=>$id , 'status'=>0];
$obj->update_record("leave_apply_detail",$where,['status'=>$status]);

echo "success";
}else if($type=='single'){
    
$where = ['leave_details_id'=>$id];
$obj->update_record("leave_apply_detail",$where,['status'=>$status]);

echo "success";
 
}else{
 echo 'error';

}
 
?>