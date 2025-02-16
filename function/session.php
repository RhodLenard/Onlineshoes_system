<?php
session_start();

// Check if session is lost
if (!isset($_SESSION['id']) && isset($_GET['tid'])) {
	$transaction_id = (int)$_GET['tid'];

	// Fetch the user ID based on the last transaction
	include("db/dbconn.php");
	$stmt = $conn->prepare("SELECT customerid FROM transaction WHERE transaction_id = ?");
	$stmt->bind_param("i", $transaction_id);
	$stmt->execute();
	$result = $stmt->get_result()->fetch_assoc();

	if ($result) {
		$_SESSION['id'] = $result['customerid']; // Restore user session
	}
}
