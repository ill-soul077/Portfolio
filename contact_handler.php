<?php
// Handle contact form submission
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

try {
    require_once 'config/database.php';
    
    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        echo json_encode(['error' => 'Required fields are missing']);
        exit();
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email address']);
        exit();
    }
    
    // Get client info
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Connect to database
    $db = new Database();
    $conn = $db->getConnection();
    
    // Insert contact message
    $stmt = $conn->prepare("
        INSERT INTO contact_messages (name, email, subject, message, ip_address, user_agent) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([
        $name,
        $email,
        $subject,
        $message,
        $ip_address,
        $user_agent
    ]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Thank you for your message! I will get back to you soon.'
        ]);
    } else {
        throw new Exception('Failed to save message');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to send message',
        'details' => $e->getMessage()
    ]);
}
?>
