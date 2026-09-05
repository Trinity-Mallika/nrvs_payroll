<style>
.attendance-card {
    border-radius: 16px;
    padding: 14px 16px;
    margin-bottom: 12px;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
    background: #fff;
}

.attendance-date {
    font-size: 14px;
    font-weight: 700;
    color: #124671;
}

.status-badge {
   padding: 0px 7px;
    border-radius: 17px;
    font-size: 10px;
    font-weight: 700;
    display: inline-block;
}

.status-present {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #86efac;
}

.status-absent {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fca5a5;
}

.status-incom {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fb6f6f;
}

.status-leave {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fcd34d;
}

.status-holiday {
    background: #ede9fe;
    color: #6d28d9;
    border: 1px solid #c4b5fd;
}

.status-half {
    background: #dbeafe;
    color: #1d4ed8;
    border: 1px solid #93c5fd;
}

.attendance-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef4ff;
    color: #124671;
    font-size: 20px;
}

.attendance-main {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.attendance-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.attendance-day {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
}

.legend-box {
    width: 14px;
    height: 14px;
    border-radius: 4px;
    display: inline-block;
    margin-right: 5px;
}

.legend-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 15px;
    padding: 12px;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}
</style>

<?php include("appsession.php");

$title = 'Attendence Details';
$pagename = 'attendance_details.php';
$emp_id          = $_POST['emp_id']??$_SESSION['emp_id'];
// $emp_id = $_SESSION['emp_id'];
$basic_salary = $obj->getvalfield("employee_master","basic_salary","emp_id='$emp_id'");

$currentYear  = $_POST['currentYear'];
$currentMonth = $_POST['currentMonth'];
$date_as_new  = $_POST['date_as_new'];
$doj          = $_POST['doj'];

$current_date = date('Y-m-d');

$days_array = $obj->getDaysArray($currentMonth, $currentYear);

$has_valid_day = false;
?>

<!-- STATUS LEGEND -->

<div class="legend-wrap">

    <div>
        <span class="legend-box status-present"></span>
        <small>Present</small>
    </div>

    <div>
        <span class="legend-box status-incom"></span>
        <small>Incomplete</small>
    </div>

    <div>
        <span class="legend-box status-absent"></span>
        <small>Absent</small>
    </div>

    <div>
        <span class="legend-box status-leave"></span>
        <small>Leave</small>
    </div>

    <div>
        <span class="legend-box status-holiday"></span>
        <small>Holiday</small>
    </div>

    <div>
        <span class="legend-box status-half"></span>
        <small>Half Day</small>
    </div>

</div>

<?php
  $fromDate  = date("Y-m-d", strtotime("$currentYear-$currentMonth-01"));
  $toDate   = date("Y-m-t", strtotime($fromDate));

$holidayAttendanceStart = date('Y-m-d', strtotime($fromDate . ' -1 day'));
$holidayAttendanceEnd   = date('Y-m-d', strtotime($toDate . ' +1 day'));
$holidayAttendanceRows = $obj->executequery("
        SELECT 
         emp_id,
         attendance_date,
         attendance_status

         FROM attendance_entry

         WHERE emp_id ='$emp_id'

         AND attendance_date BETWEEN '$holidayAttendanceStart' 
         AND '$holidayAttendanceEnd'

         AND unit_id='$unitid'
                        ");
$holidayAttendanceMap = [];
 
foreach ($holidayAttendanceRows as $row) {
    $holidayAttendanceMap[$row['emp_id']][$row['attendance_date']]
                                        = $row['attendance_status'];
}
$holidayRows = $obj->executequery(" SELECT date , holiday_type FROM holiday_entry WHERE FIND_IN_SET('$unitid', unit_id) AND date BETWEEN '$fromDate' AND '$toDate'");

  $holidays = [];
  foreach ($holidayRows as $h) {
      $holidays[$h['date']] = true;
  }

  $holidayData = $obj->getHolidayCountWithSandwichRule2(
    $emp_id,
    $holidayRows,
    $holidayAttendanceMap
);
 
$holiday = $holidayData['total'];
$reportFromDate = "$currentYear-$currentMonth-01";          // report start
$fromDate_att = date('Y-m-d', strtotime("$reportFromDate -1 day")); // fetch start
$toDate_att  = date("Y-m-t", strtotime($reportFromDate));  

$punchData = $obj->executequery("
    SELECT 
        l.emp_id,
        em.shift_id,
        l.attendance_date,
        l.attendance_stamp,
        l.in_status
    FROM attendance_log l left join employee_master em on em.emp_id=l.emp_id
    WHERE l.emp_id='$emp_id'
    AND l.attendance_date BETWEEN '$fromDate_att' AND '$toDate_att'
    ORDER BY l.emp_id, l.attendance_stamp
");

                                        
    $punchMap = [];
    $lastOpen = [];
    foreach ($punchData as $row) {

    $emp    = $row['emp_id'];
    $date   = $row['attendance_date'];
    $status = $row['in_status'];
    $time   = date("H:i", strtotime($row['attendance_stamp']));
    $stamp  = strtotime($row['attendance_stamp']);

    if (!isset($punchMap[$emp])) {
        $punchMap[$emp] = [];
    }

    /* ================= GET SHIFT WINDOW ================= */

    $working_hrs = $row['shift_id'] ?? '08:00:00';
 
    $shift_row = $obj->executequery("
        SELECT in_time 
        FROM shift_master 
        WHERE working_hour='$working_hrs' 
        ORDER BY in_time ASC 
        LIMIT 1
    ");

    $morning_in = $shift_row[0]['in_time'] ?? '06:00:00';

    /* ================= IN LOGIC ================= */
    if ($status == 'IN') {

        if (!isset($punchMap[$emp][$date])) {
            $punchMap[$emp][$date] = [];
        }

        $punchMap[$emp][$date][] = [
            'in'  => $time,
            'in_stamp' => $stamp,
            'out' => '',
         
        ];

        $lastOpen[$emp] = [
            'date'  => $date,
            'index' => count($punchMap[$emp][$date]) - 1,
            'stamp' => $stamp
        ];
    }

    /* ================= OUT LOGIC ================= */
   if ($status == 'OUT') {

    $matched = false;

    foreach (array_reverse($punchMap[$emp], true) as $pDate => $entries) {

        foreach (array_reverse($entries, true) as $idx => $entry) {

            if (!empty($entry['in'])) {

                $inStamp = $entry['in_stamp'];

                $base_date = date('Y-m-d', strtotime($pDate . ' +1 day'));
                $max_out = strtotime($base_date . ' ' . $morning_in) + (4 * 3600);
// echo "<pre>";
// echo "IN Date: " . $pDate . "\n";
// echo "IN Time: " . date('Y-m-d H:i:s', $inStamp) . "\n";
// echo "OUT Time: " . date('Y-m-d H:i:s', $stamp) . "\n";
// echo "Max Out Time: " . date('Y-m-d H:i:s', $max_out) . "\n";
// echo "</pre>";

                if (
                    (
                        $stamp > $inStamp ||
                        date('Y-m-d', $stamp) > date('Y-m-d', $inStamp)
                    )
                    && $stamp <= $max_out
                ) {

                    // 🔥 ALWAYS overwrite
                // agar already OUT hai → naya pair banao
if (!empty($punchMap[$emp][$pDate][$idx]['out'])) {

    $punchMap[$emp][$pDate][] = [
        'in' => '',
        'out' => $time
    ];

} else {

    $punchMap[$emp][$pDate][$idx]['out'] = $time;
}

                    $matched = true;
                    break 2;
                }
            }
        }
    }

  if (!$matched) {

    // ✅ SAME DATE me hi show hoga
    if (!isset($punchMap[$emp][$date])) {
        $punchMap[$emp][$date] = [];
    }

    $punchMap[$emp][$date][] = [
        'in'  => '',
        'out' => $time
    ];
}
}
}
                                    


                                    $missPunchMap = [];

foreach ($punchMap as $empId => $dates) {

    foreach ($dates as $pDate => $entries) {

        $hasIn = false;

        foreach ($entries as $p) {
            if (!empty($p['in'])) {
                $hasIn = true;
                break;
            }
        }

        // ✅ ONLY OUT (no IN)
        if (!$hasIn && !empty($entries)) {
            $missPunchMap[$empId][$pDate] = 'Miss Punch';
        }
    }
}

 

foreach ($days_array as $key) {

    if ($key['fulldate'] > $current_date) {
        continue;
    }

    if ($key['fulldate'] < $doj) {
        continue;
    }

    $attendance_rows = $obj->executequery("
    SELECT attendance_status
    FROM attendance_entry
    WHERE emp_id='$emp_id'
    AND attendance_date='{$key['fulldate']}'
    AND month='$currentMonth'
    AND year='$currentYear'
    ORDER BY attendance_id ASC
");

$attendance_statuses = [];

foreach ($attendance_rows as $row) {
    if (!empty($row['attendance_status'])) {
        $attendance_statuses[] = $row['attendance_status'];
    } 
}

    $attendance_data = $obj->select_record(
        'attendance_entry',
        [
            'emp_id' => $emp_id,
            'attendance_date' => $key['fulldate'],
            'month' => $currentMonth,
            'year' => $currentYear
        ]
    );

    $attendance_status = $attendance_data['attendance_status'] ?? '';

    /* ================= HOLIDAY ================= */

    // $holiday_title = $obj->getvalfield(
    //     "holiday_entry",
    //     "holiday_tittle",
    //     "FIND_IN_SET('$unitid', unit_id)
    //     AND date='{$key['fulldate']}'"
    // );

    // if (!empty($holiday_title) && empty($attendance_statuses)) {
    //      $attendance_statuses[] = 'Holiday';
    // }

    /* ================= ABSENT ================= */
   
   if (
    isset($missPunchMap[$emp_id][$key['fulldate']]) &&
    empty($attendance_statuses)
) {
    $attendance_statuses[] = 'Miss Punch';
}
elseif (
    $key['fulldate'] < $current_date &&
    empty($attendance_statuses)
) {
    $attendance_statuses[] = 'Absent';
}
    
    if ($key['fulldate'] <= $current_date) {
        $has_valid_day = true;
?>

<div class="attendance-card">

    <div class="attendance-main">

        <div class="attendance-left">
            <div class="attendance-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div>
                <div class="attendance-date">
                    <?= date("d M Y", strtotime($key['fulldate'])) ?>
                </div>
                <div class="attendance-day">
                    <?= $key['day_name'] ?>
                </div>
            </div>
        </div>
        <div>
            <?php
              $tempStatuses = [];
              
                foreach($attendance_statuses as $attendance_status){
                    $isHoliday = isset($holidays[$key['fulldate']]);
                    $tempStatuses = []; 
                   
                    $statusClass = 'status-present';

                    if ($isHoliday &&  $holiday > 0 && $basic_salary <= 42000 && $attendance_status == 'Present') {
                        $statusClass = 'status-holiday';
                        $attendance_status = 'Public Holiday';
                    }
                    elseif ($attendance_status == 'Public Holiday'|| $attendance_status == 'National Holiday'||$attendance_status == 'Religion Holiday' || $attendance_status == 'Seasonal Holiday') {
                        $statusClass = 'status-holiday';
                    }
                    elseif ($attendance_status == 'Present') {
                        $statusClass = 'status-present';
                    }
                    elseif ($attendance_status == 'Absent') {
                        $statusClass = 'status-absent';
                    }
                    elseif ($attendance_status == 'Incomplete') {
                        $statusClass = 'status-incom';
                    }elseif ($attendance_status == 'Miss Punch') {
                        $statusClass = 'status-holiday';
                    } 
                    elseif (
                        in_array($attendance_status, [
                            'Weekly Leave',
                            'Earning Leave',
                            'C Off',
                            'Half Weekly Leave',
                            'Half Earning Leave',
                            'Half C Off',
                            'Extra Off',
                            'Half Extra Off',
                            'Half Leave',
                            'Leave'
                        ])
                    ) {
                        $statusClass = 'status-leave';
                    }
                    elseif ($attendance_status == 'Holiday') {
                        $statusClass = 'status-holiday';
                    }
                    elseif ($attendance_status == 'Half Day') {
                        $statusClass = 'status-half';
                    }
                ?>
           <span class="status-badge <?= $statusClass ?>">
        <?= $attendance_status ?>
    </span>
            <?php } ?>
        </div>
    </div>
</div>
<?php
    }
}
?>

<?php if (!$has_valid_day) { ?>

<div class="alert alert-warning text-center rounded-4 shadow-sm mt-3">
    <i class="ri ri-information-line fs-4 me-2"></i>
    Attendance is not available for this month.
</div>

<?php } ?>