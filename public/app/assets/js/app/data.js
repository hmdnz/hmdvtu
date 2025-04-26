
const networkItem = document.getElementById('network');
const buyDataButton = document.getElementById("buy-data-button");
const errorMessage = document.getElementById('error_message');
const buyDataButtonpaySpan = document.getElementById("buyDataButtonpaySpan");
const buyDataButtonIcon = document.getElementById("buyDataButtonIcon");
const buyDataButtonLoading = document.getElementById("buyDataButtonLoading");
const categorySelect = document.getElementById('category');
const packageSelect = document.getElementById('newPackage');
const contactPickerButton = document.getElementById('selectContactButton');
const numberInput = document.getElementById('numbers');

var walletId = null;
var userName = null;
var userEmail = null;
var userFName = null;
var userLName = null;
var pin;
var userPhone = null;
var myWalletBalance = null;
let validReceipients = null;
let dataTotalAmount = 0;
let dataNetworkOperator = null;
// let category = null;
let dataPackage = null;
let dataPlan = null;
let amountMoreRequired = 0;
let dataPackagePrice = 0;
let packageName = null;
let operatorName = null;


var userID = userId;
let recipient = null; 
let category = null;
let networkID = null;
let networkName = null;
let packageID = null;
let amount = null;
let total = null;


// check if the service is available
function checkService () {
    // Assuming you have a service ID, replace '123' with the actual service ID
    var serviceId = 'Data';
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

// check active category
function checkCategories(service, biller) {
    // Make AJAX request
    $.ajax({
        url: `/user/check-switches/${service}/${biller}`,
        type: 'GET',
        success: function (response) {
            // Handle the response from the server
            while (categorySelect.options.length > 0) {
                categorySelect.remove(0);
            }
            categorySelect.innerHTML = '<option value="">Choose..</option>';
            console.log(response);
            response.forEach(package => {
                const option = document.createElement('option');
                option.id = package.title;
                option.label = package.title;
                option.value = package.title;
                // Append option to the container
                categorySelect.appendChild(option);
            });
        },
        error: function (error) {
            // Handle errors
            console.error('Error:', error);
        }
    });
}
// close modal
function closeModal(modal) {
    // console.log(modal);
    $('#' + modal).remove();
    // $('#' + modal).modal('hide');
}

// contact Picker API
async function contactPicker(){
    try {
        const props = ["name", "email", "tel", "address", "icon"];
        const opts = { multiple: false };
        // Check if the Contact Picker API is supported
        if ('contacts' in navigator) {
          // Use the Contact Picker API
          const contacts = await navigator.contacts.select(props, opts);
          // Check if a contact was selected and it has a phone number
          if (contacts && contacts.length > 0 && contacts[0].tel[0]) {
            // Get the first phone number from the selected contact
            const selectedNumber = formatPhoneNumber(contacts[0].tel[0]);
            // Set the selected number as the value of the input element
            document.getElementById('numbers').value = selectedNumber;
          } else {
            console.warn('No contact or phone number selected.');
            alert('No contact or phone number selected.');
          }
        } else {
            alert('Contact Picker is not supported in this app.');
        }
    } catch (error) {
    // Handle errors
    console.error(error);
    alert('Error.Try again');
    }
}
// handling network selection
networkItem.addEventListener('change', () => {
    // Uncheck all other network operator items
    networkItem.values = '';
    // Reset the category and package selection to empty
    categorySelect.value = '';
    packageSelect.value = '';
    let selectedOption = networkItem.options[networkItem.selectedIndex].text;
    networkID = networkItem.value;
    networkName =  selectedOption;
    
    checkCategories('Data', networkName);
});
// handling category selection
categorySelect.addEventListener('change', () => {
    category = categorySelect.value;
    packageSelect.value = '';
    numberInput.value = '';
    // Remove all options from the second select element
    while (packageSelect.options.length > 0) {
        packageSelect.remove(0);
    }
    category = categorySelect.value;
    fetchPackages(networkID, category);
});
// Function to fetch and display packages
function fetchPackages(networkOperatorId, category) {
    // buyDataButton.disabled = true;
    // Fetch packages data from the server
    $.ajax({
        url: `/user/fetch-packages/${networkOperatorId}/${category}/Data`,
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
                    numberInput.value = '';
                    const selectedPackageId = container.value;
                    const selectedPackage = response.find(package => String(package.id) === selectedPackageId);
                    if (selectedPackage) {
                        console.log("Changed to: " + selectedPackage.id + " Price: " + selectedPackage.price);
                        packageID = selectedPackage.id ;
                        amount = selectedPackage.price;
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
// process selected package
function processPackage(package) {
    // buyDataButton.disabled = true;
    const totalSpan = document.getElementById('total-span');

    numberInput.addEventListener('input', function () {
        const phoneNumberString = this.value.trim();
        const phoneNumbers = phoneNumberString.split(/[,\n\s]+/); // Split numbers by comma, space, or new line

        const uniquePhoneNumbers = [...new Set(phoneNumbers)]; // Remove duplicate numbers

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
        let validNumberCount = 0; // Track the count of valid numbers

        for (const phoneNumber in numberCounts) {
            const count = numberCounts[phoneNumber];
            totalAmount += package.price * count;
            validNumbers += `${phoneNumber}, `;
            validNumberCount++;
            // buyDataButton.disabled = false;
        }

        validNumbers = validNumbers.slice(0, -2); // Remove the trailing comma and space
        recipient = validNumbers;
        total = totalAmount;

        logValidNumbers(validNumbers, totalAmount);
        // document.getElementById('total').value = formatNumber(totalAmount);
        document.getElementById('total').value = totalAmount;
        // document.getElementById('billerName').value = operatorName;
        // document.getElementById('packageName').value = packageName;
        totalSpan.textContent = formatNumber(totalAmount); // Update the total cost with formatted number
        
    });

    // console.error(package);
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
// check nigerian number
function isValidNigeriaPhoneNumber(phoneNumber) {
    // Validate the phone number format for Nigeria (11 digits)
    const nigeriaPhoneNumberRegex = /^(?:\+?234|0)?[789]\d{9}$/;
    return nigeriaPhoneNumberRegex.test(phoneNumber);
}
// format phone number
function formatPhoneNumber(number){
    // Replace all spaces with an empty string
    var processed = number.replace(/ /g, '');
    var output = parseInt(processed, 10);
   return output;
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
// log valid numbers
function logValidNumbers(validNumbers, totalAmount) {
    // store the values 
    validReceipients = validNumbers;
    dataTotalAmount = totalAmount;
}
// check if wallent is sufficient
function isWalletBalanceSufficient() {
    var formattedBalance = parseFloat(myWalletBalance.replace(/,/g, ''));
    var formattedAmount = parseFloat(dataTotalAmount.toString().replace(/,/g, ''));

    if (formattedBalance >= formattedAmount) {
        return true; // Wallet balance is sufficient
    } else {
        var difference = formattedAmount - formattedBalance;
        amountMoreRequired = difference;
        return false; // Wallet balance is not sufficient
    }
}
// show success message after purchase
function showDataSuccessMessage() {
    // After successful payment
    document.getElementById("error_message").style.display = "none";
    document.getElementById('data_topup_form').style.display = 'none';
    document.getElementById('successFull').style.display = 'block';

    // Clear form after 5 seconds
    setTimeout(function () {
        document.getElementById("error_message").style.display = "none";
        document.getElementById('data_topup_form').reset();
        // buyDataButton.disabled = true;
        document.getElementById('data_topup_form').style.display = 'block';
        document.getElementById('successFull').style.display = 'none';
    }, 8000); // Change the duration as needed (in milliseconds)
}

document.addEventListener('DOMContentLoaded', function () {
    checkService();

    const form = document.getElementById("data-topup-form");
    form.addEventListener("submit", function (event) {
        event.preventDefault(); // prevent form submission until validation
        // show loading state
        buyDataButton.disabled = true;
        buyDataButtonIcon.classList.add('fa-spinner', 'fa-spin');
        // get values from the form
        let numberInput = document.getElementById("numbers").value.trim();
        let categoryInput = document.getElementById("category").value.trim();
        let networkInput = document.getElementById("network").value;
        let packageInput = document.getElementById("newPackage").value;
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
        console.log(formData);
        // sending data to backend
        fetch("/user/vend-data", {
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
                buyDataButtonLoading.innerHTML = "Recharge";
                buyDataButtonIcon.classList.remove('fa-spinner', 'fa-spin');
                buyDataButton.disabled = false;

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
            buyDataButtonLoading.innerHTML = "Recharge";
            buyDataButtonIcon.classList.remove('fa-spinner', 'fa-spin');
            buyDataButton.disabled = false;
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
