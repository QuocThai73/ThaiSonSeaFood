<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "ThaiSonSeafood");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Kết nối database thất bại']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data['user_id'] ?? null;
$cart = $data['cart'] ?? [];
$address = $data['address'] ?? '';
$phone = $data['phone'] ?? '';
$method = $data['method'] ?? '';

if (
    !$user_id || !is_array($cart) || count($cart) === 0 ||
    !$address || !$phone || !$method
) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin đơn hàng']);
    exit;
}

// Tính tổng tiền
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Thêm vào bảng Orders (có thêm address, phone, method)
$stmt = $conn->prepare("INSERT INTO Orders (user_id, total, address, phone, method) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sdsss", $user_id, $total, $address, $phone, $method);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Không thể tạo đơn hàng']);
    exit;
}
$order_id = $conn->insert_id;

// Thêm từng sản phẩm vào OrderDetails
$stmtDetail = $conn->prepare("INSERT INTO OrderDetails (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($cart as $item) {
    $stmtDetail->bind_param("isid", $order_id, $item['id'], $item['quantity'], $item['price']);
    $stmtDetail->execute();
}

echo json_encode(['success' => true, 'message' => 'Đặt hàng thành công!']);
$conn->close();
?>