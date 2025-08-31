<?php
session_start();
require_once '../config/database.php';
require_once 'auth_check.php';

$db = new Database();
$conn = $db->getConnection();

$success = '';
$error = '';

// Handle Skills Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    if ($_POST['action'] === 'add_skill') {
        $situation = trim($_POST['situation']);
        $keywords = $_POST['keywords'] ?? [];
        $level = trim($_POST['level']);
        
        // Debug: Log the received data
        error_log("Add skill - Situation: $situation, Keywords: " . print_r($keywords, true) . ", Level: $level");
        
        // Clean and filter keywords
        $cleanKeywords = array_filter(array_map('trim', $keywords));
        
        try {
            $stmt = $conn->prepare("INSERT INTO skills (situation, keywords, level) VALUES (?, ?, ?)");
            $result = $stmt->execute([$situation, json_encode($cleanKeywords), $level]);
            if ($result) {
                $success = "Skill category added successfully! Added " . count($cleanKeywords) . " keywords.";
            } else {
                $error = "Failed to add skill category.";
            }
        } catch (PDOException $e) {
            $error = "Error adding skill: " . $e->getMessage();
        }
    }
    
    if ($_POST['action'] === 'update_skill') {
        $id = $_POST['id'];
        $situation = trim($_POST['situation']);
        $keywords = $_POST['keywords'] ?? [];
        $level = trim($_POST['level']);
        
        // Debug: Log the received data
        error_log("Update skill ID $id - Situation: $situation, Keywords: " . print_r($keywords, true) . ", Level: $level");
        
        // Clean and filter keywords
        $cleanKeywords = array_filter(array_map('trim', $keywords));
        
        try {
            $stmt = $conn->prepare("UPDATE skills SET situation = ?, keywords = ?, level = ? WHERE id = ?");
            $result = $stmt->execute([$situation, json_encode($cleanKeywords), $level, $id]);
            if ($result) {
                $success = "Skill category updated successfully! Now has " . count($cleanKeywords) . " keywords.";
            } else {
                $error = "Failed to update skill category.";
            }
        } catch (PDOException $e) {
            $error = "Error updating skill: " . $e->getMessage();
        }
    }
    
    if ($_POST['action'] === 'delete_skill') {
        $id = $_POST['id'];
        try {
            $stmt = $conn->prepare("DELETE FROM skills WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Skill category deleted successfully!";
        } catch (PDOException $e) {
            $error = "Error deleting skill: " . $e->getMessage();
        }
    }
}

// Get all skills
$stmt = $conn->prepare("SELECT * FROM skills ORDER BY created_at DESC");
$stmt->execute();
$skills = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Skills - Admin</title>
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

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .keywords-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .keyword-tag {
            background: #3498db;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .keyword-tag .remove {
            cursor: pointer;
            font-weight: bold;
        }

        .keyword-input {
            border: none;
            background: transparent;
            color: white;
            outline: none;
        }

        .keyword-input::placeholder {
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

        .skills-list {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .skill-item {
            border: 1px solid #ecf0f1;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: box-shadow 0.3s;
        }

        .skill-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .skill-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 15px;
        }

        .skill-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }

        .skill-level {
            background: #ecf0f1;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            color: #7f8c8d;
        }

        .skill-keywords {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }

        .skill-keyword {
            background: #f8f9fa;
            color: #495057;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .skill-actions {
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
            margin: 5% auto;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
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
            <h1><i class="fas fa-cogs"></i> Manage Skills</h1>
            <p>Add, edit, or remove skill categories and keywords</p>
            <div class="nav-links">
                <a href="dashboard.php"><i class="fas fa-dashboard"></i> Dashboard</a>
                <a href="manage_education.php"><i class="fas fa-graduation-cap"></i> Education</a>
                <a href="manage_projects.php"><i class="fas fa-project-diagram"></i> Projects</a>
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
            <h2><i class="fas fa-plus"></i> Add New Skill Category</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add_skill">
                
                <div class="form-group">
                    <label for="situation">Category Name</label>
                    <input type="text" id="situation" name="situation" placeholder="e.g., Programming Languages" required>
                </div>

                <div class="form-group">
                    <label for="level">Level</label>
                    <select id="level" name="level" required>
                        <option value="">Select Level</option>
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advanced">Advanced</option>
                        <option value="Expert">Expert</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Keywords/Skills</label>
                    <p style="margin-bottom: 10px; color: #666; font-size: 14px;">
                        <i class="fas fa-info-circle"></i> Type a keyword and press <strong>Enter</strong> to add it. You can add multiple keywords.
                    </p>
                    <div id="keywords-container">
                        <div class="keyword-tag">
                            <input type="text" class="keyword-input" placeholder="Type keyword and press Enter..." onkeypress="addKeyword(event, this)">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-plus"></i> Add Skill Category
                </button>
            </form>
        </div>

        <div class="skills-list">
            <h2><i class="fas fa-list"></i> Current Skills</h2>
            
            <?php if (empty($skills)): ?>
                <p>No skills found. Add your first skill category above.</p>
            <?php else: ?>
                <?php foreach ($skills as $skill): ?>
                    <div class="skill-item">
                        <div class="skill-header">
                            <span class="skill-title"><?php echo htmlspecialchars($skill['situation']); ?></span>
                            <span class="skill-level"><?php echo htmlspecialchars($skill['level']); ?></span>
                        </div>
                        
                        <div class="skill-keywords">
                            <?php 
                            $keywords = json_decode($skill['keywords'], true);
                            if ($keywords && is_array($keywords)) {
                                foreach ($keywords as $keyword) {
                                    echo '<span class="skill-keyword">' . htmlspecialchars($keyword) . '</span>';
                                }
                            }
                            ?>
                        </div>
                        
                        <div class="skill-actions">
                            <button class="btn btn-success" onclick="addKeywordToCategory(<?php echo $skill['id']; ?>)">
                                <i class="fas fa-plus"></i> Add Keyword
                            </button>
                            <button class="btn btn-warning" onclick="editSkill(<?php echo $skill['id']; ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this skill category?')">
                                <input type="hidden" name="action" value="delete_skill">
                                <input type="hidden" name="id" value="<?php echo $skill['id']; ?>">
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
            <h2>Edit Skill Category</h2>
            <form method="POST" id="editForm">
                <input type="hidden" name="action" value="update_skill">
                <input type="hidden" name="id" id="editId">
                
                <div class="form-group">
                    <label for="editSituation">Category Name</label>
                    <input type="text" id="editSituation" name="situation" required>
                </div>

                <div class="form-group">
                    <label for="editLevel">Level</label>
                    <select id="editLevel" name="level" required>
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advanced">Advanced</option>
                        <option value="Expert">Expert</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Keywords/Skills</label>
                    <p style="margin-bottom: 10px; color: #666; font-size: 14px;">
                        <i class="fas fa-info-circle"></i> Type a keyword and press <strong>Enter</strong> to add it. Click <strong>×</strong> to remove keywords.
                    </p>
                    <div id="editKeywordsContainer">
                        <!-- Keywords will be populated by JavaScript -->
                    </div>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-save"></i> Update Skill Category
                </button>
            </form>
        </div>
    </div>

    <script>
        let skillsData = <?php echo json_encode($skills); ?>;

        function addKeyword(event, input) {
            if (event.key === 'Enter' && input.value.trim()) {
                event.preventDefault();
                
                const container = input.closest('#keywords-container') || input.closest('#editKeywordsContainer');
                const newTag = document.createElement('div');
                newTag.className = 'keyword-tag';
                newTag.innerHTML = `
                    <span>${input.value.trim()}</span>
                    <span class="remove" onclick="removeKeyword(this)">×</span>
                    <input type="hidden" name="keywords[]" value="${input.value.trim()}">
                `;
                
                container.insertBefore(newTag, input.parentElement);
                input.value = '';
            }
        }

        function removeKeyword(element) {
            element.parentElement.remove();
        }

        function addKeywordToCategory(skillId) {
            const skill = skillsData.find(s => s.id == skillId);
            if (!skill) return;

            const keyword = prompt(`Add a new keyword to "${skill.situation}":`);
            if (keyword && keyword.trim()) {
                // Create a form to submit the new keyword
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                // Get existing keywords
                const existingKeywords = JSON.parse(skill.keywords || '[]');
                existingKeywords.push(keyword.trim());
                
                // Add form fields
                form.innerHTML = `
                    <input type="hidden" name="action" value="update_skill">
                    <input type="hidden" name="id" value="${skillId}">
                    <input type="hidden" name="situation" value="${skill.situation}">
                    <input type="hidden" name="level" value="${skill.level}">
                    ${existingKeywords.map(k => `<input type="hidden" name="keywords[]" value="${k}">`).join('')}
                `;
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function editSkill(id) {
            const skill = skillsData.find(s => s.id == id);
            if (!skill) return;

            document.getElementById('editId').value = skill.id;
            document.getElementById('editSituation').value = skill.situation;
            document.getElementById('editLevel').value = skill.level;

            // Clear and populate keywords
            const container = document.getElementById('editKeywordsContainer');
            container.innerHTML = '';

            const keywords = JSON.parse(skill.keywords || '[]');
            keywords.forEach(keyword => {
                const tag = document.createElement('div');
                tag.className = 'keyword-tag';
                tag.innerHTML = `
                    <span>${keyword}</span>
                    <span class="remove" onclick="removeKeyword(this)">×</span>
                    <input type="hidden" name="keywords[]" value="${keyword}">
                `;
                container.appendChild(tag);
            });

            // Add input for new keywords
            const inputTag = document.createElement('div');
            inputTag.className = 'keyword-tag';
            inputTag.innerHTML = '<input type="text" class="keyword-input" placeholder="Add keyword..." onkeypress="addKeyword(event, this)">';
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
