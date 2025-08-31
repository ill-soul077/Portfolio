<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: admin_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Update database
        if (isset($_POST['education']) && is_array($_POST['education'])) {
            // Clear existing education records
            $conn->query("DELETE FROM education");
            
            // Insert new education records
            $stmt = $conn->prepare("INSERT INTO education (institution, area, study_type, start_date, end_date, gpa, achievements) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            foreach ($_POST['education'] as $index => $edu) {
                $institution = $edu['institution'] ?? '';
                $area = $edu['area'] ?? '';
                $study_type = $edu['study_type'] ?? '';
                $start_date = $edu['start_date'] ?? '';
                $end_date = $edu['end_date'] ?? '';
                $gpa = $edu['gpa'] ?? '';
                $achievements = $edu['achievements'] ?? '';
                
                $stmt->execute([$institution, $area, $study_type, $start_date, $end_date, $gpa, $achievements]);
            }
        }
        
        // Update JSON file
        $json_file = '../resume.json';
        if (file_exists($json_file)) {
            $json_data = json_decode(file_get_contents($json_file), true);
            
            // Update education section
            $json_data['education'] = [];
            if (isset($_POST['education']) && is_array($_POST['education'])) {
                foreach ($_POST['education'] as $edu) {
                    $json_data['education'][] = [
                        'institution' => $edu['institution'] ?? '',
                        'area' => $edu['area'] ?? '',
                        'studyType' => $edu['study_type'] ?? '',
                        'startDate' => $edu['start_date'] ?? '',
                        'endDate' => $edu['end_date'] ?? '',
                        'gpa' => $edu['gpa'] ?? '',
                        'courses' => !empty($edu['achievements']) ? [$edu['achievements']] : []
                    ];
                }
            }
            
            // Update academic highlights
            if (isset($_POST['academic_highlights']) && is_array($_POST['academic_highlights'])) {
                $json_data['academic_highlights'] = array_filter($_POST['academic_highlights']);
            }
            
            // Save updated JSON
            file_put_contents($json_file, json_encode($json_data, JSON_PRETTY_PRINT));
        }
        
        header('Location: admin/dashboard.php?success=education_updated');
        exit();
        
    } catch (Exception $e) {
        header('Location: admin/dashboard.php?error=' . urlencode($e->getMessage()));
        exit();
    }
}

// If not POST, redirect to dashboard
header('Location: admin/dashboard.php');
exit();
?>
