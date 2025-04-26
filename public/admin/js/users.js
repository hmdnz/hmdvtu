var statusMsg2 = document.getElementById("status-message2");
// Function to format number with thousands separator
function formatNumber(number) {
    return new Intl.NumberFormat().format(number);
}

// Function to format date with time
function formatDateTime(dateTime) {
    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric'};
    const storedDate = new Date(dateTime); 
    const existingDate = new Date(storedDate);
    const modifiedDate = existingDate.setHours(storedDate.getHours() + 1);
    const newDate = new Date(modifiedDate);
    return newDate.toLocaleString('en-US', options).substring(0,25);
}

function fetchUsers() {
    const table = $('#datatable').DataTable();
    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable').DataTable({
        ajax: {
            url: `../classes/user.php?f=fetch_users`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    name: delivery.firstName + " " + delivery.lastName,
                    username: delivery.username,
                    type: delivery.userType,
                    verified: (delivery.status === "1" ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Deactivated</span>') + " <br>" + (delivery.isVerified === 1 ? '<span class="text-success">Verified</span>' : '<span class="text-danger">Not Verified</span>'),
                    createdAt: "<small>" + formatDateTime(delivery.createdAt) + "</small>",
                    status: delivery.status,
                    userId: delivery.userId
                }));
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'name' },
            { data: 'username' },
            { data: 'type' },
            { data: 'verified' },
            { data: 'createdAt' },
            {
                data: null,
                render: function (data, type, row) {
                    if (data.status === "1") {
                        return `
                        <a type="button" data-bs-toggle="modal" class="btn btn-primary btn-sm  p-2" data-bs-target="#viewUserDetailsModal" data-delivery-id="${data.userId}">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a type="button" data-bs-toggle="modal" class="btn btn-warning delete-btn btn-sm p-2" onclick="deactivateUser(${data.userId})">
                            <i class="fa fa-times"></i>
                        </a>
                        <a type="button" data-bs-toggle="modal" class="btn btn-danger btn-sm  p-2" data-bs-target="#resetUserPasswordModal" data-delivery-id="${data.userId}">
                            <i class="fa fa-lock"></i>
                        </a>
                        <a type="button"  class="btn btn-success btn-sm  p-2" onclick="resetPin(${data.userId})">
                            <i class="fa fa-unlock"></i>
                        </a>
                        `;
                    } else {
                        return `
                            <a type="button" data-bs-toggle="modal" class="btn btn-primary btn-sm  p-2" data-bs-target="#viewUserDetailsModal" data-delivery-id="${data.userId}">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a type="button" data-bs-toggle="modal" class="btn btn-success activate-btn btn-sm p-2" onclick="activateUser(${data.userId})">
                                <i class="fa fa-check"></i>
                            </a>
                            <a type="button" data-bs-toggle="modal" class="btn btn-danger btn-sm  p-2" data-bs-target="#resetUserPasswordModal" data-delivery-id="${data.userId}">
                                <i class="fa fa-lock"></i>
                            </a>
                            <a type="button"  class="btn btn-success btn-sm  p-2" onclick="resetPin(${data.userId})">
                                <i class="fa fa-unlock"></i>
                            </a>
                        `;
                    }
                }
            }
        ]
    });

    // Attach a click event listener to the eye buttons
    $('#datatable').on('click', '.btn-primary', function () {
        const userId = $(this).data('delivery-id');

        // Fetch individual delivery details using the deliveryId
        fetchModalDetails(userId);
    });

    // Attach a click event listener to the lock button
    $('#datatable').on('click', '.btn-danger', function () {
        const userId = $(this).data('delivery-id');

        // Fetch individual delivery details using the deliveryId
        fetchResetDetails(userId);
    });
}



function fetchModalDetails(userId) {
    // Perform AJAX request to fetch individual user details using the userId
    $.ajax({
        url: `../classes/user.php?f=fetch_user_details&userId=${userId}`,
        type: 'GET',
        success: function (data) {
            if (data.length > 0) {
                const userDetails = data[0];

                // Construct the HTML content using a <ul> with Bootstrap classes
                const htmlContent = `
                <ul class="list-group user-details-list">
                  <li class="list-group-item"><strong>Name:</strong> ${userDetails.firstName} ${userDetails.lastName}</li>
                  <li class="list-group-item"><strong>Username:</strong> ${userDetails.username}</li>
                  <li class="list-group-item"><strong>Type:</strong> ${userDetails.userType}</li>
                  <li class="list-group-item"><strong>Gender:</strong> ${userDetails.gender}</li>
                  <li class="list-group-item"><strong>Phone:</strong> ${userDetails.phone}</li>
                  <li class="list-group-item"><strong>Email:</strong> ${userDetails.email}</li>
                  <li class="list-group-item"><strong>Verification status:</strong> ${userDetails.isVerified === 1 ? '<span class="badge bg-success">Verified</span>' : '<span class="badge bg-danger">Not Verified</span>'}</li>
                  <li class="list-group-item"><strong>Status:</strong> ${userDetails.status === '1' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'}</li>
                  <li class="list-group-item"><strong>Registration Date:</strong> ${formatDateTime(userDetails.createdAt)}</li>
                </ul>
              `;

                // Update the modal body with the constructed HTML content
                $('#viewUserDetailsModal .modal-body').html(htmlContent);

                // Show the details and remove the loading spinner
                $('#viewUserDetailsModal .modal-body ul').show();
            } else {
                // Display a message if no user details were found
                $('#viewUserDetailsModal .modal-body').html('<div class="text-center text-danger">No user details found.</div>');
            }
        },
        error: function (xhr, status, error) {
            // Display an error message if the request fails
            $('#viewUserDetailsModal .modal-body').html('<div class="text-center text-danger">Failed to fetch user details.</div>');
        }
    });
}

function fetchResetDetails(userId) {
    document.getElementById('resetUserId').value = userId;
    // Perform AJAX request to fetch individual user details using the userId
    $.ajax({
        url: `../classes/user.php?f=fetch_user_details&userId=${userId}`,
        type: 'GET',
        success: function (data) {
            if (data.length > 0) {
                const userDetails = data[0];

                // Construct the HTML content using a <ul> with Bootstrap classes
                const htmlContent = `
                <h4 ><strong>User:</strong> ${userDetails.firstName} ${userDetails.lastName}</h4>
              `;

                // Update the modal body with the constructed HTML content
                $('#resetUserPasswordModal .user-details').html(htmlContent);

                // Show the details and remove the loading spinner
                // $('#viewUserDetailsModal .modal-body ul').show();
            } else {
                // Display a message if no user details were found
                $('#resetUserPasswordModal .modal-body').html('<div class="text-center text-danger">No user details found.</div>');
            }
        },
        error: function (xhr, status, error) {
            // Display an error message if the request fails
            $('#resetUserPasswordModal .modal-body').html('<div class="text-center text-danger">Failed to fetch user details.</div>');
        }
    });
}

document.getElementById("resetUserPasswordForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the form from submitting

    // Get the form inputs
    var currentPassword = document.getElementById("currentPassword").value;
    var resetUserId = document.getElementById("resetUserId").value;
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
    fetch("../classes/user.php?f=update_user_password", {
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

// setInterval(fetchUsers, 5000); // Fetch the updated data every 1 second

function closeModal(modal) {
    $('#' + modal).modal('hide');
}

function deactivateUser(userId) {

    var formData = new FormData();
    formData.append("userId", userId);
    formData.append("status", 0);
    fetch('..//classes/user.php?f=update_user_status', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            fetchUsers();
        })
        .catch(error => {
            console.error(error);
        });
}

function activateUser(userId) {

    var formData = new FormData();
    formData.append("userId", userId);
    formData.append("status", 1);
    fetch('..//classes/user.php?f=update_user_status', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            fetchUsers();
        })
        .catch(error => {
            console.error(error);
        });
}

function resetPin(userId) {
    var formData = new FormData();
    formData.append("userId", userId);
    fetch('..//classes/user.php?f=reset_user_pin', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            fetchUsers();
        })
        .catch(error => {
            console.error(error);
        });
}


document.addEventListener('DOMContentLoaded', function () {
    fetchUsers();
});
