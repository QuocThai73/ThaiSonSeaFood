<?php
header('Content-Type: application/json');
require_once 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$order_id = $data['order_id'] ?? '';
$address = $data['address'] ?? '';
$phone = $data['phone'] ?? '';
$method = $data['method'] ?? '';

if (!$order_id) {
  echo json_encode(['success' => false, 'message' => 'Thiếu mã đơn hàng']);
  exit;
}

$stmt = $conn->prepare("UPDATE Orders SET address = ?, phone = ?, method = ? WHERE order_id = ?");
$stmt->bind_param("sssi", $address, $phone, $method, $order_id);
$success = $stmt->execute();
$stmt->close();

if ($success) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'Không thể cập nhật đơn hàng']);
}

$conn->close();
?>