<?php
header('Content-Type: application/json');

// Database connection details
$servername = "localhost"; // Use the same IP address
$username = "root";
$password = ""; // Enter the correct password if it exists
$dbname = "inventory_management_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
    exit;
}

$user_email = $data['email'];
$new_password = $data['password'];

// Check if email exists
$sql = "SELECT * FROM users WHERE user_email = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Prepare statement failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update the password
    $update_sql = "UPDATE users SET user_password = ? WHERE user_email = ?";
    $update_stmt = $conn->prepare($update_sql);

    if (!$update_stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare statement failed: " . $conn->error]);
        exit;
    }

    $update_stmt->bind_param("ss", $new_password, $user_email);

    if ($update_stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Password updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update password."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Email not found."]);
}

$stmt->close();
$conn->close();
?>