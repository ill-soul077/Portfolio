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
$project_id = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $error_message = 'Invalid security token. Please try again.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $tech_stack = trim($_POST['tech_stack'] ?? '');
        $github_link = trim($_POST['github_link'] ?? '');
        $demo_link = trim($_POST['demo_link'] ?? '');
        
        // Validation
        if (empty($title)) {
            $error_message = 'Project title is required';
        } elseif (strlen($title) > 255) {
            $error_message = 'Title is too long';
        } elseif (strlen($description) > 2000) {
            $error_message = 'Description is too long';
        } else {
            try {
                if ($action === 'add') {
                    // Add new project
                    $params = [$title, $description, $tech_stack, $github_link, $demo_link];
                    $image_update = '';
                    
                    // Handle image upload
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $validation = validateUpload($_FILES['image']);
                        
                        if ($validation['success']) {
                            $filename = generateUniqueFilename($_FILES['image']['name']);
                            $upload_path = UPLOAD_PATH . $filename;
                            
                            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                                $image_update = ', ?';
                                $params[] = $filename;
                            }
                        } else {
                            throw new Exception($validation['message']);
                        }
                    } else {
                        $image_update = ', NULL';
                    }
                    
                    $sql = "INSERT INTO projects (title, description, tech_stack, github_link, demo_link, image) VALUES (?, ?, ?, ?, ?$image_update)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    
                    $success_message = 'Project added successfully!';
                    $action = 'list';
                    
                } elseif ($action === 'edit' && $project_id) {
                    // Update existing project
                    $params = [$title, $description, $tech_stack, $github_link, $demo_link];
                    $image_update = '';
                    
                    // Handle image upload
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $validation = validateUpload($_FILES['image']);
                        
                        if ($validation['success']) {
                            $filename = generateUniqueFilename($_FILES['image']['name']);
                            $upload_path = UPLOAD_PATH . $filename;
                            
                            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                                $image_update = ', image = ?';
                                $params[] = $filename;
                            }
                        } else {
                            throw new Exception($validation['message']);
                        }
                    }
                    
                    $params[] = $project_id;
                    $sql = "UPDATE projects SET title = ?, description = ?, tech_stack = ?, github_link = ?, demo_link = ?$image_update WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    
                    $success_message = 'Project updated successfully!';
                    $action = 'list';
                }
                
            } catch (Exception $e) {
                $error_message = $e->getMessage();
            }
        }
    }
}

// Handle delete action
if ($action === 'delete' && $project_id && $_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        try {
            $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
            $stmt->execute([$project_id]);
            $success_message = 'Project deleted successfully!';
            $action = 'list';
        } catch (PDOException $e) {
            $error_message = 'Error deleting project';
        }
    }
}

// Get projects for listing
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
        $projects = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error_message = 'Error loading projects';
        $projects = [];
    }
}

// Get project for editing
if ($action === 'edit' && $project_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$project_id]);
        $project = $stmt->fetch();
        
        if (!$project) {
            $error_message = 'Project not found';
            $action = 'list';
        }
    } catch (PDOException $e) {
        $error_message = 'Error loading project';
        $action = 'list';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects - Admin Dashboard</title>
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
        
        .projects-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg-secondary);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .projects-table th,
        .projects-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-light);
        }
        
        .projects-table th {
            background: var(--bg-tertiary);
            font-weight: 600;
        }
        
        .project-image-thumb {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .project-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .project-form {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 2rem;
        }
        
        .delete-confirmation {
            background: var(--bg-secondary);
            border: 1px solid var(--error);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
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
            <h1>Manage Projects</h1>
            <div>
                <?php if ($action === 'list'): ?>
                    <a href="?action=add" class="btn btn-primary">Add New Project</a>
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
            <!-- Projects List -->
            <div class="card">
                <h2>All Projects</h2>
                
                <?php if (empty($projects)): ?>
                    <p style="text-align: center; color: var(--text-secondary); padding: 2rem;">
                        No projects found. <a href="?action=add">Add your first project</a>
                    </p>
                <?php else: ?>
                    <table class="projects-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Tech Stack</th>
                                <th>Links</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $project): ?>
                                <tr>
                                    <td>
                                        <?php if ($project['image']): ?>
                                            <img src="../uploads/<?= h($project['image']) ?>" 
                                                 alt="Project image" 
                                                 class="project-image-thumb">
                                        <?php else: ?>
                                            <div class="project-image-thumb" style="background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; color: var(--text-muted);">
                                                No Image
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?= h($project['title']) ?></strong></td>
                                    <td>
                                        <?= h(strlen($project['description']) > 100 ? 
                                               substr($project['description'], 0, 100) . '...' : 
                                               $project['description']) ?>
                                    </td>
                                    <td><?= h($project['tech_stack']) ?></td>
                                    <td>
                                        <?php if ($project['github_link']): ?>
                                            <a href="<?= h($project['github_link']) ?>" target="_blank" style="display: block; font-size: 0.8rem;">GitHub</a>
                                        <?php endif; ?>
                                        <?php if ($project['demo_link']): ?>
                                            <a href="<?= h($project['demo_link']) ?>" target="_blank" style="display: block; font-size: 0.8rem;">Demo</a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="project-actions">
                                            <a href="?action=edit&id=<?= $project['id'] ?>" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">Edit</a>
                                            <a href="?action=delete&id=<?= $project['id'] ?>" class="btn btn-danger" style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            
        <?php elseif ($action === 'add' || $action === 'edit'): ?>
            <!-- Add/Edit Project Form -->
            <form method="POST" enctype="multipart/form-data" class="project-form">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= generateCSRFToken() ?>">
                
                <h2><?= $action === 'add' ? 'Add New Project' : 'Edit Project' ?></h2>
                
                <div class="grid grid-2">
                    <div>
                        <div class="form-group">
                            <label for="title" class="form-label">Project Title *</label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   class="form-input" 
                                   required 
                                   maxlength="255"
                                   value="<?= h($project['title'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="tech_stack" class="form-label">Tech Stack</label>
                            <input type="text" 
                                   id="tech_stack" 
                                   name="tech_stack" 
                                   class="form-input" 
                                   placeholder="e.g., React, Node.js, MongoDB"
                                   value="<?= h($project['tech_stack'] ?? '') ?>">
                            <small style="color: var(--text-muted); font-size: 0.8rem;">Separate technologies with commas</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="github_link" class="form-label">GitHub Link</label>
                            <input type="url" 
                                   id="github_link" 
                                   name="github_link" 
                                   class="form-input" 
                                   placeholder="https://github.com/username/repo"
                                   value="<?= h($project['github_link'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="demo_link" class="form-label">Demo Link</label>
                            <input type="url" 
                                   id="demo_link" 
                                   name="demo_link" 
                                   class="form-input" 
                                   placeholder="https://your-demo-url.com"
                                   value="<?= h($project['demo_link'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div>
                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" 
                                      name="description" 
                                      class="form-textarea" 
                                      rows="6" 
                                      maxlength="2000"
                                      placeholder="Describe your project, its features, and what makes it special"><?= h($project['description'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="image" class="form-label">Project Image</label>
                            <?php if ($action === 'edit' && $project['image']): ?>
                                <div style="margin-bottom: 1rem;">
                                    <img src="../uploads/<?= h($project['image']) ?>" 
                                         alt="Current project image" 
                                         style="max-width: 200px; height: auto; border-radius: 8px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   class="form-input" 
                                   accept="image/jpeg,image/png,image/webp">
                            <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 0.5rem;">
                                Supported formats: JPG, PNG, WebP. Max size: 5MB
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="?action=list" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <?= $action === 'add' ? 'Add Project' : 'Update Project' ?>
                    </button>
                </div>
            </form>
            
        <?php elseif ($action === 'delete'): ?>
            <!-- Delete Confirmation -->
            <div class="delete-confirmation">
                <h2 style="color: var(--error);">Confirm Deletion</h2>
                <p>Are you sure you want to delete this project? This action cannot be undone.</p>
                
                <div style="margin-top: 2rem;">
                    <a href="?action=list" class="btn btn-secondary" style="margin-right: 1rem;">Cancel</a>
                    <a href="?action=delete&id=<?= h($project_id) ?>&confirm=yes" class="btn btn-danger">Yes, Delete</a>
                </div>
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
