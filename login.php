<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "ThaiSonSeafood");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối database!']);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);

$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if (!$username || !$password) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin!']);
    exit;
}

// Lấy thông tin user
$stmt = $conn->prepare("SELECT user_id, password FROM Users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($user_id, $hashedPassword);
    $stmt->fetch();
    // Nếu mật khẩu đã mã hóa thì dùng password_verify, nếu chưa thì so sánh trực tiếp
    if (
        password_verify($password, $hashedPassword) ||
        $password === $hashedPassword // Trường hợp mật khẩu lưu dạng text
    ) {
        echo json_encode(['success' => true, 'user_id' => $user_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Sai mật khẩu!']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Tài khoản không tồn tại!']);
}
$stmt->close();
$conn->close();
?>