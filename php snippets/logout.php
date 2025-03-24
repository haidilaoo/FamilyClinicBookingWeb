<?php
session_start();
$_SESSION = []; // Clear all session variables
session_destroy(); // Destroy the session
header('Location: ../php/index.php'); // Redirect to the main page after logout
exit;
?>
