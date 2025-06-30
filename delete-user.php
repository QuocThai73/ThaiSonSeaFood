<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "ThaiSonSeafood");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Kết nối database thất bại']);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data['user_id'] ?? '';
if (!$user_id || $user_id === 'U001') {
    echo json_encode(['success' => false, 'message' => 'Không thể xóa tài khoản admin!']);
    exit;
}

// Lấy danh sách order_id của user này
$orderIds = [];
$res = $conn->query("SELECT order_id FROM Orders WHERE user_id='$user_id'");
while ($row = $res->fetch_assoc()) {
    $orderIds[] = $row['order_id'];
}

// Xóa OrderDetails liên quan
if (!empty($orderIds)) {
    $orderIdStr = implode(",", array_map('intval', $orderIds));
    $conn->query("DELETE FROM OrderDetails WHERE order_id IN ($orderIdStr)");
}

// Xóa Orders liên quan
$conn->query("DELETE FROM Orders WHERE user_id='$user_id'");

// Xóa SavedItems liên quan
$conn->query("DELETE FROM SavedItems WHERE user_id='$user_id'");

// Cuối cùng xóa user
$stmt = $conn->prepare("DELETE FROM Users WHERE user_id=?");
$stmt->bind_param("s", $user_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Đã xóa tài khoản thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa tài khoản!']);
}
$stmt->close();
$conn->close();
?>