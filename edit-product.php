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

$id = trim($data['id'] ?? '');
$name = isset($data['name']) ? trim($data['name']) : null;
$price = isset($data['price']) ? floatval($data['price']) : null;
$category = isset($data['category']) ? trim($data['category']) : null;
$img = isset($data['img']) ? trim($data['img']) : null;
$quantity = isset($data['quantity']) ? intval($data['quantity']) : null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã sản phẩm!']);
    exit;
}

// Chỉ cập nhật các trường được gửi lên
$fields = [];
$params = [];
$types = '';

if ($name !== null && $name !== '') {
    $fields[] = "name=?";
    $params[] = $name;
    $types .= 's';
}
if ($price !== null && $price !== '') {
    $fields[] = "price=?";
    $params[] = $price;
    $types .= 'd';
}
if ($category !== null && $category !== '') {
    $fields[] = "description=?";
    $params[] = $category;
    $types .= 's';
}
if ($img !== null && $img !== '') {
    $fields[] = "img=?";
    $params[] = $img;
    $types .= 's';
}
if ($quantity !== null && $quantity !== '') {
    $fields[] = "quantity=?";
    $params[] = $quantity;
    $types .= 'i';
}

if (!$fields) {
    echo json_encode(['success' => false, 'message' => 'Không có trường nào để cập nhật!']);
    exit;
}

$params[] = $id;
$types .= 's';

$sql = "UPDATE Products SET " . implode(',', $fields) . " WHERE product_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Đã cập nhật sản phẩm!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật sản phẩm!']);
}
$stmt->close();
$conn->close();
?>