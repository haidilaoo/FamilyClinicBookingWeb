-- Doctors Table
CREATE TABLE Doctors (
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    specialty VARCHAR(255) NOT NULL,
    contact_number VARCHAR(15) NOT NULL,
    doctor_image VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    background TEXT NOT NULL,  
    qualifications TEXT NOT NULL 
);

CREATE TABLE Languages (
    language_id INT AUTO_INCREMENT PRIMARY KEY,
    language_name VARCHAR(100) NOT NULL
);

CREATE TABLE Doctor_Languages (
    doctor_id INT,
    language_id INT,
    FOREIGN KEY (doctor_id) REFERENCES Doctors(doctor_id),
    FOREIGN KEY (language_id) REFERENCES Languages(language_id),
    PRIMARY KEY (doctor_id, language_id)
);


-- Users Table
CREATE TABLE Patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    NRIC VARCHAR(15) NOT NULL UNIQUE,
    patient_password VARCHAR(255) NOT NULL
);

-- Doctor Monthly Schedule Table
CREATE TABLE Doctor_Schedule (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT,
    schedule_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (doctor_id) REFERENCES Doctors(doctor_id)
);

-- Appointment Slots Table
CREATE TABLE Appointment_Slots (
    slot_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT,
    appointment_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    is_available BOOLEAN DEFAULT TRUE NOT NULL,
    FOREIGN KEY (doctor_id) REFERENCES Doctors(doctor_id)
);

-- Appointments Table
CREATE TABLE Appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    doctor_id INT,
    slot_id INT,
    appointment_type VARCHAR(255) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('Scheduled', 'Past', 'Cancelled') NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES Patients(patient_id),
    FOREIGN KEY (doctor_id) REFERENCES Doctors(doctor_id),
    FOREIGN KEY (slot_id) REFERENCES Appointment_Slots(slot_id)
);
