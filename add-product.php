<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "ThaiSonSeafood");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Kết nối database thất bại']);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);

if (($data['user_id'] ?? '') !== 'U001') {
    echo json_encode(['success' => false, 'message' => 'Không có quyền!']);
    exit;
}

$name = trim($data['name'] ?? '');
$price = floatval($data['price'] ?? 0);
$category = trim($data['category'] ?? '');
$description = $category;

if (!$name || !$price || !$category) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sản phẩm!']);
    exit;
}

// Kiểm tra trùng tên sản phẩm
$check = $conn->prepare("SELECT product_id FROM Products WHERE name = ?");
$check->bind_param("s", $name);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Tên sản phẩm đã tồn tại!']);
    $check->close();
    $conn->close();
    exit;
}
$check->close();

// Sinh product_id tự động
$result = $conn->query("SELECT product_id FROM Products ORDER BY product_id DESC LIMIT 1");
if ($row = $result->fetch_assoc()) {
    $lastId = intval(substr($row['product_id'], 1));
    $newId = 'P' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
} else {
    $newId = 'P001';
}

// Thêm sản phẩm chưa có ảnh
$stmt = $conn->prepare("INSERT INTO Products (product_id, name, description, price, quantity) VALUES (?, ?, ?, ?, 100)");
$stmt->bind_param("sssd", $newId, $name, $description, $price);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'product_id' => $newId]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm sản phẩm!']);
}
$stmt->close();
$conn->close();
?>