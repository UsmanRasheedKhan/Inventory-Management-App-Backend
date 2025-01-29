<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow all origins for testing purposes
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Content-Type');

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

// Get the PUT data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->product_id) && !empty($data->product_name) && !empty($data->product_price) && !empty($data->product_quantity)) {
    $product_id = $data->product_id;
    $product_name = $data->product_name;
    $product_price = $data->product_price;
    $product_quantity = $data->product_quantity;

    // Update product details
    $sql = "UPDATE inventory SET product_name = ?, product_price = ?, product_quantity = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdii", $product_name, $product_price, $product_quantity, $product_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["status" => "success", "message" => "Product updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Product not found or no changes made."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating product: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Incomplete data."]);
}

$conn->close();
?>
