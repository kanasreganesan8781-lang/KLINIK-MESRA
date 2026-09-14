<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['complete_consultation'])) {
    $id = $_POST['appointment_id'];
    $bp = $_POST['bp'];
    $pulse = $_POST['pulse'];
    $meds = $_POST['medicine_description'];
    $stmt = $conn->prepare("UPDATE appointments SET bp=?, pulse=?, medicine_description=?, status='In Consultation' WHERE id=?");
    $stmt->bind_param("sssi", $bp, $pulse, $meds, $id);
    $stmt->execute();
    header("Location: doctor.php");
}
$accepted = $conn->query("SELECT a.*, d.name as doctor_name FROM appointments a JOIN doctors d ON a.doctor_id = d.id WHERE a.status = 'Accepted'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Klinik Mesra - Doctor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header"><h1>KLINIK MESRA</h1><p><strong>DOCTOR PARTS</strong></p></div>
        <div class="nav-bar">
            <a href="index.php">Book Appointment</a>
            <a href="staff.php">Staff Portal</a>
            <a href="doctor.php" class="active">Doctor Portal</a>
        </div>
        <h3>Active Patient Queue</h3><br>
        <?php while($row = $accepted->fetch_assoc()): ?>
            <div class="card" style="background:#fff9e6;">
                <h2><?php echo $row['patient_name']; ?></h2>
                <p><strong>Complaints:</strong> <?php echo $row['complaints']; ?></p>
                <form method="POST" style="margin-top:15px;">
                    <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                    <div class="form-group">
                        <label>Vitals:</label>
                        <input type="text" name="bp" placeholder="BP (e.g., 123/87)" required style="margin-bottom:5px;">
                        <input type="text" name="pulse" placeholder="Pulse (e.g., 89)" required>
                    </div>
                    <div class="form-group">
                        <label>Description Medicine:</label>
                        <textarea name="medicine_description" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="complete_consultation" class="btn btn-danger" style="width:100%;">COMPLETE</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
