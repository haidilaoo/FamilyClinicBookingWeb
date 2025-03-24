<!-- code snippet for fetching one data in reference to current session's id -->
<?php
include "dbconnect.php";
include "login.php";

if (!isset($_SESSION)) //check if session is available aka if user has logged in yet -> by right shldnt be able to access
session_start(); // Start the session to access session variables 
var_dump($_SESSION); //shows no variables stored in $_SESSION array
$id = session_id(); 

//set variables from session
$email = $_SESSION['valid_user'];

// Fetch patient details from the database
if (isset($_SESSION['valid_user'])) {
    $query = "SELECT nric FROM patients WHERE email = '$email' ";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Fetch the NRIC from the query result
        $row = $result->fetch_assoc();
        //set variables
        $nric = htmlspecialchars($row['nric']);
        echo "<script>var nric = '$nric';</script>";

    } else {
        // If no result, output an error message in JavaScript
        echo "<script>console.error('NRIC not found');</script>";
    }
} else {
    echo "<script>console.error('User is not logged in');</script>";
}

$conn->close();

?>
