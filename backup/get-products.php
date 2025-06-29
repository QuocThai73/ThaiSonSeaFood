<?php
// filepath: d:\xamp\htdocs\thaisonweb\get-products.php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "ThaiSonSeafood");
if ($conn->connect_error) {
  echo json_encode([]);
  exit;
}

$sql = "SELECT product_id, name, description, price FROM Products";
$result = $conn->query($sql);

$products = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    // Đường dẫn ảnh động, chống cache
    $imgUrl = "product-image.php?id=" . urlencode($row['product_id']) . "&v=" . time();
    $products[] = [
      'id' => $row['product_id'],
      'name' => $row['name'],
      'description' => $row['description'],
      'price' => $row['price'],
      'img' => $imgUrl
    ];
  }
}
echo json_encode($products);
$conn->close();
?>