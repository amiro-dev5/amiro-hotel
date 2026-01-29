<?php
session_start();
include('../includes/db_config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['change_role'])) {
    $user_id = $_GET['change_role'];
    $new_role = $_GET['new_role'];
    if ($user_id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $user_id);
        $stmt->execute();
        header("Location: manage_users.php");
        exit();
    }
}

if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];
    if ($user_id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        header("Location: manage_users.php");
        exit();
    }
}

$users = $conn->query("SELECT id, full_name, email, role FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Elite Hotel Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 260px; height: 100vh; background: #2c3e50; color: white; padding: 25px; position: fixed; border-right: 3px solid #f39c12; overflow-y: auto; }
        .sidebar h2 { color: #f39c12; font-size: 1.5rem; margin-bottom: 30px; font-family: serif; letter-spacing: 1px; }
        .sidebar a { display: block; color: #ecf0f1; text-decoration: none; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1); transition: 0.3s; font-size: 0.9rem; }
        .sidebar a:hover { color: #f39c12; padding-left: 10px; }
        
        .main-content { margin-left: 300px; padding: 40px; width: calc(100% - 300px); }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h3 { color: #2c3e50; border-bottom: 2px solid #f39c12; display: inline-block; padding-bottom: 5px; margin-bottom: 25px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; background: #fdfdfd; padding: 15px; color: #888; font-size: 0.8rem; text-transform: uppercase; border-bottom: 2px solid #eee; }
        td { padding: 15px; border-bottom: 1px solid #eee; font-size: 0.9rem; vertical-align: middle; }
        .role-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; }
        .role-admin { background: #e8f4fd; color: #2980b9; border: 1px solid #d0e7f9; }
        .role-user { background: #fef9e7; color: #f39c12; border: 1px solid #fcf3cf; }
        .btn-action { text-decoration: none; font-size: 0.85rem; font-weight: 600; margin-right: 15px; }
        .btn-change { color: #27ae60; }
        .btn-delete { color: #e74c3c; }
    </style>
</head>
<body>
    <?php include('includes/admin_sidebar.php'); ?>

    <div class="main-content">
        <div class="card">
            <h3>System User Management</h3>
            <table>
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo $user['full_name']; ?></strong></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>
                            <span class="role-badge <?php echo ($user['role'] == 'admin') ? 'role-admin' : 'role-user'; ?>">
                                ● <?php echo ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="?change_role=<?php echo $user['id']; ?>&new_role=<?php echo ($user['role'] == 'user') ? 'admin' : 'user'; ?>" class="btn-action btn-change">
                                    Change Role
                                </a>
                                <a href="?delete_user=<?php echo $user['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Delete user?')">Delete</a>
                            <?php else: ?>
                                <span style="color: #95a5a6; font-style: italic;">(You)</span>
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