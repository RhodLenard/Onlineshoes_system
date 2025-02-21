<?php
session_start();
include("../db/dbconn.php");

// ✅ Check user authentication
if (!isset($_SESSION['id'])) {
  http_response_code(403);
  echo json_encode(["status" => "error", "message" => "User not authenticated."]);
  exit();
}

$customerid = $_SESSION['id'];

try {
  // 🔄 Update all unread notifications to read
  $query = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE customer_id = ?");
  $query->bind_param("i", $customerid);
  if ($query->execute()) {
    echo json_encode(["status" => "success", "message" => "Notifications marked as read."]);
  } else {
    echo json_encode(["status" => "error", "message" => "Failed to mark notifications as read."]);
  }
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(["status" => "error", "message" => "Server error: " . $e->getMessage()]);
}
