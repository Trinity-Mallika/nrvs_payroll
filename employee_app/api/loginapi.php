<?php
include 'db.php';

header("Access-Control-Allow-Origin: *");  // You can replace '*' with specific domains if needed
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

// Get the POST data
$mobile_number = $_POST['mobile_number'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (empty($mobile_number) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Mobile number and password are required.']);
    exit();
}

// Escape input to prevent SQL injection
$mobile_number = $conn->real_escape_string($mobile_number);
$password = $conn->real_escape_string($password);

// Check credentials in the database
$sql = "SELECT * FROM student_table WHERE mobile = '$mobile_number' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Successful login
    $user = $result->fetch_assoc(); // Fetch user data if needed
    echo json_encode(['status' => 'success', 'message' => 'Login successful', 'user' => $user]);
} else {
    // Invalid credentials
    echo json_encode(['status' => 'error', 'message' => 'Invalid mobile number or password.']);
}

// Close the connection
$conn->close();
