<?php
header('Content-Type: application/json');

// Test 1: Basic JSON output
echo json_encode(['test' => 'API is working', 'timestamp' => date('Y-m-d H:i:s')]);
?>
