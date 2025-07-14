<?php
// filepath: d:\xamp\htdocs\thaisonweb\get-products.php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "ThaiSonSeafood");
if ($conn->connect_error) {
  echo json_encode([]);
  exit;
}

// Thêm quantity vào SELECT
$sql = "SELECT product_id, name, description, price, img, category, quantity FROM Products";
$result = $conn->query($sql);

$products = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $products[] = [
      'id' => $row['product_id'],
      'name' => $row['name'],
      'description' => $row['description'],
      'price' => $row['price'],
      'img' => $row['img'],
      'category' => $row['category'],
      'quantity' => $row['quantity'] // Thêm dòng này
    ];
  }
}
echo json_encode($products);
$conn->close();
?>