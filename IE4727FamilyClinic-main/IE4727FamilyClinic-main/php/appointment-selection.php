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
        <section style="margin-top: 100px;">
            <div style="text-align: center;">
                <h2 style="margin:0;">Book an Appointment</h2>
                <p>Your health and convenience are our priority—schedule your appointment today!</p>
            </div>

            <div class="padding-2"></div>

            <div class="display-flex gap-24">
                <div class="card grid-container-2 gap-20 align-items-center " style="width:100%; padding: 3%;">
                    <img src="../images/app-singledoctor.svg">
                    <div>
                        <h3>Option 1: Choose Your Preferred Doctor</h3>
                        <br>
                        <p>Select a doctor you trust or have previously visited to ensure your appointment meets your specific needs and comfort.</p>
                        <br>
                        <a href="../php/ourdoctors.php">
                            <button class="btn-blue-lg">Select GP</button>
                        </a>
                    </div>
                </div>
                <div class="card grid-container-2 gap-20 align-items-center " style="width:100%; padding: 3%;">
                    <img src="../images/app-twodoctors.svg">
                    <div>
                        <h3>Option 2: Choose Any Available Doctor</h3>
                        <br>
                        <p>If you don’t have a particular doctor in mind, simply choose this option, and you’ll be matched with an available doctor for your appointment today.</p>
                        <br>

                        <?php
                            include "../php snippets/dbconnect.php";

                            $sql = 'SELECT doctor_id 
                                    FROM appointment_slots
                                    WHERE appointment_date = CURDATE() 
                                    AND is_available = TRUE
                                    ORDER BY RAND()
                                    LIMIT 1;';
                
                            $result = $conn->query($sql);

                            $isDisabled = false;
                            
                
                            if ($result->num_rows > 0) {
                                $isDisabled = false;
                                
                            } else {
                                // echo 'no doctor assigned';
                                $isDisabled = true;
                            }
                            $disableButton = $isDisabled ? 'Disabled' : '';

                            echo '<form method="POST" id="app-selection-form" action="../php/form_datetime.php">
                                    <input type="hidden" id="random-doctor-selection" name="random-doctor-selection" value="random">
                                    <button type="submit" class="btn-blue-lg"'.$disableButton.'>Assign the next available doctor</button>
                                </form>'
                        ?>
                        
                    </div>
                </div>
                
            </div>
            
        </section>
        <div>

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