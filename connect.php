<?php
// filepath: test-mysql.php
header('Content-Type: application/json');

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "thaisonseafood";

$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);

if ($conn) {
    echo " Kết nối MySQL thành công!";
} else {
    echo " Kết nối MySQL thất bại!";
}
?>