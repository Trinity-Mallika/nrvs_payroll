<?php include("../adminsession.php");
ini_set('max_execution_time', 600); // 10 minutes
set_time_limit(600);
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
$month = $month = (int)date('m');
$year = date('Y');
$emp_id =  $department_id = "";
$unit_pf_rate = $obj->getvalfield("unit_master", "pf_rate", "unit_id='$unitid'");
$unit_esic_rate = $obj->getvalfield("unit_master", "esic_rate", "unit_id='$unitid'");
function roundVal($v)
{
    return round((float)$v);
}
$is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$unitid'");
$slabs = $obj->executequery("SELECT sm.slab_id,sm.from_salary,sm.to_salary, ss.basic_percent,ss.hra_percent,ss.medical_allow,ss.conve_allow,ss.pf_per,ss.esic_per,ss.pf_emp_per,ss.esic_emp_per FROM salary_slab sm JOIN salary_slab_master ss ON ss.slab_id = sm.slab_id ORDER BY sm.from_salary ASC");

if (isset($_POST['month'], $_POST['year'])) {
    $month     = $obj->test_input($_POST['month']);
    $year      = $obj->test_input($_POST['year']);
    if ($month == 1) {
        $prev_month = 12;
        $prev_year  = $year - 1;
    } else {
        $prev_month = $month - 1;
        $prev_year  = $year;
    }



    $department_id = (isset($_POST['department_id'])) ? $obj->test_input($_POST['department_id']) : 0;
    $deleteWhere = [
        'month'   => $month,
        'year'    => $year,
        'unit_id' => $unitid
    ];
    $emp_id = (isset($_POST['emp_id'])) ? $obj->test_input($_POST['emp_id']) : 0;
    if ($department_id > 0) {
        $crit .= " and department_id='$department_id'";
        $deleteWhere['department_id'] = $department_id;
    }
    if ($emp_id > 0) {
        $crit .= " and emp_id='$emp_id'";
    }
    $totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $overtimeRows = $obj->executequery("
    SELECT emp_id, no_of_overtime
    FROM emp_overtime
    WHERE month='$month' AND year='$year'
");

    $overtimeMap = [];
    foreach ($overtimeRows as $r) {
        $overtimeMap[$r['emp_id']] = $r['no_of_overtime'];
    }


    $loanAdvanceRows = $obj->executequery("
    SELECT * FROM loan_advance_details
    WHERE month='$month'
    AND year='$year'
    AND status=1
");

    $loanMap = [];
    foreach ($loanAdvanceRows as $r) {
        $loanMap[$r['emp_id']][$r['type']] = $r;
    }

    // $where = array(
    //     'month'  => $month,
    //     'department_id'  => $department_id,
    //     'year'   => $year,
    //     'unit_id'   => $unitid
    // );

    $obj->delete_record('salary_structure', $deleteWhere);

    //$employees = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' $crit");
    $employees = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' $crit AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");

    $salaryRows = [];

    if (count($employees) > 0) {
        $count_generated = 0;

        foreach ($employees as $emp) {
            $emp_id   = $emp['emp_id'];
            $depart_id   = $emp['department_id'];
            $allow_weekly_off = $emp['allow_weekly_off'];
            $is_pf   = $emp['is_pf'];
            $is_esic   = $emp['is_esic'];
            $opening_balance_save = $emp['used_opening_balance'];
            $opening_balance_date = $emp['opening_date'];

            $depart_data  = $obj->select_record('department_master', array('department_id' => $depart_id));
            $is_allow_c_off = $depart_data['c_off_check'] ?? '0';
 

            $loan_amt = $loanMap[$emp_id]['Loan']['amount'] ?? 0;
            $loan_details_id = $loanMap[$emp_id]['Loan']['loan_details_id'] ?? 0;
            $advance_amt = $loanMap[$emp_id]['Advance']['amount'] ?? 0;
            $advance_details_id = $loanMap[$emp_id]['Advance']['loan_details_id'] ?? 0;


            // $allow_weekly_off = $depart_data['allow_weekly_off'] ?? '0';


            // $present_days = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' AND month='$month' AND year='$year'");
            // if ($present_days == 0) continue;

            $presentSalary = $emp['basic_salary'];

            $attendance = $obj->executequery("
    SELECT
        SUM(attendance_status='Present') AS present,
        SUM(attendance_status='Half Day') AS half,
        SUM(attendance_status IN('Weekly Leave','Earning Leave','C Off','Leave')) AS paid_leave,
        SUM(attendance_status IN('Half Weekly Leave','Half Earning Leave','Half C Off','Half Leave')) AS tot_half,
        SUM(attendance_status ='Half Extra Off') AS half_extra_off,
        SUM(attendance_status ='Extra Off') AS full_extra_off,
        SUM(
            CASE 
                WHEN attendance_status = 'Leave' THEN 1
                WHEN attendance_status = 'Half Leave' THEN 0.5
                ELSE 0
            END
        ) AS total_opening_leave
    FROM attendance_entry
    WHERE emp_id='$emp_id' AND month='$month' AND year='$year' AND unit_id='$unitid'
");

            $a = $attendance[0] ?? [];

            $total_present1 = $a['present'] ?? 0;
            $total_half1    = $a['half'] ?? 0;
            $total_present  = ($a['present'] ?? 0) + ($a['paid_leave'] ?? 0);
            $total_half     = ($a['tot_half'] ?? 0) + ($a['half'] ?? 0);
            $half_extra_off = $a['full_extra_off'] ?? 0;
            $full_extra_off = $a['full_extra_off'] ?? 0;
            $total_opening_leave = $a['total_opening_leave'] ?? 0;

            $used_extra_off = $full_extra_off+($half_extra_off/2);

            // $overtime_days = $obj->getvalfield("emp_overtime", "no_of_overtime", "emp_id='$emp_id' and month='$month' and year='$year'");
            $overtime_days = $overtimeMap[$emp_id] ?? 0;

            $real_total_working_day = $total_present1 + ($total_half1 / 2);

            $total_working_day = $total_present + ($total_half / 2);
            $setting_type = ($emp['is_esic']  == 1) ? 'ESIC' : 'Non ESIC';

            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year) ?? 31;

            $increment     = 0;
            $revisedSalary = roundVal($presentSalary + $increment);
            $daysWorked = $total_working_day ? $total_working_day : $totalDaysInMonth;
            $week_leave = $obj->totalWeeklyLeave($unitid, $real_total_working_day, $emp_id,$month, $year);
            $earn_leave_present =  $real_total_working_day+$week_leave;
            $monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $earn_leave_present, $unitid);
            

            $holidayData = $obj->getHolidayCountWithSandwichRule(
                $emp_id,
                $unitid,
                $month,
                $year
            );
            $holiday     = $holidayData['total'] ?? 0;
           // $three_month_leave = $obj->getLeave($emp_id, $month, $year);

            $total_earning_leave = $obj->getEarningLeave($emp_id, $sessionid);
            $result = $obj->calculateWorkingDays([
                'daysInMonth' => $totalDaysInMonth,
                'present'     => $total_working_day,
                'holiday'     => $holiday,
                //'advance'     => $total_att_leave,
                'weekly'      => $week_leave,
                'monthly'     => $monthly_leave,
                //'c_off'       => $three_month_leave,
                'used_extra_off'    => $used_extra_off,
                'allow_c_off' => $is_allow_c_off,
                'add_all_leave' => $is_all_leave_add
            ]);

            $totalWorkingDays = $result['total_working_days'];
            $usedCOff         = $result['used_c_off'];
            $usedWeekly       = $result['used_weekly'];
            $usedMonthly      = $result['used_monthly'];
          

            $overtimeDays = $week_leave - $usedWeekly;
            $remining_earn_leave = $monthly_leave - $usedMonthly;

            $overtime_data = [
                "emp_id" => $emp_id,
                "department_id" => $depart_id,
                "month" => $month,
                "year" => $year,
                "basic_salary" => $presentSalary,
                "total_leave" => $overtimeDays,
                "remining_leave" => $overtimeDays,
                "leave_type" => 'weekly',
                "unit_id" => $unitid,
                "createdby" => $loginid,
                "ipaddress" => $ipaddress,
                "sessionid" => $sessionid,
                "createdate" => date("Y-m-d H:i:s")
            ];
            //$previous_remining_leave = $previous_earn_leave + $remining_earn_leave;
            $remining_earn_leave_data = [
                "emp_id" => $emp_id,
                "department_id" => $depart_id,
                "month" => $month,
                "year" => $year,
                "basic_salary" => $presentSalary,
                "total_leave" => $remining_earn_leave,
                "remining_leave" => $remining_earn_leave,
                "leave_type" => 'earning',
                "unit_id" => $unitid,
                "createdby" => $loginid,
                "ipaddress" => $ipaddress,
                "sessionid" => $sessionid,
                "createdate" => date("Y-m-d H:i:s")
            ];

            if ($overtimeDays > 0  && $is_allow_c_off == '1') {
                $where = array(
                    'emp_id' => $emp_id,
                    'month'  => $month,
                    'year'   => $year,
                    'leave_type'   => 'weekly',
                    'unit_id'   => $unitid
                );
                $obj->delete_record('emp_monthly_leave', $where);
                $obj->insert_record('emp_monthly_leave', $overtime_data);
            }

            if ($remining_earn_leave > 0  && $is_allow_c_off == '1') {
                $where = array(
                    'emp_id' => $emp_id,
                    'month'  => $month,
                    'year'   => $year,
                    'leave_type'   => 'earning',
                    'unit_id'   => $unitid
                );
                $obj->delete_record('emp_monthly_leave', $where);
                $obj->insert_record('emp_monthly_leave', $remining_earn_leave_data);
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



            if ($is_pf == 1) {
                $pf_rate =  $unit_pf_rate;
                if ($basicDA <= $unit_pf_rate) {
                    $pf_paid_basic = round($basicRate / $totalDaysInMonth * $pf_days);
                    $pf_emp = round($pf_paid_basic * $slab['pf_per']  / 100);
                    $pf_employer = round($pf_paid_basic * $slab['pf_emp_per']  / 100);
                } else {
                    $pf_paid_basic = $unit_pf_rate;
                    $pf_emp = round($pf_paid_basic * $slab['pf_per']  / 100);
                    $pf_employer = round($pf_paid_basic * $slab['pf_emp_per']  / 100);
                }
            } else {
                if ($presentSalary <= $unit_pf_rate) {
                    $pf_rate =  $unit_pf_rate;
                    if ($basicDA <= $unit_pf_rate) {
                        $pf_paid_basic = round($basicRate / $totalDaysInMonth * $pf_days);
                        $pf_emp = round($pf_paid_basic * $slab['pf_per']  / 100);
                        $pf_employer = round($pf_paid_basic * $slab['pf_emp_per']  / 100);
                    } else {
                        $pf_paid_basic = $unit_pf_rate;
                        $pf_emp = round($pf_paid_basic * $slab['pf_per']  / 100);
                        $pf_employer = round($pf_paid_basic * $slab['pf_emp_per']  / 100);
                    }
                } else {
                    $pf_rate = '0';
                    $pf_paid_basic = '0';
                }
            }

            $esic_paid_basic_val = roundVal($basicRate / $totalDaysInMonth * $pf_days);

            if ($is_esic == 1) {
                // if ($basicRate <= $unit_esic_rate) {
                $esic_emp = ceil($esic_paid_basic_val *  $slab['esic_per'] / 100);
                $esic_employer = round($esic_paid_basic_val * $slab['esic_emp_per']  / 100);
                // }
            } else {
                //  if ($presentSalary <= $unit_esic_rate) {
                if ($basicRate <= $unit_esic_rate) {
                    $esic_emp = ceil($esic_paid_basic_val *  $slab['esic_per'] / 100);
                    $esic_employer = round($esic_paid_basic_val * $slab['esic_emp_per']  / 100);
                }
                // }
            }
            $total_net_salary = $totalSalary - $pf_emp - $esic_emp;
            $total_pay_sal_after_ded =  $total_net_salary - $loan_amt - $advance_amt;

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
                "esic_paid_basic"      => $esic_paid_basic_val,
                "overtime_days"      => $overtime_days,
                "total_working_days" => $totalWorkingDays,
                
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
                "weekly_off"      => $usedWeekly,
                "leave_days"      => $usedMonthly,
                "total_week_leave"  => $week_leave,
                "total_earn_leave"  => $monthly_leave,
                "pre_earn_leave"  => $total_earning_leave,
                "c_off_leave" => $usedCOff,
                "used_extra_off" => $used_extra_off,
                "total_opening_leave" => $total_opening_leave,
                //"total_c_off" => $three_month_leave,
                "loan_amt" => $loan_amt,
                "total_pay_sal_after_ded" => $total_pay_sal_after_ded,
                "advance_amt" => $advance_amt
            ];
            // print_r($form_data);
            // die;
            $obj->update_record("loan_advance_details", ['loan_details_id' => $loan_details_id], ['is_paid' => 1, 'paid_date' => $createdate]);
            $obj->update_record("loan_advance_details", ['loan_details_id' => $advance_details_id], ['is_paid' => 1, 'paid_date' => $createdate]);

            $salaryRows[] = $form_data;
            //$obj->insert_record("salary_structure", $form_data);

            

            $count_generated++;
        }
        foreach (array_chunk($salaryRows, 500) as $chunk) {
            $bulk_lastid = $obj->bulk_insert('salary_structure', $chunk);
            $form_data1 = array(
                "primary_id" => $bulk_lastid,
                "flag" => $title,
                "activity_type" => 'Inserted',
                "createdby" => $loginid,
                "pagename" => $pagename,
                "created_date" => $createdate,
                "created_time" => date('H:i:s'),
                "unit_id" => $unitid,
                'ipaddress' => $ipaddress,
                "sessionid" => $sessionid
            );
            $logactivity = $obj->insert_record("logactivity_master", $form_data1);
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
                                            <label for="emp_id" class="form-label">Department<span class="text-danger fw-bold"> </span></label>
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
                                        <?php $chkadd = $obj->check_addBtn($pagename, $loginid);
                                        if ($chkadd == 1) {  ?>
                                            <div class="col-lg-4 mt-4">
                                                <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="Generate" onClick="return checkinputmaster('month,year')">
                                                <a href="<?php echo $pagename ?>" class="btn btn-sm btn-danger add-btn">Reset</a>
                                            </div>
                                        <?php } ?>
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