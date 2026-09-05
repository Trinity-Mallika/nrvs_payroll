<?php
include("../adminsession.php");

$emp_id = $_POST['emp_id'];
$sessionid = $_SESSION['sessionid'];
$month = $_POST['month'];
$year  = $_POST['year'];

 
$opening_earning = $obj->getOpeningLeave($emp_id,'earning',$month,$year,$sessionid);
$opening_weekly  = $obj->getOpeningLeave($emp_id,'weekly',$month,$year,$sessionid);
$opening_eoff    = $obj->getOpeningLeave($emp_id,'eoff',$month,$year,$sessionid);
 
$sql = "
SELECT 
    month,
    year,
    leave_type,
    COALESCE(SUM(total_leave),0) AS uploaded_leave
FROM emp_monthly_leave
WHERE emp_id='$emp_id' and month='$month' and year='$year'  
ORDER BY year ASC, month ASC
";
$res1 = $obj->executequery($sql);

?>

<!-- SUMMARY -->
 <?php
$monthName = date("F", mktime(0, 0, 0, $month, 1));
?>

<h6 class="mb-3 fw-bold">
    <?= $monthName ?> Opening Balance
</h6>
<div class="row g-2 mb-3">
 

    <!-- EARNING LEAVE -->
     
    <div class="col-md-4">
        <div class="card border shadow-sm">
            <div class="card-body text-center p-2">
                <h6 class="mb-1" style="font-size:12px;">Earn Leave</h6>
                <h5 class="text-primary mb-0">
                    <?=$opening_earning?>
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border shadow-sm">
            <div class="card-body text-center p-2">
                <h6 class="mb-1" style="font-size:12px;">C Off</h6>
                <h5 class="text-danger mb-0">
                    <?=$opening_weekly?>
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border shadow-sm">
            <div class="card-body text-center p-2">
                <h6 class="mb-1" style="font-size:12px;">Extra Off</h6>
                <h5 class="text-success mb-0">
                    <?=$opening_eoff?>
                </h5>
            </div>
        </div>
    </div>

</div>

<?php

$ledger = [];

/* ================= CREDIT ENTRIES ================= */

$credit_res = $obj->executequery("
    SELECT
        CONCAT(year,'-',LPAD(month,2,'0'),'-01') AS trans_date,
        leave_type,
        total_leave
    FROM emp_monthly_leave
    WHERE emp_id='$emp_id'
    AND month='$month'
    AND year='$year'
    ORDER BY year,month
");

foreach($credit_res as $row){

    $ledger[] = [
        'date'       => $row['trans_date'],
        'leave_type' => $row['leave_type'],
        'particular' => 'Leave Credit',
        'credit'     => $row['total_leave'],
        'debit'      => 0
    ];
}


/* ================= USED ENTRIES ================= */

$used_res = $obj->executequery("
    SELECT
        attendance_date,
        attendance_status
    FROM attendance_entry
    WHERE emp_id='$emp_id'
    AND MONTH(attendance_date)='$month'
    AND YEAR(attendance_date)='$year'
    AND attendance_status IN (
        'Extra Off','Half Extra Off',
        'C Off','Half C Off',
        'Earning Leave','Half Earning Leave',
        'Leave','Half Leave'
    )
");

foreach($used_res as $row){

    $leave_type = '';

    if(in_array($row['attendance_status'],['Extra Off','Half Extra Off'])){
        $leave_type = 'eoff';
    }
    elseif(in_array($row['attendance_status'],['C Off','Half C Off'])){
        $leave_type = 'weekly';
    }
    else{
        $leave_type = 'earning';
    }

    $debit = (
        strpos($row['attendance_status'],'Half') !== false
    ) ? 0.5 : 1;

    $ledger[] = [
        'date'       => $row['attendance_date'],
        'leave_type' => $leave_type,
        'particular' => $row['attendance_status'],
        'credit'     => 0,
        'debit'      => $debit
    ];
}

/* ================= SORT BY DATE ================= */

usort($ledger,function($a,$b){
    return strtotime($a['date']) - strtotime($b['date']);
});

/* ================= BALANCES ================= */

$balances = [
    'earning' => $opening_earning,
    'weekly'  => $opening_weekly,
    'eoff'    => $opening_eoff
];
  
?>




<div class="table-responsive">
    
    <table class="table table-bordered table-sm">

        <thead class="table-primary">
            <tr>
                <th>Date</th>
                <th>Leave Type</th>
                <th>Particular</th>
                <th>Credit</th>
                <th>Debit</th>
                <th>Balance</th>
            </tr>
        </thead>

        <tbody>

            <?php

foreach($ledger as $row){

    $balances[$row['leave_type']] += $row['credit'];
    $balances[$row['leave_type']] -= $row['debit'];

    switch($row['leave_type']){

        case 'earning':
            $leave_name = 'Earning Leave';
            break;

        case 'weekly':
            $leave_name = 'C Off';
            break;

        case 'eoff':
            $leave_name = 'Extra Off';
            break;

        default:
            $leave_name = ucfirst($row['leave_type']);
    }

?>

            <tr>

                <td>
                    <?=date('d-m-Y',strtotime($row['date']))?>
                </td>

                <td>
                    <?=$leave_name?>
                </td>

                <td>
                    <?=$row['particular']?>
                </td>

                <td class="text-success fw-bold">
                    <?=$row['credit']?>
                </td>

                <td class="text-danger fw-bold">
                    <?=$row['debit']?>
                </td>

                <td>
                    <?=$balances[$row['leave_type']]?>
                </td>

            </tr>

            <?php } ?>

        </tbody>

    </table>
</div>