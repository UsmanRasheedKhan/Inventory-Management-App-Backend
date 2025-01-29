<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_management_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed"]));
}
$input = json_decode(file_get_contents("php://input"), true);
$email = $input['email'];

// Fetch user details
$sql = "SELECT * FROM users WHERE user_email = '$email'"; // Replace 'user@example.com' with the user's email

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["status" => "success", "data" => $row]);
} else {
    echo json_encode(["status" => "error", "message" => "User not found"]);
}

$conn->close();
?>
