const totalSpan = document.getElementById("totalSpan");
const senderInput = document.getElementById("sender");
const numberInput = document.getElementById('numbers');
const messageInput = document.getElementById('sms-message');
const buySMSButton = document.getElementById("buy-sms-btn");
const buySMSButtonSpan = document.getElementById("buySMSButtonSpan");

let validNumbersCount = 0;
let smsMessage = null;
let smsSenderId = null;
let packageName = null;
let smsPackage = null;
let smsPackagePrice = 0;
let smsTotalAmount = 0;
let validReceipients = null;

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


// check if the service is available
function checkService () {
    // Assuming you have a service ID, replace '123' with the actual service ID
    var serviceId = 'bulk SMS';
    // Make AJAX request
    $.ajax({
        url: '/user/checkService/' + serviceId,
        type: 'GET',
        success: function (response) {
            // Handle the response from the server
            if(response.status){
                console.log('The service is active and working');
            }else{
                $("#network").prop("disabled", true);
                $('#serviceNotification').show();
            }
        },
        error: function (error) {
            // Handle errors
            console.error('Error:', error);
        }
    });
};

// close modal
function closeModal(modal) {
    // console.log(modal);
    $('#' + modal).remove();
    // $('#' + modal).modal('hide');
}

function calculateTotalAmount() {
    let phoneNumberString = numberInput.value.trim();
    let phoneNumbers = phoneNumberString.split(/[,\n\s]+/); // Split numbers by comma, space, or new line

    const uniquePhoneNumbers = [...new Set(phoneNumbers)];

    const numberCounts = {}; // Track the count of each unique number

    uniquePhoneNumbers.forEach((phoneNumber) => {
        if (isValidNigeriaPhoneNumber(phoneNumber)) {
            if (numberCounts[phoneNumber]) {
                numberCounts[phoneNumber] += 1;
            } else {
                numberCounts[phoneNumber] = 1;
            }
        }
    });

    let validNumbers = '';
    let validNumberCount = 0; // Track the count of valid numbers

    for (const phoneNumber in numberCounts) {
        const count = numberCounts[phoneNumber];
        validNumbers += `${phoneNumber}, `;
        validNumberCount++;
        buySMSButton.disabled = false;
    }

    // Calculate the amount user should pay based on the phone numbers
    amountToPay = smsPackagePrice * validNumberCount;
    smsTotalAmount = amountToPay;
    validNumbersCount = validNumberCount;
    document.getElementById('total').value = smsTotalAmount;
    document.getElementById('package').value = smsPackage;
    document.getElementById('packageName').value = packageName;

    // Display the discounted amount and discount information
    const formattedAmount = formatNumber(smsPackagePrice);
    let formattedAmountToPay = formatNumber(amountToPay);
    const discountInfo = `${formattedAmount} x ${validNumberCount} = &#8358;${formattedAmountToPay} `;

    // total_summary.innerHTML = discountInfo;
    totalSpan.innerHTML = ` - Pay: &#8358;${formattedAmountToPay}`;

    validNumbers = validNumbers.slice(0, -2); // Remove the trailing comma and space
    validReceipients = validNumbers;
    
    checkInputs();
}
senderInput.addEventListener('input', function () {
    smsSenderId = this.value;
});


function toggleBack(event) {
    event.preventDefault();
    document.getElementById('quick-topup').style.display = "none";
    document.getElementById('sms-form').style.display = "block";
}

// Function to format the number with commas
function formatNumber(number) {
    return new Intl.NumberFormat().format(number);
}

function isValidNigeriaPhoneNumber(phoneNumber) {
    // Validate the phone number format for Nigeria (11 digits)
    const nigeriaPhoneNumberRegex = /^(?:\+?234|0)?[789]\d{9}$/;
    return nigeriaPhoneNumberRegex.test(phoneNumber);
}

function handleMessageCharacters() {
    const messageInput = document.getElementById('sms-message');
    const message = messageInput.value;
    smsMessage = message;
    const maxLength = 160;
    let remainingChars = maxLength - message.length;

    // Update remaining characters count
    remainingChars = Math.max(0, remainingChars); // Set to 0 if it goes below 0
    // charCountElement.textContent = `${remainingChars} characters remaining (out of ${maxLength})`;

    // Perform additional actions when character limit is reached
    if (remainingChars === 0) {
        messageInput.value = message.slice(0, maxLength); // Truncate message to 160 characters
        // Perform additional actions here (e.g., show error message, disable submit button)
    }

    // Enable input for further editing
    messageInput.disabled = false;

    checkInputs(); // Check input validity

}

function checkInputs() {
    // Check if all inputs are valid and disable the button if not
    const validMessage = messageInput.value.length > 0 && messageInput.value.length <= 160;
    const validSender = senderInput.value.trim() !== "";
    validNumbers = validNumbersCount > 0;
    buySMSButton.disabled = !(validMessage && validNumbers && validSender);
}

function fetchPackages(category) {
    // Fetch packages data from the server
    $.ajax({
        url: `/user/fetch-packages/${category}/${category}/Bulk SMS`,
        type: 'GET',
        success: function (response) {
            smsPackagePrice = response[0].price;
            packageName = response[0].title;
            smsPackage = response[0].id;
        },
        error: function (error) {
            // Handle errors
            console.error('Error:', error);
        }
    });
    
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

function fetchDeliveryDetails(deliveryId) {
    // Display loading spinner
    $('#viewDeliveryDetailsModal .modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');

    // Hide the details until the response is received
    $('#viewDeliveryDetailsModal .modal-body ul').hide();

    // Perform AJAX request to fetch individual delivery details using the deliveryId
    $.ajax({
        url: `../classes/Data.php?f=fetch_delivery_details&deliveryId=${deliveryId}`,
        type: 'GET',
        success: function (data) {
            // console.log(data);
            // Update the spans with the fetched data
            $('#viewSMSDetails-code').text(data[0].orderCode);
            $('#viewSMSDetails-type').text(data[0].type);
            $('#viewSMSDetails-amount').text(formatNumber(data[0].amount));
            $('#viewSMSDetails-total').text(formatNumber(data[0].total));
            $('#viewSMSDetails-balance').text(formatNumber(data[0].balance));
            $('#viewSMSDetails-package').text(data[0].package_name);
            $('#viewSMSDetails-senderId').text(data[0].senderId);
            $('#viewSMSDetails-beneficiaries').text(data[0].recipients);
            $('#viewSMSDetails-message').text(data[0].message);
            $('#viewSMSDetails-status').html(data[0].delivery_status === '1' ? '<span class="text-success">Success</span>' : '<span class="text-danger">Failed</span>');
            $('#viewSMSDetails-date').text(formatDateTime(data[0].createdAt));

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

function fetchRecentDelivery() {
    const table = $('#datatable').DataTable();

    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable').DataTable({
        ajax: {
            url: `../classes/User.php?f=fetch_sms_delivery`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    package: delivery.package_name,
                    service: delivery.dtype,
                    senderId: delivery.senderId,
                    amount: '<span class="text-primary">&#8358;' + delivery.amount + "</span>",
                    total: '<span class="text-primary">&#8358;' + delivery.total + "</span>",
                    beneficiaries: "<small>" + delivery.beneficiaries + "</small>",
                    status: delivery.delivery_status === '1' ? '<span class="text-success">Success</span>' : '<span class="text-danger">Failed</span>',
                    deliveryId: delivery.deliveryId
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
            { data: 'package' },
            { data: 'amount' },
            { data: 'total' },
            { data: 'senderId' },
            { data: 'beneficiaries' },
            { data: 'status' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <a type="button" data-bs-toggle="modal" class="btn btn-primary btn-sm" data-bs-target="#viewSMSDeliveryInfo" data-delivery-id="${data.deliveryId}">
                            <i class="fa fa-eye"></i>
                        </a>`;
                }
            }
        ]
    });

    // Attach a click event listener to the eye buttons
    $('#datatable').on('click', '.btn-primary', function () {
        const deliveryId = $(this).data('delivery-id');

        // Fetch individual delivery details using the deliveryId
        fetchDeliveryDetails(deliveryId);
    });
}

numberInput.addEventListener('input', calculateTotalAmount);
messageInput.addEventListener('input', handleMessageCharacters);
senderInput.addEventListener('input', checkInputs);


document.addEventListener('DOMContentLoaded', function () {
    // fetchRecentDelivery();
    checkInputs();
    fetchPackages('BulkSMS');
    checkService();
    // setTimeout(function () {
    //     fetchRecentDelivery();
    // }, 5000);
});

