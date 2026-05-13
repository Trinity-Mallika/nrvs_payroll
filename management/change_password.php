<?php
include("../adminsession.php");

$pagename = "change_password.php";
$title = "Change Password";
$tblname = "user";
$module = "Change Password";
$submodule = "Change Password";

$action = isset($_GET['action']) ? addslashes(trim($_GET['action'])) : "";

if (isset($_POST['sub'])) {
    $oldpass = $_POST['oldpass'];
    $newpass = $_POST['newpass'];
    $confirmpass = $_POST['confirmpass'];
    $loginid = $_SESSION['userid'];

    $where = array('password' => $oldpass, 'userid' => $loginid);
    $res = $obj->count_method($tblname, $where);

    if ($res != 0) {
        if ($newpass == $confirmpass) {
            $where = array('userid' => $loginid);
            $fields = array('password' => $newpass);
            $sql_get = $obj->update_record($tblname, $where, $fields);
            $action = 2;
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                handlePasswordChange();
            });
        </script>";
        } else {
            echo "Password Not Matched";
        }
    } else {
        echo "<script>alert('Wrong Password')</script>";
    }
}
?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?> | Infosol Systems LTD</title>
    <?php include('inc/css.php') ?>

    <script>
        function checkOldPass() {
            var oldpass = document.getElementById("oldpass").value;
            if (navigator.appName == "Microsoft Internet Explorer")
                obj3 = new ActiveXObject("Msxml2.XMLHTTP");
            else
                obj3 = new XMLHttpRequest();
            obj3.open("post", "check_old_pass.php?" + oldpass, true);
            obj3.send(null);
            obj3.onreadystatechange = function() {
                if (obj3.readyState == 4) {
                    var idname = obj3.responseText;
                    document.getElementById('msg1').innerHTML = idname;
                }
            }
        }

        function checkPassEqual() {
            var newpass = document.getElementById("newpass").value;
            var confirmpass = document.getElementById("confirmpass").value;
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

<body>
    <?php include('inc/header.php') ?>
    <?php include('inc/sidebar.php') ?>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?php include('inc/bredcrum.php') ?>

                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post">
                                    <div class="row g-2 mb-4 mt-3">
                                        <div class="col-lg-2">
                                            <label for="oldpasswordInput" class="form-label mt-2">Old Password*</label>
                                        </div>
                                        <div class="col-lg-5">
                                            <div>
                                                <input type="password" class="form-control" name="oldpass" id="oldpass" onChange="checkOldPass()" placeholder="Enter Old password">
                                            </div>
                                            <small id="msg1"></small>
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-4">
                                        <div class="col-lg-2">
                                            <label for="newpass" class="form-label mt-2">New Password*</label>
                                        </div>
                                        <div class="col-lg-5">
                                            <div>
                                                <input type="password" class="form-control" name="newpass" id="newpass" maxlength="12" placeholder="New Password" onKeyUp="checkPassEqual()">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-5">
                                        <div class="col-lg-2">
                                            <label for="confirmpass" class="form-label mt-2">Confirm Password*</label>
                                        </div>
                                        <div class="col-lg-5">
                                            <div>
                                                <input type="password" class="form-control" name="confirmpass" id="confirmpass" placeholder="Confirm password" onKeyUp="checkPassEqual()" autocomplete="off">
                                            </div>
                                            <small id="msg2"></small>
                                        </div>
                                    </div>

                                    <div class="row g-2">
                                        <div class="col-lg-7">
                                            <div class="text-end">
                                                <button type="submit" name="sub" class="btn btn-success" id="change_btn" onClick="return checkinputmaster('oldpass,newpass,confirmpass')">Change Password</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('inc/footer.php') ?>
    </div>
    <?php include('inc/js.php') ?>
    <script>
        function handlePasswordChange() {
            Swal.fire({
                title: 'Password changed successfully!',
                text: "Do you want to continue or re-login?",
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'Continue',
                cancelButtonText: 'Re-login'
            }).then((result) => {
                if (!result.isConfirmed) {
                    window.location.href = "logout.php";
                }
            });
        }
    </script>
</body>

</html>