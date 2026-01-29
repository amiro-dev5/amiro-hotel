<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include('../includes/db_config.php');

$total_rooms = $conn->query("SELECT id FROM rooms")->num_rows;
$active_bookings = $conn->query("SELECT id FROM bookings WHERE status='Approved'")->num_rows;
$new_orders = $conn->query("SELECT id FROM food_orders WHERE status='Pending'")->num_rows; // አዲስ ትዕዛዝ
$new_messages = $conn->query("SELECT id FROM contacts WHERE status='Sent'")->num_rows; // አዲስ መልእክት
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Elite Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; margin: 0; display: flex; }
    
        .sidebar { width: 260px; height: 100vh; background: #2c3e50; color: white; padding: 25px; position: fixed; border-right: 3px solid #f39c12; overflow-y: auto; }
        .sidebar h2 { color: #f39c12; font-size: 1.5rem; margin-bottom: 30px; font-family: serif; letter-spacing: 1px;}
        .sidebar a { display: block; color: #ecf0f1; text-decoration: none; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1); transition: 0.3s; font-size: 0.9rem; }
        .sidebar a:hover { color: #f39c12; padding-left: 10px; }
        
        .main-content { margin-left: 300px; padding: 40px; width: calc(100% - 300px); }
        .header { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .stat-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-top: 4px solid #f39c12; text-align: center; transition: 0.3s;}
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

    <?php include('includes/admin_sidebar.php'); ?>

    <div class="main-content">
        <div class="header">
            <h1>Welcome to Admin Panel</h1>
            <p>Hello Admin, here is the latest activity in your hotel.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Rooms</h3>
                <p style="font-size: 1.8rem; font-weight: bold; color: #2c3e50;"><?php echo $total_rooms; ?></p>
            </div>
            <div class="stat-card">
                <h3>Active Bookings</h3>
                <p style="font-size: 1.8rem; font-weight: bold; color: #2ecc71;"><?php echo $active_bookings; ?></p>
            </div>
            <div class="stat-card">
                <h3>New Food Orders</h3>
                <p style="font-size: 1.8rem; font-weight: bold; color: #f39c12;"><?php echo $new_orders; ?></p>
            </div>
            <div class="stat-card">
                <h3>New Messages</h3>
                <p style="font-size: 1.8rem; font-weight: bold; color: #e74c3c;"><?php echo $new_messages; ?></p>
            </div>
        </div>
    </div>

</body>
</html>