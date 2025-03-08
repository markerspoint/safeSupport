<?php
// filepath: c:\safesupport\project-root\admindashboard\adminProfile.php

// Start the session
session_start();

// Include database connection (replace with your actual path)
include('../config/config.php');

// Include the admin header
include('adminHeader.php');

// Function to fetch counselor data
function fetchCounselorData($pdo, $email) {
    $sql = "SELECT id, name, email, specialization, bio, availability, experience FROM counselors WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to update counselor data
function updateCounselorData($pdo, $id, $name, $email, $specialization, $bio, $availability, $experience, $password = null) {
    if ($password) {
        $sql = "UPDATE counselors SET name = :name, email = :email, specialization = :specialization, bio = :bio, availability = :availability, experience = :experience, password = :password WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['name' => $name, 'email' => $email, 'specialization' => $specialization, 'bio' => $bio, 'availability' => $availability, 'experience' => $experience, 'password' => password_hash($password, PASSWORD_DEFAULT), 'id' => $id]);
    } else {
        $sql = "UPDATE counselors SET name = :name, email = :email, specialization = :specialization, bio = :bio, availability = :availability, experience = :experience WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['name' => $name, 'email' => $email, 'specialization' => $specialization, 'bio' => $bio, 'availability' => $availability, 'experience' => $experience, 'id' => $id]);
    }
}

// Function to delete counselor data
function deleteCounselorData($pdo, $id) {
    $sql = "DELETE FROM counselors WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['id' => $id]);
}

// Get PDO connection
$pdo = getDb(); // Get the PDO connection from config.php

// Fetch counselor data for the logged-in counselor
$counselor = fetchCounselorData($pdo, $_SESSION['user_email']);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $specialization = $_POST['specialization'];
    $bio = $_POST['bio'];
    $availability = $_POST['availability'];
    $experience = $_POST['experience'];
    $password = $_POST['password'];
    $id = $_POST['id'];

    if (updateCounselorData($pdo, $id, $name, $email, $specialization, $bio, $availability, $experience, $password)) {
        $_SESSION['message'] = "Profile updated successfully!";
        $_SESSION['message_type'] = "success";
        header("Location: adminProfile.php");
        exit();
    } else {
        $_SESSION['message'] = "Failed to update profile.";
        $_SESSION['message_type'] = "danger";
    }
}

// Handle profile deletion
if (isset($_POST['delete_profile'])) {
    $id = $_POST['id'];

    if (deleteCounselorData($pdo, $id)) {
        $_SESSION['message'] = "Profile deleted successfully!";
        $_SESSION['message_type'] = "success";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['message'] = "Failed to delete profile.";
        $_SESSION['message_type'] = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - SafeSupport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4 px-4" style="margin-left: 220px; max-width: calc(100% - 220px);">
        <div class="col-12 col-md-8 col-lg-6">
            <h1 class="mb-4">Counselor Profile</h1>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                    <?php echo $_SESSION['message']; ?>
                </div>
                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
            <?php endif; ?>
            <?php if ($counselor): ?>
                <div class="card">
                    <div class="card-body">
                        <!-- Update Profile Form -->
                        <h2 class="mt-4">Update Profile</h2>
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($counselor['id']); ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($counselor['name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($counselor['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="specialization" class="form-label">Specialization</label>
                                <input type="text" class="form-control" id="specialization" name="specialization" value="<?php echo htmlspecialchars($counselor['specialization']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" required><?php echo htmlspecialchars($counselor['bio']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="availability" class="form-label">Availability</label>
                                <input type="text" class="form-control" id="availability" name="availability" value="<?php echo htmlspecialchars($counselor['availability']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="experience" class="form-label">Experience</label>
                                <input type="number" class="form-control" id="experience" name="experience" value="<?php echo htmlspecialchars($counselor['experience']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password (Leave empty if you don't want to change it)</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                        </form>

                        <!-- Delete Profile Form -->
                        <hr>
                        <div class="alert alert-danger mt-4">
                            <h5>Delete Your Account</h5>
                            <p>If you want to delete your account, this action is permanent and cannot be undone.</p>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($counselor['id']); ?>">
                                <button type="submit" name="delete_profile" class="btn btn-danger">Delete Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <p>No counselor data found.</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>