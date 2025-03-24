<?php
 include "../php snippets/dbconnect.php";

// Fetch all doctors
$sql = "SELECT doctor_id FROM Doctors";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($doctor = $result->fetch_assoc()) {
        $doctor_id = $doctor['doctor_id'];

        // Fetch the doctor's schedule for all dates
        $schedule_sql = "SELECT schedule_date, start_time, end_time 
                         FROM Doctor_Schedule 
                         WHERE doctor_id = $doctor_id";
        $schedule_result = $conn->query($schedule_sql);

        if ($schedule_result->num_rows > 0) {
            while ($schedule = $schedule_result->fetch_assoc()) {
                $schedule_date = $schedule['schedule_date'];
                $start_time = new DateTime($schedule['start_time']);
                $end_time = new DateTime($schedule['end_time']);

                // Adjust the end time to ensure the last appointment slot ends 1 hour before the actual end time
                $last_slot_end_time = clone $end_time;
                $last_slot_end_time->sub(new DateInterval('PT1H')); // Subtract 1 hour

                // Create appointment slots
                while ($start_time < $last_slot_end_time) {
                    // Calculate the end time of the current slot
                    $slot_end_time = clone $start_time;
                    $slot_end_time->add(new DateInterval('PT30M')); // Add 30 minutes

                    // Ensure that the slot does not exceed the last allowed slot time
                    if ($slot_end_time <= $last_slot_end_time) {
                        // Insert the slot into the Appointment_Slots table
                        $insert_sql = "INSERT INTO Appointment_Slots (doctor_id, appointment_date, start_time, end_time) 
                                       VALUES ($doctor_id, '$schedule_date', '" . $start_time->format('H:i:s') . "', '" . $slot_end_time->format('H:i:s') . "')";
                        
                        if (!$conn->query($insert_sql)) {
                            echo "Error inserting slot: " . $conn->error;
                        }
                    }

                    // Move to the next 30-minute interval
                    $start_time->add(new DateInterval('PT30M'));
                }
            }
        }
    }
}

echo "Appointment slots created successfully for all doctors.";
?>