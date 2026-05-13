<?php
// Include the database connection file
include 'db.php';
// Define your API key
define('API_KEY', 'ramjane');
// // Function to check the API key
function isValidApiKey($key)
{
    return $key === API_KEY;
}
// // Set the header to allow cross-origin requests and to return JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// // Get the API key from the request headers
$headers = apache_request_headers();
$apiKey = isset($headers['API-Key']) ? $headers['API-Key'] : '';

// // Validate the API key
if (!isValidApiKey($apiKey)) {
    http_response_code(403);
    echo json_encode(["message" => "Forbidden: Invalid API key"]);
    exit();
}
// Define the RESTful API methods
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        // Check if 'id' is set in the URL parameters
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM student_table WHERE sid='$id'";
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                http_response_code(500);
                echo json_encode(["message" => "Error executing query: " . mysqli_error($conn)]);
                exit();
            }
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                echo json_encode($row);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Record not found"]);
            }
        } else {
            $sql = "SELECT * FROM student_table";
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                http_response_code(500);
                echo json_encode(["message" => "Error executing query: " . mysqli_error($conn)]);
                exit();
            }
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
            echo json_encode($rows);
        }
        break;
    case 'POST':
        // Handle POST request for creating a new record
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            $data = $_POST;
            if (empty($data)) {
                $data = $_GET;
            }
        }

        if (isset($data['name']) && isset($data['email'])) {
            $name = $data['name'];
            $email = $data['email'];

            $sql = "INSERT INTO student_table (name, email) VALUES ('$name', '$email')";
            if (mysqli_query($conn, $sql)) {
                http_response_code(201);
                echo json_encode(["message" => "Record created successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Error creating record: " . mysqli_error($conn)]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid input"]);
        }
        break;
    case 'PUT':
        // Handle PUT request for updating an existing record
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            $data = $_POST;
            if (empty($data)) {
                $data = $_GET;
            }
        }
        if (isset($data['id']) && isset($data['name']) && isset($data['email'])) {
            $id = $data['id'];
            $name = $data['name'];
            $email = $data['email'];

            $sql = "UPDATE student_table SET name='$name', email='$email' WHERE sid='$id'";
            if (mysqli_query($conn, $sql)) {
                if (mysqli_affected_rows($conn) > 0) {
                    http_response_code(200);
                    echo json_encode(["message" => "Record updated successfully"]);
                } else {
                    http_response_code(404);
                    echo json_encode(["message" => "Record not found"]);
                }
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Error updating record: " . mysqli_error($conn)]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid input"]);
        }
        break;
    case 'DELETE':
        // Handle DELETE request for deleting an existing record
        parse_str(file_get_contents("php://input"), $data);
        $id = isset($data['id']) ? $data['id'] : (isset($_GET['id']) ? $_GET['id'] : null);
        if ($id) {
            $sql = "DELETE FROM student_table WHERE sid='$id'";
            if (mysqli_query($conn, $sql)) {
                if (mysqli_affected_rows($conn) > 0) {
                    http_response_code(200);
                    echo json_encode(["message" => "Record deleted successfully"]);
                } else {
                    http_response_code(404);
                    echo json_encode(["message" => "Record not found"]);
                }
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Error deleting record: " . mysqli_error($conn)]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid input: ID not found"]);
        }
        break;
    default:
        // Handle unsupported HTTP methods
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
}
// Close the database connection
mysqli_close($conn);
