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
        $tagline = trim($_POST['tagline'] ?? '');
        $about_me = trim($_POST['about_me'] ?? '');
        
        // Validation
        if (strlen($tagline) > 500) {
            $error_message = 'Tagline is too long (max 500 characters)';
        } elseif (strlen($about_me) > 5000) {
            $error_message = 'About me is too long (max 5000 characters)';
        } else {
            try {
                $profile_pic_update = '';
                $params = [$tagline, $about_me, $_SESSION['admin_id']];
                
                // Handle file upload
                if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
                    $validation = validateUpload($_FILES['profile_pic']);
                    
                    if ($validation['success']) {
                        $filename = generateUniqueFilename($_FILES['profile_pic']['name']);
                        $upload_path = UPLOAD_PATH . $filename;
                        
                        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_path)) {
                            $profile_pic_update = ', profile_pic = ?';
                            array_splice($params, -1, 0, $filename);
                        } else {
                            throw new Exception('Failed to upload file');
                        }
                    } else {
                        throw new Exception($validation['message']);
                    }
                }
                
                $sql = "UPDATE admin SET tagline = ?, about_me = ? $profile_pic_update WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                
                $success_message = 'Profile updated successfully!';
                
            } catch (Exception $e) {
                $error_message = $e->getMessage();
            }
        }
    }
}

// Get current data
try {
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch();
} catch (PDOException $e) {
    $error_message = 'Error loading profile data';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-container {
            max-width: 800px;
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
        
        .current-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border-color);
            margin-bottom: 1rem;
        }
        
        .image-upload-area {
            text-align: center;
            padding: 1rem;
            background: var(--bg-tertiary);
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .char-counter {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-align: right;
            margin-top: 0.25rem;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Profile Settings</h1>
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
        
        <form method="POST" enctype="multipart/form-data" class="card">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= generateCSRFToken() ?>">
            
            <h2>Profile Information</h2>
            
            <div class="grid grid-2">
                <div>
                    <div class="form-group">
                        <label for="tagline" class="form-label">Tagline</label>
                        <textarea id="tagline" 
                                  name="tagline" 
                                  class="form-textarea" 
                                  rows="3" 
                                  maxlength="500"
                                  placeholder="A brief tagline that appears on your homepage"><?= h($admin['tagline'] ?? '') ?></textarea>
                        <div class="char-counter">
                            <span id="tagline-count">0</span> / 500 characters
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="about_me" class="form-label">About Me</label>
                        <textarea id="about_me" 
                                  name="about_me" 
                                  class="form-textarea" 
                                  rows="8" 
                                  maxlength="5000"
                                  placeholder="Tell visitors about yourself, your background, and your interests"><?= h($admin['about_me'] ?? '') ?></textarea>
                        <div class="char-counter">
                            <span id="about-count">0</span> / 5000 characters
                        </div>
                    </div>
                </div>
                
                <div>
                    <div class="form-group">
                        <label class="form-label">Current Profile Picture</label>
                        <div class="image-upload-area">
                            <?php if ($admin['profile_pic']): ?>
                                <img src="../uploads/<?= h($admin['profile_pic']) ?>" 
                                     alt="Current profile picture" 
                                     class="current-image"
                                     id="current-image">
                            <?php else: ?>
                                <div class="current-image" style="background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                                    No Image
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_pic" class="form-label">Upload New Picture</label>
                        <input type="file" 
                               id="profile_pic" 
                               name="profile_pic" 
                               class="form-input" 
                               accept="image/jpeg,image/png,image/webp">
                        <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 0.5rem;">
                            Supported formats: JPG, PNG, WebP. Max size: 5MB
                        </small>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
        
        <div style="margin-top: 2rem;">
            <div class="card">
                <h3>Tips for a Great Profile</h3>
                <ul style="color: var(--text-secondary); line-height: 1.8;">
                    <li><strong>Tagline:</strong> Keep it concise and highlight your key strengths or career focus</li>
                    <li><strong>About Me:</strong> Include your background, current role, interests, and what makes you unique</li>
                    <li><strong>Profile Picture:</strong> Use a professional, high-quality photo that represents you well</li>
                    <li><strong>Keep it Updated:</strong> Regularly update your profile to reflect your current status and achievements</li>
                </ul>
            </div>
        </div>
    </div>
    
    <script>
        // Apply saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        
        // Character counters
        function updateCharCount(textareaId, counterId) {
            const textarea = document.getElementById(textareaId);
            const counter = document.getElementById(counterId);
            
            function updateCount() {
                counter.textContent = textarea.value.length;
            }
            
            textarea.addEventListener('input', updateCount);
            updateCount(); // Initial count
        }
        
        updateCharCount('tagline', 'tagline-count');
        updateCharCount('about_me', 'about-count');
        
        // Image preview
        document.getElementById('profile_pic').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const currentImage = document.getElementById('current-image');
                    if (currentImage) {
                        currentImage.src = e.target.result;
                        currentImage.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
