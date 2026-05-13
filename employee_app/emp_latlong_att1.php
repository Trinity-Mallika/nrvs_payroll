<?php include("appsession.php");
$empid = $_SESSION['empid'];
$attendance_date = date('Y-m-d');
$latitude = $longitude = $address = '';
//latitude and longitude get by gulshan apk
if (isset($_GET['latitude'])) {
    $latitude = $_GET['latitude'];
    }
if (isset($_GET['longitude'])) {
    $longitude = $_GET['longitude'];
    }

    if (isset($_GET['address'])) {
    $address = $_GET['address'];
    }

    $in_date = $obj->dateformatindia($obj->getvalfield("attendance_entry", "attendance_date", "empid='$empid' and attendance_date='$attendance_date'"));
    $in_time = $obj->getvalfield("attendance_entry", "attendance_in_time", "empid='$empid' and attendance_date='$attendance_date'");
    $count_in = $obj->getvalfield("attendance_entry", "count(attendance_in_time)", "empid='$empid' and attendance_date='$attendance_date' and attendance_in_time != '00:00:00'");
   $out_date = $obj->dateformatindia($obj->getvalfield("attendance_entry", "attendance_date", "empid='$empid' and attendance_date='$attendance_date'"));
   $out_time = $obj->getvalfield("attendance_entry", "attendance_out_time", "empid='$empid' and attendance_date='$attendance_date'");
   $count_out = $obj->getvalfield("attendance_entry", "count(attendance_out_time)", "empid='$empid' and attendance_date='$attendance_date' and attendance_out_time != '00:00:00'");
    $indatetime = $in_date.' / '.$in_time;
    $outdatetime = $out_date.' / '.$out_time;

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
        <div class="setting-page" style="    background: #015c3d0f; border-radius: 0px 0px 65px 65px;">
            <div class="container">
                <div class="row ">
                    <div class="col s12 center">
                        <div class="section-title">
                            <h1>Date</h1>
                            <span class="theme-secondary-color" style="color: black;"><?php echo date('d-m-Y'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row" style="margin-top: 50px;">
                <div class="input-field col s12 m12 l12">
                    <center>
                        <?php if($count_in == 0){ ?>
                        <button style="background-color: #015c3d;width:80%;border-radius: 20px;" class="btn btn-block" onclick="set_attandance_in();" value="IN" id="status_in">IN</button>
                        <?php }else{
                            ?>

                            <button style="background-color: #015c3d;width:80%;border-radius: 20px;" class="btn btn-block">In Time : <?php echo $indatetime; ?></button>
                            

                      <?php  } ?>
                    </center>
                </div>
                <div class="input-field col s12 m12 l12">
                    <center>
                        <?php if($count_out == 0){ ?>
                        <button style="background-color: #830826;width:80%;border-radius: 20px;margin-top:20px;" class="btn btn-block" onclick="set_attandance_out();" value="OUT" id="status_out">OUT</button>
                        <?php }else{
                            ?>

                            <button style="background-color: #830826;width:80%;border-radius: 20px;" class="btn btn-block">Out Time : <?php echo $outdatetime; ?></button>
                            

                      <?php  } ?>
                    </center>
                </div>
            </div>

        </div>
    </div>
    <!-- END CONTENT -->



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

    <script type="text/javascript">
        function set_attandance_in()
        {
            
            var status_in = document.getElementById('status_in').value;
            var empid = '<?php echo $empid; ?>';
            var longitude = '<?php echo $longitude; ?>';
            var latitude = '<?php echo $latitude; ?>';
            var address = '<?php echo $address; ?>';
            var status_out = '';
            

            jQuery.ajax({
                    method: "POST",
                    url: 'letlong_attandance.php',
                    data: 'status_in=' + status_in + "&empid=" + empid + '&status_out=' + status_out + '&longitude=' + longitude + '&latitude=' + latitude + '&address=' + address,
                    dataType: 'html',
                    success: function(data) {
                        //console.log(data);
                         //alert(data);
                        location.reload();
                    }
                });
        }

        function set_attandance_out()
        {   
            var status_in = '';
            var status_out = document.getElementById('status_out').value;
            var empid = '<?php echo $empid; ?>';
            var longitude = '<?php echo $longitude; ?>';
            var latitude = '<?php echo $latitude; ?>';
            var address = '<?php echo $address; ?>';

            jQuery.ajax({
                    method: "POST",
                    url: 'letlong_attandance.php',
                    data: 'status_out=' + status_out + "&empid=" + empid + '&status_in=' + status_in + '&longitude=' + longitude + '&latitude=' + latitude + '&address=' + address,
                    dataType: 'html',
                    success: function(data) {
                        //console.log(data);
                        // alert(data);
                        location.reload();
                    }
                });
        }
    </script>
</body>

</html>