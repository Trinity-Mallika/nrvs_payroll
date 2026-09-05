<?php include("../adminsession.php");
$pagename = "document.php";
?>

<!DOCTYPE html>
<html>

<head>
    <title>Documents</title>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <!-- <style>
        /* Document menu */

.document-dropdown {
    display: none;

    position: absolute;

    left: 100%;
    top: 0;

    width: 260px;

    background: white;

    padding: 5px 0;

    margin: 0;

    list-style: none;

    box-shadow: 0 3px 10px rgba(0,0,0,0.2);

    z-index: 9999;
}


/* Show dropdown */

.document-dropdown.show {
    display: block;
}


/* Dropdown links */

.document-dropdown li a {
    display: block;

    padding: 10px 15px;

    color: #354a80;

    background: white;

    text-decoration: none;

    font-size: 13px;
}


/* Hover */

.document-dropdown li a:hover {
    background: #f1f3f8;

    color: #354a80;
}</style> -->
</head>

<body>

    <div class="container mt-4">

        <h3>Documents</h3>

        <div class="row">

           

            <?php
            $chkmenu = $obj->check_menuname("intent_letter.php", $loginid);

            if ($chkmenu > 0) {
            ?>
                <div class="col-md-4 mb-3">
                    <a href="intent_letter.php" class="btn btn-primary w-100">
                        Intent Letter
                    </a>
                </div>
            <?php
            }
            ?>


            <?php
            $chkmenu = $obj->check_menuname("relieving_letter.php", $loginid);

            if ($chkmenu > 0) {
            ?>
                <div class="col-md-4 mb-3">
                    <a href="relieving_letter.php" class="btn btn-primary w-100">
                        Relieving Letter
                    </a>
                </div>
            <?php
            }
            ?>


            <?php
            $chkmenu = $obj->check_menuname("experience_letter.php", $loginid);

            if ($chkmenu > 0) {
            ?>
                <div class="col-md-4 mb-3">
                    <a href="experience_letter.php" class="btn btn-primary w-100">
                        Experience Letter
                    </a>
                </div>
            <?php
            }
            ?>


            <?php
            $chkmenu = $obj->check_menuname("current_employment_certificate.php", $loginid);

            if ($chkmenu > 0) {
            ?>
                <div class="col-md-4 mb-3">
                    <a href="current_employment_certificate.php" class="btn btn-primary w-100">
                        Current Employment Certificate
                    </a>
                </div>
            <?php
            }
            ?>


            <?php
            $chkmenu = $obj->check_menuname("character_certificate.php", $loginid);

            if ($chkmenu > 0) {
            ?>
                <div class="col-md-4 mb-3">
                    <a href="character_certificate.php" class="btn btn-primary w-100">
                        Character Certificate
                    </a>
                </div>
            <?php
            }
            ?>


            <?php
            $chkmenu = $obj->check_menuname("no_dues_form.php", $loginid);

            if ($chkmenu > 0) {
            ?>
                <div class="col-md-4 mb-3">
                    <a href="no_dues_form.php" class="btn btn-primary w-100">
                        No Dues Form
                    </a>
                </div>
            <?php
            }
            ?>


            <?php
            $chkmenu = $obj->check_menuname("resignation_acceptance.php", $loginid);

            if ($chkmenu > 0) {
            ?>
                <div class="col-md-4 mb-3">
                    <a href="resignation_acceptance.php" class="btn btn-primary w-100">
                        Resignation Acceptance
                    </a>
                </div>
            <?php
            }
            ?>

        </div>

    </div>
<!-- <script>

function toggleDocumentMenu()
{
    var menu = document.getElementById("documentMenu");

    menu.classList.toggle("show");
}

</script> -->
</body>

</html>