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

// Kiểm tra độ mạnh của mật khẩu: ít nhất 8 ký tự, có ít nhất 1 số và 1 chữ thường
if (
    strlen($password) < 8 ||
    !preg_match('/[a-z]/', $password) ||
    !preg_match('/[0-9]/', $password)
) {
    echo json_encode(['success' => false, 'message' => 'Mật khẩu phải ít nhất 8 ký tự, gồm ít nhất 1 số và 1 chữ thường!']);
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

// Kiểm tra email đã tồn tại
$stmt = $conn->prepare("SELECT user_id FROM Users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email đã được sử dụng!']);
    exit;
}
$stmt->close();

// Lấy tất cả user_id hiện có
$result = $conn->query("SELECT user_id FROM Users ORDER BY user_id ASC");
$usedIds = [];
while ($row = $result->fetch_assoc()) {
    $usedIds[] = intval(substr($row['user_id'], 1));
}

// Tìm số nhỏ nhất chưa dùng
$newNum = 1;
while (in_array($newNum, $usedIds)) {
    $newNum++;
}
$newId = 'U' . str_pad($newNum, 3, '0', STR_PAD_LEFT);

// Mã hóa mật khẩu
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Thêm user mới
$stmt = $conn->prepare("INSERT INTO Users (user_id, username, password, email) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $newId, $username, $hashedPassword, $email);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Đăng ký thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi đăng ký!']);
}
$stmt->close();
$conn->close();
?>