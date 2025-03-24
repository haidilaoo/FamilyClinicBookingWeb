<?php
include "../php snippets/dbconnect.php";
// include "../php snippets/redirect.php";


session_start();

//check if user input both email and password alr
if (isset($_POST['email'], $_POST['password'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password); //need this to encrpyt password so that it matches encrpted password in database

    $query = "SELECT * from patients 
            where email='$email' 
            and patient_password='$password' ";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        //start session if user is in database
        $_SESSION['valid_user'] = $email;
        // echo "successful login " . "$email";

        //setting logged in state 
        $_SESSION['logged_in'] = true;

        $firstname = htmlspecialchars($row['first_name']);
        $lastname = htmlspecialchars($row['last_name']);
        $_SESSION['username'] = "$firstname $lastname";

        // Redirect back to the main page
        header("Location: index.php");
        exit();
    } else {
        echo '<script>alert("Invalid email or password.");</script>';
        // echo "$email" . "$password";
        //need to remain on login page to reenter details if invalid login details
    }

    $conn->close();
}
?>

<!-- //for testing session  -->
<!-- <a href="settings.php">settings</a> -->

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
                <div class="card-registration-container">
                <form method="POST" action="../php/login.php">
                    <div class="card-registration-2">
                        <div>
                            <h2 class="h2-no-margin">Login</h2>
                            <p>
                                Don't have an account?
                                <a href="../php/signup.php">Sign up</a>
                            </p>
                        </div>
                        <div class="display-flex flex-direction-column gap-24" id="form-registration" style="width: 100%;">

                            <div class="flex-grow-shrink">
                                <label for="form-email">
                                    <p>Email</p>
                                </label>
                                <input type="text" id="form-email" name="email" class="input-style" placeholder="Johntan@gmail.com">
                                
                            </div>
                            <div class="display-flex gap-16 flex-grow-shrink">
                                <div class="flex-grow-shrink">
                                    <label for="password">
                                        <p>Password</p>
                                    </label>
                                    <input type="password" id="password" name="password" class="input-style" placeholder="********">
                                </div>
                            </div>

                        </div>
                        <input type="submit" class="btn-blue-lg" value="Log in">
                    </div>
                </form>

                <form action="../php snippets/forgotpassword_email.php" method="POST">
                    <input type="hidden" id="custEmail" name="custEmail">
                    <button style="background: none; border: none; margin-bottom:16px; cursor:pointer" type="submit" class="error" style="display: flex; justify-content: flex-end; font-style: normal; ">
                        Forgot password?
                    </button>
                </form>
            </div>
                
            </div>
            <div style="margin-bottom: 100px;"></div>
        </section>

    </div>


</body>


<script>
    const emailInput = document.getElementById("form-email");

    // Add an event listener to capture the input value as it changes
    emailInput.addEventListener("input", function () {
        const email = emailInput.value;
        console.log(email); // Logs the updated email each time the user types

        const custEmail = document.getElementById("custEmail");
        custEmail.value = email;
    });

</script>

</html>