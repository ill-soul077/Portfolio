<?php
// Database connection test
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Portfolio Database Connection Test</h2>";

try {
    require_once 'config/database.php';
    
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test each table
    $tables = [
        'admin_users' => 'Admin Users',
        'portfolio_basics' => 'Portfolio Basics',
        'social_profiles' => 'Social Profiles',
        'skills' => 'Skills',
        'repositories' => 'Repositories',
        'work_experience' => 'Work Experience',
        'education' => 'Education',
        'academic_highlights' => 'Academic Highlights',
        'interests' => 'Interests',
        'contact_messages' => 'Contact Messages',
        'site_settings' => 'Site Settings'
    ];
    
    echo "<h3>Table Status:</h3>";
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr style='background: #f0f0f0;'><th style='padding: 8px;'>Table</th><th style='padding: 8px;'>Status</th><th style='padding: 8px;'>Records</th></tr>";
    
    foreach ($tables as $table => $name) {
        try {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM $table");
            $stmt->execute();
            $result = $stmt->fetch();
            $count = $result['count'];
            
            echo "<tr>";
            echo "<td style='padding: 8px;'>$name</td>";
            echo "<td style='padding: 8px; color: green;'>✅ OK</td>";
            echo "<td style='padding: 8px;'>$count</td>";
            echo "</tr>";
            
        } catch (Exception $e) {
            echo "<tr>";
            echo "<td style='padding: 8px;'>$name</td>";
            echo "<td style='padding: 8px; color: red;'>❌ Error</td>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($e->getMessage()) . "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    
    // Test API endpoint
    echo "<h3>API Test:</h3>";
    $api_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/api/portfolio.php';
    echo "<p>API URL: <a href='$api_url' target='_blank'>$api_url</a></p>";
    
    // Test admin login
    echo "<h3>Admin Access:</h3>";
    $admin_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/admin_login.php';
    echo "<p>Admin Login: <a href='$admin_url' target='_blank'>$admin_url</a></p>";
    echo "<p><strong>Default Credentials:</strong><br>";
    echo "Username: admin<br>";
    echo "Password: admin123</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed!</p>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please check your database configuration and make sure the database is imported.</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { border: 1px solid #ddd; }
th, td { border: 1px solid #ddd; text-align: left; }
th { background-color: #f2f2f2; font-weight: bold; }
</style>
