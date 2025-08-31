<?php
header('Content-Type: application/json');
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate inputs
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Insert message into database
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $result = $stmt->execute([$name, $email, $subject, $message]);
    
    if ($result) {
        // Optional: Send email notification (requires mail server configuration)
        $to = "naquib@example.com"; // Replace with your email
        $emailSubject = "Portfolio Contact: " . $subject;
        $emailBody = "Name: " . $name . "\n";
        $emailBody .= "Email: " . $email . "\n";
        $emailBody .= "Subject: " . $subject . "\n\n";
        $emailBody .= "Message:\n" . $message;
        $headers = "From: " . $email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        
        // Uncomment the line below if you have mail server configured
        // mail($to, $emailSubject, $emailBody, $headers);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Thank you for your message! I will get back to you soon.'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again.']);
    }
    
} catch (PDOException $e) {
    error_log("Contact form error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
}
?>
