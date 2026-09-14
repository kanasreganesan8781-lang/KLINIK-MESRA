CREATE DATABASE IF NOT EXISTS klinik_mesra;
USE klinik_mesra;

CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

INSERT INTO doctors (name) VALUES ('Dr Siti'), ('Dr Priya');

CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(100) NOT NULL,
    doctor_id INT NOT NULL,
    slot_time VARCHAR(50) NOT NULL,
    complaints TEXT NOT NULL,
    status ENUM('Pending', 'Accepted', 'Declined', 'In Consultation', 'Pending Payment', 'Completed') DEFAULT 'Pending',
    bp VARCHAR(20) DEFAULT NULL,
    pulse VARCHAR(20) DEFAULT NULL,
    medicine_description TEXT DEFAULT NULL,
    consultation_fee DECIMAL(10,2) DEFAULT 0.00,
    medication_fee DECIMAL(10,2) DEFAULT 0.00,
    total_fee DECIMAL(10,2) DEFAULT 0.00,
    payment_method VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
);
