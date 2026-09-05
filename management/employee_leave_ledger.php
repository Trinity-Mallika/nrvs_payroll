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
$leave_type = isset($_GET['leave_type']) ? $obj->test_input($_GET['leave_type']) : 'earning';
$date = date('Y-m-d', strtotime("$year-$month-01"));


$from_date = isset($_GET['from_date']) ? $obj->test_input($_GET['from_date']) : date('Y-m-01');
$to_date   = isset($_GET['to_date']) ? $obj->test_input($_GET['to_date']) :  date('Y-m-d');

$whereCredit = "emp_id='$emp_id' AND unit_id='$unitid'";
$whereAtt = "emp_id='$emp_id'";
 
if($from_date!='' && $to_date!=''){

    $whereCredit .= "
    AND createdate
    BETWEEN '$from_date' AND '$to_date'";

    $whereAtt .= "
    AND attendance_date
    BETWEEN '$from_date' AND '$to_date'";
}

if($leave_type!=''){
    if($leave_type=='earning'){
    $whereCredit .= " AND leave_type IN('earning','earning_ded')";
    }
    else{
        $whereCredit .= " AND leave_type='$leave_type'";
    }
} 


if (isset($_POST['department_idd'])) {
    $department_id = $_POST['department_idd'];
    $unit_id = $_POST['unit_id'];
    $options = "<option value=''>All</option>";
    $selected = "";
    if ($unit_id != "" || $unit_id > 0) {

        $res = $obj->executequery("Select * from department_master where unit_id='$unit_id' order by department_name asc");

        foreach ($res as $row) {
            $selected = ($department_id == $row['department_id']) ? 'selected' : '';
            $options .= "<option value='" . $row['department_id'] . "' $selected>" . $row['department_name'] . "  </option>";
        }
    }

    echo $options;
    die;
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
                        <form method="get" action="leave_ledger_pdf.php" target="_blank">
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
                                        <div class="col-lg-4 mb-3">
                                            <label for="unit_id" class="form-label">Unit Name<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select chosen-select" name="unit_id" id="unit_id"
                                                onchange="get_department(this.value);">
                                                <!-- <option value="">All</option> -->
                                                <?php $res = $obj->executequery("Select * from unit_master order by unit_name asc");
                                                        foreach ($res as $key) {
                                                            echo "<option value='" . $key['unit_id'] . "'>" . $key['unit_name'] . "</option>";
                                                        } ?>
                                            </select>
                                            <script>
                                            document.getElementById('unit_id').value = '<?= $unitid; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="emp_id" class="form-label">Department<span
                                                    class="text-danger fw-bold"> *</span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="department_id" id="department_id"
                                                onchange="get_employee(this.value);">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_id asc");
                                                foreach ($res as $key) { ?>
                                                <option value="<?= $key['department_id']; ?>">
                                                    <?= $key['department_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById('department_id').value =
                                                '<?= $department_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-4 col-12">
                                            <label for="emp_id" class="form-label">Employee Name<span
                                                    class="text-danger fw-bold">* </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_idd"
                                                id="emp_id">
                                                <option value="">Select Employee</option>
                                                <?php 
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


                                        <!-- Year -->
                                        <div class="col-lg-4 col-12">
                                            <label for="year" class="form-label">Year<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="year"
                                                id="year" onchange="setYearDate()">
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

                                        <div class="col-lg-2">
                                            <label>From Date</label>
                                            <input type="date" name="from_date" id="from_date"
                                                class="form-control form-control-sm" value="<?= $from_date ?>">
                                        </div>

                                        <div class="col-lg-2">
                                            <label>To Date</label>
                                            <input type="date" name="to_date" id="to_date"
                                                class="form-control form-control-sm" value="<?= $to_date ?>">
                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <label>Leave Type<span class="text-danger fw-bold">*</span></label>
                                            <select name="leave_type" id="leave_type"
                                                class="form-select form-select-sm chosen-select">
                                                <option value="">Select</option>
                                                <option value="earning">Earning Leave</option>
                                                <!-- <option value="weekly">C Off</option>
                                                <option value="eoff">Extra Off</option> -->
                                            </select>
                                            <script>
                                            document.getElementById('leave_type').value = '<?= $leave_type ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-4 mb-3 mt-2">
                                            <br>

                                            <input type="submit" class="btn btn-sm btn-primary add-btn" value="Search"
                                                onclick="return validateSearch();">
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
                                                    $from_date,
                                                    $to_date,
                                                    $sessionid
                                                ); 

                                                $closing_balance =  $obj->getClosingBalance( 
                                                    $emp_id,
                                                    $leave_type,
                                                    $from_date,
                                                    $to_date,
                                                    $sessionid
                                                );
                                                $ledger = [];  
                                                $openingDate = date('d-m-Y', strtotime($from_date));
                                                $sortDate    = date('Y-m-d', strtotime($from_date));

                                                $ledger[]=[
                                                    'sort_date' => $sortDate,
                                                    'date'=>$openingDate,
                                                    'remark' => '',
                                                    'leave_type'=>$leave_type,
                                                    'particular'=>'Opening Balance',
                                                    'credit'=>$opening_balance,
                                                    'debit'=>0
                                                ];
                                                $credit_res = $obj->executequery("
                                                    SELECT 
                                                        createdate,
                                                        month,
                                                        year,
                                                        leave_type,
                                                        is_opb,
                                                        remark,
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

                                             
                                                        if($row['leave_type']=='earning_ded'){
                                                            $ledger[]=[
                                                                'sort_date'=>$row['createdate'],
                                                                'date'=>date('M Y',mktime(0,0,0,$row['month'],1,$row['year'])),
                                                                'leave_type'=>'earning',
                                                                'particular'=>'Leave Deduction',
                                                                'remark' => $row['remark']??'',
                                                                'credit'=>0,
                                                                'debit'=>$row['total_leave']
                                                            ];
                                                        }else{
                                                            $ledger[]=[
                                                                'sort_date'=>$row['createdate'],
                                                                'date'=>date('M Y',mktime(0,0,0,$row['month'],1,$row['year'])),
                                                                'leave_type'=>$row['leave_type'],
                                                                'remark' => $row['remark']??'',
                                                                'particular'=>($row['is_opb']==1 ? 'By Entry Opening Balance' : 'Allotment'),
                                                                'credit'=>$row['total_leave'],
                                                                'debit'=>0
                                                            ];
                                                        }
                                                } 

                                                $used_res = $obj->executequery("
                                                    SELECT
                                                        attendance_date,
                                                        month,
                                                        year,
                                                        in_remark,
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
                                                $particular='Application';
                                                $ledger[] = [
                                                    'sort_date'=>$row['attendance_date'],
                                                    'date' => date(
                                                        'M Y',
                                                        mktime(0,0,0,$row['month'],1,$row['year'])
                                                    ),
                                                    'leave_type' => $type,
                                                    'remark' => $row['in_remark'],
                                                    'particular' => $particular,
                                                    'credit'     => 0,
                                                    'debit'      => $debit
                                                ];
                                            }
                                            usort($ledger,function($a,$b){

                                                return strtotime($a['sort_date']) <=> strtotime($b['sort_date']);

                                            });
                                        
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
                                                    <th>Remark</th>
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
                                                        <?=$obj->dateformatindia($row['sort_date']) ?>
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
                                                    <td>
                                                        <?=$row['remark']?>
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
                                                    <th></th>
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

    function get_employee(department_id, emp_id = 0) {
        $.ajax({
            type: "POST",
            url: 'get_dep_wise_emp.php',
            data: {
                department_id: department_id,
                emp_id: emp_id
            },

            success: function(data) {
                $('#emp_id').html(data).trigger("change.select2");
            }
        });

    }

    function setYearDate() {
        var year = document.getElementById("year").value;
        if (year == "") {
            document.getElementById("from_date").value = "";
            document.getElementById("to_date").value = "";
            return;
        }
        var today = new Date();
        var currentYear = today.getFullYear();
        document.getElementById("from_date").value = year + "-01-01";
        if (parseInt(year) == currentYear) {
            var month = ("0" + (today.getMonth() + 1)).slice(-2);
            var day = ("0" + today.getDate()).slice(-2);
            document.getElementById("to_date").value = currentYear + "-" + month + "-" + day;
        } else {
            document.getElementById("to_date").value = year + "-12-31";
        }
    }

    function validateSearch() {

        let department = $('#department_id').val();
        let employee = $('#emp_id').val();
        let year = $('#year').val();
        let leaveType = $('#leave_type').val();
 

        if (unit_id == '') {
            alert("Please select Unit.");
            $('#year').focus();
            return false;
        }
        // At least one required
        if (department == '' && employee == '') {
            alert("Please select at least Department or Employee.");
            $('#department_id').focus();
            return false;
        }

        if (year == '') {
            alert("Please select Year.");
            $('#year').focus();
            return false;
        }

        if (leaveType == '') {
            alert("Please select Leave Type.");
            $('#leave_type').focus();
            return false;
        }

        return true;
    }

     function get_department(unit_id, department_id = 0) {
            $.ajax({
                type: "POST",
                url: '',
                data: {
                    department_idd: department_id,
                    unit_id: unit_id,
                },
                success: function(data) {
                    $('#department_id').html(data).trigger("change.select2");
                }
            });

        }
    </script>


</body>

</html>