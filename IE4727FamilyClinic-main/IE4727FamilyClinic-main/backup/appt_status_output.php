<?php
                // if (isset($_SESSION['valid_user'])) {
                    $query = "SELECT 
                    a.appointment_id,   
                    a.appointment_type,
                    a.appointment_time,
                    a.appointment_date,
                    d.doctor_id AS doctor_id,
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
                // }

                // Add additional conditions based on the selected status
                if ($status === 'past') {
                    $query .= " AND a.appointment_date < NOW()"; // Fetch past appointments
                } else if ($status === 'cancelled') {
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
                        $doctor_id = htmlspecialchars($row['doctor_id']);
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

                        // Output the day and formatted date
                        // echo "<br>The appointment falls on a $day_of_week. <br>";
                        // echo "Formatted date: $formatted_date <br>";
                        // echo "Formatted time: $formatted_time <br>";

                        $doctor_full_name = $doctor_first_name . " " . $doctor_last_name;

                ?>
                        <div class="display-flex card" style="justify-content: space-between;">
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
                                    <img src="../images/doctor-profilepic.svg" width="32px">
                                    <p>Dr
                                        <?php
                                        echo $doctor_full_name;
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <form id="reschedule-form" action="reschedule_appt.php" method="POST">
                                <input type="hidden" name="appointment_id" id="hidden-appointment-id">
                            </form>
                            <div class="display-flex gap-16">
                                <button class="btn-blue-sm" onclick="openNav(this)" data-appointment-id="<?php echo $appointment_id; ?>">Reschedule</button>
                                <img src="../images/icon-bin.svg" width="24px">
                            </div>
                            
                        </div>

                <?php
                    }
                } else {
                    echo "No appointments found.";
                }

                ?>

                   <!-- Conditionally Render UI Elements Based on Status
                   <?php if ($status === 'Scheduled') : ?>
                            <!-- UI elements specific to upcoming/scheduled appointments -->
                                <form id="reschedule-form" action="reschedule_appt.php" method="POST">
                                    <input type="hidden" name="appointment_id" id="hidden-appointment-id">
                                </form>
                                <div class="display-flex gap-16">
                                    <button class="btn-blue-sm" onclick="openNav(this)" data-appointment-id="<?php echo $appointment_id; ?>">Reschedule</button>
                                    <img src="../images/icon-bin.svg" width="24px">
                                </div>
                            <?php elseif ($status === 'Past') : ?>
                            <!-- UI elements specific to past appointments -->
                                <p>This appointment has concluded.</p>
                            <?php elseif ($status === 'Cancelled') : ?>
                            <!-- UI elements specific to cancelled appointments -->
                                <p>This appointment was cancelled.</p>
                            <?php endif; ?> 