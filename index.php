<?php
include 'db.php';
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_name = $_POST['patient_name'];
    $doctor_id = $_POST['doctor_id'];
    $slot_time = $_POST['slot_time'];
    $complaints = $_POST['complaints'];

    $stmt = $conn->prepare("INSERT INTO appointments (patient_name, doctor_id, slot_time, complaints) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $patient_name, $doctor_id, $slot_time, $complaints);
    $message = $stmt->execute() ? "Appointment submitted successfully!" : "Error submitting appointment.";
}
$doctors = $conn->query("SELECT * FROM doctors");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Klinik Mesra - Book Appointment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>KLINIK MESRA</h1>
            <p><strong>PATIENT PARTS</strong></p>
        </div>
        <div class="nav-bar">
            <a href="index.php" class="active">Book Appointment</a>
            <a href="patient_invoice.php">My Invoices</a>
            <a href="staff.php">Staff Portal</a>
            <a href="doctor.php">Doctor Portal</a>
        </div>
        <?php if($message): ?><div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius:6px;"><?php echo $message; ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Patient Name:</label>
                <input type="text" name="patient_name" placeholder="e.g., Nurul Hidayah" required>
            </div>
            <div class="form-group">
                <label>Select Doctor:</label>
                <select name="doctor_id" required>
                    <option value="">-- Select Doctor --</option>
                    <?php while($doc = $doctors->fetch_assoc()): ?>
                        <option value="<?php echo $doc['id']; ?>"><?php echo $doc['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Slot Time:</label>
                <select name="slot_time" required>
                    <option value="1.00 pm">1.00 pm</option>
                    <option value="1.30 pm">1.30 pm</option>
                    <option value="2.00 pm">2.00 pm</option>
                </select>
            </div>
            <div class="form-group">
                <label>Complaints:</label>
                <textarea name="complaints" placeholder="e.g., Fever, Flu, Cough" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">SUBMIT APPOINTMENT</button>
        </form>
    </div>
</body>
</html>
