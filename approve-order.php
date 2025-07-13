<?php
// filepath: d:\xamp\htdocs\ThaiSonSeaFood\approve-order.php
header('Content-Type: application/json');
require_once 'connect.php';
$data = json_decode(file_get_contents("php://input"), true);
$order_id = $data['order_id'] ?? '';
if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã đơn hàng!']);
    exit;
}
$stmt = $conn->prepare("UPDATE Orders SET status='Đã giao' WHERE order_id=?");
$stmt->bind_param("s", $order_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không thể cập nhật trạng thái!']);
}
$stmt->close();
$conn->close();
?>