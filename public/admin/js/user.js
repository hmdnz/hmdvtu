



const addButton = document.querySelector('button[type="submit"]');
const addMessage = document.getElementById("user-status-message");
const addSuccessMessage = document.getElementById("user-success-message");
const username_message = document.getElementById("username-message");
const confirm_message = document.getElementById("confirm-message");
const password_message = document.getElementById("password-message");
const email_message = document.getElementById("email-message");

addButton.disabled = true;

// Function to validate the email address
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhoneNumber(phoneNumber) {
    const re = /^\d{11}$/;
    return re.test(phoneNumber);
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
                fetch(`../classes/user.php?f=check_email&value=${encodeURIComponent(value)}`)
                    .then(response => response.json())
                    .then(data => {
                        // Handle responseusers
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
            fetch(`../classes/user.php?f=check_username&value=${encodeURIComponent(value)}`)
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
        if (value.length < 6) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            password_message.classList.add('text-danger');
            password_message.textContent = "Password must be at least 6 characters long";
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
    } else if (input.id === 'phone') {
        // Check phone number validity
        const isValidPhoneNumber = validatePhoneNumber(value);
        if (isValidPhoneNumber || value === '') {
            input.classList.add('is-valid');
        } else {
            input.classList.add('is-invalid');
        }
    } else {
        // Check for non-empty value
        if (value !== '') {
            input.classList.add('is-valid');
        } else {
            input.classList.add('is-invalid');
        }
    }

}

// Get all input elements
const inputs = document.querySelectorAll('input, select, textarea');

// Add event listener for input event on each input element
inputs.forEach(input => {
    input.addEventListener('input', handleInput);
});

// Function to handle the add form submission
function submitAddForm(event) {
    event.preventDefault();

    const form = document.getElementById('addUser');
    const formData = new FormData(form);

    // Disable the button while submitting
    addButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
    addButton.disabled = true;

    fetch('../classes/user.php?f=add_user', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                form.reset(); // Reset the form fields if the submission is successful
                addMessage.style.display = "none";
                addSuccessMessage.style.display = "block";
            } else {
                addMessage.classList.add("alert-danger");
                addMessage.innerHTML = data.message;
            }
        })
        .catch(error => {
            addMessage.classList.add("alert-danger");
            addMessage.innerHTML = "Something went wrong! Try again later";
            console.error('Error:', error);
        })
        .finally(() => {
            // Enable the button after submission
            addButton.innerHTML = 'Submit';
            addButton.disabled = false;
        });
}

const addForm = document.getElementById('addUser');
addForm.addEventListener('submit', submitAddForm);
