function verifyEmail() {
    const verificationDiv = document.getElementById('verification');

    const code = document.getElementById("code_holder").value;
    const success_message = document.getElementById("success-message");
    const error_message = document.getElementById("error-message");
    const verification = document.getElementById("verification");

    const url = '../classes/Registration.php?f=verify_email&value=' + code;

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
document.addEventListener('DOMContentLoaded', function () {
    verifyEmail();
});
