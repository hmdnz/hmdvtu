const captionTitle = document.getElementById("caption-title");
const walletBalance = document.getElementsByClassName("wallet-balance");
const walletBalanceArray = Array.from(walletBalance);
const announcementSpan = document.getElementById("marquee");
// user information
var userId = null;
var walletId = null;
var userName = null;
var userEmail = null;
var userFName = null;
var userLName = null;
var pin;
var userPhone = null;
var myWalletBalance = null;

var statusMsg = document.getElementById("status-message");
var statusMsg2 = document.getElementById("status-message2");
var pinMsg = document.getElementById("pin-message");
var updateBtn = document.getElementById("updateBtn");

function fetchUserInfo() {
    fetch('../classes/User.php?f=fetch_user_information')
        .then(response => response.json())
        .then(data => {
            var name = data.firstName + " " + data.lastName;
            userId = data.userId;
            userName = data.username;
            userEmail = data.email;
            var userFName = data.firstName;
            var userLName = data.lastName;
            var userGender = data.gender;
            var userPhone = data.phone;
            var address = data.address;
            var state = data.state;
            var pin = data.pin;
            captionTitle.textContent = formatSpecialCar(name);
            walletId = data.walletId;
            walletBalanceArray.forEach(walletBalance => {
                walletBalance.textContent = Number(data.balance).toLocaleString();
            })
            walletId = data.walletId;
            myWalletBalance = Number(data.balance).toLocaleString();

            document.getElementById("first-name").value = formatSpecialCar(userFName);
            document.getElementById("last-name").value = formatSpecialCar(userLName);
            document.getElementById("username").value = formatSpecialCar(userName);
            document.getElementById("email").value = userEmail;
            document.getElementById("phone").value = userPhone;
            document.getElementById("address").value = formatSpecialCar(address);
            document.getElementById("customerId").value = userId;
            document.getElementById("changePUserId").value = userId;
            document.getElementById("changePinUserId").value = userId;
            var genderSelect = document.getElementById("gender");
            var stateSelect = document.getElementById("state");
            // Match gender value
            if (userGender !== "") {
                // If userGender is not empty, find the option with matching value
                var genderOption = Array.from(genderSelect.options).find(option => option.value.toLowerCase() === userGender.toLowerCase());
                if (genderOption) {
                    genderOption.selected = true;
                }
            }
            // Match state value
            if (state !== "") {
                // If userState is not empty, find the option with matching value
                var stateOption = Array.from(stateSelect.options).find(option => option.value.toLowerCase() === state.toLowerCase());
                if (stateOption) {
                    stateOption.selected = true;
                }
            }

        })
        .catch(error => {
            // Handle any errors
            console.error('Error:', error);
        });
}

// Get the form element
var form = document.getElementById("registrationForm");

// Add event listener to the form's submit event
form.addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the default form submission

    // Retrieve the form data
    var formData = new FormData(form);
    updateBtn.innerHTML = "Updating...";


    // Make the form submission using fetch
    fetch("../classes/User.php?f=update_user_profile", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateBtn.innerHTML = "Update";
                statusMsg.style.display = "block";
                statusMsg.classList.remove("alert-danger");
                statusMsg.classList.add("alert-success");
                statusMsg.innerHTML = "Updated successfully";
                setTimeout(function() {
                    statusMsg.style.display = "none";
                }, 3000);
            } else {
                updateBtn.innerHTML = "Update";
                statusMsg.style.display = "block";
                statusMsg.classList.remove("alert-success");
                statusMsg.classList.add("alert-danger");
                statusMsg.innerHTML = data.message;
                setTimeout(function() {
                    statusMsg.style.display = "none";
                }, 3000);
            }

        })
        .catch(error => {
            updateBtn.innerHTML = "Update";
            statusMsg.style.display = "block";
            statusMsg.classList.remove("alert-success");
            statusMsg.classList.add("alert-danger");
            statusMsg.innerHTML = "An error occured!";
            setTimeout(function() {
                statusMsg.style.display = "none";
            }, 3000);
            console.error('Error:', error);
        });
});


document.getElementById("updatePasswordForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the form from submitting

    // Get the form inputs
    var currentPassword = document.getElementById("currentPassword").value;
    var newPassword = document.getElementById("newPassword").value;
    var confirmPassword = document.getElementById("confirmPassword").value;

    // Validate the password
    if (newPassword !== confirmPassword) {
        document.getElementById("confirm-message").textContent = "Passwords do not match";
        return;
    }

    if (newPassword.length < 6) {
        document.getElementById("new-message").textContent = "Password must be at least 6 characters long";
        return;
    }
    fetch("../classes/User.php?f=update_user_password", {
            method: "POST",
            body: new URLSearchParams(new FormData(event.target))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Handle successful response
                statusMsg2.style.display = "block";
                statusMsg2.classList.remove("alert-danger");
                statusMsg2.classList.add("alert-success");
                statusMsg2.innerHTML = "Updated successfully";
                setTimeout(function() {
                    statusMsg2.style.display = "none";
                }, 3000);
            } else {
                // Handle error response
                statusMsg2.style.display = "block";
                statusMsg2.classList.remove("alert-success");
                statusMsg2.classList.add("alert-danger");
                statusMsg2.innerHTML = data.message;
                setTimeout(function() {
                    statusMsg2.style.display = "none";
                }, 3000);
            }
        })
        .catch(error => {
            // Handle AJAX errors
            statusMsg2.style.display = "block";
            statusMsg2.classList.remove("alert-success");
            statusMsg2.classList.add("alert-danger");
            statusMsg2.innerHTML = "An error occurred: " + error.message; // Display the actual error message
            setTimeout(function() {
                statusMsg2.style.display = "none";
            }, 3000);

            console.error("Error:", error);
        });


});

// update pin
document.getElementById("updatePinForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the form from submitting
    // Get the form inputs
    var oldPassword = document.getElementById("oldPassword").value;
    var newPin = document.getElementById("newPin").value;
    var confirmNewPin = document.getElementById("confirmNewPin").value;
    // Validate the pin
    if (newPin !== confirmNewPin) {
        document.getElementById("confirmNewPin-message").textContent = "Pins do not match";
        return;
    }
    if (newPassword.length < 4) {
        document.getElementById("newPin-message").textContent = "Pin must be at least 4 characters long";
        return;
    }
    fetch("../classes/User.php?f=update_user_pin", {
            method: "POST",
            body: new URLSearchParams(new FormData(event.target))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Handle successful response
                pinMsg.style.display = "block";
                pinMsg.classList.remove("alert-danger");
                pinMsg.classList.add("alert-success");
                pinMsg.innerHTML = "Updated successfully";
                setTimeout(function() {
                    pinMsg.style.display = "none";
                }, 3000);
            } else {
                // Handle error response
                pinMsg.style.display = "block";
                pinMsg.classList.remove("alert-success");
                pinMsg.classList.add("alert-danger");
                pinMsg.innerHTML = data.message;
                setTimeout(function() {
                    pinMsg.style.display = "none";
                    document.getElementById('updatePinForm').reset();
                }, 3000);
            }
        })
        .catch(error => {
            // Handle AJAX errors
            pinMsg.style.display = "block";
            pinMsg.classList.remove("alert-success");
            pinMsg.classList.add("alert-danger");
            pinMsg.innerHTML = "An error occurred: " + error.message; // Display the actual error message
            setTimeout(function() {
                pinMsg.style.display = "none";
            }, 3000);

            console.error("Error:", error);
        });


});

// format special characters
function formatSpecialCar(text){
    var doc = new DOMParser().parseFromString(text, "text/html");
  return doc.body.textContent;
}

fetchUserInfo();
document.addEventListener('DOMContentLoaded', function () {
    // fetchUserInformation();
        fetchUserInfo();
});



