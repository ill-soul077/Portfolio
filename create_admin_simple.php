<?php
// Simple Admin User Creation Tool

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';

echo "<h2>Simple Admin User Creator</h2>";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        echo "<p style='color: red;'>❌ Username and password are required!</p>";
    } else {
        try {
            $db = new Database();
            $conn = $db->getConnection();
            
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Check if user already exists
            $checkStmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ?");
            $checkStmt->execute([$username]);
            
            if ($checkStmt->fetch()) {
                // Update existing user
                $stmt = $conn->prepare("UPDATE admin_users SET password = ?, is_active = 1 WHERE username = ?");
                $result = $stmt->execute([$hashedPassword, $username]);
                
                if ($result) {
                    echo "<p style='color: green;'>✅ User '$username' password updated successfully!</p>";
                } else {
                    echo "<p style='color: red;'>❌ Failed to update user.</p>";
                }
            } else {
                // Create new user
                $stmt = $conn->prepare("INSERT INTO admin_users (username, password, is_active) VALUES (?, ?, 1)");
                $result = $stmt->execute([$username, $hashedPassword]);
                
                if ($result) {
                    echo "<p style='color: green;'>✅ User '$username' created successfully!</p>";
                } else {
                    echo "<p style='color: red;'>❌ Failed to create user.</p>";
                }
            }
            
            echo "<div style='background: #e8f5e8; padding: 15px; margin: 15px 0; border-radius: 5px;'>";
            echo "<h3>🎉 Login Details:</h3>";
            echo "<p><strong>Username:</strong> $username</p>";
            echo "<p><strong>Password:</strong> $password</p>";
            echo "<p><a href='admin_login.php' style='color: #007cba;'>→ Go to Admin Login</a></p>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}

// Display current users
try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare("SELECT username, is_active, last_login FROM admin_users ORDER BY username");
    $stmt->execute();
    $users = $stmt->fetchAll();
    
    if ($users) {
        echo "<h3>Current Admin Users:</h3>";
        echo "<ul>";
        foreach ($users as $user) {
            $status = $user['is_active'] ? '✅ Active' : '❌ Inactive';
            $lastLogin = $user['last_login'] ? $user['last_login'] : 'Never';
            echo "<li><strong>" . htmlspecialchars($user['username']) . "</strong> - $status (Last: $lastLogin)</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>⚠️ No admin users found in database!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error loading users: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 20px; 
    max-width: 600px; 
    background: #f5f5f5; 
}
.form-container { 
    background: white; 
    padding: 30px; 
    border-radius: 10px; 
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin: 20px 0;
}
input[type="text"], input[type="password"] { 
    width: 100%; 
    padding: 12px; 
    margin: 8px 0; 
    border: 2px solid #ddd; 
    border-radius: 5px; 
    font-size: 16px;
}
button { 
    background: #007cba; 
    color: white; 
    padding: 12px 30px; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer; 
    font-size: 16px;
    width: 100%;
}
button:hover { background: #005a87; }
label { font-weight: bold; color: #333; }
</style>

<div class="form-container">
    <h3>🔐 Create Admin User</h3>
    <form method="POST">
        <p>
            <label>Username:</label><br>
            <input type="text" name="username" required placeholder="Enter username" value="admin">
        </p>
        <p>
            <label>Password:</label><br>
            <input type="password" name="password" required placeholder="Enter password" value="admin123">
        </p>
        <button type="submit">Create/Update User</button>
    </form>
</div>

<div class="form-container">
    <h3>🔗 Quick Links</h3>
    <p>
        <a href="admin_login.php" style="color: #007cba; text-decoration: none; font-weight: bold;">→ Admin Login Page</a><br><br>
        <a href="test_database.php" style="color: #007cba; text-decoration: none;">→ Database Test</a><br><br>
        <a href="Naquib.htm" style="color: #007cba; text-decoration: none;">→ Portfolio Home</a>
    </p>
</div>
