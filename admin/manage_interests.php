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
                $stmt = $conn->prepare("INSERT INTO interests (interest, description, display_order) VALUES (?, ?, ?)");
                $stmt->execute([
                    $_POST['interest'],
                    $_POST['description'],
                    $_POST['display_order']
                ]);
                $message = "Interest added successfully!";
                break;
                
            case 'edit':
                $stmt = $conn->prepare("UPDATE interests SET interest = ?, description = ?, display_order = ? WHERE id = ?");
                $stmt->execute([
                    $_POST['interest'],
                    $_POST['description'],
                    $_POST['display_order'],
                    $_POST['id']
                ]);
                $message = "Interest updated successfully!";
                break;
                
            case 'delete':
                $stmt = $conn->prepare("DELETE FROM interests WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $message = "Interest deleted successfully!";
                break;
        }
    }
}

// Get interest for editing
$editInterest = null;
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM interests WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editInterest = $stmt->fetch();
}

// Get all interests
$interests = $conn->query("SELECT * FROM interests ORDER BY display_order ASC, created_at ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Interests - Portfolio Admin</title>
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
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group textarea {
            height: 80px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .interests-list {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .interest-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .interest-item:last-child {
            border-bottom: none;
        }

        .interest-info {
            flex: 1;
        }

        .interest-info h3 {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .interest-meta {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .interest-description {
            color: #2c3e50;
            line-height: 1.5;
        }

        .interest-actions {
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

        .interests-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            padding: 20px;
        }

        .interest-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #ECB365;
        }

        .interest-card h4 {
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .interest-card p {
            color: #7f8c8d;
            font-size: 13px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h1><i class="fas fa-heart"></i> Manage Interests</h1>
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
            <h2><?php echo $editInterest ? 'Edit Interest' : 'Add New Interest'; ?></h2>
            <form method="POST">
                <input type="hidden" name="action" value="<?php echo $editInterest ? 'edit' : 'add'; ?>">
                <?php if ($editInterest): ?>
                    <input type="hidden" name="id" value="<?php echo $editInterest['id']; ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="interest">Interest Name*</label>
                        <input type="text" id="interest" name="interest" required 
                               value="<?php echo $editInterest ? htmlspecialchars($editInterest['interest']) : ''; ?>"
                               placeholder="e.g., Photography, Chess, Music Production">
                    </div>
                    <div class="form-group">
                        <label for="display_order">Display Order</label>
                        <input type="number" id="display_order" name="display_order" min="0"
                               value="<?php echo $editInterest ? $editInterest['display_order'] : '0'; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description (Optional)</label>
                    <textarea id="description" name="description" 
                              placeholder="Brief description of your interest or involvement..."><?php echo $editInterest ? htmlspecialchars($editInterest['description']) : ''; ?></textarea>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> <?php echo $editInterest ? 'Update Interest' : 'Add Interest'; ?>
                </button>
                
                <?php if ($editInterest): ?>
                    <a href="manage_interests.php" class="btn btn-warning">
                        <i class="fas fa-times"></i> Cancel Edit
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Interests Preview -->
        <div class="form-container">
            <h2><i class="fas fa-eye"></i> Preview - How it looks on your portfolio</h2>
            <div class="interests-grid">
                <?php foreach ($interests as $interest): ?>
                    <div class="interest-card">
                        <h4><?php echo htmlspecialchars($interest['interest']); ?></h4>
                        <?php if ($interest['description']): ?>
                            <p><?php echo htmlspecialchars($interest['description']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Interests List -->
        <div class="interests-list">
            <div style="padding: 20px; border-bottom: 1px solid #eee; background: #f8f9fa;">
                <h2><i class="fas fa-list"></i> Current Interests (<?php echo count($interests); ?>)</h2>
            </div>

            <?php if (empty($interests)): ?>
                <div style="padding: 40px; text-align: center; color: #7f8c8d;">
                    <i class="fas fa-heart" style="font-size: 48px; margin-bottom: 20px;"></i>
                    <p>No interests found. Add your first interest above!</p>
                </div>
            <?php else: ?>
                <?php foreach ($interests as $interest): ?>
                    <div class="interest-item">
                        <div class="interest-info">
                            <h3><?php echo htmlspecialchars($interest['interest']); ?></h3>
                            <div class="interest-meta">
                                <strong>Order:</strong> <?php echo $interest['display_order']; ?>
                                <?php if ($interest['created_at']): ?>
                                    | <strong>Added:</strong> <?php echo date('M j, Y', strtotime($interest['created_at'])); ?>
                                <?php endif; ?>
                            </div>
                            <?php if ($interest['description']): ?>
                                <div class="interest-description">
                                    <?php echo htmlspecialchars($interest['description']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="interest-actions">
                            <a href="?edit=<?php echo $interest['id']; ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this interest?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $interest['id']; ?>">
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
