<?php include("../action.php");
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$username = "";
if (isset($_COOKIE['TA_mobile_no']))
  $mobile_no = $obj->test_input($_COOKIE['TA_mobile_no']);
else
  $mobile_no = "";

if (isset($_COOKIE['TA_password']))
  $password = $obj->test_input($_COOKIE['TA_password']);
else
  $password = "";

if (isset($_POST['submit'])) {
  $username = $obj->test_input($_POST['username']);
  $password = $obj->test_input($_POST['password']);

  if ($username != "" && $password != "") {


    $count = $obj->getvalfield(
      "employee_master",
      "count(*)",
      "(mobile_no='$username' OR emp_code='$username') AND password='$password'"
    );

    $emp_id = $obj->getvalfield(
      "employee_master",
      "emp_id",
      "(mobile_no='$username' OR emp_code='$username') AND password='$password'"
    );



    if ($count > 0) {
      $_SESSION['emp_id'] = $emp_id;
      echo "<script>location='dashboard.php'</script>";
    } else {
      echo "<script>location='index.php?msg=faild'</script>";
    }
  } else
    echo "<script>location='index.php?msg=blank'</script>";
}

$error = "";
if (isset($_GET['msg'])) {
  $msg = $obj->test_input($_GET['msg']);
  if ($msg == 'faild')
    $error = "Invalid Userid or Password";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Employee App</title>
  <link rel="icon" type="image/x-icon" href="image/favicon1.png">
  <link rel="shortcut icon" type="image/png" href="image/favicon1.png">
  <link rel="apple-touch-icon" href="image/favicon1.png">
  <link rel="apple-touch-icon" sizes="152x152" href="image/favicon1.png">
  <link rel="apple-touch-icon" sizes="180x180" href="image/favicon1.png">
  <link rel="apple-touch-icon" sizes="167x167" href="image/favicon1.png">
  <link rel="android-touch-icon" href="image/favicon1.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-touch-fullscreen" content="yes">
  <meta name="HandheldFriendly" content="True">
  <!-- <link rel="icon" href="favicon.ico" type="image/x-icon"> -->
  <!-- CSS  -->
  <link rel="stylesheet" href="lib/font-awesome/web-fonts-with-css/css/fontawesome-all.css">
  <link rel="stylesheet" href="css/materialize.min.css">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/style.css">
  <!-- materialize icon -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

<!-- END PRELOADING -->
<style type="text/css">
  body {
    background: #f7f7f7;
    background: linear-gradient(to bottom, #f7f7f7, #f1f2fa, rgb(36, 43, 107));
  }

  body input[type=text]:not(.browser-default),
  input[type=password]:not(.browser-default) {
    background-color: white;
    border-radius: 10px;
    padding-left: 5px;
    font-size: 1.3rem;
    margin-top: 10px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  }

  body input[type="text"]:focus:not(.browser-default):not([readonly])+label,
  body input[type="password"]:focus:not(.browser-default):not([readonly])+label {
    color: #70826d;
  }

  label {
    padding-left: 10px;
    padding-top: 8px;
  }

  body a {
    color: white;
  }

  body .btn {
    background-color: #15295f;
    border-radius: 5px;
  }
</style>
</head>

<body id="homepage">
  <!-- BEGIN PRELOADING -->
  <div class="preloading" style="background:white;">
    <div class="wrap-preload">
      <div class="cssload-loader" style="background: #15295f;"></div>
    </div>
  </div>
  <!-- END PRELOADING -->
  <!-- HEADER -->

  <!-- CONTENT -->
  <div id="page-content">
    <div class="login-form">
      <div class="container">
        <div class="row">
          <div class="col s12">
            <!-- <div class="section-title">
              <img src="image/logo.jpeg" style="width:300px;">
            </div> -->
          </div>
          <div class="col s12">
            <div class="section-title" style="padding-top:30px;">
              <span class="theme-secondary-color" style="color:#15295f"> EMPLOYEE ATTENDANCE</span>
            </div>
          </div>
        </div>
        <div class="row">
          <h5 style="color:#15295f;margin-top:60px;">Login Account</h5>
          <form class="col s12" action="" method="post">
            <div class="row">
              <div class="input-field col s12 m6 l4 offset-m3 offset-l4">
                <input id="username" type="number" name="username" onkeypress="validate(event)" maxlength="10"
                  value="<?php echo $username; ?>" autocomplete="off" onkeyup="allowNumbersOnly(this)" type="text"
                  maxlength="10">
                <label for="username">Mobile No OR Employee Code</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12 m6 l4 offset-m3 offset-l4">
                <input id="password" type="password" name="password" value="<?php echo $password; ?>"
                  autocomplete="off">
                <label for="password">Password</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12 m6 l4 offset-m3 offset-l4 center">
                <input class="btn" value="LOG IN" type="submit" name="submit" id="submit"
                  onClick="return checkinputmaster('username,password')">
              </div>
              <!-- <a href="privacypolicy.php">
                <div class="input-field col s12 m6 l4 offset-m3 offset-l4 center">
                  <input class="btn" value="Privacy Policy">
                </div>
              </a> -->
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- END CONTENT-->

  <!-- SUBSCRIBE -->
  <!-- Script -->
  <script src="js/jquery.min.js"></script>
  <script src="js/materialize.min.js"></script>
  <script src="js/commonfun.js"></script>
  <!-- Custom script -->
  <script src="js/sweetalert.min.js"></script>
  <script>
    /*=================== PRELOADER ===================*/
    $(window).on('load', function() {
      $(".preloading").fadeOut("slow");
    });
  </script>
  <?php
  $msg = "";
  if (isset($_GET['msg'])) {
    $msg = $obj->test_input($_GET['msg']);
    if ($msg == 'faild') { ?>
      <script type="text/javascript">
        swal({
          title: "Invalid Login Details!",
          text: "You entered invalid member code or password",
          icon: "error",
          button: "Try Again",
        });
      </script>
    <?php
    }
    if ($msg == 'invalid') { ?>
      <script type="text/javascript">
        swal({
          title: "Invalid Login Details!",
          text: "You entered invalid member code or password",
          icon: "error",
          button: "Try Again",
        });
      </script>
    <?php
    }
    if ($msg == 'blank') { ?>
      <script type="text/javascript">
        swal({
          title: "Invalid Login Details!",
          text: "Member code or Password is blank",
          icon: "error",
          button: "Try Again",
        });
      </script>
    <?php
    }
    if ($msg == 'logout') { ?>
      <script type="text/javascript">
        swal({
          title: "Successfully LogOut!",
          text: "",
          icon: "success",
          button: "OK",
        });
      </script>
  <?php

    }
  }
  ?>
  <script>
    function validate(evt) {
      var theEvent = evt || window.event;

      // Handle paste
      if (theEvent.type === 'paste') {
        key = event.clipboardData.getData('text/plain');
      } else {
        // Handle key press
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
      }
      var regex = /[0-9]|\./;
      if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
      }
    }

    function allowNumbersOnly(input) {
      // Remove any non-numeric characters
      input.value = input.value.replace(/[^0-9]/g, '');
    }
  </script>
</body>

</html>