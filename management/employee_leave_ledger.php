<?php include("../adminsession.php");
$pagename = "employee_leave_ledger.php";
$title = "Employee Leave Ledger";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Employee Leave Ledger";
$submodule = "Employee Leave Ledger";
$btn_name = "Save";

$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$emp_id = (isset($_GET['emp_idd'])) ? $obj->test_input($_GET['emp_idd']) : '';
$month = (isset($_GET['month'])) ? $obj->test_input($_GET['month']) :date('n');
$year = (isset($_GET['year'])) ? $obj->test_input($_GET['year']) :  date('Y');
$leave_type = isset($_GET['leave_type']) ? $obj->test_input($_GET['leave_type']) : '';
$date = date('Y-m-d', strtotime("$year-$month-01"));
$whereCredit = "emp_id='$emp_id' AND unit_id='$unitid'";
$whereAtt = "emp_id='$emp_id'";
 
 
if($year!=''){
    $whereCredit .= " AND year='$year'";
    $whereAtt .= " AND YEAR(attendance_date)='$year'";
}

if($month!=''){
    $whereCredit .= " AND month='$month'";
    $whereAtt .= " AND MONTH(attendance_date)='$month'";
}

if($leave_type!=''){
    $whereCredit .= " AND leave_type='$leave_type'";
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
<style>
.table-borderless tr td {
    border: 0px !important;
    padding-bottom: 0px;
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
                <?php include('inc/alert.php'); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <form method="get" action="">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $submodule; ?>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <label for="emp_id" class="form-label">Employee Name<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_idd"
                                                id="emp_id">
                                                <option value="">Select Employee</option>
                                                <?php
                                                    //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                    $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                    foreach ($res as $key) { ?>
                                                <option value="<?= $key['emp_id']; ?>">
                                                    <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?>
                                                    <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById('emp_id').value =
                                                '<?= $emp_id; ?>';
                                            </script>
                                        </div>
                                        <!-- Month -->
                                        <div class="col-lg-3 col-12">
                                            <label for="month" class="form-label">Month<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="month"
                                                id="month">
                                                <option value="">Select</option>
                                                <?php
                                                    $months = [
                                                        1 => 'January',
                                                        2 => 'February',
                                                        3 => 'March',
                                                        4 => 'April',
                                                        5 => 'May',
                                                        6 => 'June',
                                                        7 => 'July',
                                                        8 => 'August',
                                                        9 => 'September',
                                                        10 => 'October',
                                                        11 => 'November',
                                                        12 => 'December'
                                                    ];
                                                    foreach ($months as $value => $name) {
                                                        echo "<option value=\"$value\">$name</option>";
                                                    }
                                                    ?>
                                            </select>
                                            <script>
                                            document.getElementById('month').value = '<?php echo $month ?>'
                                            </script>
                                        </div>

                                        <!-- Year -->
                                        <div class="col-lg-3 col-12">
                                            <label for="year" class="form-label">Year<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="year"
                                                id="year">
                                                <option value="">Select</option>
                                                <?php
                                                    $startYear = 2025;
                                                    $endYear = 2100;
                                                    for ($year1 = $startYear; $year1 <= $endYear; $year1++) {
                                                        echo "<option value=\"$year1\">$year1</option>";
                                                    } ?>
                                            </select>
                                            <script>
                                            document.getElementById('year').value = '<?php echo $year ?>'
                                            </script>
                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <label>Leave Type<span class="text-danger fw-bold">*</span></label>
                                            <select name="leave_type" id="leave_type"
                                                class="form-select form-select-sm chosen-select">
                                                <option value="">All</option>
                                                <option value="earning">Earning Leave</option>
                                                <option value="weekly">C Off</option>
                                                <option value="eoff">Extra Off</option>
                                            </select>
                                            <script>
                                            document.getElementById('leave_type').value = '<?= $leave_type ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-4 mb-3 mt-2">
                                            <br>

                                            <input type="submit" class="btn btn-sm btn-primary add-btn" value="Search"
                                                onClick="return checkinputmaster('emp_id,year,leave_type')">
                                            <a href=" <?php echo $pagename ?>" type="button"
                                                class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php if(isset($_GET['leave_type'])){ ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"><?php echo $submodule; ?> </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <div class="auto-scroll-wrapper">
                                        <div class="table-responsive">
                                            <?php
                                                $opening_balance = $obj->getOpeningBalance( 
                                                    $emp_id,
                                                    $leave_type,
                                                    $month,
                                                    $year,
                                                    $sessionid
                                                ); 

                                                $closing_balance =  $obj->getClosingBalance( 
                                                    $emp_id,
                                                    $leave_type,
                                                    $month,
                                                    $year,
                                                    $sessionid
                                                );
                                                $ledger = [];  
                                                $ledger[] = [
                                                        'date' => date(
                                                            'M Y',
                                                            mktime(
                                                                0,
                                                                0,
                                                                0,
                                                                !empty($month) ? $month : date('n'),
                                                                1,
                                                                !empty($year) ? $year : date('Y')
                                                            )
                                                        ),
                                                        'leave_type' => $leave_type,
                                                        'particular' => 'Opening Balance',
                                                        'credit'     => $opening_balance,
                                                        'debit'      => 0
                                                    ];
                                            
                                                $credit_res = $obj->executequery("
                                                    SELECT 
                                                        createdate,
                                                        month,
                                                        year,
                                                        leave_type,
                                                        is_opb,
                                                        total_leave
                                                    FROM emp_monthly_leave
                                                    WHERE $whereCredit
                                                    ORDER BY year,month
                                                "); 
                                                
                                                foreach($credit_res as $row){
                                                    if($row['is_opb']==2){
                                                        $txt = 'Public Holiday';
                                                    }else{
                                                        $txt = 'Opening';
                                                    }
                                                   $transDate = ($row['is_opb'] == 1)
                                                        ? $row['year'] . '-' . str_pad($row['month'], 2, '0', STR_PAD_LEFT) . '-01'
                                                        : (!empty($row['createdate'])
                                                            ? $row['createdate']
                                                            : $row['year'] . '-' . str_pad($row['month'], 2, '0', STR_PAD_LEFT) . '-01');

                                             
                                                        
                                                    $ledger[] = [
                                                        'date' => date(
                                                                'M Y',
                                                                mktime(0,0,0,$row['month'],1,$row['year'])
                                                            ),
                                                        'leave_type' => $row['leave_type'],
                                                        'particular' => 'By'.' '. $txt,
                                                        'credit'     => $row['total_leave'],
                                                        'debit'      => 0
                                                    ];
                                                } 

                                                $used_res = $obj->executequery("
                                                    SELECT
                                                        attendance_date,
                                                        month,
                                                        year,
                                                        attendance_status
                                                    FROM attendance_entry
                                                    WHERE $whereAtt
                                                    AND attendance_status IN (
                                                        'Extra Off',
                                                        'Half Extra Off',
                                                        'C Off',
                                                        'Half C Off',
                                                        'Leave',
                                                        'Half Leave',
                                                        'Earning Leave',
                                                        'Half Earning Leave'
                                                    )
                                                    ORDER BY attendance_date
                                                ");

                                                foreach($used_res as $row){

                                                $type = '';
                                                $debit = 1;

                                                switch($row['attendance_status']){

                                                    case 'Extra Off':
                                                        $type='eoff';
                                                        $debit=1;
                                                        break;

                                                    case 'Half Extra Off':
                                                        $type='eoff';
                                                        $debit=0.5;
                                                        break;

                                                    case 'C Off':
                                                        $type='weekly';
                                                        $debit=1;
                                                        break;

                                                    case 'Half C Off':
                                                        $type='weekly';
                                                        $debit=0.5;
                                                        break;

                                                    case 'Leave':
                                                    case 'Earning Leave':
                                                        $type='earning';
                                                        $debit=1;
                                                        break;

                                                    case 'Half Leave':
                                                    case 'Half Earning Leave':
                                                        $type='earning';
                                                        $debit=0.5;
                                                        break;
                                                }

                                                if($leave_type!='' && $leave_type!=$type){
                                                    continue;
                                                }

                                                $ledger[] = [
                                                    'date' => date(
                                                        'M Y',
                                                        mktime(0,0,0,$row['month'],1,$row['year'])
                                                    ),
                                                    'leave_type' => $type,
                                                    'particular' => $row['attendance_status'],
                                                    'credit'     => 0,
                                                    'debit'      => $debit
                                                ];
                                            }
                                            
                                        
                                            $balances = [
                                                    'earning' => 0,
                                                    'weekly'  => 0,
                                                    'eoff'    => 0
                                                ];
                                            ?>

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
                                                           <?= $row['date'] ?>
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
                                                <tfoot>
                                                    <tr class="table-success">
                                                        <th colspan="5" class="text-end">
                                                            Closing Balance
                                                        </th>
                                                        <th>
                                                            <?= number_format($closing_balance,2) ?>
                                                        </th>
                                                    </tr>
                                                </tfoot>

                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>  
                    <?php  } ?>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    </div>
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <script>
    $(document).ready(function() {
        $('#example').DataTable();
        $(".chosen-select").select2({
            width: '100%',
            search_contains: true
        });

    });

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
    </script>
</body>

</html>