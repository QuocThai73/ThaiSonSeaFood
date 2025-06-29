<?php
// connect.php file inclusion
require_once 'connect.php';

// Fetch products from the database
function getProducts() {
    global $conn; // Use the global connection variable
    $sql = "SELECT * FROM Products";
    $result = $conn->query($sql);

    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

// Set the content type to JSON
header('Content-Type: application/json');

// Get the products and output them as JSON
echo json_encode(getProducts());
?>