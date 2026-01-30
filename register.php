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
        $toastClass = "#ffc107";
    } else {
        $stmt = $conn->prepare("INSERT INTO user1 (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            $message = "Account created successfully";
            $toastClass = "#198754";
        } else {
            $message = "Error: " . $stmt->error;
            $toastClass = "#dc3545";
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
            min-height: 100vh;
            background: linear-gradient(rgba(143, 0, 0, 0.75), rgba(143, 0, 0, 0.75)),
                        url('uph-campus.jpg') center/cover no-repeat;
            font-family: 'Segoe UI', sans-serif;
        }

        .register-card {
            background: #7b0d0d;
            border-radius: 30px;
            width: 380px;
            padding: 30px 35px;
            color: #fff;
            box-shadow: rgba(0,0,0,0.4) 0 10px 30px;
            border: none;
        }

        .register-card img {
            width: 380px;
            display: block;
            margin: 0 auto 20px;
        }

        /* INPUT DESIGN */
        .register-card input.form-control {
            padding: 10px 15px;
            border-radius: 12px;
            border: none;
            background-color: #f2f2f2;
            font-size: 14px;
        }

        .register-card input.form-control:focus {
            outline: none;
            background-color: #ffffff;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.6);
        }

        .register-card label {
            color: #fff;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .btn-theme {
            background-color: #661414 !important;
            border-color: #661414 !important;
            color: #ffffff !important;
            border-radius: 12px;
        }

        .btn-theme:hover {
            opacity: 0.9;
        }

        .link-text {
            color: #ffdede;
            font-weight: 700;
            text-decoration: none;
        }

        .link-text:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="container d-flex align-items-center justify-content-center min-vh-100">

    <?php if ($message): ?>
        <div class="alert text-center mb-3" style="background-color: <?php echo $toastClass; ?>; color: #fff;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="form-control register-card text-center">

        <!-- UPH Logo -->
        <img src="uph-logo.png" alt="UPH Logo">

        <h5 class="mb-4 ">Create Your Account</h5>

        <div class="mb-3 text-start">
            <label><i class="fa fa-user"></i> User Name</label>
            <input type="text" name="username" class="form-control" placeholder="Choose a username" required>
        </div>

        <div class="mb-3 text-start">
            <label><i class="fa fa-envelope"></i> Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>

        <div class="mb-3 text-start">
            <label><i class="fa fa-lock"></i> Password</label>
            <input type="password" name="password" class="form-control" placeholder="Create a password" required>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-theme w-100 p-2 fw-bold">
                Create Account
            </button>
        </div>

        <div class="mt-4">
            <p class="fw-medium mb-0">
                I have an account?
                <a href="./login.php" class="link-text">Login</a>
            </p>
        </div>

    </form>
</div>

</body>
</html>
