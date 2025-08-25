<?php
require_once '../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

try {
    $stmt = $pdo->prepare("SELECT * FROM achievements ORDER BY date_achieved DESC");
    $stmt->execute();
    $achievements = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'achievements' => $achievements
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred'
    ]);
}
?>
