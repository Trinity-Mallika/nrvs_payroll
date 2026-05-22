<?php
include("appsession.php");

$title = "Change Password";
$pagename = "change-password.php";
$tblname = "employee_master";
$module = "Change Password";
$submodule = "Change Password";
if (isset($_GET['action']))
    $action = addslashes(trim($_GET['action']));
else
    $action = "";



if (isset($_POST['sub'])) {
    $oldpass = $_POST['oldpass'];
    $newpass = $_POST['newpass'];
    $confirmpass = $_POST['confirmpass'];
    $loginid = $_SESSION['emp_id'];
    $where = array('password' => $oldpass, 'emp_id' => $loginid);
    $res = $obj->count_method($tblname, $where);
    if ($res != 0) {
        if ($newpass == $confirmpass) {   //echo ("hii"); die;
            //$sql_data = mysqli   2qw3_query($con,"SET NAMES utf8");
            $where = array('emp_id' => $loginid);
            $fields = array('password' => $newpass);
            //print_r($fields);
            $sql_get = $obj->update_record("employee_master", $where, $fields);
            $action = 2;
            echo "<script>location='$pagename?action=$action'</script>";
        } else
            echo "Password Not Matched";
    } else
        echo "<script>alert('Wrong Password')</script>";
    echo "
   <script>alert('Password Changed Successfully')</script>

    <script>
closeframe();
function closeframe()
{
parent.location='change-password.php';
parent.jQuery.fancybox.close()
}
</script>";
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

    <script>
        function checkOldPass() {
            var oldpass = document.getElementById("oldpass").value;
            // test = "xyz";
            if (navigator.appName == "Microsoft Internet Explorer")
                obj3 = new ActiveXObject("Msxml2.XMLHTTP");
            else
                obj3 = new XMLHttpRequest();
            obj3.open("post", "check_old_pass.php?" + oldpass, true);
            obj3.send(null);
            obj3.onreadystatechange = function() {
                if (obj3.readyState == 4) {
                    var idname = obj3.responseText;
                    const button = document.getElementById('myButton');
                    if (idname == "<span style='color:red'>Old password is wrong</span>") {


                        // Disable the button
                        button.disabled = true;
                    } else {
                        button.disabled = false;
                    }
                    //document.getElementById('msg').value = idname;
                    document.getElementById('msg1').innerHTML = idname;
                    document.getElementById('msg1').innerHTML = idname;
                }
            }
        }

        function checkPassEqual() {
            var newpass = document.getElementById("newpass").value;
            var confirmpass = document.getElementById("confirmpass").value;
            //alert(newpass);
            //alert(confirmpass);
            if (newpass != "" && confirmpass != "") {
                if (newpass == confirmpass) {

                    document.getElementById('msg2').innerHTML = "Password matched";
                    document.getElementById('msg2').style.color = "green";
                } else {

                    document.getElementById('msg2').innerHTML = "Password not matched";
                    document.getElementById('msg2').style.color = "red";
                }
            }
        }
    </script>

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
                            <span class="theme-secondary-color" style="color: black;">Change</span> Password
                        </div>
                    </div>
                </div>

                <form action="" method="post">
                    <div class="row">
                        <div class="input-field col s12 m12 l12 ">
                            <input value="" id="oldpass" name="oldpass" type="password" class="validate"
                                onChange="checkOldPass()">
                            <label for="old password">Old Password</label>
                            <small id="msg1"></small>
                        </div>
                    </div>


                    <div class="row">
                        <div class="input-field col s12 m12 l12 ">
                            <input value="" id="newpass" name="newpass" type="password" class="validate" maxlength="12"
                                onKeyUp="checkPassEqual()">
                            <label for="password">New Password</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m12 l12 ">
                            <input value="" id="confirmpass" name="confirmpass" type="password" class="validate"
                                autocomplete="off" onKeyUp="checkPassEqual()">
                            <label for="password">Confirm Password</label>
                            <small id="msg2"></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class=" col s12 m12 l12">
                            <button type="submit" id="myButton" name="sub" class="btn btn-block"
                                onClick="return checkinputmaster('oldpass,newpass,confirmpass')"
                                style="background-color: #015c3d;width:100%;border-radius: 20px;">Change</button>

                        </div>
                    </div>

            </div>
        </div>
    </div>
    <!-- END CONTENT -->

    <ul id="tabs" class="tabs" style="position: fixed; bottom: 0px;border-top:1px solid #ddd;z-index: 999;">
        <li class="tab">
            <a class=" small active" target="_self" href="change-password.php" style="line-height: 1;font-size: 11px; ">
                <i class="material-icons" style="display: block;padding-top: 5px;line-height: 1.1;">create</i> Password

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let action = '<?php echo $action ?>';
        if( action == 2){
            Swal.fire({
            title: "Password Changed Successfully!",
            icon: "success",
            draggable: true
            });
        }
        
    </script>
</body>

</html>