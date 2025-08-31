<?php
header('Content-Type: application/json');

try {
    require_once '../config/database.php';
    echo json_encode(['status' => 'Database config loaded successfully']);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database config error: ' . $e->getMessage()]);
}
?>
