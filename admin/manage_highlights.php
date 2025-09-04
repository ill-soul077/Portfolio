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
                $stmt = $conn->prepare("INSERT INTO academic_highlights (title, description, date, category, display_order) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['date'],
                    $_POST['category'],
                    $_POST['display_order']
                ]);
                $message = "Academic highlight added successfully!";
                break;
                
            case 'edit':
                $stmt = $conn->prepare("UPDATE academic_highlights SET title = ?, description = ?, date = ?, category = ?, display_order = ? WHERE id = ?");
                $stmt->execute([
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['date'],
                    $_POST['category'],
                    $_POST['display_order'],
                    $_POST['id']
                ]);
                $message = "Academic highlight updated successfully!";
                break;
                
            case 'delete':
                $stmt = $conn->prepare("DELETE FROM academic_highlights WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $message = "Academic highlight deleted successfully!";
                break;
        }
    }
}

// Get highlight for editing
$editHighlight = null;
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM academic_highlights WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editHighlight = $stmt->fetch();
}

// Get all highlights
$highlights = $conn->query("SELECT * FROM academic_highlights ORDER BY display_order ASC, created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Academic Highlights - Portfolio Admin</title>
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

        .highlights-list {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .highlight-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .highlight-item:last-child {
            border-bottom: none;
        }

        .highlight-info {
            flex: 1;
        }

        .highlight-info h3 {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .highlight-meta {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .highlight-description {
            color: #2c3e50;
            line-height: 1.5;
        }

        .highlight-actions {
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
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h1><i class="fas fa-award"></i> Manage Academic Highlights</h1>
            <a href="dashboard.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <?php if (isset($message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Add/Edit Form -->
        <div class="form-container">
            <h2><?php echo $editHighlight ? 'Edit Academic Highlight' : 'Add New Academic Highlight'; ?></h2>
            <form method="POST">
                <input type="hidden" name="action" value="<?php echo $editHighlight ? 'edit' : 'add'; ?>">
                <?php if ($editHighlight): ?>
                    <input type="hidden" name="id" value="<?php echo $editHighlight['id']; ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Title*</label>
                        <input type="text" id="title" name="title" required 
                               value="<?php echo $editHighlight ? htmlspecialchars($editHighlight['title']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="text" id="date" name="date" 
                               value="<?php echo $editHighlight ? htmlspecialchars($editHighlight['date']) : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo $editHighlight ? htmlspecialchars($editHighlight['description']) : ''; ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="Academic" <?php echo ($editHighlight && $editHighlight['category'] == 'Academic') ? 'selected' : ''; ?>>Academic</option>
                            <option value="Research" <?php echo ($editHighlight && $editHighlight['category'] == 'Research') ? 'selected' : ''; ?>>Research</option>
                            <option value="Publication" <?php echo ($editHighlight && $editHighlight['category'] == 'Publication') ? 'selected' : ''; ?>>Publication</option>
                            <option value="Award" <?php echo ($editHighlight && $editHighlight['category'] == 'Award') ? 'selected' : ''; ?>>Award</option>
                            <option value="Other" <?php echo ($editHighlight && $editHighlight['category'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="display_order">Display Order</label>
                        <input type="number" id="display_order" name="display_order" min="0"
                               value="<?php echo $editHighlight ? $editHighlight['display_order'] : '0'; ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> <?php echo $editHighlight ? 'Update Highlight' : 'Add Highlight'; ?>
                </button>
                
                <?php if ($editHighlight): ?>
                    <a href="manage_highlights.php" class="btn btn-warning">
                        <i class="fas fa-times"></i> Cancel Edit
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Highlights List -->
        <div class="highlights-list">
            <div style="padding: 20px; border-bottom: 1px solid #eee; background: #f8f9fa;">
                <h2><i class="fas fa-list"></i> Current Academic Highlights (<?php echo count($highlights); ?>)</h2>
            </div>

            <?php if (empty($highlights)): ?>
                <div style="padding: 40px; text-align: center; color: #7f8c8d;">
                    <i class="fas fa-award" style="font-size: 48px; margin-bottom: 20px;"></i>
                    <p>No academic highlights found. Add your first highlight above!</p>
                </div>
            <?php else: ?>
                <?php foreach ($highlights as $highlight): ?>
                    <div class="highlight-item">
                        <div class="highlight-info">
                            <h3><?php echo htmlspecialchars($highlight['title']); ?></h3>
                            <div class="highlight-meta">
                                <?php if ($highlight['date']): ?>
                                    <strong>Date:</strong> <?php echo htmlspecialchars($highlight['date']); ?> | 
                                <?php endif; ?>
                                <strong>Category:</strong> <?php echo htmlspecialchars($highlight['category']); ?> |
                                <strong>Order:</strong> <?php echo $highlight['display_order']; ?>
                            </div>
                            <?php if ($highlight['description']): ?>
                                <div class="highlight-description">
                                    <?php echo htmlspecialchars($highlight['description']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="highlight-actions">
                            <a href="?edit=<?php echo $highlight['id']; ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this highlight?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $highlight['id']; ?>">
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
