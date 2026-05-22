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


$only_present = $obj->getvalfield("attendance_entry", "count(attendance_date)", "date_format(attendance_date,'%Y-%m')='$attendance_month' and emp_id='$emp_id' and is_half_day=0");
$present_cnt = $only_present;

$half_day_cnt = $obj->getvalfield("attendance_entry", "count(is_half_day)", "date_format(attendance_date,'%Y-%m')='$attendance_month' and emp_id='$emp_id' and is_half_day=1");

$lastdate = date('t', strtotime($attendance_month));

$absent_cnt = $lastdate - $present_cnt - $half_day_cnt;

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
            color: #512DA8;
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
            background-color: #3f51b5;
            will-change: left, right;
        }

        .donut-inner {
            position: absolute;
            bottom: 25%;
            left: 28%;
            text-align: center;
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

<body>

    <div class="section">
        <div class="container">

            <div class="row">
                <div class="col s4">
                    <i class="material-icons"
                        onclick="go_url('<?php echo $previousMonth; ?>','<?php echo $emp_id; ?>')">chevron_left</i>
                </div>
                <div class="col s4">
                    <strong><?php echo date('M-Y', strtotime($attendance_month)); ?></strong>
                </div>
                <div class="col s4 ">
                    <i class="material-icons right"
                        onclick="go_url('<?php echo $nextMonth; ?>','<?php echo $emp_id; ?>')">chevron_right</i>
                </div>
            </div>
            <?php if ($usertype == 'admin') { ?>
                <div class="row">
                    <div class="input-field col s12">
                        <select name="employee_name" id="employee_name" onchange="select_employee(this.value)">
                            <?php
                            $sql = $obj->executequery("select * from m_employee order by emp_name asc");
                            foreach ($sql as $row) {
                                ?>
                                <option value="<?php echo $row['emp_id']; ?>"><?php echo ucwords($row['emp_name']); ?></option>
                            <?php } ?>
                        </select>
                        <label>Select Name</label>
                        <script>
                            document.getElementById('employee_name').value = '<?php echo $emp_id; ?>';
                        </script>
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col s12">
                    <table class="centered bordered striped" style="border:1px solid #ddd;">
                        <thead>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Half Day</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $present_cnt; ?></td>
                                <td><?php echo $absent_cnt; ?></td>
                                <td><?php echo $half_day_cnt; ?></td>
                            </tr>
                        </tbody>
                    </table>


                </div>
            </div>
            <div class="row">
                <div class="col s12">

                    <ul class="collection">
                        <?php for ($a = 1; $a <= $lastdate; $a++) {
                            $today = $attendance_month . "-" . $a;
                            $in_time = "";
                            $out_time = "";
                            $absent = true;
                            $half_day = 0;
                            // for sunday
                            $check_sunday = false;
                            if (in_array($a, $sundays))
                                $check_sunday = true;
                            $where = array("attendance_date" => $today, "emp_id" => $emp_id);
                            $row_get = $obj->select_record($tblname, $where);
                            if (!empty($row_get) && sizeof($row_get) > 1) {

                                $in_time = $row_get["attendance_in_time"];
                                if ($in_time != "00:00:00")
                                    $in_time = date('h:i:s a', strtotime($in_time));

                                $out_time = $row_get["attendance_out_time"];
                                if ($out_time != "00:00:00")
                                    $out_time = date('h:i:s a', strtotime($out_time));

                                $absent = false;
                                $half_day = $row_get['is_half_day'];
                            }
                            ?>
                            <li class="collection-item avatar" style="min-height: 60px;">
                                <i
                                    class="material-icons circle <?php if (!$check_sunday) { ?>purple<?php } ?> darken-4"></i>
                                <span
                                    style="position: absolute; left: 33px; top: 20px; font-weight: 600; color: white;"><?php echo $a; ?></span>
                                <p style="font-size: smaller;">Time In - <?php echo $in_time; ?><br>
                                    Time Out - <?php echo $out_time; ?>
                                </p>
                                <?php if ($absent) { ?>
                                    <span class="new badge secondary-content red" data-badge-caption="A"></span>
                                <?php } elseif ($half_day) { ?>
                                    <span class="new badge secondary-content blue" data-badge-caption="HD"></span>
                                <?php } else { ?>
                                    <span class="new badge secondary-content green" data-badge-caption="P"></span>
                                <?php } ?>
                            </li>
                        <?php } ?>
                        <?php if (false) { //this is not usefull  
                                ?>
                            <li class="collection-item avatar" style="min-height: 60px;">
                                <i class="material-icons circle purple darken-4"></i>
                                <span
                                    style="position: absolute; left: 33px; top: 20px; font-weight: 600; color: white;"><?php echo $a; ?></span>
                                <p style="font-size: smaller;">Time In - 10:30 AM<br>
                                    Time Out - 07:00 PM
                                </p>


                            </li>
                            <li class="collection-item avatar" style="min-height: 60px;">
                                <i class="material-icons circle purple darken-4"></i>
                                <span
                                    style="position: absolute; left: 33px; top: 20px; font-weight: 600; color: white;"><?php echo $a; ?></span>
                                <p style="font-size: smaller;">Time In - 10:30 AM<br>
                                    Time Out - 07:00 PM
                                </p>


                            </li>
                        <?php } ?>
                    </ul>



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