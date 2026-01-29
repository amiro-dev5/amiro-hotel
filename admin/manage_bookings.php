<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include('../includes/db_config.php');

// --- 1. APPROVE Logic ---
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    $room_res = $conn->query("SELECT room_id FROM bookings WHERE id = $id");
    $room_id = $room_res->fetch_assoc()['room_id'];
    $conn->query("UPDATE bookings SET status = 'Approved' WHERE id = $id");
    $conn->query("UPDATE rooms SET status = 'Booked' WHERE id = $room_id");
    header("Location: manage_bookings.php?msg=approved");
    exit();
}

// --- 2. REJECT Logic ---
if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    $conn->query("UPDATE bookings SET status = 'Rejected' WHERE id = $id");
    header("Location: manage_bookings.php?msg=rejected");
    exit();
}

// --- 3. CHECK-OUT Logic ---
if (isset($_GET['checkout'])) {
    $id = $_GET['checkout'];
    $room_res = $conn->query("SELECT room_id FROM bookings WHERE id = $id");
    $room_id = $room_res->fetch_assoc()['room_id'];
    $conn->query("UPDATE bookings SET status = 'Completed' WHERE id = $id");
    $conn->query("UPDATE rooms SET status = 'Available' WHERE id = $room_id");
    header("Location: manage_bookings.php?msg=checkedout");
    exit();
}

// --- 4. DELETE Logic ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM bookings WHERE id = $id");
    header("Location: manage_bookings.php?msg=deleted");
    exit();
}

$bookings = $conn->query("SELECT b.*, u.full_name, r.room_number FROM bookings b 
                          JOIN users u ON b.user_id = u.id 
                          JOIN rooms r ON b.room_id = r.id ORDER BY b.id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings | Elite Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; margin: 0; display: flex; }
        /* Sidebar CSS */
        .sidebar { width: 260px; height: 100vh; background: #2c3e50; color: white; padding: 25px; position: fixed; border-right: 3px solid #f39c12; overflow-y: auto; }
        .sidebar h2 { color: #f39c12; font-size: 1.5rem; margin-bottom: 30px; font-family: serif; letter-spacing: 1px;}
        .sidebar a { display: block; color: #ecf0f1; text-decoration: none; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1); transition: 0.3s; font-size: 0.9rem; }
        .sidebar a:hover { color: #f39c12; padding-left: 10px; }
        
        .main-content { margin-left: 300px; padding: 40px; width: calc(100% - 300px); }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h2.title { color: #2c3e50; border-bottom: 2px solid #f39c12; display: inline-block; padding-bottom: 5px; margin-bottom: 20px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; color: #888; font-size: 0.8rem; text-transform: uppercase; border-bottom: 2px solid #eee; }
        td { padding: 15px; border-bottom: 1px solid #eee; font-size: 0.9rem; }
        
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .Pending { background: #fff3cd; color: #856404; }
        .Approved { background: #d4edda; color: #155724; }
        .Rejected { background: #f8d7da; color: #721c24; }
        .Completed { background: #e2e8f0; color: #475569; }
        
        .btn { padding: 6px 12px; border-radius: 5px; text-decoration: none; font-size: 0.75rem; color: white; margin-right: 5px; font-weight: bold;}
        .btn-app { background: #2ecc71; }
        .btn-rej { background: #f39c12; }
        .btn-check { background: #3498db; }
        .btn-del { background: #e74c3c; }
    </style>
</head>
<body>
    <?php include('includes/admin_sidebar.php'); ?>

    <div class="main-content">
        <div class="card">
            <h2 class="title">Reservations Control Panel</h2>
            <table>
                <thead>
                    <tr>
                        <th>Guest</th>
                        <th>Room</th>
                        <th>Dates</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $bookings->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo $row['full_name']; ?></strong></td>
                        <td>Room <?php echo $row['room_number']; ?></td>
                        <td><?php echo $row['check_in_date']; ?> - <?php echo $row['check_out_date']; ?></td>
                        <td><span class="badge <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                        <td>
                            <?php if($row['status'] == 'Pending'): ?>
                                <a href="?approve=<?php echo $row['id']; ?>" class="btn btn-app">Approve</a>
                                <a href="?reject=<?php echo $row['id']; ?>" class="btn btn-rej">Reject</a>
                            <?php elseif($row['status'] == 'Approved'): ?>
                                <a href="?checkout=<?php echo $row['id']; ?>" class="btn btn-check">Check-out</a>
                            <?php endif; ?>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-del" onclick="return confirm('Delete record permanently?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>