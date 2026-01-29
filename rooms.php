<?php 
include('includes/db_config.php');
include('includes/header.php'); 

$rooms = $conn->query("SELECT * FROM rooms WHERE status = 'Available' ORDER BY id DESC");
?>

    <style>
        .header-title { padding: 60px 10%; text-align: center; background: white; }
        .header-title h1 { font-family: 'Playfair Display', serif; font-size: 3rem; color: #121212; margin: 0; }
        .rooms-container { padding: 40px 10% 100px; display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px; }
        .room-card { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid #eee; }
        .room-img { width: 100%; height: 250px; object-fit: cover; }
        .room-details { padding: 25px; }
        .btn-book { display: block; text-align: center; background: #2c3e50; color: #f39c12; padding: 12px; text-decoration: none; font-weight: bold; border: 1px solid #f39c12; border-radius: 4px; }
    </style>

    <div class="header-title">
        <h1>Our Exclusive Rooms</h1>
        <hr style="width: 80px; border: 1.5px solid #f39c12; margin: 15px auto 0;">
    </div>

    <div class="rooms-container">
        <?php if($rooms->num_rows > 0): ?>
            <?php while($row = $rooms->fetch_assoc()): ?>
            <div class="room-card">
                <img src="uploads/<?php echo $row['image']; ?>" class="room-img" alt="Room Image">
                <div class="room-details">
                    <span style="color: #f39c12; font-weight: 600; font-size: 0.8rem;"><?php echo $row['room_type']; ?></span>
                    <h3 style="margin: 10px 0; font-family: 'Playfair Display', serif;">Room <?php echo $row['room_number']; ?></h3>
                    <p style="font-size: 1.2rem; font-weight: 600;"><?php echo number_format($row['price'], 2); ?> ETB</p>
                    <a href="booking.php?room_id=<?php echo $row['id']; ?>" class="btn-book">BOOK NOW</a>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 50px;">
                <h3 style="color: #888;">All luxury rooms are currently occupied.</h3>
            </div>
        <?php endif; ?>
    </div>

<?php include('includes/footer.php'); ?>