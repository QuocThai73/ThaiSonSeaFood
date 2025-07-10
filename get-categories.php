<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "ThaiSonSeafood");
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}
$result = $conn->query("SELECT DISTINCT category FROM Products WHERE category IS NOT NULL AND category <> ''");
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['category'];
}
echo json_encode($categories);
$conn->close();
?>