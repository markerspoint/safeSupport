<?php
// Include your database connection file
include '../includes/db.php';  // Make sure to create a proper connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
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
        $sql = "INSERT INTO users (name, email, phone_number, password) VALUES (:name, :email, :phone_number, :password)";
        
        try {
            // Get the PDO database connection
            $conn = getDb(); // Assuming getDb() is already defined in config.php

            // Prepare the statement
            $stmt = $conn->prepare($sql);

            // Bind parameters to the query
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
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
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light"> <!-- Light Beige/Wheat background for the page -->

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #c1703d;">
        <div class="container">
            <a class="navbar-brand" href="../public/index.php">SafeSupport</a>
        </div>
    </nav>

    <!-- Register Form -->
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-lg p-4" style="width: 400px;">
            <h2 class="text-center text-dark">Create Your Account</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
            <?php elseif (isset($success)): ?>
                <div class="alert alert-success mt-3"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required
                        pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$" 
                        title="Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one symbol.">
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100" style="background-color: #c1703d; border-color: #c1703d;">Register</button>
                </div>

                <div class="text-center mt-3">
                    <p>Already have an account? <a href="../public/login.php" class="text-decoration-none" style="color: #c1703d;">Login here</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
