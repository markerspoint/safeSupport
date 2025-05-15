<?php
// Include your database connection file
include '../includes/db.php';  // Make sure to create a proper connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = "Password must include at least one uppercase letter.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = "Password must include at least one number.";
    } elseif (!preg_match('/[\W_]/', $password)) {
        $error = "Password must include at least one symbol.";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database using PDO
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        
        try {
            // Get the PDO database connection
            $conn = getDb(); // Assuming getDb() is already defined in config.php

            // Prepare the statement
            $stmt = $conn->prepare($sql);

            // Bind parameters to the query
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

            // Execute the statement
            if ($stmt->execute()) {
                $success = "Registration successful!";
            } else {
                $error = "Error: " . $stmt->errorInfo()[2]; // Get PDO error message
            }
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}

// Close the database connection
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SafeSupport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/register.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-light">
    <div class="loading-spinner">
        <div class="spinner-border" role="status" style="color: #0b6043 !important;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow-sm" style="width: 60vw; max-width: 700px; border-color: #cecece;">
            <div class="row g-0">
                <div class="col-md-6 d-flex align-items-center justify-content-center">
                    <img src="../images/login.png" alt="Register Image" class="img-fluid p-3" style="max-height: 300px; object-fit: contain;">
                </div>
                <div class="col-md-6">
                    <div class="card-body" style="padding: 2rem 1.5rem;">
                        <a href="index.php" class="text-decoration-none position-absolute" style="left: 15px; top: 15px;">
                            <i class="fas fa-arrow-left" style="color: #0b6043; font-size: 1.25rem;"></i>
                        </a>
                        <h2 class="text-center mb-3" style="color: #0b6043; font-size: clamp(1.5rem, 5vw, 2rem);">Create Account</h2>
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger py-2 mb-2 small"><?php echo $error; ?></div>
                        <?php elseif (isset($success)): ?>
                            <div class="alert alert-success py-2 mb-2 small"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <form action="register.php" method="POST">
                            <div class="mb-2">
                                <label for="name" class="form-label small mb-1" style="color: #0b6043;">Name</label>
                                <input type="text" id="name" name="name" class="form-control form-control-sm border-2" required 
                                       style="border-color: #0b6043;">
                            </div>
                            
                            <div class="mb-2">
                                <label for="email" class="form-label small mb-1" style="color: #0b6043;">Email</label>
                                <input type="email" id="email" name="email" class="form-control form-control-sm border-2" required 
                                       style="border-color: #0b6043;">
                            </div>
                            
                            <div class="mb-2">
                                <label for="password" class="form-label small mb-1" style="color: #0b6043;">Password</label>
                                <input type="password" id="password" name="password" class="form-control form-control-sm border-2" required
                                       style="border-color: #0b6043;"
                                       pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$" 
                                       title="Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one symbol.">
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label small mb-1" style="color: #0b6043;">Confirm Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control form-control-sm border-2" required 
                                       style="border-color: #0b6043;">
                            </div>

                            <button type="submit" class="btn btn-sm w-100 text-white" 
                                    style="background-color: #0b6043; border: none; transition: transform 0.2s; background-color: #0b6043;"
                                    onmouseover="this.style.transform='scale(1.005)'; this.style.backgroundColor='#094d36'" 
                                    onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#0b6043'">Register</button>

                            <div class="text-center mt-2">
                                <small>Already have an account? 
                                    <a href="../public/login.php" class="text-decoration-none" 
                                       style="color: #0b6043; transition: transform 0.2s;"
                                       onmouseover="this.style.transform='scale(1.05)'; this.style.color='#094d36'" 
                                       onmouseout="this.style.transform='scale(1)'; this.style.color='#0b6043'">Login here</a>
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/register.js"></script>
</body>
</html>