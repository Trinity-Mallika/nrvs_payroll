<?php
// include("appsession.php");
// $_SESSION['fullname'] = $row['fullname'];
// $_SESSION['user_id'] = $row['user_id'];
?>
<header class="rounded-4 ms-2 me-2">
    <div class="container">
        <div class="row">
            <div class="col-7 col-lg-7 col-mg-7">
                <a href="<?php echo $pagename; ?>">
                    <h6 class="text-white text-start mt-1 mb-0"> <i class="bi bi-house-door text-white"></i>
                        <?php echo $title; ?></h6>
                </a>
            </div>
            <div class="col-5 col-lg-5 col-mg-5 text-end">
                <!-- <a href="#0" class=" position-relative">
                    <i class="bi bi-bell fs-5 text-white "></i>
                    <span class="position-absolute top-0 translate-middle p-1 bg-danger border border-light rounded-circle" style="left: 18px;">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                </a> &nbsp;&nbsp; -->
                <a data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                    <i class="bi bi-list-nested fs-3 text-white "></i>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- sidemenu  -->
<div class="offcanvas offcanvas-start rounded-end-5" tabindex="-1" id="offcanvasExample"
    aria-labelledby="offcanvasExampleLabel" style="background: linear-gradient(45deg, #157bb0, #023a5b);">
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-9 col-lg-9 col-md-9 p-2">
                <h2 class="text-white">Gate Pass</h2>
            </div>
            <div class="col-3 col-lg-3 col-md-3 positon-relative text-center">
                <button type="button"
                    class="btn btn-light positon-absolute rounded-circle bg-blue text-white border-2 pt-1 pb-1 ps-2 pe-2"
                    data-bs-dismiss="offcanvas" aria-label="Close"><i class="bi bi-chevron-left"></i></button>
            </div>
            <hr>
            <div class="col-12 col-lg-12 col-md-12 p-2">
                <div class="d-flex">
                    <div class="profile-bg">
                        <!-- <i class="bi bi-person-square fs-1 text-blue mt-1"></i> -->
                        <?php
                        $photos = $obj->getvalfield("employee_master", "profile_image", "emp_id='$_SESSION[emp_id]'");
                        $photo = !empty($photos)
                            ? "../admin/uploaded/emp_documents/" . $photos
                            : 'img/user.jpg';
                        ?>

                        <img src="<?php echo $photo ?>" alt=""
                            style="border-radius:50%; object-fit:cover; height:60px; width: 60px; margin-top:8px;" />

                    </div>
                    <div class="ms-3">
                        <h4 class="text-white mb-0"> Hi,
                            <?php echo $obj->getvalfield("employee_master", "first_name", "emp_id='$_SESSION[emp_id]'"); ?></h4>
                        <small class="text-wlight"> ID:
                            <?php echo $obj->getvalfield("employee_master", "biomatric_id", "emp_id='$_SESSION[emp_id]'"); ?></small>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <hr>
            </div>
            <div class="col-12">
                <ul class="list-group list-group-flush side-menu-list">
                    <a href="dashboard.php" class="mt-2">
                        <li class="list-group-item border-0">
                            <span><i class="bi bi-house"></i></span>&nbsp; Home
                        </li>
                    </a>

                    <a href="attendance_details.php" class="mt-2">
                        <li class="list-group-item border-0">
                            <span><i class="bi bi-calendar-check"></i></span>&nbsp; Attendance Details
                        </li>
                    </a>

                    <a href="leave_apply.php" class="mt-2">
                        <li class="list-group-item border-0">
                            <span><i class="bi bi-calendar-minus"></i></span>&nbsp; Leave Application
                        </li>
                    </a>
                    <a href="extra_off_apply.php" class="mt-2">
                        <li class="list-group-item border-0">
                            <span><i class="bi bi-calendar-minus"></i></span>&nbsp; Extra Off Application
                        </li>
                    </a>

                    <a href="c_off_apply.php" class="mt-2">
                        <li class="list-group-item border-0">
                            <span><i class="bi bi-calendar-minus"></i></span>&nbsp; C Off Application
                        </li>
                    </a>
                    <a href="approval_leave_list.php" class="mt-2">
                        <li class="list-group-item border-0">
                            <span><i class="bi bi-briefcase"></i></span>&nbsp; Leave Requests for Approval
                        </li>
                    </a>
                    <a href="emp_on_duty.php" class="mt-2">
                        <li class="list-group-item border-0">
                            <span><i class="bi bi-briefcase"></i></span>&nbsp; On Duty Application
                        </li>
                    </a>
                    <a href="approval_on_duty_list.php" class="mt-2">
                        <li class="list-group-item border-0">
                            <span><i class="bi bi-briefcase"></i></span>&nbsp;On Duty Requests for Approval
                        </li>
                    </a>
                    <?php 
                    $report_emp_count = $obj->getvalfield("employee_master","count(*)","reporting_manager='$_SESSION[emp_id]'");
                    if($report_emp_count >0){ ?>
                    <a href="emp_att.php" class="mt-2">
                        <li class="list-group-item border-0">
                            <span><i class="bi bi-briefcase"></i></span>&nbsp;Assigned Emp Attendance
                        </li>
                    </a>
                    <?php } ?>
                    <!-- <a href="manage-gatepass.php" class="mt-2">
                        <li class="list-group-item border-0"><span> <i class="bi bi-file-text"></i></span>&nbsp; Manage Gate Pass</li>
                    </a>
                    <a href="#0" class="mt-2">
                        <li class="list-group-item border-0"><span><i class="bi bi-qr-code-scan"></i></span>&nbsp; Verify Gate Pass</li>
                    </a>
                    <a href="add-user.php" class="mt-2">
                        <li class="list-group-item border-0"><span><i class="bi bi-person-add"></i></span>&nbsp; Manage User</li>
                    </a> -->
                    <hr>
                    <a href="change-password.php" class="mt-2">
                        <li class="list-group-item border-0"><span><i class="bi bi-lock-fill"></i></span>&nbsp; Change
                            Password</li>
                    </a>
                    <a href="logout.php" class="mt-2">
                        <li class="list-group-item border-0"><span><i class="bi bi-box-arrow-right"></i></span>&nbsp;
                            Log-Out</li>
                    </a>
                </ul>
            </div>
        </div>
    </div>
</div>