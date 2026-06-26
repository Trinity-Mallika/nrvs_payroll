<?php include("../adminsession.php");
$pagename = "emp_public_holiday_details.php";
$title = "Employee Public Holiday Details";
$tblname = "loan_advance";
$tblpkey = "loan_advance_id";
$module = "Employee Public Holiday Details";
$submodule = "Employee Public Holiday Details";
$btn_name = "Search";
$imgpath1 = 'uploaded/on_duty/';
$crit = ' and e.basic_salary <= 42000';
 
if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    if ($emp_id != '') {
        $crit .= " and e.emp_id='$emp_id'";
    }
} else {
    $emp_id = "";
};

if (isset($_GET['salary_count'])) {
    $salary_count = $obj->test_input($_GET['salary_count']);
    if ($salary_count == 'above') {
        $crit .= " and e.basic_salary >'$salary_count'";
    }elseif($salary_count == 'less'){
        $crit .= " and e.basic_salary <='$salary_count'";
    }
} else {
    $salary_count = "";
};
 
 
if (isset($_GET['month'])) {
    $month = $obj->test_input($_GET['month']);
     if ($month != '') {
       // $crit .= " and la.month='$month'";
    }
} else {
    $month =date('n');
};

if (isset($_GET['year'])) {
    $year = $obj->test_input($_GET['year']);
     if ($year != '') {
        //$crit .= " and la.year='$year'";
    }
} else {
    $year = date('Y');
};

 
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

.detail-row {
    display: none;
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
                <?php //include('inc/alert.php'); 
                ?>
                <div class="row">
                    <?php if(!isset($_GET['submit'])) { ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a href="loan_advance.php"
                                                    class="float-end btn btn-sm btn-primary">Add</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-lg-3 mb-2">
                                            <label for="emp_id" class="form-label">Employee Name<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id"
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
                                            document.getElementById('emp_id').value = '<?= $emp_id; ?>';
                                            </script>
                                        </div>


                                        <div class="col-lg-3 mb-3">
                                            <label for="">Holiday Month</label>
                                            <select name="month" id="month"
                                                class="form-select form-select-sm chosen-select">
                                                <option value="">Select Month</option>
                                                <option value="1">January</option>
                                                <option value="2">February</option>
                                                <option value="3">March</option>
                                                <option value="4">April</option>
                                                <option value="5">May</option>
                                                <option value="6">June</option>
                                                <option value="7">July</option>
                                                <option value="8">August</option>
                                                <option value="9">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-3 col-12">
                                            <label for="year" class="form-label">Year<span
                                                    class="text-danger fw-bold"></span></label>
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

                                            document.getElementById('month').value = '<?php echo $month ?>'
                                            </script>
                                        </div>


                                        <!-- <div class="col-lg-3 mb-2">
                                            <label for="salary_count" class="form-label">salary Details<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="salary_count"
                                                id="salary_count">
                                                <option value="above">Above 42000</option>
                                                <option value="less">Less then & Equal 42000</option>
                                                <option value="less">Less then & Equal 42000</option>
                                               
                                            </select>
                                            
                                        </div> -->



                                        <div class="col-lg-12 text-center mt-4">

                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn"
                                                value="<?php echo $btn_name ?> ">
                                            <a href=" <?php echo $pagename ?>" type="button"
                                                class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php 
                   
                    $monthName = date('F', mktime(0, 0, 0, $month, 1, $year));
                    if(isset($_GET['submit'])) { ?>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0">
                                                <?= $module; ?>
                                                <span class="ms-2 text-primary">
                                                    (<?= $monthName . ' ' . $year; ?>)
                                                </span>

                                                <a href="<?= $pagename ?>" class="float-end btn btn-sm btn-primary">
                                                    Search Again
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="auto-scroll-wrapper">
                                    <div class="table-responsive"> 
                                        
                                        <table id="buttons-datatables" class="display table table-sm table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>SNo</th>
                                                    <th>Emp Code</th>
                                                    <th>Emp Name</th>
                                                    <th>Salary</th>
                                                    <th>Holiday Date</th>
                                                    <th>Total Holiday</th>
                                                    <th>Holiday Salary</th>
                                                </tr>

                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;
                                                $total_employee=0;
                                                $total_holiday_salary = 0;
                                                $firstDateOfMonth = date("Y-m-01", strtotime("$year-$month-01"));
                            $lastDateOfMonth = date("Y-m-t", strtotime("$year-$month-01")); 
                              $fromDate  = date("Y-m-d", strtotime("$year-$month-01"));
 
                                $toDate   = date("Y-m-t", strtotime($fromDate));
                                               $employees = $obj->executequery("
                                    SELECT 
                                        e.emp_id,
                                        e.allow_weekly_off,
                                        e.department_id,
                                        e.is_esic,
                                        e.emp_code,
                                        e.first_name,
                                        e.last_name,
                                        e.mobile_no,
                                        e.aadhar_no,
                                        e.shift_id,
                                        e.basic_salary,
                                        e.date_of_joining,
                                        e.job_location,
                                        g.grade_name,
                                        d.department_name,
                                        d.earn_leave_check,
                                        d.c_off_check,
                                        des.designation,
                                        s.working_hour AS shift_hours

                                    FROM employee_master e

                                    LEFT JOIN grade_master g 
                                        ON g.grade_id = e.grade_id

                                    LEFT JOIN department_master d 
                                        ON d.department_id = e.department_id

                                    LEFT JOIN designation_master des 
                                        ON des.designation_id = e.designation_id

                                    LEFT JOIN shift_master s 
                                        ON s.shift_id = e.shift_id
                                
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
                                        ON eas.emp_id = e.emp_id

                                    WHERE 
                                        e.unit_id = '$unitid'   
                                        AND e.is_active = '1'
                                        AND e.date_of_joining <= '$lastDateOfMonth'

                                        AND (
                                            e.resign_status != '1' 
                                            OR (
                                                e.resign_status = '1' 
                                                AND e.last_working_date >= '$firstDateOfMonth'
                                            )
                                        )
                                        
                                        AND (
                                            eas.active_id IS NULL
                                            OR eas.is_active = '1'
                                        )

                                        $crit

                                    GROUP BY e.emp_id
                                    ORDER BY e.emp_code
                                ");

                                 if (empty($employees)) {
                                    $employees = [];
                                }
                                $empIds = array_column($employees, 'emp_id');
                                if (empty($empIds)) {
                                    $empIdsStr = '0';
                                } else {
                                    $empIdsStr = implode(',', $empIds);
                                }
 
                                  $holidayAttendanceStart = date('Y-m-d', strtotime($fromDate . ' -1 day'));
                                $holidayAttendanceEnd   = date('Y-m-d', strtotime($toDate . ' +1 day'));
                                $holidayAttendanceRows = $obj->executequery("
                                                            SELECT 
                                                                emp_id,
                                                                attendance_date,
                                                                attendance_status

                                                            FROM attendance_entry

                                                            WHERE emp_id IN ($empIdsStr)

                                                            AND attendance_date BETWEEN '$holidayAttendanceStart' 
                                                            AND '$holidayAttendanceEnd'

                                                            AND unit_id='$unitid'
                                                        ");
                                                        $holidayAttendanceMap = [];

                                foreach ($holidayAttendanceRows as $row) {

                                    $holidayAttendanceMap[$row['emp_id']][$row['attendance_date']]
                                        = $row['attendance_status'];
                                }
                                $holidayRows = $obj->executequery("
                                                                SELECT date , holiday_type
                                                                FROM holiday_entry
                                                                WHERE FIND_IN_SET('$unitid', unit_id)
                                                                AND date BETWEEN '$fromDate' AND '$toDate'
                                                            ");

                                $holidays = [];
                                $total_holiday=0;
                                
                                foreach ($holidayRows as $h) {
                                    $holidays[$h['date']] = true;
                                }
                                  $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                                foreach ($employees as $row) { 
                                                    $empId = $row['emp_id'];
                                                    $holidayData = $obj->getHolidayCountWithSandwichRule2(
                                                        $empId,
                                                        $holidayRows,
                                                        $holidayAttendanceMap
                                                    );

                                                    $holiday = $holidayData['total']; 
                                                    if ($holiday <= 0) {
                                                        continue;
                                                    }
                                                    $total_holiday +=$holiday ;
                                                    $total_employee++;
                                                   
                                                    $perDaySalary = $row['basic_salary'] / $daysInMonth;
                                                    $holidaySalary = round($perDaySalary * $holiday, 2);

                                                    $total_holiday_salary += $holidaySalary;
                                                    $holidayDates = !empty($holidayData['dates'])
                                                        ? implode(', ', array_map(function($d){
                                                            return date('d-m-Y', strtotime($d));
                                                        }, $holidayData['dates']))
                                                        : '-';

                                                ?>

                                                <tr>
                                                    <td class="details-control text-center">
                                                        <?php echo $slno++; ?>
                                                    </td>
                                                    <td><?= $row['emp_code'] ?></td>
                                                    <td> <?= $row['first_name'] . " " . $row['last_name'] ?> </td>
                                                    <td><?= $row['basic_salary'] ?></td>

                                                    <td><?= $holidayDates ?></td>
                                                    <td><?= $holiday?></td>
                                                    <td><?= number_format($holidaySalary, 2) ?></td>
                                                </tr>
                                                <?php }  ?>
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-success fw-bold">
                                                    <td colspan="5" class="text-end">
                                                        Total Holiday
                                                    </td>
                                                    <td><?=$total_holiday;?></td>
                                                    <td><?= number_format($total_holiday_salary, 2) ?></td>
                                                </tr>
                                            </tfoot>

                                        </table>


                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    </div><!-- Loan / Advance Approval Modal -->

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