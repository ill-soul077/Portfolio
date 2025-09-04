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
                $stmt = $conn->prepare("INSERT INTO social_profiles (platform, username, url, display_order, is_active) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['platform'],
                    $_POST['username'],
                    $_POST['url'],
                    $_POST['display_order'],
                    1
                ]);
                $message = "Social profile added successfully!";
                break;
                
            case 'edit':
                $stmt = $conn->prepare("UPDATE social_profiles SET platform = ?, username = ?, url = ?, display_order = ?, is_active = ? WHERE id = ?");
                $stmt->execute([
                    $_POST['platform'],
                    $_POST['username'],
                    $_POST['url'],
                    $_POST['display_order'],
                    isset($_POST['is_active']) ? 1 : 0,
                    $_POST['id']
                ]);
                $message = "Social profile updated successfully!";
                break;
                
            case 'delete':
                $stmt = $conn->prepare("DELETE FROM social_profiles WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $message = "Social profile deleted successfully!";
                break;
                
            case 'toggle_status':
                $stmt = $conn->prepare("UPDATE social_profiles SET is_active = ? WHERE id = ?");
                $stmt->execute([$_POST['status'], $_POST['id']]);
                $message = "Social profile status updated!";
                break;
        }
    }
}

// Get profile for editing
$editProfile = null;
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM social_profiles WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editProfile = $stmt->fetch();
}

// Get all social profiles
$profiles = $conn->query("SELECT * FROM social_profiles ORDER BY display_order ASC, created_at ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Social Profiles - Portfolio Admin</title>
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
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .profiles-list {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .profile-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .profile-item:last-child {
            border-bottom: none;
        }

        .profile-info {
            flex: 1;
            display: flex;
            align-items: center;
        }

        .profile-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 20px;
            color: white;
        }

        .profile-details h3 {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .profile-meta {
            color: #7f8c8d;
            font-size: 14px;
        }

        .profile-actions {
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

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h1><i class="fas fa-share-alt"></i> Manage Social Profiles</h1>
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
            <h2><?php echo $editProfile ? 'Edit Social Profile' : 'Add New Social Profile'; ?></h2>
            <form method="POST">
                <input type="hidden" name="action" value="<?php echo $editProfile ? 'edit' : 'add'; ?>">
                <?php if ($editProfile): ?>
                    <input type="hidden" name="id" value="<?php echo $editProfile['id']; ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="platform">Platform*</label>
                        <select id="platform" name="platform" required>
                            <option value="">Select Platform</option>
                            <option value="GitHub" <?php echo ($editProfile && $editProfile['platform'] == 'GitHub') ? 'selected' : ''; ?>>GitHub</option>
                            <option value="LinkedIn" <?php echo ($editProfile && $editProfile['platform'] == 'LinkedIn') ? 'selected' : ''; ?>>LinkedIn</option>
                            <option value="Twitter" <?php echo ($editProfile && $editProfile['platform'] == 'Twitter') ? 'selected' : ''; ?>>Twitter/X</option>
                            <option value="Codeforces" <?php echo ($editProfile && $editProfile['platform'] == 'Codeforces') ? 'selected' : ''; ?>>Codeforces</option>
                            <option value="LeetCode" <?php echo ($editProfile && $editProfile['platform'] == 'LeetCode') ? 'selected' : ''; ?>>LeetCode</option>
                            <option value="CodeChef" <?php echo ($editProfile && $editProfile['platform'] == 'CodeChef') ? 'selected' : ''; ?>>CodeChef</option>
                            <option value="Facebook" <?php echo ($editProfile && $editProfile['platform'] == 'Facebook') ? 'selected' : ''; ?>>Facebook</option>
                            <option value="Instagram" <?php echo ($editProfile && $editProfile['platform'] == 'Instagram') ? 'selected' : ''; ?>>Instagram</option>
                            <option value="YouTube" <?php echo ($editProfile && $editProfile['platform'] == 'YouTube') ? 'selected' : ''; ?>>YouTube</option>
                            <option value="Portfolio" <?php echo ($editProfile && $editProfile['platform'] == 'Portfolio') ? 'selected' : ''; ?>>Portfolio Website</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="username">Username*</label>
                        <input type="text" id="username" name="username" required 
                               value="<?php echo $editProfile ? htmlspecialchars($editProfile['username']) : ''; ?>"
                               placeholder="e.g., your_username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="url">Full URL*</label>
                    <input type="url" id="url" name="url" required 
                           value="<?php echo $editProfile ? htmlspecialchars($editProfile['url']) : ''; ?>"
                           placeholder="https://platform.com/your_profile">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="display_order">Display Order</label>
                        <input type="number" id="display_order" name="display_order" min="0"
                               value="<?php echo $editProfile ? $editProfile['display_order'] : '0'; ?>">
                    </div>
                    <?php if ($editProfile): ?>
                        <div class="form-group">
                            <label>Status</label>
                            <div class="checkbox-group">
                                <input type="checkbox" id="is_active" name="is_active" 
                                       <?php echo ($editProfile && $editProfile['is_active']) ? 'checked' : ''; ?>>
                                <label for="is_active">Active (visible on portfolio)</label>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> <?php echo $editProfile ? 'Update Profile' : 'Add Profile'; ?>
                </button>
                
                <?php if ($editProfile): ?>
                    <a href="manage_social.php" class="btn btn-warning">
                        <i class="fas fa-times"></i> Cancel Edit
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Profiles List -->
        <div class="profiles-list">
            <div style="padding: 20px; border-bottom: 1px solid #eee; background: #f8f9fa;">
                <h2><i class="fas fa-list"></i> Current Social Profiles (<?php echo count($profiles); ?>)</h2>
            </div>

            <?php if (empty($profiles)): ?>
                <div style="padding: 40px; text-align: center; color: #7f8c8d;">
                    <i class="fas fa-share-alt" style="font-size: 48px; margin-bottom: 20px;"></i>
                    <p>No social profiles found. Add your first profile above!</p>
                </div>
            <?php else: ?>
                <?php 
                $platformColors = [
                    'GitHub' => '#333',
                    'LinkedIn' => '#0077B5',
                    'Twitter' => '#1DA1F2',
                    'Codeforces' => '#1F8ACB',
                    'LeetCode' => '#FFA116',
                    'CodeChef' => '#5B4638',
                    'Facebook' => '#1877F2',
                    'Instagram' => '#E4405F',
                    'YouTube' => '#FF0000',
                    'Portfolio' => '#ECB365'
                ];
                
                $platformIcons = [
                    'GitHub' => 'fab fa-github',
                    'LinkedIn' => 'fab fa-linkedin',
                    'Twitter' => 'fab fa-twitter',
                    'Codeforces' => 'fas fa-code',
                    'LeetCode' => 'fas fa-laptop-code',
                    'CodeChef' => 'fas fa-utensils',
                    'Facebook' => 'fab fa-facebook',
                    'Instagram' => 'fab fa-instagram',
                    'YouTube' => 'fab fa-youtube',
                    'Portfolio' => 'fas fa-globe'
                ];
                ?>
                
                <?php foreach ($profiles as $profile): ?>
                    <div class="profile-item">
                        <div class="profile-info">
                            <div class="profile-icon" style="background: <?php echo $platformColors[$profile['platform']] ?? '#ECB365'; ?>">
                                <i class="<?php echo $platformIcons[$profile['platform']] ?? 'fas fa-link'; ?>"></i>
                            </div>
                            <div class="profile-details">
                                <h3><?php echo htmlspecialchars($profile['platform']); ?></h3>
                                <div class="profile-meta">
                                    <strong>@<?php echo htmlspecialchars($profile['username']); ?></strong> |
                                    <strong>Order:</strong> <?php echo $profile['display_order']; ?>
                                    <span class="status-badge status-<?php echo $profile['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $profile['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                    <br>
                                    <a href="<?php echo htmlspecialchars($profile['url']); ?>" target="_blank" style="color: #3498db; text-decoration: none;">
                                        <i class="fas fa-external-link-alt"></i> <?php echo htmlspecialchars($profile['url']); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="profile-actions">
                            <a href="?edit=<?php echo $profile['id']; ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this profile?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $profile['id']; ?>">
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
