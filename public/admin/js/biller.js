function handleInput(event) {
    const input = event.target;
    const value = input.value.trim();

    // Remove any existing validation classes
    input.classList.remove('is-valid', 'is-invalid');

    if (input.id === 'title') {
        // Send request to check username
        if (value === '') {
            title_message.innerText = "";
        } else {
            fetch(`../classes/biller.php?f=check_title&value=${encodeURIComponent(value)}`)
                .then(response => response.json())
                .then(data => {
                    // Handle response
                    if (data.exists) {
                        input.classList.add('is-invalid');
                        title_message.classList.add('text-danger');
                        title_message.innerText = "This title already exists! Use a new one instead";
                    } else {
                        title_message.classList.remove('text-danger');
                        title_message.innerText = "";
                        input.classList.add('is-valid');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    title_message.innerText = "error";

                });
        }
    }
}
// Function to handle the add form submission
function submitAddForm(event) {
    event.preventDefault();

    const inputs = document.querySelectorAll('#addBiller input, #addBiller select');
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
        const form = document.getElementById('addBiller');
        const formData = new FormData(form);

        // Disable the button while submitting
        addButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
        addButton.disabled = true;

        // Perform form submission using the Fetch API
        fetch('../classes/biller.php?f=add_biller', {
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
// const addForm = document.getElementById('addBiller');
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

function fetchBillers() {
    const table = $('#datatable').DataTable();
    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable').DataTable({
        ajax: {
            url: `../classes/biller.php?f=fetch_billers`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    title: delivery.title,
                    createdAt: "<small>" + formatDateTime(delivery.createdAt) + "</small>",
                    status: delivery.status === '1' ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>',
                    theStatus: delivery.status,
                    billerId: delivery.billerId
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
                    console.log("status: " + data.status);
                    if (data.theStatus === "1") {
                        return `
                        <a type="button" data-bs-toggle="modal" class="btn btn-primary edit-btn btn-sm p-2" onclick="editBiller(${data.billerId}, '${data.title}')" data-delivery-id="${data.billerId}">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a type="button" data-bs-toggle="modal" class="btn btn-warning delete-btn btn-sm p-2" onclick="deactivateBiller(${data.billerId})" data-delivery-id="${data.billerId}">
                    <i class="fa fa-times"></i>
                  </a>
                    <a type="button" data-bs-toggle="modal" class="btn btn-danger delete-btn btn-sm p-2" onclick="deleteBiller(${data.billerId})" data-delivery-id="${data.billerId}">
                        <i class="fa fa-trash"></i>
                    </a>
                         `;
                    } else {
                        return `
                        <a type="button" data-bs-toggle="modal" class="btn btn-primary edit-btn btn-sm p-2" onclick="editBiller(${data.billerId}, '${data.title}')" data-delivery-id="${data.billerId}">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a type="button" data-bs-toggle="modal" class="btn btn-success activate-btn btn-sm p-2" onclick="activateBiller(${data.billerId})" data-delivery-id="${data.billerId}">
                            <i class="fa fa-check"></i>
                          </a>
                    <a type="button" data-bs-toggle="modal" class="btn btn-danger delete-btn btn-sm p-2" onclick="deleteBiller(${data.billerId})" data-delivery-id="${data.billerId}">
                        <i class="fa fa-trash"></i>
                    </a>
                          `;
                    }

                }
            }
        ]
    });

    $('#datatable').on('click', '.edit-btn', function () {
        const billerId = $(this).data('delivery-id');

        // Fetch individual delivery details using the deliveryId
        // fetchModalDetails(AdminId);
    });


}

function deleteBiller(billerId) {
    // Show the confirm dialogue
    if (confirm('Are you sure you want to delete this biller?')) {
        var formData = new FormData();
        formData.append("billerId", billerId);

        fetch('..//classes/biller.php?f=delete_biller', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                console.log(result);
                fetchBillers();
            })
            .catch(error => {
                console.error(error);
            });
    }
}

function closeModal(modal) {
    $('#' + modal).modal('hide');
}

function addBiller(event) {
    event.preventDefault();
    var form = document.getElementById("addBillerForm");
    var formData = new FormData(form);
    fetch('..//classes/biller.php?f=add_biller', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            // Close the modal box
            $('#addBillerModalBox').modal('hide');
            fetchBillers();


        })
        .catch(error => {
            console.error(error);
        });
}

function deactivateBiller(billerId) {

    var formData = new FormData();
    formData.append("billerId", billerId);
    formData.append("status", 0);
    fetch('..//classes/biller.php?f=update_biller_status', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            fetchBillers();


        })
        .catch(error => {
            console.error(error);
        });
}

function activateBiller(billerId) {

    var formData = new FormData();
    formData.append("billerId", billerId);
    formData.append("status", 1);
    fetch('..//classes/biller.php?f=update_biller_status', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            fetchBillers();


        })
        .catch(error => {
            console.error(error);
        });
}

function updateBiller(event) {
    event.preventDefault();
    var form = document.getElementById("editBillerForm");
    var formData = new FormData(form);
    fetch('..//classes/biller.php?f=update_biller', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            // Close the modal box
            $('#editBillerModalBox').modal('hide');
            fetchBillers();
        })
        .catch(error => {
            console.error(error);
        });
}

function editBiller(billerId, title) {
    var newTitle = document.getElementById("editBillerTitle");
    document.getElementById("editBillerId").value = billerId;
    newTitle.value = title;
    $('#editBillerModalBox').modal('show');
}


document.addEventListener('DOMContentLoaded', function () {
    fetchBillers();
});