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
$skill_id = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $error_message = 'Invalid security token. Please try again.';
    } else {
        $skill_name = trim($_POST['skill_name'] ?? '');
        $skill_category = $_POST['skill_category'] ?? 'Other';
        $proficiency_level = $_POST['proficiency_level'] ?? 'Intermediate';
        
        // Validation
        if (empty($skill_name)) {
            $error_message = 'Skill name is required';
        } elseif (strlen($skill_name) > 100) {
            $error_message = 'Skill name is too long';
        } else {
            try {
                if ($action === 'add') {
                    // Check for duplicates
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM skills WHERE skill_name = ? AND skill_category = ?");
                    $stmt->execute([$skill_name, $skill_category]);
                    
                    if ($stmt->fetchColumn() > 0) {
                        $error_message = 'This skill already exists in the selected category';
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO skills (skill_name, skill_category, proficiency_level) VALUES (?, ?, ?)");
                        $stmt->execute([$skill_name, $skill_category, $proficiency_level]);
                        $success_message = 'Skill added successfully!';
                        $action = 'list';
                    }
                } elseif ($action === 'edit' && $skill_id) {
                    $stmt = $pdo->prepare("UPDATE skills SET skill_name = ?, skill_category = ?, proficiency_level = ? WHERE id = ?");
                    $stmt->execute([$skill_name, $skill_category, $proficiency_level, $skill_id]);
                    $success_message = 'Skill updated successfully!';
                    $action = 'list';
                }
                
            } catch (PDOException $e) {
                $error_message = 'Database error occurred';
            }
        }
    }
}

// Handle delete action
if ($action === 'delete' && $skill_id && $_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        try {
            $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
            $stmt->execute([$skill_id]);
            $success_message = 'Skill deleted successfully!';
            $action = 'list';
        } catch (PDOException $e) {
            $error_message = 'Error deleting skill';
        }
    }
}

// Get skills for listing
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM skills ORDER BY skill_category, skill_name");
        $all_skills = $stmt->fetchAll();
        
        // Group by category
        $skills_by_category = [];
        foreach ($all_skills as $skill) {
            $skills_by_category[$skill['skill_category']][] = $skill;
        }
    } catch (PDOException $e) {
        $error_message = 'Error loading skills';
        $skills_by_category = [];
    }
}

// Get skill for editing
if ($action === 'edit' && $skill_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM skills WHERE id = ?");
        $stmt->execute([$skill_id]);
        $skill = $stmt->fetch();
        
        if (!$skill) {
            $error_message = 'Skill not found';
            $action = 'list';
        }
    } catch (PDOException $e) {
        $error_message = 'Error loading skill';
        $action = 'list';
    }
}

$categories = ['Programming', 'Web', 'Database', 'Tools', 'Other'];
$proficiency_levels = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Skills - Admin Dashboard</title>
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
        
        .skills-grid {
            display: grid;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .skill-category-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.5rem;
        }
        
        .skill-category-title {
            color: var(--accent-primary);
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .skill-item {
            background: var(--bg-tertiary);
            border: 1px solid var(--border-light);
            border-radius: 20px;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        
        .skill-actions {
            display: flex;
            gap: 0.25rem;
        }
        
        .skill-actions button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.7rem;
            padding: 0.1rem 0.3rem;
            border-radius: 3px;
            color: var(--text-muted);
        }
        
        .skill-actions button:hover {
            background: var(--bg-primary);
        }
        
        .proficiency-badge {
            font-size: 0.7rem;
            padding: 0.1rem 0.4rem;
            border-radius: 10px;
            font-weight: 500;
        }
        
        .proficiency-beginner { background: #e3f2fd; color: #1976d2; }
        .proficiency-intermediate { background: #fff3e0; color: #f57c00; }
        .proficiency-advanced { background: #e8f5e8; color: #388e3c; }
        .proficiency-expert { background: #fce4ec; color: #c2185b; }
        
        .skill-form {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            max-width: 500px;
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
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Manage Skills</h1>
            <div>
                <?php if ($action === 'list'): ?>
                    <a href="?action=add" class="btn btn-primary">Add New Skill</a>
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
            <!-- Skills List -->
            <?php if (empty($skills_by_category)): ?>
                <div class="card" style="text-align: center; padding: 3rem;">
                    <h3>No skills found</h3>
                    <p style="color: var(--text-secondary);">Add your first skill to get started.</p>
                    <a href="?action=add" class="btn btn-primary">Add Skill</a>
                </div>
            <?php else: ?>
                <div class="skills-grid">
                    <?php foreach ($skills_by_category as $category => $skills): ?>
                        <div class="skill-category-card">
                            <div class="skill-category-title">
                                <h3><?= h($category) ?></h3>
                                <span style="color: var(--text-muted); font-size: 0.9rem;">
                                    <?= count($skills) ?> skill<?= count($skills) !== 1 ? 's' : '' ?>
                                </span>
                            </div>
                            
                            <div class="skills-list">
                                <?php foreach ($skills as $skill): ?>
                                    <div class="skill-item">
                                        <span><?= h($skill['skill_name']) ?></span>
                                        <span class="proficiency-badge proficiency-<?= strtolower($skill['proficiency_level']) ?>">
                                            <?= h($skill['proficiency_level']) ?>
                                        </span>
                                        <div class="skill-actions">
                                            <a href="?action=edit&id=<?= $skill['id'] ?>" 
                                               title="Edit skill">✏️</a>
                                            <a href="?action=delete&id=<?= $skill['id'] ?>" 
                                               title="Delete skill">🗑️</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
        <?php elseif ($action === 'add' || $action === 'edit'): ?>
            <!-- Add/Edit Skill Form -->
            <form method="POST" class="skill-form">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= generateCSRFToken() ?>">
                
                <h2><?= $action === 'add' ? 'Add New Skill' : 'Edit Skill' ?></h2>
                
                <div class="form-group">
                    <label for="skill_name" class="form-label">Skill Name *</label>
                    <input type="text" 
                           id="skill_name" 
                           name="skill_name" 
                           class="form-input" 
                           required 
                           maxlength="100"
                           placeholder="e.g., JavaScript, React, Node.js"
                           value="<?= h($skill['skill_name'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="skill_category" class="form-label">Category</label>
                    <select id="skill_category" name="skill_category" class="form-input">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= h($category) ?>" 
                                    <?= ($skill['skill_category'] ?? 'Other') === $category ? 'selected' : '' ?>>
                                <?= h($category) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="proficiency_level" class="form-label">Proficiency Level</label>
                    <select id="proficiency_level" name="proficiency_level" class="form-input">
                        <?php foreach ($proficiency_levels as $level): ?>
                            <option value="<?= h($level) ?>" 
                                    <?= ($skill['proficiency_level'] ?? 'Intermediate') === $level ? 'selected' : '' ?>>
                                <?= h($level) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 0.5rem;">
                        Be honest about your skill level - it helps visitors understand your expertise.
                    </small>
                </div>
                
                <div class="form-actions">
                    <a href="?action=list" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <?= $action === 'add' ? 'Add Skill' : 'Update Skill' ?>
                    </button>
                </div>
            </form>
            
        <?php elseif ($action === 'delete'): ?>
            <!-- Delete Confirmation -->
            <div class="delete-confirmation">
                <h2 style="color: var(--error);">Confirm Deletion</h2>
                <p>Are you sure you want to delete this skill? This action cannot be undone.</p>
                
                <div style="margin-top: 2rem;">
                    <a href="?action=list" class="btn btn-secondary" style="margin-right: 1rem;">Cancel</a>
                    <a href="?action=delete&id=<?= h($skill_id) ?>&confirm=yes" class="btn btn-danger">Yes, Delete</a>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($action === 'list'): ?>
            <!-- Quick Add Form -->
            <div class="card" style="margin-top: 2rem;">
                <h3>Quick Add Skill</h3>
                <form method="POST" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= generateCSRFToken() ?>">
                    <input type="hidden" name="action" value="add">
                    
                    <div>
                        <label for="quick_skill_name" class="form-label">Skill Name</label>
                        <input type="text" 
                               id="quick_skill_name" 
                               name="skill_name" 
                               class="form-input" 
                               required 
                               placeholder="Enter skill name">
                    </div>
                    
                    <div>
                        <label for="quick_category" class="form-label">Category</label>
                        <select id="quick_category" name="skill_category" class="form-input">
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= h($category) ?>"><?= h($category) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="quick_proficiency" class="form-label">Proficiency</label>
                        <select id="quick_proficiency" name="proficiency_level" class="form-input">
                            <?php foreach ($proficiency_levels as $level): ?>
                                <option value="<?= h($level) ?>" <?= $level === 'Intermediate' ? 'selected' : '' ?>>
                                    <?= h($level) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Apply saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
</body>
</html>
