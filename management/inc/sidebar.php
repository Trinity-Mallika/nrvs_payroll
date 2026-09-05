<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="dashboard.php" class="logo logo-dark">
            <span class="logo-sm text-white">
                <h1 class="fs-15 text-white">Tinity <br> Payroll</h1>
            </span>
            <span class="logo-lg text-white">

                <h1 class="text-white">Tinity Payroll</h1>
            </span>
        </a>
        <!-- Light Logo-->
        <a href="dashboard.php" class="logo logo-light mt-3">
            <span class="logo-sm  text-white">
                <h3 class="fs-15 text-white">Trinity <br> Payroll</h3>
            </span>
            <span class="logo-lg text-white">
                <h3 class="text-white">Trinity Payroll</h3>
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid mb-5 mt-3 border-top">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav mt-4" id="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link menu-link" href="dashboard.php">
                        <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                    </a>
                </li>
                <?php
                $chkmenu = $obj->check_menuname("unit_master.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a href="unit_master.php" class="nav-link">
                        <i class="ri-building-2-line"></i><span>Unit Master</span> </a>
                </li>
                <?php }
                $chkmenu = $obj->check_menuname("user_master.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a href="user_master.php" class="nav-link">
                        <i class="ri-user-3-line"></i></i><span>User Master</span> </a>
                </li>
                <?php }
                $chkmenu = $obj->check_menuname("salary_apr.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="salary_apr.php">
                        <i class="ri-calendar-check-line"></i> <span>Salary Approve</span>
                    </a>
                </li>
                <?php }
                $chkmenu = $obj->check_menuname("att_summary_report.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="att_summary_report.php">
                        <i class="ri-calendar-check-line"></i> <span>Attendance Summary</span>
                    </a>
                </li>
                <?php }
                $chkmenu = $obj->check_menuname("manual_att_report.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="manual_att_report.php">
                        <i class="ri-edit-box-line"></i> <span>Manual Attendance Entry Report</span>
                    </a>
                </li>
                <?php }
                $chkmenu = $obj->check_menuname("overtime_att_report.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="overtime_att_report.php">
                        <i class="ri-time-line"></i> <span>Employee Reward Report</span>
                    </a>
                </li>
                <?php }
                $chkmenu = $obj->check_menuname("monthly_salary_cost.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="monthly_salary_cost.php">
                        <i class="ri-money-rupee-circle-line"></i> <span>Monthly Salary Cost</span>
                    </a>
                </li>
                <?php }
                $chkmenu = $obj->check_menuname("leave_balance_report.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="leave_balance_report.php">
                        <i class="ri-calendar-event-line"></i> <span>Leave Balance Report</span>
                    </a>
                </li>
                <?php }
                $chkmenu = $obj->check_menuname("emp_turnover_report.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="emp_turnover_report.php">
                        <i class="ri-user-unfollow-line"></i> <span>Employee Turnover Report</span>
                    </a>
                </li>
                <?php }
                $chkmenu = $obj->check_menuname("department_wise_manpower.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="department_wise_manpower.php">
                        <i class="ri-team-line"></i> <span>Department Wise Manpower</span>
                    </a>
                </li>
                <?php }
                $chkmenu = $obj->check_menuname("month_wise_salary_variance.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="month_wise_salary_variance.php">
                        <i class="ri-bar-chart-line"></i><span>Month Wise Salary Variance</span>
                    </a>
                </li>
                <?php }
                $chkmenu = $obj->check_menuname("compliance_summary.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="compliance_summary.php">
                        <i class="ri-file-shield-line"></i><span>Compliance Summary (PF / ESIC)</span>
                    </a>
                </li>
                <?php }
                $chkmenu = $obj->check_menuname("hold_salary_report.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="hold_salary_report.php">
                        <i class="ri-lock-2-line"></i><span>Hold Salary Report</span>
                    </a>
                </li>

                <?php }
                $chkmenu = $obj->check_menuname("biomatric_manual_count_report.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="biomatric_manual_count_report.php">
                        <i class="ri-user-unfollow-line"></i> <span>Biomatric VS Manual Attendance</span>
                    </a>
                </li>
                <?php }
                $chkmenu = $obj->check_menuname("emp_promotion.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="emp_promotion.php">
                        <i class="ri-user-unfollow-line"></i> <span>Emp Promotion And Increment Report</span>
                    </a>
                </li>
                <?php }
                    $chkmenu = $obj->check_menuname("unit_transfer.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="unit_transfer.php">
                        <i class="ri-user-unfollow-line"></i> <span>Emp Transfer Report</span>
                    </a>
                </li>
                <?php } 
                $chkmenu = $obj->check_menuname("employee_leave_ledger.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="employee_leave_ledger.php">
                        <i class="ri-user-unfollow-line"></i> <span>Emp Leave Ledger</span>
                    </a>
                </li>
                <?php }
                
                $chkmenu = $obj->check_menuname("change_password.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="change_password.php">
                        <i class="ri-lock-password-line"></i> <span>Change Password</span>
                    </a>
                </li>

                <?php }
                $chkmenu = $obj->check_menuname("user_privilage.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="user_privilage.php">
                        <i class="ri-shield-user-line"></i> <span>HRMS Privillage</span>
                    </a>
                </li>
                <?php }
                $chkmenu = $obj->check_menuname("mngmt_privilege.php", $loginid);
                if ($chkmenu > 0 || $_SESSION['usertype'] == 'super_management') {
                ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="mngmt_privilege.php">
                        <i class="ri-admin-line"></i> <span>Management Privillage</span>
                    </a>
                </li>
                <?php }  ?>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>