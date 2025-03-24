<?php
//setting up database connection
include "../php snippets/dbconnect.php";
// include "../php snippets/redirect.php";

// Start session to track registration state
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //variables
    $nric = $_POST['nric'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];
    $password = md5($password); //encrpt password
    $email = $_POST['email'];

    // Check if NRIC or email already exists
    $checkduplicateNRIC = "SELECT * FROM patients WHERE NRIC = '$nric'";
    $checkduplicateEMAIL = "SELECT * FROM patients WHERE email = '$email'";
    $NRICresult = $conn->query($checkduplicateNRIC);
    $EMAILresult = $conn->query($checkduplicateEMAIL);

    if ($NRICresult->num_rows > 0 || $EMAILresult->num_rows > 0) {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Set session message
        if ($NRICresult->num_rows > 0) {
            $_SESSION['signup_nric_error'] = "NRIC already exists in the system.";
        }

        if ($EMAILresult->num_rows > 0) {
            $_SESSION['signup_email_error'] = "Email already exists in the system.";
        }
    } else {


        //insert data into database
        $query = "INSERT INTO patients (first_name, last_name, email, NRIC, patient_password) 
        VALUES ('$firstname', '$lastname', '$email', '$nric', '$password')";

        $result = $conn->query($query);

        if (!$result) {
            echo "Your query failed.";
        } else {
            echo "Welcome " . $firstname . " " . $lastname . ". You are now registered";

            // Set session variables if signup is successful
            $_SESSION['logged_in'] = true;
            $_SESSION['user_email'] = $email; //from signup.php
            $_SESSION['valid_user'] = $email; //from login.php

            $_SESSION['username'] = "$firstname $lastname";

            // Redirect back to the main page
            header("Location: index.php");
            exit();
        }
    }
    $conn->close();
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

            <div class="card-registration-layout">
                <form action="../php/signup.php" method="POST" id="signupForm" onsubmit="return validateSignupForm(true);">
                    <div class="card-registration">
                        <div>
                            <h2 class="h2-no-margin">Sign up</h2>
                            <p>
                                Have an account?
                                <a href="../php/login.php">Login</a>
                            </p>
                        </div>
                        <div class="display-flex flex-direction-column gap-24" id="form-registration" style="width: 100%;">
                            <div class="display-flex gap-16 flex-grow-shrink">
                                <div class="flex-grow-shrink" style="max-width: 316.5px;">
                                    <label for="firstname">
                                        <p>First name</p>
                                    </label>
                                    <input type="text" id="firstname" name="firstname" class="input-style" placeholder="John">
                                    <span class="error" id="firstnameError"></span>
                                </div>
                                <div class="flex-grow-shrink" style="max-width: 316.5px;">
                                    <label for="lastname">
                                        <p>Last name</p>
                                    </label>
                                    <input type="text" id="lastname" name="lastname" class="input-style" placeholder="Tan">
                                    <span class="error" id="lastnameError"></span>
                                </div>
                            </div>
                            <div class="flex-grow-shrink">
                                <label for="form-NRIC">
                                    <p>NRIC/FIN</p>
                                </label>
                                <input type="text" class="input-style" id="form-NRIC" name="nric" placeholder="S1234567A">
                                <span class="error" id="nricError">
                                    <?php
                                    if (isset($_SESSION['signup_nric_error'])) {
                                        echo "<script>
                                    //alert('" . $_SESSION['signup_nric_error'] . "');
                                    document.getElementById('nricError').innerText = '" . $_SESSION['signup_nric_error'] . "'; 
                                  </script>";
                                        // Clear the message after displaying it
                                        unset($_SESSION['signup_nric_error']);
                                        // session_destroy();
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="flex-grow-shrink">
                                <label for="form-email">
                                    <p>Contact email</p>
                                </label>
                                <p class="small-text text-mediumgrey">Appointment confirmation and reminders will be sent to
                                    this email.</p>
                                <input type="text" id="form-email" name="email" class="input-style" placeholder="Johntan@gmail.com">
                                <span class="error" id="emailError">
                                    <?php
                                    if (isset($_SESSION['signup_email_error'])) {
                                        echo "<script>
                                    //alert('" . $_SESSION['signup_email_error'] . "');
                                    document.getElementById('emailError').innerText = '" . $_SESSION['signup_email_error'] . "'; 
                                  </script>";
                                        // Clear the message after displaying it
                                        unset($_SESSION['signup_email_error']);
                                        // session_destroy();
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="display-flex gap-16 flex-grow-shrink">
                                <div class="flex-grow-shrink">
                                    <label for="password">
                                        <p>Password</p>
                                    </label>
                                    <input type="password" id="password" name="password" class="input-style" placeholder="********">
                                    <span class="error" id="passwordError"></span>
                                </div>
                                <div class="flex-grow-shrink">
                                    <label for="confirm-password">
                                        <p>Confirm password</p>
                                    </label>
                                    <input type="password" id="confirmpassword" class="input-style" placeholder="********">
                                    <span class="error" id="confirmpasswordError"></span>
                                </div>
                            </div>
                        </div>
                        <input type="submit" class="btn-blue-lg" value="Sign up">
                    </div>

                </form>
            </div>

    </div>
    <div style="margin-bottom: 100px;"></div>
    </section>

    </div>
</body>
<script src="../signup.js"></script>

</html>