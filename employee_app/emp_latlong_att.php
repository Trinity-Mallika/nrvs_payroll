<?php include("appsession.php");
$emp_id = $_SESSION['emp_id'];
$attendance_date = date('Y-m-d');
date_default_timezone_set('Asia/Kolkata');

$phone_type = $obj->getvalfield("employee_master", "phone_type", "emp_id='$emp_id'");
$phone_type = $obj->getvalfield("employee_master", "phone_type", "emp_id='$emp_id'");
$in_date = $obj->dateformatindia($obj->getvalfield("attendance_entry", "attendance_date", "emp_id='$emp_id' and attendance_date='$attendance_date'"));
$in_time = $obj->getvalfield("attendance_entry", "intime", "emp_id='$emp_id' and attendance_date='$attendance_date'");
$count_in = $obj->getvalfield("attendance_entry", "count(intime)", "emp_id='$emp_id' and attendance_date='$attendance_date' and intime != '00:00:00'");
$out_date = $obj->dateformatindia($obj->getvalfield("attendance_entry", "attendance_date", "emp_id='$emp_id' and attendance_date='$attendance_date'"));
$out_time = $obj->getvalfield("attendance_entry", "outtime", "emp_id='$emp_id' and attendance_date='$attendance_date'");
$count_out = $obj->getvalfield("attendance_entry", "count(outtime)", "emp_id='$emp_id' and attendance_date='$attendance_date' and outtime != '00:00:00'");
$in_time = date('h:i:s a', strtotime($in_time));
$out_time = date('h:i:s a', strtotime($out_time));
$indatetime = $in_date . ' / ' . $in_time;
$outdatetime = $out_date . ' / ' . $out_time;

$emp_id = $_SESSION['emp_id'];
$branch_id = $obj->getvalfield("employee_master", "branch_id", "emp_id='$emp_id'");
$working_hour = $obj->getvalfield("branch_master", "working_hour", "branch_id='$branch_id'");


$current_date = date('Y-m-d');

$today_working = $obj->getvalfield("attendance_entry", "working_hours", "emp_id='$emp_id' and attendance_date='$current_date'");
$working_per = floatval($today_working) / floatval($working_hour) * 100;


function isCurrentTimeGreaterThan($currentTime, $comparisonTime)
{
    $currentTimestamp = strtotime($currentTime);
    $comparisonTimestamp = strtotime($comparisonTime);
    return $currentTimestamp >= $comparisonTimestamp;
}

$currentTime = date("H:i:s");
$comparisonTime = "10:00:00"; // Time to compare with
if (isCurrentTimeGreaterThan($currentTime, $comparisonTime)) {
    $disabled = "enable";
} else {
    $disabled = "disabled";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("topmenu.php"); ?>
    <style type="text/css">
        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            border-radius: 50%;
            width: 30%;
            background-color: #318bb1;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body id="homepage">
    <!-- BEGIN PRELOADING -->
    <!-- END PRELOADING -->
    <!-- HEADER -->
    <?php include("headed.php"); ?>
    <!-- END HEADER -->
    <!-- SIDE NAV-->
    <nav>
        <!-- LEFT SIDENAV-->
        <?php include("leftmenu.php"); ?>
        <!-- END LEFT SIDENAV-->
        <!-- RIGHT SIDENAV-->
        <?php //include("rightmenu.php");
        ?>
        <!-- END RIGHT SIDENAV-->
    </nav>
    <!-- END SIDENAV-->


    <!-- CONTENT -->
    <div id="page-content" style="background-color:#f9f9f9;">
        <div class="setting-page" style="background: #01025c0f; border-radius: 0px 0px 65px 65px;">
            <div class="container">
                <div class="row ">
                    <div class="col s12 center">
                        <div class="section-title">
                            <h1 style="margin-bottom: 10px;">Today </h1>
                            <span class="theme-secondary-color"
                                style="color: black;"><?php echo date('d-m-Y'); ?></span><br>
                            <small class="theme-secondary-color"
                                style="color: gray;">Monday</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="container">
            <div class="row" style="margin-top: 10px;">
                <div class="col s12">
                    <div id="chart"></div>
                </div>
                <div class="input-field col s12 m12 l12">
                    <center>
                        <?php if ($phone_type == 0) { ?>

                            <?php if ($count_in == 0) { ?>
                                <button style="background-color: #015c3d;width:80%;border-radius: 20px;" class="btn btn-block"
                                    onclick="set_attandance_in();" value="IN" id="status_in" <?php echo $disabled ?>>IN</button>


                            <?php } else {
                            ?>

                                <button style="background-color: #015c3d;width:100%;border-radius: 20px;"
                                    class="btn btn-block">In Time : <?php echo $indatetime; ?></button>


                            <?php } ?>
                            <?php } else {


                            if ($count_in == 0) { ?>
                                <button style="background-color: #015c3d;width:80%;border-radius: 20px;" class="btn btn-block"
                                    onclick="set_iphone_in();" value="IN" id="status_in" <?php echo $disabled ?>>IN</button>


                            <?php } else {
                            ?>

                                <button style="background-color: #015c3d;width:100%;border-radius: 20px;"
                                    class="btn btn-block">In Time : <?php echo $indatetime; ?></button>


                        <?php }
                        } ?>



                    </center>
                </div>
                <div class="input-field col s12 m12 l12">
                    <center>
                        <?php if ($phone_type == 0) { ?>
                            <?php if ($count_out == 0) { ?>
                                <button style="background-color: #830826;width:80%;border-radius: 20px;margin-top:10px;"
                                    class="btn btn-block" onclick="set_attandance_out();" value="OUT"
                                    id="status_out">OUT</button>
                            <?php } else {
                            ?>

                                <button style="background-color: #830826;width:100%;border-radius: 20px;"
                                    class="btn btn-block">Out Time : <?php echo $outdatetime; ?></button>


                            <?php } ?>
                            <?php } else {


                            if ($count_out == 0) { ?>
                                <button style="background-color: #830826;width:80%;border-radius: 20px;margin-top:10px;"
                                    class="btn btn-block" onclick="set_iphone_out();" value="OUT" id="status_out">OUT</button>


                            <?php } else {
                            ?>

                                <button style="background-color: #830826;width:100%;border-radius: 20px;"
                                    class="btn btn-block">Out Time : <?php echo $outdatetime; ?></button>


                        <?php }
                        } ?>
                    </center>
                </div>
            </div>

        </div>
    </div>
    <!-- END CONTENT -->

    <ul id="tabs" class="tabs" style="position: fixed; bottom: 0px;border-top:1px solid #ddd;z-index: 999;">
        <li class="tab">
            <a class=" small " target="_self" href="change-password.php" style="line-height: 1;font-size: 11px; ">
                <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">create</i>

            </a>
        </li>
        
        <li class="tab">
            <a class=" small " target="_self" href="dashboard.php" style="line-height: 1;font-size: 11px;">
                <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">home</i>
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

    <!-- SUBSCRIBE -->

    <!-- END SUBSCRIBE -->
    <!-- FOOTER  -->

    <!-- END FOOTER -->
    <!-- Script -->
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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var options = {
            series: [<?php echo $working_per ?>],
            chart: {
                height: 250,
                type: 'radialBar',
                toolbar: {
                    show: true
                }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -135,
                    endAngle: 225,
                    hollow: {
                        margin: 0,
                        size: '80%',
                        background: '#fff',
                        image: undefined,
                        imageOffsetX: 0,
                        imageOffsetY: 0,
                        position: 'front',
                        dropShadow: {
                            enabled: true,
                            top: 3,
                            left: 0,
                            blur: 4,
                            opacity: 0.5
                        }
                    },
                    track: {
                        background: '#fff',
                        strokeWidth: '67%',
                        margin: 0, // margin is in pixels
                        dropShadow: {
                            enabled: true,
                            top: -3,
                            left: 0,
                            blur: 4,
                            opacity: 0.7
                        }
                    },

                    dataLabels: {
                        show: true,
                        name: {
                            offsetY: -10,
                            show: true,
                            color: '#888',
                            fontSize: '17px'
                        },
                        value: {
                            formatter: function(val) {
                                return parseInt(val);
                            },
                            color: '#111',
                            fontSize: '36px',
                            show: true,
                        }
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#ABE5A1'],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            stroke: {
                lineCap: 'round'
            },
            labels: ['Percent'],
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>

    <script type="text/javascript">
        function set_attandance_in() {

            var emp_id = '<?php echo $emp_id; ?>';
            login.performClick(emp_id, "IN");
            location = "dashboard.php";

        }

        function set_attandance_out() {

            var emp_id = '<?php echo $emp_id; ?>';
            login.performClick(emp_id, "OUT");
            location = "dashboard.php";
        }


        function set_iphone_in() {

            var emp_id = '<?php echo $emp_id; ?>';
            var status_in = document.getElementById('status_in').value;
            var status_out = '';

            jQuery.ajax({
                type: 'POST',
                url: 'letlong_attandance.php',
                data: 'emp_id=' + emp_id + '&status_in=' + status_in + '&status_out=' + status_out,
                dataType: 'html',
                success: function(data) {
                    // alert(data);
                    if (data == 0) {
                        alert('Your Branch Location Dose Not Matched!');
                    } else {
                        location = "emp_latlong_att.php";
                    }

                }
            }); //ajax close
        }

        function set_iphone_out() {

            var emp_id = '<?php echo $emp_id; ?>';
            var status_out = document.getElementById('status_out').value;
            var status_in = '';

            jQuery.ajax({
                type: 'POST',
                url: 'letlong_attandance.php',
                data: 'emp_id=' + emp_id + '&status_out=' + status_out + '&status_in=' + status_in,
                dataType: 'html',
                success: function(data) {
                    // alert(data);
                    location = "emp_latlong_att.php";
                }
            }); //ajax close
        }
    </script>
</body>

</html>