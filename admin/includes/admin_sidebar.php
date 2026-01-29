<button class="mobile-nav-toggle" onclick="toggleSidebar()">☰</button>

<div class="sidebar" id="adminSidebar">
    <h2>Elite Admin</h2>
    <p style="font-size: 0.8em; opacity: 0.7; margin-bottom: 20px;">
        Logged in as: <strong><?php echo $_SESSION['user_name'] ?? 'Admin'; ?></strong>
    </p>
    <hr style="opacity: 0.1; margin-bottom: 20px;">
    <nav>
        <a href="admin_dashboard.php">📊 Dashboard Overview</a>
        <a href="manage_rooms.php">🛏️ Manage Rooms</a>
        <a href="manage_bookings.php">📅 Manage Bookings</a>
        
        <a href="add_menu.php">➕ Add Food/Drink</a>
        <a href="manage_menu.php">🍔 Manage Menu List</a>
        <a href="manage_food_orders.php">🛎️ Food Orders Status</a>
        <a href="manage_messages.php">💬 Guest Messages</a>
        
        <a href="manage_users.php">👥 Manage Users</a>
        <a href="../index.php">🌐 View Website</a>
        <a href="../logout.php" style="color: #e74c3c !important; margin-top: 30px; font-weight: bold;">🚪 Logout</a>
    </nav>
</div>

<script>
function toggleSidebar() {
    document.getElementById('adminSidebar').classList.toggle('active');
}
</script>

<style>
/* Sidebar Base Styles */
.sidebar { 
    width: 260px; 
    height: 100vh; 
    background: #2c3e50; 
    color: white; 
    padding: 25px; 
    position: fixed; 
    left: 0;
    top: 0;
    border-right: 3px solid #f39c12; 
    overflow-y: auto; 
    transition: 0.3s ease-in-out;
    z-index: 1000;
}

.mobile-nav-toggle {
    display: none;
    position: fixed;
    top: 15px;
    right: 15px;
    background: #2c3e50;
    color: #f39c12;
    border: 1px solid #f39c12;
    padding: 8px 12px;
    border-radius: 5px;
    font-size: 1.2rem;
    cursor: pointer;
    z-index: 1100;
}

/* Responsive Sidebar for Mobile */
@media (max-width: 768px) {
    .sidebar {
        left: -100%; /* Hide sidebar */
    }
    .sidebar.active {
        left: 0; /* Show sidebar on toggle */
    }
    .mobile-nav-toggle {
        display: block;
    }
}
</style>