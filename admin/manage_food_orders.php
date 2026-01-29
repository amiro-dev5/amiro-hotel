<?php
session_start();
include('../includes/db_config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['update_id']) && isset($_GET['new_status'])) {
    $order_id = $_GET['update_id'];
    $status = $_GET['new_status'];
    $stmt = $conn->prepare("UPDATE food_orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    header("Location: manage_food_orders.php");
    exit();
}

$query = "SELECT fo.*, u.full_name, fm.food_name, fm.price 
          FROM food_orders fo
          JOIN users u ON fo.user_id = u.id
          JOIN food_menu fm ON fo.food_id = fm.id
          ORDER BY fo.order_time DESC";
$orders = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Food Orders | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 260px; height: 100vh; background: #2c3e50; color: white; padding: 25px; position: fixed; border-right: 3px solid #f39c12; overflow-y: auto; }
        .sidebar h2 { color: #f39c12; font-size: 1.5rem; margin-bottom: 30px; font-family: serif; letter-spacing: 1px;}
        .sidebar a { display: block; color: #ecf0f1; text-decoration: none; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1); transition: 0.3s; font-size: 0.9rem; }
        .sidebar a:hover { color: #f39c12; padding-left: 10px; }
        
        .main-content { margin-left: 300px; padding: 40px; width: calc(100% - 300px); }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #fdfdfd; color: #888; font-size: 0.8rem; text-transform: uppercase; }

        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
        .status-Pending { background: #fff3cd; color: #856404; }
        .status-Preparing { background: #d1ecf1; color: #0c5460; }
        .status-Delivered { background: #d4edda; color: #155724; }

        .btn-action { text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; transition: 0.3s; margin-right: 5px; }
        .btn-prepare { background: #3498db; color: white; }
        .btn-deliver { background: #27ae60; color: white; }
    </style>
</head>
<body>
    <?php include('includes/admin_sidebar.php'); ?>

    <div class="main-content">
        <div class="card">
            <h2>🛎️ Room Service Orders</h2>
            <p>Track and manage guest food & drink requests in real-time.</p>

            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Guest / Room</th>
                        <th>Order Detail</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $orders->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('h:i A', strtotime($row['order_time'])); ?></td>
                        <td>
                            <strong><?php echo $row['full_name']; ?></strong><br>
                            <small>Room: <?php echo $row['room_number']; ?></small>
                        </td>
                        <td>
                            <?php echo $row['quantity']; ?> x <?php echo $row['food_name']; ?><br>
                            <small>Total: <?php echo number_format($row['price'] * $row['quantity'], 2); ?> ETB</small>
                        </td>
                        <td>
                            <span class="status-badge status-<?php echo $row['status']; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td>
                            <?php if($row['status'] == 'Pending'): ?>
                                <a href="?update_id=<?php echo $row['id']; ?>&new_status=Preparing" class="btn-action btn-prepare">Start Preparing</a>
                            <?php elseif($row['status'] == 'Preparing'): ?>
                                <a href="?update_id=<?php echo $row['id']; ?>&new_status=Delivered" class="btn-action btn-deliver">Mark Delivered</a>
                            <?php else: ?>
                                <span style="color: #95a5a6; font-style: italic;">Completed</span>
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