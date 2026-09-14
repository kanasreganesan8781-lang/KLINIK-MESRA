<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay'])) {
    $id = $_POST['appointment_id'];
    $method = $_POST['payment_method'];
    $stmt = $conn->prepare("UPDATE appointments SET payment_method=?, status='Completed' WHERE id=?");
    $stmt->bind_param("si", $method, $id);
    $stmt->execute();
    $msg = "Payment processed via " . $method;
}
$invoices = $conn->query("SELECT * FROM appointments WHERE status IN ('Pending Payment', 'Completed') ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Klinik Mesra - Invoices</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header"><h1>KLINIK MESRA</h1><p><strong>PATIENT PARTS - INVOICE</strong></p></div>
        <div class="nav-bar">
            <a href="index.php">Book Appointment</a>
            <a href="patient_invoice.php" class="active">My Invoices</a>
            <a href="staff.php">Staff Portal</a>
            <a href="doctor.php">Doctor Portal</a>
        </div>
        <?php if(isset($msg)): ?><div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius:6px;"><?php echo $msg; ?></div><?php endif; ?>
        <?php while($row = $invoices->fetch_assoc()): ?>
            <div class="card">
                <h3>Patient: <?php echo $row['patient_name']; ?></h3>
                <p>Status: <span class="badge <?php echo $row['status'] == 'Completed' ? 'bg-completed' : 'bg-pending'; ?>"><?php echo $row['status']; ?></span></p>
                <hr style="margin:10px 0;">
                <p>Consultation: RM <?php echo number_format($row['consultation_fee'], 2); ?></p>
                <p>Medication: RM <?php echo number_format($row['medication_fee'], 2); ?></p>
                <p><strong>Total Amount: RM <?php echo number_format($row['total_fee'], 2); ?></strong></p><br>
                <?php if($row['status'] == 'Pending Payment'): ?>
                    <form method="POST">
                        <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="pay" value="1">
                        <button type="submit" name="payment_method" value="CASH PAYMENT" class="btn btn-warning">CASH PAYMENT</button>
                        <button type="submit" name="payment_method" value="ONLINE BANKING" class="btn btn-primary">ONLINE BANKING</button>
                        <button type="submit" name="payment_method" value="CARD" class="btn btn-success">CARD</button>
                    </form>
                <?php else: ?>
                    <p><strong>Paid via:</strong> <?php echo $row['payment_method']; ?></p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
