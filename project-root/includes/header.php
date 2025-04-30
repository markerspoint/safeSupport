<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeSupport</title>

    <!-- Bootstrap 5.3.0 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Your custom CSS - load AFTER Bootstrap to override its styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/index.css">

    <!-- Bootstrap 5.3.0 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <nav class="navbar">
            
            <div class="navbar-logo">
                <a href="../public/index.php" style="text-decoration: none;">
                    <p>SafeSupport</p>
                </a>
            </div>

            <div class="navbar-links">
                <ul>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../public/services.php">Announcement</a></li>
                    <li><a href="../public/services.php">Services</a></li>
                    <li><a href="../public/about.php">About</a></li>
                </ul>
            </div>

            <!-- Burger Menu -->
            <div class="burger">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
        </nav>
    </header>


    <!-- burger js -->
    <script>
    const burger = document.querySelector('.burger');
    const navLinks = document.querySelector('.navbar-links');

    burger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
        burger.classList.toggle('toggle');
    });
</script>

</body>
</html>
