<?php
require_once '../config/database.php';

try {
    // Connect to database
    $db = new Database();
    $conn = $db->getConnection();
    
    // Clear existing education data
    $conn->query("DELETE FROM education");
    
    // Insert new education data with additional fields
    $education_data = [
        [
            'institution' => 'Khulna University of Engineering and Technology (KUET)',
            'area' => 'Computer Science & Engineering',
            'study_type' => 'B.Sc.',
            'start_date' => '2023',
            'end_date' => 'present',
            'gpa' => '3.72',
            'achievement' => 'Dean\'s Award recipient'
        ],
        [
            'institution' => 'Notre Dame College, Dhaka',
            'area' => 'Science',
            'study_type' => 'Higher Secondary Certificate (HSC)',
            'start_date' => '2019',
            'end_date' => '2022',
            'gpa' => '5.00',
            'achievement' => ''
        ],
        [
            'institution' => 'Cox\'s Bazar Government High School',
            'area' => 'Science',
            'study_type' => 'Secondary School Certificate (SSC)',
            'start_date' => '2014',
            'end_date' => '2019',
            'gpa' => '5.00',
            'achievement' => ''
        ]
    ];

    $stmt = $conn->prepare("INSERT INTO education (institution, area, study_type, start_date, end_date, gpa, achievement) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($education_data as $edu) {
        $stmt->execute([
            $edu['institution'],
            $edu['area'],
            $edu['study_type'],
            $edu['start_date'],
            $edu['end_date'],
            $edu['gpa'],
            $edu['achievement']
        ]);
    }
    
    echo "✅ Education data updated successfully!<br><br>";
    
    // Display the updated data
    $stmt = $conn->query("SELECT * FROM education ORDER BY end_date DESC");
    $education = $stmt->fetchAll();
    
    echo "<h3>Updated Education Records:</h3>";
    foreach ($education as $edu) {
        echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
        echo "<strong>" . htmlspecialchars($edu['institution']) . "</strong><br>";
        echo "Area: " . htmlspecialchars($edu['area']) . "<br>";
        echo "Type: " . htmlspecialchars($edu['study_type']) . "<br>";
        echo "Period: " . htmlspecialchars($edu['start_date']) . " - " . htmlspecialchars($edu['end_date']) . "<br>";
        if (!empty($edu['gpa'])) {
            echo "GPA: " . htmlspecialchars($edu['gpa']) . "<br>";
        }
        if (!empty($edu['achievement'])) {
            echo "Achievement: " . htmlspecialchars($edu['achievement']) . "<br>";
        }
        echo "</div>";
    }
    
    echo "<br><a href='admin/generate_resume.php' style='padding: 10px; background: #007cba; color: white; text-decoration: none; border-radius: 5px;'>Generate Updated Resume JSON</a>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
