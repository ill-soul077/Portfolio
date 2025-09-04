<?php
session_start();
require_once '../config/database.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin_login.php');
    exit();
}

// Get form data
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// Validate input
if (empty($username) || empty($password)) {
    header('Location: ../admin_login.php?error=required');
    exit();
}

try {
    // Connect to database
    $db = new Database();
    $conn = $db->getConnection();
    
    // Find user (updated for new structure)
    $stmt = $conn->prepare("SELECT id, username, password, full_name, is_active FROM admin_users WHERE username = ? AND is_active = 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        // Update last login
        $updateStmt = $conn->prepare("UPDATE admin_users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
        $updateStmt->execute([$user['id']]);
        
        // Login successful
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_full_name'] = $user['full_name'];
        $_SESSION['admin_logged_in'] = true;
        
        // Set remember me cookie (7 days)
        if ($remember) {
            $cookie_value = base64_encode($user['id'] . '|' . $user['username'] . '|' . time());
            setcookie('admin_remember', $cookie_value, time() + (7 * 24 * 60 * 60), '/');
        }
        
        // Redirect to dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        // Invalid credentials
        header('Location: ../admin_login.php?error=invalid');
        exit();
    }
    
} catch (PDOException $e) {
    error_log("Login error: " . $e->getMessage());
    header('Location: ../admin_login.php?error=invalid');
    exit();
}
?>
