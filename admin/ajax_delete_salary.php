<?php
include("../adminsession.php");

$records = $_POST['records'] ?? [];

if (!empty($records)) {
    foreach ($records as $rec) {
        $id     = $rec['id'];
        $month  = $rec['month'];
        $year   = $rec['year'];
        $emp_id = $rec['emp_id'];
        $obj->delete_record("salary_structure", array("salary_struc_id" => $id));
        $obj->delete_record("emp_monthly_leave", array("salary_struc_id" => $id));

        $obj->update_record("loan_advance_details", ['emp_id' => $emp_id, 'month' => $month, 'year' => $year, 'type' => 'Loan'], ['is_paid' => 0, 'paid_date' => '']);
        $obj->update_record("loan_advance_details", ['emp_id' => $emp_id, 'month' => $month, 'year' => $year, 'type' => 'Advance'], ['is_paid' => 0, 'paid_date' => '']);
    }

    echo "success";
} else {
    echo "error";
}
