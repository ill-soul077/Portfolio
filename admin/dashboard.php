<?php
require_once '../config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Update last activity
updateLastActivity();

// Get current admin data
try {
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch();
} catch (PDOException $e) {
    $error_message = 'Error loading profile data';
}

// Get statistics
$stats = [
    'projects' => 0,
    'messages' => 0,
    'unread_messages' => 0,
    'skills' => 0,
    'achievements' => 0
];

try {
    // Count projects
    $stmt = $pdo->query("SELECT COUNT(*) FROM projects");
    $stats['projects'] = $stmt->fetchColumn();
    
    // Count messages
    $stmt = $pdo->query("SELECT COUNT(*) FROM messages");
    $stats['messages'] = $stmt->fetchColumn();
    
    // Count unread messages
    $stmt = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read = 0");
    $stats['unread_messages'] = $stmt->fetchColumn();
    
    // Count skills
    $stmt = $pdo->query("SELECT COUNT(*) FROM skills");
    $stats['skills'] = $stmt->fetchColumn();
    
    // Count achievements
    $stmt = $pdo->query("SELECT COUNT(*) FROM achievements");
    $stats['achievements'] = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Silently fail for stats
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Naquib Portfolio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-color);
            padding: 2rem 1rem;
        }
        
        .admin-content {
            padding: 2rem;
            background: var(--bg-primary);
        }
        
        .admin-header {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            grid-column: 1 / -1;
        }
        
        .sidebar-nav {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-nav li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-nav a {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: var(--accent-primary);
            color: white;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--accent-primary);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .action-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.5rem;
        }
        
        .action-card h3 {
            margin-bottom: 1rem;
            color: var(--accent-primary);
        }
        
        @media (max-width: 768px) {
            .admin-layout {
                grid-template-columns: 1fr;
            }
            
            .admin-sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <header class="admin-header">
            <h1>Admin Dashboard</h1>
            <div>
                <span>Welcome, <?= h($admin['username'] ?? 'Admin') ?>!</span>
                <a href="logout.php" class="btn btn-secondary" style="margin-left: 1rem;">Logout</a>
            </div>
        </header>
        
        <aside class="admin-sidebar">
            <ul class="sidebar-nav">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="profile.php">Profile Settings</a></li>
                <li><a href="projects.php">Manage Projects</a></li>
                <li><a href="skills.php">Manage Skills</a></li>
                <li><a href="achievements.php">Manage Achievements</a></li>
                <li><a href="messages.php">Contact Messages</a></li>
                <li><a href="change_password.php">Change Password</a></li>
                <li><a href="../index.php" target="_blank">View Portfolio</a></li>
            </ul>
        </aside>
        
        <main class="admin-content">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $stats['projects'] ?></div>
                    <div class="stat-label">Projects</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?= $stats['messages'] ?></div>
                    <div class="stat-label">Total Messages</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?= $stats['unread_messages'] ?></div>
                    <div class="stat-label">Unread Messages</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?= $stats['skills'] ?></div>
                    <div class="stat-label">Skills</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?= $stats['achievements'] ?></div>
                    <div class="stat-label">Achievements</div>
                </div>
            </div>
            
            <h2>Quick Actions</h2>
            
            <div class="quick-actions">
                <div class="action-card">
                    <h3>Profile Management</h3>
                    <p>Update your tagline, about me section, and profile picture.</p>
                    <a href="profile.php" class="btn btn-primary">Manage Profile</a>
                </div>
                
                <div class="action-card">
                    <h3>Add New Project</h3>
                    <p>Showcase your latest work by adding a new project to your portfolio.</p>
                    <a href="projects.php?action=add" class="btn btn-primary">Add Project</a>
                </div>
                
                <div class="action-card">
                    <h3>Contact Messages</h3>
                    <p>View and respond to messages from visitors to your portfolio.</p>
                    <a href="messages.php" class="btn btn-primary">View Messages</a>
                    <?php if ($stats['unread_messages'] > 0): ?>
                        <span class="badge" style="background: var(--error); color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem; margin-left: 0.5rem;">
                            <?= $stats['unread_messages'] ?> new
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="action-card">
                    <h3>Skills & Achievements</h3>
                    <p>Keep your skills and achievements up to date.</p>
                    <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                        <a href="skills.php" class="btn btn-secondary">Skills</a>
                        <a href="achievements.php" class="btn btn-secondary">Achievements</a>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 3rem;">
                <h2>System Information</h2>
                <div class="card">
                    <div class="grid grid-2">
                        <div>
                            <h4>Last Login</h4>
                            <p><?= date('F j, Y g:i A', $_SESSION['last_activity']) ?></p>
                        </div>
                        <div>
                            <h4>Portfolio URL</h4>
                            <p><a href="../index.php" target="_blank">View Portfolio</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Apply saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
</body>
</html>
