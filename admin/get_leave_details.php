<?php
include("../adminsession.php");

$on_duty_id = $obj->test_input($_POST['on_duty_id']);
$total_days = $obj->test_input($_POST['total_days']??''); 
$type = $obj->test_input($_POST['type']);
$month = $obj->test_input($_POST['month']??'');
$year = $obj->test_input($_POST['year']??'');
$detail_crit = "WHERE on_duty_id='$on_duty_id'";
if ($type == "approved") {
    $detail_crit .= " AND lad.status=1";
} elseif ($type == "rejected") {
    $detail_crit .= " AND lad.status=2";
} elseif ($type == "pending") {
    $detail_crit .= " AND lad.status=0";
}
$sn = 1;
$emp_id = $obj->getvalfield("on_duty_master", "emp_id", "on_duty_id='$on_duty_id'");
$emp_data = $obj->select_record("employee_master", ['emp_id' => $emp_id]);
$emp_code = $emp_data['emp_code']??'';
$first_name = $emp_data['first_name']??'';
$department_id = $emp_data['department_id'];
$shift_hrs = $emp_data['shift_id'];
$unit_id = $emp_data['unit_id'];
$basic_salary = $emp_data['basic_salary'];
$date_of_joining = $emp_data['date_of_joining']; 
$is_esic = $emp_data['is_esic'];
$setting_type = ($is_esic  == 1) ? 'ESIC' : 'Non ESIC';
$is_allow_c_off = $obj->getvalfield("department_master", "c_off_check", "department_id='$department_id'");
$allow_earn_leave_carry = $obj->getvalfield("department_master", "earn_leave_check", "department_id='$department_id'");
$department_name = $obj->getvalfield("department_master", "department_name", "department_id='$department_id'");
$is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$emp_data[unit_id]'");
$appication_datee = $obj->getvalfield("on_duty_master", "application_date", "on_duty_id='$on_duty_id'");
$applyMonth =$month  ??date('n');
$applyYear  =$year  ??date('Y');
 
$totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
           $sql ="
    SELECT  
                        SUM(CASE 
                            WHEN attendance_status = 'Present' THEN 1 
                            ELSE 0 
                        END) AS total_present1,
                        SUM(CASE 
                            WHEN attendance_status = 'Half Day' THEN 1 
                            ELSE 0 
                        END) AS total_half1,
                        SUM(CASE 
                            WHEN attendance_status IN ('Present','Weekly Leave','Earning Leave','C Off','Extra Off','Leave') THEN 1 
                            ELSE 0 
                        END) AS total_present,

                        SUM(CASE 
                            WHEN attendance_status IN ('Weekly Leave','Earning Leave','C Off','Extra Off','Leave') THEN 1 
                            ELSE 0 
                        END) AS availed_leave,

                        SUM(CASE 
                           WHEN attendance_status IN ('Half Weekly Leave','Half Earning Leave','Half C Off','Half Extra Off','Half Leave') THEN 1 
                            ELSE 0 
                        END) AS availed_half_leave,

                        SUM(CASE 
                            WHEN attendance_status IN ('Half Day','Half Weekly Leave','Half Earning Leave','Half C Off','Half Extra Off','Half Leave') THEN 1 
                            ELSE 0 
                        END) AS total_half

                    FROM attendance_entry
                    WHERE emp_id = '$emp_id' 
                    AND month = '$month' 
                    AND year = '$year' AND unit_id='$unitid'
";

            $att = $obj->executequery($sql)[0] ?? [];
            $total_present1 = $att['total_present1'] ?? 0;
            $total_half1    = $att['total_half1'] ?? 0;
            $total_present  = $att['total_present'] ?? 0;
            $total_half     = $att['total_half'] ?? 0; 
            $availed_leave  = $att['availed_leave'] ?? 0;
            $availed_half_leave     = $att['availed_half_leave'] ?? 0; 
            $real_total_attandence = $total_present1 + ($total_half1 / 2);
            $total_attandence      = $total_present + ($total_half / 2); 
            $total_availed      = $availed_leave + ($availed_half_leave / 2); 

            $week_leave = $obj->totalWeeklyLeave($unitid, $real_total_attandence, $emp_id, $month, $year);
            $earn_leave_present =  $real_total_attandence+$week_leave;
            $monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $earn_leave_present, $unitid);
            $result = $obj->calculateLeaveUsage(
                $totalDaysInMonth,
                $total_attandence,
                $week_leave,
                $monthly_leave,
                // $three_month_leave,
                $is_allow_c_off,
                $is_all_leave_add,
                $allow_earn_leave_carry,0,0,$date_of_joining,$month,$year
            );
            $totalWorkingDays = $result['total_working_days'];
            $used_weekly = $result['used_weekly']; 

 
//$details = $obj->executequery("SELECT * FROM leave_apply_detail $detail_crit");
$details = $obj->executequery("
    SELECT 
        lad.*, 
        em.first_name AS hod_name,
        em.emp_code AS hod_code,
        u.fullname AS updated_by_name
        
    FROM leave_apply_detail lad
    LEFT JOIN user u 
        ON lad.approve_by = u.userid
    LEFT JOIN employee_master em 
        ON em.emp_id = lad.hod_apr_id
    $detail_crit
    ORDER BY lad.date ASC
");

$leaveDayArr = [
    'FD' => 'Full Day',
    'FHD' => 'First Half Day',
    'SHD' => 'Second Half Day',
    'SL' => 'Sick Leave'
];

$leaveTypeArr = [
    'EL' => 'Earned Leave',
    'EO' => 'EXTRA OFF',
    'L' => 'OPENING LEAVE',
    'WL' => 'Weekly Leave',
    'CO' => 'C Off',
    'LWP' => 'Leave Without Pay'
];

 
// $opening_leave_balance =
//     $obj->get_opening_leave_balance(
//         $emp_id,
//         $sessionid,
//         $applyMonth,
//         $applyYear
//     );

$total_earning_leave =
    $obj->getEarningLeave(
        $emp_id,
        $sessionid, 
        $applyMonth,
        $applyYear
    );

$extra_off =
    $obj->getExtraOffBalance(
        $emp_id,
        $applyMonth,
        $applyYear
    );

    $total_coff = $obj->getEmpCoffLeave(
    $emp_id,
    $sessionid,
    $applyMonth,
    $applyYear
);
 $applyMonthName = date("F", mktime(0,0,0,$applyMonth,1,$applyYear));
?>
<div class="row mb-3">
    <div class="col-md-4">
        <table class="table table-borderless table-sm mb-0">
            <tr>
                <td class="fw-semibold">Application Date</td>
                <td><?= $obj->dateformatindia($appication_datee) ?></td>
            </tr>
            <tr>
                <td class="fw-semibold">Leave Apply Days</td>
                <td><?= $total_days ?></td>
            </tr>
            <tr>
                <td class="fw-semibold">Week Off</td>
                <td><?= $used_weekly ?></td>
            </tr>
        </table>
    </div>
    <div class="col-md-4">
        <table class="table table-borderless table-sm mb-0">
            <tr>
                <td class="fw-semibold">Employee Code</td>
                <td>
                    <input type="hidden" id="modalEmpId" value="<?= $emp_id ?>">
                    <strong><?= $emp_code ?></strong>
                </td>
            </tr>
            <tr>
                <td class="fw-semibold">Employee Name</td>
                <td><strong><?= $first_name ?></strong></td>
            </tr>
            <tr>
                <td class="fw-semibold">Total Working Days</td>
                <td><strong><?= $totalWorkingDays ?></strong></td>
            </tr>
        </table>
    </div>
    <div class="col-md-4">
        <table class="table table-borderless table-sm mb-0">
            <tr>
                <td class="fw-semibold">Department</td>
                <td><strong><?= $department_name ?></strong></td>
            </tr>
            <tr>
                <td class="fw-semibold">Working Day</td>
                <td><?= $real_total_attandence ?></td>
            </tr>
        </table>
    </div>
</div>


Leave Balance As On <?=$applyMonthName?>
<div class="row mb-3"> 
    <div class="col-md-4">
        <div class="alert alert-primary py-2 mb-2">
            <strong>Earn Leave :</strong>
            <?= number_format($total_earning_leave,1) ?>
        </div>
    </div>

    <div class="col-md-4">
        <div class="alert alert-primary py-2 mb-2">
            <strong>Extra Off :</strong>
            <?= number_format($extra_off['balance'],1) ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="alert alert-primary py-2 mb-2">
            <strong>C-Off :</strong>
            <?= number_format($total_coff,1) ?>
        </div>
    </div>
</div>

<table class="table table-bordered table-sm">
    <thead class="table-light text-center">
        <tr>
            <th>SNo.</th>
            <th>Date </th>
            <th>Day</th>
            <th>Leave Type</th>
            <!-- <th>Present</th>
            <th>Week Off</th>
            <th>Availed Leave</th>
            <th>Total Working Days</th> -->

            <th>Remarks</th>
            <th>Appr <input type="checkbox" id="appr_check" class="form-check-input" /></th>
            <th>Rej <input type="checkbox" id="rej_check" class="form-check-input" /></th>
            <th>Pen <input type="checkbox" id="pen_check" class="form-check-input" /></th>
            <th>HOD Status</th>
            <th>HR Status</th>

        </tr>
    </thead>
    <tbody>

        <?php foreach ($details as $row) { 
 
        ?>
        <tr class="detail_row" data-id="<?= $row['leave_details_id'] ?>" data-odid="<?= $row['on_duty_id'] ?>">
            <td class="text-center"><?= $sn++; ?></td>

            <td><input type="hidden" id="modal_date_<?= $row['leave_details_id']; ?>" value="<?= $row['date']; ?>">
                <?= $obj->dateformatindia($row['date']) ?></td>

            <td>
                <input type="hidden" id="modal_leave_day_<?= $row['leave_details_id'] ?>"
                    value="<?= $row['leave_day'] ?>">
                <?= $leaveDayArr[$row['leave_day']] ?? $row['leave_day']; ?>
            </td>
            <td>
                <select class="form-select form-select-sm chosen-select"
                    id="modal_leave_type_<?= $row['leave_details_id'] ?>">
                    <option value="EL" <?= ($row['leave_type'] == 'EL') ? 'selected' : '' ?>>EARNED LEAVE</option>
                    <option value="EO" <?= ($row['leave_type'] == 'EO') ? 'selected' : '' ?>>EXTRA OFF</option>
                    <option value="CO" <?= ($row['leave_type'] == 'CO') ? 'selected' : '' ?>>C-OFF</option>
                    <!-- <option value="L" < ($row['leave_type'] == 'L') ? 'selected' : '' ?>>OPENING LEAVE</option> -->
                    <!-- <option value="LWP" <?= ($row['leave_type'] == 'LWP') ? 'selected' : '' ?>>LEAVE WITHOUT PAY -->
                    </option>
                </select>
            </td>

            <td><?= $row['remark'] ?></td>

            <td class="text-center">
                <!-- <input type="checkbox" < $row['status'] == 1 ? 'checked' : '' ?> data-id="< $row['leave_details_id'] ?>" class="form-check-input approve_chk" < ($row['status'] == 1 || $row['status'] == 2) ? 'disabled' : '' ?>> -->

                <input type="checkbox" <?= $row['status'] == 1 ? 'checked' : '' ?>
                    data-id="<?= $row['leave_details_id'] ?>" class="form-check-input approve_chk">

            </td>

            <td class="text-center">
                <input type="checkbox" <?= $row['status'] == 2 ? 'checked' : '' ?>
                    data-id="<?= $row['leave_details_id'] ?>" class="form-check-input reject_chk">
            </td>

            <td class="text-center">
                <input type="checkbox" <?= $row['status'] == 0 ? 'checked' : '' ?>
                    data-id="<?= $row['leave_details_id'] ?>" class="form-check-input pending_chk">
            </td>

            <td class="text-center">
                <?php
                    if ($row['is_apr_hod'] == "1") {
                        echo '<span class="badge bg-success text-white">Approved</span>';
                    } elseif ($row['is_apr_hod'] == "2") {
                        echo '<span class="badge bg-danger text-white">Rejected</span>';
                    } else {
                        echo '<span class="badge bg-warning text-white">Pending</span>';
                    }
                     if ($row['is_apr_hod'] == 1 || $row['is_apr_hod'] == 2) {
                    ?>
                <br>
                <?=$row['hod_code'].'-'.$row['hod_name']?>
                Dt: <?=$obj->dateformatindia($row['lastupdated_hod'])?>

                <?php } ?>
            </td>

            <td class="text-center">
                <?php
                    if ($row['status'] == "1") {
                        echo '<span class="badge bg-success text-white">Approved</span>';
                    } elseif ($row['status'] == "2") {
                        echo '<span class="badge bg-danger text-white">Rejected</span>';
                    } else {
                        echo '<span class="badge bg-warning text-white">Pending</span>';
                    }
                     if ($row['status'] == 1 || $row['status'] == 2) {
                    ?>
                <br>
                <?=$row['updated_by_name']?>
                Dt: <?=$obj->dateformatindia($row['approve_date'])?>

                <?php } ?>
            </td>

        </tr>
        <tr>
            <td colspan="13">
                <div class="d-flex align-items-center">
                    <label class="fw-semibold me-2">Remark :</label>
                    <input type="text" class="form-control form-control-sm w-50 remark_input"
                        id="modal_appr_remark_<?= $row['leave_details_id'] ?>" value="<?= $row['appr_remark'] ?>">
                </div>
            </td>
        </tr>
        <?php } ?>

    </tbody>

</table>