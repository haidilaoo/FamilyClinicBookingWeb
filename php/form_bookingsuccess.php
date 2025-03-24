<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Health Family Clinic</title>

        <!-- Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

        <!-- Stylesheet -->
        <link rel="stylesheet" type="text/css" href="../styles.css" />
    </head>

    <body>

        <?php
            include "../php snippets/dbconnect.php";

            session_start();

            $doctor_id=0;
            $app_date ='';
            $app_time ='';
            // $email = 'alice.tan@example.com';
            $email = $_SESSION['valid_user'];
            $patient_id = 0;
            $app_slot_id = 0;
            
            
            

            if (isset($_POST['fullname']) && isset($_POST['form-NRIC']) && isset($_POST['form-email']) && isset($_POST['form-app-type'])) {

                $doctor_id = $_SESSION['selected_doctor'];
                $app_date = $_SESSION['app_date'];
                $app_time = $_SESSION['app_time'];
                $app_type = $_POST['form-app-type'];
                

                $checkDuplicateApp = "SELECT * FROM appointments WHERE appointment_type = '$app_type' AND appointment_time = '".date('H:i:s',strtotime($app_time))."' AND appointment_date ='$app_date' AND doctor_id = $doctor_id";

                $result = $conn -> query($checkDuplicateApp);
                
                if ($result->num_rows > 0) {
                    

                } else {

                    // update appointment slot availability
                    $sql = "
                            UPDATE appointment_slots
                            SET is_available = FALSE
                            WHERE start_time ='". date('H:i:s',strtotime($app_time))."' AND appointment_date = '$app_date'";

                    $result = $conn->query($sql);

                            
                    $sql =  "SELECT patient_id 
                            FROM patients
                            WHERE email = '$email'
                            ";  

                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($patient = $result->fetch_assoc()) {
                            $patient_id = $patient['patient_id'];
                            // echo '<br>patientid '. $patient_id;
                        }
                    }

                    $sql =  "SELECT slot_id
                            FROM appointment_slots
                            WHERE start_time ='". date('H:i:s',strtotime($app_time))."' AND appointment_date = '$app_date'";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $app_slot_id = $row['slot_id'];
                            $_SESSION['app_slot_id'] = $app_slot_id;
                        }
                    }


                    $sql =  "
                            INSERT INTO appointments (patient_id, doctor_id, slot_id, appointment_type, appointment_date, appointment_time, status)
                            VALUES ($patient_id, $doctor_id, $app_slot_id, '$app_type', '$app_date', '".date('H:i:s',strtotime($app_time))."', 'Scheduled')";

                    $result = $conn->query($sql);

                    $patient_name = $_SESSION['valid_user'];
                    //Send email confirmation
                    include '../php snippets/emailconfirmation.php';
                }

            }

            
        ?>
        <!-- added navbar here -->
         <?php include "../php snippets/navbar.php"; ?>

        <div class="container">

        <section style="margin-top: 100px;">
            <div class="two-section">
                <div class="left-col">
                    <ol class="stepper">
                        <li class="stepper-done"></li>
                        <li class="stepper-done"></li>
                        <li class="stepper-active stepper-done"></li>
                     </ol>
                    <h2>Booking success!</h2>

                    <p class="text-mediumgrey">We have sent your booking information to your email address. If you have not received the email, please click the button below.</p>

                    <div class="padding-2"></div>

                    <div class="display-flex">
                        <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>
                        <form action="../php snippets/emailconfirmation.php" method="POST" target="dummyframe">
                            <button class="btn-blue-sm">Resend Email</button>
                        </form>
                        <span style="padding: 0 2px;"></span>
                        <a href="../php/appointment-selection.php">
                            <button class="btn-blue-outline-sm">Book a New Appointment</button>
                        </a>
                    </div>

                </div>

                <div class="right-col">
                    <div class="right-col-width">
                        <div class="card text-mediumgrey" style="padding: 5%;">
                            <p>Please arrive at least <span class="font-bold">15 minutes</span> prior to your scheduled appointment time.</p>

                            <hr class="hr-styles">

                            <!-- Personal detail section -->
                            <div>
                                <?php
                                    $sql = '
                                    SELECT first_name, last_name, email, NRIC 
                                    FROM patients
                                    WHERE email ="'. $email .'"
                                    ';
        
        
                                    $result = $conn->query($sql);
        
                                    if ($result->num_rows > 0) {
                                        while ($patient = $result->fetch_assoc()) {

                                            echo '<div class="display-flex gap-12">
                                                    <img src="../images/icon-profile.svg" aria-label="doctor profile picture">
                                                    <p>Personal Details</p>
                                                </div>
                                                
                                                <div class="padding-2"></div>

                                                <div class="grid-container-2">
                                                    <p>Full Name</p>
                                                    <p class="font-bold">
                                                        '.$patient['first_name'].' '.$patient['last_name'].'
                                                    </p>
                                                </div>
                                                <div class="grid-container-2 margin-y-2">
                                                    <p>NRIC/FIN</p>
                                                    <p class="font-bold">
                                                        '.$patient['NRIC'].'
                                                    </p>
                                                </div>
                                                <div class="grid-container-2">
                                                    <p>Email</p>
                                                    <p class="font-bold">
                                                        <span> '.$patient['email'].'
                                                    </p>
                                                </div>';
        
                                        }
                                    } else {
                                        echo "No patient found.";
                                    }
                                ?>

                            </div>

                            <hr class="hr-styles">

                            <!-- Appointment detail section -->
                            <div>
                                <?php
                                    $sql = '
                                    SELECT appointment_type, appointment_date, appointment_time 
                                    FROM appointments
                                    WHERE slot_id ="'. $_SESSION['app_slot_id'] .'" AND appointment_time ="'. date('H:i:s',strtotime($app_time)).'" AND appointment_date = "'.$app_date.'"
                                    ';
        
        
                                    $result = $conn->query($sql);
        
                                    if ($result->num_rows > 0) {
                                        while ($app = $result->fetch_assoc()) {
                                            echo '<div class="display-flex gap-12">
                                                    <img src="../images/icon-schedule.svg" aria-label="doctor profile picture">
                                                    <p>Appointment Details</p>
                                                    </div>
                                                    
                                                    <div class="padding-2"></div>

                                                    <div class="grid-container-2">
                                                        <p>What</p>
                                                        <p class="font-bold">
                                                            '.$app['appointment_type'].'
                                                        </p>
                                                    </div>
                                                    <div class="grid-container-2 margin-y-2">
                                                        <p>When</p>
                                                        <p class="font-bold">
                                                            <span> '.date('d F Y',strtotime($app['appointment_date'])).'</span> <br/>
                                                            <span>'.date('h:i A',strtotime($app['appointment_time'])).'</span>
                                                        </p>
                                                    </div>
                                                    <div class="grid-container-2">
                                                        <p>Where</p>
                                                        <p class="font-bold">
                                                            <span> Health Family Clinic </span> <br/>
                                                            <span class="font-normal">Jurong West Avenue 18, S123456</span>
                                                        </p>
                                                    </div>';
                                            
                                        }
                                    } else {
                                        echo "No appointment found.";
                                    }
                                ?>
                                

                            </div>

                            <hr class="hr-styles">

                            <!-- General Practitioner section -->
                            <div>
                                <div class="display-flex gap-12">
                                    <img src="../images/icon-doctor.svg" aria-label="doctor icon">
                                    <p>General Practitioner</p>
                                </div>

                                <div class="padding-2"></div>

                                <br>

                                <div class="grid-container-2">

                                    <?php 

                                        $sql = '
                                        SELECT first_name, last_name, specialty, doctor_image 
                                        FROM doctors
                                        WHERE doctor_id ='. $doctor_id .' 
                                        ';


                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($doctor = $result->fetch_assoc()) {

                                                echo '<div class="display-flex">
                                                        <img src="../images/'.$doctor['doctor_image'].'" aria-label="doctor profile picture" width="36%" style="justify-content: center;">
                                                    </div>
                                                    
                                                    <div>
                                                        <p class="line-height-24">
                                                            <span class="h4 text-darkblue font-bold"> Dr '.$doctor['first_name'].' '.$doctor['last_name'].'</span> <br />
                                                            <span>'.$doctor['specialty'].'</span>
                                                        </p>
                                                    </div>';

                                            }
                                        } else {
                                            echo "No doctor found.";
                                        }

                                    ?>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
            

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

        <?php 
        $conn->close();
        ?>

    </body>

</html>