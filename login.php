<?php
session_start();
include 'config.php';

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM user1 WHERE email = ?");

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_password);
        $stmt->fetch();

        if ($password === $db_password) {
            $_SESSION['email'] = $email;
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Incorrect password";
            $toastClass = "bg-danger";
        }
    } else {
        $message = "Email not found";
        $toastClass = "bg-warning";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>UPH Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<style>
    body {
        min-height: 100vh;
        background: linear-gradient(rgba(143, 0, 0, 0.75), rgba(143, 0, 0, 0.75)),
                    url('uph-campus.jpg') center/cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', sans-serif;
    }

    .login-card {
        background: #7b0d0d;
        border-radius: 30px;
        width: 380px;
        padding: 40px;
        color: #fff;
        box-shadow: rgba(0,0,0,.4) 0 10px 30px );
    }

    .login-card img {
        width: 350px;
        display: block;
        margin: 0 auto 10px;
    }

    .login-card h4 {
        text-align: center;
        font-weight: 700;
        color: #ffc107;
    }

    .login-card p {
        text-align: center;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .input-group {
        background: #f5eaea;
        border-radius: 30px;
        padding: 10px 15px;
        margin-bottom: 15px;
        align-items: center;
    }

    .input-group i {
        color: #7b0d0d;
    }

    .input-group input {
        border: none;
        outline: none;
        background: transparent;
        width: 80%;
        padding-left: 20px;

    }

    .forgot {
        text-align: right;
        font-size: 13px;
        margin-bottom: 15px;
    }

    .forgot a {
        color: #ffdede;
        text-decoration: none;
    }

    .btn-login {
        background: #f5eaea;
        color: #7b0d0d;
        border-radius: 30px;
        font-weight: 700;
        width: 100%;
        border: none;
        padding: 10px;
    }

    .register {
        text-align: center;
        margin-top: 15px;
        font-size: 14px;
    }

    .register a {
        color: #ffc107;
        text-decoration: none;
        font-weight: 600;
    }
</style>
</head>

<body>

<?php if ($message): ?>
<div class="toast position-fixed top-0 end-0 m-3 text-white <?php echo $toastClass; ?>" role="alert">
    <div class="d-flex">
        <div class="toast-body"><?php echo $message; ?></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
</div>
<?php endif; ?>

<div class="login-card">
    <!-- logo -->
    <img src="uph-logo.png" alt="UPH Logo">

        <p>Welcome!</p>


    <form method="POST">
        <div class="input-group">
            <i class="fa fa-user"></i>
            <input type="text" name="email" placeholder="username / email" required>
        </div>

        <div class="input-group">
            <i class="fa fa-lock"></i>
            <input type="password" name="password" placeholder="password" required>
        </div>

        <div class="forgot">
            <a href="resetpassword.php">forgot password?</a>
        </div>

        <button class="btn-login" type="submit">LOGIN</button>

        <div class="register">
            Don’t have an account? <a href="register.php">Register now</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.toast').forEach(toastEl => {
        new bootstrap.Toast(toastEl, { delay: 3000 }).show();
    });
</script>

</body>
</html>
