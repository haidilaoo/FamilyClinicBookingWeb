<?php
include "dbconnect.php";

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}


$patientName = $_SESSION['username'];
$doctor_id = $_SESSION['selected_doctor'];
$email = $_SESSION['valid_user'];
$app_slot_id = $_SESSION['app_slot_id'];

$message ='';

$message = "
<html>
<head>
  <title>Appointment Confirmation</title>
</head>
<body>
  <h2>Thank you for booking an appointment!</h2>
  <p>Dear $patientName,</p>
  <p>Your appointment is confirmed with the following details:</p>";


$sql = 'SELECT a.appointment_type, a.appointment_date, a.appointment_time, d.first_name, d.last_name
FROM appointments a, doctors d
WHERE d.doctor_id ='.$doctor_id.' AND d.doctor_id = a.doctor_id AND a.slot_id ="'.$app_slot_id.'"
';

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($app = $result->fetch_assoc()) {
      
      
        $message .= "<table>
                      <tr>
                        <th> Doctor </th>
                        <td> Dr. ".$app['first_name']." ".$app['last_name'] ."</td>
                      </tr>
                      <tr>
                        <th> Date </th>
                        <td> ".date('d F Y',strtotime($app['appointment_date']))."</td>
                      </tr>
                      <tr>
                        <th> Time </th>
                        <td> ".date('h:i A',strtotime($app['appointment_time']))." </td>
                      </tr>
                      <tr>
                        <th> Type </th>
                         <td> ".$app['appointment_type']." </td>
                      </tr>
                      <tr>
                        <th> Location </th>
                        <td> <p>
                                  <span> Health Family Clinic </span> <br/>
                                  <span>Jurong West Avenue 18, S123456</span>
                              </p> 
                        </td>
                      </tr>


                    </table>";
        
    }
}
                                


// Set the recipient email and email details
$to = "f31ee@localhost"; // Patient's email address
$subject = "Your Appointment Confirmation";
$message .= "
  <p>If you have any questions, please contact us.</p>
  <p>Thank you, <br>Health Family Clinic</p>
</body>
</html>
";

// Set headers to send HTML email
$headers = "MIME-Version: 1.0" . "\r\n"."Content-type:text/html;charset=UTF-8" . "\r\n"."From: health@familyclinic.com" . "\r\n". "To: $email" . "\r\n". "Reply-To: f32ee@localhost" . "\r\n". 'X-Mailer: PHP/' . phpversion();



mail($to, $subject, $message, $headers)

?>

