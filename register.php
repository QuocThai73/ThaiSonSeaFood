<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "ThaiSonSeafood");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Kết nối database thất bại']);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);

$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');
$email = trim($data['email'] ?? '');
$fullname = trim($data['fullname'] ?? '');

// Kiểm tra dữ liệu
if (!$username || !$password || !$email) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin!']);
    exit;
}

// Kiểm tra username đã tồn tại
$stmt = $conn->prepare("SELECT user_id FROM Users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Tên đăng nhập đã tồn tại!']);
    exit;
}
$stmt->close();

// Tạo user_id tự động (ví dụ: U003)
$result = $conn->query("SELECT user_id FROM Users ORDER BY user_id DESC LIMIT 1");
if ($row = $result->fetch_assoc()) {
    $lastId = intval(substr($row['user_id'], 1));
    $newId = 'U' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
} else {
    $newId = 'U001';
}

// Không mã hóa mật khẩu
$plainPassword = $password;

// Thêm user mới
$stmt = $conn->prepare("INSERT INTO Users (user_id, username, password, email) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $newId, $username, $plainPassword, $email);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Đăng ký thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi đăng ký!']);
}
$stmt->close();
$conn->close();
?>