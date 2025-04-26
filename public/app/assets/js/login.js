
document.getElementById("login-form").addEventListener("submit", function (event) {
    event.preventDefault();

    // Get form values
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var rememberMe = document.getElementById("customCheck1").checked;
    const message = document.getElementById("message");


    // Validate form fields
    var isValid = true;
    if (email.trim() === "") {
        document.getElementById("email").classList.add("is-invalid");
        document.getElementById("emailError").textContent = "Please enter a valid email address.";
        isValid = false;
    } else {
        document.getElementById("email").classList.remove("is-invalid");
        document.getElementById("emailError").textContent = "";
    }

    if (password.trim() === "") {
        document.getElementById("password").classList.add("is-invalid");
        document.getElementById("passwordError").textContent = "Please enter a password.";
        isValid = false;
    } else {
        document.getElementById("password").classList.remove("is-invalid");
        document.getElementById("passwordError").textContent = "";
    }

    if (!isValid) {
        return; // Exit the function if form fields are not valid
    }

    // Disable the submit button
    var submitButton = document.getElementById("submitButton");
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

    // Prepare the form data to be sent to PHP
    var formData = new FormData();
    formData.append("email", email);
    formData.append("password", password);
    formData.append("rememberMe", rememberMe);


    // Perform form validation or other operations if needed

    fetch("../classes/Login.php?f=user_login", {
        method: "POST",
        body: formData,
    })
        .then(function (response) {
            // Handle the response from the server
            if (response.ok) {
                // Request successful
                return response.json();
            } else {
                console.error("Form submission failed");
            }
        })
        .then(data => {
            if (data.success) {
                message.style.display = "block";
                message.classList.remove("alert-danger");
                message.classList.add("alert-success");
                message.innerHTML = '<div class="d-flex align-items-center"><svg class="bi flex-shrink-0 me-2" width="24" height="24"><use xlink:href="#check-circle-fill"/></svg><span>' + data.message + '</span></div>';
                setTimeout(function () {
                    window.location.href = '../app/';
                }, 1000);

            } else {
                message.style.display = "block";
                message.classList.add("alert-danger");
                message.innerHTML = '<div class="d-flex align-items-center"><svg class="bi flex-shrink-0 me-2" width="24" height="24"><use xlink:href="#exclamation-circle-fill"/></svg><span class="text-danger">' + data.message + '</span></div>';

            }

            console.log(data);
        })
        .catch(function (error) {
            // Handle any errors during form submission
            console.error("Error:", error);
        })
        .finally(function () {
            // Enable the submit button and reset its text
            submitButton.disabled = false;
            submitButton.innerHTML = "Login";
        });
});
