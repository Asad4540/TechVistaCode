<?php
session_start();

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "techvistacode";

// Check if the admin is already logged in, if yes redirect to viewdata.php
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: viewdata.php");
    exit;
}

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check for form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verify the user exists and the plain text password matches
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Direct password comparison (since the password is stored as plain text)
        if ($password === $user['password']) {
            // Set session variables
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['username'] = $username;

            // Redirect to viewdata.php
            header("Location: viewdata.php");
            exit;
        } else {
            $login_error = "Invalid username or password";
        }
    } else {
        $login_error = "Invalid username or password";
    }

    $stmt->close();
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TVC Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="../images/tvc-favicon.webp">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <a href="https://techvistacode.com"><img src="../images/tvc-logo.webp" style="width: 90%;" alt=""></a>
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