<?php
$conn = new mysqli('localhost', 'root', '', 'shoes'); // Added the port 3307

// Check for connection errors
if ($conn->connect_error) {
    die("Fatal Error: Connection Failed - " . $conn->connect_error);
}
