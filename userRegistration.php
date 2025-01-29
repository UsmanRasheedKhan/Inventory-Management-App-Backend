<?php

header("Content-Type: application/json");

// Database connection details
$servername = "localhost";
$username = "root"; // Replace with your actual database username
$password = ""; // If your MySQL root user has a password, include it here
$database = "inventory_management_app"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    $response = array("Message" => "Connection failed: " . $conn->connect_error);
    echo json_encode($response);
    http_response_code(500); // Internal Server Error
    exit();
}

// Extract the JSON POST data
$data = json_decode(file_get_contents("php://input"), true);

// Log the received data for debugging
file_put_contents('log.txt', print_r($data, true));

// Extract user data from the JSON
$user_name = $data['user_name'];
$user_email = $data['user_email'];
$user_password = $data['user_password'];
$user_contact = $data['user_contact'];

// SQL query to insert data into users table
$sql = "INSERT INTO users (user_name, user_email, user_password, user_contact) VALUES ('$user_name', '$user_email', '$user_password', '$user_contact')";

// Execute the SQL query
if ($conn->query($sql) === TRUE) {
    $response = array("Message" => "New record created successfully");
    echo json_encode($response);
} else {
    $response = array("Message" => "Error: " . $sql . "<br>" . $conn->error);
    echo json_encode($response);
    http_response_code(500); // Internal Server Error
    // Log SQL error for debugging
    file_put_contents('log.txt', $conn->error, FILE_APPEND);
}

// Close the database connection
$conn->close();

?>
