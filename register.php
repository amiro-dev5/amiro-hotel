<?php
include('includes/db_config.php');

$error = "";
$success = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        $error = "This email is already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $full_name, $email, $password);

        if ($stmt->execute()) {
        
            $success = "Registration Successful! You can now login.";
        } else {
            $error = "Registration failed! Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Elite Hotel System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* ከጀርባ ያለው ምስል (አንተ ያልከው ውብ ምስል) */
        body {
            background: url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    
    <div class="form-container">
        <h2>Create Account</h2>
        <p style="margin-bottom: 20px; font-size: 0.9em; opacity: 0.85;">Experience Luxury at Its Best</p>

        <?php if($error): ?>
            <div style="background: rgba(255, 77, 77, 0.2); color: #ff4d4d; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 0.9em; border: 1px solid #ff4d4d;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div style="background: rgba(46, 204, 113, 0.2); color: #2ecc71; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 0.9em; border: 1px solid #2ecc71;">
                <?php echo $success; ?>
                <br><a href="login.php" style="color: white; text-decoration: underline; font-weight: bold;">Click here to Login</a>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="input-group">
                <input type="text" name="full_name" placeholder="Full Name" required>
            </div>
            <div class="input-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn">Register Now</button>
        </form>

        <div class="link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>