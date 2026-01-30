<?php
include 'config.php';

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $checkEmailStmt = $conn->prepare("SELECT email FROM user1 WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        $message = "Email ID already exists";
        $toastClass = "#ffc107"; // Warning yellow
    } else {
        $stmt = $conn->prepare("INSERT INTO user1 (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            $message = "Account created successfully";
            $toastClass = "#198754"; // Success green
        } else {
            $message = "Error: " . $stmt->error;
            $toastClass = "#dc3545"; // Danger red
        }
        $stmt->close();
    }

    $checkEmailStmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Registration</title>
    <style>
                body {
            background-color: #661414 !important;
        }
        .register-card {
            background-color: #661414f;

            width: 380px; 
            box-shadow: rgba(0, 0, 0, 0.4) 0px 10px 25px;
            border-radius: 12px;
            border: none;
        }
        .register-card img {
        width: 380px;
        display: block;
        margin: 0 auto 10px;
        }

        .btn-theme {
            background-color: #661414 !important;
            border-color: #661414 !important;
            color: #ffffff !important;
            transition: opacity 0.3s;
        }
        .btn-theme:hover {
            opacity: 0.9;
            color: #ffffff;
        }
        .link-text {
            color: #661414;
            font-weight: 700;
            text-decoration: Glacial Indifference;
        }
        .link-text:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
   <div class="container d-flex flex-column align-items-center min-vh-100 justify-content-center">

    <?php if ($message): ?>
        <div class="alert text-center mb-3" style="background-color: <?php echo $toastClass; ?>; color: #fff;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="form-control p-4 register-card text-center">

        <!-- UPH Logo -->
        <img src="uph-logo.png" alt="UPH Logo">

        <h5 class="mb-4" style="font-weight: 700;">Create Your Account</h5>

        <div class="mb-3 text-start">
            <label class="form-label"><i class="fa fa-user"></i> User Name</label>
            <input type="text" name="username" class="form-control" placeholder="Choose a username" required>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label"><i class="fa fa-envelope"></i> Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label"><i class="fa fa-lock"></i> Password</label>
            <input type="password" name="password" class="form-control" placeholder="Create a password" required>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-theme w-100 p-2 fw-bold">
                Create Account
            </button>
        </div>

        <div class="mt-4">
            <p class="fw-medium">
                I have an account?
                <a href="./login.php" class="link-text">Login</a>
            </p>
        </div>

    </form>
</div>

</body>
</html>