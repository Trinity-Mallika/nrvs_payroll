<?php
include("appsession.php");
$pagename = 'company-setting.php';
$title = 'Company Setting';
$tblname = "company_setting";
$tblpkey = "company_id";

$keyvalue = 1;

$where = [$tblpkey => $keyvalue];
$row = $obj->select_record($tblname, $where);

$company_name = $mobile = $contact_no = $address = $email = $gst = "";
$btn_name = "Create";

if ($row) {
    $company_name = $row['company_name'];
    $mobile = $row['mobile'];
    $contact_no = $row['contact_no'];
    $address = $row['address'];
    $email = $row['email'];
    $gst = $row['gst'];
    $btn_name = "Update";
}

// SAVE / UPDATE
if (isset($_POST['save'])) {

    $form_data = [
        "company_name" => $_POST['company_name'],
        "mobile" => $_POST['mobile'],
        "contact_no" => $_POST['contact_no'],
        "address" => $_POST['address'],
        "email" => $_POST['email'],
        "gst" => $_POST['gst']
    ];

    if ($row) {
        // UPDATE
        $obj->update_record($tblname, $where, $form_data);
    } else {
        // INSERT ONLY ONCE
        $form_data[$tblpkey] = 1;
        $obj->insert_record($tblname, $form_data);
    }

    echo "<script>location='company-setting.php'</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Gate Pass</title>
    <!-- css links  files -->
    <?php include("inc/css-file.php"); ?>

</head>

<body class="dashboard">
    <section class="top-sec ">
        <?php include("inc/header.php"); ?>

        <div class="container">
            <div class="card border-0 shadow-lg mb-3">
                <form method="POST" id="gatepassForm">
                    <div class="mb-3">
                        <label for="" class="form-label">Company Name</label>
                        <input type="text" class="form-control shadow-sm" id="company_name" name="company_name" value="<?= $company_name ?>">
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Mobile No.</label>
                        <input type="number" class="form-control shadow-sm" id="mobile" name="mobile" value="<?= $mobile ?>">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Land-Line No</label>
                        <input type="number" class="form-control shadow-sm" id="contact_no" name="contact_no" value="<?= $contact_no ?>">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Address</label>
                        <input type="text" class="form-control shadow-sm" id="address" name="address" value="<?= $address ?>">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Email Address</label>
                        <input type="email" class="form-control shadow-sm" id="email" name="email" value="<?= $email ?>">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">GSTIN No:</label>
                        <input type="text" class="form-control shadow-sm" id="gst" name="gst" value="<?= $gst ?>">
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" name="save" class="btn btn-primary">
                            <?= $btn_name ?>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </section>

    <!-- js script files -->
    <?php include("inc/js-file.php"); ?>
</body>





</html>