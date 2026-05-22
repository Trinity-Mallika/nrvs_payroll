<?php
include("../adminsession.php");

$pagename = "company-setting.php";
$title = "Company Setting";
$tblname = "company_setting";
$tblpkey = "setting_id";
$keyvalue = 1;
$imgpath = "uploaded/company/";
$comp_logo = "";

if (isset($_POST['submit'])) {
    $company_name   = $obj->test_input($_POST['company_name']);
    $landline      = $obj->test_input($_POST['landline']);
    $address     = $obj->test_input($_POST['address']);
    $email       = $obj->test_input($_POST['email']);
    $mobile  = $obj->test_input($_POST['mobile']);
    $comp_logo   = $_FILES['comp_logo'];

    $where = array($tblpkey => $keyvalue);

    // Upload logo if provided
    if ($comp_logo['tmp_name'] != '') {
        $imageFileType = strtolower(pathinfo($comp_logo['name'], PATHINFO_EXTENSION));
        if (in_array($imageFileType, ['png', 'jpg', 'jpeg'])) {
            $old_logo = $obj->getvalfield($tblname, "company_img", "$tblpkey='$keyvalue'");
            if ($old_logo != "") {
                @unlink("$imgpath$old_logo");
            }
            $comp_logo_name = $obj->uploadImage($imgpath, $comp_logo);
            $obj->update_record($tblname, $where, ["company_img" => $comp_logo_name]);
        }
    }

    $form_data = [
        'company_name' => $company_name,
        'mobile'       => $mobile,
        'address'      => $address,
        'email'        => $email,
        'landline'   => $landline,
        'ipaddress'    => $ipaddress,
        'createdate'   => $createdate
    ];

    $obj->update_record($tblname, $where, $form_data);
}

// Fetch existing data
if ($keyvalue > 0) {
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $company_name   = $sqledit['company_name'];
    $mobile      = $sqledit['mobile'];
    $landline  = $sqledit['landline'];
    $email       = $sqledit['email'];
    $address     = $sqledit['address'];
    $comp_logo   = $sqledit['company_img'];
} else {
    $company_name = $mobile = $address = $landline = "";
}
?>


<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title>Company Setting</title>

    <?php include('inc/css.php') ?>

</head>

<body>
    <?php include('inc/header.php') ?>
    <?php include('inc/sidebar.php') ?>
    <!-- end auth-page-wrapper -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <?php include('inc/bredcrum.php') ?>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card" id="companyList">
                            <div class="card-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="company_name" class="form-label">Company Name<span class="text-danger fw-bold">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="company_name" id="company_name" placeholder="Enter your Company Name" value="<?php echo $company_name; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="first_name" class="form-label">Mobile No.<span class="text-danger fw-bold">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="mobile" id="mobile" placeholder="Enter Contact No." onkeypress="numberOnly(event)" value="<?php echo $mobile; ?>" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="last_name" class="form-label">Contact No.<span class="text-danger fw-bold">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="landline" id="landline" placeholder="Enter Landline No." onkeypress="numberOnly(event)" value="<?php echo $landline; ?>" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="emailInput" class="form-label">Email Address</label>
                                                <input type="email" class="form-control form-control-sm" name="email" id="emailInput" placeholder="Enter your email" value="<?php echo $email; ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label for="Logo" class="form-label">Logo</label>
                                                <input type="file" class="form-control form-control-sm" name="comp_logo" id="comp_logo" />
                                            </div>
                                            <?php if ($comp_logo != "") { ?>
                                                <img src="<?php echo $imgpath . $comp_logo; ?>" width="100px">
                                            <?php } ?>
                                        </div>

                                        <div class="col-lg-9">
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address</label>
                                                <textarea class="form-control form-control-sm" name="address" id="address" placeholder="Address"><?php echo $address; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="hstack gap-2 justify-content-center">
                                                <button type="submit" name="submit" class="btn btn-sm btn-primary" onclick="return checkinputmaster('company_name,mobile,landline');">Update</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--end card-->
                    </div>

                </div>
                <!--end row-->

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <?php include('inc/footer.php') ?>
    </div>
    <?php include('inc/js.php') ?>

</body>
<script>
    function numberOnly(evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\.|\s/;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }
</script>

</html>