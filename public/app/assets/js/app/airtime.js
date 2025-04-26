const captionTitle = document.getElementById("caption-title");
const walletBalance = document.getElementsByClassName("wallet-balance");
const walletBalanceArray = Array.from(walletBalance);
const announcementSpan = document.getElementById("marquee");
const networkItem = document.getElementById('network');
const buyAirtimeButton = document.getElementById("buy-airtime-button");
const errorMessage = document.getElementById('error_message');
const buyAirtimeButtonIcon = document.getElementById("buyAirtimeButtonIcon");
const buyAirtimeButtonLoading = document.getElementById("buyAirtimeButtonLoading");
const categorySelect = document.getElementById('category');
const packageSelect = document.getElementById('newPackage');

// user information

var userID = userId;
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
let validReceipients = null;
let dataTotalAmount = 0;
let amountMoreRequired = 0;
let operatorName = null;
let discountedAmount = 0;


let packagePrice = 0;
let packageName = null;

let recipient = null; 
let category = null;
let networkID = null;
let packageID = null;
let amount = null;
let total = null;



// check if the service is available
function checkService () {
    var serviceId = 'Airtime';
    // Make AJAX request
    $.ajax({
        url: '/user/checkService/' + serviceId,
        type: 'GET',
        success: function (response) {
            if(response.status){
                console.log('The service is active and working');
            }else{
                $("#network").prop("disabled", true);
                $('#serviceNotification').show();
            }
        },
        error: function (error) {
            console.error('Error:', error);
        }
    });
};

// check active category
function checkCategories(service, biller) {
    $.ajax({
        url: `/user/check-switches/${service}/${biller}`,
        type: 'GET',
        success: function (response) {
            while (categorySelect.options.length > 0) { categorySelect.remove(0); }
            categorySelect.innerHTML = '<option value="">Choose..</option>';
            response.forEach(package => {
                const option = document.createElement('option');
                option.id = package.title;
                option.label = package.title;
                option.value = package.title;
                categorySelect.appendChild(option);
            });
        },
        error: function (error) {
            console.error('Error:', error);
        }
    });
} 
// close modal
function closeModal(modal) {
    $('#' + modal).remove();
}

// handle network change
networkItem.addEventListener('change', () => {
    // Uncheck all other network operator items and packages 
    networkItem.values = '';
    packageSelect.value = '';
    networkID = networkItem.value;
    category = categorySelect.value;
    fetchPackages(networkID, category);
});

// Function to fetch and display packages
function fetchPackages(networkID, category) {
    buyAirtimeButton.disabled = true;
    $.ajax({
        url: `/user/fetch-packages/${networkID}/${category}/Airtime`,
        type: 'GET',
        success: function (response) {
            const container = document.getElementById('newPackage');
            container.innerHTML = '<option value="">Choose..</option>';
            response.forEach(package => {
                const option = document.createElement('option');
                option.id = package.id;
                option.label = package.title;
                option.value = package.id;
                container.appendChild(option);
                // Add click event listener to the package card
                container.addEventListener('change', () => {
                    packageID = package.id;
                    packagePrice = package.price;
                    packageName = package.title;
                    processPackage(package);
                });
            });
        },
        error: function (error) {
            console.error('Error:', error);
        }
    });
    
}
// process selected package
function processPackage(package) {
    buyAirtimeButton.disabled = true;
    const numberInput = document.getElementById('numbers');
    const totalSpan = document.getElementById('total-span');
    const amountInput = document.getElementById('amount-airtime');
    numberInput.addEventListener('input', calculateTotalAmount);
    amountInput.addEventListener('input', calculateTotalAmount);
    function calculateTotalAmount() {
        let providedAmount = amountInput.value;
        if (providedAmount != "") {
            numberInput.disabled = false;
        }
        
        let phoneNumberString = numberInput.value.trim();
        let phoneNumbers = phoneNumberString.split(/[,\n\s]+/); // Split numbers by comma, space, or new line
        const uniquePhoneNumbers = [...new Set(phoneNumbers)];
        let totalAmount = 0;
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
        let validNumberCount = 0; 
        for (const phoneNumber in numberCounts) {
            const count = numberCounts[phoneNumber];
            validNumbers += `${phoneNumber}, `;
            validNumberCount++;
            buyAirtimeButton.disabled = false;
        }
        // Calculate the amount user should pay based on the phone numbers
        amount = providedAmount * validNumberCount;
        // Apply the discount to the amount
        total = amount * (1 - package.price / 100);
        // Display the discounted amount and discount information
        const formattedAmount = formatNumber(providedAmount);
        const formattedAmountToPay = formatNumber(amount.toFixed(2));
        const formattedDiscountedAmount = formatNumber(total.toFixed(2));
        document.getElementById('total').value = total;
        totalSpan.innerHTML = ` - Pay: &#8358;${formattedDiscountedAmount} (${package.price}%)`;
        recipient = validNumbers.slice(0, -2); // Remove the trailing comma and space
        
    }
    calculateTotalAmount();
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
        buyAirtimeButton.disabled = true;
        document.getElementById('airtime-topup-form').style.display = 'block';
        document.getElementById('successFull').style.display = 'none';
    }, 8000);
}
// Function to format the number with commas
function formatNumber(number) {
    return new Intl.NumberFormat().format(number);
}
// format special characters
function formatSpecialCar(text){
    var doc = new DOMParser().parseFromString(text, "text/html");
  return doc.body.textContent;
}

function isValidNigeriaPhoneNumber(phoneNumber) {
    // Validate the phone number format for Nigeria (11 digits)
    const nigeriaPhoneNumberRegex = /^(?:\+?234|0)?[789]\d{9}$/;
    return nigeriaPhoneNumberRegex.test(phoneNumber);
}


document.addEventListener('DOMContentLoaded', function () {
    checkService();

    const form = document.getElementById("airtime-topup-form");
    form.addEventListener("submit", function (event) {
        event.preventDefault(); // prevent form submission until validation
        // show loading state
        buyAirtimeButton.disabled = true;
        buyAirtimeButtonIcon.classList.add('fa-spinner', 'fa-spin');
        // get values from the form
        let numberInput = document.getElementById("numbers").value.trim();
        let categoryInput = document.getElementById("category").value.trim();
        let networkInput = document.getElementById("network").value;
        let packageInput = document.getElementById("newPackage").value;
        let amountInput = document.getElementById("amount-airtime").value.trim();
        let totalInput = document.getElementById("total").value.trim();
        let pinInput = document.getElementById("pin").value.trim();
        let errorOverlay = document.getElementById("error-overlay");
        let errorMessage = document.getElementById("error-message");

        // Validate inputs
        let errors = [];

        if (!numberInput || numberInput.length < 11) errors.push("Phone Number must be 11 digits.");
        if (!categoryInput) errors.push("Category is required.");
        if (!networkInput) errors.push("Network is required.");
        if (!packageInput) errors.push("Package is required.");
        if (!amountInput || isNaN(amountInput) || parseFloat(amountInput) <= 0) errors.push("Enter a valid amount.");
        if (!totalInput || isNaN(totalInput) || parseFloat(totalInput) <= 0) errors.push("Enter a valid total amount.");
        if (!pinInput || pinInput.length < 4) errors.push("PIN must be at least 4 digits.");

        // display errors using Toastr if any exist
        if (errors.length > 0) {
            let errorMessages = errors.join("<br>"); // Convert array to a string with line breaks
            toastr.error(errorMessages, 'Validation Errors', {
                timeOut: 5000,
                closeButton: true,
                progressBar: true,
                escapeHtml: false
            });

            return; // Stop submission if there are errors
        }
        // Prepare data for submission
        let formData = {
            userID: userID,
            recipient: recipient,
            category: category,
            networkID: networkID,
            packageID: packageID,
            amount: parseFloat(amount),
            total: parseFloat(total),
            pin: pinInput
        };
        // sending data to backend
        fetch("/user/vend-airtime", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: JSON.stringify(formData),
        })
        .then(response => response.json())
        .then(data => {
            let type, title;
            if (data.status) {
                switch (data.status) {
                    case "success":
                        type = "success"; title = "Success"; break;
                    case "pending":
                        type = "info"; title = "Info";break;
                    case "failed":
                        type = "warning"; title = "Warning"; break;
                    case "pin":
                        type = "warning"; title = "Warning"; break;
                    case "network":
                        type = "warning"; title = "Warning"; break;
                    case "package":
                        type = "warning"; title = "Warning"; break;
                    case "wallet":
                        type = "warning"; title = "Warning"; break;
                    case "insufficient":
                        type = "warning"; title = "Warning"; break;
                    default:
                        type = "error"; title = "Error";
                }
            
                // use a default message if data.message is missing
                let message = data.message ?? "Something went wrong! Please try again.";

                 // restore button state
                buyAirtimeButtonLoading.innerHTML = "Recharge";
                buyAirtimeButtonIcon.classList.remove('fa-spinner', 'fa-spin');
                buyAirtimeButton.disabled = false;

                // toastr
                toastr[type](` ${message} `,{
                    timeOut: 7000, // display for 7 seconds
                    closeButton: true,
                    progressBar: true,
                    extendedTimeOut: 3000,
                    preventDuplicates: true,
                    escapeHtml: false,
                    positionClass: "toast-top-center", // display at the top center
                });
            }      
            // reset the form after a successful transaction
            if (type === "success" && form) {
                form.reset();
            }
        })
        .catch(error => {
            // restore button state
            buyAirtimeButtonLoading.innerHTML = "Recharge";
            buyAirtimeButtonIcon.classList.remove('fa-spinner', 'fa-spin');
            buyAirtimeButton.disabled = false;
            // toastr
            toastr.error(` There is a problem. Try again later. `, {
                timeOut: 7000, // display for 7 seconds
                closeButton: true,
                progressBar: true,
                extendedTimeOut: 3000,
                preventDuplicates: true,
                escapeHtml: false,
                positionClass: "toast-top-center", // display at the top center
            });
        });

    });
});