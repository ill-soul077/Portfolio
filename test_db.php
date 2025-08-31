<?php
require_once 'config/database.php';

echo "<h2>Database Connection Test</h2>";

try {
    $db = new Database();
    $connection = $db->getConnection();
    
    if ($connection) {
        echo "<p style='color: green;'>✓ Database connection successful!</p>";
        echo "<p>Host: " . DB_HOST . "</p>";
        echo "<p>Port: " . DB_PORT . "</p>";
        echo "<p>Database: " . DB_NAME . "</p>";
        
        // Test a simple query
        $stmt = $connection->query("SELECT VERSION() as version");
        $result = $stmt->fetch();
        echo "<p>MySQL Version: " . $result['version'] . "</p>";
        
    } else {
        echo "<p style='color: red;'>✗ Database connection failed!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}
?>
