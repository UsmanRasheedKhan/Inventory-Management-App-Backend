<?php
header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Ensure this is set correctly if there's no password
$dbname = "inventory_management_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error); // Log the error
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Fetch product details
$sql = "SELECT product_id, product_name, product_price, product_quantity FROM inventory";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $products = [];
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $products]);
} else {
    echo json_encode(["status" => "error", "message" => "No products found."]);
}

$conn->close();
?>
