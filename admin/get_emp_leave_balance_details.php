<?php
include("../adminsession.php");

$emp_id = $_POST['emp_id'];
$sessionid = $_SESSION['sessionid'];

/* ================= OPENING LEAVE ================= */


$total_opening_leave = $obj->getvalfield(
    "emp_leave_allotment",
    "SUM(opening_leave)",
    "emp_id = '$emp_id' 
    AND sessionid = '$sessionid'"
	);

$used_opening_leave = 0;

$res = $obj->executequery("
    SELECT 
        COALESCE(SUM(
            CASE 
                WHEN attendance_status = 'Leave' THEN 1
                WHEN attendance_status = 'Half Leave' THEN 0.5
                ELSE 0
            END
        ),0) as total_used

    FROM attendance_entry

    WHERE emp_id = '$emp_id'
    AND attendance_status IN ('Leave','Half Leave')
    AND sessionid = '$sessionid'
	");

if (!empty($res)) {
    $used_opening_leave = $res[0]['total_used'];
}

$opening_leave_balance = $total_opening_leave - $used_opening_leave;

if ($opening_leave_balance < 0) {
    $opening_leave_balance = 0;
}
    $used_earning_leave = $obj->getvalfield(
			"attendance_entry",
			"IFNULL(SUM(
				CASE 
					WHEN attendance_status = 'Earning Leave' THEN 1
					WHEN attendance_status = 'Half Earning Leave' THEN 0.5
					ELSE 0
				END
			),0)",
			"emp_id='$emp_id'
			AND sessionid='$sessionid'"
	);
    $earning_leave = $obj->getvalfield(
			"emp_monthly_leave",
			"IFNULL(SUM(total_leave),0)",
			"emp_id='$emp_id'
         AND leave_type='earning'
         AND sessionid='$sessionid'"
		);
	$total_earning = $earning_leave-$used_earning_leave;

/* ================= EXTRA OFF MONTH WISE ================= */

$sql = "
SELECT 
    month,
    year,
    leave_type,
    COALESCE(SUM(total_leave),0) as uploaded_leave

FROM emp_monthly_leave

WHERE emp_id = '$emp_id' and leave_type='eoff'

GROUP BY year, month, leave_type
ORDER BY year ASC, month ASC
";

$res1 = $obj->executequery($sql);

?>

<!-- SUMMARY -->
<div class="row g-2 mb-3">

    <!-- OPENING LEAVE -->
    <div class="col-md-2">
        <div class="card border shadow-sm">
            <div class="card-body text-center p-2">
                <h6 class="mb-1" style="font-size:12px;">Total Opening</h6>
                <h5 class="text-primary mb-0">
                    <?=$total_opening_leave?>
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card border shadow-sm">
            <div class="card-body text-center p-2">
                <h6 class="mb-1" style="font-size:12px;">Used Opening</h6>
                <h5 class="text-danger mb-0">
                    <?=$used_opening_leave?>
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card border shadow-sm">
            <div class="card-body text-center p-2">
                <h6 class="mb-1" style="font-size:12px;">Opening Balance</h6>
                <h5 class="text-success mb-0">
                    <?=$opening_leave_balance?>
                </h5>
            </div>
        </div>
    </div>

    <!-- EARNING LEAVE -->
    <div class="col-md-2">
        <div class="card border shadow-sm">
            <div class="card-body text-center p-2">
                <h6 class="mb-1" style="font-size:12px;">Total Earning</h6>
                <h5 class="text-primary mb-0">
                    <?=$earning_leave?>
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card border shadow-sm">
            <div class="card-body text-center p-2">
                <h6 class="mb-1" style="font-size:12px;">Used Earning</h6>
                <h5 class="text-danger mb-0">
                    <?=$used_earning_leave?>
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card border shadow-sm">
            <div class="card-body text-center p-2">
                <h6 class="mb-1" style="font-size:12px;">Earning Balance</h6>
                <h5 class="text-success mb-0">
                    <?=$total_earning?>
                </h5>
            </div>
        </div>
    </div>

</div>


<div class="table-responsive">

    <table class="table table-bordered table-sm">

        <thead class="table-primary">

            <tr>
                <th>Month</th>
                <th>Year</th>
                <th>Leave Type</th>
                <th>Total</th>
                <th>Used</th>
                <th>Balance</th>
            </tr>

        </thead>

        <tbody>

            <?php
            foreach ($res1 as $row) {
                $month = $row['month'];
                $year  = $row['year'];
                $month_name = date(
                    "F",
                    mktime(0, 0, 0, $month, 1)
                );

                $leave_type = $row['leave_type'];
                $uploaded = $row['uploaded_leave'];
                /* ================= USED LEAVE ================= */
                
                    $used = 0;
               $used_res = $obj->executequery("
    SELECT 
        COALESCE(SUM(
            CASE 
                WHEN attendance_status = 'Extra Off' THEN 1
                WHEN attendance_status = 'Half Extra Off' THEN 0.5
                ELSE 0
            END
        ),0) as total_used

    FROM attendance_entry 
    WHERE emp_id = '$emp_id'
    AND MONTH(attendance_date) = '$month'
    AND YEAR(attendance_date) = '$year'
    AND attendance_status IN ('Extra Off','Half Extra Off')
");
 

if (!empty($used_res)) {
    $used = $used_res[0]['total_used'];
}

                $balance = $uploaded - $used;

                if ($balance < 0) {
                    $balance = 0;
                }
            ?>

            <tr>

                <td><?=$month_name?></td>

                <td><?=$year?></td>

                <td>
                    <?=ucwords(str_replace('_',' ',$leave_type))?>
                </td>

                <td><?=$uploaded?></td>

                <td><?=$used?></td>

                <td>
                   
                        <?=$balance?>
                     
                </td>

            </tr>

            <?php } ?>

        </tbody>

    </table>

</div>