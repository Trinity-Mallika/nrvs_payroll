<?php
include("config.php");
session_start();

$db = new Database();
$conn = $db->con;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fullname = $_POST['fullname'];
    $user_id  = $_POST['user_id'];
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];

    $check = $conn->prepare("SELECT * FROM user WHERE user_id = ?");
    $check->execute([$user_id]);

    if ($check->rowCount() > 0) {
        echo "duplicate";
        exit;
    }

    $createdby = $_SESSION['userid'] ?? 0;
    $ipaddress = $_SERVER['REMOTE_ADDR'];
    $createdate = date('Y-m-d');
    $lastupdated = date('Y-m-d');


    try {

        $stmt = $conn->prepare("
            INSERT INTO user
            (fullname, user_id, password, usertype, status, createdby, ipaddress, createdate, lastupdated)
            VALUES (?, ?, ?, ?, 1, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $fullname,
            $user_id,
            $password,
            $usertype,
            $createdby,
            $ipaddress,
            $createdate,
            $lastupdated
        ]);

        echo "success";

    } catch (Exception $e) {
        echo "error";
    }
}