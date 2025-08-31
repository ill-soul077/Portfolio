<?php
// Check if user is logged in
function checkAuthentication() {
    // Check session
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        return true;
    }
    
    // Check remember me cookie
    if (isset($_COOKIE['admin_remember'])) {
        $cookie_data = base64_decode($_COOKIE['admin_remember']);
        $parts = explode('|', $cookie_data);
        
        if (count($parts) === 3) {
            $user_id = $parts[0];
            $username = $parts[1];
            $timestamp = $parts[2];
            
            // Check if cookie is still valid (7 days)
            if (time() - $timestamp < (7 * 24 * 60 * 60)) {
                // Verify user exists in database
                try {
                    $db = new Database();
                    $conn = $db->getConnection();
                    $stmt = $conn->prepare("SELECT id, username FROM admin_users WHERE id = ? AND username = ?");
                    $stmt->execute([$user_id, $username]);
                    $user = $stmt->fetch();
                    
                    if ($user) {
                        // Restore session
                        $_SESSION['admin_id'] = $user['id'];
                        $_SESSION['admin_username'] = $user['username'];
                        $_SESSION['admin_logged_in'] = true;
                        return true;
                    }
                } catch (PDOException $e) {
                    // Database error, remove cookie
                    setcookie('admin_remember', '', time() - 3600, '/');
                }
            } else {
                // Cookie expired, remove it
                setcookie('admin_remember', '', time() - 3600, '/');
            }
        }
    }
    
    return false;
}

// Redirect to login if not authenticated
if (!checkAuthentication()) {
    header('Location: ../admin_login.php');
    exit();
}
?>
