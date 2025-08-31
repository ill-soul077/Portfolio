<?php
require_once 'config/database.php';

echo "<h2>Setting Admin Password</h2>";

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Set password to 'admin123'
    $new_password = 'admin123';
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update the admin user password
    $stmt = $conn->prepare("UPDATE admin_users SET password = ? WHERE username = 'admin'");
    $result = $stmt->execute([$hashed_password]);
    
    if ($result) {
        echo "<p style='color: green;'>✅ Password updated successfully!</p>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        
        // Verify the password works
        $stmt = $conn->prepare("SELECT password FROM admin_users WHERE username = 'admin'");
        $stmt->execute();
        $user = $stmt->fetch();
        
        $verify = password_verify($new_password, $user['password']);
        echo "<p><strong>Verification:</strong> " . ($verify ? "✅ Password works!" : "❌ Password failed") . "</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Failed to update password!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='admin_login.php'>← Go to Admin Login</a></p>";
?>
