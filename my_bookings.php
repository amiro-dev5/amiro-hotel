<?php 
include('includes/db_config.php');
include('includes/header.php'); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT b.*, r.room_number, r.room_type 
          FROM bookings b 
          JOIN rooms r ON b.room_id = r.id 
          WHERE b.user_id = $user_id 
          ORDER BY b.id DESC";
$result = $conn->query($query);


$order_query = "SELECT fo.*, fm.food_name, fm.price 
                FROM food_orders fo
                JOIN food_menu fm ON fo.food_id = fm.id
                WHERE fo.user_id = $user_id 
                ORDER BY fo.id DESC";
$orders = $conn->query($order_query);
?>

    <style>
        .container { max-width: 1100px; margin: 50px auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); min-height: 60vh; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .status { padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        
        .Pending { background: #fff3cd; color: #856404; }
        .Approved, .Delivered { background: #d4edda; color: #155724; }
        .Preparing { background: #d1ecf1; color: #0c5460; }
        .Rejected { background: #f8d7da; color: #721c24; }
        
        .section-title { border-bottom: 2px solid #f39c12; display: inline-block; padding-bottom: 5px; margin-bottom: 25px; margin-top: 20px; }
    </style>

    <div class="container">
        
        <h2 class="section-title">My Booking History</h2>
        
        <?php if($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Type</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Total Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><b>Room <?php echo $row['room_number']; ?></b></td>
                    <td><?php echo $row['room_type']; ?></td>
                    <td><?php echo $row['check_in_date']; ?></td>
                    <td><?php echo $row['check_out_date']; ?></td>
                    <td><?php echo number_format($row['total_price'], 2); ?> ETB</td>
                    <td><span class="status <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p style="text-align: center; color: #888; padding: 20px;">You haven't made any bookings yet.</p>
        <?php endif; ?>

        <br><br>

        <h2 class="section-title">Room Service Orders</h2>
        
        <?php if($orders->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Order Details</th>
                    <th>Room</th>
                    <th>Total Cost</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($order = $orders->fetch_assoc()): ?>
                <tr>
                    <td><?php echo date('M d, h:i A', strtotime($order['order_time'])); ?></td>
                    <td><b><?php echo $order['quantity']; ?>x <?php echo $order['food_name']; ?></b></td>
                    <td>Room <?php echo $order['room_number']; ?></td>
                    <td><?php echo number_format($order['price'] * $order['quantity'], 2); ?> ETB</td>
                    <td>
                        <span class="status <?php echo $order['status']; ?>">
                            <?php 
                                if($order['status'] == 'Pending') echo "⏳ Pending";
                                elseif($order['status'] == 'Preparing') echo "👨‍🍳 Preparing";
                                elseif($order['status'] == 'Delivered') echo "✅ Delivered";
                                else echo $order['status'];
                            ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p style="text-align: center; color: #888; padding: 20px;">No room service orders found. <a href="order_food.php" style="color: #f39c12;">Order here</a>.</p>
        <?php endif; ?>

    </div>

<?php include('includes/footer.php'); ?>