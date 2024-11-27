<?php
session_start();

// Check if the admin is already logged in, if yes redirect to viewdata.php
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: viewdata.php");
    exit;
}

// Hardcoded username and password for the demo (you can replace these with database-stored values)
$admin_username = 'tvcadmin';
$admin_password_hash = password_hash('Tvc@Admin123', PASSWORD_DEFAULT); // Use password_hash() for storing passwords securely

// Check for form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Verify the username and password
    if ($username === $admin_username && password_verify($password, $admin_password_hash)) {
        // Login successful, create session
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['username'] = $username;

        // Redirect to the protected page (viewdata.php)
        header("Location: viewdata.php");
        exit;
    } else {
        $login_error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TVC Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="../images/tvc-favicon.png">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <a href="https://techvistacode.com"><img src="../images/tvc-logo.png" style="width: 90%;"  alt=""></a>
            <br><br>
            <?php if (isset($login_error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($login_error); ?></p>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="textbox">
                    <input type="text" placeholder="Username" name="username" required>
                </div>
                <div class="textbox">
                    <input type="password" placeholder="Password" name="password" required>
                </div>
                <div class="checkbox">
                    <input type="checkbox" id="remember-me">
                    <label for="remember-me">Remember Me</label>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>
        </div>
    </div>
</body>
</html>
