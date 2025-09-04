<?php
require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Create achievements table
    $createTable = "
    CREATE TABLE IF NOT EXISTS achievements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        year VARCHAR(10) NOT NULL,
        description TEXT,
        category VARCHAR(100),
        icon VARCHAR(100),
        color VARCHAR(20),
        display_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $conn->exec($createTable);
    echo "Achievements table created successfully!\n";
    
    // Clear existing achievements
    $stmt = $conn->prepare("DELETE FROM achievements");
    $stmt->execute();
    
    // Insert achievements data
    $achievements = [
        [
            'title' => "Dean's List",
            'year' => '2025',
            'description' => "Achieved Dean's List recognition for outstanding academic performance and maintaining excellent GPA throughout the academic year.",
            'category' => 'Academic',
            'icon' => 'fas fa-trophy',
            'color' => '#FFD700',
            'display_order' => 1
        ],
        [
            'title' => 'Specialist in Codeforces',
            'year' => '2024',
            'description' => 'Achieved Specialist rating in Codeforces competitive programming platform, demonstrating strong problem-solving and algorithmic skills.',
            'category' => 'Programming',
            'icon' => 'fas fa-code',
            'color' => '#ECB365',
            'display_order' => 2
        ],
        [
            'title' => '14th Place - KU Regional IUPC',
            'year' => '2024',
            'description' => 'Secured 14th position in Khulna University Regional Inter-University Programming Contest, competing against top programming teams from various universities.',
            'category' => 'Competition',
            'icon' => 'fas fa-medal',
            'color' => '#CD7F32',
            'display_order' => 3
        ],
        [
            'title' => '93rd Place - KUET IUPC',
            'year' => '2024',
            'description' => 'Ranked 93rd in KUET Inter-University Programming Contest, showcasing competitive programming expertise and teamwork skills in a challenging environment.',
            'category' => 'Competition',
            'icon' => 'fas fa-award',
            'color' => '#C0C0C0',
            'display_order' => 4
        ]
    ];
    
    $stmt = $conn->prepare("
        INSERT INTO achievements 
        (title, year, description, category, icon, color, display_order, is_active) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 1)
    ");
    
    foreach ($achievements as $achievement) {
        $stmt->execute([
            $achievement['title'],
            $achievement['year'],
            $achievement['description'],
            $achievement['category'],
            $achievement['icon'],
            $achievement['color'],
            $achievement['display_order']
        ]);
    }
    
    echo "Achievements data inserted successfully!\n";
    echo "Added 4 achievements:\n";
    echo "1. Dean's List (2025)\n";
    echo "2. Specialist in Codeforces (2024)\n";
    echo "3. 14th Place - KU Regional IUPC (2024)\n";
    echo "4. 93rd Place - KUET IUPC (2024)\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
