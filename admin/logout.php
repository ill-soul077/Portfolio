<?php
session_start();

// Clear session
session_unset();
session_destroy();

// Clear remember me cookie
if (isset($_COOKIE['admin_remember'])) {
    setcookie('admin_remember', '', time() - 3600, '/');
}

// Redirect to login page
header('Location: ../admin_login.php');
exit();
?>
