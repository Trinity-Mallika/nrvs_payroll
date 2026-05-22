<?php include("appsession.php");
$title = 'Attendence Details';
$pagename = 'attendance_details.php';
$emp_id = $_SESSION['emp_id'];

$currentYear = date('Y');
$currentMonth =  (int)date('m');
$emp_data =  $obj->select_record("employee_master", array("emp_id" => $emp_id));

$doj = $emp_data['date_of_joining'] ?? '';
$emp_shift_hrs = $emp_data['shift_id'] ?? '';
$unit_id = $emp_data['unit_id'] ?? '';
$department_id = $emp_data['department_id'];
$allow_weekly_off = $emp_data['allow_weekly_off'];
$is_esic = $emp_data['is_esic'];
$setting_type = ($is_esic  == 1) ? 'ESIC' : 'Non ESIC';
$is_allow_c_off = $obj->getvalfield("department_master", "c_off_check", "department_id='$department_id'");
$is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$emp_data[unit_id]'");


if (isset($_GET['currentYear']) && isset($_GET['currentMonth'])) {
    $currentYear = $_GET['currentYear'];
    $currentMonth = $_GET['currentMonth'];
}

if (isset($_GET['prev'])) {
    $currentMonth--;
    if ($currentMonth < 1) {
        $currentMonth = 12;
        $currentYear--;
    }
}
if (isset($_GET['next'])) {
    $currentMonth++;
    if ($currentMonth > 12) {
        $currentMonth = 1;
        $currentYear++;
    }
}
if (isset($_GET['date'])) {
    $date_as_new = $_GET['date'];
} else {
    $date_as_new = "";
}


$res = $obj->executequery("
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
            WHEN attendance_status IN ('Half Day','Half Weekly Leave','Half Earning Leave','Half C Off','Half Extra Off','Half Leave') THEN 1 
            ELSE 0 
        END) AS total_half

    FROM attendance_entry
    WHERE emp_id = '$emp_id' 
    AND month = '$currentMonth' 
    AND year = '$currentYear' AND unit_id='$unitid'
");

$row = $res[0] ?? [];

$total_present1 = $row['total_present1'] ?? 0;
$total_half1    = $row['total_half1'] ?? 0;

$total_present  = $row['total_present'] ?? 0;
$total_half     = $row['total_half'] ?? 0;

$real_total_attandence = $total_present1 + ($total_half1 / 2);
$total_attandence      = $total_present + ($total_half / 2);

$totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
$week_leave = $obj->totalWeeklyLeave($unitid, $real_total_attandence, $allow_weekly_off);
$earn_leave_present =  $real_total_attandence + $week_leave;
$monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $earn_leave_present, $unitid);

//$three_month_leave = $obj->getLeave($emp_id, $currentMonth, $currentYear);
$extra_off =$obj->getExtraOffBalance($emp_id, $currentMonth, $currentYear);
$opening_leave_balance =$obj->get_opening_leave_balance($emp_id, $sessionid);
$total_earning_leave = $obj->getEarningLeave($emp_id, $sessionid);
$total_curr_week_leave = $obj->getCurrentWeekLeave($emp_id, $currentMonth, $currentYear);

$holidayData = $obj->getHolidayCountWithSandwichRule(
    $emp_id,
    $unitid,
    $currentMonth,
    $currentYear
);

$holiday_total     = $holidayData['total'] ?? 0;
$holiday_national  = $holidayData['national'] ?? 0;
$holiday_religious = $holidayData['religious'] ?? 0;
$holiday_seasonal  = $holidayData['seasonal'] ?? 0;



$total_payable_days = $total_attandence;
$total_payable_days += $week_leave;
if ($is_all_leave_add == 1) {
    $total_payable_days += $monthly_leave;
}
//$total_payable_days += $three_month_leave;
if ($is_allow_c_off == 1) {
    $total_payable_days = min($total_payable_days, $totalDaysInMonth);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Attendance History</title>
    <?php include("inc/css-file.php"); ?>


</head>

<body>
    <section class="top-sec ">
        <?php include("inc/header.php"); ?>
        <div class="container">
            <div class="card p-1 attendance-card border-0 shadow-lg mb-2 rounded-pill">
                <div class="row">
                    <div class="col-3 ">
                        <!-- <a href="?prev&currentYear=<?php echo $currentYear ?>&currentMonth=<?php echo $currentMonth; ?>&emp_id=<?php echo $emp_id ?>"><i class="bi bi-arrow-left-circle-fill fs-5 text-blue"></i></a> -->
                    </div>
                    <div class="col-6 text-center ">
                        <h6 class="mb-0 mt-1 text-blue">
                            <?php echo date('M', strtotime("$currentYear-$currentMonth")); ?>
                            <?php echo date('Y', strtotime("$currentYear-$currentMonth")); ?>
                        </h6>
                    </div>
                    <div class="col-3 text-end">
                        <!-- <a href="?next&currentYear=<?php echo $currentYear ?>&currentMonth=<?php echo $currentMonth; ?>&emp_id=<?php echo $emp_id ?>"><i class="bi bi-arrow-right-circle-fill fs-5 text-blue"></i></a> -->
                    </div>
                </div>
            </div>

            <h6 class="text-center text-white mt-3 fs-5">Employee Wise Attendance List</h6>

            <div class="scroll-container mt-4">
                <div class="box border-card-blue bg-light-blue">
                    <h3 class="mb-1"><?= $real_total_attandence; ?></h3>
                    <h6 class="mb-0 text-center">Total Present</h6>
                </div>
                <div class="box border-card-red bg-light-red">
                    <h3 class="mb-1"><?= $total_attandence ?></h3>
                    <h6 class="mb-0 text-center">Total Attendance <br> With Leave</h6>
                </div>
                <div class="box border-card-green bg-light-green">
                    <h3 class="mb-1"><?= $week_leave ?></h3>
                    <h6 class="mb-0 text-center">Weekly Off</h6>
                </div>
                <div class="box border-card-yellow bg-light-yellow">
                    <h3 class="mb-1"><?= $monthly_leave ?></h3>
                    <h6 class="mb-0 text-center">Earn Leave</h6>
                </div>
                <div class="box border-card-pink bg-light-pink">
                    <h3 class="mb-1"><?= $total_payable_days ?></h3>
                    <h6 class="mb-0 text-center">Total Payable Days</h6>
                </div>
                <div class="box border-card-purple bg-light-purple">
                    <h3 class="mb-1"><?= $holiday_total  ?></h3>
                    <h6 class="mb-0 text-center">Holiday</h6>
                </div>
            </div>

            <h6>
                NH : <?= $holiday_national; ?> |
                RH : <?= $holiday_religious; ?> |
                SH : <?= $holiday_seasonal; ?></h6>

            <div class="card border-0 shadow-lg mb-2 today-date-card p-2 mt-4 bg-darkc">
                <div class="row">
                    <div class="col-9 ">
                        <small class="fw-bold text-white">Extra Off </small>
                    </div>
                    <div class="col-3 text-center">
                        <h4 class="mb-0"><?= $extra_off['balance'] ?></h4>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-lg today-date-card p-2 mb-2 bg-darkc">
                <div class="row">
                    <div class="col-9 ">
                        <small class="fw-bold text-white">Opening Leave Balance </small>
                    </div>
                    <div class="col-3 text-center">
                        <h4 class="mb-0"><?= $opening_leave_balance ?></h4>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-lg today-date-card p-2 mb-3 bg-darkc">
                <div class="row">
                    <div class="col-9 ">
                        <small class="fw-bold text-white">Pending Earn leave</small>
                    </div>
                    <div class="col-3 text-center">
                        <h4 class="mb-0"><?= $total_earning_leave ?></h4>
                    </div>
                </div>
            </div>

        </div>

        <div class="card border-0  p-2 mb-2" id="show_att_data">


        </div>
    </section>


    <?php include('inc/js-file.php') ?>
    <script>
        $(document).ready(function() {

            showatttype();
        });

        function showatttype() {
            var emp_id = '<?php echo $emp_id ?>';
            var currentMonth = '<?php echo $currentMonth ?>';
            var currentYear = '<?php echo $currentYear ?>';
            var date_as_new = '<?php echo $date_as_new ?>';
            var doj = '<?php echo $doj ?>';
            if (emp_id > 0) {
                jQuery.ajax({
                    type: 'POST',
                    url: 'view_att_details.php',
                    data: 'currentMonth=' + currentMonth + '&currentYear=' + currentYear + '&emp_id=' + emp_id + '&date_as_new=' + date_as_new + '&doj=' + doj,
                    dataType: 'html',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Please wait',
                            text: 'Loading attendance details...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(data) {
                        Swal.close();
                        document.getElementById('show_att_data').innerHTML = data;
                        //total(emp_id, currentMonth, currentYear);
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire('Error', 'Unable to load attendance data', 'error');
                    }

                }); //ajax close
            }
        }
    </script>

</body>

</html>