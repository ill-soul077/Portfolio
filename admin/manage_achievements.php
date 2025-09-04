<?php
session_start();
require_once '../config/database.php';
require_once 'auth_check.php';

$db = new Database();
$conn = $db->getConnection();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $stmt = $conn->prepare("INSERT INTO achievements (title, year, description, category, icon, color, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['title'],
                    $_POST['year'],
                    $_POST['description'],
                    $_POST['category'],
                    $_POST['icon'],
                    $_POST['color'],
                    $_POST['display_order'],
                    1
                ]);
                $message = "Achievement added successfully!";
                break;
                
            case 'edit':
                $stmt = $conn->prepare("UPDATE achievements SET title = ?, year = ?, description = ?, category = ?, icon = ?, color = ?, display_order = ? WHERE id = ?");
                $stmt->execute([
                    $_POST['title'],
                    $_POST['year'],
                    $_POST['description'],
                    $_POST['category'],
                    $_POST['icon'],
                    $_POST['color'],
                    $_POST['display_order'],
                    $_POST['id']
                ]);
                $message = "Achievement updated successfully!";
                break;
                
            case 'delete':
                $stmt = $conn->prepare("UPDATE achievements SET is_active = 0 WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $message = "Achievement deleted successfully!";
                break;
                
            case 'toggle_status':
                $stmt = $conn->prepare("UPDATE achievements SET is_active = ? WHERE id = ?");
                $stmt->execute([$_POST['status'], $_POST['id']]);
                $message = "Achievement status updated!";
                break;
        }
    }
}

// Get achievements for editing
$editAchievement = null;
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM achievements WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editAchievement = $stmt->fetch();
}

// Get all achievements
$achievements = $conn->query("SELECT * FROM achievements WHERE is_active = 1 ORDER BY display_order ASC, created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Achievements - Portfolio Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f5f6fa;
            min-height: 100vh;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: #2c3e50;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 5px;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-success {
            background: #27ae60;
            color: white;
        }

        .btn-warning {
            background: #f39c12;
            color: white;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #2c3e50;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .achievements-list {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .achievement-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .achievement-item:last-child {
            border-bottom: none;
        }

        .achievement-info {
            flex: 1;
        }

        .achievement-info h3 {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .achievement-meta {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .achievement-description {
            color: #2c3e50;
            line-height: 1.5;
        }

        .achievement-actions {
            display: flex;
            gap: 10px;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h1><i class="fas fa-trophy"></i> Manage Achievements</h1>
            <a href="dashboard.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <?php if (isset($message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Add/Edit Achievement Form -->
        <div class="form-container">
            <h2><?php echo $editAchievement ? 'Edit Achievement' : 'Add New Achievement'; ?></h2>
            <form method="POST">
                <input type="hidden" name="action" value="<?php echo $editAchievement ? 'edit' : 'add'; ?>">
                <?php if ($editAchievement): ?>
                    <input type="hidden" name="id" value="<?php echo $editAchievement['id']; ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Title*</label>
                        <input type="text" id="title" name="title" required 
                               value="<?php echo $editAchievement ? htmlspecialchars($editAchievement['title']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="year">Year*</label>
                        <input type="text" id="year" name="year" required 
                               value="<?php echo $editAchievement ? htmlspecialchars($editAchievement['year']) : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo $editAchievement ? htmlspecialchars($editAchievement['description']) : ''; ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="Academic" <?php echo ($editAchievement && $editAchievement['category'] == 'Academic') ? 'selected' : ''; ?>>Academic</option>
                            <option value="Programming" <?php echo ($editAchievement && $editAchievement['category'] == 'Programming') ? 'selected' : ''; ?>>Programming</option>
                            <option value="Competition" <?php echo ($editAchievement && $editAchievement['category'] == 'Competition') ? 'selected' : ''; ?>>Competition</option>
                            <option value="Leadership" <?php echo ($editAchievement && $editAchievement['category'] == 'Leadership') ? 'selected' : ''; ?>>Leadership</option>
                            <option value="Other" <?php echo ($editAchievement && $editAchievement['category'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="icon">Icon Class</label>
                        <input type="text" id="icon" name="icon" placeholder="e.g., fas fa-trophy"
                               value="<?php echo $editAchievement ? htmlspecialchars($editAchievement['icon']) : 'fas fa-trophy'; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" id="color" name="color" placeholder="e.g., #FFD700"
                               value="<?php echo $editAchievement ? htmlspecialchars($editAchievement['color']) : '#ECB365'; ?>">
                    </div>
                    <div class="form-group">
                        <label for="display_order">Display Order</label>
                        <input type="number" id="display_order" name="display_order" min="0"
                               value="<?php echo $editAchievement ? $editAchievement['display_order'] : '0'; ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> <?php echo $editAchievement ? 'Update Achievement' : 'Add Achievement'; ?>
                </button>
                
                <?php if ($editAchievement): ?>
                    <a href="manage_achievements.php" class="btn btn-warning">
                        <i class="fas fa-times"></i> Cancel Edit
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Achievements List -->
        <div class="achievements-list">
            <div style="padding: 20px; border-bottom: 1px solid #eee; background: #f8f9fa;">
                <h2><i class="fas fa-list"></i> Current Achievements (<?php echo count($achievements); ?>)</h2>
            </div>

            <?php if (empty($achievements)): ?>
                <div style="padding: 40px; text-align: center; color: #7f8c8d;">
                    <i class="fas fa-trophy" style="font-size: 48px; margin-bottom: 20px;"></i>
                    <p>No achievements found. Add your first achievement above!</p>
                </div>
            <?php else: ?>
                <?php foreach ($achievements as $achievement): ?>
                    <div class="achievement-item">
                        <div class="achievement-info">
                            <h3>
                                <i class="<?php echo htmlspecialchars($achievement['icon']); ?>" 
                                   style="color: <?php echo htmlspecialchars($achievement['color']); ?>; margin-right: 10px;"></i>
                                <?php echo htmlspecialchars($achievement['title']); ?>
                            </h3>
                            <div class="achievement-meta">
                                <strong>Year:</strong> <?php echo htmlspecialchars($achievement['year']); ?> | 
                                <strong>Category:</strong> <?php echo htmlspecialchars($achievement['category']); ?> |
                                <strong>Order:</strong> <?php echo $achievement['display_order']; ?>
                                <span class="status-badge status-<?php echo $achievement['is_active'] ? 'active' : 'inactive'; ?>">
                                    <?php echo $achievement['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </div>
                            <?php if ($achievement['description']): ?>
                                <div class="achievement-description">
                                    <?php echo htmlspecialchars($achievement['description']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="achievement-actions">
                            <a href="?edit=<?php echo $achievement['id']; ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this achievement?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $achievement['id']; ?>">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
