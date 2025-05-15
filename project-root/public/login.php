<?php
session_start();
require_once('../includes/db.php');

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect based on role
    header("Location: " . ($_SESSION['role'] === 'counselor' ? '../admindashboard/adminstatistics.php' : '../studentdashboard/studentAppointment.php'));
    exit();
} 
// Check for remember me cookies
elseif (!empty($_COOKIE['user_email']) && !empty($_COOKIE['user_password'])) {
    $pdo = getDb();
    // Modified query to include role
    $sql = "SELECT id, email, password, role FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $_COOKIE['user_email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($_COOKIE['user_password'], $user['password'])) {
        // Set session variables including role
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        header("Location: " . ($user['role'] === 'counselor' ? '../admindashboard/adminstatistics.php' : '../studentdashboard/studentAppointment.php'));
        exit();
    } else {
        // Clear invalid cookies
        setcookie('user_email', '', time() - 3600, "/");
        setcookie('user_password', '', time() - 3600, "/");
        unset($_COOKIE['user_email']);
        unset($_COOKIE['user_password']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SafeSupport</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body class="bg-light">
    <div class="loading-spinner">
        <div class="spinner-border" role="status" style="color: #0b6043 !important;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow-sm" style="width: 700px; border-color: #cecece;">
            <div class="row g-0">
                <div class="col-md-6">
                    <div class="card-body p-3">
                        <a href="index.php" class="text-decoration-none position-absolute" style="left: 15px; top: 15px;">
                            <i class="fas fa-arrow-left" style="color: #0b6043; font-size: 20px;"></i>
                        </a>
                        <h2 class="text-center mb-3" style="color: #0b6043; font-size: 1.5rem;">Login to SafeSupport</h2>
                        
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger py-2 mb-2 small">
                                <?php 
                                echo $_SESSION['error'];
                                unset($_SESSION['error']);
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="process_login.php" method="POST">
                            <div class="mb-2">
                                <label for="email" class="form-label small mb-1" style="color: #0b6043;">Email</label>
                                <input type="email" class="form-control form-control-sm border-2" id="email" name="email" required 
                                       style="border-color: #0b6043;">
                            </div>
                            <div class="mb-2">
                                <label for="password" class="form-label small mb-1" style="color: #0b6043;">Password</label>
                                <input type="password" class="form-control form-control-sm border-2" id="password" name="password" required
                                       style="border-color: #0b6043;">
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember"
                                           style="border-color: #0b6043; background-color: #ffffff !important;"
                                           onchange="this.style.backgroundColor = this.checked ? '#0b6043' : '#ffffff'">
                                    <label class="form-check-label small" for="remember">Remember me</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm w-100 text-white" 
                                    style="background-color: #0b6043; border: none; transition: transform 0.2s;"
                                    onmouseover="this.style.transform='scale(1.005)'; this.style.backgroundColor='#094d36'" 
                                    onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#0b6043'">Login</button>
                        </form>
                        
                        <div class="text-center mt-2">
                            <small>
                                <a href="register.php" class="text-decoration-none d-block mb-1" 
                                   style="transition: transform 0.2s;"
                                   onmouseover="this.style.transform='scale(1.05)'" 
                                   onmouseout="this.style.transform='scale(1)'">
                                   <span style="color: #333333;">Don't have an account?</span>
                                   <span style="color: #0b6043;">Register</span>
                                </a>
                                <a href="forgot_password.php" class="text-decoration-none" style="color: #0b6043;">
                                    <span style="display: inline-block; transition: transform 0.2s;"
                                          onmouseover="this.style.transform='scale(1.05)'" 
                                          onmouseout="this.style.transform='scale(1)'">Forgot Password?</span>
                                </a>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-center">
                    <img src="../images/login.png" alt="Login Image" class="img-fluid p-3" style="max-height: 300px; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/login.js"></script>
</body>
</html>