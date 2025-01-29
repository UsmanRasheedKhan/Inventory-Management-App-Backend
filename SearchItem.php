<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow all origins for testing purposes
header('Access-Control-Allow-Methods: GET');
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

// Get the product name from the query parameter
$product_name = isset($_GET['product_name']) ? $_GET['product_name'] : '';

if (!empty($product_name)) {
    // Search product by name
    $sql = "SELECT * FROM inventory WHERE product_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_term = "%" . $product_name . "%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    if (!empty($products)) {
        echo json_encode(["status" => "success", "data" => $products]);
    } else {
        echo json_encode(["status" => "error", "message" => "No products found."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Product name is required."]);
}

$conn->close();
?>
