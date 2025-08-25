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
$action = $_GET['action'] ?? 'list';
$achievement_id = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $error_message = 'Invalid security token. Please try again.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $date_achieved = $_POST['date_achieved'] ?? null;
        
        // Validation
        if (empty($title)) {
            $error_message = 'Achievement title is required';
        } elseif (strlen($title) > 255) {
            $error_message = 'Title is too long';
        } elseif (strlen($description) > 1000) {
            $error_message = 'Description is too long';
        } else {
            try {
                if ($action === 'add') {
                    $stmt = $pdo->prepare("INSERT INTO achievements (title, description, category, date_achieved) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$title, $description, $category, $date_achieved ?: null]);
                    $success_message = 'Achievement added successfully!';
                    $action = 'list';
                } elseif ($action === 'edit' && $achievement_id) {
                    $stmt = $pdo->prepare("UPDATE achievements SET title = ?, description = ?, category = ?, date_achieved = ? WHERE id = ?");
                    $stmt->execute([$title, $description, $category, $date_achieved ?: null, $achievement_id]);
                    $success_message = 'Achievement updated successfully!';
                    $action = 'list';
                }
                
            } catch (PDOException $e) {
                $error_message = 'Database error occurred';
            }
        }
    }
}

// Handle delete action
if ($action === 'delete' && $achievement_id && $_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        try {
            $stmt = $pdo->prepare("DELETE FROM achievements WHERE id = ?");
            $stmt->execute([$achievement_id]);
            $success_message = 'Achievement deleted successfully!';
            $action = 'list';
        } catch (PDOException $e) {
            $error_message = 'Error deleting achievement';
        }
    }
}

// Get achievements for listing
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM achievements ORDER BY date_achieved DESC, created_at DESC");
        $achievements = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error_message = 'Error loading achievements';
        $achievements = [];
    }
}

// Get achievement for editing
if ($action === 'edit' && $achievement_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM achievements WHERE id = ?");
        $stmt->execute([$achievement_id]);
        $achievement = $stmt->fetch();
        
        if (!$achievement) {
            $error_message = 'Achievement not found';
            $action = 'list';
        }
    } catch (PDOException $e) {
        $error_message = 'Error loading achievement';
        $action = 'list';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Achievements - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-container {
            max-width: 1200px;
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
        
        .achievements-grid {
            display: grid;
            gap: 1.5rem;
        }
        
        .achievement-item {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .achievement-icon {
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            background: var(--accent-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }
        
        .achievement-content {
            flex: 1;
        }
        
        .achievement-title {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: var(--accent-primary);
        }
        
        .achievement-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            color: var(--text-muted);
        }
        
        .achievement-category {
            background: var(--bg-tertiary);
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.8rem;
        }
        
        .achievement-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .achievement-form {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            max-width: 600px;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }
        
        .delete-confirmation {
            background: var(--bg-secondary);
            border: 1px solid var(--error);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            max-width: 500px;
        }
        
        .category-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .category-suggestion {
            background: var(--bg-tertiary);
            border: 1px solid var(--border-light);
            border-radius: 12px;
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .category-suggestion:hover {
            background: var(--accent-primary);
            color: white;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Manage Achievements</h1>
            <div>
                <?php if ($action === 'list'): ?>
                    <a href="?action=add" class="btn btn-primary">Add New Achievement</a>
                <?php endif; ?>
                <a href="dashboard.php" class="btn btn-secondary">← Dashboard</a>
            </div>
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
        
        <?php if ($action === 'list'): ?>
            <!-- Achievements List -->
            <?php if (empty($achievements)): ?>
                <div class="card" style="text-align: center; padding: 3rem;">
                    <h3>No achievements found</h3>
                    <p style="color: var(--text-secondary);">Add your first achievement to showcase your accomplishments.</p>
                    <a href="?action=add" class="btn btn-primary">Add Achievement</a>
                </div>
            <?php else: ?>
                <div class="achievements-grid">
                    <?php foreach ($achievements as $achievement): ?>
                        <div class="achievement-item">
                            <div class="achievement-icon">
                                🏆
                            </div>
                            
                            <div class="achievement-content">
                                <h3 class="achievement-title"><?= h($achievement['title']) ?></h3>
                                
                                <div class="achievement-meta">
                                    <?php if ($achievement['date_achieved']): ?>
                                        <span>📅 <?= date('F j, Y', strtotime($achievement['date_achieved'])) ?></span>
                                    <?php endif; ?>
                                    <?php if ($achievement['category']): ?>
                                        <span class="achievement-category"><?= h($achievement['category']) ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <p><?= h($achievement['description']) ?></p>
                                
                                <div class="achievement-actions">
                                    <a href="?action=edit&id=<?= $achievement['id'] ?>" 
                                       class="btn btn-secondary" 
                                       style="font-size: 0.8rem; padding: 0.25rem 0.75rem;">
                                        Edit
                                    </a>
                                    <a href="?action=delete&id=<?= $achievement['id'] ?>" 
                                       class="btn btn-danger" 
                                       style="font-size: 0.8rem; padding: 0.25rem 0.75rem;">
                                        Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
        <?php elseif ($action === 'add' || $action === 'edit'): ?>
            <!-- Add/Edit Achievement Form -->
            <form method="POST" class="achievement-form">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= generateCSRFToken() ?>">
                
                <h2><?= $action === 'add' ? 'Add New Achievement' : 'Edit Achievement' ?></h2>
                
                <div class="form-group">
                    <label for="title" class="form-label">Achievement Title *</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           class="form-input" 
                           required 
                           maxlength="255"
                           placeholder="e.g., Codeforces Specialist, Dean's Award"
                           value="<?= h($achievement['title'] ?? '') ?>">
                </div>
                
                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" 
                               id="category" 
                               name="category" 
                               class="form-input" 
                               maxlength="100"
                               placeholder="e.g., Academic, Competition, Certification"
                               value="<?= h($achievement['category'] ?? '') ?>">
                        
                        <div class="category-suggestions">
                            <span class="category-suggestion" onclick="setCategory('Academic Achievement')">Academic Achievement</span>
                            <span class="category-suggestion" onclick="setCategory('Competitive Programming')">Competitive Programming</span>
                            <span class="category-suggestion" onclick="setCategory('Certification')">Certification</span>
                            <span class="category-suggestion" onclick="setCategory('Award')">Award</span>
                            <span class="category-suggestion" onclick="setCategory('Recognition')">Recognition</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_achieved" class="form-label">Date Achieved</label>
                        <input type="date" 
                               id="date_achieved" 
                               name="date_achieved" 
                               class="form-input"
                               value="<?= h($achievement['date_achieved'] ?? '') ?>">
                        <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 0.5rem;">
                            Optional - leave blank if date is unknown
                        </small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" 
                              name="description" 
                              class="form-textarea" 
                              rows="4" 
                              maxlength="1000"
                              placeholder="Describe your achievement and its significance..."><?= h($achievement['description'] ?? '') ?></textarea>
                    <div style="font-size: 0.8rem; color: var(--text-muted); text-align: right; margin-top: 0.25rem;">
                        <span id="desc-count">0</span> / 1000 characters
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="?action=list" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <?= $action === 'add' ? 'Add Achievement' : 'Update Achievement' ?>
                    </button>
                </div>
            </form>
            
        <?php elseif ($action === 'delete'): ?>
            <!-- Delete Confirmation -->
            <div class="delete-confirmation">
                <h2 style="color: var(--error);">Confirm Deletion</h2>
                <p>Are you sure you want to delete this achievement? This action cannot be undone.</p>
                
                <div style="margin-top: 2rem;">
                    <a href="?action=list" class="btn btn-secondary" style="margin-right: 1rem;">Cancel</a>
                    <a href="?action=delete&id=<?= h($achievement_id) ?>&confirm=yes" class="btn btn-danger">Yes, Delete</a>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($action === 'list'): ?>
            <!-- Tips Section -->
            <div class="card" style="margin-top: 2rem;">
                <h3>Tips for Great Achievements</h3>
                <ul style="color: var(--text-secondary); line-height: 1.8;">
                    <li><strong>Be Specific:</strong> Include details like ratings, scores, or rankings when relevant</li>
                    <li><strong>Add Context:</strong> Explain why the achievement is significant or what it demonstrates</li>
                    <li><strong>Use Categories:</strong> Group similar achievements to help visitors understand your strengths</li>
                    <li><strong>Stay Current:</strong> Regularly update with new accomplishments and remove outdated ones</li>
                    <li><strong>Quantify When Possible:</strong> Include numbers, percentages, or rankings to show impact</li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Apply saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        
        // Character counter for description
        const descTextarea = document.getElementById('description');
        const descCounter = document.getElementById('desc-count');
        
        if (descTextarea && descCounter) {
            function updateDescCount() {
                descCounter.textContent = descTextarea.value.length;
            }
            
            descTextarea.addEventListener('input', updateDescCount);
            updateDescCount(); // Initial count
        }
        
        // Category suggestions
        function setCategory(category) {
            const categoryInput = document.getElementById('category');
            if (categoryInput) {
                categoryInput.value = category;
            }
        }
    </script>
</body>
</html>
