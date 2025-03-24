<?php
session_start();
include '../php snippets/dbconnect.php';

// Retrieve data from POST request
$app_date = isset($_POST['weekly-date-selector']) ? $_POST['weekly-date-selector'] : ''; // Selected date
$app_time = isset($_POST['timing-selector']) ? $_POST['timing-selector'] : ''; // Assume time slot is sent in POST
// $app_type = isset($_POST['form-app-type']) ? $_POST['form-app-type'] : ''; // Appointment type
$appointment_id = $_SESSION['appointment_id']; // Assuming the appointment_id to be updated is stored in session


//set doctor 
$doctor_id = $_SESSION['selected_doctor'];

// echo 'app_fullname'. $_POST['fullname'];
// echo 'app_nric'. $_POST['form-NRIC'];
// echo 'app_email'. $_POST['form-email'];
// echo 'app_type'. $_POST['form-app-type'];

//UPDATE SLOT AVAILABILITY OF PREVIOUSLY SELECTED DATE
if ($appointment_id) {
   
    $sql = "
    SELECT slot_id 
    FROM Appointments 
    WHERE appointment_id = $appointment_id";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $slot_id = $row['slot_id'];
    
       //Update the slot to make it available again
        $sql = "
            UPDATE Appointment_Slots 
            SET is_available = TRUE 
            WHERE slot_id = $slot_id";
    
        $update_result = $conn->query($sql);
    
        if ($update_result) {
            echo "Slot updated to available successfully.";
        } else {
            echo "Error updating slot: " . $conn->error;
        }
    } 
}


if ($appointment_id && $app_date && $app_time) {

    // Update appointment slot availability
    $sql = "
  UPDATE appointment_slots
  SET is_available = FALSE
  WHERE start_time = '" . date('H:i:s', strtotime($app_time)) . "' 
  AND appointment_date = '$app_date' 
  AND doctor_id = $doctor_id"; // Ensure you are updating for the correct doctor

    $result = $conn->query($sql);


    // Retrieve the new slot ID
    $sql = "
 SELECT slot_id
 FROM appointment_slots
 WHERE start_time = '" . date('H:i:s', strtotime($app_time)) . "' 
 AND appointment_date = '$app_date' 
 AND doctor_id = $doctor_id"; // Ensure you're getting the slot for the correct doctor



    $result = $conn->query($sql);
    $app_slot_id = 0;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $app_slot_id = $row['slot_id'];
    }




    // Update the appointment with the new slot_id, date, and time
    $sql = "
        UPDATE appointments
        SET slot_id = $app_slot_id, 
            appointment_date = '$app_date', 
            appointment_time = '" . date('H:i:s', strtotime($app_time)) . "'
           
        WHERE appointment_id = $appointment_id"; // Ensure you are updating the correct appointment

    if ($conn->query($sql) === TRUE) {
        echo "Appointment updated successfully.";
    } else {
        echo "Error updating appointment: " . $conn->error;
    }
    // Redirect back to the appointments page
    header("Location: ../php/appointments.php");
    exit();
} else {
    echo "Required information missing.";
    echo 'app_date: ' . $app_date . ' ';
    echo 'app_time: ' . $app_time . ' '; //missing 

    echo 'app_id: ' . $appointment_id . ' ';


    // Redirect if accessed directly
    header("Location: appointments.php");
    exit();
}
