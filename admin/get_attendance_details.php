<?php
include("../adminsession.php");

$date   = $obj->test_input($_POST['fulldate']);
$emp_id = $obj->test_input($_POST['emp_id']);

$row = $obj->executequery("
    SELECT a.*,
           uc.username as created_by_username,
           uc.fullname as created_by_name,
           uu.username as updated_by_username,
           uu.fullname as updated_by_name
    FROM attendance_entry a
    LEFT JOIN user uc ON a.createdby = uc.userid
    LEFT JOIN user uu ON a.updateby = uu.userid
    WHERE a.attendance_date = '$date'
    AND a.emp_id = '$emp_id'
    LIMIT 1
");

if (count($row) > 0) {

    $r = $row[0];

    echo "<table class='table table-bordered table-sm'>";

    // Entry Type
    echo "
        <tr>
            <th>Entry Type</th>
            <td>{$r['entry_type']}</td>
        </tr>
    ";

    // Attendance Status
    echo "
        <tr>
            <th>Status</th>
            <td>{$r['attendance_status']}</td>
        </tr>
    ";

    // In Time
    if (!empty($r['intime'])) {
        echo "
            <tr>
                <th>In Time</th>
                <td>{$r['intime']}</td>
            </tr>
        ";
    }

    // Out Time
    if (!empty($r['outtime'])) {
        echo "
            <tr>
                <th>Out Time</th>
                <td>{$r['outtime']}</td>
            </tr>
        ";
    }

    // Remark
    if (!empty($r['remark'])) {
        echo "
            <tr>
                <th>Remark</th>
                <td>{$r['remark']}</td>
            </tr>
        ";
    }

    // Created By
    if (strtolower($r['entry_type']) == 'machine') {

        echo "
        <tr class='table-light'>
            <th>Created By</th>
            <td><span class='badge bg-info'>Punch By Machine</span></td>
        </tr>
        <tr>
            <th>Created Date</th>
            <td>{$r['createdate']} {$r['createtime']}</td>
        </tr>
    ";
    } else {

        echo "
        <tr class='table-light'>
            <th>Created By</th>
            <td>{$r['created_by_username']} ({$r['created_by_name']})</td>
        </tr>
        <tr>
            <th>Created Date</th>
            <td>{$r['createdate']} {$r['createtime']}</td>
        </tr>
    ";
    }

    // ✅ Show Updated Section Only If Exists
    if (!empty($r['updateby']) && !empty($r['updated_by_username'])) {

        echo "
            <tr class='table-light'>
                <th>Updated By</th>
                <td>{$r['updated_by_username']} ({$r['updated_by_name']})</td>
            </tr>
            <tr>
                <th>Updated Date</th>
                <td>{$r['lastupdated']}</td>
            </tr>
        ";
    }

    echo "</table>";
} else {
    echo "<div class='text-danger text-center'>No record found</div>";
}
