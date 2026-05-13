<?php
include('../action.php');

// Cookie
$userid = $_COOKIE['attendance_userid'] ?? "";
$pass = $_COOKIE['attendance_pass'] ?? "";

/* ================= AJAX LOGIN ================= */
if (isset($_POST['ajax_user'])) {

    $username = $obj->test_input($_POST['ajax_user']);
    $password = $obj->test_input($_POST['password']);
    $password = html_entity_decode($password);

   if ($username != "" && $password != "") {   
        $count = $obj->login_method_app2("employee_master",$username,$password);
        if ($count > 0) {
	        $session_data = $obj->session_method_app("employee_master", $username, $password);
          
            $_SESSION['emp_id'] = $session_data['emp_id'];

            setcookie("attendance_userid", $username, time() + (86400 * 7), "/");
            setcookie("attendance_pass", $password, time() + (86400 * 7), "/");

            echo 1; // success

        } else {
            echo 2; // invalid
        }
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            margin: 0;
            background: linear-gradient(180deg, #667eea, #764ba2);
        }

        .app-container {
            min-height: 100vh;
            padding: 25px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .logo {
            margin-top: 20px;
            width: 120px;
            height: auto;
            margin-bottom: 5px;
        }

        .app-header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }

        .form-box {
            background: #fff;
            border-radius: 25px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
        }

        .btn-login {
            height: 50px;
            border-radius: 12px;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <div class="app-container">
        <div class="app-header">
            <img src="img/nrlogo.jpg" alt="NR Logo" class="logo mb-2" width="130px" height="100px">
            <h1><b>NR GROUP</b> </h1>
            <p>Employee Login</p>
        </div>
        <div class="form-box">
            <form onsubmit="return false;">
                <div class="mb-3">
                    <input type="text" id="user_id" class="form-control"
                        placeholder="Enter Employee Code"
                        value="<?= htmlspecialchars($userid) ?>">
                </div>
                <div class="mb-3">
                    <div class="input-group">
                        <input type="password" id="password" class="form-control" placeholder="Enter Password" value="<?= htmlspecialchars($pass) ?>">
                        <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer;">
                            <i id="eyeIcon" class="bi bi-eye"></i>
                        </span>
                    </div>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary btn-login" onclick="Login()">Login</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function Login() {

            let user = $('#user_id').val();
            let pass = $('#password').val();
          
            if (user == "") {
                Swal.fire('Error', 'Enter User ID', 'error');
                return;
            }

            if (pass == "") {
                Swal.fire('Error', 'Enter Password', 'error');
                return;
            }

            $.ajax({
                type: "POST",
                url: "",
                data: {
                    ajax_user: user,
                    password: pass
                },

                beforeSend: function() {
                    Swal.fire({
                        title: 'Please wait...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },

                success: function(res) {
 
                    if (res == 1) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Successful',
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            window.location = "dashboard.php";
                        });

                    } else {
                        Swal.fire('Error', 'Invalid User ID or Password', 'error');
                    }
                }
            });
        }

        function togglePassword() {
            let pass = document.getElementById("password");
            let icon = document.getElementById("eyeIcon");

            if (pass.type === "password") {
                pass.type = "text";
                icon.classList.replace("bi-eye", "bi-eye-slash");
            } else {
                pass.type = "password";
                icon.classList.replace("bi-eye-slash", "bi-eye");
            }
        }
    </script>

</body>

</html>