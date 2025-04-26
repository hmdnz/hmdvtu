
document.getElementById('forgot-password-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent form submission

    var emailInput = document.getElementById('email');
    var email = emailInput.value.trim();
    var errorContainer = document.getElementById('errorContainer');
    var submitButton = document.getElementById('forgotButton');
    const success_message = document.getElementById("success-message");

    // Validate email format using regular expression
    var emailRegex = /^\S+@\S+\.\S+$/;
    if (!emailRegex.test(email)) {
        emailInput.classList.add('is-invalid');
        errorContainer.textContent = 'Please enter a valid email address.';
        errorContainer.style.display = 'block';
        return;
    }

    // Disable the submit button and show loading spinner
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';

    // Make the AJAX request using Fetch API
    fetch('../classes/Login.php?f=forgot_password', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'email=' + encodeURIComponent(email),
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            submitButton.disabled = false;
            submitButton.innerHTML = 'Reset';
            // Clear any previous error message
            emailInput.classList.remove('is-invalid');
            errorContainer.style.display = 'none';

            if (data.success) {
                // alert(data.message);
                errorContainer.style.display = 'none';
                document.getElementById('forgot-password-form').style.display = "none";
                success_message.style.display = "block";

            } else {
                emailInput.classList.add('is-invalid');
                errorContainer.textContent = data.message;
                errorContainer.style.display = 'block';
            }
        })
        .catch(function (error) {
            // Handle any error that occurred during the request
            console.error('Error:', error);
            // Re-enable the submit button and reset its state
            submitButton.disabled = false;
            submitButton.innerHTML = 'Reset';
            // Show error message and apply validation style to the input field
            emailInput.classList.add('is-invalid');
            errorContainer.textContent = 'An error occurred during form submission.';
            errorContainer.style.display = 'block';
        });
});
