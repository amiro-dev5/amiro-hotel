<?php
session_start();
include('../includes/db_config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
$room = $conn->query("SELECT * FROM rooms WHERE id = $id")->fetch_assoc();

if (isset($_POST['update_room'])) {
    $room_no = $_POST['room_number'];
    $type = $_POST['room_type'];
    $price = $_POST['price'];
    
    $image_name = $room['image']; 
    if ($_FILES['room_image']['name']) {
        $image_name = time() . "_" . $_FILES['room_image']['name'];
        move_uploaded_file($_FILES['room_image']['tmp_name'], "../uploads/" . $image_name);
    }

    $stmt = $conn->prepare("UPDATE rooms SET room_number=?, room_type=?, price=?, image=? WHERE id=?");
    $stmt->bind_param("ssdsi", $room_no, $type, $price, $image_name, $id);
    
    if ($stmt->execute()) {
        header("Location: manage_rooms.php?msg=updated");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Room | Elite Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 260px; height: 100vh; background: #2c3e50; color: white; padding: 25px; position: fixed; border-right: 3px solid #f39c12; overflow-y: auto; }
        .sidebar h2 { color: #f39c12; font-size: 1.5rem; margin-bottom: 30px; font-family: serif; letter-spacing: 1px;}
        .sidebar a { display: block; color: #ecf0f1; text-decoration: none; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1); font-size: 0.9rem; transition: 0.3s; }
        .sidebar a:hover { color: #f39c12; padding-left: 10px; }
        .main-content { margin-left: 300px; padding: 40px; width: calc(100% - 300px); }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        label { font-weight: 600; font-size: 0.9rem; color: #2c3e50; }
        input, select { width: 100%; padding: 12px; margin: 10px 0 20px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .btn-update { background: #2c3e50; color: #f39c12; border: 2px solid #f39c12; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; width: 100%; transition: 0.3s; }
        .btn-update:hover { background: #f39c12; color: white; }
    </style>
</head>
<body>
    <?php include('includes/admin_sidebar.php'); ?>

    <div class="main-content">
        <div class="card">
            <h2 style="border-bottom: 2px solid #f39c12; display: inline-block; padding-bottom: 5px; margin-bottom: 25px;">Update Room Information</h2>
            <form method="POST" enctype="multipart/form-data">
                <label>Room Number</label>
                <input type="text" name="room_number" value="<?php echo $room['room_number']; ?>" required>
                
                <label>Room Type</label>
                <select name="room_type">
                    <option value="Single" <?php if($room['room_type']=='Single') echo 'selected'; ?>>Single Room</option>
                    <option value="Double" <?php if($room['room_type']=='Double') echo 'selected'; ?>>Double Room</option>
                    <option value="VIP Suite" <?php if($room['room_type']=='VIP Suite') echo 'selected'; ?>>VIP Suite</option>
                    <option value="Presidential" <?php if($room['room_type']=='Presidential') echo 'selected'; ?>>Presidential Suite</option>
                </select>

                <label>Price (ETB)</label>
                <input type="number" name="price" value="<?php echo $room['price']; ?>" required>

                <label>Room Image (Keep empty to stay the same)</label>
                <input type="file" name="room_image" accept="image/*">
                
                <button type="submit" name="update_room" class="btn-update">SAVE CHANGES</button>
                <p style="text-align: center; margin-top: 15px;"><a href="manage_rooms.php" style="color: #888; text-decoration: none; font-size: 0.8rem;">← Back to Inventory</a></p>
            </form>
        </div>
    </div>
</body>
</html>