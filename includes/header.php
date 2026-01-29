<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #2c3e50; --gold: #f39c12; --light: #f4f7f6; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        header { background: var(--primary); padding: 15px 5%; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1000; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .logo { color: var(--gold); font-size: 1.6rem; font-weight: bold; text-decoration: none; font-family: serif; }
        
        .nav-links { display: flex; list-style: none; gap: 25px; align-items: center; }
        .nav-links a { color: white; text-decoration: none; font-size: 0.9rem; transition: 0.3s; font-weight: 400; }
        .nav-links a:hover { color: var(--gold); }
        
        .btn-logout { background: transparent; border: 1px solid var(--gold); color: var(--gold) !important; padding: 6px 15px; border-radius: 20px; }
        .btn-logout:hover { background: var(--gold); color: white !important; }

        /* Floating Admin Button Style */
        .admin-float-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--gold);
            color: var(--primary);
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            z-index: 2000;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid white;
        }
        .admin-float-btn:hover {
            transform: scale(1.1) rotate(90deg);
            background: white;
            color: var(--gold);
        }

        /* Mobile Menu */
        .menu-toggle { display: none; color: white; font-size: 1.5rem; cursor: pointer; }

        @media (max-width: 768px) {
            .nav-links { display: none; flex-direction: column; position: absolute; top: 100%; left: 0; width: 100%; background: var(--primary); padding: 20px; gap: 15px; text-align: center; }
            .nav-links.active { display: flex; }
            .menu-toggle { display: block; }
            .admin-float-btn { bottom: 20px; right: 20px; width: 45px; height: 45px; }
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php" class="logo">Elite Hotel</a>
        <div class="menu-toggle" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
        <ul class="nav-links" id="navLinks">
            <li><a href="index.php">Home</a></li>
            <li><a href="rooms.php">Rooms</a></li>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="order_food.php">Order Food</a></li>
                <li><a href="contact_us.php">Contact Us</a></li>
                <li><a href="my_bookings.php">My Bookings</a></li>
                <li><a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php" style="color: var(--gold);">Register</a></li>
            <?php endif; ?>
        </ul>
    </header>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="admin/admin_dashboard.php" class="admin-float-btn" title="Admin Dashboard">
            <i class="fas fa-user-cog" style="font-size: 1.4rem;"></i>
        </a>
    <?php endif; ?>

    <script>
        function toggleMenu() {
            document.getElementById('navLinks').classList.toggle('active');
        }
    </script>
