<?php
header('Content-Type: application/json');
$targetDir = "product_images/";
if (!is_dir($targetDir)) mkdir($targetDir);

if (!isset($_FILES['image']) || !isset($_POST['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu!']);
    exit;
}

$product_id = preg_replace('/[^A-Za-z0-9]/', '', $_POST['product_id']);
$file = $_FILES['image'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

if (!in_array($ext, $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Chỉ cho phép ảnh JPG, PNG, GIF, WEBP!']);
    exit;
}

$targetFile = $targetDir . $product_id . "." . $ext;

// Xóa các file ảnh cũ của sản phẩm này
foreach ($allowed as $e) {
    $old = $targetDir . $product_id . "." . $e;
    if (file_exists($old)) unlink($old);
}

if (move_uploaded_file($file['tmp_name'], $targetFile)) {
    echo json_encode(['success' => true, 'url' => $targetFile]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu ảnh!']);
}
?>