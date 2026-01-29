<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('includes/db_config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['room_id'])) {
    die("Error: No room selected. Please go back to rooms page.");
}

$room_id = $_GET['room_id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();

if (!$room) {
    die("Error: Room not found in the database.");
}

$msg = "";

if (isset($_POST['confirm_booking'])) {
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    
    $diff = strtotime($check_out) - strtotime($check_in);
    $days = ceil($diff / (60 * 60 * 24));
    
    if ($days <= 0) {
        $msg = "<p style='color:red; background:#f8d7da; padding:10px; border-radius:5px;'>⚠️ Error: Check-out date must be after check-in!</p>";
    } else {
        $total_price = $days * $room['price'];
        $status = "Pending"; 
        $book_stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, total_price, status) VALUES (?, ?, ?, ?, ?, ?)");
        
        $book_stmt->bind_param("iissds", $user_id, $room_id, $check_in, $check_out, $total_price, $status);
        
        if ($book_stmt->execute()) {
        
            $msg = "<div style='background:#fff3cd; color:#856404; padding:25px; border-radius:15px; border:1px solid #ffeeba; text-align:center;'>
                    <h2 style='margin-top:0;'>Request Received!</h2>
                    <p>Your booking for <b>Room " . $room['room_number'] . "</b> has been sent successfully.</p>
                    <p style='font-size: 1.1rem;'>Current Status: <b style='color:#d4a017;'>PENDING</b></p>
                    <hr style='border: 0; border-top: 1px solid #ffeeba;'>
                    <p>Please wait while our administrator reviews and approves your reservation.</p>
                    <a href='my_bookings.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#856404; color:white; text-decoration:none; border-radius:5px;'>View My Bookings</a>
                    </div>";
        } else {
            $msg = "<p style='color:red; background:#f8d7da; padding:10px;'>Database Error: " . $conn->error . "</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Your Stay | Elite Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .booking-card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); width: 100%; max-width: 450px; text-align: center; }
        .room-preview { width: 100%; height: 220px; object-fit: cover; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h2 { color: #1a1a1a; margin-bottom: 10px; font-weight: 600; }
        p { color: #666; margin-bottom: 25px; }
        .form-group { text-align: left; margin-bottom: 20px; }
        label { display: block; font-size: 0.85rem; color: #555; margin-bottom: 8px; font-weight: 500; }
        input[type="date"] { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; font-family: inherit; font-size: 0.95rem; box-sizing: border-box; }
        .btn-confirm { background: #121212; color: #c5a059; border: 2px solid #c5a059; padding: 15px; width: 100%; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 1rem; transition: 0.3s; }
        .btn-confirm:hover { background: #c5a059; color: white; }
        .back-link { display: block; margin-top: 20px; color: #888; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>

    <div class="booking-card">
        <?php if($msg) echo $msg; ?>
        
        <?php if(!$msg || strpos($msg, 'Error') !== false): ?>
            <img src="uploads/<?php echo $room['image']; ?>" class="room-preview" alt="Room Image">
            <h2>Room <?php echo $room['room_number']; ?></h2>
            <p><?php echo $room['room_type']; ?> - <span style="color:#c5a059; font-weight:bold;"><?php echo number_format($room['price'], 2); ?> ETB</span> / Night</p>
            
            <form method="POST">
                <div class="form-group">
                    <label>Check-in Date</label>
                    <input type="date" name="check_in" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="form-group">
                    <label>Check-out Date</label>
                    <input type="date" name="check_out" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                </div>
                
                <button type="submit" name="confirm_booking" class="btn-confirm">CONFIRM RESERVATION</button>
            </form>
        <?php endif; ?>
        
        <a href="rooms.php" class="back-link">← Back to Rooms</a>
    </div>

</body>
</html>