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
    error_log("Connection failed: " . $conn->connect_error);
    die(json_encode(["status" => "error", "message" => "Connection failed"]));
}

// Get the input data
$input = json_decode(file_get_contents("php://input"), true);

// Check if email and password are set
if (!isset($input['email']) || !isset($input['password'])) {
    error_log("Invalid input: " . print_r($input, true));
    die(json_encode(["status" => "error", "message" => "Invalid input"]));
}

$email = $input['email'];
$user_password = $input['password'];

// Prepare and bind
$stmt = $conn->prepare("SELECT user_password FROM users WHERE user_email = ?");
if ($stmt === false) {
    error_log("Prepare failed: " . $conn->error);
    die(json_encode(["status" => "error", "message" => "Prepare failed"]));
}
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($stored_password);
    $stmt->fetch();

    // Verify password
    if ($user_password === $stored_password) {
        echo json_encode(["status" => "success", "message" => "Login successful"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid password"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User not found"]);
}

$stmt->close();
$conn->close();
?>
