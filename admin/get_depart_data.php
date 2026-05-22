<?php include("../adminsession.php");
$emp_id =$obj->test_input($_POST['emp_idd']);
$department_id = $obj->getvalfield("employee_master", "department_id", "emp_id='$emp_id'");
$options = "<option value=''>Please Select</option>";
$selected = "";
if ($emp_id != "" || $emp_id > 0) {

    $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_name asc");

    foreach ($res as $row) {
        $selected = ($department_id == $row['department_id']) ? 'selected' : '';
        $options .= "<option value='" . $row['department_id'] . "' $selected>" . $row['department_name'] . "  </option>";
    }
}

echo $options;
 
 