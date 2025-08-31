<?php
session_start();
require_once '../config/database.php';
require_once 'auth_check.php';

// Get dashboard data
$db = new Database();
$conn = $db->getConnection();

// Get counts for dashboard stats
$basicInfo = $conn->query("SELECT COUNT(*) as count FROM portfolio_basics")->fetch();
$skillsCount = $conn->query("SELECT COUNT(*) as count FROM skills")->fetch();
$projectsCount = $conn->query("SELECT COUNT(*) as count FROM repositories")->fetch();
$workCount = $conn->query("SELECT COUNT(*) as count FROM work_experience")->fetch();
$educationCount = $conn->query("SELECT COUNT(*) as count FROM education")->fetch();
$highlightsCount = $conn->query("SELECT COUNT(*) as count FROM academic_highlights")->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Portfolio</title>
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

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: #2c3e50;
            color: white;
            padding: 20px 0;
            z-index: 1000;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #ecf0f1;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin-bottom: 5px;
        }

        .sidebar ul li a {
            display: block;
            padding: 15px 20px;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: #34495e;
            color: #ecf0f1;
            border-left: 4px solid #3498db;
        }

        .sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
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
            justify-content: between;
            align-items: center;
        }

        .header h1 {
            color: #2c3e50;
            flex: 1;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3498db, #2980b9);
        }

        .stat-card .icon {
            font-size: 40px;
            color: #3498db;
            margin-bottom: 15px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .stat-card .label {
            color: #7f8c8d;
            font-size: 14px;
        }

        .quick-actions {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .quick-actions h3 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .action-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .action-btn i {
            margin-right: 10px;
            font-size: 18px;
        }

        .view-portfolio-btn {
            background: #27ae60;
        }

        .view-portfolio-btn:hover {
            background: #229954;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Portfolio Admin</h2>
        <ul>
            <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_basics.php"><i class="fas fa-user"></i> Basic Info</a></li>
            <li><a href="manage_skills.php"><i class="fas fa-cogs"></i> Skills</a></li>
            <li><a href="manage_projects.php"><i class="fas fa-folder"></i> Projects</a></li>
            <li><a href="manage_work.php"><i class="fas fa-briefcase"></i> Work Experience</a></li>
            <li><a href="manage_education.php"><i class="fas fa-graduation-cap"></i> Education</a></li>
            <li><a href="manage_interests.php"><i class="fas fa-heart"></i> Interests</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Dashboard</h1>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="number"><?php echo $basicInfo['count']; ?></div>
                <div class="label">Profile Records</div>
            </div>

            <div class="stat-card">
                <div class="icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="number"><?php echo $skillsCount['count']; ?></div>
                <div class="label">Skill Categories</div>
            </div>

            <div class="stat-card">
                <div class="icon">
                    <i class="fas fa-folder"></i>
                </div>
                <div class="number"><?php echo $projectsCount['count']; ?></div>
                <div class="label">Projects</div>
            </div>

            <div class="stat-card">
                <div class="icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="number"><?php echo $workCount['count']; ?></div>
                <div class="label">Work Experiences</div>
            </div>

            <div class="stat-card">
                <div class="icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="number"><?php echo $educationCount['count']; ?></div>
                <div class="label">Education Records</div>
            </div>

            <div class="stat-card">
                <div class="icon">
                    <i class="fas fa-award"></i>
                </div>
                <div class="number"><?php echo $highlightsCount['count']; ?></div>
                <div class="label">Academic Highlights</div>
            </div>
        </div>

        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <div class="action-buttons">
                <a href="manage_basics.php" class="action-btn">
                    <i class="fas fa-edit"></i>
                    Edit Basic Info
                </a>
                <a href="manage_skills.php" class="action-btn">
                    <i class="fas fa-plus"></i>
                    Add New Skill
                </a>
                <a href="manage_projects.php" class="action-btn">
                    <i class="fas fa-folder-plus"></i>
                    Add New Project
                </a>
                <a href="manage_education.php" class="action-btn">
                    <i class="fas fa-graduation-cap"></i>
                    Manage Education
                </a>
                <a href="manage_work.php" class="action-btn">
                    <i class="fas fa-briefcase"></i>
                    Manage Work Experience
                </a>
                <a href="../Naquib.htm" class="action-btn view-portfolio-btn" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    View Portfolio
                </a>
                <a href="generate_resume.php" class="action-btn">
                    <i class="fas fa-download"></i>
                    Generate Resume JSON
                </a>
            </div>
        </div>
    </div>
</body>
</html>
