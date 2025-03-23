<?php
/**
 * Admin Panel - Logout
 * 
 * Bu sayfa, admin panelinden çıkış yapmak için kullanılır.
 */

// Start session
session_start();

// Destroy session
session_unset();
session_destroy();

// Redirect to login page
header('Location: index.php');
exit;
?> 