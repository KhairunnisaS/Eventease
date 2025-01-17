<?php 
// File ini harus diawali dengan PHP untuk melakukan koneksi database atau memproses data jika ada
include 'db.php'; // Koneksi ke database

// Inisialisasi pesan untuk email dan password
$emailMessage = '';
$passwordMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validasi format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailMessage = "Invalid email!";
    } 
    // Validasi panjang password
    elseif (strlen($password) < 8) {
        $passwordMessage = "Password must be at least 8 characters!";
    } 
    else {
        // Cek apakah email sudah ada
        $query = "SELECT * FROM users WHERE email = $1";
        $result = pg_query_params($conn, $query, array($email));

        if (pg_num_rows($result) > 0) {
            $emailMessage = "Email is already registered!";
        } else {
            // Masukkan user baru
            $query = "INSERT INTO users (fullname, email, password) VALUES ($1, $2, $3)";
            $result = pg_query_params($conn, $query, array($username, $email, password_hash($password, PASSWORD_DEFAULT)));

            if ($result) {
                echo "<script>alert('Registration successful!'); window.location.href='login.html';</script>";
                exit();
            } else {
                echo "An error occurred: " . pg_last_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventEase - Sign Up</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
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
            <h2>Sign Up</h2>
            <div class="button-container">
                <a href="#" class="google-button">
                    <img src="images/google.png" alt="Google Logo">
                    Sign up with Google
                </a>
                <a href="#" class="facebook-button">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook Logo">
                    Sign up with Facebook
                </a>
            </div>
            <div class="line-or">
                <hr class="left-line">
                <span>OR</span>
                <hr class="right-line">
            </div>                       
            <form action="" method="POST">
                <div class="input">
                    <div class="input-group">
                        <label for="fullname">Full Name</label>
                        <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required>
                    </div>
                    <div class="input-group">
                        <label for="email">E-mail Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your e-mail" required>
                        <div id="emailMessage" class="message" style="color: #721c24; width: 91.5%;"><?= $emailMessage ?></div>
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" class="input-password" placeholder="Enter password" required>
                            <span class="toggle-password">
                                <i id="eye-icon" class="fas fa-eye"></i>
                            </span>
                        </div>
                        <div id="passwordMessage" class="message"  style="color: #721c24; width: 91.5%;"><?= $passwordMessage ?></div>
                    </div>                    
                </div>
                <div class="buttons">
                    <button type="submit" class="create-button">Sign Up</button>
                </div>
                <p class="create-account">Already have an account? <a href="login.php">Sign In</a></p>
            </form>
        </div>
    </div>
    <script src="main.js"></script>
</body>
</html>
