<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="../assets/css/adashboard/adminHeader.css">

    <!-- lineicons.com for icons -->
    <link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css" />

    <!-- Add Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button id="toggle-btn">
                        <i class="lni lni-dashboard-square-1"></i>
                </button>

                <div class="sidebar-logo">
                    <a href="#">Dashboard</a>
                </div>
            </div>

            <div class="sidebar-title">
                <p>Statistics</p>
            </div>

            <hr class="text-secondary">

            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="../admindashboard/adminstatistics.php" class="sidebar-link">
                    <i class="lni lni-bar-chart-4"></i>
                    <span>Analytics</span>
                    </a>
                </li>

                <hr class="text-secondary">

                <div class="sidebar-title">
                    <p>Manage</p>
                </div>

                <hr class="text-secondary">



                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                    <i class="lni lni-briefcase-2"></i>
                    <span>Appointments</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                    <i class="lni lni-hierarchy-1"></i>
                    <span>Counselor Directory</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                    <i class="lni lni-books-2"></i>
                    <span>Resource Library</span>
                    </a>
                </li>
    
                <hr class="text-secondary">

                <div class="sidebar-title">
                    <p>App Status</p>
                </div>


                <hr class="text-secondary">

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                    <i class="lni lni-plug-1"></i>
                    <span>Status</span>
                    </a>
                </li>

                <hr class="text-secondary">

                <div class="sidebar-title">
                    <p>Account</p>
                </div>

                <hr class="text-secondary">
                

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                    <i class="lni lni-user-4"></i>
                    <span>Profile</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" style="color: red;">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>
    </div>

    <main>
    </main>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS for sidebar -->
<script src="../assets/js/adminheader.js"></script>

</body>
</html>
