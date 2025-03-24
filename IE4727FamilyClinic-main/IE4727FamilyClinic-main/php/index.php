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
        <div class="banner-container" style="margin-top: 70px;">
            <img src="../images/homepg-banner.svg" alt="Banner Image" class="banner-image">
            <div class="display-flex flex-direction-column gap-48 text-div" style="margin-left: 135px;">
                <div class="display-flex flex-direction-column gap-16" style="margin-top: 50px;">
                    <h1 style="margin: 0px;">Book your health appointments from home with ease</h1>
                    <p style="margin: 0px;">With our intuitive online booking system, scheduling appointments have never been easier.</p>
                </div>
                <a href="appointment-selection.php"><button class="btn-blue-lg">Book an Appointment</button></a>
            </div>
        </div>
        <section style="margin-top: 100px">
            <div class="display-flex flex-direction-column align-items-center">
                <div style="text-align:center; margin-bottom: 72px">
                    <h1>Book an appointment in</h1>
                    <div class="highlight-text">3 easy steps</div>
                </div>
                <div class="steps-container" style="margin-bottom: 72px;">
                    <div class="steps-card">
                        <div class="steps">
                            <h3>1. Choose a doctor</h3>
                            <p>Choose your preferred doctor or get randomly assigned with any one of our licensed professionals.</p>
                        </div>
                        <img src="../images/homepg-step1.svg">
                    </div>
                    <div class="steps-card">
                    <img src="../images/homepg-step2.svg">
                        <div class="steps">
                            <h3>2. Pick a time slot</h3>
                            <p>See all available slots for each day and pick one that is most convenient for you. No more back and forth calls needed!</p>
                        </div>
                        
                    </div>
                    <div class="steps-card">
                        <div class="steps">
                            <h3>3. Review and confirm</h3>
                            <p>Fill in your personal details and type of consultation. Reschedule your appointments in your Appointments page if needed.</p>
                        </div>
                        <img src="../images/homepg-step3.svg">
                    </div>
                </div>

            </div>

        </section>
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
    </div>
</body>

<script src="../signup-settings.js"></script>

</html>