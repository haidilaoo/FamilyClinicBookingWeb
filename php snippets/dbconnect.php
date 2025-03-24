<?php
//setting up database connection
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "familyclinic";

$conn = new mysqli($servername, $username, $password, $dbname);

if (mysqli_connect_error()) {
    echo 'Error: could not connect database' ;
    exit;
}

if (!$conn->select_db ("familyclinic"))
	exit("<p>Unable to locate the auth database</p>");


    $updateStatusQuery = "
    UPDATE Appointments
    SET status = CASE
        WHEN appointment_date < NOW() 
             OR (appointment_date = NOW() AND appointment_time < NOW()) 
             THEN 'Past'
        WHEN status = 'Scheduled' AND appointment_date >= NOW() AND appointment_time >= NOW() 
             THEN 'Scheduled'
        ELSE status
    END
    WHERE status <> 'Cancelled';
";

$conn->query($updateStatusQuery);
?>