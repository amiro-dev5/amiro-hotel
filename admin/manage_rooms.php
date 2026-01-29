<?php
session_start();
include('../includes/db_config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$success_msg = "";
$error_msg = "";

if (isset($_POST['add_room'])) {
    $room_no = mysqli_real_escape_string($conn, $_POST['room_number']);
    $type = mysqli_real_escape_string($conn, $_POST['room_type']);
    $price = $_POST['price'];

    $check_stmt = $conn->prepare("SELECT room_number FROM rooms WHERE room_number = ?");
    $check_stmt->bind_param("s", $room_no);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $error_msg = "Duplicate Entry: Room number '$room_no' already exists!";
    } else {
        $image_name = time() . "_" . $_FILES['room_image']['name'];
        $target = "../uploads/" . $image_name;

        if (move_uploaded_file($_FILES['room_image']['tmp_name'], $target)) {
            $stmt = $conn->prepare("INSERT INTO rooms (room_number, room_type, price, image, status) VALUES (?, ?, ?, ?, 'Available')");
            $stmt->bind_param("ssds", $room_no, $type, $price, $image_name);
            if ($stmt->execute()) {
                $success_msg = "Success: Room $room_no has been added successfully.";
            } else {
                $error_msg = "Database Error: Could not save the room.";
            }
        } else {
            $error_msg = "Upload Error: Could not upload the image.";
        }
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $img_query = $conn->query("SELECT image FROM rooms WHERE id = $id");
    $img_data = $img_query->fetch_assoc();
    if ($img_data) {
        if(file_exists("../uploads/" . $img_data['image'])) {
            unlink("../uploads/" . $img_data['image']);
        }
    }
    $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_rooms.php?msg=deleted");
    exit();
}

$rooms = $conn->query("SELECT * FROM rooms ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms | Elite Hotel Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 260px; height: 100vh; background: #2c3e50; color: white; padding: 25px; position: fixed; border-right: 3px solid #f39c12; overflow-y: auto; }
        .sidebar h2 { color: #f39c12; font-size: 1.5rem; margin-bottom: 30px; font-family: serif; letter-spacing: 1px; }
        .sidebar a { display: block; color: #ecf0f1; text-decoration: none; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1); transition: 0.3s; font-size: 0.9rem; }
        .sidebar a:hover { color: #f39c12; padding-left: 10px; }
        
        .main-content { margin-left: 300px; padding: 40px; width: calc(100% - 300px); }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
        h3 { color: #2c3e50; border-bottom: 2px solid #f39c12; display: inline-block; padding-bottom: 5px; margin-bottom: 25px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; }
        input, select { padding: 12px; border: 1px solid #ddd; border-radius: 8px; outline: none; font-family: inherit; }
        .btn-submit { background: #2c3e50; color: #f39c12; border: 2px solid #f39c12; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: 0.3s; }
        .btn-submit:hover { background: #f39c12; color: white; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; background: #fdfdfd; padding: 15px; color: #888; font-size: 0.8rem; text-transform: uppercase; border-bottom: 2px solid #eee; }
        td { padding: 15px; border-bottom: 1px solid #eee; vertical-align: middle; font-size: 0.9rem; }
        .room-img { width: 80px; height: 50px; object-fit: cover; border-radius: 6px; }
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; display: inline-block; }
        .status-available { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-booked { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn-edit { color: #3498db; text-decoration: none; font-weight: 600; margin-right: 15px; }
        .btn-delete { color: #e74c3c; text-decoration: none; font-weight: 600; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <?php include('includes/admin_sidebar.php'); ?>

    <div class="main-content">
        <?php if($success_msg): ?>
            <div class="alert alert-success">✅ <?php echo $success_msg; ?></div>
        <?php endif; ?>
        <?php if($error_msg): ?>
            <div class="alert alert-error">⚠️ <?php echo $error_msg; ?></div>
        <?php endif; ?>

        <div class="card">
            <h3>Add New Luxury Room</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <input type="text" name="room_number" placeholder="Room No" required>
                    <select name="room_type" required>
                        <option value="Single">Single Room</option>
                        <option value="Double">Double Room</option>
                        <option value="VIP Suite">VIP Suite</option>
                        <option value="Presidential">Presidential Suite</option>
                    </select>
                    <input type="number" name="price" placeholder="Price (ETB)" required>
                    <input type="file" name="room_image" accept="image/*" required>
                </div>
                <div style="margin-top: 20px;">
                    <button type="submit" name="add_room" class="btn-submit">REGISTER ROOM</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h3>Room Inventory</h3>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Room No</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $rooms->fetch_assoc()): ?>
                    <tr>
                        <td><img src="../uploads/<?php echo $row['image']; ?>" class="room-img"></td>
                        <td><strong><?php echo $row['room_number']; ?></strong></td>
                        <td><?php echo $row['room_type']; ?></td>
                        <td><?php echo number_format($row['price'], 2); ?> ETB</td>
                        <td>
                            <?php if(strtolower($row['status']) == 'available'): ?>
                                <span class="badge status-available">● Available</span>
                            <?php else: ?>
                                <span class="badge status-booked">● Booked</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_room.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete room?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>