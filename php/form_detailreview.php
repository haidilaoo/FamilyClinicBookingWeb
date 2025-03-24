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
        <!-- added navbar here -->
    <?php include "../php snippets/navbar.php"; ?>
    <div class="container">
        <?php
            include "../php snippets/dbconnect.php";

            session_start();

            $doctor_id=0;
            $app_date ='';
            $app_time ='';
            // $email = 'alice.tan@example.com';
            $email = $_SESSION['valid_user'];

            if (isset($_POST['weekly-date-selector']) && isset($_POST['timing-selector'])) {
                $doctor_id = $_SESSION['selected_doctor'];
                $app_date = $_POST['weekly-date-selector'];
                $app_time = $_POST['timing-selector'];
                $_SESSION['app_date'] = $app_date;
                $_SESSION['app_time'] = $app_time;
            }
        ?>
        <section style="margin-top: 60px;">
            <div class="two-section">
                <div class="left-col">
                    <ol class="stepper">
                        <li class="stepper-done"></li>
                        <li class="stepper-active"></li>
                        <li></li>
                     </ol>
                    <h2>Review your details</h2>

                    <p class="text-mediumgrey">Here are the available slots for:</p>

                    <div class="card display-flex gap-24">

                    <?php 

                        $sql = '
                        SELECT first_name, last_name, specialty, doctor_image 
                        FROM doctors
                        WHERE doctor_id ='. $doctor_id .' 
                        ';


                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($doctor = $result->fetch_assoc()) {
                                
                                echo '<img src="../images/'.$doctor['doctor_image'].'" aria-label="doctor profile picture">
                    
                                <div>
                                    <p>
                                        <span class="h4 text-darkblue font-bold"> Dr '.$doctor['first_name'].' '.$doctor['last_name'].'</span> <br />
                                        <span class="text-mediumgrey">'.$doctor['specialty'].'</span>
                                    </p>
                                </div>';

                            }
                        } else {
                            echo "No doctor found.";
                        }

                    ?>
                        
                    </div>

                    <br>

                    <div class="card">
                        <div class="display-flex gap-12">
                            <img src="../images/icon-schedule.svg" aria-label="doctor profile picture">
                            <p >Appointment Details</p>
                        </div>

                        <div class="padding-2"></div>

                        <div class="grid-container-2">
                            <p style="margin-top:0;">When</p>
                            <p class="font-bold text-mediumgrey" style="margin-top:0;">
                                <span> <?php echo date('d F Y',strtotime($app_date)) ?></span> <br/>
                                <span><?php echo $app_time ?></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="right-col">
                    <div class="right-col-width">
                        <form id="form-reviewdetails" method="POST" action="../php/form_bookingsuccess.php">
                            <h4 class="font-bold">Patient Details</h4>

                            <?php
                                $sql = '
                                SELECT first_name, last_name, email, NRIC 
                                FROM patients
                                WHERE email ="'. $email .'"
                                ';
    
    
                                $result = $conn->query($sql);
    
                                if ($result->num_rows > 0) {
                                    while ($patient = $result->fetch_assoc()) {
                                        
                                        echo '<label for="fullname"><p>Full Name</p></label>
                                            <input type="text" id="fullname" name="fullname" class="input-style input-readonly" placeholder="Your full name" value="'.$patient['first_name'].' '.$patient['last_name'].'" readonly>
                                            <label for="form-NRIC"><p>NRIC/FIN</p></label>
                                            <input type="text" class="input-style input-readonly" id="form-NRIC" name="form-NRIC" value="'.$patient['NRIC'].'" readonly>
                                            <label for="form-email"><p>Contact Email</p></label>
                                            <p class="small-text text-mediumgrey">Appointment confirmation and reminders will be sent to this email.</p>
                                            <input type="text" id="form-email" name="form-email" class="input-style input-readonly" value="'.$patient['email'].'" readonly>';
    
                                    }
                                } else {
                                    echo "No patient found.";
                                }
                            ?>
    
                            
    
                            <hr class="hr-styles">
    
                            <h4>Appointment Type</h4>
                            
                            <div>
                                <select class="input-style" name='form-app-type' required placeholder="Select type of consultation">
                                    <option disabled selected hidden value="">Select type of consultation</option>
                                    <option value="General Consultation">General Consultation</option>
                                    <option value="Health Screening & Preventive Care">Health Screening & Preventive Care (routine check-ups, vaccinations, screenings)</option>
                                    <option value="Chronic Disease Management">Chronic Disease Management (for ongoing conditions like diabetes, hypertension)</option>
                                    <option value="Minor Procedures">Minor Procedures (wound care, dressing changes, injections)</option>
                                </select>
                            </div>
                            
                            <div class="display-flex align-items-right">
                                <input type="submit" class="btn-blue-sm btn-margintop">
                            </div>
                            
                            <div class="padding-2"></div>
                        </form>
                    </div>
                </div>
            </div>
            
            
        </section>
        <!-- <div class="padding-2"></div> -->
        </div>
        <?php 
        $conn->close();
        ?>

    </body>


</html>