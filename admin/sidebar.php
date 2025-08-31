<div class="sidebar">
    <h2>Portfolio Admin</h2>
    <ul>
        <li><a href="dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : ''; ?>>
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a></li>
        <li><a href="manage_basics.php" <?php echo basename($_SERVER['PHP_SELF']) == 'manage_basics.php' ? 'class="active"' : ''; ?>>
            <i class="fas fa-user"></i> Basic Info
        </a></li>
        <li><a href="manage_skills.php" <?php echo basename($_SERVER['PHP_SELF']) == 'manage_skills.php' ? 'class="active"' : ''; ?>>
            <i class="fas fa-cogs"></i> Skills
        </a></li>
        <li><a href="manage_projects.php" <?php echo basename($_SERVER['PHP_SELF']) == 'manage_projects.php' ? 'class="active"' : ''; ?>>
            <i class="fas fa-folder"></i> Projects
        </a></li>
        <li><a href="manage_work.php" <?php echo basename($_SERVER['PHP_SELF']) == 'manage_work.php' ? 'class="active"' : ''; ?>>
            <i class="fas fa-briefcase"></i> Work Experience
        </a></li>
        <li><a href="manage_education.php" <?php echo basename($_SERVER['PHP_SELF']) == 'manage_education.php' ? 'class="active"' : ''; ?>>
            <i class="fas fa-graduation-cap"></i> Education
        </a></li>
        <li><a href="manage_messages.php" <?php echo basename($_SERVER['PHP_SELF']) == 'manage_messages.php' ? 'class="active"' : ''; ?>>
            <i class="fas fa-envelope"></i> Messages
        </a></li>
        <li><a href="manage_interests.php" <?php echo basename($_SERVER['PHP_SELF']) == 'manage_interests.php' ? 'class="active"' : ''; ?>>
            <i class="fas fa-heart"></i> Interests
        </a></li>
    </ul>
</div>

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
        justify-content: space-between;
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
    }
</style>
