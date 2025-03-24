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
    // document.getElementById("password") = password; //use for matching new input



});


//to disable Save changes button 
formElement = document.getElementById("settingsForm");
const saveButton = document.getElementById("saveButton"); // Ensure the button is selected
// formElement.addEventListener(this.onchange);
saveButton.disabled = true; // disabled at first

// Function to enable the Save button
function enableSaveButton() {
    saveButton.disabled = false; // Enable the Save button
}

// Attach change event listeners to all input fields in the form
const inputElements = formElement.querySelectorAll('input, textarea, select');
inputElements.forEach(inputElement => {
    inputElement.addEventListener('change', enableSaveButton); // Use 'change' event
    inputElement.addEventListener('input', enableSaveButton);
});



// validation criterias
const namePattern = /^[A-Za-z\s]+$/;
const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+(\.[a-zA-Z]{2,3}){1,4}$/; //extension limit to 4 address not working
const nricPattern = /^[A-Za-z]{1}[0-9]{7}[A-Za-z]{1}$/;
const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;


// function validatenewPassword() {
//     const newPassword = document.getElementById("newpassword").value;
//     return checkPassword(newPassword);
// }

function validateSignupForm() {
    // Clear previous error messages
    document.getElementById("nricError").innerText = "";
    document.getElementById("firstnameError").innerText = "";
    document.getElementById("lastnameError").innerText = "";
    document.getElementById("emailError").innerText = "";
    // Only clear password errors if the password fields exist
    // if (document.getElementById("password") && document.getElementById("confirmpassword")) {
    //     document.getElementById("passwordError").innerText = "";
    //     document.getElementById("confirmpasswordError").innerText = "";
    // }

    const nric = document.getElementById("form-NRIC").value;
    const firstname = document.getElementById("firstname").value;
    const lastname = document.getElementById("lastname").value;
    // Only attempt to get password values if the fields exist
    // const password = document.getElementById("password") ? document.getElementById("password").value : null;
    // const confirmpassword = document.getElementById("confirmpassword") ? document.getElementById("confirmpassword").value : null;
    const email = document.getElementById("form-email").value;
    // const inputpassword = document.getElementById("inputpassword") ? document.getElementById("inputpassword").value : null;
    const newPassword = document.getElementById("newpassword").value;
    // if (document.getElementById("newpassword").value.length > 0) {
    //     const newPassword = document.getElementById("newpassword").value;
    // }

    // var isPasswordCorrect = '0';

    let valid = true; // Track if the form is valid

    // Individual field validations
    if (!checkNric(nric)) valid = false;
    if (!checkFirstName(firstname)) valid = false;
    if (!checkLastName(lastname)) valid = false;
    if (!checkEmail(email)) valid = false;
    // Only validate passwords if the fields are present
    // if (password !== null && confirmpassword !== null) {
    //     if (!checkPassword(password)) valid = false;
    //     if (!checkConfirmPassword(confirmpassword)) valid = false;
    // }

    if (typeof isPasswordCorrect !== 'undefined' && isPasswordCorrect === '1') {
        if (!checkPassword(newPassword)) valid = false;
    }
    // else {
    //     console.log("Password is incorrect, so new password validation will not run.");
    // }

    else if (newPassword.length > 0 && isPasswordCorrect === '0') {
        console.log(isPasswordCorrect);
        // Prevent user from entering a new password if the current password is incorrect
        // document.getElementById("passwordError").innerText = "Please enter your current password correctly.";
        // valid = false;
        if (!checkPassword(newPassword)) valid = false;
    }

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
        document.getElementById("passwordError").innerText = "New Password is required to change password.";
        return false;
    } else if (password.length < 8) {
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

// function checkConfirmPassword(confirmpassword) {
//     if (!confirmpassword) {
//         document.getElementById("confirmpasswordError").innerText = "Please write your password to confirm.";
//         return false;
//     } else if (confirmpassword !== document.getElementById("password").value) { //cannot use password variable eventhough it is declared outside function as global variable for some reason
//         document.getElementById("confirmpasswordError").innerText = "Password does not match.";
//         console.log(confirmpassword);
//         console.log("password =" + document.getElementById("password").value);
//         return false;
//     } else {
//         return true;
//     }

// }

// function enableButton {
//     if (nochange) {
//         document.getElementById("btn-save").style.color = white;
//     }
// }
