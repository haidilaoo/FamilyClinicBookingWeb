<?php
include "../php snippets/dbconnect.php";
// include "login.php";

if (!isset($_SESSION)) //check if session is available aka if user has logged in yet -> by right shldnt be able to access
    session_start(); // Start the session to access session variables 
var_dump($_SESSION); //shows no variables stored in $_SESSION array
$id = session_id();

//set variables from session
$email = $_SESSION['valid_user'];

// Fetch patient details from the database
if (isset($_SESSION['valid_user'])) {

    //for testing if session works with nric query
    // $query = "SELECT nric FROM patients WHERE email = '$email' ";
    // $result = $conn->query($query);

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

        // Output each variable as a JavaScript variable
        echo "<script>
        var nric = '$nric';
        var firstname = '$firstname';
        var lastname = '$lastname';
        var password = '$password';
        var email = '$email';
        </script>";


        // if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //     $inputpassword = $_POST['password'];
        //     $inputPasswordHashed = md5($inputpassword);

        //     // echo "<script>
        //     // var hashed_inputPassword = '$inputPasswordHashed'; 
        //     // </script>";

        //     // Compare the hashed input password with the stored password
        //     if ($inputPasswordHashed === $password) {
        //         echo "success"; // Password matches
        //     } else {
        //         echo "error . $inputpassword "; // Password does not match
        //     }
        // }

    } else {
        // If no result, output an error message in JavaScript
        echo "<script>console.error('User details not found');</script>";
    }
} else {
    echo "<script>console.error('User is not logged in');</script>";
}

$securequery->close();

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $inputpassword = $_POST['password'];
//     $inputPasswordHashed = md5($inputpassword);

//     echo "<script>
//     var hashed_inputPassword = '$inputpassword'; 
//     </script>";

//     // Compare the hashed input password with the stored password
//     if ($inputPasswordHashed === $password) {
//         echo "success"; // Password matches
//     } else {
//         echo "error . $inputpassword "; // Password does not match
//     }
// }

$conn->close();

?>

<a href="appointments.php">appointments</a>

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

<body class="container">
    <section style="margin-top: 100px">
        <form method="POST" action="../php/settings.php" onsubmit="return validateSignupForm(false);">
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
                                <input type="text" id="firstname" class="input-style" placeholder="John">
                                <span class="error" id="firstnameError"></span>
                            </div>
                            <div class="flex-grow-shrink">
                                <label for="lastname">
                                    <p>Last name</p>
                                </label>
                                <input type="text" id="lastname" class="input-style" placeholder="Tan">
                                <span class="error" id="lastnameError"></span>
                            </div>
                        </div>
                        <div class="flex-grow-shrink">
                            <label for="form-NRIC">
                                <p>NRIC/FIN</p>
                            </label>
                            <input type="text" class="input-style" id="form-NRIC" placeholder="S1234567A">
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
                            <input type="text" id="form-email" class="input-style" placeholder="Johntan@gmail.com">
                            <span class="error" id="emailError"></span>
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
                                <input type="text" id="inputpassword" class="input-style" name="password" placeholder="********">
                                <span class="error" id="inputpasswordError"></span>
                                <?php

                                if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
                                   
                                    echo "<script>
                                    return validateSignupForm(false);
                                    </script>
                                    ";

                                    $inputpassword = $_POST['password'];
                                    $inputPasswordHashed = md5($inputpassword);

                                    echo "<script>
                                    var inputpassword = '$inputpassword'; 
                                    </script>";

                                    // Compare the hashed input password with the stored password
                                    if ($inputPasswordHashed === $password) {
                                        // echo "success"; // Password 
                                        echo "<script>
                                        // document.getElementById('inputpasswordError').innerText = 'Correct password entered.' ;
                                        document.getElementById('inputpassword').value = inputpassword ;
                                        </script> ";

                                        $newpassword = $_POST['newpassword'];
                                        $newpasswordHashed = md5($newpassword);
                                        if ($newpassword) {
                                            echo "<script>
                                            newpassword = document.getElementById('newpassword').value;
                                            checkPassword(newpassword);
                                            </script>" ;
                                        }

                                    } else {
                                        // echo "error . $inputpassword "; // Password does not match
                                        echo "<script>
                                        document.getElementById('inputpasswordError').innerText = 'Wrong password entered.' ;
                                        </script> ";
                                    }
                                }

                                ?>
                            </div>
                            <div class="flex-grow-shrink">
                                <label for="confirm-password">
                                    <p>New password</p>
                                </label>
                                <input type="text" id="password" name="newpassword" class="input-style" placeholder="********">
                                <span class="error" id="passwordError"></span>
                            </div>
                        </div>
                    </div>
                    <div class="display-flex align-items-right">
                        <input type="submit" id="btn-save" class="btn-blue-sm" value="Save changes">
                    </div>
                </div>
            </div>
            <div style="margin-bottom: 100px;"></div>
        </form>
    </section>
</body>
<script src="../signup.js"></script>

</html>