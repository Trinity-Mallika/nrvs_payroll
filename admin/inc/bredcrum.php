
<?php
$exclude_pages = ['page_privellege.php', 'mngmt_page_privilege.php', 'tds_slabs.php', 'change_emp_code_excel.php', 'emp_promotion_list.php', 'leave_apply_list.php', 'change_password.php', 'category_master.php', 'emp_multi_att_report_copy.php', 'emp_bank_details.php', 'api_call.php','misspunch_report.php','employee_bank_details_excel.php','att_status_report.php','emp_salary_update.php','emp_leave_opb.php','excel_emp_opb_upload.php','emp_leave_monthly.php','emp_coff_report.php'];
$chkmenu = $obj->check_menuname($pagename, $loginid);
if ($chkmenu > 0 || in_array($pagename, $exclude_pages)) {
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