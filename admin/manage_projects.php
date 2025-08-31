<?php
session_start();
require_once '../config/database.php';
require_once 'auth_check.php';

$db = new Database();
$conn = $db->getConnection();

$success = '';
$error = '';

// Handle Projects Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    if ($_POST['action'] === 'add_project') {
        $name = trim($_POST['name']);
        $explanation = trim($_POST['explanation']);
        $tags = $_POST['tags'] ?? [];
        $best_lang = trim($_POST['best_lang']);
        $date = $_POST['date'];
        $link = trim($_POST['link']);
        $view_link = trim($_POST['view_link']);
        
        try {
            $stmt = $conn->prepare("INSERT INTO repositories (name, explanation, tags, best_lang, date, link, view_link) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $explanation, json_encode($tags), $best_lang, $date, $link, $view_link]);
            $success = "Project added successfully!";
        } catch (PDOException $e) {
            $error = "Error adding project: " . $e->getMessage();
        }
    }
    
    if ($_POST['action'] === 'update_project') {
        $id = $_POST['id'];
        $name = trim($_POST['name']);
        $explanation = trim($_POST['explanation']);
        $tags = $_POST['tags'] ?? [];
        $best_lang = trim($_POST['best_lang']);
        $date = $_POST['date'];
        $link = trim($_POST['link']);
        $view_link = trim($_POST['view_link']);
        
        try {
            $stmt = $conn->prepare("UPDATE repositories SET name = ?, explanation = ?, tags = ?, best_lang = ?, date = ?, link = ?, view_link = ? WHERE id = ?");
            $stmt->execute([$name, $explanation, json_encode($tags), $best_lang, $date, $link, $view_link, $id]);
            $success = "Project updated successfully!";
        } catch (PDOException $e) {
            $error = "Error updating project: " . $e->getMessage();
        }
    }
    
    if ($_POST['action'] === 'delete_project') {
        $id = $_POST['id'];
        try {
            $stmt = $conn->prepare("DELETE FROM repositories WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Project deleted successfully!";
        } catch (PDOException $e) {
            $error = "Error deleting project: " . $e->getMessage();
        }
    }
}

// Get all projects
$stmt = $conn->prepare("SELECT * FROM repositories ORDER BY date DESC");
$stmt->execute();
$projects = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects - Admin</title>
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
            min-height: 100px;
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

        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .tag-item {
            background: #3498db;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .tag-item .remove {
            cursor: pointer;
            font-weight: bold;
        }

        .tag-input {
            border: none;
            background: transparent;
            color: white;
            outline: none;
        }

        .tag-input::placeholder {
            color: rgba(255,255,255,0.7);
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

        .projects-list {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .project-item {
            border: 1px solid #ecf0f1;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: box-shadow 0.3s;
        }

        .project-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .project-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .project-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .project-lang {
            background: #e74c3c;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
        }

        .project-date {
            color: #7f8c8d;
            font-size: 12px;
        }

        .project-description {
            color: #555;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .project-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }

        .project-tag {
            background: #f8f9fa;
            color: #495057;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .project-links {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .project-link {
            color: #3498db;
            text-decoration: none;
            font-size: 12px;
        }

        .project-link:hover {
            text-decoration: underline;
        }

        .project-actions {
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
            margin: 2% auto;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-project-diagram"></i> Manage Projects</h1>
            <p>Add, edit, or remove your portfolio projects and repositories</p>
            <div class="nav-links">
                <a href="dashboard.php"><i class="fas fa-dashboard"></i> Dashboard</a>
                <a href="manage_skills.php"><i class="fas fa-cogs"></i> Skills</a>
                <a href="manage_education.php"><i class="fas fa-graduation-cap"></i> Education</a>
                <a href="manage_work.php"><i class="fas fa-briefcase"></i> Work Experience</a>
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
            <h2><i class="fas fa-plus"></i> Add New Project</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add_project">
                
                <div class="form-group">
                    <label for="name">Project Name</label>
                    <input type="text" id="name" name="name" placeholder="e.g., Android Time Tracker" required>
                </div>

                <div class="form-group">
                    <label for="explanation">Description</label>
                    <textarea id="explanation" name="explanation" placeholder="Describe your project..." required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="best_lang">Primary Language</label>
                        <input type="text" id="best_lang" name="best_lang" placeholder="e.g., JavaScript" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="link">Repository Link</label>
                        <input type="url" id="link" name="link" placeholder="https://github.com/..." required>
                    </div>
                    <div class="form-group">
                        <label for="view_link">Live Demo Link (Optional)</label>
                        <input type="url" id="view_link" name="view_link" placeholder="https://...">
                    </div>
                </div>

                <div class="form-group">
                    <label>Tags/Technologies</label>
                    <div id="tags-container">
                        <div class="tag-item">
                            <input type="text" class="tag-input" placeholder="Add tag..." onkeypress="addTag(event, this)">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-plus"></i> Add Project
                </button>
            </form>
        </div>

        <div class="projects-list">
            <h2><i class="fas fa-list"></i> Current Projects</h2>
            
            <?php if (empty($projects)): ?>
                <p>No projects found. Add your first project above.</p>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <div class="project-item">
                        <div class="project-header">
                            <div>
                                <h3 class="project-title"><?php echo htmlspecialchars($project['name']); ?></h3>
                                <div class="project-date"><?php echo date('M d, Y', strtotime($project['date'])); ?></div>
                            </div>
                            <div class="project-lang"><?php echo htmlspecialchars($project['best_lang']); ?></div>
                        </div>
                        
                        <div class="project-description">
                            <?php echo htmlspecialchars($project['explanation']); ?>
                        </div>
                        
                        <div class="project-tags">
                            <?php 
                            $tags = json_decode($project['tags'], true);
                            if ($tags && is_array($tags)) {
                                foreach ($tags as $tag) {
                                    echo '<span class="project-tag">' . htmlspecialchars($tag) . '</span>';
                                }
                            }
                            ?>
                        </div>

                        <div class="project-links">
                            <a href="<?php echo htmlspecialchars($project['link']); ?>" target="_blank" class="project-link">
                                <i class="fas fa-code"></i> Repository
                            </a>
                            <?php if (!empty($project['view_link'])): ?>
                                <a href="<?php echo htmlspecialchars($project['view_link']); ?>" target="_blank" class="project-link">
                                    <i class="fas fa-external-link-alt"></i> Live Demo
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="project-actions">
                            <button class="btn btn-warning" onclick="editProject(<?php echo $project['id']; ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this project?')">
                                <input type="hidden" name="action" value="delete_project">
                                <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
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
            <h2>Edit Project</h2>
            <form method="POST" id="editForm">
                <input type="hidden" name="action" value="update_project">
                <input type="hidden" name="id" id="editId">
                
                <div class="form-group">
                    <label for="editName">Project Name</label>
                    <input type="text" id="editName" name="name" required>
                </div>

                <div class="form-group">
                    <label for="editExplanation">Description</label>
                    <textarea id="editExplanation" name="explanation" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="editBestLang">Primary Language</label>
                        <input type="text" id="editBestLang" name="best_lang" required>
                    </div>
                    <div class="form-group">
                        <label for="editDate">Date</label>
                        <input type="date" id="editDate" name="date" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="editLink">Repository Link</label>
                        <input type="url" id="editLink" name="link" required>
                    </div>
                    <div class="form-group">
                        <label for="editViewLink">Live Demo Link</label>
                        <input type="url" id="editViewLink" name="view_link">
                    </div>
                </div>

                <div class="form-group">
                    <label>Tags/Technologies</label>
                    <div id="editTagsContainer">
                        <!-- Tags will be populated by JavaScript -->
                    </div>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-save"></i> Update Project
                </button>
            </form>
        </div>
    </div>

    <script>
        let projectsData = <?php echo json_encode($projects); ?>;

        function addTag(event, input) {
            if (event.key === 'Enter' && input.value.trim()) {
                event.preventDefault();
                
                const container = input.closest('#tags-container') || input.closest('#editTagsContainer');
                const newTag = document.createElement('div');
                newTag.className = 'tag-item';
                newTag.innerHTML = `
                    <span>${input.value.trim()}</span>
                    <span class="remove" onclick="removeTag(this)">×</span>
                    <input type="hidden" name="tags[]" value="${input.value.trim()}">
                `;
                
                container.insertBefore(newTag, input.parentElement);
                input.value = '';
            }
        }

        function removeTag(element) {
            element.parentElement.remove();
        }

        function editProject(id) {
            const project = projectsData.find(p => p.id == id);
            if (!project) return;

            document.getElementById('editId').value = project.id;
            document.getElementById('editName').value = project.name;
            document.getElementById('editExplanation').value = project.explanation;
            document.getElementById('editBestLang').value = project.best_lang;
            document.getElementById('editDate').value = project.date;
            document.getElementById('editLink').value = project.link;
            document.getElementById('editViewLink').value = project.view_link || '';

            // Clear and populate tags
            const container = document.getElementById('editTagsContainer');
            container.innerHTML = '';

            const tags = JSON.parse(project.tags || '[]');
            tags.forEach(tag => {
                const tagElement = document.createElement('div');
                tagElement.className = 'tag-item';
                tagElement.innerHTML = `
                    <span>${tag}</span>
                    <span class="remove" onclick="removeTag(this)">×</span>
                    <input type="hidden" name="tags[]" value="${tag}">
                `;
                container.appendChild(tagElement);
            });

            // Add input for new tags
            const inputTag = document.createElement('div');
            inputTag.className = 'tag-item';
            inputTag.innerHTML = '<input type="text" class="tag-input" placeholder="Add tag..." onkeypress="addTag(event, this)">';
            container.appendChild(inputTag);

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
