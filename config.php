<?php
// Database configuration
define('DB_HOST', 'localhost:4306');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'portfolio_db');

// Application configuration
define('UPLOAD_PATH', __DIR__ . '/uploads/');
define('UPLOAD_URL', '/portfolio/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp']);

// Security configuration
define('SESSION_TIMEOUT', 3600); // 1 hour
define('CSRF_TOKEN_NAME', 'csrf_token');

// Site configuration
define('SITE_NAME', 'Naquib Portfolio');
define('ADMIN_EMAIL', 'admin@naquib.com');

// Database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRF token generation
function generateCSRFToken() {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

// CSRF token validation
function validateCSRFToken($token) {
    return hash_equals($_SESSION[CSRF_TOKEN_NAME] ?? '', $token);
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && 
           isset($_SESSION['admin_username']) &&
           (time() - $_SESSION['last_activity']) < SESSION_TIMEOUT;
}

// Update last activity
function updateLastActivity() {
    $_SESSION['last_activity'] = time();
}

// Sanitize output for XSS prevention
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Validate file upload
function validateUpload($file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Upload error occurred.'];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File size exceeds maximum allowed size.'];
    }
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($mimeType, $allowedMimes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and WebP are allowed.'];
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        return ['success' => false, 'message' => 'Invalid file extension.'];
    }
    
    return ['success' => true];
}

// Generate unique filename
function generateUniqueFilename($originalName) {
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    return uniqid() . '_' . time() . '.' . $extension;
}
?>
