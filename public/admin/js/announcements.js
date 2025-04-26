

// Function to format date with time
function formatDateTime(dateTime) {
    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric'};
    const storedDate = new Date(dateTime); 
    const existingDate = new Date(storedDate);
    const modifiedDate = existingDate.setHours(storedDate.getHours() + 1);
    const newDate = new Date(modifiedDate);
    return newDate.toLocaleString('en-US', options).substring(0,25);
}

// format special characters
function formatSpecialCar(text){
    var doc = new DOMParser().parseFromString(text, "text/html");
  return doc.body.textContent;
}

function fetchAnnouncements() {
    const table = $('#datatable').DataTable();
    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable').DataTable({
        ajax: {
            url: `../classes/announcements.php?f=fetch_announcements`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    title: formatSpecialCar(delivery.title),
                    status: delivery.status === 1 ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>',
                    createdAt: "<small>" + formatDateTime(delivery.createdAt) + "</small>",
                    action: `
                    <button id="viewAnnouncementBtn" onclick="getAnnouncement(${delivery.announcementId})" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></button> 
                    <button id="viewAnnouncementBtn" onclick="editAnnouncement(${delivery.announcementId})" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></button>
                    <button id="deleteAnnouncementBtn" onclick="deleteAnnouncement(${delivery.announcementId})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>`
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
            { data: 'status' },
            { data: 'createdAt' },
            { data: 'action' },
        ]
    });

    $('#datatable').on('click', '.edit-btn', function () {
        const billerId = $(this).data('delivery-id');

        // Fetch individual delivery details using the deliveryId
        // fetchModalDetails(AdminId);
    });
}

function getAnnouncement(announcementId) {
    fetch('../classes/announcements.php?f=get_announcement&announcementId=' + announcementId)
        .then(response => response.json())
        .then(package => {
            // Populate the form with the fetched data
            document.getElementById('viewAnnouncementTitle').textContent = formatSpecialCar(package.title);
            document.getElementById('viewAnnouncementBody').textContent = formatSpecialCar(package.body);
            document.getElementById('viewAnnouncementStatus').textContent = package.status === 1 ? 'Active' : 'Inactive';
            // Open the view modal
            $('#viewAnnouncementModal').modal('show');
        })
        .catch(error => {
            console.error('Error fetching announcement details:', error);
        });
}

function deleteAnnouncement(announcementId) {
    // Show the confirm dialogue
    if (confirm('Are you sure you want to delete this Announcement?')) {
        var formData = new FormData();
        formData.append("announcementId", announcementId);

        fetch('..//classes/announcements.php?f=delete_announcement', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                console.log(result);
                fetchAnnouncements();
            })
            .catch(error => {
                console.error(error);
            });
    }
}

function closeModal(modal) {
    $('#' + modal).modal('hide');
}

function addAnnouncement(event) {
    event.preventDefault();
    var form = document.getElementById("addAnnouncementForm");
    var formData = new FormData(form);
    fetch('..//classes/announcements.php?f=add_announcement', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            // Close the modal box
            $('#addAnnouncementModal').modal('hide');
            // fetchAnnouncements();


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

function updateAnnouncement(event) {
    event.preventDefault();
    var form = document.getElementById("editAnnouncementForm");
    var formData = new FormData(form);
    fetch('..//classes/announcements.php?f=update_announcement', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            // Close the modal box
            $('#editAnnouncementModal').modal('hide');
            fetchAnnouncements();
        })
        .catch(error => {
            console.error(error);
        });
}

function editAnnouncement(announcementId) {
    fetch('../classes/announcements.php?f=get_announcement&announcementId=' + announcementId)
        .then(response => response.json())
        .then(package => {
            // Populate the form with the fetched data
            document.getElementById("editAnnouncementId").value = package.announcementId;
            document.getElementById("editAnnouncementTitle").value = package.title;
            document.getElementById("editAnnouncementBody").value = package.body;
            // Open the view modal
            $('#editAnnouncementModal').modal('show');
        })
        .catch(error => {
            console.error('Error fetching announcement details:', error);
        });
}


document.addEventListener('DOMContentLoaded', function () {
    fetchAnnouncements();
});
