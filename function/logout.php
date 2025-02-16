<?php
session_start();

// Unset only the 'id' session (customer logout only)
unset($_SESSION['id']);

echo '<script>
    alert("You have successfully logged out.");
    window.location.href = "../index.php"; // Redirect to index.php in the parent directory
</script>';
exit();
