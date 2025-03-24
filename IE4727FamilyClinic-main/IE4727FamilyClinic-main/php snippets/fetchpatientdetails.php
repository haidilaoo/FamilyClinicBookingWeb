<?php
// Set session variables if signup is successful
$_SESSION['logged_in'] = true;
// $_SESSION['valid_user'] = $email;
$email = $_SESSION['valid_user'];
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

    // Output each variable as a JavaScript variable
    echo "<script>
    var nric = '$nric';
    var firstname = '$firstname';
    var lastname = '$lastname';
    var password = '$password';
    var email = '$email';
    </script>";
}
