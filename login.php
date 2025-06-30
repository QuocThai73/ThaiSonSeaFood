<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "ThaiSonSeafood");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Kết nối database thất bại']);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if (!$username || !$password) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin!']);
    exit;
}

$stmt = $conn->prepare("SELECT user_id, password FROM Users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 1) {
    $stmt->bind_result($user_id, $dbPassword);
    $stmt->fetch();
    // So sánh trực tiếp vì mật khẩu lưu dạng text thường
    if ($password === $dbPassword) {
        echo json_encode(['success' => true, 'user_id' => $user_id]);
        exit;
    }
}
echo json_encode(['success' => false, 'message' => 'Sai tài khoản hoặc mật khẩu!']);
?>