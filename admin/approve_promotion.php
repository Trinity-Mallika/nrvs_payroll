<?php include("../adminsession.php");
if (isset($_POST['promotion_idd'])) {
    $emp_promotion_id = $obj->test_input($_POST['promotion_idd']);
    $emp_id = $obj->test_input($_POST['emp_id']);
    $department_id = $obj->test_input($_POST['department_id']);
    $designation_id = $obj->test_input($_POST['designation_id']);
    $basic_salary = $obj->test_input($_POST['basic_salary']);
    $effected_month   = $obj->test_input($_POST['effected_month']);
    $effected_year    = $obj->test_input($_POST['effected_year']);
    $action = $_POST['action'];

    if ($emp_id != '' && $action != '') {

        if ($action == 'approve') {

            $obj->update_record("emp_promotion", array("emp_promotion_id" => $emp_promotion_id), array("status" => 1, "updatedby" => $loginid, 'lastupdated' => $createdate));

            $current_month = date('n');
            $current_year  = date('Y');
            if ($effected_month == $current_month && $effected_year == $current_year) {
                $obj->update_record(
                    "employee_master",
                    array("emp_id" => $emp_id, "unit_id" => $unitid),
                    array("department_id" => $department_id, "designation_id" => $designation_id, "basic_salary" => $basic_salary)
                );
            }
        } else if ($action == 'reject') {

            //   only update status = 2
            $obj->update_record(
                "emp_promotion",
                array("emp_promotion_id" => $emp_promotion_id),
                array("status" => 2)
            );
        }

        $form_data1 = array(
            "primary_id" => $emp_promotion_id,
            "flag" => "Employee Promotion " . $action,
            "activity_type" => 'Inserted',
            "createdby" => $loginid,
            "ipaddress" => $ipaddress,
            "unit_id" => $unitid,
            "created_date" => $createdate,
            "created_time" => date("H:i:s"),
            "sessionid" => $sessionid
        );
        $logactivity = $obj->insert_record("logactivity_master", $form_data1);
        echo 'success';
    } else {
        echo 'error';
    }
}