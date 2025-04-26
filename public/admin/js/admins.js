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

function fetchAdmins() {
    const table = $('#datatable').DataTable();
    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable').DataTable({
        ajax: {
            url: `../classes/admins.php?f=fetch_Admins`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    name: delivery.name,
                    username: delivery.username,
                    role: delivery.role,
                    email: delivery.email,
                    adminId: delivery.adminId,
                    status: delivery.status === '1' ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>',
                    theStatus: delivery.status,
                    createdAt: "<small>" + formatDateTime(delivery.createdAt) + "</small>",
                    // AdminId: delivery.adminId
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
            { data: 'role' },
            { data: 'email' }, 
            { data: 'status' },
            { data: 'createdAt' },
            {
                data: null,
                render: function (data, type, row) {
                    if (data.theStatus === "1") {
                        return `
                        <a type="button" data-bs-toggle="modal" class="btn btn-primary btn-sm  p-2" onclick="editAdmin('${data.name}', '${data.email}', '${data.username}', '${data.role}', '${data.adminId}')" >
                            <i class="fa fa-edit"></i>
                        </a>
                        <a type="button" data-bs-toggle="modal" class="btn btn-warning delete-btn btn-sm p-2" onclick="deactivateUser(${data.adminId})">
                        <i class="fa fa-times"></i>
                      </a>
                        `;
                    } else {
                        return `
                        <a type="button" data-bs-toggle="modal" class="btn btn-primary btn-sm  p-2" onclick="editAdmin('${data.name}', '${data.email}', '${data.username}', '${data.role}', '${data.adminId}')" >
                        <i class="fa fa-edit"></i>
                         </a>
                        <a type="button" data-bs-toggle="modal" class="btn btn-success activate-btn btn-sm p-2" onclick="activateUser(${data.adminId})">
                            <i class="fa fa-check"></i>
                          </a>
                        `;
                    }
                }
            }
        ]
    });

    // Attach a click event listener to the eye buttons
    // $('#datatable').on('click', '.btn-primary', function () {
    //     const AdminId = $(this).data('delivery-id');

    //     // Fetch individual delivery details using the deliveryId
    //     // fetchModalDetails(AdminId);
    // });
}

document.addEventListener('DOMContentLoaded', function () {

    // Delay the execution of fetchRecentActivities() by 3 seconds
    setTimeout(function () {
        fetchAdmins();
    }, 3000);

    // Get the form element
    const form = document.querySelector('form');

    // Attach an event listener to the form's submit event
    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the form from submitting normally
        // Example: Sending an AJAX request
        const formData = new FormData(form);
        const url = '../classes/admins.php?f=add_admin';

        fetch(url, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#newAdmin').modal("hide");
                    fetchAdmins();
                }
                // Handle the response data
                console.log(data);
            })
            .catch(error => {
                // Handle any errors
                console.error(error);
            });
    })
});

// Get the username and email input fields
const usernameInput = document.getElementById('add1');
const emailInput = document.getElementById('add2');

// Get the submit button
const submitButton = document.querySelector('button[type="submit"]');

// Function to handle username availability check
function checkUsernameAvailability(username) {
    return fetch(`../classes/admins.php?f=check_username&value=${encodeURIComponent(username)}`)
        .then(response => response.json())
        .then(data => data.exists);
}

// Function to handle email availability check
function checkEmailAvailability(email) {
    return fetch(`../classes/admins.php?f=check_email&value=${encodeURIComponent(email)}`)
        .then(response => response.json())
        .then(data => data.exists);
}

// Function to handle input event on username field
function handleUsernameInput() {
    const username = usernameInput.value.trim();
    if (username === '') {
        return;
    }

    checkUsernameAvailability(username)
        .then(exists => {
            if (exists) {
                showErrorMessageU('Username already taken');
                disableSubmitButton();
            } else {
                clearErrorMessageU();
                enableSubmitButton();
            }
        })
        .catch(error => {
            console.error('Error checking username availability:', error);
        });
}

// Function to handle input event on email field
function handleEmailInput() {
    const email = emailInput.value.trim();
    if (email === '') {
        return;
    }

    checkEmailAvailability(email)
        .then(exists => {
            if (exists) {
                showErrorMessageE('Email already taken');
                disableSubmitButton();
            } else {
                clearErrorMessageE();
                enableSubmitButton();
            }
        })
        .catch(error => {
            console.error('Error checking email availability:', error);
        });
}

// Function to show error message
function showErrorMessageU(message) {
    const errorElement = document.getElementById('u-msg');
    errorElement.textContent = message;
    errorElement.style.display = 'block';

}

function showErrorMessageE(message) {
    const errorElement = document.getElementById('e-msg');
    errorElement.textContent = message;
    errorElement.style.display = 'block';

}

// Function to clear error message
function clearErrorMessageU() {
    const errorElement = document.getElementById('u-msg');
    errorElement.textContent = '';
    errorElement.style.display = 'none';

}

function clearErrorMessageE() {
    const errorElement = document.getElementById('e-msg');
    errorElement.textContent = '';
    errorElement.style.display = 'none';

}
// Function to disable submit button
function disableSubmitButton() {
    document.getElementById("submitAddAdmin").disabled = true;
}

// Function to enable submit button
function enableSubmitButton() {
    document.getElementById("submitAddAdmin").disabled = false;
}

// Attach input event listeners to username and email fields
usernameInput.addEventListener('input', handleUsernameInput);
emailInput.addEventListener('input', handleEmailInput);

function deactivateUser(userId) {

    var formData = new FormData();
    formData.append("userId", userId);
    formData.append("status", 0);
    fetch('..//classes/admins.php?f=update_user_status', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            fetchAdmins();
        })
        .catch(error => {
            console.error(error);
        });
}

function activateUser(userId) {

    var formData = new FormData();
    formData.append("userId", userId);
    formData.append("status", 1);
    fetch('..//classes/admins.php?f=update_user_status', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            fetchAdmins();
        })
        .catch(error => {
            console.error(error);
        });
}

function editAdmin(name, email, username, role, adminId) {
    // Set the values in the modal input fields
    document.getElementById('editAdminName').value = name;
    document.getElementById('editAdminEmail').value = email;
    document.getElementById('editAdminUsername').value = username;
    document.getElementById('editAdminId').value = adminId;

    // Set the selected option in the role dropdown
    const roleDropdown = document.getElementById('editAdminRole');
    for (let i = 0; i < roleDropdown.options.length; i++) {
        if (roleDropdown.options[i].value === role) {
            roleDropdown.selectedIndex = i;
            break;
        }
    }

    // Open the modal
    const modal = new bootstrap.Modal(document.getElementById('editAdminModal'));
    modal.show();
}


function saveEditedAdmin(event) {
    event.preventDefault();
    var form = document.getElementById("editFormAdmin");
    var formData = new FormData(form);
    fetch('..//classes/admins.php?f=update_admin', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            // Close the modal box
            $('#editAdminModal').modal('hide');
            fetchAdmins();
        })
        .catch(error => {
            console.error(error);
        });
}