<?php
include("appsession.php"); 
$id     = $_POST['id'];
$type   = $_POST['type'];
$status = $_POST['status'];

if ($type == 'all') {
    // GET ALL PENDING LEAVES
    $leaveData = $obj->executequery("
        SELECT lad.*, 
               la.emp_id,
               em.department_id,
               em.shift_id,
               em.unit_id,
               em.basic_salary
        FROM leave_apply_detail lad

        INNER JOIN on_duty_master la 
            ON la.on_duty_id = lad.on_duty_id

        INNER JOIN employee_master em 
            ON em.emp_id = la.emp_id

        WHERE lad.on_duty_id = '$id'
        AND lad.status = '0'
    ");

    foreach ($leaveData as $row) {
        $leave_details_id = $row['leave_details_id'];
        if ($row['is_apr_hod'] == 1) {
                continue;
        }
        $obj->update_record(
            "leave_apply_detail",
            ['leave_details_id' => $leave_details_id],
            [
                //'status'       => $status, 
                'is_apr_hod'   =>$status,
                'hod_apr_id'    => $emp_id,
                'lastupdated_hod'  => $createdate,
                //'approve_date' => $createdate,
                //'approve_by'   => $emp_id
            ]
        );  
    }

    echo "success";

} elseif ($type == 'single') {

    // GET SINGLE LEAVE
    $leaveData = $obj->executequery("
        SELECT lad.*, 
               la.emp_id,
               em.department_id,
               em.shift_id,
               em.unit_id,
               em.basic_salary
        FROM leave_apply_detail lad

        INNER JOIN on_duty_master la 
            ON la.on_duty_id = lad.on_duty_id

        INNER JOIN employee_master em 
            ON em.emp_id = la.emp_id

        WHERE lad.leave_details_id = '$id'
        LIMIT 1
    ");

    if (!empty($leaveData)) {

        $row = $leaveData[0];

        // UPDATE STATUS
        $obj->update_record(
            "leave_apply_detail",
            ['leave_details_id' => $id],
            [
                //'status'       => $status,
                'is_apr_hod'   => $status,
                'hod_apr_id'    => $emp_id,
                'lastupdated_hod'  => $createdate,
                //'approve_date' => $createdate,
                //'approve_by'   => $emp_id
            ]
        ); 
        echo "success";

    } else {

        echo "error";
    }
}
?>