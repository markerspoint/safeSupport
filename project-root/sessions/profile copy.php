<?php
session_start();
include('../includes/db.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php"); // Adjust path if needed
    exit();
}

// Prevent browser from caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$user_id = $_SESSION['user_id']; // Logged-in user's ID

// Fetch user data for displaying in the form
$stmt = $pdo->prepare("SELECT name, email, created_at FROM users WHERE id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // If password is not empty, update it (password should be hashed)
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :user_id");
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $password, ':user_id' => $user_id]);
    } else {
        // If password is empty, don't update it
        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :user_id");
        $stmt->execute([':name' => $name, ':email' => $email, ':user_id' => $user_id]);
    }

    $_SESSION['message'] = "Profile updated successfully!";
    $_SESSION['message_type'] = "success"; // Success toast
    header("Location: profile.php");
    exit();
}

// Handle account deletion
if (isset($_GET['delete_account'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :user_id");
    $stmt->execute([':user_id' => $user_id]);

    session_destroy(); // Log the user out after account deletion
    header("Location: login.php"); // Redirect to login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<!-- links of the studentheader -->
<?php include('../studentdashboard/studentHeader.php'); ?>

<script>
// Reload page if it's loaded from back/forward cache (bfcache)
window.addEventListener('pageshow', function (event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
        // Page was restored from bfcache or navigated via back button
        window.location.reload();
    }
});
</script>

<body>
<div class="dashboard-wrapper">
<?php include('../studentdashboard/studentHead.php'); ?>

    <main class="studentprofile-main">
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title text-center">Your Profile</h2>
                            <hr>

                            <!-- Display session messages (success, error, etc.) -->
                            <?php if (isset($_SESSION['message'])): ?>
                                <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                                    <?php echo $_SESSION['message']; ?>
                                </div>
                                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                            <?php endif; ?>

                            <!-- Profile form -->
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password (Leave empty if you don't want to change it)</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <button type="submit" name="update_profile" class="btn btn-primary w-100">Update Profile</button>
                            </form>

                            <!-- Delete Account Section -->
                            <hr>
                            <div class="alert alert-danger mt-4">
                                <h5>Delete Your Account</h5>
                                <p>If you want to delete your account, this action is permanent and cannot be undone.</p>
                                <a href="?delete_account=1" class="btn btn-danger">Delete Account</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
