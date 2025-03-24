-- Insert values into Doctors table
INSERT INTO Doctors (first_name, last_name, specialty, contact_number, doctor_image, email, background, qualifications)
VALUES 
    ('John', 'Smith', 'Family Medicine', '91234567', 'images/doctor-jerry.svg', 'john.smith@clinic.com', 'John has over 10 years of experience in family practice, focusing on preventive care.', 'MBBS, MRCGP'),
    ('Emily', 'Chen', 'Pediatrics', '98765432', 'images/doctor-jerry.svg', 'emily.chen@clinic.com', 'Emily specializes in child development and has worked with various pediatric health issues.', 'MD, FAAP');

INSERT INTO Languages (language_name)
VALUES 
    ('English'), 
    ('Mandarin'), 
    ('Hokkien'),
    ('Tamil'),
    ('Malay');

INSERT INTO Doctor_Languages (doctor_id, language_id)
VALUES 
    (1, 1),  -- John speaks English
    (1, 2),  -- John speaks Mandarin
    (2, 1),  -- Emily speaks English
    (2, 3);  -- Emily speaks Spanish

-- Insert values into Patients table
INSERT INTO Patients (first_name, last_name, email, NRIC, patient_password) 
VALUES 
    ('Alice', 'Tan', 'alice.tan@example.com', 'S1234567A', 'password123'), 
    ('Bob', 'Lim', 'bob.lim@example.com', 'S7654321B', 'securepassword');

-- Insert values into Doctor_Schedule table
INSERT INTO Doctor_Schedule (doctor_id, schedule_date, start_time, end_time)
VALUES 
    (1, '2024-11-01', '09:00:00', '17:00:00'),
    (1, '2024-11-02', '09:00:00', '17:00:00'),
    (2, '2024-11-01', '10:00:00', '18:00:00'),
    (2, '2024-11-02', '10:00:00', '18:00:00');

-- Insert values into Appointment_Slots table
INSERT INTO Appointment_Slots (doctor_id, appointment_date, start_time, end_time, is_available)
VALUES 
    (1, '2024-11-01', '09:00:00', '09:30:00', TRUE),
    (1, '2024-11-01', '09:30:00', '10:00:00', TRUE),
    (2, '2024-11-01', '10:00:00', '10:30:00', TRUE),
    (2, '2024-11-01', '10:30:00', '11:00:00', TRUE);

-- Insert values into Appointments table
INSERT INTO Appointments (patient_id, doctor_id, slot_id, appointment_type, appointment_date, appointment_time, status)
VALUES 
    (1, 1, 1, 'General Checkup', '2024-11-01', '09:00:00', 'Scheduled'),
    (2, 2, 3, 'Consultation', '2024-11-01', '10:00:00', 'Scheduled');
