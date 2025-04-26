const verificationDiv = document.getElementById('verification');

const code = document.getElementById("code_holder").value;
const success_message = document.getElementById("success-message");
const error_message = document.getElementById("error-message");
const verification = document.getElementById("verification");
const success_message_shower = document.getElementById("success-message-shower");

function verifyEmail() {
    const url = '../classes/Login.php?f=check_reset_password&value=' + code;

    fetch(url)
        .then(response => {
            // Handle the response
            if (response.ok) {
                // Request successful
                return response.json();
            } else {
                // Request failed
                throw new Error('Request failed');
            }
        })
        .then(data => {
            if (data.success) {
                verification.style.display = "none";
                success_message.style.display = "block";
                document.getElementById("userId").value = data.userId;
            } else {
                verification.style.display = "none";
                error_message.style.display = "block";
            }
            console.log(data);
        })
        .catch(error => {
            // Handle any errors
            console.error('Error:', error);
        })
        .finally(() => {
            // Remove the loader from the verification div
            verificationDiv.innerHTML = '';
        });
}

function changePassword() {
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirm-password");
    const submitButton = document.getElementById("submitButton");
    const passwordError = document.getElementById("passwordError");
    const passwordError2 = document.getElementById("passwordError2");

    // Add event listeners to validate input fields
    passwordInput.addEventListener("input", function () {
        if (passwordInput.value.length >= 6) {
            passwordInput.classList.add("is-valid");
            passwordInput.classList.remove("is-invalid");
            passwordError.innerText = "";
        } else {
            passwordInput.classList.remove("is-valid");
            passwordInput.classList.add("is-invalid");
            passwordError.innerText = "Password must be at least 6 characters long";
        }
    });

    confirmPasswordInput.addEventListener("input", function () {
        if (confirmPasswordInput.value === passwordInput.value) {
            confirmPasswordInput.classList.add("is-valid");
            confirmPasswordInput.classList.remove("is-invalid");
            passwordError2.innerText = "";
        } else {
            confirmPasswordInput.classList.remove("is-valid");
            confirmPasswordInput.classList.add("is-invalid");
            passwordError2.innerText = "Passwords do not match";
        }
    });

    // Add event listener to submit form
    submitButton.addEventListener("click", function (event) {
        event.preventDefault();
        if (passwordInput.value.length < 6) {
            passwordInput.classList.remove("is-valid");
            passwordInput.classList.add("is-invalid");
            passwordError.innerText = "Password must be at least 6 characters long";
            return;
        }
        if (confirmPasswordInput.value !== passwordInput.value) {
            confirmPasswordInput.classList.remove("is-valid");
            confirmPasswordInput.classList.add("is-invalid");
            passwordError2.innerText = "Passwords do not match";
            return;
        }
        formData = new FormData();
        formData.append("password", passwordInput.value);
        formData.append("confirm_password", confirmPasswordInput.value);
        formData.append("userId", document.getElementById("userId").value);

        // Disable submit button and show loading spinner
        submitButton.disabled = true;
        submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Changing...`;

        // Send data to server using Fetch API
        fetch("../classes/Login.php?f=reset_password", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("There was a problem changing your password.");
                } else {
                    return response.json();
                }
            })
            .then(data => {
                if (data.success) {
                    document.getElementById("error-msg").style.display = "none";
                    success_message.style.display = "none";
                    success_message_shower.style.display = "block";
                } else {
                    document.getElementById("error-msg").style.display = "block";
                    document.getElementById("error-msg").innerText = data.message;

                }
                console.log(data);
            })

            .catch((error) => {
                console.error(error);
                // alert(error.message);
            })
            .finally(() => {
                // Enable submit button and hide loading spinner
                submitButton.disabled = false;
                submitButton.innerHTML = "Change";
            });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    verifyEmail();
    changePassword();
});
