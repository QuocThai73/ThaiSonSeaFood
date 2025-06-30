<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "ThaiSonSeafood");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Kết nối database thất bại']);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data['user_id'] ?? '';
$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (!$user_id || !$username) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin!']);
    exit;
}

if ($password !== '') {
    $stmt = $conn->prepare("UPDATE Users SET username=?, email=?, password=? WHERE user_id=?");
    $stmt->bind_param("ssss", $username, $email, $password, $user_id);
} else {
    $stmt = $conn->prepare("UPDATE Users SET username=?, email=? WHERE user_id=?");
    $stmt->bind_param("sss", $username, $email, $user_id);
}
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Đã cập nhật tài khoản!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật tài khoản!']);
}
$stmt->close();
$conn->close();
?>