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
                <h1 class="fs-15 text-white">Trinity <br> Payroll</h1>
            </span>
            <span class="logo-lg text-white">
                <h1 class="text-white">Trinity Payroll</h1>
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid mb-5 mt-3 border-top">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link menu-link" href="dashboard.php">
                        <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarMasters" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMasters">
                        <i class="ri-folder-2-line"></i> <span data-key="t-Masters">Master</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarMasters">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="unit_master.php" class="nav-link"> Unit Master</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="company-setting.php" class="nav-link">Company Setting </a>
                            </li> -->
                            <li class="nav-item">
                                <a href="session_master.php" class="nav-link">Financial Year </a>
                            </li>
                            <li class="nav-item">
                                <a href="shift_master.php" class="nav-link"> Shift Master</a>
                            </li>
                            <li class="nav-item">
                                <a href="holiday_entry.php" class="nav-link"> Holiday Entry</a>
                            </li>
                            <li class="nav-item">
                                <a href="division_master.php" class="nav-link">Division Master </a>
                            </li>
                            <li class="nav-item">
                                <a href="subdivision_master.php" class="nav-link"> Sub Division Master</a>
                            </li>

                            <li class="nav-item">
                                <a href="department_master.php" class="nav-link"> Department Master</a>
                            </li>
                            <li class="nav-item">
                                <a href="grade_master.php" class="nav-link"> Grade Master</a>
                            </li>
                            <li class="nav-item">
                                <a href="designation_master.php" class="nav-link"> Designation Master</a>
                            </li>

                            <li class="nav-item">
                                <a href="user_master.php" class="nav-link"> User Master</a>
                            </li>

                            <li class="nav-item">
                                <a href="document_master.php" class="nav-link">Document Master </a>
                            </li>
                            <li class="nav-item">
                                <a href="bank_master.php" class="nav-link">Bank Master</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#Employee_module" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="Employee_module">
                        <i class="ri-user-2-fill"></i> <span data-key="t-Masters">Employee </span>
                    </a>
                    <div class="collapse menu-dropdown" id="Employee_module">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="employee_master.php" class="nav-link"> Employee Entry</a>
                            </li>
                            <li class="nav-item">
                                <a href="excel_upload.php" class="nav-link">Excel Upload Employee's</a>
                            </li>
                            <li class="nav-item">
                                <a href="emp_list.php" class="nav-link">Employee Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="emi_setting.php" class="nav-link">EMI Setting</a>
                            </li>
                            <li class="nav-item">
                                <a href="emp_separation.php" class="nav-link">Employee Exit / Separation</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#Attendance" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="Attendance">
                        <i class="ri-user-follow-fill"></i> <span data-key="t-Masters">Attendance</span>
                    </a>
                    <div class="collapse menu-dropdown" id="Attendance">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="employee_wise_attendance.php" class="nav-link">Employee Wise Attendance</a>
                            </li>

                            <li class="nav-item">
                                <a href="day_wise_attendence_report.php" class="nav-link">Day Wise Attendance Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="late_in_report.php" class="nav-link">Late In Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="month_wise_attendance_report.php" class="nav-link">Month Wise Attendance Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="excel_att_upload.php" class="nav-link">Excel Upload Employee's Attendance</a>
                            </li>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#salary_structure" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="salary_structure">
                        <i class="ri-wallet-fill"></i> <span data-key="t-Masters">Salary Generate</span>
                    </a>
                    <div class="collapse menu-dropdown" id="salary_structure">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="emp_overtime.php" class="nav-link">Overtime Entry</a>
                            </li>
                            <li class="nav-item">
                                <a href="deduction_entry.php" class="nav-link">Deduction Entry</a>
                            </li>
                            <li class="nav-item">
                                <a href="salary_generate_detail.php" class="nav-link">Salary Generate</a>
                            </li>
                            <li class="nav-item">
                                <a href="salary_generate.php" class="nav-link">Bulk Salary Generate</a>
                            </li>
                            <li class="nav-item">
                                <a href="salary_generate_report.php" class="nav-link">Salary Generate Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="salary_hold_report.php" class="nav-link">Salary Hold Report</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="salary_sheet.php" class="nav-link">Salary Sheet</a>
                            </li> -->
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#week_off_module" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="week_off_module">
                        <i class="ri-home-gear-line"></i> <span data-key="t-Masters"> Settings</span>
                    </a>
                    <div class="collapse menu-dropdown" id="week_off_module">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="c_off_setting.php" class="nav-link">Monthly Leave Setting</a>
                            </li>
                            <li class="nav-item">
                                <a href="weekly_off_settings.php" class="nav-link"> Weekly Off Setting</a>
                            </li>
                            <li class="nav-item">
                                <a href="salary_slab_setting.php" class="nav-link">Salary Slab Setting</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link menu-link" href="#Attendance" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="Attendance">
                        <i class="ri-calendar-line"></i> <span data-key="t-Masters">Attendance</span>
                    </a>
                    <div class="collapse menu-dropdown" id="Attendance">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="day_wise_attendance.php" class="nav-link"> Day Wise Attendance</a>
                            </li>
                            <li class="nav-item">
                                <a href="manual_attendance.php" class="nav-link">Manual Attendance Entry</a>
                            </li>
                            <li class="nav-item">
                                <a href="month_wise_attendance_report.php" class="nav-link">Month Wise Attendance </a>
                            </li>
                            <li class="nav-item">
                                <a href="emp_wise_attendance_report.php" class="nav-link">Employee Wise Attendance </a>
                            </li>
                            <li class="nav-item">
                                <a href="employee_report.php" class="nav-link">Employee Report </a>
                            </li>
                        </ul>
                    </div>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="upload_attachment.php">
                        <i class="ri-lock-password-line"></i> <span>Upload Attachment</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="change_password.php">
                        <i class="ri-lock-password-line"></i> <span>Change Password</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>