<?php
header('Content-Type: application/json');
require_once 'connect.php';

$orders = [];

$sql = "SELECT o.order_id, o.user_id, u.username, o.order_date, o.total, 
               IFNULL(o.address, '') AS address, 
               IFNULL(o.phone, '') AS phone, 
               IFNULL(o.method, '') AS method
        FROM Orders o
        JOIN Users u ON o.user_id = u.user_id
        ORDER BY o.order_id DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $order_id = $row['order_id'];
    $details = [];
    $sql2 = "SELECT od.product_id, p.name, od.quantity, od.price
                 FROM OrderDetails od
                 JOIN Products p ON od.product_id = p.product_id
                 WHERE od.order_id = '$order_id'";
    $res2 = $conn->query($sql2);
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
?>