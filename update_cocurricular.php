<?php
require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Clear existing work experience data
    $stmt = $conn->prepare("DELETE FROM work_experience");
    $stmt->execute();
    
    // Insert new co-curricular activities
    $activities = [
        [
            'position' => 'Workshop Manager',
            'company_name' => 'SGIPC, KUET',
            'start_date' => '2025-01-01',
            'end_date' => '2026-12-31',
            'location' => 'Khulna, Bangladesh',
            'summary' => 'Leading workshop organization and management activities for the Software and Game Industry Preparation Club at Khulna University of Engineering & Technology. Coordinating technical workshops, training sessions, and skill development programs for students interested in software development and game industry careers.',
            'is_current' => 1,
            'display_order' => 1
        ],
        [
            'position' => 'Assistant Workshop Manager',
            'company_name' => 'SGIPC, KUET',
            'start_date' => '2024-01-01',
            'end_date' => '2025-12-31',
            'location' => 'Khulna, Bangladesh',
            'summary' => 'Assisted in organizing technical workshops and training programs. Coordinated with industry professionals for guest lectures and hands-on sessions. Managed event logistics and student participation in competitive programming and software development workshops.',
            'is_current' => 0,
            'display_order' => 2
        ],
        [
            'position' => 'Secretary of Field & Voluntary Executive',
            'company_name' => 'Notre Dame Math Club',
            'start_date' => '2020-01-01',
            'end_date' => '2021-12-31',
            'location' => 'Dhaka, Bangladesh',
            'summary' => 'Managed mathematical competitions, olympiad preparations, and academic events. Coordinated volunteer activities for educational outreach programs. Organized math camps and problem-solving sessions for junior students, fostering mathematical thinking and competitive spirit.',
            'is_current' => 0,
            'display_order' => 3
        ]
    ];
    
    $stmt = $conn->prepare("
        INSERT INTO work_experience 
        (position, company_name, start_date, end_date, location, summary, is_current, display_order) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    foreach ($activities as $activity) {
        $stmt->execute([
            $activity['position'],
            $activity['company_name'],
            $activity['start_date'],
            $activity['end_date'],
            $activity['location'],
            $activity['summary'],
            $activity['is_current'],
            $activity['display_order']
        ]);
    }
    
    echo "Co-curricular activities updated successfully!\n";
    echo "Added 3 activities:\n";
    echo "1. Workshop Manager - SGIPC, KUET (2025-2026)\n";
    echo "2. Assistant Workshop Manager - SGIPC, KUET (2024-2025)\n";
    echo "3. Secretary of Field & Voluntary Executive - Notre Dame Math Club (2020-2021)\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
