 dashboard

 
$year = date('Y');
$month = date('n');

$firstDateOfMonth = date("Y-m-01", strtotime("$year-$month-01"));
$lastDateOfMonth = date("Y-m-t", strtotime("$year-$month-01")); 
// $employees = $obj->executequery("
//         SELECT 
//             e.emp_id 
//         FROM employee_master e
      
//         LEFT JOIN (
//             SELECT a1.*
//             FROM emp_active_status a1
//             INNER JOIN (
//                 SELECT 
//                     emp_id,
//                     MAX(active_id) AS last_id
//                 FROM emp_active_status
//                 WHERE (
//                         YEAR(last_inactive_date) < '$year'
//                         OR (
//                             YEAR(last_inactive_date) = '$year'
//                             AND MONTH(last_inactive_date) <= '$month'
//                         )
//                     )
//                 GROUP BY emp_id
//             ) a2 
//             ON a1.active_id = a2.last_id
//         ) eas 
//             ON eas.emp_id = e.emp_id

//         WHERE e.unit_id='$unitid'
//             AND e.is_active = '1'
//             AND e.date_of_joining <= '$lastDateOfMonth'

//             AND (
//                 e.resign_status != '1' 
//                 OR (
//                     e.resign_status = '1' 
//                     AND e.last_working_date >= '$firstDateOfMonth'
//                 )
//             ) 
//             AND (
//                 eas.active_id IS NULL
//                 OR eas.is_active = '1'
//             )
//         GROUP BY e.emp_id
//         ORDER BY e.emp_code"); 

//$total_active_emp = count($employees);


employee report 

    $smonth = date('n');
    $syear = date('Y');
    $firstDateOfMonth = date("Y-m-01", strtotime("$syear-$smonth-01"));
    $lastDateOfMonth = date("Y-m-t", strtotime("$syear-$smonth-01")); 

     // if($resign_status == 1){
                        //     $sql ="
                        //         SELECT 
                        //             em.*, 
                        //             ebd.is_active,
                        //             erpt.reporting_manager,
                        //             dm.department_name,
                        //             dem.designation AS current_designation,
                        //             dem2.designation AS previous_designation,
                        //             erpt.first_name as reporting_manager_name,
                        //             gm.grade_name,um.unit_name
                        //         FROM employee_master em
                        //         LEFT JOIN department_master dm 
                        //         ON em.department_id = dm.department_id
                        //         LEFT JOIN unit_master um 
                        //         ON em.unit_id = um.unit_id
                        //         LEFT JOIN designation_master dem 
                        //         ON em.designation_id = dem.designation_id
                        //         LEFT JOIN designation_master dem2 
                        //         ON em.employer_designation_id = dem2.designation_id
                        //         LEFT JOIN grade_master gm 
                        //         ON em.grade_id = gm.grade_id
                        //         LEFT JOIN emp_bank_details ebd 
                        //         ON em.emp_id = ebd.emp_id and ebd.is_active = 1
                        //         LEFT JOIN employee_master erpt 
                        //         ON em.reporting_manager = erpt.emp_id
                            
                        //         LEFT JOIN (
                        //             SELECT a1.*
                        //             FROM emp_active_status a1
                        //             INNER JOIN (
                        //                 SELECT 
                        //                     emp_id,
                        //                     MAX(active_id) AS last_id
                        //                 FROM emp_active_status
                        //                 WHERE (
                        //                         YEAR(last_inactive_date) < '$syear'
                        //                         OR (
                        //                             YEAR(last_inactive_date) = '$syear'
                        //                             AND MONTH(last_inactive_date) <= '$smonth'
                        //                         )
                        //                     )
                        //                 GROUP BY emp_id
                        //             ) a2 
                        //             ON a1.active_id = a2.last_id
                        //         ) eas 
                        //             ON eas.emp_id = em.emp_id

                        //    where em.unit_id = '$unitid'  $crit  And em.is_active = '1'  
                        //         AND em.date_of_joining <= '$lastDateOfMonth'
                        //             AND (
                        //                 em.resign_status != '1' 
                        //                 OR (
                        //                     em.resign_status = '1' 
                        //                     AND em.last_working_date >= '$firstDateOfMonth'
                        //                 )
                        //             ) 
                        //             AND (
                        //                 eas.active_id IS NULL
                        //                 OR eas.is_active = '1'
                        //             )  
                        //         GROUP BY em.emp_id
                        //         ORDER BY em.emp_code"; 
                        // }else