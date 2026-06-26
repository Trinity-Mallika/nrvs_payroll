<?php include("../adminsession.php");
$pagename = "pf_esic_formate.php";
 
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "PF ESIC FORMAT";
$submodule = "PF ESIC FORMAT";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$unit_pf_rate = $obj->getvalfield("unit_master", "pf_rate", "unit_id='$unitid'");
$unit_esic_rate = $obj->getvalfield("unit_master", "esic_rate", "unit_id='$unitid'");

$crit = '';

if (isset($_GET['month'])) {
    $month = $_GET['month'];
    // if ($month != '') {
    //     $crit .= " and  month='$month'";
    // }
} else {
    $month = (int)date('m');
}

if (isset($_GET['year'])) {
    $year = $_GET['year'];
} else {
    $year = date('Y');
}
$totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
if (isset($_GET['format_type'])) {
    $format_type = $_GET['format_type']; 
    if ($format_type == '0') {
        $crit .= " and (em.is_pf='1' OR ss.revised_salary<='$unit_pf_rate')";
    }else{
        $crit .= " and (em.is_esic='1' OR ss.esic_rate<='$unit_esic_rate')";
    }
} else {
    $format_type = '';
}
$monthName = date("F", mktime(0, 0, 0, $month, 10));
$title = "PF ESIC FORMAT " . $monthName . ' - ' . $year;
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
                    <?php if (!isset($_GET['search'])) { ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">
                                        <!-- Month -->
                                        <div class="col-lg-3 col-12">
                                            <label for="month" class="form-label">Month<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select chosen-select" name="month" id="month">
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
                                            <select class="form-select chosen-select" name="year" id="year">
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
                                            <label for="format_type" class="form-label">Formate Type<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select chosen-select" name="format_type"
                                                id="format_type">
                                                <option value="0">PF Format</option>
                                                <option value="1">ESIC Format</option>
                                            </select>

                                        </div>
                                        <div class="col-lg-3 col-12 mt-4">
                                            <input type="submit" name="search" class="btn btn-sm btn-primary add-btn"
                                                value="Search" onClick="return checkinputmaster('emp_id,month,year')">
                                            <a href="<?php echo $pagename ?>"
                                                class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php } 
                     if(isset($_GET['search'])){
                        $firstDateOfMonth = date("Y-m-01", strtotime("$year-$month-01"));
                        $lastDateOfMonth = date("Y-m-t", strtotime("$year-$month-01")); 
                        $sql ="
                            SELECT 
                                em.*,
                                ss.pf_paid_basic,
                                ss.pf_emp,
                                ss.revised_salary,
                                ss.total_working_days
                              
                            FROM employee_master em  
                            LEFT JOIN salary_structure as ss ON ss.emp_id = em.emp_id and month='$month' and year='$year'
                            LEFT JOIN (
                                SELECT a1.*
                                FROM emp_active_status a1
                                INNER JOIN (
                                    SELECT 
                                        emp_id,
                                        MAX(active_id) AS last_id
                                    FROM emp_active_status
                                    WHERE (
                                            YEAR(last_inactive_date) < '$year'
                                            OR (
                                                YEAR(last_inactive_date) = '$year'
                                                AND MONTH(last_inactive_date) <= '$month'
                                            )
                                        )
                                    GROUP BY emp_id
                                ) a2 
                                ON a1.active_id = a2.last_id
                            ) eas 
                                ON eas.emp_id = em.emp_id

                            where em.unit_id = '$unitid'  And em.is_active = '1'  
                            AND em.date_of_joining <= '$lastDateOfMonth'
                                AND (
                                    em.resign_status != '1' 
                                    OR (
                                        em.resign_status = '1' 
                                        AND em.last_working_date >= '$firstDateOfMonth'
                                    )
                                ) 
                                AND (
                                    eas.active_id IS NULL
                                    OR eas.is_active = '1'
                                ) 
                                $crit
                            GROUP BY em.emp_id
                            ORDER BY em.emp_code";
                        $res = $obj->executequery($sql);
                        $emp_count = count($res); 
                        $slno = 1;
                     
                    ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <!-- Left -->
                                    <div class="col-sm-4">
                                        <h5 class="card-title mb-0">
                                           <?php echo $format_type== 0 ?'PF':'ESIC'; ?>
                                                Format
                                        </h5>
                                    </div>
                                    <!-- Center -->
                                    <div class="col-sm-4 text-center">
                                        <h5 class="mb-0 fw-bold text-primary">
                                            <?= $monthName . " - " . $year; ?>
                                        </h5>
                                    </div>
                                    <!-- Right -->
                                    <div class="col-sm-4 text-end">
                                        <a href="pf_esic_formate.php" class="float-end btn btn-primary btn-sm ms-4">search Again</a>
                                    </div>

                                </div>
                            </div> 
                            <div class="card-body">
                                <div class="auto-scroll-wrapper">
                                    <div class="table-responsive">
                                        <h5 class="text-primary">Total Employee : <?= $emp_count ?></h5>

                                        <?php if($format_type==0){?>
                                        <table id="buttons-datatables" class="display table table-sm table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>Sr No.</th>
                                                    <th>EMP CODE</th>
                                                    <th>UAN</th>
                                                    <th>MEMBER NAME</th>
                                                    <th>GROSS WAGES</th>
                                                    <th>EPF WAGES</th>
                                                    <th>EPS WAGES</th>
                                                    <th>EDLI WAGES</th>
                                                    <th>EPF CONTRI REMITTED</th>
                                                    <th>EPS CONTRI REMITTED</th>
                                                    <th>EPF EPS DIFF REMITTED</th> 
                                                    <th>NCP DAYS</th>
                                                    <th>REFUND OF ADVANCES</th>
                                                    <th>TOTAL DAYS IN MONTH</th>
                                                    <th>WORKING DAYS</th>  
                                                    <th>EPS + EPF EPS REMITTED</th> 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php  
                                                
                                                foreach ($res as $row) {
                                                    $eps_contri   = round(($row['pf_paid_basic'] * 8.33) / 100);
                                                    $epf_eps_diff = round(($row['pf_paid_basic'] * 3.67) / 100);
                                                    $ncp_days = $totalDaysInMonth - $row['total_working_days'];
                                                    $ncp_days = $totalDaysInMonth - $row['total_working_days'];
                                                    $eps_epf_diff = $row['pf_emp']-($eps_contri+ $epf_eps_diff);
                                                ?>
                                                <tr id="tr_<?= $row["emp_id"]; ?>">
                                                    <td><?php echo $slno++; ?></td>
                                                    <td><?= $row["emp_code"]; ?></td>
                                                    <td><?php echo $row['pf_uan']; ?></td>
                                                    <td> <?= ucfirst($row['first_name'] ?? ''); ?> </td>
                                                    <td><?php echo $row['revised_salary']; ?></td>
                                                    <td><?= $row['pf_paid_basic']?></td>
                                                    <td><?= $row['pf_paid_basic']?></td>
                                                    <td><?= $row['pf_paid_basic']?></td>
                                                    <td><?= $row['pf_emp']?></td> 
                                                    <td><?=$eps_contri?></td>
                                                    <td><?=$epf_eps_diff?></td>
                                                    <td><?=$ncp_days?></td>
                                                    <td>0</td>
                                                    <td><?=$totalDaysInMonth?></td>
                                                    <td><?= $row['total_working_days']?></td>
                                                    <td><?= $eps_epf_diff?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php }else{ ?>
                                        <table id="buttons-datatables" class="display table table-sm table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>Sr No.</th>
                                                    <th>EMP CODE</th>
                                                    <th>IP Number <br><span class="text-danger fw-bold">(10
                                                            Digits)</span> </th>
                                                    <th>IP Name <br><span class="text-danger fw-bold">( Only alphabets
                                                            and space )</span></th>
                                                    <th>No of Days for <br> which wages paid/payable <br> during the
                                                        month</th>
                                                    <th>Total Monthly Wages</th>
                                                    <th>Reason Code for Zero workings days(numeric only; provide 0 for
                                                        all other reasons- Click on the link for reference)</th>
                                                    <th> Last Working Day <br> <span class="text-danger fw-bold">(
                                                            Format DD/MM/YYYY or DD-MM-YYYY) </span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php   
                                                foreach ($res as $row) {

                                                ?>
                                                <tr id="tr_<?= $row["emp_id"]; ?>">
                                                    <td><?php echo $slno++; ?></td>

                                                    <td><?= $row["emp_code"]; ?></td>
                                                    <td><?php echo $row['pf_uan']; ?></td>
                                                    <td> <?= ucfirst($row['first_name'] ?? ''); ?> </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php } ?>

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
    </script>
</body>

</html>