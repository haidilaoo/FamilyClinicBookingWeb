<?php
include "../php snippets/dbconnect.php";

// Set the timezone to Singapore
date_default_timezone_set('Asia/Singapore');

// Get today's date
$startDate = new DateTime();
$endDate = clone $startDate;
$endDate->modify('+2 months'); // Two months later

// Fetch all doctors
$sql = "SELECT doctor_id FROM Doctors";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Define the weekly schedule
    $weeklySchedule = [
        'Monday' => ['start_time' => '09:00:00', 'end_time' => '17:00:00'],
        'Tuesday' => ['start_time' => '09:00:00', 'end_time' => '17:00:00'],
        'Wednesday' => ['start_time' => '09:00:00', 'end_time' => '17:00:00'],
        'Thursday' => ['start_time' => '09:00:00', 'end_time' => '17:00:00'],
        'Friday' => ['start_time' => '09:00:00', 'end_time' => '17:00:00'],
        'Saturday' => ['start_time' => '10:00:00', 'end_time' => '15:00:00'],
        'Sunday' => ['start_time' => '10:00:00', 'end_time' => '13:00:00'], // Ends earlier on Sunday
    ];

    while ($doctor = $result->fetch_assoc()) {
        $doctor_id = $doctor['doctor_id'];

        // Loop through each day from today to two months later
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $dayOfWeek = $currentDate->format('l'); // Get the day of the week
            
            // Check if the day is in the schedule
            if (isset($weeklySchedule[$dayOfWeek])) {
                $start_time = $weeklySchedule[$dayOfWeek]['start_time'];
                $end_time = $weeklySchedule[$dayOfWeek]['end_time'];

                // Insert the schedule into the Doctor_Schedule table
                $insert_sql = "INSERT INTO Doctor_Schedule (doctor_id, schedule_date, start_time, end_time) 
                               VALUES ($doctor_id, '" . $currentDate->format('Y-m-d') . "', '$start_time', '$end_time')";
                
                if (!$conn->query($insert_sql)) {
                    echo "Error inserting schedule for doctor $doctor_id on " . $currentDate->format('Y-m-d') . ": " . $conn->error . "<br>";
                }
            }

            // Move to the next day
            $currentDate->modify('+1 day');
        }
    }
}

echo "Doctor schedules created successfully for the next two months.";
?>
