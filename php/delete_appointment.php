<?php
// Start session if needed and include database connection
session_start();
include '../php snippets/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['appointment_id'])) {
    // Get appointment ID from form
    $appointment_id = $_POST['appointment_id'];
    echo "appointment_id: " . $appointment_id;

    
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

    // Update the status to 'Cancelled' instead of deleting (for record keeping)
    $sql = "UPDATE Appointments SET status = 'Cancelled' WHERE appointment_id = ?";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $appointment_id);
    if ($stmt->execute()) {
        // Optional: Set a success message in session or redirect back to appointments page
        $_SESSION['message'] = "Appointment has been successfully cancelled.";
    } else {
        // Optional: Set an error message
        $_SESSION['message'] = "Error cancelling the appointment. Please try again.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the appointments page
    header("Location: appointments.php");
    exit();
} else {
    // Redirect if accessed directly
    header("Location: appointments.php");
    exit();
}
?>
