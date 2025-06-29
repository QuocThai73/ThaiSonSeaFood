<?php
$productId = $_GET['id'] ?? '';
$imageDir = "product_images/";
$imgExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
$imagePath = '';
foreach ($imgExtensions as $ext) {
    $candidate = $imageDir . $productId . "." . $ext;
    if (file_exists($candidate)) {
        $imagePath = $candidate;
        break;
    }
}
if (!$imagePath) {
    header("Location: https://placehold.co/250x180/cccccc/333333?text=No+Image");
    exit;
}
$info = getimagesize($imagePath);
$mime = $info['mime'];
header("Content-Type: $mime");
readfile($imagePath);

$imgUrl = "product-media.php?id=" . urlencode($row['product_id']) . "&v=" . time();
$type = 'image';
foreach (['mp4', 'webm'] as $ext) {
    if (file_exists("product_images/" . $row['product_id'] . "." . $ext)) {
        $type = 'video';
        break;
    }
}
$products[] = [
  'id' => $row['product_id'],
  'name' => $row['name'],
  'description' => $row['description'],
  'price' => $row['price'],
  'media' => $imgUrl,
  'mediaType' => $type
];
?>