<?php
require_once 'config/database.php';

echo "<h2>Admin Password Debug</h2>";

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Get the admin user
    $stmt = $conn->prepare("SELECT username, password FROM admin_users WHERE username = 'admin'");
    $stmt->execute();
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p><strong>Username:</strong> " . $user['username'] . "</p>";
        echo "<p><strong>Stored Password Hash:</strong> " . substr($user['password'], 0, 30) . "...</p>";
        
        // Test password verification
        $test_passwords = ['admin123', 'admin', 'password', '123456'];
        
        echo "<h3>Testing Common Passwords:</h3>";
        foreach ($test_passwords as $test_pass) {
            $verify = password_verify($test_pass, $user['password']);
            $status = $verify ? "✅ CORRECT" : "❌ Wrong";
            echo "<p>Password '$test_pass': $status</p>";
        }
        
        // Show how to create a new password hash
        echo "<h3>Create New Password:</h3>";
        echo "<p>To set password 'admin123': " . password_hash('admin123', PASSWORD_DEFAULT) . "</p>";
        
    } else {
        echo "<p style='color: red;'>No admin user found!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
