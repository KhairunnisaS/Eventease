<?php
include 'db.php';
session_start();

// Ambil id dari session
$user_id = $_SESSION['id'];

// Ambil data user dari database
$query = "SELECT * FROM users WHERE id = $1";
$result = pg_query_params($conn, $query, array($user_id));
$user = pg_fetch_assoc($result);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Bagian Update Account Information
    if (isset($_POST['update_account_info'])) {
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $phone_number = $_POST['phone_number'] ?? '';
        $address = $_POST['address'] ?? '';
        $city = $_POST['city'] ?? '';

        // Proses upload gambar (jika ada)
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $target_dir = "uploads/";
            $image_name = basename($_FILES['profile_picture']['name']);
            $target_file = $target_dir . $image_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $valid_extensions = array("jpg", "jpeg", "png", "gif");
            if (in_array($imageFileType, $valid_extensions) && move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $query = "UPDATE users SET first_name = $1, last_name = $2, phone_number = $3, address = $4, city = $5, profile_picture = $6 WHERE id = $7";
                $result = pg_query_params($conn, $query, array($first_name, $last_name, $phone_number, $address, $city, $target_file, $user_id));
                $success = $result ? "Account information updated successfully!" : "Error updating account information.";
            }
        } else {
            $query = "UPDATE users SET first_name = $1, last_name = $2, phone_number = $3, address = $4, city = $5 WHERE id = $6";
            $result = pg_query_params($conn, $query, array($first_name, $last_name, $phone_number, $address, $city, $user_id));
            $success = $result ? "Account information updated successfully!" : "Error updating account information.";
        }

        // Refresh data yang baru diisi (query yang benar)
        $query = "SELECT * FROM users WHERE id = $1"; // Query untuk mengambil data pengguna
        $result = pg_query_params($conn, $query, array($user_id)); // Menggunakan parameter yang benar
        $user = pg_fetch_assoc($result);
    }

    // Bagian Change Email
    if (isset($_POST['change_email'])) {
        $new_email = $_POST['new_email'] ?? '';
        $confirm_email = $_POST['confirm_email'] ?? '';

        if ($new_email === $confirm_email) {
            $query = "UPDATE users SET email = $1 WHERE id = $2";
            $result = pg_query_params($conn, $query, array($new_email, $user_id));
            $success = $result ? "Email updated successfully!" : "Error updating email.";
        } else {
            $error = "Emails do not match!";
        }
    }

    // Bagian Change Password
    if (isset($_POST['change_password'])) {
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET password = $1 WHERE id = $2";
            $result = pg_query_params($conn, $query, array($hashed_password, $user_id));
            $success = $result ? "Password updated successfully!" : "Error updating password.";
        } else {
            $error = "Passwords do not match!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eventease - Profile Settings</title>

    <!-- CSS styles -->
    <link rel="stylesheet" type="text/css" href="profilepage.css" />

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Abhaya+Libre:wght@400;500;600;700;800&family=Shrikhand&display=swap" rel="stylesheet"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.2/css/bootstrap.min.css"/>
</head>
<body>
    <!-- Navbar Section -->
    <div class="navbar">
        <div class="logo">.Eventease</div>
        <button class="mobile-menu-btn">
        <i class="fas fa-bars"></i>
        </button>
        <nav>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Events</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
        </ul>

        <div class="icons">
            <a onclick="window.location.href='indeks-create.html';"><i aria-hidden="true"></i>Create Event</a>
            <a href="#"><i class="fas fa-ticket-alt"></i> Tickets</a>
            <a href="#"><i class="fas fa-file-alt"></i> Draft</a>
            <a href="profilepage.php" ><i class="fas fa-user-circle"></i> Profile</a>
            <svg
                viewBox="-5 -5 110 110"
                preserveAspectRatio="none"
                aria-hidden="true"
            >
                <path
                d="M0,0 C0,0 100,0 100,0 C100,0 100,100 100,100 C100,100 0,100 0,100 C0,100 0,0 0,0"
                />
            </svg>
            </button>
        </div>
        </nav>
    </div>

    <?php if ($error): ?>
    <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <div class="container mt-5">
        <div class="sidebar">
            <h2>Profile</h2>
            <ul class="nav nav-tabs" id="profileTabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#account-info" onclick="showTab('account-info')">Account Information</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#change-email" onclick="showTab('change-email')">Change Email</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#change-password" onclick="showTab('change-password')">Change Password</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#history" onclick="showTab('history')">History</a>
                </li>
            </ul>
        </div>
        <div class="content">
            <div id="account-info" class="tab-content active">
                <form method="post" enctype="multipart/form-data">
                    <div class="header">
                        <h2>Account Information</h2>
                    </div>
                    <div class="profile-container">
                        <div class="profile-pic" id="profilePic">
                            <img id="profileImage" src="<?php echo $user['profile_picture'] ?? 'default-profile.png'; ?>" alt="Profile Picture"/>
                        </div>
                        <label class="camera-icon" for="fileInput"><img alt="Camera icon" src="https://storage.googleapis.com/a1aa/image/Z98VHnvG7Tr3EVO5Dih3KReoIRIKlOAHtrDbdWHYIRVqw01JA.jpg"/></label>
                        <input id="fileInput" type="file" name="profile_picture" accept="image/*" onchange="loadFile(event)"/>
                    </div>
                    <div class="form-group">
                        <h3>Profile Information</h3>
                    </div>
                    <div class="form-group">
                        <label for="first-name">First Name:</label>
                        <input id="first-name" placeholder="Enter first name" type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="last-name">Last Name:</label>
                        <input id="last-name" placeholder="Enter last name" type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>"/>
                    </div>
                    <div class="form-group">
                        <h3>Contact Details</h3>
                    </div>
                    <div class="form-group">
                        <label for="phone-number">Phone Number:</label>
                        <input id="phone-number" placeholder="Enter phone number" type="text" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input id="address" placeholder="Enter address" type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="city">City/Town:</label>
                        <input id="city" placeholder="Enter city/town" type="text" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>"/>
                    </div>
                    <div class="save-button">
                        <button onclick="saveChanges()"  name="update_account_info">Save Changes</button>
                    </div>
                </form>
            </div>
            <div id="change-email" class="tab-content" style="display:none;">
                <div class="header">
                    <h2>Change Email</h2>
                </div>
                <div class="form-group">
                    <label class="block text-gray-700 mb-2">Current Email:</label>
                        <p><?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <div class="form-group">
                    <label for="first-name">New Email:</label>
                    <input id="first-name" placeholder="Enter new email" type="text" name="new_email" required/>
                </div>
                <div class="form-group">
                    <label for="last-name">Confirm Email:</label>
                    <input id="last-name" placeholder="Enter email again" type="text" name="confirm_email" required/>
                </div>
                <div class="save-button">
                    <button name="change_email">Save Changes</button>
                </div>
            </div>
            <div id="change-password" class="tab-content" style="display:none;">
                <div class="header">
                    <h2>Change Password</h2>
                </div>
                <div class="form-group">
                    <label for="first-name">Current Password:</label>
                    <input id="first-name" placeholder="Enter current password" type="text" name="current_password" required/>
                </div>
                <div class="form-group">
                    <label for="first-name">New Password:</label>
                    <input id="first-name" placeholder="Enter new password" type="text" name="new_password" required/>
                </div>
                <div class="form-group">
                    <label for="last-name">Confirm Password:</label>
                    <input id="last-name" placeholder="Enter password again" type="text" name="confirm_password" required/>
                </div>
                <div class="save-button">
                    <button onclick="saveChanges()" name="change_password">Save Changes</button>
                </div>
            </div>
            <div id="history" class="tab-content" style="display:none;">
                <div class="header">
                    <h2>Order & Sales History</h2>
                </div>
                <div class="order-item">
                    <img alt="Placeholder image for Buy Ticket" height="60" src="https://storage.googleapis.com/a1aa/image/ojfzYpTO0axoW6NofSawTrwZJ91cQ3kNY1ciEs3gevfEqvuOB.jpg" width="60"/>
                    <div class="order-details">
                        <h3>Buy Ticket Name</h3>
                        <p>Day and Date</p>
                    </div>
                    <div class="order-price">
                        <h3>Ticket Price</h3>
                        <p>Virtual Account</p>
                    </div>
                </div>
                <div class="order-item">
                    <img alt="Placeholder image for Buy Ticket" height="60" src="https://storage.googleapis.com/a1aa/image/ojfzYpTO0axoW6NofSawTrwZJ91cQ3kNY1ciEs3gevfEqvuOB.jpg" width="60"/>
                    <div class="order-details">
                        <h3>Sales Ticket Name</h3>
                        <p>Day and Date</p>
                    </div>
                    <div class="order-price">
                        <h3>Ticket Price</h3>
                        <p>Virtual Account</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <script>
        function loadFile(event) {
        var image = document.getElementById('profileImage');
        image.src = URL.createObjectURL(event.target.files[0]);
        }
        function showTab(tabId) {
            // Hide all tabs
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => tab.style.display = 'none');
            
            // Remove 'active' class from all nav links
            const links = document.querySelectorAll('.nav-link');
            links.forEach(link => link.classList.remove('active'));

            // Show selected tab and add 'active' class to clicked link
            document.getElementById(tabId).style.display = 'block';
            document.querySelector(`[href="#${tabId}"]`).classList.add('active');
        }

        // Show first tab by default
        document.addEventListener("DOMContentLoaded", function() {
            showTab('account-info');
        });
    </script>
</body>
</html>