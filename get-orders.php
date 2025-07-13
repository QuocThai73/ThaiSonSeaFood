<?php
header('Content-Type: application/json');
require_once 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data['user_id'] ?? '';

$orders = [];

if ($user_id === 'U001') {
    // Admin: lấy tất cả đơn hàng
    $sql = "SELECT o.order_id, o.user_id, u.username, o.order_date, o.total, 
                   IFNULL(o.address, '') AS address, 
                   IFNULL(o.phone, '') AS phone, 
                   IFNULL(o.method, '') AS method,
                   IFNULL(o.status, 'Chờ duyệt') AS status
            FROM Orders o
            JOIN Users u ON o.user_id = u.user_id
            ORDER BY o.order_id DESC";
    $result = $conn->query($sql);
} else {
    // User thường: chỉ lấy đơn hàng của chính mình
    $sql = "SELECT o.order_id, o.user_id, u.username, o.order_date, o.total, 
                   IFNULL(o.address, '') AS address, 
                   IFNULL(o.phone, '') AS phone, 
                   IFNULL(o.method, '') AS method,
                   IFNULL(o.status, 'Chờ duyệt') AS status
            FROM Orders o
            JOIN Users u ON o.user_id = u.user_id
            WHERE o.user_id = ?
            ORDER BY o.order_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
}

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $order_id = $row['order_id'];
    $details = [];
    $sql2 = "SELECT od.product_id, p.name, od.quantity, od.price
                 FROM OrderDetails od
                 JOIN Products p ON od.product_id = p.product_id
                 WHERE od.order_id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("s", $order_id);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    if ($res2 && $res2->num_rows > 0) {
      while ($d = $res2->fetch_assoc()) {
        $details[] = $d;
      }
    }
    $row['details'] = $details;
    $orders[] = $row;
  }
}

echo json_encode($orders);
$conn->close();
?>