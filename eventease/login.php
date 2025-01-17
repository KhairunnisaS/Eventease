<?php
include 'db.php'; 
session_start();

$email = "";
$password = "";
$email_error = "";
$password_error = "";

// Jika form dikirim (POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form menggunakan $_POST
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validasi input email dan password
    if (empty($email)) {
        $email_error = "Email is required!";
    }

    if (empty($password)) {
        $password_error = "Password is required!";
    }

    // Jika tidak ada error, lakukan pengecekan di database
    if (empty($email_error) && empty($password_error)) {
        $query = "SELECT * FROM users WHERE email = $1";
        $result = pg_query_params($conn, $query, array($email));

        if ($result && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                // Login berhasil, simpan user_id ke dalam session
                $_SESSION['id'] = $row['id'];
                header("Location: index-2.html");
                // Redirect ke halaman utama atau dashboard (uncomment jika ingin redirect)
                // header("Location: dashboard.php");
                exit();
            } else {
                // Password salah
                $password_error = "Incorrect password!";
            }
        } else {
            // User tidak ditemukan
            $email_error = "User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventEase - Sign In</title>
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
            <h2>Sign In</h2>
            <form method="POST">
                <div class="input" style="margin-left: 22px;">
                    <div class="input-group">
                        <label for="email">E-mail Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your e-mail" style="width: 88%;" value="<?= htmlspecialchars($email) ?>">
                        <!-- Tampilkan pesan error di bawah input email -->
                        <?php if (!empty($email_error)): ?>
                            <div class="message" style="color: #721c24;"><?= htmlspecialchars($email_error) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group">
                        <div class="label-container">
                            <label for="password">Password</label>
                            <a href="reset.php" class="forgot-password">Forgot Password?</a>
                        </div>
                        <div class="password-container">
                            <input type="password" id="password" class="input-password" placeholder="Enter password" name="password" style="width: 88%;">
                            <span class="toggle-password">
                                <i id="eye-icon" class="fas fa-eye" style="margin-right: 58px"></i>
                            </span>
                        </div>
                        <!-- Tampilkan pesan error di bawah input password -->
                        <?php if (!empty($password_error)): ?>
                            <div class="message" style="color: #721c24;"><?= htmlspecialchars($password_error) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="buttons" style="gap: 70px; margin-top: 25px;">
                    <button type="button" class="back-button">Back</button>
                    <button type="submit" class="login-button" style="width: 115px;">Sign In</button>
                </div>
                <p class="login-account">Don't have an account? <a href="register.php">Create Account</a></p>
            </form>
        </div>
    </div>
</body>
</html>
