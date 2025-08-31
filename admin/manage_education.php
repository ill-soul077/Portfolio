<?php
session_start();
require_once '../config/database.php';
require_once 'auth_check.php';

$db = new Database();
$conn = $db->getConnection();

$success = '';
$error = '';

// Handle Education Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    if ($_POST['action'] === 'add_education') {
        $institution = trim($_POST['institution']);
        $area = trim($_POST['area']);
        $study_type = trim($_POST['study_type']);
        $start_date = trim($_POST['start_date']);
        $end_date = trim($_POST['end_date']);
        $gpa = trim($_POST['gpa']);
        $achievement = trim($_POST['achievement']);
        
        try {
            $stmt = $conn->prepare("INSERT INTO education (institution, area, study_type, start_date, end_date, gpa, achievement) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$institution, $area, $study_type, $start_date, $end_date, $gpa, $achievement]);
            $success = "Education record added successfully!";
        } catch (PDOException $e) {
            $error = "Error adding education: " . $e->getMessage();
        }
    }
    
    if ($_POST['action'] === 'update_education') {
        $id = $_POST['id'];
        $institution = trim($_POST['institution']);
        $area = trim($_POST['area']);
        $study_type = trim($_POST['study_type']);
        $start_date = trim($_POST['start_date']);
        $end_date = trim($_POST['end_date']);
        $gpa = trim($_POST['gpa']);
        $achievement = trim($_POST['achievement']);
        
        try {
            $stmt = $conn->prepare("UPDATE education SET institution = ?, area = ?, study_type = ?, start_date = ?, end_date = ?, gpa = ?, achievement = ? WHERE id = ?");
            $stmt->execute([$institution, $area, $study_type, $start_date, $end_date, $gpa, $achievement, $id]);
            $success = "Education record updated successfully!";
        } catch (PDOException $e) {
            $error = "Error updating education: " . $e->getMessage();
        }
    }
    
    if ($_POST['action'] === 'delete_education') {
        $id = $_POST['id'];
        try {
            $stmt = $conn->prepare("DELETE FROM education WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Education record deleted successfully!";
        } catch (PDOException $e) {
            $error = "Error deleting education: " . $e->getMessage();
        }
    }
    
    if ($_POST['action'] === 'add_highlight') {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        
        try {
            $stmt = $conn->prepare("INSERT INTO academic_highlights (title, description) VALUES (?, ?)");
            $stmt->execute([$title, $description]);
            $success = "Academic highlight added successfully!";
        } catch (PDOException $e) {
            $error = "Error adding highlight: " . $e->getMessage();
        }
    }
    
    if ($_POST['action'] === 'update_highlight') {
        $id = $_POST['id'];
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        
        try {
            $stmt = $conn->prepare("UPDATE academic_highlights SET title = ?, description = ? WHERE id = ?");
            $stmt->execute([$title, $description, $id]);
            $success = "Academic highlight updated successfully!";
        } catch (PDOException $e) {
            $error = "Error updating highlight: " . $e->getMessage();
        }
    }
    
    if ($_POST['action'] === 'delete_highlight') {
        $id = $_POST['id'];
        try {
            $stmt = $conn->prepare("DELETE FROM academic_highlights WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Academic highlight deleted successfully!";
        } catch (PDOException $e) {
            $error = "Error deleting highlight: " . $e->getMessage();
        }
    }
}

// Get current data
$educations = $conn->query("SELECT * FROM education ORDER BY start_date DESC")->fetchAll();
$highlights = $conn->query("SELECT * FROM academic_highlights ORDER BY id")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Education - Portfolio Admin</title>
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
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: #2c3e50;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .content-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
            padding: 12px;
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

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-success {
            background: #27ae60;
            color: white;
        }

        .btn-success:hover {
            background: #229954;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-warning {
            background: #f39c12;
            color: white;
        }

        .btn-warning:hover {
            background: #e67e22;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .action-buttons .btn {
            padding: 6px 12px;
            font-size: 12px;
        }

        .tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
        }

        .tab {
            padding: 15px 25px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #7f8c8d;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
        }

        .tab.active {
            color: #3498db;
            border-bottom-color: #3498db;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Portfolio Admin</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_basics.php"><i class="fas fa-user"></i> Basic Info</a></li>
            <li><a href="manage_skills.php"><i class="fas fa-cogs"></i> Skills</a></li>
            <li><a href="manage_projects.php"><i class="fas fa-folder"></i> Projects</a></li>
            <li><a href="manage_work.php"><i class="fas fa-briefcase"></i> Work Experience</a></li>
            <li><a href="manage_education.php" class="active"><i class="fas fa-graduation-cap"></i> Education</a></li>
            <li><a href="manage_interests.php"><i class="fas fa-heart"></i> Interests</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Manage Education & Academic Highlights</h1>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <?php if ($success): ?>
            <div class="alert success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="content-section">
            <div class="tabs">
                <button class="tab active" onclick="showTab('education')">Education</button>
                <button class="tab" onclick="showTab('highlights')">Academic Highlights</button>
            </div>

            <!-- Education Tab -->
            <div id="education" class="tab-content active">
                <h3>Add New Education</h3>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add_education">
                    
                    <div class="form-group">
                        <label for="institution">Institution</label>
                        <input type="text" id="institution" name="institution" required 
                               placeholder="e.g., Khulna University of Engineering and Technology (KUET)">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="area">Study Area</label>
                            <input type="text" id="area" name="area" 
                                   placeholder="e.g., Computer Science & Engineering">
                        </div>

                        <div class="form-group">
                            <label for="study_type">Study Type</label>
                            <input type="text" id="study_type" name="study_type" 
                                   placeholder="e.g., B.Sc., HSC, SSC">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="text" id="start_date" name="start_date" 
                                   placeholder="e.g., 2023">
                        </div>

                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="text" id="end_date" name="end_date" 
                                   placeholder="e.g., present, 2022">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="gpa">GPA/CGPA</label>
                            <input type="text" id="gpa" name="gpa" 
                                   placeholder="e.g., 3.72, 5.00">
                        </div>

                        <div class="form-group">
                            <label for="achievement">Achievement</label>
                            <input type="text" id="achievement" name="achievement" 
                                   placeholder="e.g., Dean's Award recipient">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Education
                    </button>
                </form>

                <h3 style="margin-top: 40px;">Current Education Records</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Institution</th>
                            <th>Study Area</th>
                            <th>Type</th>
                            <th>Period</th>
                            <th>GPA</th>
                            <th>Achievement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($educations as $education): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($education['institution']); ?></td>
                            <td><?php echo htmlspecialchars($education['area']); ?></td>
                            <td><?php echo htmlspecialchars($education['study_type']); ?></td>
                            <td><?php echo htmlspecialchars($education['start_date'] . ' - ' . $education['end_date']); ?></td>
                            <td><?php echo htmlspecialchars($education['gpa']); ?></td>
                            <td><?php echo htmlspecialchars($education['achievement']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-warning" onclick="editEducation(<?php echo htmlspecialchars(json_encode($education)); ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this education record?')">
                                        <input type="hidden" name="action" value="delete_education">
                                        <input type="hidden" name="id" value="<?php echo $education['id']; ?>">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Academic Highlights Tab -->
            <div id="highlights" class="tab-content">
                <h3>Add New Academic Highlight</h3>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add_highlight">
                    
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" required 
                               placeholder="e.g., CGPA: 3.72 (till present)">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" 
                                  placeholder="e.g., Maintaining excellent academic performance"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Highlight
                    </button>
                </form>

                <h3 style="margin-top: 40px;">Current Academic Highlights</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($highlights as $highlight): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($highlight['title']); ?></td>
                            <td><?php echo htmlspecialchars($highlight['description']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-warning" onclick="editHighlight(<?php echo htmlspecialchars(json_encode($highlight)); ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this highlight?')">
                                        <input type="hidden" name="action" value="delete_highlight">
                                        <input type="hidden" name="id" value="<?php echo $highlight['id']; ?>">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Education Modal (inline form) -->
    <div id="editEducationForm" style="display: none; margin-top: 30px;">
        <div class="content-section">
            <h3>Edit Education Record</h3>
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_education">
                <input type="hidden" name="id" id="edit_edu_id">
                
                <div class="form-group">
                    <label for="edit_institution">Institution</label>
                    <input type="text" id="edit_institution" name="institution" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_area">Study Area</label>
                        <input type="text" id="edit_area" name="area">
                    </div>

                    <div class="form-group">
                        <label for="edit_study_type">Study Type</label>
                        <input type="text" id="edit_study_type" name="study_type">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_start_date">Start Date</label>
                        <input type="text" id="edit_start_date" name="start_date">
                    </div>

                    <div class="form-group">
                        <label for="edit_end_date">End Date</label>
                        <input type="text" id="edit_end_date" name="end_date">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_gpa">GPA/CGPA</label>
                        <input type="text" id="edit_gpa" name="gpa">
                    </div>

                    <div class="form-group">
                        <label for="edit_achievement">Achievement</label>
                        <input type="text" id="edit_achievement" name="achievement">
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update Education
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="cancelEdit()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Highlight Modal (inline form) -->
    <div id="editHighlightForm" style="display: none; margin-top: 30px;">
        <div class="content-section">
            <h3>Edit Academic Highlight</h3>
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_highlight">
                <input type="hidden" name="id" id="edit_highlight_id">
                
                <div class="form-group">
                    <label for="edit_highlight_title">Title</label>
                    <input type="text" id="edit_highlight_title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="edit_highlight_description">Description</label>
                    <textarea id="edit_highlight_description" name="description"></textarea>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update Highlight
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="cancelEditHighlight()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }

        function editEducation(education) {
            document.getElementById('edit_edu_id').value = education.id;
            document.getElementById('edit_institution').value = education.institution;
            document.getElementById('edit_area').value = education.area || '';
            document.getElementById('edit_study_type').value = education.study_type || '';
            document.getElementById('edit_start_date').value = education.start_date || '';
            document.getElementById('edit_end_date').value = education.end_date || '';
            document.getElementById('edit_gpa').value = education.gpa || '';
            document.getElementById('edit_achievement').value = education.achievement || '';
            
            document.getElementById('editEducationForm').style.display = 'block';
            document.getElementById('editEducationForm').scrollIntoView();
        }

        function editHighlight(highlight) {
            document.getElementById('edit_highlight_id').value = highlight.id;
            document.getElementById('edit_highlight_title').value = highlight.title;
            document.getElementById('edit_highlight_description').value = highlight.description || '';
            
            document.getElementById('editHighlightForm').style.display = 'block';
            document.getElementById('editHighlightForm').scrollIntoView();
        }

        function cancelEdit() {
            document.getElementById('editEducationForm').style.display = 'none';
        }

        function cancelEditHighlight() {
            document.getElementById('editHighlightForm').style.display = 'none';
        }
    </script>
</body>
</html>
