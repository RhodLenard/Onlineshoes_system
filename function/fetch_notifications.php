<?php
session_start();
include("../db/dbconn.php"); // ✅ Adjust path if needed

// ⚡ Check if session ID is set
if (!isset($_SESSION['id'])) {
  http_response_code(403);
  echo json_encode(["error" => "User not authenticated."]);
  exit();
}

$customerid = $_SESSION['id']; // 🏷️ Assuming 'id' matches customer_id in the notifications table

try {
  // 🔄 Fetch notifications + product details
  $query = $conn->prepare(
    "SELECT 
            n.title, 
            n.message, 
            n.created_at, 
            n.is_read, 
            p.product_name, 
            p.product_image
        FROM notifications n
        LEFT JOIN transaction t 
            ON n.link LIKE CONCAT('%transaction_id=', t.transaction_id)
        LEFT JOIN transaction_detail td 
            ON t.transaction_id = td.transaction_id
        LEFT JOIN product p 
            ON td.product_id = p.product_id
        WHERE n.customer_id = ?
        ORDER BY n.created_at DESC"
  );
  $query->bind_param("i", $customerid);
  $query->execute();
  $result = $query->get_result();

  $notifications = [];
  while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
  }

  echo json_encode($notifications);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(["error" => "Server error: " . $e->getMessage()]);
}
