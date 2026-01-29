<?php 
include('includes/db_config.php');
include('includes/header.php'); 

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$order_sent = false;
if (isset($_POST['place_order'])) {
    $user_id = $_SESSION['user_id'];
    $room = $_POST['room_number'];
    $food_id = $_POST['food_id'];
    $qty = $_POST['quantity'];

    $sql = "INSERT INTO food_orders (user_id, room_number, food_id, quantity, status) 
            VALUES ('$user_id', '$room', '$food_id', '$qty', 'Pending')";
    if ($conn->query($sql)) {
        $order_sent = true;
    }
}

$menu_items = $conn->query("SELECT * FROM food_menu ORDER BY category ASC");
?>

<style>
    :root { --gold: #f39c12; --dark: #2c3e50; }

    .main-wrapper { min-height: 80vh; background: #fdfdfd; padding-bottom: 50px; }

    .food-banner { 
        display: grid; grid-template-columns: repeat(4, 1fr); height: 200px; overflow: hidden; 
        border-bottom: 4px solid var(--gold);
    }
    .banner-img { width: 100%; height: 100%; object-fit: cover; filter: brightness(0.8); transition: 0.5s; }
    .banner-img:hover { filter: brightness(1.1); transform: scale(1.05); }

    .container { padding: 40px 10%; }
    .welcome-text { text-align: center; margin-bottom: 50px; }
    .welcome-text h1 { font-family: 'Playfair Display', serif; font-size: 2.5rem; color: var(--dark); }
    
    .menu-section { margin-top: 30px; }
    .category-title { 
        border-left: 5px solid var(--gold); padding-left: 15px; margin: 40px 0 20px; 
        color: var(--dark); font-size: 1.5rem; text-transform: uppercase;
    }

    .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
    .menu-item { 
        background: white; border-radius: 12px; display: flex; align-items: center; 
        padding: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s;
    }
    .menu-item:hover { transform: scale(1.02); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .item-thumb { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-right: 15px; }
    .item-info { flex-grow: 1; }
    .item-info h4 { margin: 0; color: #333; font-size: 1.1rem; }
    .item-info p { margin: 5px 0; color: var(--gold); font-weight: bold; }

    /* Order Form Styling */
    .order-form { display: flex; gap: 5px; align-items: center; margin-top: 10px; }
    .order-form input { padding: 5px; border: 1px solid #ddd; border-radius: 4px; width: 60px; }
    .btn-small-order { 
        background: var(--dark); color: var(--gold); border: 1px solid var(--gold); 
        padding: 5px 12px; cursor: pointer; border-radius: 4px; font-size: 0.8rem; font-weight: bold;
    }
    .btn-small-order:hover { background: var(--gold); color: white; }

    /* Success Message */
    .alert-success { 
        background: #d4edda; color: #155724; padding: 20px; border-radius: 8px; 
        text-align: center; margin-bottom: 30px; border: 1px solid #c3e6cb;
    }
</style>

<div class="main-wrapper">
    <div class="food-banner">
        <img src="https://images.pexels.com/photos/376464/pexels-photo-376464.jpeg?auto=compress&cs=tinysrgb&w=600" class="banner-img" alt="Pancakes">
        <img src="https://images.pexels.com/photos/1199957/pexels-photo-1199957.jpeg?auto=compress&cs=tinysrgb&w=600" class="banner-img" alt="Burger">
        <img src="https://images.pexels.com/photos/1089930/pexels-photo-1089930.jpeg?auto=compress&cs=tinysrgb&w=600" class="banner-img" alt="Drink">
        <img src="https://images.pexels.com/photos/291528/pexels-photo-291528.jpeg?auto=compress&cs=tinysrgb&w=600" class="banner-img" alt="Cake">
    </div>

    <div class="container">
        <div class="welcome-text">
            <h1>In-Room Dining Menu</h1>
            <p>Savor world-class flavors delivered straight to your door. Experience luxury in every bite.</p>
        </div>

        <?php if($order_sent): ?>
            <div class="alert-success">
                <h3>🛎️ Your order has been placed successfully!</h3>
                <p>Please relax while our chefs prepare your meal. Our staff will be at your door in a moment.</p>
            </div>
        <?php endif; ?>

        <div class="menu-section">
            <?php 
            $current_cat = "";
            while($item = $menu_items->fetch_assoc()): 
                if($current_cat != $item['category']): 
                    $current_cat = $item['category'];
                    echo "<h3 class='category-title'>$current_cat Selection</h3>";
                    echo "<div class='menu-grid'>";
                endif;
            ?>
                <div class="menu-item">
                    <img src="uploads/<?php echo $item['image']; ?>" class="item-thumb" alt="Food">
                    <div class="item-info">
                        <h4><?php echo $item['food_name']; ?></h4>
                        <p><?php echo number_format($item['price'], 2); ?> ETB</p>
                        
                        <form method="POST" class="order-form">
                            <input type="hidden" name="food_id" value="<?php echo $item['id']; ?>">
                            <input type="text" name="room_number" placeholder="Room" required>
                            <input type="number" name="quantity" value="1" min="1">
                            <button type="submit" name="place_order" class="btn-small-order">ORDER</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
            </div> </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>