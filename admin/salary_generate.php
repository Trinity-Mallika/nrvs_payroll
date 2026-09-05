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
$is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$unitid'");
function roundVal($v)
{
    return round((float)$v);
}
 
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

//     $overtimeRows = $obj->executequery("
//     SELECT emp_id, no_of_overtime
//     FROM emp_overtime
//     WHERE month='$month' AND year='$year'
// ");

//     $overtimeMap = [];
//     foreach ($overtimeRows as $r) {
//         $overtimeMap[$r['emp_id']] = $r['no_of_overtime'];
//     }


$fromDate  = date("Y-m-d", strtotime("$year-$month-01"));
$toDate   = date("Y-m-t", strtotime($fromDate));  

$loanAdvanceRows = $obj->executequery("
    SELECT * FROM loan_advance_details
    WHERE month='$month'
    AND year='$year'
    AND status=1
");

$loanMap = []; 
foreach ($loanAdvanceRows as $r) {
    $loanMap[$r['emp_id']][$r['type']][] = $r;
}

$is_locked = $obj->getvalfield("salary_structure","count(*)","payment_status=2 and month='$month' and year='$year' $crit and unit_id='$unitid'");
$apr_from_mgm = $obj->getvalfield("salary_structure","count(*)","apr_from_mgm=1 and month='$month' and year='$year' $crit and unit_id='$unitid'");
if ($apr_from_mgm > 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Some salaries are Approved By Management. Please Unapproved salary records before generating salary again.'
    ]);
    die;
}
if ($is_locked > 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Some salaries are locked. Please unlock the locked salary records before generating salary again.'
    ]);
    die;
}

$obj->delete_record('salary_structure', $deleteWhere);

$form_data1_del = array(
    "primary_id" => 0,
    "flag" => 'Multiple Record Deleted Successfully',
    "activity_type" => 'Deleted',
    "createdby" => $loginid,
    "pagename" => 'salary_generate.php',
    "created_date" => $createdate,
    "created_time" => date('H:i:s'),
    "unit_id" => $unitid,
    'ipaddress' => $ipaddress,
    "sessionid" => $sessionid
);
$logactivity = $obj->insert_record("logactivity_master", $form_data1_del);

 
$firstDateOfMonth = date("Y-m-01", strtotime("$year-$month-01"));
$lastDateOfMonth = date("Y-m-t", strtotime("$year-$month-01")); 
$employees = $obj->executequery("
    SELECT 
        e.*
    FROM employee_master e
    /* Last active/inactive status till selected month */
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
        $crit
        AND (
            e.resign_status != '1'
            OR (
                e.resign_status = '1'
                AND e.last_working_date >='$firstDateOfMonth'
            )
        )
        /* Show employee if:
           1. No record in emp_active_status => Active
           2. Last record is active
        */
        AND (
            eas.active_id IS NULL
            OR eas.is_active = '1'
        )  
       
    GROUP BY e.emp_id
    ORDER BY e.first_name ASC
"); 
 
    if(count($employees) > 0){
        $empIds = array_column($employees, 'emp_id');
        $empIdsStr = implode(",", $empIds);


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
    AND date BETWEEN '$fromDate' AND '$toDate'");

    $holidays = [];
    foreach ($holidayRows as $h) {
        $holidays[$h['date']] = true;
    }



        $summaryRows = $obj->executequery("
        SELECT emp_id,
            SUM(attendance_status='Present') AS present,
            SUM(attendance_status='Half Day') AS half,
            SUM(attendance_status IN('Weekly Leave','Earning Leave','C Off','Leave','Public Holiday','National Holiday','Religion Holiday','Seasonal Holiday')) AS paid_leave_day,

            SUM(attendance_status IN('National Holiday','Religion Holiday','Seasonal Holiday')) AS tot_paid_holiday,

            SUM(attendance_status IN('Half Weekly Leave','Half Earning Leave','Half C Off','Half Leave')) AS tot_half_day,
            SUM(attendance_status IN('Weekly Leave','Earning Leave','C Off','Leave','Extra Off','Public Holiday','National Holiday','Religion Holiday','Seasonal Holiday')) AS paid_leave,
            SUM(attendance_status IN('Half Weekly Leave','Half Earning Leave','Half C Off','Half Leave','Half Extra Off')) AS tot_half,
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
        WHERE emp_id IN ($empIdsStr) AND month='$month' AND year='$year' AND unit_id='$unitid' GROUP BY emp_id
        ");

        $summary = [];

        foreach($summaryRows as $row){
            $summary[$row['emp_id']] = $row;
        }
 
        $earningLeaveRows = $obj->earningLeaveRows($sessionid,$month,$year);
        $usedEarnMap = [];

        foreach($earningLeaveRows as $r){
            $usedEarnMap[$r['emp_id']] = $r['used_leave'];
        }
  
        $earningUploadRows=$obj->earningUploadRows($sessionid,$month,$year);
        $earningUploadMap = [];

        foreach($earningUploadRows as $r){
            $earningUploadMap[$r['emp_id']] = $r['total_leave'];
        }

        $departments = $obj->executequery("SELECT * FROM department_master");

        $departmentMap = [];

        foreach($departments as $d){
            $departmentMap[$d['department_id']] = $d;
        }


        $deductionRows = $obj->executequery("
            SELECT
                emp_id, 
                IFNULL(SUM(lpg_ded + shoes_ded + other),0) AS total_other_deduction
            FROM emp_deduction
            WHERE month='$month'
            AND year='$year'
            AND emp_id IN ($empIdsStr)
            GROUP BY emp_id
        ");

        $deductionMap = [];
        foreach ($deductionRows as $row) {
            $deductionMap[$row['emp_id']] = $row;
        }

        $additionRow = $obj->executequery("
            SELECT
                emp_id, 
                IFNULL(SUM(basic_arear + other_reimbursement + increment_arear+bonus+leave_encasement+notice_period),0) AS total_additional
            FROM additional_payment
            WHERE month='$month'
            AND year='$year'
            AND emp_id IN ($empIdsStr)
            GROUP BY emp_id
        ");

        $additionMap = [];
        foreach ($additionRow as $row1) {
            $additionMap[$row1['emp_id']] = $row1;
        }
    }

    $salaryRows = [];
    $overtimeLeaveRows = [];
    $earningLeaveRows  = [];
    $processedEmpIds = [];
    if (count($employees) > 0) {
        $count_generated = 0;

        foreach ($employees as $emp) {
            $emp_id   = $emp['emp_id'];
            $processedEmpIds[$emp_id] = true;
            $depart_id   = $emp['department_id'];
            $allow_weekly_off = $emp['allow_weekly_off'];
            $is_pf   = $emp['is_pf'];
            $is_esic   = $emp['is_esic'];
            $opening_balance_save = $emp['used_opening_balance'];
            $opening_balance_date = $emp['opening_date']; 
            $is_perform_incen = $emp['is_perform_incen'];
            $depart_data = $departmentMap[$depart_id] ?? []; 
            $is_allow_c_off = $depart_data['c_off_check'] ?? '0';
            $allow_earn_leave_carry = $depart_data['earn_leave_check'] ?? '';
            $date_of_joining = $emp['date_of_joining'] ?? '';

            $loan_amt = 0;
            $advance_amt = 0;

            $loanRecords = $loanMap[$emp_id]['Loan'] ?? [];
            $advanceRecords = $loanMap[$emp_id]['Advance'] ?? [];

            foreach ($loanRecords as $loan) {
                $loan_amt += $loan['amount'];
            }

            foreach ($advanceRecords as $advance) {
                $advance_amt += $advance['amount'];
            }
            $otherDeduction = isset($deductionMap[$emp_id]) ? $deductionMap[$emp_id]['total_other_deduction'] : 0;
            $additional_payment = isset($additionMap[$emp_id]) ? $additionMap[$emp_id]['total_additional'] : 0;
            
           

            $presentSalary = $emp['basic_salary'];
            $a = $summary[$emp_id] ?? []; 
            $total_present1 = $a['present'] ?? 0;
            $total_half1    = $a['half'] ?? 0;
            $total_present  = ($a['present'] ?? 0) + ($a['paid_leave'] ?? 0);
            $total_half     = ($a['tot_half'] ?? 0) + ($a['half'] ?? 0);
            $half_extra_off = $a['half_extra_off'] ?? 0;
            $full_extra_off = $a['full_extra_off'] ?? 0;
            $total_opening_leave = $a['total_opening_leave'] ?? 0;
            $tot_paid_holiday = $a['tot_paid_holiday'] ?? 0;

            $total_present_day  = ($a['present'] ?? 0) + ($a['paid_leave_day'] ?? 0);
            $total_half_day     = ($a['tot_half_day'] ?? 0) + ($a['half'] ?? 0);

            $used_extra_off = $full_extra_off+($half_extra_off/2);
            $present_day_total = $total_present_day +($total_half_day/2);

            $holidayData = $obj->getHolidayCountWithSandwichRule2($emp_id,$holidayRows,$holidayAttendanceMap);
            $holiday = $holidayData['total']; 

            $real_total_working_day = $total_present1 + ($total_half1 / 2)+$holiday+$tot_paid_holiday; 

            $total_working_day = $total_present + ($total_half / 2); 
         
            $setting_type = ($emp['is_esic']  == 1) ? 'ESIC' : 'Non ESIC';

            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year) ?? 31;

            $increment     = 0;
            $revisedSalary = roundVal($presentSalary + $increment);

            $daysWorked = $total_working_day ? $total_working_day : $totalDaysInMonth;

            $week_leave = $obj->totalWeeklyLeave($unitid, $real_total_working_day, $emp_id,$month, $year);
            $earn_leave_present =  $real_total_working_day+$week_leave;
            $monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $earn_leave_present, $unitid);
         
            $total_earning_leave = ($earningUploadMap[$emp_id] ?? 0) - ($usedEarnMap[$emp_id] ?? 0); 
            
          


            $result = $obj->calculateWorkingDays([
                'daysInMonth' => $totalDaysInMonth,
                'present'     => $total_working_day,
                'holiday'     => $holiday,
                //'advance'     => $total_att_leave,
                'weekly'      => $week_leave,
                'monthly'     => $monthly_leave,
                //'c_off'       => $three_month_leave,
                'used_extra_off'    =>0,
                'allow_c_off' => $is_allow_c_off,
                'add_all_leave' => $is_all_leave_add,
                'allow_earn_leave_carry' => $allow_earn_leave_carry, 
                'joining_date' => $date_of_joining, 
                'month' => $month, 
                'year' => $year, 
                
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

            if ($overtimeDays > 0  && $is_allow_c_off == '1' && $is_perform_incen==0) {
                $overtimeLeaveRows[] = $overtime_data;
            }

            if ($remining_earn_leave > 0  && $allow_earn_leave_carry == '1') {
                $earningLeaveRows[] = $remining_earn_leave_data; 
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
            $total_pay_sal_after_ded =  $total_net_salary+$additional_payment - $loan_amt - $advance_amt - $otherDeduction;

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
                //"overtime_days"      => $overtime_days,
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
                "present_day"      => $present_day_total,
                "weekly_off"      => $usedWeekly,
                "leave_days"      => $usedMonthly,
                "total_week_leave"  => $week_leave,
                "total_earn_leave"  => $monthly_leave,
                "pre_earn_leave"  => $total_earning_leave,
                "c_off_leave" => $usedCOff,
                "used_extra_off" => $used_extra_off,
                "total_opening_leave" => $total_opening_leave,
                //"total_c_off" => $three_month_leave,
                "other_deduction" => $otherDeduction,
                "additional_payment" => $additional_payment,
                "loan_amt" => $loan_amt,
                "total_pay_sal_after_ded" => $total_pay_sal_after_ded,
                "advance_amt" => $advance_amt
            ];
            // print_r($form_data);
            // die; 
            foreach ($loanRecords as $loan) {
                $obj->update_record(
                    "loan_advance_details",
                    ['loan_details_id' => $loan['loan_details_id'] , 'type'=>'Loan'],
                    [
                        'is_paid'   => 1,
                        'paid_date' => $createdate
                    ]
                );
            }

            foreach ($advanceRecords as $advance) {
                $obj->update_record(
                    "loan_advance_details",
                    [ 'loan_details_id' => $advance['loan_details_id'] , 'type'=>'Advance' ],
                    ['is_paid'   => 1,
                        'paid_date' => $createdate
                    ]
                );
            }

            $salaryRows[] = $form_data;
            //$obj->insert_record("salary_structure", $form_data);

            $count_generated++;
        }

        if (!empty($processedEmpIds)) {
            $empIdStr = implode(',', array_keys($processedEmpIds));
            $obj->bulk_delete('emp_monthly_leave', [
                'emp_id'  => array_keys($processedEmpIds),
                'month'   => $month,
                'year'    => $year,
                'is_opb'   => 0,
                'leave_type'   => 'weekly',
                'unit_id' => $unitid
            ]);
            $obj->bulk_delete('emp_monthly_leave', [
                'emp_id'  => array_keys($processedEmpIds),
                'month'   => $month,
                'year'    => $year,
                'is_opb'   => 0,
                'leave_type'   => 'earning',
                'unit_id' => $unitid
            ]); 
        } 

        if (!empty($overtimeLeaveRows)) {
            foreach (array_chunk($overtimeLeaveRows, 500) as $chunk) {
                $obj->bulk_insert('emp_monthly_leave', $chunk);
            }
        }
        if (!empty($earningLeaveRows)) {
            foreach (array_chunk($earningLeaveRows, 500) as $chunk) {
                $obj->bulk_insert('emp_monthly_leave', $chunk);
            }
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
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> <a
                                                    href="salary_generate_report.php"
                                                    class="float-end btn btn-primary btn-sm">Salary Report</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="row">
                                        <!-- Employee -->

                                        <div class="col-lg-3 mb-3">
                                            <label for="emp_id" class="form-label">Department<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="department_id" id="department_id">
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
                                        <div class="col-lg-3 mb-3">
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
                                        <?php $chkadd = $obj->check_addBtn($pagename, $loginid);
                                        if ($chkadd == 1) {  ?>
                                        <div class="col-lg-4 mt-4">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn"
                                                value="Generate" onClick="return checkinputmaster('month,year')">
                                            <a href="<?php echo $pagename ?>"
                                                class="btn btn-sm btn-danger add-btn">Reset</a>
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
                        //location = "salary_generate_report.php";
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