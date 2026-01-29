<?php 
include('includes/db_config.php');
include('includes/header.php'); 

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$msg_sent = false;
if (isset($_POST['send_message'])) {
    $user_id = $_SESSION['user_id'];
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO contacts (user_id, subject, message) VALUES ('$user_id', '$subject', '$message')";
    if ($conn->query($sql)) { $msg_sent = true; }
}


$user_id = $_SESSION['user_id'];
$my_messages = $conn->query("SELECT * FROM contacts WHERE user_id = $user_id ORDER BY id DESC");
?>

<style>
    .contact-wrapper { padding: 50px 10%; background: #f9f9f9; min-height: 80vh; }
    .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
    .form-card, .history-card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
    input, textarea { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; }
    .btn-send { background: #2c3e50; color: #f39c12; border: 1.5px solid #f39c12; padding: 12px 30px; cursor: pointer; font-weight: bold; border-radius: 5px; transition: 0.3s; }
    .btn-send:hover { background: #f39c12; color: white; }
    .reply-box { background: #e8f4fd; padding: 10px; border-radius: 8px; margin-top: 10px; font-size: 0.9rem; border-left: 4px solid #3498db; }
</style>

<div class="contact-wrapper">
    <div class="welcome-text" style="text-align:center; margin-bottom:40px;">
        <h1 style="font-family: 'Playfair Display', serif;">Get In Touch</h1>
        <p>Do you have any requests or feedback? Our team is here to listen.</p>
    </div>

    <div class="contact-grid">
        <div class="form-card">
            <h3>Send us a Message</h3>
            <?php if($msg_sent): ?>
                <p style="color: green; font-weight: bold;">✅ Message sent! We will get back to you soon.</p>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="subject" placeholder="Subject (e.g., Room Cleaning, Towels request)" required>
                <textarea name="message" rows="6" placeholder="Write your message here..." required></textarea>
                <button type="submit" name="send_message" class="btn-send">SEND MESSAGE</button>
            </form>
        </div>

        <div class="history-card">
            <h3>My Inquiries</h3>
            <div style="max-height: 500px; overflow-y: auto;">
                <?php while($row = $my_messages->fetch_assoc()): ?>
                    <div style="border-bottom: 1px solid #eee; padding: 15px 0;">
                        <span style="font-size: 0.8rem; color: #888;"><?php echo $row['created_at']; ?></span>
                        <h4 style="margin: 5px 0;"><?php echo $row['subject']; ?></h4>
                        <p style="font-size: 0.95rem; color: #555;"><?php echo $row['message']; ?></p>
                        
                        <?php if($row['admin_reply']): ?>
                            <div class="reply-box">
                                <strong>Admin Reply:</strong><br>
                                <?php echo $row['admin_reply']; ?>
                            </div>
                        <?php else: ?>
                            <span style="color: #f39c12; font-size: 0.8rem;">⌛ Waiting for reply...</span>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>