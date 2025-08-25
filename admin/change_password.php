<?php
require_once '../config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

updateLastActivity();

$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $error_message = 'Invalid security token. Please try again.';
    } else {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error_message = 'All fields are required';
        } elseif (strlen($new_password) < 8) {
            $error_message = 'New password must be at least 8 characters long';
        } elseif ($new_password !== $confirm_password) {
            $error_message = 'New passwords do not match';
        } else {
            try {
                // Verify current password
                $stmt = $pdo->prepare("SELECT password_hash FROM admin WHERE id = ?");
                $stmt->execute([$_SESSION['admin_id']]);
                $admin = $stmt->fetch();
                
                if (!$admin || !password_verify($current_password, $admin['password_hash'])) {
                    $error_message = 'Current password is incorrect';
                } else {
                    // Update password
                    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE admin SET password_hash = ? WHERE id = ?");
                    $stmt->execute([$new_password_hash, $_SESSION['admin_id']]);
                    
                    $success_message = 'Password changed successfully!';
                    
                    // Clear form data
                    $_POST = [];
                }
            } catch (PDOException $e) {
                $error_message = 'Database error occurred';
                error_log('Password change error: ' . $e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .password-form {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 2rem;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }
        
        .password-requirements {
            background: var(--bg-tertiary);
            border-radius: 6px;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .password-requirements ul {
            margin: 0;
            padding-left: 1.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .password-requirements li {
            margin-bottom: 0.5rem;
        }
        
        .password-strength {
            height: 4px;
            background: var(--bg-tertiary);
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }
        
        .strength-weak { background: var(--error); }
        .strength-medium { background: var(--warning); }
        .strength-strong { background: var(--success); }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Change Password</h1>
            <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
        </div>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?= h($success_message) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-error">
                <?= h($error_message) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="password-form">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= generateCSRFToken() ?>">
            
            <h2>Update Your Password</h2>
            <p style="color: var(--text-secondary); margin-bottom: 2rem;">
                Choose a strong password to keep your portfolio secure.
            </p>
            
            <div class="form-group">
                <label for="current_password" class="form-label">Current Password *</label>
                <input type="password" 
                       id="current_password" 
                       name="current_password" 
                       class="form-input" 
                       required 
                       autocomplete="current-password">
            </div>
            
            <div class="form-group">
                <label for="new_password" class="form-label">New Password *</label>
                <input type="password" 
                       id="new_password" 
                       name="new_password" 
                       class="form-input" 
                       required 
                       minlength="8"
                       autocomplete="new-password">
                <div class="password-strength">
                    <div class="password-strength-bar" id="strength-bar"></div>
                </div>
                <small id="strength-text" style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 0.5rem;">
                    Password strength will be shown here
                </small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirm New Password *</label>
                <input type="password" 
                       id="confirm_password" 
                       name="confirm_password" 
                       class="form-input" 
                       required 
                       minlength="8"
                       autocomplete="new-password">
                <small id="match-text" style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 0.5rem;">
                    Passwords must match
                </small>
            </div>
            
            <div class="password-requirements">
                <h4>Password Requirements:</h4>
                <ul>
                    <li>At least 8 characters long</li>
                    <li>Mix of uppercase and lowercase letters</li>
                    <li>Include at least one number</li>
                    <li>Include at least one special character (!@#$%^&*)</li>
                    <li>Avoid using personal information</li>
                </ul>
            </div>
            
            <div class="form-actions">
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                    Change Password
                </button>
            </div>
        </form>
        
        <div class="card" style="margin-top: 2rem;">
            <h3>Security Tips</h3>
            <ul style="color: var(--text-secondary); line-height: 1.8;">
                <li><strong>Use a unique password:</strong> Don't reuse passwords from other accounts</li>
                <li><strong>Consider a password manager:</strong> Tools like 1Password or Bitwarden can help</li>
                <li><strong>Enable two-factor authentication:</strong> Add an extra layer of security when possible</li>
                <li><strong>Change regularly:</strong> Update your password every few months</li>
                <li><strong>Keep it private:</strong> Never share your admin password with anyone</li>
            </ul>
        </div>
    </div>
    
    <script>
        // Apply saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        const matchText = document.getElementById('match-text');
        const submitBtn = document.getElementById('submit-btn');
        
        function checkPasswordStrength(password) {
            let score = 0;
            let feedback = [];
            
            if (password.length >= 8) score++;
            else feedback.push('at least 8 characters');
            
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;
            else feedback.push('uppercase and lowercase letters');
            
            if (/\d/.test(password)) score++;
            else feedback.push('at least one number');
            
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) score++;
            else feedback.push('a special character');
            
            return { score, feedback };
        }
        
        function updatePasswordStrength() {
            const password = newPasswordInput.value;
            const { score, feedback } = checkPasswordStrength(password);
            
            let strengthClass = '';
            let strengthLabel = '';
            let width = '0%';
            
            if (password.length === 0) {
                strengthLabel = 'Enter a password';
                width = '0%';
            } else if (score <= 1) {
                strengthClass = 'strength-weak';
                strengthLabel = 'Weak password';
                width = '25%';
            } else if (score <= 2) {
                strengthClass = 'strength-weak';
                strengthLabel = 'Fair password';
                width = '50%';
            } else if (score <= 3) {
                strengthClass = 'strength-medium';
                strengthLabel = 'Good password';
                width = '75%';
            } else {
                strengthClass = 'strength-strong';
                strengthLabel = 'Strong password';
                width = '100%';
            }
            
            strengthBar.className = `password-strength-bar ${strengthClass}`;
            strengthBar.style.width = width;
            
            if (feedback.length > 0 && password.length > 0) {
                strengthText.textContent = `${strengthLabel} - Missing: ${feedback.join(', ')}`;
            } else {
                strengthText.textContent = strengthLabel;
            }
            
            checkFormValidity();
        }
        
        function checkPasswordMatch() {
            const password = newPasswordInput.value;
            const confirm = confirmPasswordInput.value;
            
            if (confirm.length === 0) {
                matchText.textContent = 'Passwords must match';
                matchText.style.color = 'var(--text-muted)';
            } else if (password === confirm) {
                matchText.textContent = 'Passwords match ✓';
                matchText.style.color = 'var(--success)';
            } else {
                matchText.textContent = 'Passwords do not match';
                matchText.style.color = 'var(--error)';
            }
            
            checkFormValidity();
        }
        
        function checkFormValidity() {
            const password = newPasswordInput.value;
            const confirm = confirmPasswordInput.value;
            const { score } = checkPasswordStrength(password);
            
            const isValid = password.length >= 8 && 
                           confirm.length >= 8 && 
                           password === confirm && 
                           score >= 3;
            
            submitBtn.disabled = !isValid;
        }
        
        newPasswordInput.addEventListener('input', updatePasswordStrength);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        
        // Focus on first input
        document.getElementById('current_password').focus();
    </script>
</body>
</html>
