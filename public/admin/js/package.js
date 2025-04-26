
function fetchPackages() {
    const table = $('#datatable').DataTable();
    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable').DataTable({
        ajax: {
            url: `../classes/package.php?f=fetch_packages`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    title: delivery.title,
                    biller: delivery.biller,
                    type: delivery.type,
                    price: '<span class="text-primary">&#8358;' + delivery.price + "</span>",
                    status: delivery.status === '1' ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>',
                    theStatus: delivery.status,
                    packageId: delivery.packageId
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
            { data: 'biller' },
            { data: 'type' },
            { data: 'price' },
            { data: 'status' },
            {
                data: null,
                render: function (data, type, row) {
                    if (data.theStatus === "1") {
                        return `
                        <a type="button" data-bs-toggle="modal" class="btn btn-primary edit-btn btn-sm p-2" onclick="getPackage(${data.packageId})">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a type="button" data-bs-toggle="modal" class="btn btn-warning delete-btn btn-sm p-2" onclick="deactivatePackage(${data.packageId})">
                        <i class="fa fa-times"></i>
                      </a>
                        <a type="button" data-bs-toggle="modal" class="btn btn-danger delete-btn btn-sm p-2" onclick="deletePackage(${data.packageId})" data-delivery-id="${data.packageId}">
                            <i class="fa fa-trash"></i>
                        </a>`;
                    } else {
                        return `
                        <a type="button" data-bs-toggle="modal" class="btn btn-primary edit-btn btn-sm p-2" onclick="getPackage(${data.packageId})">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a type="button" data-bs-toggle="modal" class="btn btn-success activate-btn btn-sm p-2" onclick="activatePackage(${data.packageId})">
                        <i class="fa fa-check"></i>
                      </a>
                        <a type="button" data-bs-toggle="modal" class="btn btn-danger delete-btn btn-sm p-2" onclick="deletePackage(${data.packageId})" data-delivery-id="${data.packageId}">
                            <i class="fa fa-trash"></i>
                        </a>`;

                    }
                }
            }
        ]
    });

    $('#datatable').on('click', '.edit-btn', function () {
        const packageId = $(this).data('delivery-id');

        // Fetch individual delivery details using the deliveryId
        // fetchModalDetails(AdminId);
    });


}

function fetchBillers() {
    // Get the select element
    const billerSelect = document.getElementById('editPackageBillerSelect');

    // Fetch billers and populate the select element
    fetch('../classes/package.php?f=get_billers')
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Iterate over the billers and create options
            data.forEach(biller => {
                const option = document.createElement('option');
                option.value = biller.billerId;
                option.textContent = biller.title;
                billerSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching billers:', error);
        });
}

function fetchBillers2() {
    // Get the select element
    const billerSelect = document.getElementById('billerSelect');

    // Fetch billers and populate the select element
    fetch('../classes/package.php?f=get_billers')
        .then(response => response.json())
        .then(data => {
            // Iterate over the billers and create options
            data.forEach(biller => {
                const option = document.createElement('option');
                option.value = biller.billerId;
                option.textContent = biller.title;
                billerSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching billers:', error);
        });
}

function closeModal(modal) {
    $('#' + modal).modal('hide');
}

function addPackage(event) {
    event.preventDefault();
    var form = document.getElementById("addPackageForm");
    var formData = new FormData(form);
    fetch('../classes/package.php?f=add_package', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            // Close the modal box
            $('#addPackageModalBox').modal('hide');
            fetchPackages();


        })
        .catch(error => {
            console.error(error);
        });
}

function deactivatePackage(packageId) {

    var formData = new FormData();
    formData.append("packageId", packageId);
    formData.append("status", 0);
    fetch('..//classes/package.php?f=update_package_status', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            fetchPackages();


        })
        .catch(error => {
            console.error(error);
        });
}

function activatePackage(packageId) {

    var formData = new FormData();
    formData.append("packageId", packageId);
    formData.append("status", 1);
    fetch('..//classes/package.php?f=update_package_status', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            fetchPackages();


        })
        .catch(error => {
            console.error(error);
        });
}

function deletePackage(packageId) {
    if (confirm('Are you sure you want to delete this package?')) {
        var formData = new FormData();
        formData.append("packageId", packageId);
        fetch('..//classes/package.php?f=delete_package', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                console.log(result);
                fetchPackages();
            })
            .catch(error => {
                console.error(error);
            });
    }
}

function updatePackage(event) {
    event.preventDefault();
    var form = document.getElementById("editPackageForm");
    var formData = new FormData(form);
    fetch('../classes/package.php?f=update_package', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            // Close the modal box
            $('#editPackageModal').modal('hide');
            fetchPackages();


        })
        .catch(error => {
            console.error(error);
        });
}

// Function to fetch package details and populate the edit form
function getPackage(packageId) {
    fetch('../classes/package.php?f=get_package&packageId=' + packageId)
        .then(response => response.json())
        .then(package => {
            // Populate the form with the fetched data
            document.getElementById('editPackageTitle').value = package.title;
            document.getElementById('editPackageBillerSelect').value = package.billerId;
            document.getElementById('editPackageServiceSelect').value = package.service;
            document.getElementById('editPlanTypeSelect').value = package.planType;
            document.getElementById('editPackagePrice').value = package.price;
            document.getElementById('editPackageValidity').value = package.validity;
            document.getElementById('editPackageSize').value = package.size;
            document.getElementById('editPackageTypeSelect').value = package.type;
            document.getElementById('editPackageCode').value = package.plan;
            document.getElementById('editPackageId').value = packageId;

            // Open the edit modal
            $('#editPackageModal').modal('show');
        })
        .catch(error => {
            console.error('Error fetching package details:', error);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    fetchPackages();
    fetchBillers();
    fetchBillers2();
});