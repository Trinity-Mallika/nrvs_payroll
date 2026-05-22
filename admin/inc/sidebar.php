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
        <a href="dashboard.php" class="logo logo-light mt-1">
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

    <div id="scrollbar" data-simplebar="init" class="h-100 simplebar-scrollable-y">
        <div class="container-fluid mb-5 border-top">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav" data-simplebar="init">
                <li class="nav-item">
                    <a class="nav-link menu-link" href="dashboard.php">
                        <i class="ri-dashboard-2-line">
                            <p>Dashboards</p>
                        </i>
                        <span>Dashboards</span>
                    </a>
                </li>
                <?php
                $master_chk = $obj->checkmenu("Master", $loginid);
                if ($master_chk != '0') {
                ?>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarMasters" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarMasters">
                            <i class="ri-apps-2-line">
                                <p>Master</p>
                            </i> <span data-key="t-Masters">Master</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarMasters">
                            <ul class="nav nav-sm flex-column">
                                <?php
                                $chkmenu = $obj->check_menuname("unit_master.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="unit_master.php" class="nav-link"> Unit Master</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("session_master.php", $loginid);

                                if ($chkmenu > 0) {
                                ?>
                                    <!-- <li class="nav-item">
                                        <a href="company-setting.php" class="nav-link">Company Setting </a>
                                    </li> -->
                                    <li class="nav-item">
                                        <a href="session_master.php" class="nav-link">Financial Year </a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("shift_master.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="shift_master.php" class="nav-link"> Shift Master</a>
                                    </li>
                                <?php }

                                $chkmenu = $obj->check_menuname("holiday_entry.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="holiday_entry.php" class="nav-link"> Holiday Entry</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("division_master.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="division_master.php" class="nav-link">Division Master </a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("subdivision_master.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="subdivision_master.php" class="nav-link"> Sub Division Master</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("department_master.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>


                                    <li class="nav-item">
                                        <a href="department_master.php" class="nav-link"> Department Master</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("grade_master.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="grade_master.php" class="nav-link"> Grade Master</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("designation_master.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="designation_master.php" class="nav-link"> Designation Master</a>
                                    </li>

                                <?php }
                                $chkmenu = $obj->check_menuname("user_master.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="user_master.php" class="nav-link"> User Master</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("document_master.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>

                                    <li class="nav-item">
                                        <a href="document_master.php" class="nav-link">Document Master </a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("bank_master.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="bank_master.php" class="nav-link">Bank Master</a>
                                    </li>

                                <?php }

                                ?>
                                <!-- <li class="nav-item">
                                    <a href="category_master.php" class="nav-link">Category Master</a>
                                </li> -->
                            </ul>
                        </div>
                    </li>
                <?php }
                $master_chk = $obj->checkmenu("Employee", $loginid);
                if ($master_chk != '0') {
                ?>


                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#Employee_module" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="Employee_module">
                            <i class="ri-account-circle-line">
                                <p>Employee</p>
                            </i> <span data-key="t-Masters">Employee </span>
                        </a>
                        <div class="collapse menu-dropdown" id="Employee_module">
                            <ul class="nav nav-sm flex-column">
                                <?php
                                $chkmenu = $obj->check_menuname("employee_master.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="employee_master.php" class="nav-link"> Employee Entry</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("excel_upload.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="excel_upload.php" class="nav-link">Excel Upload Employee's</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("employee_report.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="employee_report.php" class="nav-link">Employee Report</a>
                                    </li>
                                    <!-- <li class="nav-item">
                                <a href="emi_setting.php" class="nav-link">EMI Setting</a>
                            </li> -->
                                <?php }
                                $chkmenu = $obj->check_menuname("emp_separation.php", $loginid);

                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="emp_separation.php" class="nav-link">Employee Exit / Separation</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("on_duty.php", $loginid);

                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="on_duty.php" class="nav-link">On Duty</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("loan_advance.php", $loginid);

                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="loan_advance.php" class="nav-link">Loan Advance</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("unit_transfer.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="unit_transfer.php" class="nav-link">Employee Transfer</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("leave_apply.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="leave_apply.php" class="nav-link">Leave Application</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("emp_promotion.php", $loginid);

                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="emp_promotion.php" class="nav-link">Employee Promotion</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("emp_leave_opb.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="emp_leave_opb.php" class="nav-link">Emp Opening Leave</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("emp_leave_monthly.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="emp_leave_monthly.php" class="nav-link">Emp Monthly Leave</a>
                                    </li>
                                <?php } ?>

                                <li class="nav-item">
                                    <a href="emp_bank_details.php" class="nav-link">Emp Bank Details</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                <?php }
                $master_chk = $obj->checkmenu("Attendance", $loginid);
                if ($master_chk != '0') {
                ?>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#Attendance" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="Attendance">
                            <i class="ri-calendar-check-line">
                                <p>Attendance</p>
                            </i> <span data-key="t-Masters">Attendance</span>
                        </a>
                        <div class="collapse menu-dropdown" id="Attendance">
                            <ul class="nav nav-sm flex-column">
                                <?php
                                $chkmenu = $obj->check_menuname("employee_wise_attendance.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>

                                    <li class="nav-item">
                                        <a href="employee_wise_attendance.php" class="nav-link">Employee Wise Attendance</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("day_wise_attendence_report.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="day_wise_attendence_report.php" class="nav-link">Day Wise Attendance Report</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("late_in_report.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="late_in_report.php" class="nav-link">Late In Report</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("month_wise_attendance_report.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="month_wise_attendance_report.php" class="nav-link">Month Wise Attendance
                                            Report</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("month_wise_attendance_report.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="misspunch_report.php" class="nav-link">Incomplete/Misspunch Report</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("excel_att_upload.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="excel_att_upload.php" class="nav-link">Excel Upload Employee's Attendance</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("employee_attendence_report.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="employee_attendence_report.php" class="nav-link"> Employee's Attendance
                                            Report</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("att_status_report.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="att_status_report.php" class="nav-link">Attendance Status
                                            Report</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("emp_multi_att_report.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="emp_multi_att_report.php" class="nav-link"> Employee's Multiple Attendance
                                            Report</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("att_recall.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="att_recall.php" class="nav-link">Attendance Re-call</a>
                                    </li>
                                <?php } ?>
                                  <li class="nav-item">
                                        <a href="emp_coff_report.php" class="nav-link">Employee C Off Details</a>
                                    </li>
                            </ul>
                        </div>
                    </li>
                <?php }
                $master_chk = $obj->checkmenu("Salary Generate", $loginid);
                if ($master_chk != '0') {
                ?>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#salary_structure" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="salary_structure">
                            <i class=" ri-hand-coin-line">
                                <p>Salary <br> Generate</p>
                            </i> <span data-key="t-Masters">Salary Generate</span>
                        </a>
                        <div class="collapse menu-dropdown" id="salary_structure">
                            <ul class="nav nav-sm flex-column">
                                <?php
                                $chkmenu = $obj->check_menuname("emp_overtime.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="emp_overtime.php" class="nav-link">Overtime Entry</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("emp_salary_update.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="emp_salary_update.php" class="nav-link">Salary Update</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("deduction_entry.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="deduction_entry.php" class="nav-link">Deduction Entry</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("esic_excel.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="esic_excel.php" class="nav-link">ESIC Excel</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("pf_excel.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="pf_excel.php" class="nav-link">PF Excel</a>
                                    </li>

                                    <!-- <li class="nav-item">
                                <a href="pf_esic_upload.php" class="nav-link">PF ESIC Excel Upload</a>
                            </li> -->
                                <?php }
                                $chkmenu = $obj->check_menuname("salary_generate_detail.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="salary_generate_detail.php" class="nav-link">Salary Generate</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("salary_generate.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="salary_generate.php" class="nav-link">Bulk Salary Generate</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("salary_generate_report.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="salary_generate_report.php" class="nav-link">Salary Generate Report</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("additional_pay_master.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="additional_pay_master.php" class="nav-link">Additional Payment Master</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("salary_hold_report.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="salary_hold_report.php" class="nav-link">Salary Hold Report</a>
                                    </li>
                                <?php } ?>
                                <!-- <li class="nav-item">
                                <a href="salary_sheet.php" class="nav-link">Salary Sheet</a>
                            </li> -->
                            </ul>
                        </div>
                    </li>
                <?php }
                $master_chk = $obj->checkmenu("Settings", $loginid);
                if ($master_chk != '0') {
                ?>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#week_off_module" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="week_off_module">
                            <i class=" ri-settings-5-line">
                                <p>Settings</p>
                            </i> <span data-key="t-Masters">Settings</span>
                        </a>
                        <div class="collapse menu-dropdown" id="week_off_module">
                            <ul class="nav nav-sm flex-column">
                                <?php
                                $chkmenu = $obj->check_menuname("department_setting.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="department_setting.php" class="nav-link">Department Setting</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("c_off_setting.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="c_off_setting.php" class="nav-link">Monthly Leave Setting</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("weekly_off_settings.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="weekly_off_settings.php" class="nav-link"> Weekly Off Setting</a>
                                    </li>
                                <?php }
                                $chkmenu = $obj->check_menuname("salary_slab_setting.php", $loginid);
                                if ($chkmenu > 0) {
                                ?>
                                    <li class="nav-item">
                                        <a href="salary_slab_setting.php" class="nav-link">Salary Slab Setting</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                <?php }
                ?>
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

                <?php
                $chkmenu = $obj->check_menuname("upload_attachment.php", $loginid);
                if ($chkmenu > 0) {
                ?>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="upload_attachment.php">
                            <i class=" ri-upload-cloud-2-line">
                                <p>Upload Attachment</p>
                            </i> <span>Upload Attachment</span>
                        </a>
                    </li>
                <?php }  ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="change_password.php">
                        <i class=" ri-shield-keyhole-line">
                            <p>Change Password</p>
                        </i> <span>Change Password</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>