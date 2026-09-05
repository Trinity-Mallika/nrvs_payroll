<?php
include("../adminsession.php");

$records = $_POST['records'] ?? [];

$salaryIds = [];
$empIds = [];
$promotionIds = [];
 
if (!empty($records)) {
    foreach ($records as $rec) {
        $id = $rec['id'];
        $is_locked = $obj->getvalfield(
            "salary_structure",
            "payment_status",
            "salary_struc_id='$id'"
        );
        if ($is_locked == 2) {
            continue;  
        }
        $salaryIds[] = $rec['id'];
        $empIds[] = $rec['emp_id'];
        $month = $rec['month'];
        $year  = $rec['year'];
        $emp_id = $rec['emp_id'];
    }

    if (empty($salaryIds)) {
        echo "all_locked";
        exit;
    }

    $obj->bulk_delete('salary_structure', [
        'salary_struc_id'     => $salaryIds, 
    ]);

    $empIds = array_unique($empIds);

    $obj->bulk_update_with_arr(
        "loan_advance_details",
        [
            'is_paid' => 0,
            'paid_date' => null
        ],
        [
            'emp_id' => $empIds,
            'month' => $month,
            'year' => $year,
            'type' => 'Loan'
        ]
    );

    $obj->bulk_update_with_arr(
        "loan_advance_details",
        [
            'is_paid' => 0,
            'paid_date' => null
        ],
        [
            'emp_id' => $empIds,
            'month' => $month,
            'year' => $year,
            'type' => 'Advance'
        ]
    );

    $obj->bulk_delete('emp_monthly_leave', [
        'emp_id'     => $empIds,
        'month'      => $month,
        'year'       => $year,
        'unit_id'    => $unitid,
        'is_opb'     => 0,
        'leave_type' => 'earning'
    ]);

    $obj->bulk_delete('emp_monthly_leave', [
        'emp_id'     => $empIds,
        'month'      => $month,
        'year'       => $year,
        'unit_id'    => $unitid,
        'is_opb'     => 0,
        'leave_type' => 'weekly'
    ]);
    
    $obj->bulk_delete('emp_promotion', [
        'salary_struc_id'     => $salaryIds,
        'type'      => 'increment', 
    ]);

    $empIds = array_unique($empIds);

    foreach ($empIds as $emp_id) {

        $basic_salary = $obj->getvalfield(
            "emp_promotion",
            "basic_salary",
            "emp_id='$emp_id' order by emp_promotion_id desc"
        );

        if ($basic_salary == '') {
            $basic_salary = 0;
        }

        $obj->update_record(
            "employee_master",
            ['emp_id' => $emp_id],
            ['basic_salary' => $basic_salary]
        );
    }

     $form_data1 = array(
        "primary_id" => 0,
        "flag" => 'Multiple Record Deleted Successfully',
        "activity_type" => 'Deleted',
        "createdby" => $loginid,
        "pagename" => 'salary_generate_report.php',
        "created_date" => $createdate,
        "created_time" => date('H:i:s'),
        "unit_id" => $unitid,
        'ipaddress' => $ipaddress,
        "sessionid" => $sessionid
    );
    $logactivity = $obj->insert_record("logactivity_master", $form_data1);

    echo "success";
} else {
    echo "error";
}