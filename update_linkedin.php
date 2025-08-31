<?php
require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Update LinkedIn URL
    $stmt = $conn->prepare('UPDATE social_profiles SET username = ?, url = ? WHERE platform = ?');
    $result = $stmt->execute([
        'hassan-mohammed-naquibul-hoque-1b11701b6', 
        'https://www.linkedin.com/in/hassan-mohammed-naquibul-hoque-1b11701b6', 
        'linkedin'
    ]);
    
    if ($result) {
        echo "LinkedIn URL updated successfully!\n";
        
        // Verify the update
        $check = $conn->prepare('SELECT * FROM social_profiles WHERE platform = ?');
        $check->execute(['linkedin']);
        $profile = $check->fetch();
        
        echo "Current LinkedIn data:\n";
        echo "Username: " . $profile['username'] . "\n";
        echo "URL: " . $profile['url'] . "\n";
    } else {
        echo "Failed to update LinkedIn URL\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
