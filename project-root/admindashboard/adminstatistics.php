<?php
require_once('../includes/db.php');

// Get database connection
$db = getDb();

try {
    // Get appointment count by status
    $statusQuery = $db->query("SELECT status, COUNT(*) as count FROM appointments GROUP BY status");
    $statusCounts = $statusQuery->fetchAll(PDO::FETCH_ASSOC);

    // Initialize counts
    $pendingAppointments = 0;
    $completedAppointments = 0;
    $rejectedAppointments = 0;

    // Loop through the result to assign counts
    foreach ($statusCounts as $status) {
        if ($status['status'] === 'pending') {
            $pendingAppointments = $status['count'];
        } elseif ($status['status'] === 'accepted') {
            $completedAppointments = $status['count'];
        } elseif ($status['status'] === 'rejected') {
            $rejectedAppointments = $status['count'];
        }
    }

    // Get other stats
    $appointmentsQuery = $db->query("SELECT COUNT(*) FROM appointments");
    $totalAppointments = $appointmentsQuery->fetchColumn();

    $studentsQuery = $db->query("SELECT COUNT(*) FROM users WHERE role = 'user'");
    $totalStudents = $studentsQuery->fetchColumn();

    $counselorsQuery = $db->query("SELECT COUNT(*) FROM users WHERE role = 'counselor'");
    $totalCounselors = $counselorsQuery->fetchColumn();

    $avgAppointments = $totalStudents > 0 ? round($totalAppointments / $totalStudents, 1) : 0;
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $error = "An error occurred while fetching statistics.";
}
?>


<?php include('../admindashboard/adminHeader.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Statistics</title>
    <link rel="stylesheet" href="../assets/css/adashboard/adminstatistics.css">
    <!-- Add Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Add Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-4 px-4" style="margin-left: 220px; max-width: calc(100% - 220px);">
    <h1 style="font-weight: bold; margin-left: 20px;">
        Dash<span style="color: #e3b766;">board</span>
    </h1>
            <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php else: ?>
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number">
                    <?php echo $totalAppointments; ?>
                    <i class="fas fa-calendar-check stat-icon"></i>
                </div>
                <div class="stat-label">Total Appointments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php echo $pendingAppointments; ?>
                    <i class="fas fa-clock stat-icon"></i>
                </div>
                <div class="stat-label">Pending Appointments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php echo $completedAppointments; ?>
                    <i class="fas fa-check-circle stat-icon"></i>
                </div>
                <div class="stat-label">Accepted Appointments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php echo $totalStudents; ?>
                    <i class="fas fa-user-graduate stat-icon"></i>
                </div>
                <div class="stat-label">Total Students</div>   
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php echo $totalCounselors; ?>
                    <i class="fas fa-user-tie stat-icon"></i>
                </div>
                <div class="stat-label">Total Counselors</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php echo $avgAppointments; ?>
                    <i class="fas fa-chart-line stat-icon"></i>
                </div>
                <div class="stat-label">Avg. Appointments/Student</div>
            </div>
        </div> <!-- End of stats-container -->
        
        <div class="charts-container">
            <div class="chart-card">
                <canvas id="appointmentsChart" style="height: 300px;"></canvas>
            </div>
            <div class="chart-card">
                <canvas id="usersChart" style="height: 300px;"></canvas>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
    // Pass PHP variables to JavaScript
    const statisticsData = {
        pendingAppointments: <?php echo $pendingAppointments; ?>,
        completedAppointments: <?php echo $completedAppointments; ?>,
        rejectedAppointments: <?php echo $rejectedAppointments; ?>,  // Added rejected data
        totalStudents: <?php echo $totalStudents; ?>,
        totalCounselors: <?php echo $totalCounselors; ?>
    };
    
    // Debugging output for PHP-to-JS data
    console.log('Pending:', statisticsData.pendingAppointments);
    console.log('Accepted:', statisticsData.completedAppointments);
    console.log('Rejected:', statisticsData.rejectedAppointments);
    </script>

    <script src="../assets/js/adminstatistics.js"></script>
</body>
</html>
