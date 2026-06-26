<?php
include("appsession.php");
$today = date('Y-m-d');
$emp_id = $_SESSION['emp_id'];
$week_offset = $_POST['week'] ?? 0;

$startOfWeek = date('Y-m-d', strtotime("monday this week $week_offset week"));
$endOfWeek   = date('Y-m-d', strtotime("sunday this week $week_offset week"));

$weekData = $obj->executequery("
    SELECT attendance_date, attendance_status
    FROM attendance_entry
    WHERE emp_id = '$emp_id'
    AND attendance_date BETWEEN '$startOfWeek' AND '$endOfWeek'
");

$weekStatus = [];

foreach ($weekData as $row) {
    $day = date('N', strtotime($row['attendance_date']));
    $weekStatus[$day] = $row['attendance_status'];
}

$holidayData = $obj->executequery("
    SELECT date, holiday_tittle 
    FROM holiday_entry 
    WHERE date BETWEEN '$startOfWeek' AND '$endOfWeek'
    AND FIND_IN_SET('$unitid', unit_id)
");

$holidayDates = [];

foreach ($holidayData as $h) {
    $holidayDates[$h['date']] = $h['holiday_tittle'];
}

    $punchData = $obj->executequery("
                                SELECT 
                                    l.emp_id,
                                    em.shift_id,
                                    l.attendance_date,
                                    l.attendance_stamp,
                                    l.in_status
                                FROM attendance_log l left join employee_master em on em.emp_id=l.emp_id
                                WHERE l.emp_id='$emp_id'
                                AND l.attendance_date BETWEEN '$startOfWeek' AND '$endOfWeek'
                                ORDER BY l.emp_id, l.attendance_stamp
                            ");
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
    
                                if ($status == 'OUT') {
                                    $matched = false;
                                    foreach (array_reverse($punchMap[$emp], true) as $pDate => $entries) {
                                        foreach (array_reverse($entries, true) as $idx => $entry) {
                                            if (!empty($entry['in'])) {
                                                $inStamp = $entry['in_stamp'];
                                                $base_date = date('Y-m-d', strtotime($pDate . ' +1 day'));
                                                $max_out = strtotime($base_date . ' ' . $morning_in) + (4 * 3600);
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

function getStatusBox($status)
{
    switch ($status) {
        case 'Present':
            return ['P', 'present'];
        case 'Incomplete':
            return ['I', 'absent'];
        case 'Half Day':
            return ['H', 'half'];
        case 'Weekly Leave':
            return ['W', 'weekoff'];
        case 'Earning Leave':
            return ['E', 'leave'];
        case 'C Off':
            return ['C', 'coff'];
        case 'Half Weekly Leave':
            return ['HW', 'weekoff'];
        case 'Half Earning Leave':
            return ['HE', 'leave'];
        case 'Half C Off':
            return ['HC', 'coff'];
        case 'Absent':
            return ['A', 'absent'];
        default:
            return ['I', 'leave'];
    }
}
$html = '';

for ($i = 1; $i <= 7; $i++) {

    $currentDate = date('Y-m-d', strtotime($startOfWeek . " +" . ($i - 1) . " days"));
    $dayOfWeek = date('N', strtotime($currentDate));

    // ✅ 1. If attendance exists → highest priority
    if (isset($weekStatus[$i])) {

        list($text, $class) = getStatusBox($weekStatus[$i]);
    }elseif (isset($missPunchMap[$emp_id][$currentDate])) {
        $status = 'Miss Punch';
        $text = 'M';
      $class = 'holiday';
    } elseif (isset($holidayDates[$currentDate])) {
        $text = 'H';
        $class = 'holiday';
    } elseif (strtotime($currentDate) < strtotime($today)) {

        $text = 'A';
        $class = 'absent';
    } else {
        $text = '';
        $class = 'day';
    }
    // Optional: show date
    $html .= "<td>
                <div class='box $class' title='" . ($holidayDates[$currentDate] ?? '') . "'>$text</div>
                <small>" . date('d', strtotime($currentDate)) . "</small>
              </td>";
}

echo json_encode([
    "status" => "success",
    "html" => $html,
    "start" => $startOfWeek,
    "end" => $endOfWeek
]);
