<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "ThaiSonSeafood");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Kết nối database thất bại']);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data['user_id'] ?? '';
$cart = $data['cart'] ?? [];
$address = trim($data['address'] ?? '');
$phone = trim($data['phone'] ?? '');
$method = trim($data['method'] ?? '');

if (!$user_id || !$cart || !$address || !$phone || !$method) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin đơn hàng!']);
    exit;
}

// Tính tổng tiền
$total = 0;
foreach ($cart as $item) {
    $total += floatval($item['price']) * intval($item['quantity']);
}

// Thêm đơn hàng vào bảng Orders
$stmt = $conn->prepare("INSERT INTO Orders (user_id, total, address, phone, method) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sdsss", $user_id, $total, $address, $phone, $method);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Không thể lưu đơn hàng!']);
    $stmt->close();
    $conn->close();
    exit;
}
$order_id = $stmt->insert_id;
$stmt->close();

// Thêm từng sản phẩm vào OrderDetails
$ok = true;
foreach ($cart as $item) {
    $stmt = $conn->prepare("INSERT INTO OrderDetails (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isid", $order_id, $item['id'], $item['quantity'], $item['price']);
    if (!$stmt->execute()) $ok = false;
    $stmt->close();
}

if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Đặt hàng thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu chi tiết đơn hàng!']);
}
$conn->close();
?>