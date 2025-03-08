<?php
// Start the session
session_start();

// Include database connection (replace with your actual path)
include('../config/config.php');

// Initialize error variables
$emailErr = $passwordErr = $loginErr = '';

// Check if the user is already logged in via session or cookies
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/indexdashboard.php");
    exit();
} elseif (!empty($_COOKIE['user_email']) && !empty($_COOKIE['user_password'])) {
    // Debugging: Check if cookies are actually received
    error_log("Cookies detected: " . $_COOKIE['user_email']);

    $pdo = getDb();
    $sql = "SELECT id, email, password FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $_COOKIE['user_email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($_COOKIE['user_password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];

        header("Location: ../dashboard/indexdashboard.php");
        exit();
    } else {
        // If cookies are invalid, delete them and redirect to login
        setcookie('user_email', '', time() - 3600, "/");
        setcookie('user_password', '', time() - 3600, "/");
        unset($_COOKIE['user_email']);
        unset($_COOKIE['user_password']);

        error_log("Invalid cookies deleted. Redirecting to login.");
        header("Location: login.php");
        exit();
    }
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input and validate
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    // Basic validation
    if (empty($email)) {
        $emailErr = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }

    if (empty($password)) {
        $passwordErr = "Password is required";
    }

    // If no errors, proceed with checking credentials
    if (empty($emailErr) && empty($passwordErr)) {
        try {
            // Get PDO connection
            $pdo = getDb(); // Get the PDO connection from config.php

            // First, check for the user in the 'users' table (for students)
            $sql = "SELECT id, email, password FROM users WHERE email = :email LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // If not found in users, check the counselors table (for administrators)
            if (!$user) {
                $sql = "SELECT id, email, password FROM counselors WHERE email = :email LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if ($user && password_verify($password, $user['password'])) {
                // Password is correct, start session and redirect
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];

                // Remember me functionality (optional: use cookies to remember user)
                if ($remember) {
                    setcookie('user_email', $email, time() + (86400 * 30), "/"); // 30 days
                    setcookie('user_password', $password, time() + (86400 * 30), "/"); // 30 days
                }

                // Redirect to appropriate dashboard based on user type
                if ($user['email'] === $email) { // Check if it's a match with the student's email
                    header("Location: ../dashboard/indexdashboard.php");
                } else {
                    header("Location: ../admindashboard/adminindex.php");
                }
                exit;
            } else {
                $loginErr = "Invalid credentials. Please try again.";
            }
        } catch (PDOException $e) {
            $loginErr = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SafeSupport</title>

    <!-- Add Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light"> <!-- Light Beige/Wheat background for the page -->

    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg" style="width: 400px;">
            <div class="card-body">
                <h2 class="text-center mb-4" style="color: #333333;">Login to SafeSupport</h2>
                <?php if ($loginErr) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $loginErr; ?>
                    </div>
                <?php } ?>

                <!-- Tab Navigation -->
                <ul class="nav nav-tabs mb-3" id="roleTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="student-tab" data-bs-toggle="tab" data-bs-target="#student" type="button" role="tab" aria-controls="student" aria-selected="true">Student</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button" role="tab" aria-controls="admin" aria-selected="false">Administrator</button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="roleTabContent">
                    <div class="tab-pane fade show active" id="student" role="tabpanel" aria-labelledby="student-tab">
                        <form method="POST" action="">
                            <input type="hidden" name="role" value="student">
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($email) ? $email : ''; ?>" required>
                                <?php if ($emailErr) { ?>
                                    <div class="text-danger small"> <?php echo $emailErr; ?> </div>
                                <?php } ?>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                                <?php if ($passwordErr) { ?>
                                    <div class="text-danger small"> <?php echo $passwordErr; ?> </div>
                                <?php } ?>
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-3">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                <label for="remember" class="form-check-label">Remember Me</label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn w-100" style="background-color: #c1703d; color: white;">Login</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin-tab">
                        <form method="POST" action="">
                            <input type="hidden" name="role" value="admin">
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($email) ? $email : ''; ?>" required>
                                <?php if ($emailErr) { ?>
                                    <div class="text-danger small"> <?php echo $emailErr; ?> </div>
                                <?php } ?>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                                <?php if ($passwordErr) { ?>
                                    <div class="text-danger small"> <?php echo $passwordErr; ?> </div>
                                <?php } ?>
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-3">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                <label for="remember" class="form-check-label">Remember Me</label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn w-100" style="background-color: #c1703d; color: white;">Login</button>
                        </form>
                    </div>
                </div>

                <!-- Register Link -->
                <div class="text-center mt-3">
                    <p class="text-muted">Don't have an account? <a href="../public/register.php" class="text-decoration-none" style="color: #c1703d;">Register</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
