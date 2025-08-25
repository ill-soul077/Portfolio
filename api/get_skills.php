<?php
require_once '../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

try {
    $stmt = $pdo->prepare("SELECT * FROM skills ORDER BY skill_category, skill_name");
    $stmt->execute();
    $skills = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'skills' => $skills
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred'
    ]);
}
?>
