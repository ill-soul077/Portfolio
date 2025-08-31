<?php
session_start();
require_once '../config/database.php';
require_once 'auth_check.php';

$db = new Database();
$conn = $db->getConnection();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $label = trim($_POST['label']);
    $email = trim($_POST['email']);
    $city = trim($_POST['city']);
    $country_code = trim($_POST['country_code']);
    $website = trim($_POST['website']);
    $summary = trim($_POST['summary']);
    $image = trim($_POST['image']);
    
    try {
        // Check if record exists
        $check = $conn->query("SELECT COUNT(*) as count FROM portfolio_basics")->fetch();
        
        if ($check['count'] > 0) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE portfolio_basics SET name = ?, label = ?, email = ?, city = ?, country_code = ?, website = ?, summary = ?, image = ? WHERE id = 1");
            $stmt->execute([$name, $label, $email, $city, $country_code, $website, $summary, $image]);
        } else {
            // Insert new record
            $stmt = $conn->prepare("INSERT INTO portfolio_basics (name, label, email, city, country_code, website, summary, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $label, $email, $city, $country_code, $website, $summary, $image]);
        }
        
        $success = "Basic information updated successfully!";
    } catch (PDOException $e) {
        $error = "Error updating information: " . $e->getMessage();
    }
}

// Get current data
$basicInfo = $conn->query("SELECT * FROM portfolio_basics LIMIT 1")->fetch();
$socialProfiles = $conn->query("SELECT * FROM social_profiles")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Basic Info - Portfolio Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="header">
            <h1>Manage Basic Information</h1>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="content-grid">
            <div class="form-section">
                <h3>Personal Information</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($basicInfo['name'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="label">Professional Title</label>
                        <input type="text" id="label" name="label" value="<?php echo htmlspecialchars($basicInfo['label'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($basicInfo['email'] ?? ''); ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($basicInfo['city'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="country_code">Country Code</label>
                            <input type="text" id="country_code" name="country_code" value="<?php echo htmlspecialchars($basicInfo['country_code'] ?? ''); ?>" maxlength="3" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="website">Website URL</label>
                        <input type="url" id="website" name="website" value="<?php echo htmlspecialchars($basicInfo['website'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="image">Profile Image URL</label>
                        <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($basicInfo['image'] ?? ''); ?>" placeholder="e.g., ILLSOUL.jpg">
                        <small>Use relative path or URL to your profile image</small>
                    </div>

                    <div class="form-group">
                        <label for="summary">Professional Summary</label>
                        <textarea id="summary" name="summary" rows="6" required><?php echo htmlspecialchars($basicInfo['summary'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Information
                    </button>
                </form>
            </div>

            <div class="preview-section">
                <h3>Social Profiles</h3>
                <div class="social-profiles">
                    <?php foreach ($socialProfiles as $profile): ?>
                        <div class="profile-item">
                            <i class="fab fa-<?php echo $profile['platform']; ?>"></i>
                            <span><?php echo htmlspecialchars($profile['platform']); ?></span>
                            <small><?php echo htmlspecialchars($profile['username']); ?></small>
                            <a href="edit_social.php?id=<?php echo $profile['id']; ?>" class="edit-btn">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a href="manage_social.php" class="btn btn-secondary">
                    <i class="fas fa-plus"></i> Manage Social Profiles
                </a>
            </div>
        </div>
    </div>

    <style>
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .form-section, .preview-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-section h3, .preview-section h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #2c3e50;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        .form-group small {
            display: block;
            margin-top: 5px;
            color: #7f8c8d;
            font-size: 12px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .alert i {
            margin-right: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .social-profiles {
            margin-bottom: 20px;
        }

        .profile-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .profile-item i {
            font-size: 20px;
            margin-right: 15px;
            color: #3498db;
            width: 25px;
        }

        .profile-item span {
            font-weight: 500;
            margin-right: 10px;
            text-transform: capitalize;
        }

        .profile-item small {
            color: #7f8c8d;
            flex: 1;
        }

        .edit-btn {
            color: #3498db;
            text-decoration: none;
            padding: 5px 8px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .edit-btn:hover {
            background: #ecf0f1;
        }

        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
