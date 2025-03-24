<?php
include "../php snippets/dbconnect.php";
// include "../php snippets/updateappt.php";

if (!isset($_SESSION))
    session_start();
// var_dump($_SESSION);
$id = session_id();

//set variables from session
$email = $_SESSION['valid_user'];

$status = isset($_GET['status']) ? $_GET['status'] : 'upcoming';


if (isset($_SESSION['valid_user'])) {
    $query = "SELECT 
    a.appointment_id,
    a.appointment_type,
    a.appointment_time,
    a.appointment_date,
    d.first_name AS doctor_first_name,
    d.last_name AS doctor_last_name,
    a.status
FROM 
    Appointments a
JOIN 
    Doctors d ON a.doctor_id = d.doctor_id
JOIN 
    Patients p ON a.patient_id = p.patient_id
WHERE 
    p.email = '$email' 
";
}

// Add additional conditions based on the selected status
if ($status === 'past') {
    $query .= " AND a.appointment_date < NOW()"; // Fetch past appointments
} elseif ($status === 'cancelled') {
    $query .= " AND a.status = 'Cancelled'"; // Fetch cancelled appointments
} else {
    $query .= " AND a.appointment_date >= NOW() AND a.status = 'Scheduled'"; // Fetch upcoming appointments
}


$result = $conn->query($query);

if ($result->num_rows > 0) {

    // $row = $result->fetch_assoc();
    while ($row = $result->fetch_assoc()) {

        //set variables
        $appointment_id = htmlspecialchars($row['appointment_id']);
        $appointment_type = htmlspecialchars($row['appointment_type']);
        $appointment_time = htmlspecialchars($row['appointment_time']);
        $appointment_date = htmlspecialchars($row['appointment_date']);
        $doctor_first_name = htmlspecialchars($row['doctor_first_name']);
        $doctor_last_name = htmlspecialchars($row['doctor_last_name']);
        $status = htmlspecialchars($row['status']);

        //formatting appointment_date to get its DAY OF WK

        // Create a DateTime object from the date
        $date = new DateTime($appointment_date);
        $time = new DateTime($appointment_time);

        // Format the date 
        $day_of_week = $date->format('l'); // 'l' represents the full name of the day // to get the day of the week
        $formatted_date = $date->format('d M Y');
        $formatted_time = $time->format('h:i a');

        //FOR DEBUGGING: TO BE DELETED
        // Output the day and formatted date
        // echo "<br>The appointment falls on a $day_of_week. <br>";
        // echo "Formatted date: $formatted_date <br>";
        // echo "Formatted time: $formatted_time <br>";
        // echo "Doctor full name: " . $doctor_first_name . " " . $doctor_last_name . "<br>";

        // //for testing if query is correct
        // echo "Appointment ID: " . $appointment_id . "<br>";
        // echo "Appointment Type: " . $appointment_type . "<br>";
        // echo "Appointment Time: " . $appointment_time . "<br>";
        // echo "Appointment Date: " . $appointment_date . "<br>";
        // echo "Doctor's First Name: " . $doctor_first_name . "<br>";
        // echo "Doctor's Last Name: " . $doctor_last_name . "<br>";
        // echo "Status: " . $status . "<br>";
    }
} else {
    // echo "No appointments found.";
}



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Family Clinic</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" type="text/css" href="../styles.css" />
</head>

<body>
    <?php include "../php snippets/navbar.php"; ?>
    <div class="container">
        <section style="margin-top: 100px">
            <div>
                <h2>Appointments</h2>
                <nav class="nav-appt-container">
                    <ul class="nav-appt">
                        <li class="nav-item">
                            <a class="nav-link <?php echo (!isset($_GET['status']) || $_GET['status'] === 'upcoming') ? 'active' : ''; ?>" aria-current="page" href="?status=upcoming">Upcoming</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo isset($_GET['status']) && $_GET['status'] === 'past' ? 'active' : ''; ?>" href="?status=past">Past</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo isset($_GET['status']) && $_GET['status'] === 'cancelled' ? 'active' : ''; ?>" href="?status=cancelled">Cancelled</a>
                        </li>
                    </ul>

                </nav>

                <div style="margin-bottom: 40px;"></div>
                <div id="appointments" class="display-flex gap-16 flex-direction-column">


                    <?php
                    $status = isset($_GET['status']) ? $_GET['status'] : 'upcoming'; // Default to upcoming appointments

                    $sql = "SELECT 
                    a.appointment_id,
                    a.appointment_type,
                    a.appointment_time,
                    a.appointment_date,
                    d.first_name AS doctor_first_name,
                    d.last_name AS doctor_last_name,
                    a.status,
                    d.doctor_image
                FROM 
                    Appointments a
                JOIN 
                    Doctors d ON a.doctor_id = d.doctor_id
                JOIN 
                    Patients p ON a.patient_id = p.patient_id
                WHERE 
                    p.email = '$email' 
                ";


                    // Add additional conditions based on the selected status
                    if ($status === 'past') {
                        $sql .= " AND a.status = 'Past'"; // Fetch past appointments
                    } elseif ($status === 'cancelled') {
                        $sql .= " AND a.status = 'Cancelled'"; // Fetch cancelled appointments
                    } else {
                        $sql .= " AND a.appointment_date >= NOW() AND a.status = 'Scheduled'"; // Fetch upcoming appointments
                    }

                    $result = $conn->query($sql);
                    ?>

                    <div class="appointments-container display-flex flex-direction-column gap-16">
                        <?php
                        if ($result->num_rows > 0) {
                            // Loop through each appointment
                            while ($row = $result->fetch_assoc()) {
                                // Set variables
                                $appointment_id = htmlspecialchars($row['appointment_id']);
                                $appointment_type = htmlspecialchars($row['appointment_type']);
                                $appointment_time = htmlspecialchars($row['appointment_time']);
                                $appointment_date = htmlspecialchars($row['appointment_date']);
                                $doctor_first_name = htmlspecialchars($row['doctor_first_name']);
                                $doctor_last_name = htmlspecialchars($row['doctor_last_name']);
                                $status = htmlspecialchars($row['status']);
                                $doctorImage = htmlspecialchars($row['doctor_image']);

                                // Create a DateTime object from the date
                                $date = new DateTime($appointment_date);
                                $time = new DateTime($appointment_time);

                                // Format the date 
                                $day_of_week = $date->format('l'); // 'l' represents the full name of the day // to get the day of the week
                                $formatted_date = $date->format('d M Y');
                                $formatted_time = $time->format('h:i a');

                                // Output the day and formatted date
                                // echo "<br>The appointment falls on a $day_of_week. <br>";
                                // echo "Formatted date: $formatted_date <br>";
                                // echo "Formatted time: $formatted_time <br>";
                                $doctor_full_name = $doctor_first_name . " " . $doctor_last_name;
                        ?>

                                <!-- <div class="appointment-card">
                                <h3><?php echo $appointment_type; ?></h3>
                                <p><strong>Date:</strong> <?php echo $appointment_date; ?></p>
                                <p><strong>Time:</strong> <?php echo $appointment_time; ?></p>
                                <p><strong>Doctor:</strong> Dr. <?php echo $doctor_first_name . ' ' . $doctor_last_name; ?></p>
                                <p><strong>Status:</strong> <?php echo $status; ?></p>
                            </div> -->

                                <div id="appointment-card" class="display-flex card <?php echo strtolower($status); ?>" style="justify-content: space-between;">
                                    <div class="display-flex gap-32">
                                        <p class="line-height-24 date-style">
                                            <span>
                                                <?php
                                                echo $day_of_week;
                                                ?>
                                            </span>
                                            <br>
                                            <span><strong>
                                                    <?php
                                                    echo $formatted_date;
                                                    ?>
                                                </strong></span>
                                        </p>

                                        <p class="line-height-24">
                                            <span>
                                                <?php
                                                echo $appointment_type;
                                                ?>
                                            </span>
                                            <br>
                                            <span class="display-flex gap-8 align-items-center">
                                                <img src="../images/icon-clock.svg" width="16px">
                                                <span>
                                                    <?php
                                                    echo $formatted_time;
                                                    ?>
                                                </span>
                                            </span>
                                        </p>

                                        <div class="display-flex gap-8 align-items-center">
                                            <img src="../images/<?php
                                                echo $doctorImage;
                                                ?>" width="32px">
                                            <p>Dr
                                                <?php
                                                echo $doctor_full_name;
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Conditionally Render UI Elements Based on Status
                             <?php if ($status === 'Scheduled') : ?>
                            <!-- UI elements specific to upcoming/scheduled appointments -->
                                    <form id="reschedule-form" action="reschedule_appt.php" method="POST">
                                        <input type="hidden" name="appointment_id" id="hidden-appointment-id">
                                    </form>
                                    <div class="display-flex gap-16 align-items-center">
                                        <button class="btn-blue-sm" onclick="openReschedule(this)" data-appointment-id="<?php echo $appointment_id; ?>">Reschedule</button>
                                        <!-- Form for Deletion or Cancellation -->
                                        <form id="delete-appointment-form" action="delete_appointment.php" method="post"
                                            onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                            <input type="hidden" id="hidden-appointment-id" name="appointment_id" value="<?php echo $appointment_id; ?>">
                                            <button class="remove-default-btn-style display-flex align-items-center" type="button" data-appointment-id="<?php echo $appointment_id; ?>" onclick="deleteAppt(this);">
                                                <img src="../images/icon-bin.svg" width="24px" alt="Delete">
                                                 
                                            </button>
                                        </form>


                                        <!-- <img src="../images/icon-bin.svg" width="24px"> -->
                                    </div>
                                <?php elseif ($status === 'Past') : ?>
                                    <!-- UI elements specific to past appointments -->
                                    <p>This appointment has concluded.</p>
                                <?php elseif ($status === 'Cancelled') : ?>
                                    <!-- UI elements specific to cancelled appointments -->
                                    <p>This appointment was cancelled.</p>
                                <?php endif; ?>
                                </div>

                        <?php
                            }
                        } else {
                            echo "<p>No appointments found.</p>";
                        }
                        ?>
                    </div>

                </div>
                <div style="margin-bottom: 100px;"></div>
        </section>
    </div>
    <div class="padding-2"></div>
        <footer>
            <div class="footer-container">
                <div class="footer-content">
                    <div styles="display: flex;
                                            align-items: center;
                                            gap: 16px;">
                        <div class="display-flex gap-16 align-items-center">
                            <img src="../images/logo-without-name.svg">
                            <h4 style="margin: 0px;">Health Family Clinic</h4>
                        </div>
                        <p style="margin-top: 12px;">The go-to clinic choice for families from diverse backgrounds and nationwide.</p>
                    </div>           
                     </div>
                <div class="footer-content-links">
                    <p><strong>Quick links</strong></p>
                    <div style="display: flex;
                                        flex-direction: column;
                                        align-items: flex-start;
                                        gap: 12px;
                                        align-self: stretch;">
                        <a href="index.php">Home</a>
                        <a href="ourdoctors.php">Our Doctors</a>
                        <a href="appointment-selection.php">Book an Appointment</a>
                    </div>
                </div>
                <div class="footer-content-links">
                    <p><strong>Location & Contact</strong></p>
                    <div style="display: flex;
                                        flex-direction: column;
                                        align-items: flex-start;
                                        gap: 12px;
                                        align-self: stretch;">
                        <p>Jurong West Avenue 18, S123456</p>
                        <p>+(65) 87654321</p>
                    </div>
                </div>

            </div>

        </footer>
</body>
<script src="../appointments.js"></script>

</html>