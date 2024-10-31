<?php
include 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['password']; // Ambil password baru dari form

    // Update password di database
    $query = "UPDATE users SET password = $1 WHERE email = $2";
    $result = pg_query_params($conn, $query, array(password_hash($new_password, PASSWORD_DEFAULT), $email));

    if ($result) {
        // Pesan sukses
        $message = "Your password has been successfully reset!";
    } else {
        // Pesan kesalahan
        $message = "An error occurred while resetting the password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventEase - Reset Password</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="left-side">
            <img src="images/logo.png" alt="EventEase Logo">
            <h1>.Eventease:</h1>
            <h2>Your</h2>
            <h2>Gateway to</h2>
            <h2>the Hottest</h2>
            <h2>Events!</h2>
            <p>Find it, book it, live it. One<br>click, all vibes.</p>
        </div>
        <div class="right-side">
            <h2>Reset Password</h2>    
            <form action="reset.php" method="POST">
                <div class="input" style="margin-left: 22px;">
                    <div class="input-group">
                        <label for="email">E-mail Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your e-mail" style="width: 88%;" required>
                    </div>
                    <div class="input-group">
                        <label for="password">New Password</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" class="input-password" placeholder="Enter new password" style="width: 88%;" required>
                            <span class="toggle-password">
                                <i id="eye-icon" class="fas fa-eye" style="margin-right: 58px"></i>
                            </span>
                        </div>
                    </div>                    
                </div>
                <div class="buttons" style="gap: 70px; margin-top: 25px;">
                    <button type="button" class="back-button" onclick="window.location.href='login.php';">Back</button>
                    <button type="submit" class="login-button" style="width: 115px;">Reset</button>
                </div>
                <p class="login-account">Don't have an account? <a href="register.html">Create Account</a></p>
            </form>
        </div>
    </div>
    <script>
        window.onload = function() {
            <?php if ($message): ?>
                alert("<?= $message ?>");
                window.location.href = "login.php"; // Redirect ke halaman login setelah 2 detik
            <?php endif; ?>
        };
    </script>
    <script src="main.js"></script>
</body>
</html>
