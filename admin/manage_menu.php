<?php
session_start();
include('../includes/db_config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM food_menu WHERE id = $id");
    header("Location: manage_menu.php?msg=deleted");
    exit();
}

$menu_items = $conn->query("SELECT * FROM food_menu ORDER BY category ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Menu | Elite Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 260px; height: 100vh; background: #2c3e50; color: white; padding: 25px; position: fixed; border-right: 3px solid #f39c12; overflow-y: auto; }
        .sidebar h2 { color: #f39c12; font-size: 1.5rem; margin-bottom: 30px; font-family: serif; letter-spacing: 1px;}
        .sidebar a { display: block; color: #ecf0f1; text-decoration: none; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1); font-size: 0.9rem; transition: 0.3s; }
        .sidebar a:hover { color: #f39c12; padding-left: 10px; }
        .main-content { margin-left: 300px; padding: 40px; width: calc(100% - 300px); }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn-add { background: #f39c12; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .food-thumb { width: 50px; height: 50px; border-radius: 5px; object-fit: cover; }
        .cat-badge { padding: 4px 10px; border-radius: 15px; font-size: 0.75rem; font-weight: bold; background: #e8f4fd; color: #2980b9; }
        .btn-del { color: #e74c3c; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <?php include('includes/admin_sidebar.php'); ?>

    <div class="main-content">
        <div class="card">
            <div class="header-flex">
                <h2 style="margin:0;">🍔 Restaurant Menu Management</h2>
                <a href="add_menu.php" class="btn-add">+ Add New Item</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $menu_items->fetch_assoc()): ?>
                    <tr>
                        <td><img src="../uploads/<?php echo $row['image']; ?>" class="food-thumb"></td>
                        <td><strong><?php echo $row['food_name']; ?></strong></td>
                        <td><span class="cat-badge"><?php echo $row['category']; ?></span></td>
                        <td><?php echo number_format($row['price'], 2); ?> ETB</td>
                        <td>
                            <a href="?delete_id=<?php echo $row['id']; ?>" class="btn-del" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>