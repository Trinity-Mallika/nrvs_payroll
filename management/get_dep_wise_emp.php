<?php include("../adminsession.php");
$department_id =$obj->test_input($_POST['department_id']); 
$emp_id =$obj->test_input($_POST['emp_id']); 
$options = "<option value=''>All</option>";
$selected = "";
if ($department_id != "" || $department_id > 0) {
   $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) AND department_id='$department_id' ORDER BY first_name ASC");
    foreach ($res as $row) {
        $selected = ($emp_id == $row['emp_id']) ? 'selected' : '';
        $options .= "<option value='" . $row['emp_id'] . "' $selected>" . $row['emp_code'] . " - ".ucfirst($row['first_name'] ?? '')."  </option>";
    }
}

echo $options;
 
 