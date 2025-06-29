<?php
$productId = $_GET['id'] ?? '';
$imageDir = "product_images/";
$imgExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
$videoExtensions = ['mp4', 'webm'];
$mediaPath = '';
$type = 'image';

foreach ($videoExtensions as $ext) {
    $candidate = $imageDir . $productId . "." . $ext;
    if (file_exists($candidate)) {
        $mediaPath = $candidate;
        $type = 'video';
        break;
    }
}
if (!$mediaPath) {
    foreach ($imgExtensions as $ext) {
        $candidate = $imageDir . $productId . "." . $ext;
        if (file_exists($candidate)) {
            $mediaPath = $candidate;
            $type = 'image';
            break;
        }
    }
}
if (!$mediaPath) {
    // Trả về ảnh placeholder nếu không có gì
    header("Location: https://placehold.co/250x180/cccccc/333333?text=No+Media");
    exit;
}
if ($type === 'video') {
    $mime = ($ext === 'mp4') ? 'video/mp4' : 'video/webm';
    header("Content-Type: $mime");
} else {
    $info = getimagesize($mediaPath);
    $mime = $info['mime'];
    header("Content-Type: $mime");
}
readfile($mediaPath);
?>