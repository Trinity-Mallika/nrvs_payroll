<?php
include("appsession.php");

$current_date = date('Y-m-d');

$app_month = date('m', strtotime($current_date));
$app_year  = date('Y', strtotime($current_date));
$total_earning_leave = $obj->getEarningLeave($emp_id,$sessionid);
$c_off_balance = $obj->getEmpCoffLeave($emp_id,$sessionid,$app_month,$app_year);
$extra_off = $obj->getExtraOffBalance($emp_id,$app_month,$app_year);

$sql = "
SELECT 
leave_type,
IFNULL(SUM(
CASE
WHEN leave_day IN ('FD','SL') THEN 1
WHEN leave_day IN ('FHD','SHD') THEN 0.5
ELSE 0
END
),0) availed_leave
FROM leave_apply_detail
WHERE emp_id='$emp_id'
AND status!='1'
AND unit_id='$unitid'
GROUP BY leave_type
";

$res = $obj->executequery($sql);

$availed_EL=0;
$availed_EO=0;
$availed_CO=0;

foreach($res as $r){

    if($r['leave_type']=='EL')
        $availed_EL=$r['availed_leave'];

    if($r['leave_type']=='EO')
        $availed_EO=$r['availed_leave'];

    if($r['leave_type']=='CO')
        $availed_CO=$r['availed_leave'];
}

echo json_encode([
    'remaining_EL'=>$total_earning_leave-$availed_EL,
    'remaining_EO'=>$extra_off['balance']-$availed_EO,
    'remaining_CO'=>$c_off_balance-$availed_CO,

    'availed_EL'=>$availed_EL,
    'availed_EO'=>$availed_EO,
    'availed_CO'=>$availed_CO,
]);