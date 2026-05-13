<?php
include("appsession.php");
$tblname = "attendance_entry";
$tblpkey = "attendance_id";
if (isset($_GET['attendance_date']))
    $attendance_date = $obj->test_input($_GET['attendance_date']);
else
    $attendance_date = date('Y-m-d');



$previousDay = date('Y-m-d', strtotime($attendance_date . ' - 1day'));
$nextDay = date('Y-m-d', strtotime($attendance_date . ' + 1day'));


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
                <div class="col s3">
                    <i style="cursor: pointer;" class="material-icons" onclick="go_url('<?php echo $previousDay; ?>')">chevron_left</i>
                </div>
                <div class="col s6 center">
                    <strong> <span class="purple-text">Friday</span> &nbsp;&nbsp;&nbsp; <?php echo $obj->dateformatindia($attendance_date); ?></strong>
                </div>
                <div class="col s3">
                    <i style="cursor: pointer;" class="material-icons right" onclick="go_url('<?php echo $nextDay; ?>')">chevron_right</i>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                    <?php
                    $qry = $obj->executequery("select * from m_employee");

                    foreach ($qry as $row) {

                        $in_time = "";
                        $out_time = "";
                        $attendance_cnt = $obj->getvalfield($tblname, "count(*)", "attendance_date='$attendance_date' and empid='$row[empid]'");
                        $absent = false;
                        $present = false;
                        $half_day = false;
                        if ($attendance_cnt > 0) {
                            $where = array("attendance_date" => $attendance_date, "empid" => $row["empid"]);
                            $data = $obj->select_record($tblname, $where);

                            $in_time = $data['attendance_in_time'];
                            if ($in_time != "00:00:00") {
                                $in_time = date('h:i:s', strtotime($in_time));
                            } else {
                                $in_time = "";
                            }
                            $out_time = $data["attendance_out_time"];
                            if ($out_time != "00:00:00") {
                                $out_time = date('h:i:s', strtotime($out_time));
                            } else {
                                $out_time = "";
                            }

                            $present = true;
                            $half_day = (bool)$data["is_half_day"];
                        } else {
                            $absent = true;
                        }

                    ?>
                        <div class="card">
                            <div class="card-content" style="padding: 10px 10px;">
                                <div class="row" style="margin-bottom: 0px;">
                                    <div class="col s8" style="padding: 0 .1rem;padding-left:15px;">
                                        <h6><?php echo strtoupper($row['emp_name']); ?></h6>
                                       <!--  <span onclick="change_attendance('present','<?php echo $row['empid']; ?>')" style="margin-left: 0px;" class="<?php if ($present && !$half_day) { ?>new <?php } ?> badge left" data-badge-caption="Present"></span>
                                        <span onclick="change_attendance('absent','<?php echo $row['empid']; ?>')" class="<?php if ($absent) { ?>red white-text<?php } ?> badge left" data-badge-caption="Absent"></span> -->
                                        <span onclick="change_attendance('halfday','<?php echo $row['empid']; ?>')" class="<?php if ($half_day) { ?>yellow<?php } ?> badge left" data-badge-caption="Half Day"></span>
                                    </div>
                                    <div class="col s2" style="padding: 0 .1rem;">
                                        <h6 style="font-size: 10px;margin-bottom: 10px;">Time-In</h6>
                                        <span style="font-size: 10px;"><?php echo $in_time; ?></span>
                                    </div>
                                    <div class="col s2" style="padding: 0 .1rem;">
                                        <h6 style="font-size: 10px;margin-bottom: 10px;">Time-Out</h6>
                                        <span style="font-size: 10px;"><?php echo $out_time; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

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
        function go_url(attendance_date) {
            location = '?attendance_date=' + attendance_date
        }
    </script>
    <script>
        function change_attendance(attendance, empid) {
            attendance_date = '<?php echo $attendance_date; ?>';

            if (confirm("Are you sure?")) {
                jQuery.ajax({
                    method: "POST",
                    url: 'ajax_change_attendance.php',
                    data: 'attendance=' + attendance + '&attendance_date=' + attendance_date + "&empid=" + empid,
                    dataType: 'html',
                    success: function(data) {
                        console.log(data);
                        // alert(data);
                        location.reload();
                    }
                });
            }
        }
    </script>
</body>

</html>