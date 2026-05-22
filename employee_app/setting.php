<?php include("appsession.php");

if (isset($_SESSION['emp_id'])) {
  $emp_id = $_SESSION['emp_id'];
  $where = array('emp_id' => $emp_id);
  $app_row = $obj->select_record('employee_master', $where);
  $emp_name = $app_row['first_name'];
  $mobile = $app_row['mobile_no'];
  $password = $app_row['password'];
  $address = $app_row['permanent_address'];
} else
  $userid = "";

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
    <div class="setting-page">
      <div class="container">
        <div class="row ">
          <div class="col s12 m12 l12 ">
            <div class="section-title">
              <span class="theme-secondary-color" style="color: black;">MY</span> PROFILE
            </div>
          </div>
        </div>

        <form action="setting.php" method="get">

          <div class="row">
            <div class="col s12 m12 l12 ">
              <div class="">
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="input-field col s12 m12 l12 ">
              <input value="<?php echo $emp_name; ?>" id="emp_name" name="emp_name" type="text" class="validate"
                readonly>
              <label for="emp_name">Full Name</label>
            </div>
          </div>

          <div class="row">
            <div class="input-field col s12 m12 l12 ">
              <input value="<?php echo $password; ?>" id="password" name="password" type="password" class="validate"
                readonly>
              <label for="password">Password</label>
            </div>
          </div>

          <div class="row">
            <div class="input-field col s12 m12 l12 ">
              <input value="<?php echo $mobile; ?>" id="mobile" name='mobile' type="text" class="validate" readonly>
              <label for="mobile">Mobile Number</label>
            </div>
          </div>

          <div class="row">
            <div class="input-field col s12 m12 l12 ">
              <textarea id="address" name="address" class="materialize-textarea" readonly></textarea>
              <label for="address">Address</label>
              <script type="text/javascript">
                document.getElementById('address').value = '<?php echo $address; ?>';
              </script>
            </div>
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
      <a class=" small active" target="_self" href="setting.php" style="line-height: 1;font-size: 11px; ">
        <i class="material-icons" style="display: block;padding-top: 5px;line-height: 1.1;">people</i> Profile
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
</body>

<!-- Mirrored from resptheme.com/tf/asiapp/setting.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 23 Apr 2020 06:31:43 GMT -->

</html>