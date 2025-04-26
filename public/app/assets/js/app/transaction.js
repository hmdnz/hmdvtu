const greetingSpan = document.getElementById("greetingSpan");
const captionTitle = document.getElementById("caption-title");
const walletBalance = document.getElementsByClassName("wallet-balance");
const walletBalanceArray = Array.from(walletBalance);
const walletIdentifierSpan = document.getElementById("walletIdentifierSpan");
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
var walletIdentifier = null;
var senderWalletId = null;

async function fetchUserInformation() {
    await fetch('../classes/User.php?f=fetch_user_information')
        .then(response => response.json())
        .then(data => {
            var name = data.firstName + " " + data.lastName;
            userId = data.userId;
            pin = data.pin;
            userName = data.username;
            userEmail = data.email;
            captionTitle.textContent = formatSpecialCar(name);
            userFName = data.firstName;
            userLName = data.lastName;
            window.gender = data.gender;
            userPhone = data.phone;
            walletBalanceArray.forEach(walletBalance => {
                walletBalance.textContent = Number(data.balance).toLocaleString();
            })
            walletId = data.walletId;
            senderWalletId = data.walletId;
            walletIdentifier = data.walletIdentifier;
            if (walletIdentifierSpan) {
                walletIdentifierSpan.innerHTML = data.walletIdentifier;
            }
            myWalletBalance = Number(data.balance).toLocaleString();


        })
        .catch(error => {
            // Handle any errors
            console.error('Error:', error);
        });
}

function fetchAnnouncements() {
    fetch('../classes/User.php?f=fetch_announcements')
        .then(response => response.json())
        .then(data => {
            var latestAnnouncement = data[0].body;
            var allAnnouncements = " ** "
            for (let index = 0; index < data.length; index++) {
                allAnnouncements += data[index].body + " ** ";
            }
            announcementSpan.textContent = 'Announcement: ' + allAnnouncements;
            if(currentPageTitle == 'index') {
                document.getElementById('viewAnnouncementBody').textContent = latestAnnouncement;
                // Open the view modal
                $('#viewAnnouncementModal').modal('show');
            }
        })
        // .catch(error => {
        //     // Handle any errors
        //     console.error('Error:', error);
        // });
}

function fetchRecentActivities() {
    const table = $('#datatable').DataTable();

    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable').DataTable({
        ajax: {
            url: `../classes/User.php?f=fetch_all_transactions`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    package: "<small>" + delivery.package_name + "</small>",
                    service: delivery.t_type,
                    balance: '<span class="text-primary">&#8358;' + formatNumber(delivery.balance) + "</span>",
                    amount: delivery.delivery_status === '1' ? '<span class="text-danger">-&#8358;' + formatNumber(delivery.amount) + "</span>" : '<span class="text-success">+&#8358;' + formatNumber(delivery.amount) + "</span>",
                    note: "<small>" + delivery.note + "</small>",
                    time: "<small>" + formatDateTime(delivery.createdAt) + "</small>",
                    transactionId: delivery.transactionId
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
            { data: 'service' },
            { data: 'amount' },
            { data: 'balance' },
            { data: 'note' },
            { data: 'time' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <a type="button" data-bs-toggle="modal" class="btn btn-primary btn-sm" data-bs-target="#viewDeliveryInfo" data-delivery-id="${data.transactionId}">
                            <i class="fa fa-eye"></i>
                        </a>`;
                }
            }
        ]
    });

    // Attach a click event listener to the eye buttons
    $('#datatable').on('click', '.btn-primary', function () {
        const transactionId = $(this).data('delivery-id');

        // Fetch individual delivery details using the deliveryId
        fetchDeliveryDetails(transactionId);
    });
}

function fetchDeliveryDetails(transactionId) {
    // Display loading spinner
    $('#viewDeliveryDetailsModal .modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');

    // Hide the details until the response is received
    $('#viewDeliveryDetailsModal .modal-body ul').hide();

    // Perform AJAX request to fetch individual delivery details using the deliveryId
    $.ajax({
        url: `../classes/User.php?f=fetch_transaction_details&transactionId=${transactionId}`,
        type: 'GET',
        success: function (data) {
            // Update the spans with the fetched data
            $('#viewDeliveryDetails-code').text(data[0].orderCode);
            $('#viewDeliveryDetails-type').text(data[0].type);
            $('#viewDeliveryDetails-amount').text(formatNumber(data[0].amount));
            $('#viewDeliveryDetails-total').text(formatNumber(data[0].total));
            $('#viewDeliveryDetails-balance').text(formatNumber(data[0].balance));
            $('#viewDeliveryDetails-package').text(data[0].package_name);
            $('#viewDeliveryDetails-biller').text(data[0].biller_name);
            $('#viewDeliveryDetails-note').text(data[0].note);
            $('#viewDeliveryDetails-receipients').text(data[0].recipients);
            $('#viewDeliveryDetails-status').html(data[0].delivery_status === '1' ? '<span class="text-success">Success</span>' : '<span class="text-danger">Failed</span>');
            $('#viewDeliveryDetails-date').text(formatDateTime(data[0].createdAt));

            // Show the details and remove loading spinner
            $('#viewDeliveryDetailsModal .modal-body ul').show();
            $('#viewDeliveryDetailsModal .modal-body > div').remove();
        },
        error: function (xhr, status, error) {
            // Display an error message if the request fails
            $('#viewDeliveryDetailsModal .modal-body').html('<div class="text-center text-danger">Failed to fetch delivery details.</div>');
        }
    });
}
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
// format special characters
function formatSpecialCar(text){
    var doc = new DOMParser().parseFromString(text, "text/html");
  return doc.body.textContent;
}

document.addEventListener('DOMContentLoaded', function () {   
    fetchUserInformation(); 
    fetchRecentActivities();
    fetchAnnouncements();
    setInterval(fetchUserInformation, 3000);
    // setInterval(function () {
    //     fetchRecentActivities();
    //     fetchAnnouncements();
    // }, 5000);
});