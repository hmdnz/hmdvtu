// Enable or disable the button based on error validation
const button = document.querySelector('button[type="submit"]');
const errors = document.querySelectorAll('.is-invalid');
const message = document.getElementById("status-message");
const email_message = document.getElementById("email-message");
const username_message = document.getElementById("username-message");
const confirm_message = document.getElementById("confirm-message");
const password_message = document.getElementById("password-message");
const pin_message = document.getElementById("pin-message");
const confirmPin_message = document.getElementById("confirmPin-message");
const success_message = document.getElementById("success-message");
button.disabled = true;

// Function to validate the email address
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Function to validate the phone number
function validatePhoneNumber(phoneNumber) {
    const re = /^\d{11}$/;
    return re.test(phoneNumber);
}

function isStrongPassword(password) {
    // Check for at least one lowercase letter
    const lowercaseRegex = /[a-z]/;
    if (!lowercaseRegex.test(password)) {
      return false;
    }
  
    // Check for at least one uppercase letter
    const uppercaseRegex = /[A-Z]/;
    if (!uppercaseRegex.test(password)) {
      return false;
    }
  
    // Check for at least one digit
    const digitRegex = /\d/;
    if (!digitRegex.test(password)) {
      return false;
    }
  
    // Check for at least one special character
    const specialCharRegex = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/;
    if (!specialCharRegex.test(password)) {
      return false;
    }
  
    // If all checks pass, the password is considered strong
    return true;
}

// Function to handle the input event
function handleInput(event) {
    const input = event.target;
    const value = input.value.trim();

    // Remove any existing validation classes
    input.classList.remove('is-valid', 'is-invalid');

    if (input.id === 'email') {
        // Check email validity
        const isValidEmail = validateEmail(value);
        if (isValidEmail || value === '') {
            // Send request to check email
            if (value === '') {
                email_message.innerText = ""; // Clear the error message when the input is empty
            } else {
                fetch(`../classes/Registration.php?f=check_email&value=${encodeURIComponent(value)}`)
                    .then(response => response.json())
                    .then(data => {
                        // Handle response
                        if (data.exists) {
                            input.classList.add('is-invalid');
                            email_message.classList.add('text-danger');
                            email_message.innerText = "This email already exists!";
                        } else {
                            email_message.classList.remove('text-danger');
                            email_message.innerText = "";
                            input.classList.add('is-valid');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        email_message.innerText = "error";

                    });
            }
        } else {
            input.classList.add('is-invalid');
        }
    } else if (input.id === 'username') {
        // Send request to check username
        if (value === '') {
            username_message.innerText = "";
        } else {
            fetch(`../classes/Registration.php?f=check_username&value=${encodeURIComponent(value)}`)
                .then(response => response.json())
                .then(data => {
                    // Handle response
                    if (data.exists) {
                        input.classList.add('is-invalid');
                        username_message.classList.add('text-danger');
                        username_message.innerText = "This username already exists! Use a new one instead";
                    } else {
                        username_message.classList.remove('text-danger');
                        username_message.innerText = "";
                        input.classList.add('is-valid');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    username_message.innerText = "error";

                });
        }
    }
    else if (input.id === 'password') {
        // Password validation
        if (value.length < 8) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            password_message.classList.add('text-danger');
            password_message.textContent = "Password must be at least 8 characters long";
        }else if(!isStrongPassword(value)){
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            password_message.classList.add('text-danger');
            password_message.textContent = "Password must contain lowercase, upercase, number & special character";
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            password_message.classList.remove('text-danger');
            password_message.textContent = "";
        }
    } else if (input.id === 'confirm-password') {
        // Confirm password validation
        const passwordInput = document.getElementById('password');
        const passwordValue = passwordInput.value.trim();

        if (value !== passwordValue) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            confirm_message.classList.add('text-danger');
            confirm_message.textContent = "Confirm password does not match the password";
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            confirm_message.classList.remove('text-danger');
            confirm_message.textContent = "";
        }
    } else if (input.id === 'newPin') {
        // Pin validation
        if (value.length < 4 ) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            pin_message.classList.add('text-danger');
            pin_message.textContent = "Pin must be 4 characters";
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            pin_message.classList.remove('text-danger');
            pin_message.textContent = "";
        }
    } else if (input.id === 'confirmNewPin') {
        // Confirm pin validation
        const newPinInput = document.getElementById('newPin');
        const newPinValue = newPinInput.value.trim();

        if (value !== newPinValue) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            confirmPin_message.classList.add('text-danger');
            confirmPin_message.textContent = "Confirm pin does not match the pin";
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            confirmPin_message.classList.remove('text-danger');
            confirmPin_message.textContent = "";
        }
    }


    else if (input.id === 'phone') {
        // Check phone number validity
        const isValidPhoneNumber = validatePhoneNumber(value);
        if (isValidPhoneNumber || value === '') {
            input.classList.add('is-valid');
        } else {
            input.classList.add('is-invalid');
        }
    }
    else if (input.id === 'referralId') {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
    } else {
        // Check for non-empty value
        if (value !== '') {
            input.classList.add('is-valid');
        } else {
            input.classList.add('is-invalid');
        }
    }

    // Update the errors NodeList after validation
    const updatedErrors = document.querySelectorAll('.is-invalid');

    if (updatedErrors.length === 0 && document.getElementById('customCheck1').checked) {
        button.disabled = false;
    } else {
        button.disabled = true;
    }
}

// Get all input elements
const inputs = document.querySelectorAll('input, select, textarea');

// Add event listener for input event on each input element
inputs.forEach(input => {
    input.addEventListener('input', handleInput);
});



// Function to handle the form submission
function submitForm(event) {
    event.preventDefault();
    const inputs = document.querySelectorAll('input, select, textarea');
    let isValid = true;
    inputs.forEach(input => {
        // Check if the input is empty
        if (input.value.trim() === '') {
            input.classList.add('is-invalid');
            isValid = false;
            if(input.id === 'referralId'){
                isValid = true;
            }
            return;
        }
        // Check again if any input is invalid
        if (input.classList.contains('is-invalid')) {
            isValid = false;
            return;
        }
    });

    // Submit the form if all inputs are valid
    if (isValid) {
        const form = document.getElementById('registrationForm');
        const formData = new FormData(form);


        // Add a loader to the button
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
        button.disabled = true;

        // Perform form submission using the Fetch API
        fetch('../classes/Registration.php?f=register_user', {
            method: 'POST',
            body: formData

        })
            .then(response => {
                // Handle the response
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Form submission failed');
                }
            })
            .then(data => {
                if (data.success) {
                    form.style.display = "none";
                    success_message.style.display = "block";
                } else {
                    message.classList.add("alert-danger");
                    message.innerHTML = "" + data.message;
                }
            })
            .catch(error => {
                message.classList.add("alert-danger");
                message.innerHTML = "Something went wrong! Try again later";
                // Handle any errors
                console.error('Error:', error);
            })
            .finally(() => {
                // Remove the loader from the button
                button.innerHTML = 'Register';
                button.disabled = false;
            });

    }
}


