const captionTitle = document.getElementById("caption-title");
const walletBalance = document.getElementsByClassName("wallet-balance");
const walletBalanceArray = Array.from(walletBalance);
const announcementSpan = document.getElementById("marquee");
const networkItem = document.getElementById('network');
const total_summary = document.getElementById("total_summary");
const buyDataButton = document.getElementById("buy-data-button");
const buyDataButtonpaySpan = document.getElementById("buyDataButtonpaySpan");
const categorySelect = document.getElementById('category');
const packageSelect = document.getElementById('newPackage');

var userId = null;
var walletId = null;
var userName = null;
var userEmail = null;
var userFName = null;
var userLName = null;
var pin;
var userPhone = null;
var myWalletBalance = null;

let validReceipients = null;
let dataCardTotalAmount = 0;
let dataNetworkOperator = null;
let dataPackage = null;
let dataPlan = null;
let amountMoreRequired = 0;
let dataPackagePrice = 0;
let packageName = null;
let operatorName = null;
let dataCardQuantity = 0;

// check if the service is available
function checkService () {
    // Assuming you have a service ID, replace '123' with the actual service ID
    var serviceId = 'Data Card';
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

// handle network change
networkItem.addEventListener('change', () => {
    // Uncheck all other network operator items
    networkItem.values = '';
    // Reset the category and package selection to empty
    packageSelect.value = '';
    const network = networkItem.value;
    // Check the clicked network operator item
    dataNetworkOperator = networkItem.value;
    network == 1 ? operatorName = 'MTN' : 
    network == 2 ? operatorName = 'AIRTEL' :
    network == 3 ? operatorName = 'GLO' :
    operatorName =  '9MOBILE';

    // Call the function to fetch and show packages
    fetchPackages(networkItem.value, 'DataCard');
});
// Function to fetch and display packages
function fetchPackages(networkOperatorId, category) {
    buyDataButton.disabled = true;
    // Fetch packages data from the server
    $.ajax({
        url: `/user/fetch-packages/${networkOperatorId}/${category}/Data Card`,
        type: 'GET',
        success: function (response) {
            // Handle the response from the server
            const container = document.getElementById('newPackage');
            container.innerHTML = '<option value="">Choose..</option>';
            response.forEach(package => {
                const option = document.createElement('option');
                option.id = package.id;
                option.label = package.title;
                option.value = package.id;
                // Append column to the container
                container.appendChild(option);
                // Add change event listener to the package select element
                container.addEventListener('change', () => {
                    const selectedPackageId = container.value;
                    const selectedPackage = response.find(package => String(package.id) === selectedPackageId);
                    if (selectedPackage) {
                        console.log("Changed to: " + selectedPackage.id + " Price: " + selectedPackage.price);
                        dataPackage = selectedPackage.packageId;
                        dataPackagePrice = selectedPackage.price;
                        dataPlan = selectedPackage.plan;
                        packageName = selectedPackage.title;
                        document.getElementById('dataPlan').value = selectedPackage.plan;
                        // Call the action passing the package variables
                        processPackage(selectedPackage);
                    } else {
                        console.log("Not selected");
                    }
                });
            });
        },
        error: function (error) {
            // Handle errors
            console.error('Error:', error);
        }
    });
    
}

function processPackage(package) {
    buyDataButton.disabled = true;
    const quantityInput = document.getElementById('quantity');
    const totalSpan = document.getElementById('total-span');

    // console.log(quantityInput);
    quantityInput.addEventListener('input', function () {
        const quantityInputString = this.value;
        const packagePrice = package.price;

        if(quantityInputString > 0){
            let totalAmount = quantityInputString * packagePrice;
            buyDataButton.disabled = false;
            // store the total amount
            dataCardTotalAmount = totalAmount;
            dataCardQuantity = quantityInputString;
            totalSpan.textContent = formatNumber(totalAmount);  // Update the total cost with formatted number            
            document.getElementById('total').value = formatNumber(totalAmount);
            document.getElementById('billerName').value = operatorName;
            document.getElementById('packageName').value = packageName;
        }else{
            buyAirtimePinButton.disabled = true;
        }
        
    });

    // console.error(package);
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

function logValidNumbers(validNumbers, totalAmount) {
    // console.log(validNumbers);
    // store the values 
    validReceipients = validNumbers;
    dataCardTotalAmount = totalAmount;
}

function isWalletBalanceSufficient() {
    var formattedBalance = parseFloat(myWalletBalance.replace(/,/g, ''));
    var formattedAmount = parseFloat(dataCardTotalAmount.toString().replace(/,/g, ''));

    if (formattedBalance >= formattedAmount) {
        return true; // Wallet balance is sufficient
    } else {
        var difference = formattedAmount - formattedBalance;
        amountMoreRequired = difference;
        return false; // Wallet balance is not sufficient
    }


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
            if(data[0].tokenType == '01'){ var text = 'Prepaid'}else{var text = 'Postpaid'}
            // Update the spans with the fetched data
            $('#viewDeliveryDetails1-code').text(data[0].orderCode);
            $('#viewDeliveryDetails1-type').text(data[0].type);
            $('#viewDeliveryDetails1-amount').text(formatNumber(data[0].amount));
            $('#viewDeliveryDetails1-total').text(formatNumber(data[0].total));
            $('#viewDeliveryDetails1-balance').text(formatNumber(data[0].balance));
            $('#viewDeliveryDetails1-package').text(data[0].package_name);
            $('#viewDeliveryDetails1-biller').text(data[0].product);
            $('#viewDeliveryDetails1-meterType').text(data[0].tokenType === '01' ? 'Prepaid' : 'PostPaid');
            $('#viewDeliveryDetails1-meterNo').text(data[0].meterNo);
            $('#viewDeliveryDetails1-token').text(data[0].pin);
            $('#viewDeliveryDetails1-status').html(data[0].delivery_status === '1' ? '<span class="text-success">Success</span>' : '<span class="text-danger">Failed</span>');
            $('#viewDeliveryDetails1-date').text(formatDateTime(data[0].createdAt));

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
            url: `../classes/DataCard.php?f=fetch_deliveries`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    reference: delivery.reference,
                    product: delivery.product,
                    amount: '<span class="text-primary">&#8358;' + delivery.amount + "</span>",
                    token: "<small>" + delivery.pin + "</small>",
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
            { data: 'product' },
            { data: 'amount' },
            { data: 'token' },
            { data: 'reference' },
            { data: 'status' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <a type="button" data-bs-toggle="modal" class="btn btn-primary btn-sm" data-bs-target="#viewElectricityDeliveryInfo" data-delivery-id="${data.deliveryId}">
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

function toggleQuickTopup(event) {
    event.preventDefault();
    document.getElementById('data_topup_form').style.display = "none";
    document.getElementById('quick-topup').style.display = "block";
    document.getElementById("amount").value = amountMoreRequired;

}

function toggleBack(event) {
    event.preventDefault();
    document.getElementById('quick-topup').style.display = "none";
    document.getElementById('data_topup_form').style.display = "block";

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

function showDataSuccessMessage() {
    // After successful payment
    document.getElementById("error_message").style.display = "none";
    document.getElementById('data_topup_form').style.display = 'none';
    document.getElementById('successFull').style.display = 'block';

    // Clear form after 5 seconds
    setTimeout(function () {
        document.getElementById("error_message").style.display = "none";
        document.getElementById('data_topup_form').reset();
        document.getElementById('data_topup_form').style.display = 'block';
        document.getElementById('successFull').style.display = 'none';
    }, 8000); // Change the duration as needed (in milliseconds)
}

document.addEventListener('DOMContentLoaded', function () {
    setInterval(fetchUserInformation, 3000);
    fetchRecentDelivery();
    checkService();
});
