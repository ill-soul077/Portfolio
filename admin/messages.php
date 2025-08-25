<?php
require_once '../config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

updateLastActivity();

$success_message = '';
$error_message = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $message_id = $_POST['message_id'] ?? '';
    
    if ($action === 'mark_read' && $message_id) {
        try {
            $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
            $stmt->execute([$message_id]);
            $success_message = 'Message marked as read';
        } catch (PDOException $e) {
            $error_message = 'Error updating message';
        }
    } elseif ($action === 'delete' && $message_id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
            $stmt->execute([$message_id]);
            $success_message = 'Message deleted successfully';
        } catch (PDOException $e) {
            $error_message = 'Error deleting message';
        }
    } elseif ($action === 'mark_all_read') {
        try {
            $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE is_read = 0");
            $stmt->execute();
            $success_message = 'All messages marked as read';
        } catch (PDOException $e) {
            $error_message = 'Error updating messages';
        }
    }
}

// Get messages
$filter = $_GET['filter'] ?? 'all';
$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 20;
$offset = ($page - 1) * $per_page;

try {
    // Build query based on filter
    $where_clause = '';
    $params = [];
    
    if ($filter === 'unread') {
        $where_clause = 'WHERE is_read = 0';
    } elseif ($filter === 'read') {
        $where_clause = 'WHERE is_read = 1';
    }
    
    // Get total count
    $count_sql = "SELECT COUNT(*) FROM messages $where_clause";
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute($params);
    $total_messages = $stmt->fetchColumn();
    
    // Get messages for current page
    $sql = "SELECT * FROM messages $where_clause ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $per_page;
    $params[] = $offset;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $messages = $stmt->fetchAll();
    
    $total_pages = ceil($total_messages / $per_page);
    
} catch (PDOException $e) {
    $error_message = 'Error loading messages';
    $messages = [];
    $total_messages = 0;
    $total_pages = 0;
}

// Get counts for filters
try {
    $stmt = $pdo->query("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread,
        SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as read
        FROM messages");
    $counts = $stmt->fetch();
} catch (PDOException $e) {
    $counts = ['total' => 0, 'unread' => 0, 'read' => 0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .message-filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .filter-link {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }
        
        .filter-link.active {
            background: var(--accent-primary);
            color: white;
            border-color: var(--accent-primary);
        }
        
        .filter-link:hover {
            border-color: var(--accent-primary);
        }
        
        .messages-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg-secondary);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .messages-table th,
        .messages-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-light);
        }
        
        .messages-table th {
            background: var(--bg-tertiary);
            font-weight: 600;
        }
        
        .message-row {
            transition: background-color 0.2s ease;
        }
        
        .message-row:hover {
            background: var(--bg-tertiary);
        }
        
        .message-row.unread {
            background: rgba(61, 124, 206, 0.05);
        }
        
        .message-unread-indicator {
            width: 8px;
            height: 8px;
            background: var(--accent-primary);
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
        }
        
        .message-content {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .message-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .message-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .message-modal.active {
            display: flex;
        }
        
        .message-modal-content {
            background: var(--bg-secondary);
            border-radius: 8px;
            padding: 2rem;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        
        .pagination a {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-primary);
        }
        
        .pagination a.active {
            background: var(--accent-primary);
            color: white;
            border-color: var(--accent-primary);
        }
        
        .pagination a:hover:not(.active) {
            border-color: var(--accent-primary);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Contact Messages</h1>
            <div>
                <?php if ($counts['unread'] > 0): ?>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="mark_all_read">
                        <button type="submit" class="btn btn-secondary">Mark All Read</button>
                    </form>
                <?php endif; ?>
                <a href="dashboard.php" class="btn btn-secondary">← Dashboard</a>
            </div>
        </div>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?= h($success_message) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-error">
                <?= h($error_message) ?>
            </div>
        <?php endif; ?>
        
        <!-- Message Filters -->
        <div class="message-filters">
            <a href="?filter=all" class="filter-link <?= $filter === 'all' ? 'active' : '' ?>">
                All (<?= $counts['total'] ?>)
            </a>
            <a href="?filter=unread" class="filter-link <?= $filter === 'unread' ? 'active' : '' ?>">
                Unread (<?= $counts['unread'] ?>)
            </a>
            <a href="?filter=read" class="filter-link <?= $filter === 'read' ? 'active' : '' ?>">
                Read (<?= $counts['read'] ?>)
            </a>
        </div>
        
        <!-- Messages Table -->
        <div class="card">
            <?php if (empty($messages)): ?>
                <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                    <h3>No messages found</h3>
                    <p>
                        <?php if ($filter === 'unread'): ?>
                            You have no unread messages.
                        <?php elseif ($filter === 'read'): ?>
                            You have no read messages.
                        <?php else: ?>
                            No one has contacted you yet.
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <table class="messages-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $message): ?>
                            <tr class="message-row <?= !$message['is_read'] ? 'unread' : '' ?>">
                                <td>
                                    <?php if (!$message['is_read']): ?>
                                        <span class="message-unread-indicator" title="Unread"></span>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 0.8rem;">Read</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= h($message['name']) ?></strong></td>
                                <td>
                                    <a href="mailto:<?= h($message['email']) ?>"><?= h($message['email']) ?></a>
                                </td>
                                <td>
                                    <div class="message-content">
                                        <?= h($message['message']) ?>
                                    </div>
                                </td>
                                <td>
                                    <small><?= date('M j, Y g:i A', strtotime($message['created_at'])) ?></small>
                                </td>
                                <td>
                                    <div class="message-actions">
                                        <button onclick="viewMessage(<?= $message['id'] ?>)" 
                                                class="btn btn-secondary" 
                                                style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                                            View
                                        </button>
                                        
                                        <?php if (!$message['is_read']): ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="mark_read">
                                                <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                                                <button type="submit" 
                                                        class="btn btn-primary" 
                                                        style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                                                    Mark Read
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <form method="POST" style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this message?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                                            <button type="submit" 
                                                    class="btn btn-danger" 
                                                    style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?filter=<?= h($filter) ?>&page=<?= $page - 1 ?>">← Previous</a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <a href="?filter=<?= h($filter) ?>&page=<?= $i ?>" 
                               class="<?= $i === $page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <a href="?filter=<?= h($filter) ?>&page=<?= $page + 1 ?>">Next →</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Message Modal -->
    <div class="message-modal" id="messageModal">
        <div class="message-modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3>Message Details</h3>
                <button onclick="closeModal()" class="btn btn-secondary">×</button>
            </div>
            <div id="modalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
    
    <script>
        // Apply saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        
        // Message data for modal
        const messages = <?= json_encode($messages) ?>;
        
        function viewMessage(messageId) {
            const message = messages.find(m => m.id == messageId);
            if (!message) return;
            
            const modal = document.getElementById('messageModal');
            const content = document.getElementById('modalContent');
            
            content.innerHTML = `
                <div class="card">
                    <div style="display: grid; gap: 1rem; margin-bottom: 1rem;">
                        <div><strong>From:</strong> ${escapeHtml(message.name)} (${escapeHtml(message.email)})</div>
                        <div><strong>Date:</strong> ${new Date(message.created_at).toLocaleString()}</div>
                        <div><strong>Status:</strong> ${message.is_read ? 'Read' : 'Unread'}</div>
                    </div>
                    <div style="border-top: 1px solid var(--border-color); padding-top: 1rem;">
                        <strong>Message:</strong>
                        <div style="margin-top: 0.5rem; line-height: 1.6; white-space: pre-wrap;">${escapeHtml(message.message)}</div>
                    </div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                        <a href="mailto:${escapeHtml(message.email)}?subject=Re: Portfolio Contact" class="btn btn-primary">Reply via Email</a>
                    </div>
                </div>
            `;
            
            modal.classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('messageModal').classList.remove('active');
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Close modal when clicking outside
        document.getElementById('messageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>
