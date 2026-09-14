<?php
include 'db.php';
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $status = ($_GET['action'] == 'accept') ? 'Accepted' : 'Declined';
    $conn->query("UPDATE appointments SET status='$status' WHERE id=$id");
    header("Location: staff.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['finalize_bill'])) {
    $id = $_POST['appointment_id'];
    $c_fee = $_POST['consultation_fee'];
    $m_fee = $_POST['medication_fee'];
    $total = $c_fee + $m_fee;
    $stmt = $conn->prepare("UPDATE appointments SET consultation_fee=?, medication_fee=?, total_fee=?, status='Pending Payment' WHERE id=?");
    $stmt->bind_param("dddi", $c_fee, $m_fee, $total, $id);
    $stmt->execute();
    header("Location: staff.php");
}
$pending = $conn->query("SELECT a.*, d.name as doctor_name FROM appointments a JOIN doctors d ON a.doctor_id = d.id WHERE a.status = 'Pending'");
$for_billing = $conn->query("SELECT a.*, d.name as doctor_name FROM appointments a JOIN doctors d ON a.doctor_id = d.id WHERE a.status = 'In Consultation'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Klinik Mesra - Staff</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header"><h1>KLINIK MESRA</h1><p><strong>STAFF PARTS</strong></p></div>
        <div class="nav-bar">
            <a href="index.php">Book Appointment</a>
            <a href="staff.php" class="active">Staff Portal</a>
            <a href="doctor.php">Doctor Portal</a>
        </div>
        <h3>Pending Appointments</h3><br>
        <?php while($row = $pending->fetch_assoc()): ?>
            <div class="card">
                <h4>Patient: <?php echo $row['patient_name']; ?></h4>
                <p>Doctor: <?php echo $row['doctor_name']; ?> | Slot: <?php echo $row['slot_time']; ?></p>
                <p>Complaints: <?php echo $row['complaints']; ?></p><br>
                <a href="staff.php?action=accept&id=<?php echo $row['id']; ?>" class="btn btn-success">Accept</a>
                <a href="staff.php?action=decline&id=<?php echo $row['id']; ?>" class="btn btn-danger">Decline</a>
            </div>
        <?php endwhile; ?>
        <hr style="margin: 20px 0;">
        <h3>Discharge & Final Billing</h3><br>
        <?php while($row = $for_billing->fetch_assoc()): ?>
            <div class="card">
                <h4>Patient: <?php echo $row['patient_name']; ?></h4>
                <p>Prescription: <?php echo nl2br($row['medicine_description']); ?></p>
                <form method="POST" style="margin-top:10px;">
                    <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                    <div class="form-group"><label>Consultation (RM):</label><input type="number" step="0.01" name="consultation_fee" value="20.00" required></div>
                    <div class="form-group"><label>Medication (RM):</label><input type="number" step="0.01" name="medication_fee" value="50.00" required></div>
                    <button type="submit" name="finalize_bill" class="btn btn-primary">COMPLETE BILL</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
