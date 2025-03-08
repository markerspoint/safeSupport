<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeSupport</title>

    <!-- Add the CSS for animations -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top" style="background-color: #333333;">
        <div class="container">
            <!-- Logo Section -->
            <a class="navbar-brand fw-semibold" href="../public/index.php">
                <span style="color: #e3b766; font-weight: bold; font-size: 1.2rem;">Safe</span><span class="text-white" style="font-weight: bold; font-size: 1.2rem;">Support</span>
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/services">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/contact">Contact</a>
                        </li>
                    </ul>
                </div>

                <!-- User Icon Dropdown -->
                <div class="dropdown ms-3">
                    <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                        <img src="../images/profileblack.gif" alt="profile" class="rounded-circle" width="30" height="30" style="filter: brightness(0) saturate(100%) invert(100%);">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="../public/login.php">Login</a></li>
                        <li><a class="dropdown-item" href="../public/register.php">Register</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Include Bootstrap JS at the bottom of <body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
