<?php include("../adminsession.php");
if (isset($_POST['action']) && $_POST['action'] == 'sync_holiday') {

    $month = (int)$_POST['month'];
    $year  = (int)$_POST['year'];
     $crit='';

if (isset($_POST['department_id'])) {
    $department_id = $obj->test_input($_POST['department_id']);
    if ($department_id != '') {
        $crit .= " and e.department_id = '$department_id'";
    }
} else {
    $department_id = ""; 
};

 
    $firstDateOfMonth = date("Y-m-01", strtotime("$year-$month-01"));
    $lastDateOfMonth = date("Y-m-t", strtotime("$year-$month-01")); 
    $fromDate = date("Y-m-d", strtotime("$year-$month-01"));
    $toDate   = date("Y-m-t", strtotime($fromDate));
 
    // Employee List
   $employees = $obj->executequery(" SELECT 
                                        e.emp_id, 
                                        e.department_id, 
                                        e.emp_code, 
                                        e.shift_id,
                                        e.basic_salary
                                    FROM employee_master e 
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
                                        AND e.basic_salary > 42000
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

    $inserted = 0;

    foreach ($employees as $emp) {
        $empId = $emp['emp_id'];
        $holidayData = $obj->getHolidayCountWithSandwichRule2(
            $empId,
            $holidayRows,
            $holidayAttendanceMap
        );

        $holidayCount = $holidayData['total'];

        if ($holidayCount <= 0) {
            continue;
        } 

        $where = ["emp_id"=>$empId,"month"=>$month,"year"=>$year,"leave_type"=>'eoff',"is_opb"=>'2'];
        $obj->delete_record("emp_monthly_leave",$where);

        $form_data =[
            "emp_id" => $empId,
            "department_id" => $emp['department_id'],
            "total_leave" => $holidayCount,
            "remining_leave" => $holidayCount,
            "leave_type" => 'eoff',
            "is_opb"=>'2',
            "month" => $month,
            "year" => $year,
            "unit_id" => $unitid,
            "sessionid" => $sessionid,
            "createdby" => $loginid,
            "ipaddress" => $ipaddress,
            "createdate" => $createdate
        ];
 
        $obj->insert_record("emp_monthly_leave", $form_data);
        $inserted++;
    }

    echo $inserted . " employee holiday balance synced successfully.";
    exit;
}