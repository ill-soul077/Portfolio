<?php
session_start();
require_once '../config/database.php';
require_once 'auth_check.php';

$db = new Database();
$conn = $db->getConnection();

$success = '';
$error = '';

// Handle Work Experience Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    if ($_POST['action'] === 'add_work') {
        $position = trim($_POST['position']);
        $company_name = trim($_POST['company_name']);
        $start_date = trim($_POST['start_date']);
        $end_date = trim($_POST['end_date']);
        $location = trim($_POST['location']);
        $summary = trim($_POST['summary']);
        
        try {
            $stmt = $conn->prepare("INSERT INTO work_experience (position, company_name, start_date, end_date, location, summary) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$position, $company_name, $start_date, $end_date, $location, $summary]);
            $success = "Work experience added successfully!";
        } catch (PDOException $e) {
            $error = "Error adding work experience: " . $e->getMessage();
        }
    }
    
    if ($_POST['action'] === 'update_work') {
        $id = $_POST['id'];
        $position = trim($_POST['position']);
        $company_name = trim($_POST['company_name']);
        $start_date = trim($_POST['start_date']);
        $end_date = trim($_POST['end_date']);
        $location = trim($_POST['location']);
        $summary = trim($_POST['summary']);
        
        try {
            $stmt = $conn->prepare("UPDATE work_experience SET position = ?, company_name = ?, start_date = ?, end_date = ?, location = ?, summary = ? WHERE id = ?");
            $stmt->execute([$position, $company_name, $start_date, $end_date, $location, $summary, $id]);
            $success = "Work experience updated successfully!";
        } catch (PDOException $e) {
            $error = "Error updating work experience: " . $e->getMessage();
        }
    }
    
    if ($_POST['action'] === 'delete_work') {
        $id = $_POST['id'];
        try {
            $stmt = $conn->prepare("DELETE FROM work_experience WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Work experience deleted successfully!";
        } catch (PDOException $e) {
            $error = "Error deleting work experience: " . $e->getMessage();
        }
    }
}

// Get all work experiences
$stmt = $conn->prepare("SELECT * FROM work_experience ORDER BY start_date DESC");
$stmt->execute();
$work_experiences = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Work Experience - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .nav-links {
            margin-top: 15px;
        }

        .nav-links a {
            color: #3498db;
            text-decoration: none;
            margin-right: 20px;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .nav-links a:hover {
            background: #ecf0f1;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
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
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn {
            background: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #2980b9;
        }

        .btn-danger {
            background: #e74c3c;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-warning {
            background: #f39c12;
        }

        .btn-warning:hover {
            background: #e67e22;
        }

        .work-list {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .work-item {
            border: 1px solid #ecf0f1;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: box-shadow 0.3s;
        }

        .work-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .work-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .work-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .work-company {
            color: #3498db;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .work-duration {
            color: #7f8c8d;
            font-size: 14px;
        }

        .work-location {
            background: #ecf0f1;
            color: #2c3e50;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
        }

        .work-summary {
            color: #555;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .work-actions {
            display: flex;
            gap: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 3% auto;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 700px;
            max-height: 85vh;
            overflow-y: auto;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .current-badge {
            background: #27ae60;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-briefcase"></i> Manage Work Experience</h1>
            <p>Add, edit, or remove your professional work experience</p>
            <div class="nav-links">
                <a href="dashboard.php"><i class="fas fa-dashboard"></i> Dashboard</a>
                <a href="manage_skills.php"><i class="fas fa-cogs"></i> Skills</a>
                <a href="manage_projects.php"><i class="fas fa-project-diagram"></i> Projects</a>
                <a href="manage_education.php"><i class="fas fa-graduation-cap"></i> Education</a>
                <a href="../Naquib.htm" target="_blank"><i class="fas fa-eye"></i> View Portfolio</a>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <h2><i class="fas fa-plus"></i> Add New Work Experience</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add_work">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="position">Position/Job Title</label>
                        <input type="text" id="position" name="position" placeholder="e.g., Software Developer" required>
                    </div>
                    <div class="form-group">
                        <label for="company_name">Company Name</label>
                        <input type="text" id="company_name" name="company_name" placeholder="e.g., Tech Solutions Ltd." required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="text" id="start_date" name="start_date" placeholder="e.g., 2023-01 or Jan 2023" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="text" id="end_date" name="end_date" placeholder="e.g., Present or 2024-12">
                    </div>
                </div>

                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" placeholder="e.g., Dhaka, Bangladesh" required>
                </div>

                <div class="form-group">
                    <label for="summary">Job Summary/Description</label>
                    <textarea id="summary" name="summary" placeholder="Describe your role, responsibilities, and achievements..." required></textarea>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-plus"></i> Add Work Experience
                </button>
            </form>
        </div>

        <div class="work-list">
            <h2><i class="fas fa-list"></i> Work History</h2>
            
            <?php if (empty($work_experiences)): ?>
                <p>No work experience found. Add your first job above.</p>
            <?php else: ?>
                <?php foreach ($work_experiences as $work): ?>
                    <div class="work-item">
                        <div class="work-header">
                            <div>
                                <h3 class="work-title"><?php echo htmlspecialchars($work['position']); ?></h3>
                                <div class="work-company"><?php echo htmlspecialchars($work['company_name']); ?></div>
                                <div class="work-duration">
                                    <?php echo htmlspecialchars($work['start_date']); ?> - 
                                    <?php echo htmlspecialchars($work['end_date']); ?>
                                    <?php if (strtolower($work['end_date']) === 'present'): ?>
                                        <span class="current-badge">Current</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="work-location"><?php echo htmlspecialchars($work['location']); ?></div>
                        </div>
                        
                        <div class="work-summary">
                            <?php echo nl2br(htmlspecialchars($work['summary'])); ?>
                        </div>
                        
                        <div class="work-actions">
                            <button class="btn btn-warning" onclick="editWork(<?php echo $work['id']; ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this work experience?')">
                                <input type="hidden" name="action" value="delete_work">
                                <input type="hidden" name="id" value="<?php echo $work['id']; ?>">
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

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Work Experience</h2>
            <form method="POST" id="editForm">
                <input type="hidden" name="action" value="update_work">
                <input type="hidden" name="id" id="editId">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="editPosition">Position/Job Title</label>
                        <input type="text" id="editPosition" name="position" required>
                    </div>
                    <div class="form-group">
                        <label for="editCompanyName">Company Name</label>
                        <input type="text" id="editCompanyName" name="company_name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="editStartDate">Start Date</label>
                        <input type="text" id="editStartDate" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="editEndDate">End Date</label>
                        <input type="text" id="editEndDate" name="end_date">
                    </div>
                </div>

                <div class="form-group">
                    <label for="editLocation">Location</label>
                    <input type="text" id="editLocation" name="location" required>
                </div>

                <div class="form-group">
                    <label for="editSummary">Job Summary/Description</label>
                    <textarea id="editSummary" name="summary" required></textarea>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-save"></i> Update Work Experience
                </button>
            </form>
        </div>
    </div>

    <script>
        let workData = <?php echo json_encode($work_experiences); ?>;

        function editWork(id) {
            const work = workData.find(w => w.id == id);
            if (!work) return;

            document.getElementById('editId').value = work.id;
            document.getElementById('editPosition').value = work.position;
            document.getElementById('editCompanyName').value = work.company_name;
            document.getElementById('editStartDate').value = work.start_date;
            document.getElementById('editEndDate').value = work.end_date;
            document.getElementById('editLocation').value = work.location;
            document.getElementById('editSummary').value = work.summary;

            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
