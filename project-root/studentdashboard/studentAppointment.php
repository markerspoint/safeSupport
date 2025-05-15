<?php
session_start();
include('../includes/db.php');

// Fetch counselor data with error handling
try {
    $counselorQuery = $pdo->prepare("
        SELECT id, name, email, phone_number, role 
        FROM users 
        WHERE role = 'counselor' 
        ORDER BY name ASC
    ");
    
    $counselorQuery->execute();
    $counselors = $counselorQuery->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($counselors)) {
        $_SESSION['message'] = "No counselors are currently available.";
        $_SESSION['message_type'] = "warning";
    }
} catch (PDOException $e) {
    $_SESSION['message'] = "Error loading counselors. Please try again later.";
    $_SESSION['message_type'] = "danger";
    error_log("Counselor fetch error: " . $e->getMessage());
    $counselors = [];
}

// Handle appointment booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_appointment'])) {
    $counselor_id = $_POST['counselor_id'];
    $appointment_time = $_POST['appointment_time'];
    $user_id = 1; // Assuming user ID is available
    $reason = $_POST['reason'];
    $status = 'pending'; // Set the initial status as "pending"
    $notes = 'Scheduled appointment'; // Move status to notes

    // Insert appointment
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, counselor_id, appointment_time, reason, status, notes) 
                           VALUES (:user_id, :counselor_id, :appointment_time, :reason, :status, :notes)");
    if ($stmt->execute([
        ':user_id' => $user_id,
        ':counselor_id' => $counselor_id,
        ':appointment_time' => $appointment_time,
        ':reason' => $reason,
        ':status' => $status,
        ':notes' => $notes
    ])) {
        $_SESSION['message'] = "Appointment booked successfully!";
        $_SESSION['message_type'] = "success"; // Success toast
    } else {
        $_SESSION['message'] = "Error booking appointment.";
        $_SESSION['message_type'] = "danger"; // Error toast
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle multiple appointment deletion
if (isset($_POST['delete_selected'])) {
    if (isset($_POST['appointments']) && is_array($_POST['appointments'])) {
        $appointment_ids = implode(",", $_POST['appointments']);
        $deleteQuery = "DELETE FROM appointments WHERE id IN ($appointment_ids)";
        $pdo->exec($deleteQuery);
        $_SESSION['message'] = "Appointments deleted successfully!";
        $_SESSION['message_type'] = "success"; // Success toast
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Fetch existing appointments with proper join to users table
try {
    $appointmentQuery = $pdo->prepare("
        SELECT a.id, 
               a.appointment_time, 
               u.name AS counselor_name, 
               a.status, 
               a.notes 
        FROM appointments a 
        JOIN users u ON a.counselor_id = u.id 
        WHERE a.user_id = ? 
        AND u.role = 'counselor'
        ORDER BY a.appointment_time DESC
    ");
    
    $appointmentQuery->execute([1]); // Replace 1 with actual user ID
    $appointments = $appointmentQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Appointment fetch error: " . $e->getMessage());
    $_SESSION['message'] = "Error loading appointments.";
    $_SESSION['message_type'] = "danger";
    $appointments = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="../assets/css/studentdashboard/studentappointment.css">
</head>

<!-- links of the studentheader -->
<?php include('../studentdashboard/studentHeader.php'); ?>

<body>
    <div class="dashboard-wrapper">
        <?php include('../studentdashboard/studentHead.php'); ?>

        <main class="studentAppointment-main">
            <div class="row gx-4 mt-4">
            <!-- Appointment Booking -->
            <section>
                <div class="card shadow-sm" style="height: 100%;">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Book an Appointment</h2>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="counselor_id" class="form-label">Choose a Counselor</label>
                                <select name="counselor_id" id="counselor_id" class="form-select" required>
                                    <option value="">Select a counselor</option>
                                    <?php foreach ($counselors as $counselor): ?>
                                        <option value="<?php echo htmlspecialchars($counselor['id']); ?>">
                                            <?php 
                                                // Display name and email (since it's guaranteed non-null)
                                                echo htmlspecialchars($counselor['name']) . 
                                                    ' (' . htmlspecialchars($counselor['email']) . ')' .
                                                    // Only add phone if available
                                                    (!empty($counselor['phone_number']) ? ' - Tel: ' . htmlspecialchars($counselor['phone_number']) : '');
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">
                                    Select from available counselors. All counselors have verified credentials.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="appointment_time" class="form-label">Choose Date and Time</label>
                                <input type="text" id="appointment_time" name="appointment_time" class="form-control" required placeholder="Select date and time">
                            </div>
                            <!-- Add Flatpickr CSS and JS -->
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
                                .flatpickr-current-month {
                                    font-size: 14px;
                                }
                                .flatpickr-day {
                                    height: 32px;
                                    line-height: 32px;
                                }
                            </style>
                            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                            <script>
                                flatpickr("#appointment_time", {
                                    enableTime: true,
                                    dateFormat: "Y-m-d H:i",
                                    minDate: "today",
                                    minTime: "09:00",
                                    maxTime: "17:00",
                                    disable: [
                                        function(date) {
                                            return (date.getDay() === 0 || date.getDay() === 6);
                                        }
                                    ],
                                    time_24hr: true,
                                    static: true
                                });
                            </script>
                            <div class="mb-3">
                                <label for="reason" class="form-label">Reason for Appointment</label>
                                <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" name="book_appointment" class="btn btn-primary w-100">Book Appointment</button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Your Appointments -->
            <section class="col-12 col-md-6 col-lg-9">
                <div class="card shadow-sm" style="height: 100%;">
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
                                            <td><input type="checkbox" name="appointments[]" value="<?php echo $appointment['id']; ?>" class="form-check-input"></td>
                                            <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['counselor_name']); ?></td>
                                            <td>
                                                <?php
                                                    $statusClass = '';
                                                    if ($appointment['status'] == 'accepted') {
                                                        $statusClass = 'text-success';
                                                    } elseif ($appointment['status'] == 'rejected') {
                                                        $statusClass = 'text-danger';
                                                    } elseif ($appointment['status'] == 'pending') {
                                                        $statusClass = 'text-warning';
                                                    }
                                                ?>
                                                <span class="<?php echo $statusClass; ?>">
                                                    <?php echo htmlspecialchars($appointment['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($appointment['notes']); ?></td>
                                            <td>
                                                <?php if ($appointment['status'] == 'pending'): ?>
                                                <a href="?cancel_appointment_id=<?php echo $appointment['id']; ?>" class="btn btn-danger btn-sm">Cancel</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Trash Can Button (delete selected appointments) -->
                            <button type="submit" name="delete_selected" class="btn btn-danger" id="delete_selected_btn">
                                <i class="bi bi-trash"></i> Delete Selected
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        </main>
    </div>

    <!-- Toast Notification -->
    <?php if (isset($_SESSION['message'])): ?>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
        <div id="toastMessage" class="toast show align-items-center text-white bg-<?php echo $_SESSION['message_type']; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            var toastEl = document.getElementById("toastMessage");
            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl);
                toast.hide();
            }
        }, 5000);
    </script>
    <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript for Select All Checkbox -->
    <script>
        document.getElementById('select_all').addEventListener('click', function () {
            const checkboxes = document.querySelectorAll('input[name="appointments[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    </script>
</body>
</html>



