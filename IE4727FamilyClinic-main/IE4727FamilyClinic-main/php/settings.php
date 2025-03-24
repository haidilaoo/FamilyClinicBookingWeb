<?php
include "../php snippets/dbconnect.php";
include "../php snippets/redirect.php";
// include "login.php";

if (!isset($_SESSION)) //check if session is available aka if user has logged in yet -> by right shldnt be able to access
    session_start(); // Start the session to access session variables 
// var_dump($_SESSION); //shows no variables stored in $_SESSION array
$id = session_id();


//set variables from session
$email = $_SESSION['valid_user'];
$currentEmail = $_SESSION['valid_user'];

// Fetch patient details from the database
if (isset($_SESSION['valid_user'])) {

    //for testing if session works with nric query
    // $query = "SELECT nric FROM patients WHERE email = '$email' ";
    // $result = $conn->query($query);

    echo "<script>var isPasswordCorrect = '" . (isset($passwordCorrect) ? $passwordCorrect : "0") . "';</script>";


    $query = "SELECT first_name, last_name, email, patient_password, NRIC FROM patients WHERE email = ?";
    $securequery = $conn->prepare($query); // Prepare the query
    $securequery->bind_param("s", $email); // Bind the email parameter securely
    $securequery->execute(); // Execute the query
    $result = $securequery->get_result(); // Get the result

    if ($result->num_rows > 0) {
        // Fetch the data from the query result
        $row = $result->fetch_assoc(); //keys correspond to name of column

        //set variables
        $nric = htmlspecialchars($row['NRIC']);
        $firstname = htmlspecialchars($row['first_name']);
        $lastname = htmlspecialchars($row['last_name']);
        $password = htmlspecialchars($row['patient_password']);
        $email = htmlspecialchars($row['email']);


        //set variables from form
        // $new_nric = htmlspecialchars($row['NRIC']);
        // $new_firstname = htmlspecialchars($row['first_name']);
        // $new_lastname = htmlspecialchars($row['last_name']);;
        // $new_email = htmlspecialchars($row['email']);

        $newpassword = "";

        // Output each variable as a JavaScript variable
        echo "<script>
        var nric = '$nric';
        var firstname = '$firstname';
        var lastname = '$lastname';
        var password = '$password';
        var email = '$email';
        </script>";
    } else {
        // If no result, output an error message in JavaScript
        echo "<script>console.error('User details not found');</script>";
    }
} else {
    echo "<script>console.error('User is not logged in');</script>";
}

$securequery->close();
// $conn->close();
?>

<!-- <a href="appointments.php">appointments</a> -->

<!-- throw extracted data from database into javascript -->
<script type="text/javascript">
    // var nric = "<?php echo $nric; ?>" //need put quotations marks if is string
    console.log("nric is " + nric);
    console.log("first name is " + firstname);
    console.log("last name is " + lastname);
    console.log("password is " + password);
    console.log("email is " + email);
</script>


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
            <form id="settingsForm" method="POST" action="../php/settings.php" onsubmit="return validateSignupForm();">
                <div id="settings-layout">
                    <div id="settings-content" class="flex-direction-column gap-32">
                        <h2 class="h2-no-margin">Settings</h2>
                        <div class="settings-card display-flex flex-direction-column gap-16">
                            <h4 class="font-bold margin-0">Profile information</h4>
                            <div class="display-flex gap-16 flex-grow-shrink">
                                <div class="flex-grow-shrink">
                                    <label for="firstname">
                                        <p>First name</p>
                                    </label>
                                    <input type="text" id="firstname" name="firstname" class="input-style" placeholder="John">
                                    <span class="error" id="firstnameError"></span>
                                </div>
                                <div class="flex-grow-shrink">
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
                                <input type="text" class="input-style input-readonly" name="nric" id="form-NRIC" placeholder="S1234567A" readonly>
                                <span class="error" id="nricError"></span>
                            </div>

                        </div>
                        <div class="settings-card display-flex flex-direction-column gap-16 ">
                            <div>
                                <h4 class="font-bold margin-0" style="margin-bottom: 8px;">Contact email</h4>
                                <p class="small-text text-mediumgrey">Appointment confirmation and reminders will be sent to
                                    this email.</p>
                            </div>
                            <div class="flex-grow-shrink">
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
                        </div>
                        <div class="settings-card display-flex flex-direction-column gap-16 ">
                            <div>
                                <h4 class="font-bold margin-0" style="margin-bottom: 8px;">Password</h4>
                                <p class="small-text text-mediumgrey">Modify your current password.</p>
                            </div>
                            <div class="display-flex gap-16 flex-grow-shrink">
                                <div class="flex-grow-shrink">
                                    <label for="password">
                                        <p>Current password</p>
                                    </label>
                                    <input type="password" id="inputpassword" name="inputpassword" class="input-style" placeholder="********">
                                    <?php
                                    $inputpassword = "";
                                    ?>
                                    <span class="error" id="inputpasswordError"></span>
                                    <?php

                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {

                                        // echo "<script>
                                        // document.getElementById('inputpassword').disabled = false;
                                        // </script>";

                                        $inputpassword = $_POST['inputpassword'];

                                        if (!empty($inputpassword)) {
                                            $inputPasswordHashed = md5($inputpassword);

                                            echo "<script>
                                        var inputpassword = '$inputpassword'; 
                                        </script>";



                                            // Compare the hashed input password with the stored password
                                            if ($inputPasswordHashed === $password) {
                                                // echo "success"; // Password 
                                                echo "<script>
                                            var isPasswordCorrect = '1';
                                        </script>";

                                                echo "<script>
                                        document.getElementById('inputpasswordError').innerText = 'Correct password entered. Please enter your new password.' ;
                                        document.getElementById('inputpassword').value = inputpassword ;
                                        // document.getElementById('inputpassword').disabled = true;
                                        document.getElementById('
                                        </script> ";

                                                if (isset($_POST['newpassword']) && !empty($_POST['newpassword'])) {

                                                    $newpassword = $_POST['newpassword'];
                                                    //js validation to check if new password fits criteria before encrpyting
                                                    $newpasswordHashed = md5($newpassword);
                                                    echo "$newpassword";

                                                    //set variables from form
                                                    $nric = $_POST['nric'];
                                                    $firstname = $_POST['firstname'];
                                                    $lastname = $_POST['lastname'];
                                                    $email = $_POST['email'];

                                                    var_dump($_POST);

                                                    $valid_user_email = $_SESSION['valid_user'];

                                                    //update data into database
                                                    $updatequery = "UPDATE patients 
                                                SET first_name = '$firstname', 
                                                    last_name = '$lastname', 
                                                    email = '$email', 
                                                    patient_password = '$newpasswordHashed' ,
                                                    NRIC = '$nric'
                                                WHERE email = '$valid_user_email'";


                                                    $updateresult = $conn->query($updatequery);

                                                    if (!$updateresult) {
                                                        echo "Your query failed.";
                                                    } else {
                                                        echo "updated database with new information.";
                                                        $_SESSION['logged_in'] = true;
                                                        $_SESSION['valid_email'] = $email;
                                                        $_SESSION['valid_user'] = $email;
                                                        unset($_SESSION['username']);
                                                        $_SESSION['username'] = "$firstname $lastname";
                                                        echo '<script type="text/javascript">
                                                        window.location.href="' . $_SERVER['PHP_SELF'] . '";
                                                        </script>';
                                                exit();
                                                        //clear messages and password input
                                                        echo "<script>
                                                    document.getElementById('inputpassword').value = '';
                                                   document.getElementById('inputpasswordError').innerText = '';
                                                   <script>";

                                                        // Set session variables if signup is successful
                                                        $_SESSION['logged_in'] = true;
                                                        $_SESSION['valid_email'] = $email;
                                                        $_SESSION['valid_user'] = $email;
                                                        unset($_SESSION['username']);
                                                        $_SESSION['username'] = "$firstname $lastname";
                                                        // Redirect to previous page
                                                        // redirectToPreviousPage();

                                                        //re declare newly updated data 
                                                        // $email = $_SESSION['valid_user'];
                                                        $query = "SELECT first_name, last_name, email, patient_password, NRIC FROM patients WHERE email = ?";
                                                        $securequery = $conn->prepare($query); // Prepare the query
                                                        $securequery->bind_param("s", $email); // Bind the email parameter securely
                                                        $securequery->execute(); // Execute the query
                                                        $result = $securequery->get_result(); // Get the result

                                                        if ($result->num_rows > 0) {
                                                            // Fetch the data from the query result
                                                            $row = $result->fetch_assoc(); //keys correspond to name of column

                                                            //set variables
                                                            $nric = htmlspecialchars($row['NRIC']);
                                                            $firstname = htmlspecialchars($row['first_name']);
                                                            $lastname = htmlspecialchars($row['last_name']);
                                                            $password = htmlspecialchars($row['patient_password']);
                                                            $email = htmlspecialchars($row['email']);

                                                            unset($_SESSION['username']);
                                                            $_SESSION['username'] = "$firstname $lastname";

                                                            $newpassword = "";

                                                            // Output each variable as a JavaScript variable
                                                            echo "<script>
                                                        var nric = '$nric';
                                                        var firstname = '$firstname';
                                                        var lastname = '$lastname';
                                                        var password = '$password';
                                                        var email = '$email';
                                                        </script>";
                                                        }
                                                    }
                                                }
                                            } else {
                                                // echo "error . $inputpassword "; // Password does not match
                                                echo "<script>
                                            var isPasswordCorrect = '0';
                                            document.getElementById('inputpasswordError').innerText = 'Wrong password entered.' ;
                                            </script> ";

                                                include "../php snippets/fetchpatientdetails.php";
                                            }
                                        } else {
                                            // Optionally clear any error text if inputpassword is empty
                                            echo "<script>
                                        document.getElementById('inputpasswordError').innerText = '';
                                        </script>";

                                            //WHEN PASSWORD INPUT EMPTY
                                            //update database with other inputs 

                                            //set variables from form
                                            $nric = $_POST['nric'];
                                            $firstname = $_POST['firstname'];
                                            $lastname = $_POST['lastname'];
                                            $email = $_POST['email'];
                                           

                                            // Check if NRIC or email already exists
                                            // $checkduplicateNRIC = "SELECT * FROM patients WHERE NRIC = '$nric'";
                                            $checkduplicateEMAIL = "SELECT * FROM patients WHERE email = '$email' AND email != '$currentEmail'";

                                          
                                            $EMAILresult = $conn->query($checkduplicateEMAIL);

                                            var_dump($_POST);

                                            $valid_user_email = $_SESSION['valid_user'];

                                            if ($EMAILresult->num_rows > 0) {

                                                if (session_status() === PHP_SESSION_NONE) {
                                                    session_start();
                                                }

                                        

                                                if ($EMAILresult->num_rows > 0) {
                                                    $_SESSION['signup_email_error'] = "Email already exists in the system.";
                                                }
                                                echo '<script type="text/javascript">
                                                        window.location.href="' . $_SERVER['PHP_SELF'] . '";
                                                        </script>';
                                                exit();
                                            } else {

                                                //update data into database
                                                $updatequery = "UPDATE patients 
                                              SET first_name = '$firstname', 
                                                  last_name = '$lastname', 
                                                  email = '$email', 
                                                  NRIC = '$nric'
                                              WHERE email = '$valid_user_email'";


                                                $updateresult = $conn->query($updatequery);

                                                if (!$updateresult) {
                                                    echo "Your query failed.";
                                                } else {
                                                    echo "updated database with new information.";
                                                    // Set session variables if signup is successful
                                                    $_SESSION['logged_in'] = true;
                                                    $_SESSION['valid_email'] = $email;
                                                    $_SESSION['valid_user'] = $email;
                                                    unset($_SESSION['username']);
                                                    $_SESSION['username'] = "$firstname $lastname";
                                                    echo '<script type="text/javascript">
                                                    window.location.href="' . $_SERVER['PHP_SELF'] . '";
                                                    </script>';
                                            exit();
                                                    echo "<script>
                                                    document.getElementById('inputpassword').value = '';
                                                    </script>";
                                                    // Set session variables if signup is successful
                                                    $_SESSION['logged_in'] = true;
                                                    $_SESSION['valid_email'] = $email;
                                                    $_SESSION['valid_user'] = $email;
                                                    unset($_SESSION['username']);
                                                    $_SESSION['username'] = "$firstname $lastname";
                                                    // Redirect to previous page
                                                    // redirectToPreviousPage();

                                                    //re declare newly updated data 
                                                    // $email = $_SESSION['valid_user'];
                                                    $query = "SELECT first_name, last_name, email, patient_password, NRIC FROM patients WHERE email = ?";
                                                    $securequery = $conn->prepare($query); // Prepare the query
                                                    $securequery->bind_param("s", $email); // Bind the email parameter securely
                                                    $securequery->execute(); // Execute the query
                                                    $result = $securequery->get_result(); // Get the result

                                                    if ($result->num_rows > 0) {
                                                        // Fetch the data from the query result
                                                        $row = $result->fetch_assoc(); //keys correspond to name of column

                                                        //set variables
                                                        $nric = htmlspecialchars($row['NRIC']);
                                                        $firstname = htmlspecialchars($row['first_name']);
                                                        $lastname = htmlspecialchars($row['last_name']);
                                                        $password = htmlspecialchars($row['patient_password']);
                                                        $email = htmlspecialchars($row['email']);

                                                        $newpassword = "";

                                                        unset($_SESSION['username']);
                                                        $_SESSION['username'] = "$firstname $lastname";

                                                        // Output each variable as a JavaScript variable
                                                        echo "<script>
                                                      var nric = '$nric';
                                                      var firstname = '$firstname';
                                                      var lastname = '$lastname';
                                                      var password = '$password';
                                                      var email = '$email';
                                                      </script>";
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    ?>
                                </div>
                                <div class="flex-grow-shrink" id="newpasswordHTML">
                                    <label for="confirm-password">
                                        <p>New password</p>
                                    </label>
                                    <input type="password" id="newpassword" name=newpassword class="input-style" placeholder="********">
                                    <span class="error" id="passwordError"></span>
                                </div>
                            </div>
                        </div>
                        <div class="display-flex align-items-right">
                            <input id="saveButton" type="submit" id="btn-save" class="btn-blue-sm" value="Save changes" disabled>
                        </div>
                    </div>
                </div>
                <div style="margin-bottom: 100px;"></div>
            </form>
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
<script src="../signup-settings.js"></script>

</html>
<?php
$conn->close();
?>