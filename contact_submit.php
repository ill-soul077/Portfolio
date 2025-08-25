<?php
require_once 'config.php';

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get and validate input
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];

// Validation
if (empty($name)) {
    $errors[] = 'Name is required';
} elseif (strlen($name) > 255) {
    $errors[] = 'Name is too long';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
} elseif (strlen($email) > 255) {
    $errors[] = 'Email is too long';
}

if (empty($message)) {
    $errors[] = 'Message is required';
} elseif (strlen($message) < 10) {
    $errors[] = 'Message must be at least 10 characters long';
} elseif (strlen($message) > 5000) {
    $errors[] = 'Message is too long';
}

// Return validation errors
if (!empty($errors)) {
    echo json_encode([
        'success' => false,
        'message' => implode(', ', $errors)
    ]);
    exit;
}

// Basic spam protection
$spam_keywords = ['viagra', 'casino', 'lottery', 'bitcoin', 'crypto'];
$content_to_check = strtolower($name . ' ' . $email . ' ' . $message);

foreach ($spam_keywords as $keyword) {
    if (strpos($content_to_check, $keyword) !== false) {
        echo json_encode([
            'success' => false,
            'message' => 'Message detected as spam'
        ]);
        exit;
    }
}

// Rate limiting (simple implementation)
$ip = $_SERVER['REMOTE_ADDR'];
$current_time = time();
$rate_limit_file = __DIR__ . '/tmp/rate_limit.json';

// Create tmp directory if it doesn't exist
if (!is_dir(__DIR__ . '/tmp')) {
    mkdir(__DIR__ . '/tmp', 0755, true);
}

$rate_limits = [];
if (file_exists($rate_limit_file)) {
    $rate_limits = json_decode(file_get_contents($rate_limit_file), true) ?: [];
}

// Clean old entries (older than 1 hour)
$rate_limits = array_filter($rate_limits, function($time) use ($current_time) {
    return ($current_time - $time) < 3600;
});

// Check if IP has submitted too many messages (max 5 per hour)
$ip_submissions = array_filter($rate_limits, function($time, $stored_ip) use ($ip) {
    return $stored_ip === $ip;
}, ARRAY_FILTER_USE_BOTH);

if (count($ip_submissions) >= 5) {
    echo json_encode([
        'success' => false,
        'message' => 'Too many submissions. Please try again later.'
    ]);
    exit;
}

try {
    // Insert message into database
    $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $result = $stmt->execute([$name, $email, $message]);
    
    if ($result) {
        // Update rate limiting
        $rate_limits[$ip] = $current_time;
        file_put_contents($rate_limit_file, json_encode($rate_limits));
        
        echo json_encode([
            'success' => true,
            'message' => 'Message sent successfully!'
        ]);
        
        // Optional: Send email notification to admin
        // mail(ADMIN_EMAIL, 'New Contact Form Submission', $message, "From: $email");
        
    } else {
        throw new Exception('Failed to insert message');
    }
    
} catch (PDOException $e) {
    error_log('Contact form error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred. Please try again later.'
    ]);
} catch (Exception $e) {
    error_log('Contact form error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred. Please try again later.'
    ]);
}
?>
