<?php
session_start();
require_once '../config/database.php';
require_once 'auth_check.php';

$db = new Database();
$conn = $db->getConnection();

// Handle mark as read action
if (isset($_POST['mark_read'])) {
    $messageId = (int)$_POST['message_id'];
    $stmt = $conn->prepare("UPDATE contact_messages SET is_read = TRUE WHERE id = ?");
    $stmt->execute([$messageId]);
    header("Location: manage_messages.php");
    exit;
}

// Handle delete action
if (isset($_POST['delete_message'])) {
    $messageId = (int)$_POST['message_id'];
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->execute([$messageId]);
    header("Location: manage_messages.php");
    exit;
}

// Get all messages
$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
$unreadCount = $conn->query("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = FALSE")->fetch()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Messages - Portfolio Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            border-right: 1px solid rgba(255, 255, 255, 0.18);
        }

        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
        }

        .header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            text-align: center;
        }

        .stat-card i {
            font-size: 2.5em;
            color: #667eea;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-card p {
            color: #7f8c8d;
            font-size: 0.9em;
        }

        .messages-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
        }

        .message-item {
            border: 1px solid #e1e8ed;
            border-radius: 10px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .message-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .message-item.unread {
            border-left: 4px solid #667eea;
            background: rgba(102, 126, 234, 0.05);
        }

        .message-header {
            padding: 20px;
            border-bottom: 1px solid #e1e8ed;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .message-info h4 {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .message-info p {
            color: #7f8c8d;
            font-size: 0.9em;
        }

        .message-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9em;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .message-body {
            padding: 20px;
        }

        .message-body h5 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .message-body p {
            color: #34495e;
            line-height: 1.6;
        }

        .no-messages {
            text-align: center;
            padding: 50px;
            color: #7f8c8d;
        }

        .no-messages i {
            font-size: 4em;
            margin-bottom: 20px;
            color: #bdc3c7;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .main-content {
                padding: 20px;
            }
            
            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h1><i class="fas fa-envelope"></i> Contact Messages</h1>
            <p>Manage messages from your portfolio contact form</p>
        </div>

        <div class="stats">
            <div class="stat-card">
                <i class="fas fa-envelope"></i>
                <h3><?php echo count($messages); ?></h3>
                <p>Total Messages</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-envelope-open"></i>
                <h3><?php echo $unreadCount; ?></h3>
                <p>Unread Messages</p>
            </div>
        </div>

        <div class="messages-container">
            <h2 style="margin-bottom: 20px; color: #2c3e50;">
                <i class="fas fa-inbox"></i> Messages
            </h2>

            <?php if (empty($messages)): ?>
                <div class="no-messages">
                    <i class="fas fa-inbox"></i>
                    <h3>No messages yet</h3>
                    <p>Messages from your contact form will appear here.</p>
                </div>
            <?php else: ?>
                <?php foreach ($messages as $message): ?>
                    <div class="message-item <?php echo $message['is_read'] ? '' : 'unread'; ?>">
                        <div class="message-header">
                            <div class="message-info">
                                <h4><?php echo htmlspecialchars($message['name']); ?></h4>
                                <p>
                                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($message['email']); ?> |
                                    <i class="fas fa-clock"></i> <?php echo date('M j, Y g:i A', strtotime($message['created_at'])); ?>
                                    <?php if (!$message['is_read']): ?>
                                        <span style="color: #667eea; font-weight: bold;">• NEW</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="message-actions">
                                <?php if (!$message['is_read']): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                        <button type="submit" name="mark_read" class="btn btn-primary">
                                            <i class="fas fa-check"></i> Mark Read
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this message?')">
                                    <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                    <button type="submit" name="delete_message" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="message-body">
                            <h5><i class="fas fa-tag"></i> Subject: <?php echo htmlspecialchars($message['subject']); ?></h5>
                            <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
