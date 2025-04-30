<?php
session_start();
require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    try {
        $pdo = getDb();
        
        // First check in users table
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = isset($user['role']) ? $user['role'] : 'student';

            // Determine redirect based on role
            if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'counselor') {
                header("Location: ../admindashboard/adminIndex.php");
            } else {
                header("Location: ../studentdashboard/indexdashboard.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password";
            header("Location: login.php");
            exit();
        }
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: login.php");
        exit();
    }
} else {
    // If someone tries to access this file directly
    header("Location: login.php");
    exit();
}
?>
