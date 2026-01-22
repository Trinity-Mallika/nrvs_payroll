<?php include("../adminsession.php");
$pagename = "salary_generate.php";
$title = "Salary Generate";
$tblname = "salary_structure";
$tblpkey = "salary_struc_id";
$module = "Salary Generate";
$submodule = "Salary Generate List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = ' and 1=1';
$month = $year = $emp_id =  $department_id = "";


function roundVal($v)
{
    return round((float)$v);
}

$slabs = $obj->executequery("SELECT sm.slab_id,sm.from_salary,sm.to_salary, ss.basic_percent,ss.hra_percent,ss.medical_allow,ss.conve_allow,ss.pf_per,ss.esic_per,ss.pf_emp_per,ss.esic_emp_per FROM salary_slab sm JOIN salary_slab_master ss ON ss.slab_id = sm.slab_id ORDER BY sm.from_salary ASC");

if (isset($_POST['month'], $_POST['year'])) {
    $month     = $obj->test_input($_POST['month']);
    $year      = $obj->test_input($_POST['year']);
    $department_id = (isset($_POST['department_id'])) ? $obj->test_input($_POST['department_id']) : 0;
    $emp_id = (isset($_POST['emp_id'])) ? $obj->test_input($_POST['emp_id']) : 0;
    if ($department_id > 0) {
        $crit .= " and department_id='$department_id'";
    }
    if ($emp_id > 0) {
        $crit .= " and emp_id='$emp_id'";
    }
    $totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $employees = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' $crit");

    if (count($employees) > 0) {
        $count_generated = 0;

        foreach ($employees as $emp) {
            $emp_id   = $emp['emp_id'];
            $depart_id   = $emp['department_id'];
            $is_allow_c_off = $obj->getvalfield("department_master", "c_off_check", "department_id='$depart_id'");
            $is_pf   = $emp['is_pf'];
            $is_esic   = $emp['is_esic'];

            $is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$unitid'");
            // $present_days = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' AND month='$month' AND year='$year'");
            // if ($present_days == 0) continue;
            $where = array(
                'emp_id' => $emp_id,
                'month'  => $month,
                'year'   => $year,
                'unit_id'   => $unitid
            );

            $obj->delete_record('salary_structure', $where);
            $presentSalary = $emp['basic_salary'];

            // print_r($presentSalary);
            // die;

            $total_present = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$month' and year='$year' and attendance_status='Present'");

            $total_half = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$month' and year='$year' and attendance_status='Half Day'");

            $total_att_leave = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$month' and year='$year' and attendance_status='Leave'");

            $overtime_days = $obj->getvalfield("emp_overtime", "no_of_overtime", "emp_id='$emp_id' and month='$month' and year='$year'");

            $loan =  $obj->getvalfield("emi_setting_details", "amount_detail", "emp_id='$emp_id' and month_detail='$month' and year_detail='$year'") ?? 0;
            // if ($loan > 0) {
            //     $is_loan_ded = '1';
            // } else {
            //     $is_loan_ded = '0';
            // }

            $total_working_day = $total_present + ($total_half / 2);

            $setting_type = ($emp['is_esic']  == 1) ? 'ESIC' : 'Non ESIC';

            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year) ?? 31;

            $increment     = 0;
            $revisedSalary = roundVal($presentSalary + $increment);
            $daysWorked = $total_working_day ? $total_working_day : $daysInMonth;

            $monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $total_working_day, $unitid);
            $week_leave = $obj->totalWeeklyLeave($unitid, $total_working_day);
            $holiday  = $obj->getvalfield("holiday_entry", "count(*)", "unit_id='$unitid' AND MONTH(date) = '$month' AND YEAR(date) = '$year'");

            $three_month_leave = $obj->getLeave($emp_id, $month, $year);

            //$total_leave = $monthly_leave + $week_leave + $holiday + $total_att_leave;

            if ($is_all_leave_add == '1') {
                $baseTotal = $total_working_day + $holiday + $week_leave + $monthly_leave + $overtime_days + $total_att_leave;
            } else {
                $baseTotal = $total_working_day + $holiday + $week_leave + $monthly_leave + $overtime_days;
            }

            $shortage = $totalDaysInMonth - $baseTotal;

            $usedCOff = ($shortage > 0) ? min($shortage, $three_month_leave) : 0;
            $remainingCOff = max(0, $three_month_leave - $usedCOff);
            $totalWorkingDays = min($baseTotal + $usedCOff, $totalDaysInMonth);

            $totalAllowedDays = $baseTotal;
            $overtimeDays = max(0, $totalAllowedDays - $daysInMonth);

            if ($overtimeDays > 0  && $is_allow_c_off == '1') {
                $overtime_data = [
                    "emp_id" => $emp_id,
                    "department_id" => $depart_id,
                    "month" => $month,
                    "year" => $year,
                    "basic_salary" => $presentSalary,
                    "total_leave" => $overtimeDays,
                    "remining_leave" => $overtimeDays,
                    "unit_id" => $unitid,
                    "createdby" => $loginid,
                    "ipaddress" => $ipaddress,
                    "sessionid" => $sessionid,
                    "createdate" => date("Y-m-d H:i:s")
                ];
                // print_r($overtime_data);
                // die;
                $where = array(
                    'emp_id' => $emp_id,
                    'month'  => $month,
                    'year'   => $year,
                    'unit_id'   => $unitid
                );

                $obj->delete_record('emp_monthly_leave', $where);
                $obj->insert_record('emp_monthly_leave', $overtime_data);
            }

            $slab = null;
            foreach ($slabs as $s) {
                if (
                    $revisedSalary >= $s['from_salary'] &&
                    ($s['to_salary'] == 0 || $revisedSalary < $s['to_salary'])
                ) {
                    $slab = $s;
                    break;
                }
            }

            if (!$slab) continue;

            if ($is_allow_c_off == '0') {
                // $totalWorkingDays = $baseTotal + $three_month_leave;
                $totalWorkingDays = $baseTotal + $three_month_leave;
                $usedCOff = $three_month_leave;
                $remainingCOff = 0;
            }

            //$totalWorkingDays = 35;
            //   die;
            if ($totalWorkingDays == 0) continue;

            $basicRate = roundVal($revisedSalary * $slab['basic_percent'] / 100);
            $basicDA   = roundVal($basicRate / $totalDaysInMonth * $totalWorkingDays);

            $hra       = roundVal($basicDA * $slab['hra_percent'] / 100);
            $perDaySalary = roundVal($revisedSalary / $totalDaysInMonth * $totalWorkingDays);
            if ($totalWorkingDays > $totalDaysInMonth) {
                $pf_days = $totalDaysInMonth;
            } else {
                $pf_days = $totalWorkingDays;
            }

            // $medical   = roundVal($slab['medical_allow'] / $totalDaysInMonth * $totalWorkingDays);
            // $convey    = roundVal($slab['conve_allow'] / $totalDaysInMonth * $totalWorkingDays);

            $medical   = roundVal($slab['medical_allow'] / $totalDaysInMonth * $pf_days);
            $convey    = roundVal($slab['conve_allow'] / $totalDaysInMonth * $pf_days);


            $special      = $perDaySalary - ($basicDA + $hra + $medical + $convey);

            $totalSalary = $basicDA + $hra + $medical + $convey + $special;
            $pf_emp = '0';
            $pf_employer = '0';
            $esic_emp = '0';
            $esic_employer = '0';

            // if ($totalWorkingDays > $totalDaysInMonth) {
            //     $pf_days = $totalDaysInMonth;
            // } else {
            //     $pf_days = $totalWorkingDays;
            // }

            if ($is_pf == 1) {
                $pf_rate =  15000;
                if ($basicDA <= 15000) {
                    $pf_paid_basic = round($basicRate / $totalDaysInMonth * $pf_days);
                    $pf_emp = round($pf_paid_basic * $slab['pf_per']  / 100);
                    $pf_employer = round($pf_paid_basic * $slab['pf_emp_per']  / 100);
                } else {
                    $pf_paid_basic = 15000;
                    $pf_emp = round($pf_paid_basic * $slab['pf_per']  / 100);
                    $pf_employer = round($pf_paid_basic * $slab['pf_emp_per']  / 100);
                }
            } else {
                $pf_rate = '0';
                $pf_paid_basic = '0';
            }

            if ($is_esic == 1) {
                if ($basicRate <= 21000) {
                    $esic_emp = round($basicDA *  $slab['esic_per'] / 100);
                    $esic_employer = round($basicDA * $slab['esic_emp_per']  / 100);
                }
            }
            $total_net_salary = $totalSalary - $pf_emp - $esic_emp;


            $form_data = [
                "emp_id" => $emp_id,
                "department_id" => $depart_id,
                "month" => $month,
                "year" => $year,
                "basic_salary" => $presentSalary,
                "increment" => $increment,
                "revised_salary" => $revisedSalary,
                // "basic_pf_rate" => $basicRate,
                // "pf_esic_basic" => $basicDA,
                // "is_loan_ded"      => $is_loan_ded,
                "pf_rate"      => $pf_rate,
                "esic_rate"      => $basicRate,
                "pf_paid_basic"      => $pf_paid_basic,
                "esic_paid_basic"      => $basicDA,
                "overtime_days"      => $overtime_days,
                "total_working_days" => $totalWorkingDays,
                "advance_leave"      => $total_att_leave,
                "basic_da" => $basicDA,
                "hra" => $hra,
                "medical" => $medical,
                "conveyance" => $convey,
                "special_allow" => $special,
                "total_salary" => $totalSalary,
                "total_net_salary" => $total_net_salary,
                "pf_emp" => $pf_emp,
                "esic_emp" => $esic_emp,
                "pf_employer" => $pf_employer,
                "esic_employer" => $esic_employer,
                "payment_status" => '1',
                "unit_id" => $unitid,
                "createdby" => $loginid,
                "ipaddress" => $ipaddress,
                "sessionid" => $sessionid,
                "createdate" => date("Y-m-d H:i:s"),

                "paid_holiday"      => $holiday,
                "present_day"      => $total_working_day,
                "weekly_off"      => $week_leave,
                "leave_days"      => $monthly_leave,
                "c_off_leave" => $usedCOff,
                "total_c_off" => $three_month_leave


            ];
            // print_r($form_data);
            // die;
            $obj->insert_record("salary_structure", $form_data);
            if ($usedCOff > 0) {
                $obj->update_record(
                    "employee_master",
                    ['emp_id' => $emp_id],
                    ['opening_balance' => $remainingCOff]
                );
            }

            if ($usedCOff > 0) {

                $baseDate = date('Y-m-d', strtotime("$year-$month-01"));
                $fromDate = date('Y-m-01', strtotime("-3 months", strtotime($baseDate)));
                $toDate   = date('Y-m-t', strtotime("-1 month", strtotime($baseDate)));

                $remainingToDeduct = $usedCOff;

                $rows = $obj->executequery("
        SELECT month_leave_id, remining_leave
        FROM emp_monthly_leave
        WHERE emp_id = '$emp_id'
          AND STR_TO_DATE(CONCAT(year,'-',month,'-01'), '%Y-%m-%d')
              BETWEEN '$fromDate' AND '$toDate'
          AND remining_leave > 0
        ORDER BY month_leave_id ASC
    ");

                foreach ($rows as $row) {
                    if ($remainingToDeduct <= 0) break;

                    $deduct = min($row['remining_leave'], $remainingToDeduct);

                    $obj->update_record(
                        "emp_monthly_leave",
                        ['month_leave_id' => $row['month_leave_id']],
                        ['remining_leave' => $row['remining_leave'] - $deduct]
                    );

                    $remainingToDeduct -= $deduct;
                }
            }

            $count_generated++;
        }
        echo json_encode(['status' => 'success', 'message' => "Salary Generated For $count_generated People"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "No employees found"]);
    }
    die;
}


?>
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

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
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a href="salary_generate_report.php" class="float-end btn btn-primary btn-sm">Salary Report</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="row">
                                        <!-- Employee -->
                                        <div class="col-lg-3 mb-3">
                                            <label for="emp_id" class="form-label">Employee Name<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id" id="emp_id">
                                                <option value="">All</option>
                                                <?php
                                                //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?> <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('emp_id').value =
                                                    '<?= $emp_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="emp_id" class="form-label">Department<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select" name="department_id" id="department_id">
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

                                        <!-- Month -->
                                        <div class="col-lg-3 mb-3">
                                            <label for="month" class="form-label">Month<span class="text-danger fw-bold">*</span></label>
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
                                        <div class="col-lg-3 mb-3">
                                            <label for="year" class="form-label">Year<span class="text-danger fw-bold">*</span></label>
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

                                        <div class="col-lg-4 mt-4">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="Generate" onClick="return checkinputmaster('month,year')">
                                            <a href="<?php echo $pagename ?>" class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>



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
            });
            document.querySelectorAll("#tablesss tbody tr")
                .forEach(row => calculateRow(row));
        });


        $("form").off('submit').on("submit", function(e) {
            e.preventDefault();


            let department_id = $("#department_id").val();
            let emp_id = $("#emp_id").val();
            let month = $("#month").val();
            let year = $("#year").val();

            if (month == "" || year == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Please select Month and Year!'
                });
                return false;
            }

            Swal.fire({
                title: 'Generating salaries...',
                html: 'Please wait while we process the salaries.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '', // same page ajax
                type: 'POST',
                data: {
                    month: month,
                    department_id: department_id,
                    emp_id: emp_id,
                    year: year
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);

                    Swal.close();
                    Swal.fire({
                        icon: response.status == 'success' ? 'success' : 'error',
                        title: response.status == 'success' ? 'Done!' : 'Error!',
                        text: response.message
                    }).then((result) => {
                        if (response.status === 'success') {
                            location = "salary_generate_report.php";
                        }
                    });
                },
                error: function(err) {
                    console.log("error", err);
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.'
                    });
                }
            });
        });
    </script>

</body>

</html>