
// Function to handle the add form submission
function submitAddForm(event) {
    event.preventDefault();

    const inputs = document.querySelectorAll('#addService input, #addService select');
    let isValid = true;

    inputs.forEach(input => {
        // Check if the input is empty
        if (input.value.trim() === '') {
            input.classList.add('is-invalid');
            isValid = false;
            return;
        }

        // Remove the invalid class if input is valid
        input.classList.remove('is-invalid');
    });

    // Submit the form if all inputs are valid
    if (isValid) {
        const form = document.getElementById('addService');
        const formData = new FormData(form);

        // Disable the button while submitting
        addButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
        addButton.disabled = true;

        // Perform form submission using the Fetch API
        fetch('../classes/service.php?f=add_service', {
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
                // Handle any errors
                console.error('Error:', error);
            })
            .finally(() => {
                // Enable the button after submission
                addButton.innerHTML = 'Submit';
                addButton.disabled = false;
            });
    }
}

// const addForm = document.getElementById('addService');
// addForm.addEventListener('submit', submitAddForm);


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

function fetchServices() {
    const table = $('#datatable').DataTable();
    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable').DataTable({
        ajax: {
            url: `../classes/service.php?f=fetch_services`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    title: delivery.title,
                    status: delivery.status === '1' ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>',
                    theStatus: delivery.status,
                    createdAt: "<small>" + formatDateTime(delivery.createdAt) + "</small>",
                    serviceId: delivery.serviceId
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
            { data: 'title' },
            { data: 'createdAt' },
            { data: 'status' },
            {
                data: null,
                render: function (data, type, row) {
                    if (data.theStatus === "1") {
                        return `
                    <a type="button" data-bs-toggle="modal" class="btn btn-primary edit-btn btn-sm p-2" onclick="editService(${data.serviceId}, '${data.title}')" data-delivery-id="${data.serviceId}">
                      <i class="fa fa-edit"></i>
                    </a>
                    <a type="button" data-bs-toggle="modal" class="btn btn-warning delete-btn btn-sm p-2" onclick="deactivateService(${data.serviceId})" >
                    <i class="fa fa-times"></i>
                  </a>
                    
                    <a type="button" data-bs-toggle="modal" class="btn btn-danger delete-btn btn-sm p-2" onclick="deleteService(${data.serviceId})" data-delivery-id="${data.serviceId}">
                      <i class="fa fa-trash"></i>
                    </a>`;
                    } else {
                        return `
                        <a type="button" data-bs-toggle="modal" class="btn btn-primary edit-btn btn-sm p-2" onclick="editService(${data.serviceId}, '${data.title}')" data-delivery-id="${data.serviceId}">
                          <i class="fa fa-edit"></i>
                        </a>
                        <a type="button" data-bs-toggle="modal" class="btn btn-success activate-btn btn-sm p-2" onclick="activateService(${data.serviceId})">
                        <i class="fa fa-check"></i>
                      </a>
                        
                        <a type="button" data-bs-toggle="modal" class="btn btn-danger delete-btn btn-sm p-2" onclick="deleteService(${data.serviceId})" data-delivery-id="${data.serviceId}">
                          <i class="fa fa-trash"></i>
                        </a>`;
                    }

                }
            }
        ]
    });

    // Attach a click event listener to the eye buttons
    $('#datatable').on('click', '.view-btn', function () {
        const serviceId = $(this).data('delivery-id');

        // Fetch individual delivery details using the deliveryId
        // fetchModalDetails(AdminId);
    });

    $('#datatable').on('click', '.edit-btn', function () {
        const serviceId = $(this).data('delivery-id');

        // Fetch individual delivery details using the deliveryId
        // fetchModalDetails(AdminId);
    });



}

function closeModal(modal) {
    $('#' + modal).modal('hide');
}

function addService(event) {
    event.preventDefault();
    var form = document.getElementById("addServiceForm");
    var formData = new FormData(form);
    fetch('..//classes/service.php?f=add_service', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            // Close the modal box
            $('#addServiceModalBox').modal('hide');
            fetchServices();


        })
        .catch(error => {
            console.error(error);
        });
}

function updateService(event) {
    event.preventDefault();
    var form = document.getElementById("editServiceForm");
    var formData = new FormData(form);
    fetch('..//classes/service.php?f=update_service', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            // Close the modal box
            $('#editServiceModalBox').modal('hide');
            fetchServices();
        })
        .catch(error => {
            console.error(error);
        });
}


function editService(serviceId, title) {
    var newTitle = document.getElementById("editServiceTitle");
    document.getElementById("editServiceId").value = serviceId;
    newTitle.value = title;
    $('#editServiceModalBox').modal('show');
}

function deleteService(serviceId) {
    // Show the confirm dialogue
    if (confirm('Are you sure you want to delete this service?')) {
        var formData = new FormData();
        formData.append("serviceId", serviceId);

        fetch('..//classes/service.php?f=delete_service', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                console.log(result);
                // Close the modal box
                // $('#editServiceModalBox').modal('hide');
                fetchServices();
            })
            .catch(error => {
                console.error(error);
            });
    }
}

function deactivateService(serviceId) {

    var formData = new FormData();
    formData.append("serviceId", serviceId);
    formData.append("status", 'null');
    fetch('..//classes/service.php?f=update_service_status', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            fetchServices();


        })
        .catch(error => {
            console.error(error);
        });
}

function activateService(serviceId) {

    var formData = new FormData();
    formData.append("serviceId", serviceId);
    formData.append("status", 1);
    fetch('..//classes/service.php?f=update_service_status', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            fetchServices();


        })
        .catch(error => {
            console.error(error);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    fetchServices();
});