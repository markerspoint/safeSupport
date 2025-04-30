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
    <link href="../assets/css/adashboard/adminprofile.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-2 px-4" style="margin-left: 220px; max-width: calc(100% - 220px);">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-2">Counselor Profile</h1>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message_type']; ?> py-1">
                        <?php echo $_SESSION['message']; ?>
                    </div>
                    <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                <?php endif; ?>
                <?php if ($counselor): ?>
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" class="row">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($counselor['id']); ?>">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control form-control-sm" id="name" name="name" value="<?php echo htmlspecialchars($counselor['name']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control form-control-sm" id="email" name="email" value="<?php echo htmlspecialchars($counselor['email']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="specialization" class="form-label">Specialization</label>
                                        <input type="text" class="form-control form-control-sm" id="specialization" name="specialization" value="<?php echo htmlspecialchars($counselor['specialization']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bio" class="form-label">Bio</label>
                                        <textarea class="form-control form-control-sm" id="bio" name="bio" required><?php echo htmlspecialchars($counselor['bio']); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="availability" class="form-label">Availability</label>
                                        <input type="text" class="form-control form-control-sm" id="availability" name="availability" value="<?php echo htmlspecialchars($counselor['availability']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="experience" class="form-label">Experience</label>
                                        <input type="number" class="form-control form-control-sm" id="experience" name="experience" value="<?php echo htmlspecialchars($counselor['experience']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">New Password (Optional)</label>
                                        <input type="password" class="form-control form-control-sm" id="password" name="password">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <button type="submit" name="update_profile" class="btn btn-primary btn-sm">Update Profile</button>
                                        <button type="submit" name="delete_profile" class="btn btn-danger btn-sm" onclick="return confirm('❗\u{1F534} Are you sure you want to delete your account? This action cannot be undone.')">Delete Account</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <p>No counselor data found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>