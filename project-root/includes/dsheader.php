<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>

    <!-- Add Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>

<!-- Header -->
<header class="bg-dark text-white p-2 position-sticky top-0 w-100" style="z-index: 1050;">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <a class="navbar-brand fw-semibold" href="../public/index.php" style="font-size: 1rem;">
            <span style="color: #e3b766; font-weight: bold; font-size: 1.1rem;">Safe</span>
            <span class="text-white" style="font-weight: bold; font-size: 1.1rem;">Support</span>
        </a>
    </div>
</header>

<!-- Sidebar -->
<div class="d-flex">
    <div class="p-3 text-dark d-flex flex-column position-fixed vh-100" style="width: 200px; background-color: #333333;">
        <h5 class="text-white">Student Menu</h5>
        <nav class="nav flex-column">
            <a class="nav-link text-white" href="../sessions/profile.php" style="transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#e3b766'; this.style.color='white'" onmouseout="this.style.backgroundColor=''; this.style.color='white'">
                <i class="bi bi-person-circle"></i> Profile
            </a>
            <a class="nav-link text-white" href="../studentdashboard/indexdashboard.php" style="transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#e3b766'; this.style.color='white'" onmouseout="this.style.backgroundColor=''; this.style.color='white'">
                <i class="bi bi-calendar-event"></i> Appointments
            </a>
            <a class="nav-link text-white" href="../studentdashboard/counselorDirectory.php" style="transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#e3b766'; this.style.color='white'" onmouseout="this.style.backgroundColor=''; this.style.color='white'">
                <i class="bi bi-people-fill"></i> Counselor Directory
            </a>
            <a class="nav-link text-white" href="../studentdashboard/resourcesLibrary.php" style="transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#e3b766'; this.style.color='white'" onmouseout="this.style.backgroundColor=''; this.style.color='white'">
                <i class="bi bi-gear-fill"></i> Resources Library
            </a>
            <hr class="text-secondary">
            <a class="nav-link text-danger" href="#" onclick="logout()" style="transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#e3b766'; this.style.color='white'" onmouseout="this.style.backgroundColor=''; this.style.color='white'">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </nav>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function logout() {
        // Clear session and cookies
        document.cookie = 'user_email=; Max-Age=0; path=/';
        document.cookie = 'user_password=; Max-Age=0; path=/';
        fetch('../public/logout.php', { method: 'POST' })
            .then(response => {
                if (response.ok) {
                    window.location.href = '../public/index.php';
                }
            });
    }
</script>

</body>
</html>
