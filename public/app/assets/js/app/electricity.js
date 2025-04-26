const networkItems = document.querySelectorAll('.network-item');
const buyElectricityButton = document.getElementById("buy-Electricity-button");
const buyElectricityButtonSpan = document.getElementById("buyElectricityButtonSpan");
const disco = document.getElementById("disco");
const meterNo = document.getElementById("meterNo");
const meterType = document.getElementById("meterType");
const amount = document.getElementById("amount");

let utilityMeterNo = null;
let utilityTotalAmount = 0;
let utilityPackage = null;
let amountMoreRequired = 0;
let utilityPackagePrice = 0;
let utilityDisco = null;
let utilityPackageName = null;
let utilityMeterType = null;

// check if the service is available
function checkService () {
    // Assuming you have a service ID, replace '123' with the actual service ID
    var serviceId = 'Electricity';
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

disco.addEventListener('change', function () {
    utilityDisco = this.value;
    // console.log(utilityDisco);
});

meterType.addEventListener('change', function () {
    utilityMeterType = this.value;
    // console.log(utilityMeterType)
});

meterNo.addEventListener('input', function () {
    utilityMeterNo = this.value;
    // console.log(utilityMeterNo)
});

amount.addEventListener('input', function () {
    utilityTotalAmount = this.value;
    utilityPackagePrice = this.value;
    document.getElementById("total").value = this.value;
});

function fetchPackages(category) {
    // Fetch packages data from the server
    $.ajax({
        url: `/user/fetch-packages/${category}/${category}/Electricity`,
        type: 'GET',
        success: function (response) {
            document.getElementById("packageName").value = response[0].title;
            document.getElementById("package").value = response[0].id;
        },
        error: function (error) {
            // Handle errors
            console.error('Error:', error);
        }
    });
    
}
// wallet
function isWalletBalanceSufficient() {
    var formattedBalance = parseFloat(myWalletBalance.replace(/,/g, ''));
    var formattedAmount = parseFloat(utilityTotalAmount.toString().replace(/,/g, ''));

    if (formattedBalance >= formattedAmount) {
        return true; // Wallet balance is sufficient
    } else {
        var difference = formattedAmount - formattedBalance;
        amountMoreRequired = difference;
        return false; // Wallet balance is not sufficient
    }

}
// Function to format the number with commas
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
    fetchPackages('Electricity');
    checkService();
});
