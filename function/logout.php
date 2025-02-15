<?php
session_start();

// Unset only the 'id' session (customer logout only)
unset($_SESSION['id']);

// Redirect to login or home page
header("Location: ../index.php");
exit();
