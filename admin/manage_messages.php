<?php
session_start();
include('../includes/db_config.php');

// አድሚን መሆኑን ማረጋገጥ
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// ለሜሴጅ መልስ ለመስጠት (Reply)
if (isset($_POST['send_reply'])) {
    $msg_id = $_POST['msg_id'];
    $reply = $conn->real_escape_string($_POST['reply_text']);
    $conn->query("UPDATE contacts SET admin_reply = '$reply', status = 'Replied' WHERE id = $msg_id");
}

// መልዕክቶችን ከነተጠቃሚው ስም ማምጣት
$messages = $conn->query("SELECT c.*, u.full_name FROM contacts c JOIN users u ON c.user_id = u.id ORDER BY c.id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Messages | Elite Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; margin: 0; display: flex; }
        
        /* Sidebar Styling */
        .sidebar { width: 260px; height: 100vh; background: #2c3e50; color: white; padding: 25px; position: fixed; border-right: 3px solid #f39c12; overflow-y: auto; }
        .sidebar h2 { color: #f39c12; font-size: 1.5rem; margin-bottom: 30px; font-family: serif; letter-spacing: 1px;}
        .sidebar a { display: block; color: #ecf0f1; text-decoration: none; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1); transition: 0.3s; font-size: 0.9rem; }
        .sidebar a:hover { color: #f39c12; padding-left: 10px; }
        
        /* Main Content Styling */
        .main-content { margin-left: 300px; padding: 40px; width: calc(100% - 300px); }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        h2 { color: #2c3e50; border-bottom: 2px solid #f39c12; display: inline-block; padding-bottom: 5px; margin-bottom: 25px; }

        /* Table Styling */
        .msg-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .msg-table th { text-align: left; background: #fdfdfd; padding: 15px; color: #888; font-size: 0.8rem; text-transform: uppercase; border-bottom: 2px solid #eee; }
        .msg-table td { padding: 20px 15px; border-bottom: 1px solid #eee; vertical-align: top; }
        
        /* Reply Section Styling */
        .reply-box { background: #f9f9f9; padding: 15px; border-radius: 8px; border-left: 4px solid #f39c12; margin-top: 10px; }
        .reply-input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 10px; box-sizing: border-box; font-family: inherit; }
        
        .btn-reply { background: #2c3e50; color: #f39c12; border: 2px solid #f39c12; padding: 8px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: 0.3s; }
        .btn-reply:hover { background: #f39c12; color: white; }
        
        .status-badge { font-size: 0.75rem; padding: 3px 10px; border-radius: 12px; font-weight: bold; margin-bottom: 5px; display: inline-block; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-replied { background: #d4edda; color: #155724; }
        
        .guest-info { color: #2c3e50; font-weight: 600; }
        .msg-text { color: #555; font-size: 0.9rem; line-height: 1.6; margin-top: 5px; }
    </style>
</head>
<body>
    <?php include('includes/admin_sidebar.php'); ?>

    <div class="main-content">
        <div class="card">
            <h2>📩 Guest Messages & Feedback</h2>
            <p style="color: #777; margin-bottom: 30px;">Read and respond to inquiries from your hotel guests.</p>

            <table class="msg-table">
                <thead>
                    <tr>
                        <th style="width: 20%;">Guest</th>
                        <th style="width: 45%;">Subject & Message</th>
                        <th style="width: 35%;">Response / Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $messages->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div class="guest-info"><?php echo $row['full_name']; ?></div>
                            <small style="color: #999;"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></small>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #333;"><?php echo $row['subject']; ?></div>
                            <div class="msg-text"><?php echo $row['message']; ?></div>
                        </td>
                        <td>
                            <?php if(!$row['admin_reply']): ?>
                                <span class="status-badge status-pending">Pending Response</span>
                                <form method="POST">
                                    <input type="hidden" name="msg_id" value="<?php echo $row['id']; ?>">
                                    <textarea name="reply_text" class="reply-input" placeholder="Write your reply here..." rows="2" required></textarea>
                                    <button type="submit" name="send_reply" class="btn-reply">Send Reply</button>
                                </form>
                            <?php else: ?>
                                <span class="status-badge status-replied">Replied</span>
                                <div class="reply-box">
                                    <small style="font-weight: 600; color: #f39c12; display: block; margin-bottom: 5px;">Admin Response:</small>
                                    <span style="font-size: 0.85rem; color: #444;"><?php echo $row['admin_reply']; ?></span>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>