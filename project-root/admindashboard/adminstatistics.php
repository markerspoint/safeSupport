<?php
require_once('../includes/db.php');

// Start the session
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php"); // Adjust path if needed
    exit();
}

// Prevent browser from caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

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

    // Get appointments per day for the current week
    $startOfWeek = date('Y-m-d', strtotime('monday this week'));
    $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
    $appointmentsPerDayQuery = $db->prepare("
        SELECT DATE(appointment_time) as day, COUNT(*) as count
        FROM appointments
        WHERE appointment_time BETWEEN :start AND :end
        GROUP BY day
    ");
    $appointmentsPerDayQuery->execute([
        ':start' => $startOfWeek . ' 00:00:00',
        ':end' => $endOfWeek . ' 23:59:59'
    ]);
    $appointmentsPerDay = $appointmentsPerDayQuery->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for JS (ensure all days of the week are present)
    $weekDays = [];
    $weekCounts = [];
    for ($i = 0; $i < 7; $i++) {
        $day = date('Y-m-d', strtotime($startOfWeek . " +$i days"));
        $weekDays[] = $day;
        $found = false;
        foreach ($appointmentsPerDay as $row) {
            if ($row['day'] === $day) {
                $weekCounts[] = (int)$row['count'];
                $found = true;
                break;
            }
        }
        if (!$found) $weekCounts[] = 0;
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $error = "An error occurred while fetching statistics.";
}

try {
    // Update the calendar query to include all necessary fields
    $calendarQuery = $db->query("
    SELECT 
        a.id,
        a.appointment_time,
        a.status,
        a.reason,
        u.name as user_name,   -- Fetching the student's name
        c.name as counselor_name  -- Fetching the counselor's name
    FROM appointments a
    JOIN users u ON a.user_id = u.id  -- Join to get the student's info
    JOIN users c ON a.counselor_id = c.id  -- Join to get the counselor's info
    WHERE a.appointment_time IS NOT NULL
    ORDER BY a.appointment_time ASC
");

    
    $calendarEvents = [];
    while ($row = $calendarQuery->fetch(PDO::FETCH_ASSOC)) {
        $calendarEvents[] = [
            'title' => 'Appointment: ' . $row['user_name'] . ' with ' . $row['counselor_name'],
            'start' => $row['appointment_time'],
            'allDay' => false,
            'backgroundColor' => getStatusColor($row['status']),
            'extendedProps' => [
                'status' => $row['status'],
                'reason' => $row['reason']
            ]
        ];
    }
} catch (PDOException $e) {
    error_log("Calendar Error: " . $e->getMessage());
    $calendarEvents = [];
}

// Helper function for status colors
function getStatusColor($status) {
    $statusColors = [
        'pending' => '#f39c12',    // Orange
        'accepted' => '#2ecc71',    // Green
        'rejected' => '#e74c3c'     // Red
    ];
    return $statusColors[$status] ?? '#3498db';
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Statistics</title>
    <link rel="stylesheet" href="../assets/css/adashboard/adminstatistics.css">
    <!-- Add Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- icons from bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Add Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Replace the old FullCalendar links with these new ones -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css' rel='stylesheet'/>

    <!-- Prevent browser from caching -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
</head>

<?php include('../admindashboard/adminHead.php'); ?>

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
        <?php include('../admindashboard/adminHeader.php'); ?>

        <main class="adminstatistics-main">
            <div class="container">
                <h1 style="font-weight: bold; margin-left: -2rem;">
                    Dash<span style="color: #e3b766;">board</span>
                </h1>
                <?php if (isset($error)): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php else: ?>
                <div class="dashboard-layout">
                    <!-- Left side - Calendar -->
                    <div class="dashboard-left">
                        <div class="calendar-container">
                            <div class="calendar-card">
                                <!-- <div class="text-center mb-2">
                                    <i class="bi bi-calendar3" style="font-size: 2rem; color: #9b59b6;"></i>
                                    <div>
                                        <h5 class="mb-0 mt-1">Appointments Calendar</h5>
                                    </div>
                                </div> -->
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right side - Stats Cards -->
                    <div class="dashboard-right">
                        <div class="stats-container">
                            <div class="stat-card">
                                <div class="stat-content">
                                    <div class="stat-label">Total Students</div>
                                    <div class="stat-number"><?php echo $totalStudents; ?></div>
                                </div>
                                <i class="fas fa-user-graduate stat-icon"></i>
                            </div>

                            <div class="stat-card">
                                <div class="stat-content">
                                    <div class="stat-label">Total Counselors</div>
                                    <div class="stat-number"><?php echo $totalCounselors; ?></div>
                                </div>
                                <i class="fas fa-user-tie stat-icon"></i>
                            </div>

                            <div class="stat-card">
                                <div class="stat-content">
                                    <div class="stat-label">Avg. Appointments</div>
                                    <div class="stat-number"><?php echo $avgAppointments; ?></div>
                                </div>
                                <i class="fas fa-chart-line stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom section - Charts -->
                <div class="charts-section">
                    <div class="charts-container">
                        <div class="chart-card">
                            <div class="text-center mb-2">
                                <i class="bi bi-calendar-week" style="font-size: 2rem; color: #3e95cd;"></i>
                                <div>
                                    <h5 class="mb-0 mt-1">Appointments This Week</h5>
                                </div>
                            </div>
                            <canvas id="appointmentsChart" style="height: 35vh;"></canvas>
                        </div>
                        <div class="chart-card">
                            <div class="text-center mb-2">
                                <i class="bi bi-bar-chart-line" style="font-size: 2rem; color: #5cb85c;"></i>
                                <div>
                                    <h5 class="mb-0 mt-1">Booking Status Overview</h5>
                                </div>
                            </div>
                            <div class="chart-canvas">
                                <canvas id="usersChart" style="height: 35vh;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>



    <script>
    // Pass PHP variables to JavaScript
    const statisticsData = {
        pendingAppointments: <?php echo $pendingAppointments; ?>,
        completedAppointments: <?php echo $completedAppointments; ?>,
        rejectedAppointments: <?php echo $rejectedAppointments; ?>,
        totalStudents: <?php echo $totalStudents; ?>,
        totalCounselors: <?php echo $totalCounselors; ?>,
        weekDays: <?php echo json_encode($weekDays); ?>,
        weekCounts: <?php echo json_encode($weekCounts); ?>
    };
    
    // Debugging output for PHP-to-JS data
    console.log('Pending:', statisticsData.pendingAppointments);
    console.log('Accepted:', statisticsData.completedAppointments);
    console.log('Rejected:', statisticsData.rejectedAppointments);
    </script>

    <script src="../assets/js/adminstatistics.js"></script>
    <script>
        console.log("Calendar events passed to JS:", <?php echo json_encode($calendarEvents); ?>);
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: <?php echo json_encode($calendarEvents); ?>,
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            slotDuration: '01:00:00', // 1 hour slots
            slotMinTime: '08:00:00', // Start time
            slotMaxTime: '18:00:00', // End time
            eventDidMount: function(info) {
                // Enhanced tooltip
                const event = info.event;
                const props = event.extendedProps;
                info.el.title = `
                    Student: ${props.student}
                    Counselor: ${props.counselor}
                    Status: ${props.status}
                    Reason: ${props.reason}
                `.trim();
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            }
        });
        calendar.render();
    });
    </script>
</body>
</html>
