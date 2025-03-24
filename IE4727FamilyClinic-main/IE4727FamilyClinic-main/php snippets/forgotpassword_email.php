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

    



<?php
include "dbconnect.php";

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function generateRandomPassword($length = 8) {
  // Characters to include in the password
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $password = '';
  $charactersLength = strlen($characters);

  // Build the password with random characters
  for ($i = 0; $i < $length; $i++) {
      $password .= $characters[rand(0, $charactersLength - 1)];
  }

  return $password;
}

$password = generateRandomPassword();

//Declare variables
$message ='';
$email = $_POST['custEmail'];
$encyptedPassword = md5($password);

//Testing
// echo "Generated Password: " . $password;
// echo "email: " . $email;

$sql ="UPDATE patients
       SET patient_password = '$encyptedPassword'
       WHERE email = '$email'";

$result = $conn->query($sql);

$message = "
<html>
<head>
  <title>Forgot Pasword</title>
</head>
<body>
  <h2>Forget Password </h2>
  ";


  $sql = "SELECT *
  FROM patients
  WHERE email = '$email'
  ";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      while ($patient = $result->fetch_assoc()) {

        $patientName = $patient['first_name'] . ' '. $patient['last_name'];


        $message .= "<p>Dear $patientName, </p>
                      <p>We received a request to reset your password for your Health Family Clinic account. Please use the password provided below to login to your account.</p>
        
                    <table>
                      <tr>
                        <th>New Password:</th>
                        <td>$password</td>
                      <tr>

                    </table>";

        }
  }
                                        


// Set the recipient email and email details
$to = "f31ee@localhost"; // Patient's email address
$subject = "Reset Your Password for Family Health Clinic Account";
$message .= "
  <p>Change your password after logging into your account.</p>
  <p>If you have any questions, please contact us.</p>
  <p>Thank you, <br>Health Family Clinic</p>
</body>
</html>
";

// Set headers to send HTML email
// $headers = "MIME-Version: 1.0" . "\r\n"."Content-type:text/html;charset=UTF-8" . "\r\n"."From: f32ee@localhost" . "\r\n". "Reply-To: f32ee@localhost" . "\r\n". 'X-Mailer: PHP/' . phpversion();
$headers = "MIME-Version: 1.0" . "\r\n"."Content-type:text/html;charset=UTF-8" . "\r\n"."From: health@familyclinic.com" . "\r\n". "To: $email" . "\r\n". "Reply-To: f32ee@localhost" . "\r\n". 'X-Mailer: PHP/' . phpversion();
?>


<body>
  <?php include "../php snippets/navbar.php"; ?>
      <div class="container">

            <section style="margin-top: 100px;"> 

            <?php 
                $sql = "SELECT *
                FROM patients
                WHERE email = '$email'
                ";
              
                $result = $conn->query($sql);
              
                if ($result->num_rows > 0) {
                  mail($to, $subject, $message, $headers);
                  echo "<h3>Email sent.</h3><p>Please check your email for your new password.</p>";
                } else {
                  echo "<h3>Email/User not found.</h3><p>Please check if you have entered the correct email.</p>";
                }
            ?>

            </section>
      </div>
  </body>