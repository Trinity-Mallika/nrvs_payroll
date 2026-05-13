<?php
include "appsession.php";

$pagename = 'change-password.php';
$title = 'Change Password';

$msg = "";
$msg_type = "";

if (isset($_POST['save'])) {

    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $emp_id = $_SESSION['emp_id'];

    $db_password = $obj->getvalfield("employee_master", "password", "emp_id='$emp_id'");

    if ($old_password != $db_password) {
        $msg = "Old Password is incorrect!";
        $msg_type = "error";
    } elseif ($new_password != $confirm_password) {
        $msg = "New Password & Confirm Password do not match!";
        $msg_type = "warning";
    } else {
        $obj->update_record("employee_master", ["emp_id" => $emp_id], ["password" => $new_password]);

        $msg = "Password changed successfully!";
        $msg_type = "success";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>NRVS Payroll</title>
    <!-- css links  files -->
    <?php include("inc/css-file.php"); ?>

</head>

<body class="dashboard">
    <section class="top-sec ">
        <?php include("inc/header.php"); ?>

        <div class="container">
            <form method="POST">
                <div class="card border-0 shadow-lg mb-3">
                    <div class="mb-3">
                        <label for="" class="form-label">Old Password</label>
                        <input type="password" class="form-control shadow-sm" id="old_password" name="old_password"
                            placeholder="Old Password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control shadow-sm" id="new_password" name="new_password" placeholder="New Password">
                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword('new_password', this)">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control shadow-sm" id="confirm_password" name="confirm_password"
                                placeholder="Confirm Password">
                            <span class="input-group-text" onclick="togglePassword('confirm_password', this)">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" name="save" class="btn btn-primary">Save</button>
                        </div>
                    </div>
            </form>

        </div>
    </section>

    <!-- js script files -->
    <?php include("inc/js-file.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if ($msg != "") { ?>
        <script>
            Swal.fire({
                icon: '<?= $msg_type ?>',
                title: '<?= $msg ?>',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                <?php if ($msg_type == "success") { ?>
                    location = '<?= $pagename ?>';
                <?php } ?>
            });
        </script>
    <?php } ?>

    <script>
        function togglePassword(fieldId, el) {
            let input = document.getElementById(fieldId);
            let icon = el.querySelector("i");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }
    </script>
</body>

</html>