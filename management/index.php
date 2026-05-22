<?php include("../adminsession.php");

if (isset($_SESSION['usertype']) && $_SESSION['usertype'] != "" && isset($_SESSION['userid']) && $_SESSION['userid'] != "") {
    echo "<script>location='dashboard.php'</script>";
} else {
    echo "<script>location='../index.php?msg=invalid'</script>";
    die;
}
