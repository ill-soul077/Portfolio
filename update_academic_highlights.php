<?php
require_once 'config/database.php';

try {
    // Connect to database
    $db = new Database();
    $conn = $db->getConnection();
    
    // Create academic_highlights table if it doesn't exist
    $create_table_sql = "
    CREATE TABLE IF NOT EXISTS academic_highlights (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $conn->exec($create_table_sql);
    echo "✅ Academic highlights table created/verified!<br><br>";
    
    // Clear existing academic highlights
    $conn->query("DELETE FROM academic_highlights");
    
    // Insert academic highlights data with title and description
    $highlights = [
        [
            'title' => 'B.Sc. in Computer Science & Engineering',
            'description' => 'Currently pursuing Bachelor\'s degree at KUET'
        ],
        [
            'title' => 'CGPA: 3.72 (till present)',
            'description' => 'Maintaining excellent academic performance'
        ],
        [
            'title' => 'Dean\'s Award recipient',
            'description' => 'Recognized for outstanding academic achievement'
        ],
        [
            'title' => 'Higher Secondary Certificate (HSC), GPA: 5.00',
            'description' => 'Perfect score in Higher Secondary examination'
        ],
        [
            'title' => 'Secondary School Certificate (SSC), GPA: 5.00',
            'description' => 'Perfect score in Secondary School examination'
        ]
    ];
    
    $stmt = $conn->prepare("INSERT INTO academic_highlights (title, description) VALUES (?, ?)");
    
    foreach ($highlights as $highlight) {
        $stmt->execute([$highlight['title'], $highlight['description']]);
    }
    
    echo "✅ Academic highlights updated successfully!<br><br>";
    
    // Display the updated data
    $stmt = $conn->query("SELECT * FROM academic_highlights ORDER BY id");
    $academic_highlights = $stmt->fetchAll();
    
    echo "<h3>Academic Highlights:</h3>";
    foreach ($academic_highlights as $highlight) {
        echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 5px 0; border-radius: 5px;'>";
        echo "<strong>" . htmlspecialchars($highlight['title']) . "</strong><br>";
        if (!empty($highlight['description'])) {
            echo "<em>" . htmlspecialchars($highlight['description']) . "</em>";
        }
        echo "</div>";
    }
    
    echo "<br><a href='admin/generate_resume.php' style='padding: 10px; background: #007cba; color: white; text-decoration: none; border-radius: 5px;'>Generate Updated Resume JSON</a>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
