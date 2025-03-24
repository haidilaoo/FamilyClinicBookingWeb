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

    $doctor_id = 0;

    if(isset($_POST['random-doctor-selection'])) {
        if($_POST['random-doctor-selection'] == 'random') {

            $sql = 'SELECT doctor_id 
                    FROM appointment_slots
                    WHERE appointment_date = CURDATE() 
                    AND is_available = TRUE
                    ORDER BY RAND()
                    LIMIT 1;';

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($doctor = $result->fetch_assoc()) {
                    $doctor_id = $doctor['doctor_id'];
                    $_SESSION['selected_doctor'] = $doctor_id;

                    echo $doctor_id;
                }
            } else {
                echo 'no doctor assigned';
            }
        }
    }

    if (isset($_POST['doctor_id'])) {

        $_SESSION['selected_doctor'] = $_POST['doctor_id'];
        $doctor_id = $_SESSION['selected_doctor'];
    }

    ?>
    
    <section style="margin-top: 60px;">
        <div class="two-section">
            <div class="left-col">
                <ol class="stepper">
                    <li class="stepper-active"></li>
                    <li></li>
                    <li></li>
                </ol>
                <h2>Choose a date & time slot</h2>

                <p class="text-mediumgrey">Here are the available slots for:</p>

                <br>

                <div class="card display-flex gap-24">

                    <?php

                    $sql = '
                            SELECT first_name, last_name, specialty, doctor_image 
                            FROM doctors
                            WHERE doctor_id =' . $doctor_id . ' 
                            ';


                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($doctor = $result->fetch_assoc()) {

                            echo '<img src="../images/' . $doctor['doctor_image'] . '" aria-label="doctor profile picture">
                      
                                    <div>
                                        <p>
                                            <span class="h4 text-darkblue font-bold"> Dr ' . $doctor['first_name'] . ' ' . $doctor['last_name'] . '</span> <br />
                                            <span class="text-mediumgrey">' . $doctor['specialty'] . '</span>
                                        </p>
                                    </div>';
                        }
                    } else {
                        echo "No doctor found.";
                    }

                    ?>


                </div>
            </div>


            <div class="right-col">
                <div class="right-col-width">
                    <form method="POST" id="date-time-form" action="../php/form_detailreview.php">

                        <?php

                        // Calculate the date range for the next two months
                        // $currentDate = date("Y-m-d");
                        // $endDate = date("Y-m-d", strtotime("+2 months"));

                        $currentDate = new DateTime();

                        // Set the timezone to Singapore
                        $singaporeTimeZone = new DateTimeZone('Asia/Singapore');
                        $currentDate->setTimezone($singaporeTimeZone);

                        $endDate = new DateTime();
                        $endDate->modify('+2 months');

                        

                        // Prepare an array to hold all the dates and unique months
                        // $allDates = [];
                        $datesByMonth = [];
                        $period = new DatePeriod($currentDate, new DateInterval('P1D'), $endDate->modify('+1 day'));

                        foreach ($period as $date) {
                            $monthYear = $date->format('F Y');
                            $datesByMonth[$monthYear][] = $date->format('Y-m-d');
                        }

                        $currentDate = $currentDate->format('Y-m-d');
                        $endDate = $endDate->format('Y-m-d');

                        $sql = '
                                        SELECT DISTINCT appointment_date 
                                        FROM Appointment_Slots 
                                        WHERE doctor_id =' . $doctor_id . '  
                                        AND is_available = TRUE 
                                        AND appointment_date BETWEEN "' . $currentDate . '" AND "' . $endDate . '"';


                        $result = $conn->query($sql);

                        // Create an array of available appointment dates
                        $availableDates = [];

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $availableDates[] = $row['appointment_date'];
                            }
                        } else {
                            echo "No slots found.";
                        }

                        echo '
                                    <div class="display-flex" style="justify-content: space-between;">
                                        <div style="width:30%;">
                                            <select name="month" id="month-select" class="input-style" onchange="filterDates()">
                                    ';

                        // Populate the dropdown with the next two months
                        foreach ($datesByMonth as $monthYear => $dates) {
                            echo '<option value="' . $monthYear . '">' . $monthYear . '</option>';
                        }

                        echo '
                                            </select>
                                        </div>
                                        <div>
                                            <button id="prev-btn" class="carousel-btn">&lsaquo;</button>
                                            <button id="next-btn" class="carousel-btn">&rsaquo;</button>
                                        </div>
                                    </div>
                                    <div class="padding-2"></div>
                                ';

                        echo '
                                    <div class="carousel">
                                        <div class="weekly-date-selector" role="radiogroup">';

                        $firstAvailableSelected = false; //Check if first radio button is selected

                        foreach ($datesByMonth as $monthYear => $dates) {

                            foreach ($dates as $date) {
                                $isAvailable = in_array($date, $availableDates);
                                $disabledAttribute = $isAvailable ? '' : 'disabled';
                                $day = date('d', strtotime($date));
                                $dayName = date('D', strtotime($date));
                                $dateStr = date('dmY', strtotime($date));
                                

                                // Only apply 'checked' to the first available date
                                $checkedAttribute = ($isAvailable && !$firstAvailableSelected) ? 'checked' : '';

                                 // If this is the first available date, add the class
                                $selectedClass = ($isAvailable && !$firstAvailableSelected) ? 'date-group-selected' : '';
                                
                                // Set the flag if the current date is available and the first one to be marked
                                if ($isAvailable && !$firstAvailableSelected) {
                                    $firstAvailableSelected = true;
                                }

                                echo "<div class='date-group $selectedClass' data-month='$monthYear'>"; // Each month has a data-month attribute
                                
                                echo "<input type='radio' name='weekly-date-selector' id='d$date' value='$date' $disabledAttribute $checkedAttribute onClick=\"showAppSlot('$date')\"/>";
                                echo "<label class='weekly-date-item' for='d$date'>
                                                <div class='weekly-day'>$dayName</div>
                                                <div class='weekly-date'>$day</div>
                                              </label>";
                                echo '</div>';
                            }
                        }
                        echo '</div></div>';



                        // Timeslot

                        $sqlTimeslots = "
                                    SELECT appointment_date, start_time, end_time 
                                    FROM Appointment_Slots 
                                    WHERE doctor_id = $doctor_id 
                                    AND is_available = TRUE 
                                    AND appointment_date IN ('" . implode("','", $availableDates) . "')
                                    ORDER BY appointment_date, start_time";

                        $resultTimeslots = $conn->query($sqlTimeslots);
                        echo "<script>var timeslotsData = {}; </script>";

                        if ($resultTimeslots->num_rows > 0) {
                            while ($row = $resultTimeslots->fetch_assoc()) {
                                $date = $row['appointment_date'];
                                $start = date('h:i A', strtotime($row['start_time']));
                                $end = date('h:i A', strtotime($row['end_time']));

                                // Store timeslots under each date
                                // $timeslotsData[$date][] = "$start - $end";

                                echo "<script>
                                        if (!timeslotsData[\"$date\"]) {
                                            timeslotsData[\"$date\"] = []; // Initialize an array for the date if it doesn't exist
                                        }
                                        timeslotsData[\"$date\"].push(\"$start\"); // Push the timeslot to the date's array
                                      </script>";
                            }
                        }



                        ?>

                        <div class="padding-2"></div>
                        <div class="padding-2"></div>

                        <p class="text-mediumgrey">Select Time</p>
                        <br>
                        <div class="timing-selector" role="radiogroup" id="timing-selector"></div>

                        <div class="display-flex align-items-right">
                            <input type="submit" class="btn-blue-sm btn-margintop" id="form-submit-datetime">
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

<script>
    // JavaScript function to filter dates based on the selected month
    var countGroup = 0;

    function filterDates() {
        var selectedMonth = document.getElementById("month-select").value;
        var dateGroups = document.querySelectorAll(".date-group");

        dateGroups.forEach(function(group) {
            if (group.getAttribute("data-month") === selectedMonth) {
                group.style.display = "block";
            } else {
                group.style.display = "none";
                countGroup += 1;
            }
        });
    }

    // Initialize by showing the dates for the first month in the dropdown
    // window.onload = filterDates;

    //--------------------------

    const carousel = document.querySelector('.weekly-date-selector');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');

    let currentIndex = 0;
    const itemWidth = getItemWidth(); // Fixed width per item including gap
    const totalItems = (carousel.children.length - countGroup) / 2; // Total number of items divided by 2 to prevent duplicates
    const itemsPerView = Math.floor(document.querySelector('.carousel').offsetWidth / itemWidth);
    const maxIndex = totalItems - itemsPerView; // Maximum index we can scroll to

    // Get the width of a single item, including the gap
    function getItemWidth() {
        // return carousel.querySelector('.weekly-date-item').offsetWidth + 16; // Adding 16px gap
        return carousel.querySelector('.date-group').offsetWidth + 16; // Adding 16px gap
    }

    // Update the carousel position based on the current index
    function updateCarousel() {
        const transformDistance = -currentIndex * itemWidth;
        carousel.style.transform = `translateX(${transformDistance}px)`;
    }

    // Next button functionality
    nextBtn.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent form submission

        // Only scroll if we are not at the maximum index
        if (currentIndex < maxIndex) {
            currentIndex = Math.min(currentIndex + itemsPerView, maxIndex);
            updateCarousel();
        }
    });

    // Previous button functionality
    prevBtn.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent form submission

        currentIndex = Math.max(currentIndex - itemsPerView, 0);
        updateCarousel();
    });


    window.onload = function() {

        // Initialize by showing the dates for the first month in the dropdown
        filterDates();
        // Find the first checked radio button
        const checkedRadioButton = document.querySelector('input[name="weekly-date-selector"]:checked');
        
        // If there is a checked radio button, call showAppSlot with its value
        if (checkedRadioButton) {
            showAppSlot(checkedRadioButton.value);
        }

    };

    

    function showAppSlot(date) {


        const timeContainer = document.getElementById('timing-selector');
        timeContainer.innerHTML = '';
        const dateRadio = document.getElementById('d' + date);

        // Remove the selected class from all date groups
        document.querySelectorAll('.date-group').forEach(group => {
            group.classList.remove('date-group-selected');
        });

        // Add the selected class to the parent date-group of the checked radio
        if (dateRadio.checked) {
            dateRadio.closest('.date-group').classList.add('date-group-selected');
        }


        for (var i = 0; i < timeslotsData[date].length; i++) {
            var newRadioHTML = '<input type="radio" name="timing-selector" id="t' + timeslotsData[date][i] + '" value="' + timeslotsData[date][i] + '" required>';
            newRadioHTML += '<label class="timing-item" for="t' + timeslotsData[date][i] + '">' + timeslotsData[date][i] + '</label>'

            // Append the new HTML to the time container
            timeContainer.innerHTML += newRadioHTML;
        }

        // Debug log to check if the function works
        console.log('Date radio is checked, adding time slot.');

    }




</script>


</html>