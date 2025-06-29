<?php
header('Content-Type: application/json');
require_once 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$order_id = $data['order_id'] ?? '';

if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã đơn hàng']);
    exit;
}

// Xoá chi tiết đơn hàng trước (nếu có)
$stmt1 = $conn->prepare("DELETE FROM OrderDetails WHERE order_id = ?");
$stmt1->bind_param("i", $order_id);
$stmt1->execute();
$stmt1->close();

// Xoá đơn hàng
$stmt2 = $conn->prepare("DELETE FROM Orders WHERE order_id = ?");
$stmt2->bind_param("i", $order_id);
$success = $stmt2->execute();
$stmt2->close();

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không thể xoá đơn hàng']);
}

$conn->close();