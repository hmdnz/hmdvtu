const captionTitle = document.getElementById("caption-title");
const walletBalance = document.getElementsByClassName("wallet-balance");
const walletBalanceArray = Array.from(walletBalance);
const announcementSpan = document.getElementById("marquee");
const buyEducationButton = document.getElementById("buy-education-button");
const errorMessage = document.getElementById('error_message');
const buyEducationButtonpaySpan = document.getElementById("buyEducationButtonpaySpan");
const exam = document.getElementById("exam");
const packageContainer = document.getElementById("packageContainer");
const packageSelect = document.getElementById('newPackage');
const amountInput = document.getElementById('amount');

let educationExam = null;
let educationTotalAmount = 0;
let educationPackage = null;
let educationPackageName = null;
let amountMoreRequired = 0;
let educationPackagePrice = 0;

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
            myWalletBalance = Number(data.balance).toLocaleString();

        })
        .catch(error => {
            // Handle any errors
            console.error('Error:', error);
        });
}
// check if the service is available
function checkService(){    
    fetch(`../classes/User.php?f=check_service_status&value=Education Pin`)
    .then(response => response.json())
    .then(data => {
        if(data){
            // alert('Active');
        }else{
            document.getElementById("exam").disabled = true;
            $('#serviceNotification').show();
        }
    })
    .catch(error => {
        console.error('Error checking service status:', error);
    });
}
// close modal
function closeModal(modal) {
    // console.log(modal);
    $('#' + modal).remove();
    // $('#' + modal).modal('hide');
}

document.getElementById("pin").addEventListener('input', () => {
    var pin = document.getElementById("pin").value;
    fetch(`../classes/User.php?f=check_pin&value=${pin}&userId=${userId}`)
    .then(response => response.json())
    .then(data => {
        if(data == true){
            buyEducationButton.style.display = 'block';
            document.getElementById("pin-message").style.display = 'none';
        }else{
            buyEducationButton.style.display = 'none';
            document.getElementById("pin-message").textContent = data;
            document.getElementById("pin-message").style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error checking pin status:', error);
    });
});

exam.addEventListener('change', function () {
    // Remove all options from the second select element
    while (packageSelect.options.length > 0) {
        packageSelect.remove(0);
    }
    educationExam = exam.value;
    fetchPackages(educationExam);
});

function fetchPackages(exam) {
    packageContainer.style.display = 'block';
    // Fetch packages from the server
    fetch(`../classes/Education.php?f=fetch_packages&exam=${exam}`)
        .then(response => response.json())
        .then(data => {
            // Clear existing options
            packageSelect.innerHTML = '';
            const selectedOption = document.createElement('option');
            selectedOption.label = 'Choose..';
            selectedOption.value = null;
            packageSelect.appendChild(selectedOption);
            data.forEach(package => {
                const option = document.createElement('option');
                option.id = package.packageId;
                option.label = package.title;
                option.value = package.packageId;

                // Append option to the packageSelect
                packageSelect.appendChild(option);
            });

            // Add change event listener to the package select element
            packageSelect.addEventListener('change', () => {
                const selectedPackageId = packageSelect.value;
                const selectedPackage = data.find(package => String(package.packageId) === selectedPackageId);
                if (selectedPackage) {
                    console.log(selectedPackage);
                    educationPackage = selectedPackage.packageId;
                    educationPackagePrice = selectedPackage.price;
                    educationTotalAmount = selectedPackage.price;
                    educationPackageName = selectedPackage.title;
                    amountInput.value = selectedPackage.price;
                } else {
                    console.log("Not selected");
                }
            });
        })
        .catch(error => {
            console.error('Error fetching  data:', error);
        });

}

function processBuyEducation(event) {
    event.preventDefault();
    isSufficient = isWalletBalanceSufficient();
    // first check if the wallet balance is sufficient for the amount
    if (isSufficient) {
        errorMessage.style.display = "none";
        errorMessage.innerHTML = "";
        buyEducationButtonpaySpan.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';
        //arrange the formData and submit
        let formData = new FormData();
        formData.append("userId", userId);
        formData.append("package", educationPackage);
        formData.append("total", educationTotalAmount);
        formData.append("exam", educationExam);
        formData.append("price", educationPackagePrice);
        formData.append("status", 1);
        formData.append("name", userName);
        formData.append("email", userEmail);
        formData.append("packageName", educationPackageName);

        //send the request
        fetch("../classes/Education.php?f=buy_education", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("There was a problem buying education pin");
                } else {
                    return response.json();
                }
            })
            .then(data => {
                if (data.success) {
                    showDataSuccessMessage();
                } else {
                    errorMessage.style.display = "block";
                    errorMessage.innerText = data.message;
                    errorMessage.scrollIntoView({ behavior: 'smooth' });

                }
                console.log(data);
            })

            .catch((error) => {
                console.error(error);
                errorMessage.style.display = "block";
                errorMessage.innerText = "An error occured. Try again later";
                errorMessage.scrollIntoView({ behavior: 'smooth' });
                alert(error.message);
            })
            .finally(() => {
                // Enable submit button and hide loading spinner
                buyEducationButton.disabled = false;
                buyEducationButtonpaySpan.innerHTML = "";
            });



    } else { //wallet balance not enough
        errorMessage.style.display = "block";
        errorMessage.innerHTML = "Your wallet balance is insufficient for this amount. You need  &#8358;" + amountMoreRequired + " more to proceed.";
        // Scroll to the error message div
        errorMessage.scrollIntoView({ behavior: 'smooth' });
    }
}

function isWalletBalanceSufficient() {
    var formattedBalance = parseFloat(myWalletBalance.replace(/,/g, ''));
    var formattedAmount = parseFloat(educationTotalAmount.toString().replace(/,/g, ''));

    if (formattedBalance >= formattedAmount) {
        return true; // Wallet balance is sufficient
    } else {
        var difference = formattedAmount - formattedBalance;
        amountMoreRequired = difference;
        return false; // Wallet balance is not sufficient
    }

}

function toggleQuickTopup(event) {
    event.preventDefault();
    document.getElementById('airtime-pin-form').style.display = "none";
    document.getElementById('quick-topup').style.display = "block";
    document.getElementById("amount").value = amountMoreRequired;

}

function toggleBack(event) {
    event.preventDefault();
    document.getElementById('quick-topup').style.display = "none";
    document.getElementById('airtime-topup-form').style.display = "block";

}

function showDataSuccessMessage() {
    // After successful payment
    document.getElementById("error_message").style.display = "none";
    document.getElementById('airtime-topup-form').style.display = 'none';
    document.getElementById('successFull').style.display = 'block';


    // Clear form after 5 seconds
    setTimeout(function () {
        document.getElementById("error_message").style.display = "none";
        document.getElementById('airtime-topup-form').reset();
        payButton.disabled = true;
        document.getElementById('airtime-topup-form').style.display = 'block';
        document.getElementById('successFull').style.display = 'none';
    }, 8000);
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
            url: `../classes/User.php?f=fetch_education_delivery`,
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

fetchUserInformation();
document.addEventListener('DOMContentLoaded', function () {
    setInterval(fetchUserInformation, 3000);
    fetchRecentDelivery();
    checkService();
});
