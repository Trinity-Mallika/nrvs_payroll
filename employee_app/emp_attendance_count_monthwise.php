<?php
include("appsession.php");
$tblname = "attendance_entry";
$tblpkey = "attendance_id";
// only Nipesh Sir can set other member id 
if (isset($_GET['emp_id']) && $_GET['emp_id'] != "" && $usertype == 'admin')
    $emp_id = $obj->test_input($_GET['emp_id']);
else
    $emp_id = $loginid;

if (isset($_GET['attendance_month']) && $_GET['attendance_month'] != "")
    $attendance_month = $obj->test_input($_GET['attendance_month']);
else
    $attendance_month = date("Y-m");
$sundays = $obj->getSundays($attendance_month);

$sundays_cnt = sizeof($obj->getSundays($attendance_month));


/*$only_present = $obj->getvalfield("attendance_entry", "count(attendance_date)", "date_format(attendance_date,'%Y-%m')='$attendance_month' and emp_id='$emp_id' and is_half_day=0");


$half_day_cnt = $obj->getvalfield("attendance_entry", "count(is_half_day)", "date_format(attendance_date,'%Y-%m')='$attendance_month' and emp_id='$emp_id' and is_half_day=1");
$present_cnt = $only_present;
$lastdate = date('t', strtotime($attendance_month));

$absent_cnt = $lastdate - $present_cnt - $half_day_cnt;*/

$previousMonth = date('Y-m', strtotime($attendance_month . ' - 1month'));
$nextMonth = date('Y-m', strtotime($attendance_month . ' + 1month'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("topmenu.php"); ?>
    <style>
        td,
        th {
            padding: 5px 5px;
            font-size: 12px;
        }

        span.badge {
            font-size: 0.8rem;
            border: 1px solid #ddd;
            margin-left: 5px;
        }

        .card-panel {
            padding: 15px;
            border-radius: 10px;
        }

        .tabs .tab a:hover,
        .tabs .tab a.active {
            background-color: transparent;
            color: #015c3d;
            font-weight: normal;
        }

        .tabs .tab a {
            color: black;
            font-weight: normal;
        }

        .tabs .indicator {
            position: absolute;
            bottom: 0;
            height: 3px;
            background-color: #015c3d;
            will-change: left, right;
        }

        .donut-inner {
            position: absolute;
            bottom: 25%;
            left: 28%;
            text-align: center;
        }

        .month-range {
            background: white;
            padding: 9px 5px 5px;
            border-radius: 25px;
        }

        .att-table {
            background: white;
            padding: 5px;
            border-radius: 25px;
        }

        table th {
            color: #015c3d;
        }

        thead {
            border-bottom: 1px solid #edf1ed;
        }

        table td {
            font-weight: 400;
        }

        .btn-floating {
            width: 35px;
            height: 35px;
            line-height: 36px;
        }

        .reponsive-table {
            overflow: auto;
        }

        .reponsive-table table .sticky-col {
            position: sticky;
            left: 0px;
            background: white;
            z-index: 9;
        }
    </style>
</head>
<!-- HEADER -->

<?php include("headed.php"); ?>
<!-- END HEADER -->
<!-- SIDE NAV-->
<nav>

    <!-- LEFT SIDENAV-->
    <?php include("leftmenu.php"); ?>
    <!-- END LEFT SIDENAV-->
    <!-- RIGHT SIDENAV-->
    <!-- END RIGHT SIDENAV-->
</nav>
<!-- END SIDENAV-->

<body style="background:#dce5dc80;">

    <div class="section">
        <div class="container">

            <div class="row month-range">
                <div class="col s4">
                    <i class="material-icons"
                        onclick="go_url('<?php echo $previousMonth; ?>','<?php echo $emp_id; ?>')">chevron_left</i>
                </div>
                <div class="col s4 center">
                    <strong><?php echo date('M-Y', strtotime($attendance_month)); ?></strong>
                </div>
                <div class="col s4 ">
                    <i class="material-icons right"
                        onclick="go_url('<?php echo $nextMonth; ?>','<?php echo $emp_id; ?>')">chevron_right</i>
                </div>
            </div>


            <div class="row att-table" style="margin-bottom: 50px;">
                <div class="col s12">

                    <div class="reponsive-table">
                        <table class="centered ">
                            <thead>
                                <th class="sticky-col">Emp_Name</th>
                                <th>Days</th>
                                <th>Present</th>
                                <!-- <th>Half Day</th>
                                <th>Taken PL</th>
                                <th>Taken SL</th>
                                <th>Taken CL</th> -->
                                <th>Working Day</th>
                                <th>Absent</th>

                            </thead>
                            <tbody>

                                <?php
                                //for online value
                                $qry = $obj->executequery("select * from employee_master where (emp_id!=1 and emp_id!=2 and emp_id!=35 and emp_id!=34) order by emp_name asc");
                                //for off line value
                                // $qry = $obj->executequery("select * from m_employee where (emp_id!=1 and emp_id!=41 and emp_id!=53) order by emp_name asc");
                                foreach ($qry as $key) {
                                    $emp_id = $key['emp_id'];
                                    $emp_name = $key['emp_name'];
                                    $mobile_no = $key['mob_no'];

                                    $only_present = $obj->getvalfield("attendance_entry", "count(attendance_date)", "date_format(attendance_date,'%Y-%m')='$attendance_month' and emp_id='$emp_id' ");



                                    //$half_day_cnt = $obj->getvalfield("attendance_entry", "count(is_half_day)", "date_format(attendance_date,'%Y-%m')='$attendance_month' and emp_id='$emp_id' and is_half_day=1");
                                
                                    $holiday = $obj->getvalfield("holiday_entry", "count(*)", "1=1");

                                    $present_cnt = $only_present + $sundays_cnt + $holiday;

                                    $lastdate = date('t', strtotime($attendance_month));
                                    $absent_cnt = $lastdate;
                                    //full pl
                                    // $count_fullpl = $obj->getvalfield("leave_application", "sum(no_of_day)", "leave_type='PL' and emp_id='$emp_id' and is_approve = 1 and full_half_day='Full Day'");
                                
                                    //half pl
                                    // $count_halfpl = $obj->getvalfield("leave_application", "sum(no_of_day)", "leave_type='PL' and emp_id='$emp_id' and is_approve = 1 and full_half_day='Half Day'") / 2;
                                    //full sl
                                    // $count_fullsl = $obj->getvalfield("leave_application", "sum(no_of_day)", "leave_type='SL' and emp_id='$emp_id' and is_approve = 1 and full_half_day='Full Day'");
                                
                                    //half sl
                                    // $count_halfsl = $obj->getvalfield("leave_application", "sum(no_of_day)", "leave_type='SL' and emp_id='$emp_id' and is_approve = 1 and full_half_day='Half Day'") / 2;
                                
                                    // $half_day_cnt = $obj->getvalfield("leave_application", "count(no_of_day)", "date_format(apply_date,'%Y-%m')='$attendance_month' and emp_id='$emp_id' and full_half_day='Half Day' and is_approve=1 and leave_type='CL'");
                                
                                    // $count_cl_full = $obj->getvalfield("leave_application", "count(no_of_day)", "date_format(apply_date,'%Y-%m')='$attendance_month' and emp_id='$emp_id' and full_half_day='Full Day' and is_approve=1 and leave_type='CL'");
                                
                                    // $half_day_cnt_inout = ($obj->getvalfield("attendance_entry", "count(is_half_day)", "date_format(attendance_date,'%Y-%m')='$attendance_month' and emp_id='$emp_id' and attendance_in_time  between '14:00:00' and '18:00:00'") / 2) + ($obj->getvalfield("attendance_entry", "count(is_half_day)", "date_format(attendance_date,'%Y-%m')='$attendance_month' and emp_id='$emp_id' and attendance_out_time  between '14:00:00' and '18:00:00'") / 2);
                                


                                    // $totpl = $obj->getvalfield("leave_setting", "pl", "emp_id='$emp_id'");
                                    // $totsl = $obj->getvalfield("leave_setting", "sl", "emp_id='$emp_id'");
                                    //$bal_pl = $totpl - $countpl;
                                    //$bal_sl = $totsl - $countsl;
                                    $countpl = (isset($countpl)) ? $countpl : 0;
                                    $countsl = (isset($countsl)) ? $countsl : 0;
                                    // $working_cnt = $present_cnt + $count_fullpl + $count_halfpl + $count_fullsl + $count_halfsl - $count_cl_full + $half_day_cnt_inout + $half_day_cnt / 2;
                                    $working_cnt = $present_cnt;
                                    $absent_cnt = $lastdate - $working_cnt;


                                    ?>

                                    <tr>
                                        <th class="center sticky-col"><?php echo $key['emp_name']; ?></th>
                                        <td><span class="btn btn-floating "><?php echo $lastdate; ?></span></td>
                                        <td><span class="btn btn-floating  teal darken-4">
                                                <?php echo $present_cnt; ?></span></td>
                                        <!-- <td><span
                                                class="btn btn-floating yellow darken-4"><?php echo $half_day_cnt + $half_day_cnt_inout; ?></span>
                                        </td>
                                        <td><span
                                                class="btn btn-floating pink darken-4"><?php echo $count_halfpl + $count_fullpl; ?></span>
                                        </td>
                                        <td><span
                                                class="btn btn-floating brown darken-4"><?php echo $count_fullsl + $count_halfsl; ?></span>
                                        </td>
                                        <td><span
                                                class="btn btn-floating grey darken-4"><?php echo $count_cl_full; ?></span>
                                        </td> -->
                                        <td><span
                                                class="btn btn-floating purple darken-4"><?php echo $working_cnt; ?></span>
                                        </td>
                                        <td><span class="btn btn-floating red darken-4"><?php echo $absent_cnt; ?></span>
                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>
                        </table>
                    </div>



                </div>
            </div>
        </div>
    </div>
    <ul id="tabs" class="tabs" style="position: fixed; bottom: 0px;border-top:1px solid #ddd;z-index: 999;">
        <li class="tab">
            <a class=" small " target="_self" href="emp_task_assign.php" style="line-height: 1;font-size: 11px; ">
                <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">create</i>
            </a>
        </li>
        <li class="tab">
            <a class="small " target="_self" href="task.php" style="line-height: 1;font-size: 11px; ">
                <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">event_note</i>
            </a>
        </li>
        <li class="tab">
            <a class=" small " target="_self" href="dashboard.php" style="line-height: 1;font-size: 11px;">
                <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">home</i>
            </a>
        </li>
        <li class="tab">
            <a class=" small " target="_self" href="assigned_task.php" style="line-height: 1;font-size: 11px; ">
                <i class="material-icons" style="display: block;padding-top: 13px;line-height: 1.1;">people</i>
            </a>
        </li>
        <li class="tab">
            <a class=" small active" target="_self" href="attandance_details.php"
                style="line-height: 1;font-size: 11px; ">
                <i class="material-icons"
                    style="display: block;padding-top: 5px;line-height: 1.1;">perm_contact_calendar</i>
                Attendence
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
    <script>
        $('.datepicker').pickadate({
            selectMonths: true, // Creates a dropdown to control month
            selectYears: 15, // Creates a dropdown of 15 years to control year,
            today: 'Today',
            clear: 'Clear',
            format: 'dd-mm-yyyy',
            close: 'Ok',
            closeOnSelect: false, // Close upon selecting a date,
            container: undefined, // ex. 'body' will append picker to body
        });
    </script>
    <script>
        function go_url(attendance_month, emp_id) {
            location = "?emp_id=" + emp_id + "&attendance_month=" + attendance_month;
        }
    </script>
    <script>
        jQuery(document).ready(function () {
            jQuery('select').material_select();
        });

        function select_employee(emp_id) {
            location = '?emp_id=' + emp_id;
        }
    </script>
</body>

</html>