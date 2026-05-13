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
