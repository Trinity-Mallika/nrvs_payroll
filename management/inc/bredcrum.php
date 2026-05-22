
<?php

$chkmenu = $obj->check_menuname($pagename, $loginid);
if ($chkmenu > 0 ||  $_SESSION['usertype'] == 'super_management') {
} else {
    echo '
    <style>
        .access-denied-box {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #fff3f3;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.2);
            text-align: center;
        }
    
        .access-denied-box .icon {
            font-size: 60px;
            color: red;
            margin-bottom: 20px;
            animation: pulse 1.2s infinite;
        }
    
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.6; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
    
    <div class="access-denied-box">
   <div class="icon"><i class="ri-error-warning-fill"></i></div>
        <h2 class="text-danger">Access Denied</h2>
        <p class="text-dark">You don\'t have the privilege to access this page.</p>
        <a href="dashboard.php" class="btn btn-primary mt-3">Return to Home</a>
    </div>';

    die;
} ?>