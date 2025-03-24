//reusing this script for SETTINGS page
//just need to add logic to modify button state onchange
let currentPassword = "";
// Set the value of the inputs when the page loads
document.addEventListener("DOMContentLoaded", function () {
    //FOR SETTINGS: DISPLAYING DATA
    document.getElementById("form-NRIC").value = nric; // Set the NRIC value to be value from database
    document.getElementById("firstname").value = firstname;
    document.getElementById("lastname").value = lastname;
    document.getElementById("form-email").value = email;
    currentPassword = password; //password is current password from database
    //END OF SETTINGS PAGE

    //FOR APPOINTMENTS: DISPLAYING APPOINTMENTS 

    //END OF APPOINTMENTS PAGE
});

//END OF SETTINGS : GETTING DATA

// validation criterias
const namePattern = /^[A-Za-z\s]+$/;
const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+(\.[a-zA-Z]{2,3}){1,4}$/; //extension limit to 4 address not working
const nricPattern = /^[A-Za-z]{1}[0-9]{7}[A-Za-z]{1}$/;
const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

function validateSignupForm(isSignup) {

    // Clear previous error messages
    document.getElementById("nricError").innerText = "";
    document.getElementById("firstnameError").innerText = "";
    document.getElementById("lastnameError").innerText = "";
    document.getElementById("emailError").innerText = "";
    document.getElementById("passwordError").innerText = "";
    document.getElementById("confirmpasswordError").innerText = "";

    const nric = document.getElementById("form-NRIC").value;
    const firstname = document.getElementById("firstname").value;
    const lastname = document.getElementById("lastname").value;
    // const password = document.getElementById("password").value;
    const confirmpassword = document.getElementById("confirmpassword").value;
    const email = document.getElementById("form-email").value;

    let valid = true; // Track if the form is valid

    // Individual field validations
    if (!checkNric(nric)) valid = false;
    if (!checkFirstName(firstname)) valid = false;
    if (!checkLastName(lastname)) valid = false;
    if (!checkEmail(email)) valid = false;


    //for settings
    // if (!isSignup) {
    //FOR CHECKING CURRENT PASSWORD INPUT 
    const password = document.getElementById("password").value;
    // const newpassword = document.getElementById("newpassword").value;
    // if (password) {
    // if (!checkPassword(password)) valid = false;

    //for testing js function runs
    // console.log(currentPassword);
    // console.log(hashed_inputPassword);
    //FOR CHECKING IF CORRECT PASSWORD
    // if (password != currentPassword) {
    //     document.getElementById("passwordError").innerText = "Wrong current password.";
    //     valid = false;
    // } 
    // if (newpassword) {
    // if (!checkPassword(newpassword)) valid = false;  
    // }
    // }

    //FOR CHECKING CURRENT PASSWORD INPUT

    // } else {
    // const password = document.getElementById("password").value;
    if (!checkPassword(password)) valid = false;
    if (!checkConfirmPassword(confirmpassword)) valid = false;
    // }

    console.log("First name:", firstname);
    console.log("entered signup.js script");
    console.log("Final validity state:", valid);

    return valid; // Return the validity status
}

function checkNric(nric) {
    if (!nric) {
        document.getElementById("nricError").innerText = "NRIC is required.";
        return false;
    } else if (!nricPattern.test(nric)) {
        document.getElementById("nricError").innerText = "Please enter a valid NRIC. (E.g S1234567A)";
        return false;
    }
    return true;
}

function checkFirstName(firstname) {

    console.log("Name Pattern:", namePattern); // Log pattern
    console.log("First Name:", firstname); // Log first name input

    if (!firstname) {
        document.getElementById("firstnameError").innerText = "First name is required.";
        return false;

    } else if (!namePattern.test(firstname)) {
        document.getElementById("firstnameError").innerText = "Name must only contain alphabets.";
        return false;
    }
    else {
        return true;
    }

}

function checkLastName(lastname) {
    if (!lastname) {
        document.getElementById("lastnameError").innerText = "Last name is required.";
        return false;
    } else if (!namePattern.test(lastname)) {
        document.getElementById("lastnameError").innerText = "Name must only contain alphabets.";
        return false;
    }
    else {
        return true;
    }

}

function checkEmail(email) {
    if (!email) {
        document.getElementById("emailError").innerText = "Email is required.";
        return false;
    } else if (!emailPattern.test(email)) {
        document.getElementById("emailError").innerText = "Please enter a valid email address. (E.g name@example.com)";
        return false;
    }
    return true;
}

function checkPassword(password) {

    if (!password) {
        document.getElementById("passwordError").innerText = "Password is required.";
        return false;
    }
    else if (password.length < 8) {
        document.getElementById("passwordError").innerText = "Password must be at least 8 characters long.";
        return false;
    } else if (!passwordPattern.test(password)) {
        document.getElementById("passwordError").innerText = "Your password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character (e.g., !@#$%^&*).";
        return false;
    }
    else {
        return true;
    }

}

function matchPassword(password) {


    if (password != current) {
        document.getElementById("passwordError").innerText = "Password is required.";
        return false;
    }
    else if (password.length < 8) {
        document.getElementById("passwordError").innerText = "Password must be at least 8 characters long.";
        return false;
    } else {
        return true;
    }

}


function checkConfirmPassword(confirmpassword) {
    if (!confirmpassword) {
        document.getElementById("confirmpasswordError").innerText = "Please write your password to confirm.";
        return false;
    } else if (confirmpassword !== document.getElementById("password").value) { //cannot use password variable eventhough it is declared outside function as global variable for some reason
        document.getElementById("confirmpasswordError").innerText = "Password does not match.";
        console.log(confirmpassword);
        console.log("password =" + document.getElementById("password").value);
        return false;
    }else if (!passwordPattern.test(confirmpassword)) {
        document.getElementById("confirmpasswordError").innerText = "Your password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character (e.g., !@#$%^&*).";
        return false;
    }
    
    else {
        return true;
    }

}

// function enableButton {
//     if (nochange) {
//         document.getElementById("btn-save").style.color = white;
//     }
// }

