<?php
// Database connection details
$dbhost = "localhost";
$dbuser = "surem2wr_nipesh";
$dbpass = "U%;ue,&uQ9Tn";
$db = "surem2wr_trinity_whatsapp";

// Create connection
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
