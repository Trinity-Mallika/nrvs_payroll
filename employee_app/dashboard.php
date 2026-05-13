<?php
include("appsession.php");
if ($ios_user == 1) {
    $atten_page = "emp_latlong_att_ios.php";
} else {
    $atten_page = "emp_latlong_att.php";
}

$curr_date = date('Y-m-d');
if (isset($_GET['token'])) {
    $token = $obj->test_input($_GET['token']);
    if ($token != '') {
        $token = base64_decode($token);
        $token_exist = $obj->getvalfield("firebase_notification", "count(*)", "firebaseid='$token'");
        if ($token_exist == 0) {
            $create_date = date('Y-m-d h:i:s');
            $userid = $_SESSION['emp_id'];
            $notification_data = array('firebaseid' => $token, 'userid' => $userid, 'create_date' => $create_date);
            $obj->insert_record("firebase_notification", $notification_data);
        }
    }
}
$curr_year = date('Y');
$curr_month = date('n');


$emp_id = $_SESSION['emp_id'];



// $emp_weakly_holiday = $obj->getvalfield("employee_master", "weekly_holiday ", "emp_id='$emp_id' ");
// $weakly_holiday_array = explode(",", $emp_weakly_holiday);
$allow_weekly_off = $obj->getvalfield("employee_master", "allow_weekly_off", "emp_id='$emp_id'");
if ($allow_weekly_off == 1) {
    // Assuming Sunday as weekly off
    $weakly_holiday_array = ['Sunday'];
} else {
    $weakly_holiday_array = [];
}


$date_ofarray =   getStartAndEndDate($curr_month, $curr_year);

$paydate =  $date_ofarray['start_date'];
$todate =  $date_ofarray['end_date'];

$specificHolidayDates = array();
$sqlholyday = $obj->executequery("select * from holiday_entry");
foreach ($sqlholyday as $key) {
    $specificHolidayDates[] = $key['date'];
}

$no_of_holiday = $obj->countHolidays($paydate, $todate, $weakly_holiday_array, $specificHolidayDates);



$presentDates = array();
$sqlholyday = $obj->executequery("select * from attendance_entry where attheadid='1' and emp_id='$emp_id' and  attendance_date between '$paydate' and '$todate'");
foreach ($sqlholyday as $key) {
    $presentDates[] = $key['attendance_date'];
}

$sandwichLeaves_array =  $obj->calculateSandwichLeaveWithHolidays($presentDates, $paydate, $todate, $weakly_holiday_array);



$sandwichLeaves = $sandwichLeaves_array['sandwichLeave'];


$no_of_holiday = $no_of_holiday - $sandwichLeaves;


$unit_id = $obj->getvalfield("employee_master", "unit_id", "emp_id='$emp_id'");

$in_time = $obj->getvalfield("unit_master", "in_time", "unit_id='$unit_id'");
$in_margin = $obj->getvalfield("unit_master", "in_margin", "unit_id='$unit_id'");
$out_margin = $obj->getvalfield("unit_master", "out_margin", "unit_id='$unit_id'");
$in_time = date('H:i:s', strtotime('+' . $in_margin . ' minutes', strtotime($in_time)));
$out_time = $obj->getvalfield("unit_master", "out_time", "unit_id='$unit_id'");

$out_time = date('H:i:s', strtotime('-' . $out_margin . ' minutes', strtotime($out_time)));
$count_working_days = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$curr_month' and year='$curr_year' and attendance_status='Present'");
$present_days = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$curr_month' and year='$curr_year' and attendance_status='Present'");

// $on_time = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$curr_month' and year='$curr_year' and attendance_status='Present' and intime <='$in_time'");
$on_time = $obj->getvalfield(
    "attendance_entry",
    "count(*)",
    "emp_id='$emp_id' 
AND month='$curr_month' 
AND year='$curr_year' 
AND attendance_status='Present' 
AND intime IS NOT NULL 
AND intime <= '$in_time'"
);

// $late_in = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$curr_month' and year='$curr_year' and attendance_status='Present' and intime >'$in_time'");
$late_in = $obj->getvalfield(
    "attendance_entry",
    "count(*)",
    "emp_id='$emp_id' 
AND month='$curr_month' 
AND year='$curr_year' 
AND attendance_status='Present' 
AND intime IS NOT NULL 
AND intime > '$in_time'"
);

$total_days = cal_days_in_month(CAL_GREGORIAN, $curr_month, $curr_year);

// $persent = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and  attendance_date between '$paydate' and '$todate'");

$persent = $obj->getvalfield(
    "attendance_entry",
    "count(*)",
    "emp_id='$emp_id' 
AND attendance_status='Present'
AND attendance_date BETWEEN '$paydate' AND '$todate'"
);


// $early_exit = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$curr_month' and year='$curr_year' and attendance_status='Present' and outtime < '$out_time'");
$early_exit = $obj->getvalfield(
    "attendance_entry",
    "count(*)",
    "emp_id='$emp_id' 
AND month='$curr_month' 
AND year='$curr_year' 
AND attendance_status='Present' 
AND outtime IS NOT NULL 
AND outtime < '$out_time'"
);


// echo $no_of_holiday;
// $total_persent = $persent + $no_of_holiday;
// $absent = $total_days  - $total_persent;

$present_days = $persent;
$working_days = $total_days - $no_of_holiday;

$absent = $working_days - $present_days;

if ($absent < 0) {
    $absent = 0;
}






function getStartAndEndDate($month, $year)
{
    // Create a DateTime object for the first day of the month
    $startDate = new DateTime("$year-$month-01");

    // Clone the start date and modify to get the last day of the month
    $endDate = clone $startDate;
    $endDate->modify('last day of this month');

    // Return the start and end dates in the desired format
    return [
        'start_date' => $startDate->format('Y-m-d'),
        'end_date' => $endDate->format('Y-m-d'),
    ];
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("topmenu.php"); ?>
    <script src="package/dist/chart.min.js"></script>
    <script src="js/chartjs-plugin-datalabels@2.0.0.js"></script>


    <style>
        .card-panel {
            padding: 15px;
            border-radius: 10px;
        }



        .donut-inner {
            position: absolute;
            bottom: 25%;
            left: 28%;
            text-align: center;
        }

        .card {
            border-radius: 10px;

        }

        .mt-20 {
            margin-top: 18px;
        }

        .card-body {
            padding: 10px;
        }

        .f6 {
            font-size: large;
        }

        .colora {
            font-weight: 600;
            color: #6b6b6b;
            font-size: 14px;
        }

        .card-box {
            border: 1px solid #002fb5;
            border-radius: 10px;
            padding: 5px;
            margin: 10px 0px;
        }

        .card-box::after {
            content: "";
            position: absolute;
            height: 10px;
            width: 10px;
            border-radius: 50%;
            right: 16px;
            top: 16px;
        }

        .card-box.first::after {
            background-color: #00e396;
        }

        .card-box.second::after {
            background-color: #008ffb;
        }

        .card-box.third::after {
            background-color: #feb019;
        }

        .card-box.fourth::after {
            background-color: #ff4560;
        }

        .card-box h5 {
            margin-bottom: 0px;
        }

        .card-box small {
            font-weight: 500;
        }
    </style>
</head>
<!-- HEADER -->
<?php include("head.php"); ?>
<!-- END HEADER -->
<!-- SIDE NAV-->
<nav>
    <!-- LEFT SIDENAV-->
    <?php include("leftmenu.php"); ?>
    <!-- END LEFT SIDENAV-->
</nav>
<!-- END SIDENAV-->

<body style="background-color: #ebeeffb3;">

    <div class="">
        <div class="container">
            <div class="row" style="margin: 8px 0px;">

                <div class="col s12" style="position: relative;">
                    <div style="position: absolute;top: 45%; left: 50%; transform: translate(-50%, -50%);text-align:center;">
                        <h3 style="margin-bottom: 0px;"><?php echo $count_working_days ?></h3>
                        <h6>Working Days</h6>
                        <h6 style="font-weight: 500;color:#16295f;font-size: 16px;"><?php echo date('M') ?> <?php echo $curr_year; ?></h6>
                    </div>
                    <div id="chart"> </div>
                </div>
            </div>
            <div class="row" style="margin-bottom: 10px;">
                <div class="col s3" style="position: relative;">
                    <div class="card-box center first">
                        <h5><?php echo $on_time; ?></h5>
                        <small style="font-size: 9px;">On Time</small>
                    </div>
                </div>
                <div class="col s3" style="position: relative;">
                    <div class="card-box center second">
                        <h5><?php echo $late_in; ?></h5>
                        <small style="font-size: 9px;">Late In</small>
                    </div>
                </div>
                <div class="col s3" style="position: relative;">
                    <div class="card-box center third">
                        <h5><?php echo $early_exit ?></h5>
                        <small style="font-size: 9px;">Early Exit</small>
                    </div>
                </div>
                <div class="col s3" style="position: relative;">
                    <div class="card-box center fourth">
                        <h5><?php echo $absent ?></h5>
                        <small style="font-size: 9px;">Absent</small>
                    </div>
                </div>
            </div>
            <!-- count -->
            <div class="col s12" style="margin-bottom: 50px;">
                <!-- <a href="<//?php echo $atten_page ?>">
                    <div class="card" style="border: 1px solid #16295f; background: #16295f;color: white;">
                        <div class="card-body center">
                            <h6> Attendance Details</h6>
                        </div>
                    </div>
                </a> -->
                <a href="attandance_details.php">
                    <div class="card" style="border: 1px solid #16295f; background: #16295f;color: white;">
                        <div class="card-body center">
                            <h6>Attendance Details</h6>
                        </div>
                    </div>
                </a>
            </div>
            <!-- count -->

            <?php if (isset($_SESSION['type']) && $_SESSION['type'] == "admin") { ?>
                <div class="col s12 m6">
                    <div class="card-panel" style="margin-bottom: 40px;">
                        <canvas id="myChart" width="300" height="550"></canvas>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    </div>
    <ul id="tabs" class="tabs" style="position: fixed; bottom: 0px;border-top:1px solid #ddd;z-index: 999;">
        <li class="tab">
            <a class=" small " target="_self" href="change-password.php" style="line-height: 1;font-size: 11px; ">
                <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">create</i>

            </a>
        </li>

        <li class="tab">
            <a class=" small active" target="_self" href="dashboard.php" style="line-height: 1;font-size: 11px;">
                <i class="material-icons" style="display: block;padding-top: 5px;line-height: 1.1;">home</i> Dashboard
            </a>
        </li>
        <li class="tab">
            <a class=" small " target="_self" href="setting.php" style="line-height: 1;font-size: 11px; ">
                <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">people</i>

            </a>
        </li>
        <li class="tab">
            <a class=" small " target="_self" href="attandance_details.php" style="line-height: 1;font-size: 11px; ">
                <i class="material-icons"
                    style="display: block;padding-top: 13px;line-height: 1.1;">perm_contact_calendar</i>

            </a>
        </li>
    </ul>
    <script src="js/jquery.min.js"></script>
    <script src="js/materialize.min.js"></script>
    <!-- Owl carousel -->
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <!-- Magnific Popup core JS file -->
    <script src="lib/Magnific-Popup-master/dist/jquery.magnific-popup.js"></script>
    <!-- Slick JS -->
    <script src="lib/slick/slick/slick.min.js"></script>
    <!-- Custom script -->
    <script src="js/custom.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var on_time = <?php echo $on_time ?>;
        var late_in = <?php echo $late_in ?>;
        var early_exit = <?php echo $early_exit ?>;
        var absent = <?php echo $absent ?>;

        var options = {
            chart: {
                type: 'donut',
                height: 300
            },
            series: [on_time, late_in, early_exit, absent],
            labels: ['On Time', 'Late In', 'Early Exit', 'Absent'],
            colors: ['#00e396', '#008ffb', '#feb019', '#ff4560'],
            legend: {
                show: false
            },
            tooltip: {
                enabled: true, // Enable hover tooltips
            },
            dataLabels: {
                enabled: false
            },
            annotations: {
                text: {
                    text: 'Total: 1', // Example total
                    position: 'absolute',
                    offsetY: 0,
                    offsetX: 0,
                    style: {
                        color: '#000',
                        fontSize: '20px',
                        background: {
                            enabled: true,
                            borderColor: '#000',
                            borderWidth: 2,
                            borderRadius: 5,
                            opacity: 0.9,
                            padding: 4
                        }
                    }
                }
            },
            responsive: [{
                breakpoint: 768,
                options: {
                    legend: {
                        show: false // Hide legend in mobile view
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>


</body>

</html>