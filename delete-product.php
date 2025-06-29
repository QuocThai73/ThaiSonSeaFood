<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "ThaiSonSeafood");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Kết nối database thất bại']);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra quyền admin (phải là U001)
if (($data['user_id'] ?? '') !== 'U001') {
    echo json_encode(['success' => false, 'message' => 'Không có quyền!']);
    exit;
}

$id = trim($data['id'] ?? '');
if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã sản phẩm!']);
    exit;
}

// Xóa file ảnh sản phẩm nếu có
$imgDeleted = false;
$imgFolder = "product_images/";
$allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
foreach ($allowed as $ext) {
    $file = $imgFolder . $id . "." . $ext;
    if (file_exists($file)) {
        unlink($file);
        $imgDeleted = true;
    }
}

$stmt = $conn->prepare("DELETE FROM Products WHERE product_id=?");
$stmt->bind_param("s", $id);

if ($stmt->execute()) {
    $msg = 'Đã xóa sản phẩm!';
    if ($imgDeleted) $msg .= ' Ảnh sản phẩm đã được xóa.';
    echo json_encode(['success' => true, 'message' => $msg]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa sản phẩm!']);
}
$stmt->close();
$conn->close();
?>