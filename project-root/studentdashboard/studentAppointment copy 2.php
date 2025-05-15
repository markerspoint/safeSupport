<?php
session_start();
include('../includes/db.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Please log in to access appointments.";
    $_SESSION['message_type'] = "warning";
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch counselors
try {
    $stmt = $pdo->prepare("SELECT id, name, email, phone_number FROM users WHERE role = 'counselor' ORDER BY name ASC");
    $stmt->execute();
    $counselors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($counselors)) {
        $_SESSION['message'] = "No counselors available.";
        $_SESSION['message_type'] = "warning";
    }
} catch (PDOException $e) {
    error_log("Counselor fetch error: " . $e->getMessage());
    $_SESSION['message'] = "Error loading counselors.";
    $_SESSION['message_type'] = "danger";
    $counselors = [];
}

// Handle appointment booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    $counselor_id = $_POST['counselor_id'];
    $appointment_time = $_POST['appointment_time'];
    $reason = $_POST['reason'];
    $status = 'pending';
    $notes = 'Scheduled appointment';

    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, counselor_id, appointment_time, reason, status, notes) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $counselor_id, $appointment_time, $reason, $status, $notes])) {
        $_SESSION['message'] = "Appointment booked successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error booking appointment.";
        $_SESSION['message_type'] = "danger";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Cancel individual appointment
if (isset($_GET['cancel_appointment_id'])) {
    $cancel_id = intval($_GET['cancel_appointment_id']);
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ? AND user_id = ? AND status = 'pending'");
    $stmt->execute([$cancel_id, $user_id]);
    $_SESSION['message'] = "Appointment cancelled.";
    $_SESSION['message_type'] = "success";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete selected appointments
if (isset($_POST['delete_selected']) && isset($_POST['appointments'])) {
    $ids = $_POST['appointments'];
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $params = array_map('intval', $ids);
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id IN ($placeholders) AND user_id = ?");
    $params[] = $user_id;
    $stmt->execute($params);
    $_SESSION['message'] = "Selected appointments deleted.";
    $_SESSION['message_type'] = "success";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch appointments
try {
    $stmt = $pdo->prepare("
        SELECT a.id, a.appointment_time, u.name AS counselor_name, a.status, a.notes 
        FROM appointments a 
        JOIN users u ON a.counselor_id = u.id 
        WHERE a.user_id = ? 
        ORDER BY a.appointment_time DESC
    ");
    $stmt->execute([$user_id]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Appointment fetch error: " . $e->getMessage());
    $_SESSION['message'] = "Error loading appointments.";
    $_SESSION['message_type'] = "danger";
    $appointments = [];
}

// Fetch counts of appointments per day for current user
$stmt = $pdo->prepare("
    SELECT DATE(appointment_time) as date, COUNT(*) as count 
    FROM appointments 
    WHERE user_id = ? 
    GROUP BY DATE(appointment_time)
");
$stmt->execute([$user_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$countsByDate = [];
foreach ($results as $row) {
    $countsByDate[$row['date']] = (int)$row['count'];
}

// Fetch all appointments for calendar color display
$stmt = $pdo->prepare("SELECT DATE(appointment_time) as date, COUNT(*) as count FROM appointments GROUP BY DATE(appointment_time)");
$stmt->execute();
$appointmentCounts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include FullCalendar CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>


    <link rel="stylesheet" href="../assets/css/studentappointment.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .flatpickr-calendar {
            font-size: 14px;
            width: 280px;
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            z-index: 9999 !important;
        }
    </style>
</head>

<?php include('../studentdashboard/studentHeader.php'); ?>



<body>
<div class="dashboard-wrapper">
        <?php include('../studentdashboard/studentHead.php'); ?>

<main class="studentAppointment-main">
    <div class="container mt-4">
        <!-- Row for Form and Calendar -->
        <div class="row mb-5">
            <!-- Book Appointment Form -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Book an Appointment</h2>
                        <form method="POST">
                            <!-- Counselor Select -->
                            <div class="mb-3">
                                <label for="counselor_id" class="form-label">Choose a Counselor</label>
                                <select name="counselor_id" id="counselor_id" class="form-select" required>
                                    <option value="">Select a counselor</option>
                                    <?php foreach ($counselors as $counselor): ?>
                                        <option value="<?= htmlspecialchars($counselor['id']) ?>">
                                            <?= htmlspecialchars($counselor['name']) ?> (<?= htmlspecialchars($counselor['email']) ?>)
                                            <?= $counselor['phone_number'] ? ' - Tel: ' . htmlspecialchars($counselor['phone_number']) : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Appointment Time -->
                            <div class="mb-3">
                                <label for="appointment_time" class="form-label">Choose Date and Time</label>
                                <input type="text" id="appointment_time" name="appointment_time" class="form-control" required placeholder="Select date and time">
                            </div>

                            <!-- Reason -->
                            <div class="mb-3">
                                <label for="reason" class="form-label">Reason for Appointment</label>
                                <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
                            </div>

                            <button type="submit" name="book_appointment" class="btn btn-primary w-100">Book Appointment</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Calendar -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Appointment Calendar</h2>
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Your Appointments</h2>
                        <form method="POST">
                            <div class="table-responsive" style="max-height: 500px;">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select_all" class="form-check-input"></th>
                                            <th>Appointment Time</th>
                                            <th>Counselor</th>
                                            <th>Status</th>
                                            <th>Notes</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($appointments as $appointment): ?>
                                        <tr>
                                            <td><input type="checkbox" name="appointments[]" value="<?= $appointment['id'] ?>" class="form-check-input"></td>
                                            <td><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                                            <td><?= htmlspecialchars($appointment['counselor_name']) ?></td>
                                            <td><span class="<?= $appointment['status'] === 'accepted' ? 'text-success' : ($appointment['status'] === 'rejected' ? 'text-danger' : 'text-warning') ?>">
                                                <?= htmlspecialchars($appointment['status']) ?></span>
                                            </td>
                                            <td><?= htmlspecialchars($appointment['notes']) ?></td>
                                            <td>
                                                <?php if ($appointment['status'] == 'pending'): ?>
                                                    <a href="?cancel_appointment_id=<?= $appointment['id'] ?>" class="btn btn-danger btn-sm">Cancel</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" name="delete_selected" class="btn btn-danger" id="delete_selected_btn">
                                <i class="bi bi-trash"></i> Delete Selected
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

</div> 

    <!-- Toast Message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast show text-white bg-<?= $_SESSION['message_type'] ?> border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body"><?= htmlspecialchars($_SESSION['message']) ?></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const toastEl = document.querySelector('.toast');
                if (toastEl) {
                    const toast = new bootstrap.Toast(toastEl);
                    toast.hide();
                }
            }, 5000);
        </script>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <!-- Your custom calendar initialization script -->
    <script src="../assets/js/studentAppointment.js"></script>

    <script>
        flatpickr("#appointment_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            minTime: "09:00",
            maxTime: "17:00",
            time_24hr: true,
            disable: [date => (date.getDay() === 0 || date.getDay() === 6)],
        });

        document.getElementById('select_all').addEventListener('click', function () {
            document.querySelectorAll('input[name="appointments[]"]').forEach(cb => cb.checked = this.checked);
        });
    </script>

    <!-- <script>
        window.appointmentCounts = <?= json_encode($countsByDate); ?>;

        window.appointmentsEvents = [
            <?php foreach ($appointments as $a): ?>
                title: '<?= addslashes(htmlspecialchars($a["counselor_name"])) ?>',
                start: '<?= htmlspecialchars($a["appointment_time"]) ?>',
                description: '<?= addslashes(htmlspecialchars($a["notes"])) ?>'
            <?php endforeach; ?>
        ];
    </script> -->

    <script>
    // appointmentCounts: { '2025-05-15': 4, '2025-05-16': 2, ... }
    window.appointmentCounts = <?= json_encode($countsByDate); ?>;
    </script>

</body>
</html>