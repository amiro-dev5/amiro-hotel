<?php
session_start();
include('../includes/db_config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['add_food'])) {
    $name = $_POST['food_name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    
    $image = $_FILES['image']['name'];
    $target = "../uploads/" . basename($image);

    $sql = "INSERT INTO food_menu (food_name, price, category, image) VALUES ('$name', '$price', '$category', '$image')";
    
    if ($conn->query($sql)) {
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $success = "New item added to the menu successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Menu Item | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; display: flex; margin: 0; }
        /* Sidebar CSS */
        .sidebar { width: 260px; height: 100vh; background: #2c3e50; color: white; padding: 25px; position: fixed; border-right: 3px solid #f39c12; overflow-y: auto; }
        .sidebar h2 { color: #f39c12; font-size: 1.5rem; margin-bottom: 30px; font-family: serif; letter-spacing: 1px;}
        .sidebar a { display: block; color: #ecf0f1; text-decoration: none; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1); transition: 0.3s; font-size: 0.9rem; }
        .sidebar a:hover { color: #f39c12; padding-left: 10px; }
        
        .main-content { margin-left: 300px; padding: 40px; width: calc(100% - 300px); }
        .form-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 600px; margin: auto; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50; }
        input, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .btn-submit { background: #f39c12; color: white; border: none; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: bold; width: 100%; font-size: 1rem; }
        .success-msg { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>
    <?php include('includes/admin_sidebar.php'); ?>

    <div class="main-content">
        <div class="form-card">
            <h2 style="color: #2c3e50; border-bottom: 3px solid #f39c12; display: inline-block;">Add New Menu Item</h2>
            
            <?php if(isset($success)) echo "<div class='success-msg'>$success</div>"; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Food/Drink Name</label>
                    <input type="text" name="food_name" placeholder="e.g. Club Sandwich" required>
                </div>
                <div class="form-group">
                    <label>Price (ETB)</label>
                    <input type="number" step="0.01" name="price" placeholder="0.00" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="Food">Food</option>
                        <option value="Drink">Drink</option>
                        <option value="Dessert">Dessert</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Item Image</label>
                    <input type="file" name="image" accept="image/*" required>
                </div>
                <button type="submit" name="add_food" class="btn-submit">🚀 ADD TO MENU</button>
            </form>
        </div>
    </div>
</body>
</html>