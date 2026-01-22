<?php include("action.php");

$message = "";
$session_name = "";
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    if ($msg == 'error')
        $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Wrong User Id or Password <button data-bs-dismiss='alert' class='btn-close' type='button'></button></div>";
    if ($msg == 'blank')
        $message = "<div class='alert alert-info alert-dismissible fade show' role='alert'><button data-bs-dismiss='alert' class='btn-close' type='button'></button>User Id & Password Should not be Blank!!</div>";
    if ($msg == 'invalid')
        $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'><button data-bs-dismiss='alert' class='btn-close' type='button'></button>Invalid User login</div>";
    if ($msg == 'logout')
        $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'><button data-bs-dismiss='alert' class='btn-close' type='button'></button>Successfully Logged Out !!</div>";
    if ($msg == 'wrong_unit')
        $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'><button data-bs-dismiss='alert' class='btn-close' type='button'></button> Unit Are Wrong !!</div>";
}




$expired_date = $obj->getvalfield("software_expired", "expired_date", "1=1 order by soft_exp_id desc");
$expired = $obj->software_expire();

?>
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title>Sign In</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Trinity Solutions" name="Trinity Solutions" />
    <meta content="Naveen Pandit" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="admin/assets/images/favicon.ico">
    <link href="admin/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="admin/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="admin/assets/css/custom.min.css" rel="stylesheet" type="text/css" />

</head>

<body>

    <!-- <div class="auth-page-wrapper pt-5">
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles" style="background-image: url(bg.jpg);">
            <div class="bg-overlay"></div>
        </div>
        <div class="auth-page-content pb-0">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card card-bg-fill">

                            <div class="card-body p-4">

                                <?php if ($expired == 1) { ?>
                                    <div class="text-center mt-2">
                                        <h5 class="text-primary">Welcome Back !</h5>
                                        <p class="text-muted">Sign in to continue.</p>
                                    </div>
                                    <form action="checklogin.php" method="post">
                                        <?php echo $message; ?>
                                        <div class="p-2 mt-4">
                                            <div class="mb-3">
                                                <label for="unit_id" class="form-label">Unit Name</label>
                                                <select type="text" class="form-control" name="unit_id" id="unit_id">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    $slno = 1;
                                                    $res = $obj->executequery("select * from unit_master order by unit_id desc");
                                                    foreach ($res as $row) {  ?>
                                                        <option value="<?php echo $row['unit_id'] ?>"><?php echo $row['unit_name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter username">
                                            </div>

                                            <div class="mb-3">

                                                <label class="form-label" for="password-input">Password</label>
                                                <div class="position-relative auth-pass-inputgroup mb-3">
                                                    <input type="password" class="form-control pe-5 password-input" placeholder="Enter password" name="password" id="password-input">
                                                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <button class="btn btn-success w-100" type="submit" name="login" onclick="return checkinputmaster('unit_id,username,password');">Sign In</button>
                                            </div>

                                            <div class="alert align-items-center mt-3" style="background-color:rgb(255, 151, 151)" role="alert">
                                                <i class="bi bi-info-circle-fill"></i> Software will be expire :
                                                <span> <?php echo $obj->dateformatindia($expired_date); ?></span>
                                            </div>
                                        </div>
                                    </form>
                                <?php } else { ?>
                                    <div class="text-center">
                                        <h3 class="text-danger fw-bold">Hosting Expired</h3>
                                        <hr>
                                        <h4 class="text-primary">Kindly contact the administrator for renewal: +91-9770131555</h4>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- auth-page content -->
    <div class="overflow-hidden">
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card overflow-hidden card-bg-fill galaxy-border-none shadow">
                    <div class="row g-0">
                        <div class="col-lg-6" style="background: url('trinity-payroll.png'); background-size: cover; background-position: center;">
                            <div class="p-lg-5 p-4 auth-one-bg h-100">
                                <div class="position-relative h-100 d-flex flex-column" style="visibility: hidden;">
                                    <div class="mb-4">
                                        <a href="index.php" class="d-block">
                                            <img src="" alt="" height="18">
                                        </a>
                                    </div>
                                    <div class="mt-auto">
                                        <div class="mb-3">
                                            <i class="ri-double-quotes-l display-4 text-success"></i>
                                        </div>

                                        <div id="qoutescarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-indicators">
                                                <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                            </div>
                                            <div class="carousel-inner text-center text-white-50 pb-5">
                                                <div class="carousel-item active">
                                                    <p class="fs-15 fst-italic">" Great! Clean code, clean design, easy for customization. Thanks very much! "</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end carousel -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                        <?php if ($expired == 1) { ?>
                            <div class="col-lg-6">
                                <div class="p-lg-5 p-4">
                                    <div>
                                        <h5 class="text-primary">Welcome Back !</h5>
                                        <p class="text-muted">Sign in to continue to Velzon.</p>
                                    </div>

                                    <div class="mt-4">
                                        <form action="checklogin.php" method="post">

                                            <div class="mb-3">
                                                <label for="unit_id" class="form-label">Unit Name</label>
                                                <select type="text" class="form-control" name="unit_id" id="unit_id">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    $slno = 1;
                                                    $res = $obj->executequery("select * from unit_master order by unit_id desc");
                                                    foreach ($res as $row) {  ?>
                                                        <option value="<?php echo $row['unit_id'] ?>"><?php echo $row['unit_name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter username">
                                            </div>

                                            <div class="mb-3">

                                                <label class="form-label" for="password-input">Password</label>
                                                <div class="position-relative auth-pass-inputgroup mb-3">
                                                    <input type="password" class="form-control pe-5 password-input" placeholder="Enter password" name="password" id="password-input">
                                                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <button class="btn btn-success w-100" type="submit" name="login" onclick="return checkinputmaster('unit_id,username,password');">Sign In</button>
                                            </div>

                                            <div class="alert align-items-center mt-3" style="background-color:rgb(255, 151, 151)" role="alert">
                                                <i class="bi bi-info-circle-fill"></i> Software will be expire :
                                                <span> <?php echo $obj->dateformatindia($expired_date); ?></span>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="text-center">
                                <h3 class="text-danger fw-bold">Hosting Expired</h3>
                                <hr>
                                <h4 class="text-primary">Kindly contact the administrator for renewal: +91-9770131555</h4>
                            </div>
                        <?php } ?>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->

        </div>
        <!-- end row -->
    </div>
    <!-- auth-page content -->

    <!-- JAVASCRIPT -->
    <script src="admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="admin/assets/libs/node-waves/waves.min.js"></script>
    <script src="admin/assets/libs/feather-icons/feather.min.js"></script>
    <script src="admin/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="admin/assets/js/commonfun.js"></script>
    <script>
        Array.from(document.querySelectorAll("form .auth-pass-inputgroup")).forEach(
            function(e) {
                Array.from(e.querySelectorAll(".password-addon")).forEach(function(r) {
                    r.addEventListener("click", function(r) {
                        var o = e.querySelector(".password-input");
                        "password" === o.type ? (o.type = "text") : (o.type = "password");
                    });
                });
            }
        );
    </script>
</body>

</html>