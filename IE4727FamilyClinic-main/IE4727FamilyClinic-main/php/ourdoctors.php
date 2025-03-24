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
        ?>

        <section style="margin-top: 100px;">

        <h2> Our doctors </h2>

            <div class="two-section">
                <div class="left-col-doc">

                    <form class="search-container" action="" method="POST">
                        <div class="search-btn-div">
                            <input type="search" class="input-search" placeholder="Search.." name="search-name" onChange="this.form.submit()">
                            <button type="submit" class="search-btn"></button>
                        </div>


                        <div class="display-flex gap-8 align-items-center" style="margin: 2% 0 4%;">
                            <p class="small-text">Filter by</p>
                            <div>
                                <select class="input-style" name='search-doc-ava' required onChange="this.form.submit()">
                                    <option disabled selected hidden>Doctor Availability</option>
                                    <option value="1">Available Today</option>
                                    <option value="2">Unavailable Today</option>
                                </select>
                            </div>
                            <div>
                                <select class="input-style" name='search-doc-language' required onChange="this.form.submit()">
                                    <option disabled selected hidden>Language</option>
                                    <option value="">All</option>
                                    <?php
                                    $sql = " SELECT language_name FROM languages";

                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($languages = $result->fetch_assoc()) {
                                            echo '<option value="' . $languages['language_name'] . '">' . $languages['language_name'] . '</option>';
                                        }
                                    }

                                    ?>
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="left-col-doc-card-container">

                    <?php

                    // Capture search parameters
                    $search_name = isset($_POST['search-name']) ? $_POST['search-name'] : '';
                    $search_availability = isset($_POST['search-doc-ava']) ? $_POST['search-doc-ava'] : '';
                    $search_language = isset($_POST['search-doc-language']) ? $_POST['search-doc-language'] : '';


                    $sql = "SELECT d.doctor_id, d.first_name, d.last_name, d.doctor_image, d.specialty, GROUP_CONCAT(DISTINCT l.language_name SEPARATOR ', ') AS languages,d.background, d.qualifications,
                       DATE_FORMAT(earliest_appointment.appointment_date, '%d %b') AS earliest_appointment_date,TIME_FORMAT(earliest_appointment.start_time,'%h:%i %p') AS earliest_start_time, IF(earliest_appointment.is_available AND earliest_appointment.appointment_date = CURDATE(), TRUE, FALSE) AS is_available_today
                       FROM doctors d
                       LEFT JOIN doctor_languages dl ON d.doctor_id = dl.doctor_id
                       LEFT JOIN languages l ON dl.language_id = l.language_id
                       LEFT JOIN(SELECT doctor_id, appointment_date, start_time, is_available
                           FROM appointment_slots
                           WHERE is_available = TRUE AND appointment_date >= CURDATE()
                           GROUP BY doctor_id
                           HAVING appointment_date = MIN(appointment_date)) AS earliest_appointment
                       ON d.doctor_id = earliest_appointment.doctor_id
                       WHERE 1=1";

                    // Append search conditions
                    if (!empty($search_name)) {
                        $sql .= " AND (d.first_name LIKE '%$search_name%' OR d.last_name LIKE '%$search_name%')";
                    }
                    if (!empty($search_availability)) {

                        if ($search_availability == 2) {
                            $search_availability = 0;
                        }

                        $sql .= " AND IF(earliest_appointment.is_available AND earliest_appointment.appointment_date = CURDATE(), TRUE, FALSE) = $search_availability";
                    }
                    if (!empty($search_language)) {
                        $sql .= " AND EXISTS (
                                       SELECT 1
                                       FROM doctor_languages dl2
                                       JOIN languages l2 ON dl2.language_id = l2.language_id
                                       WHERE dl2.doctor_id = d.doctor_id AND l2.language_name = '$search_language'
                                   )";
                    }

                    $sql .= " GROUP BY d.doctor_id;";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($doctor = $result->fetch_assoc()) {

                            echo '<div class="card display-flex gap-24 doctor-card" 
                                 data-id="' . $doctor['doctor_id'] . '"
                                 data-name="' . $doctor['first_name'] . ' ' . $doctor['last_name'] . '"
                                 data-specialty="' . $doctor['specialty'] . '"
                                 data-languages="' . $doctor['languages'] . '"
                                 data-profile="' . $doctor['background'] . '"
                                 data-qualifications="' . $doctor['qualifications'] . '"
                                 data-appdate="' . $doctor['earliest_appointment_date'] . '"
                                 data-starttime="' . $doctor['earliest_start_time'] . '"
                                 data-availability="' . $doctor['is_available_today'] . '"
                                 data-image="' . $doctor['doctor_image'] . '">';

                            echo '<img src="../images/' . $doctor['doctor_image'] . '" aria-label="doctor profile picture">
                                
                                    <div>
                                        <p class="line-height-24">
                                            <span class="h4 text-darkblue font-bold">Dr ' .  $doctor['first_name'] . " " . $doctor['last_name'] . '</span> <br />
                                            <span class="text-mediumgrey">' . $doctor['specialty'] . '</span>
                                        </p>
                                    </div>';

                            echo '<div class="display-flex align-items-center gap-12">';

                            if ($doctor['languages']) {
                                // Split the languages by comma and loop through each one
                                $languages = explode(', ', $doctor['languages']);
                                foreach ($languages as $language) {
                                    echo "<p class='tag-grey'>$language</p>";
                                }
                            }

                            echo '</div>
                                </div><br>';
                        }

                        echo "<script> isExist = true;</script>";
                    } else {

                        echo "No doctors found.";
                        echo "<script> isExist = false;</script>";
                    }
                    ?>
                    </div>
                </div>

                <div class="right-col-doc">
                    <div class="right-col-width-doc">
                        <div class="card" style="padding: 3% 5%;" id="card-doctor-details">
                            <div class="display-flex gap-24">
                                <img src="" aria-label="doctor profile picture" id="doctor-image">

                                <div>
                                    <p class="line-height-24">
                                        <span class="h4 font-semibold" id="doctor-name"> </span> <br />
                                        <span class="text-mediumgrey" id="doctor-specialty"></span>
                                    </p>
                                </div>

                                <div id='doctor-availability'>
                                </div>
                            </div>

                            <div class="padding-2"></div>

                            <!-- timing section  -->
                            <div>
                                <div class="display-flex gap-24">
                                    <img src="../images/icon-schedule.svg" width="24px" padding="24px">
                                    <p>Earliest time slot: <span id="doctor-appdate"></span>, <span id="doctor-starttime"></span></p>
                                </div>
                                <div class="display-flex gap-24">
                                    <img src="../images/icon-language.svg" width="24px" padding="24px">
                                    <p> Languages:</p>
                                    <div class="display-flex align-items-center gap-12" id="doctor-languages">
                                    </div>
                                </div>
                                <div class="display-flex gap-24">
                                    <img src="../images/icon-qualification.svg" width="24px" padding="24px">
                                    <p>Qualifications: <span id="doctor-qualifications"></span></p>
                                </div>
                                <div style="padding: 2%"></div>

                                <form action=<?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?> "../php/form_datetime.php"
                                    <?php else: ?> "../php/login.php"
                                    <?php endif; ?>

                                    method="POST" id="appointment-form">
                                    <button type="submit" class="btn-blue-lg">Book an Appointment</button>

                                    <!-- Hidden inputs to store doctor's data -->
                                    <input type="hidden" name="doctor_id" id="doctor_id">

                                </form>
                            </div>

                            <hr class="hr-styles">

                            <h4>Profile</h4>
                            <p id="doctor-profile"></p>
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

<script>
    // JavaScript to update doctor details on selection

    document.addEventListener('DOMContentLoaded', function() {

        const doctorCards = document.querySelectorAll('.doctor-card');

        if (!isExist) {
            // Update the right column
            document.getElementById('card-doctor-details').innerHTML = " <h3>Oops! No doctor found.</h3><br><p>We couldn't find any doctors that match your search criteria. Please try adjusting your filters or search again.</p>";

            // console.log('isExist else: ' + isExist);
        }

        function displayDoctorDetails(card) {
            // console.log('isExist: ' + isExist);

            if (isExist) {

                console.log('isExist if: ' + isExist);
                // to remove any previously selected card's border
                const currentlySelected = document.querySelector('.doctor-card-selected');
                if (currentlySelected) {
                    currentlySelected.classList.remove('doctor-card-selected');
                }

                // Add border to the selected card
                card.classList.add('doctor-card-selected');

                // Get doctor details from data attributes
                const name = card.getAttribute('data-name');
                const specialty = card.getAttribute('data-specialty');
                const languages = card.getAttribute('data-languages');
                const profile = card.getAttribute('data-profile');
                const image = card.getAttribute('data-image');
                const qualifications = card.getAttribute('data-qualifications');
                const appdate = card.getAttribute('data-appdate');
                const starttime = card.getAttribute('data-starttime');
                const availability = card.getAttribute('data-availability');

                // Update the right column with the selected doctor's information
                document.getElementById('doctor-name').textContent = "Dr " + name;
                document.getElementById('doctor-specialty').textContent = specialty;
                document.getElementById('doctor-profile').textContent = profile;
                document.getElementById('doctor-qualifications').textContent = qualifications;
                document.getElementById('doctor-appdate').textContent = appdate;
                document.getElementById('doctor-starttime').textContent = starttime;
                // document.getElementById('doctor-availability').textContent = availability;
                document.getElementById('doctor-image').src = '../images/' + image;


                // Set hidden input values for the form submission
                document.getElementById('doctor_id').value = card.getAttribute('data-id');


                const availabilityContainer = document.getElementById('doctor-availability');
                availabilityContainer.innerHTML = ''; // Clear existing languages
                if (availability == 1) {
                    const availabilityTag = document.createElement('p');
                    availabilityTag.className = 'small-text tag-green';
                    availabilityTag.innerHTML = '<span style="font-size: 20px; color: #00A369;">●</span> Available Today';

                    availabilityContainer.appendChild(availabilityTag);
                }


                // Populate languages in separate <p> tags
                const languagesContainer = document.getElementById('doctor-languages');
                languagesContainer.innerHTML = ''; // Clear existing languages
                languages.split(', ').forEach(language => {
                    const languageTag = document.createElement('p');
                    languageTag.className = 'tag-grey';
                    languageTag.textContent = language;
                    languagesContainer.appendChild(languageTag);
                });

            }
        };



        // Set default display on load with the first doctor
        if (doctorCards.length > 0) {
            doctorCards[0].classList.add('doctor-card-selected');
            displayDoctorDetails(doctorCards[0]);
        }

        // Add click event listener to each doctor card
        doctorCards.forEach(card => {
            card.addEventListener('click', () => {
                displayDoctorDetails(card);

            });
        });


    });
</script>

</html>