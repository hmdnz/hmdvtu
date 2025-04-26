const captionTitle = document.getElementById("caption-title");
const walletBalance = document.getElementsByClassName("wallet-balance");
const walletBalanceArray = Array.from(walletBalance);
const announcementSpan = document.getElementById("marquee");
const networkItem = document.getElementById('network');
const total_summary = document.getElementById("total_summary");
const buyAirtimeButton = document.getElementById("buy-airtime-button");
const errorMessage = document.getElementById('error_message');
const buyAirtimeButtonpaySpan = document.getElementById("buyAirtimeButtonpaySpan");

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
let dataTotalAmount = 0;
let dataNetworkOperator = null;
let dataPackage = null;
let amountMoreRequired = 0;
let dataPackagePrice = 0;
let packageName = null;
let operatorName = null;
let discountedAmount = 0;
let amountToPay = 0;

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
    fetch(`../classes/User.php?f=check_service_status&value=Airtime to Cash`)
    .then(response => response.json())
    .then(data => {
        if(data){
            // alert('Active');
        }else{
            document.getElementById("network").disabled = true;
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

function fetchAnnouncements() {
    fetch('../classes/User.php?f=fetch_announcements')
        .then(response => response.json())
        .then(data => {
            var latestAnnouncement = data[0].body;
            var allAnnouncements = " ** "
            for (let index = 0; index < data.length; index++) {
                allAnnouncements += data[index].body + " ** ";
            }
            announcementSpan.textContent = 'Announcement: ' + formatSpecialCar(allAnnouncements);
            if(currentPageTitle == 'index') {
                document.getElementById('viewAnnouncementBody').textContent = formatSpecialCar(latestAnnouncement);
                // Open the view modal
                $('#viewAnnouncementModal').modal('show');
            }
        })
        // .catch(error => {
        //     // Handle any errors
        //     console.error('Error:', error);
        // });
}

networkItem.addEventListener('change', () => {
    // Uncheck all other network operator items
    networkItem.values = '';
    const network = networkItem.value;
    // Check the clicked network operator item
    dataNetworkOperator = networkItem.value;
    network == 1 ? operatorName = 'MTN' : 
    network == 2 ? operatorName = 'AIRTEL' :
    network == 3 ? operatorName = 'GLO' :
    operatorName =  '9MOBILE';
    fetchPackages(dataNetworkOperator);
});
// Function to fetch and display packages
function fetchPackages(networkOperatorId) {
    // buyAirtimeButton.disabled = true;
    // Fetch packages data from the server
    fetch(`../classes/AirtimeCash.php?f=fetch_packages&networkOperatorId=${networkOperatorId}`)
        .then(response => response.json())
        .then(data => {
            // Get the container element & Clear existing package elements
            const container = document.getElementById('newPackage');
            container.innerHTML = '<option value="">Choose..</option>';

            data.forEach(package => {

                const option = document.createElement('option');
                option.id = package.packageId;
                option.label = package.title;
                option.value = package.packageId;
                // Append column to the container
                container.appendChild(option);
                // Add click event listener to the package card
                container.addEventListener('change', () => {
                    dataPackage = package.packageId;
                    dataPackagePrice = package.price;
                    packageName = package.title;
                    // Call the action passing the package variables
                    processPackage(package);

                });
            });

        })
        .catch(error => {
            console.error('Error fetching package data:', error);
        });
}
function processPackage(package) {
    // buyAirtimeButton.disabled = true;
    const receiveAmount = document.getElementById('receive-amount');
    // const totalSpan = document.getElementById('total-span');
    const amountInput = document.getElementById('amount-airtime');

    // numberInput.addEventListener('input', calculateTotalAmount);
    amountInput.addEventListener('input', calculateTotalAmount);
    function calculateTotalAmount() {
        let amount = amountInput.value;
        const container = document.getElementById('newPackage');
        if (amount != "") {
            container.disabled = false;
        }
        amountToPay = amount;
        // Apply the discount to the amount
        discountedAmount = amountToPay * (1 - package.price / 100);
        // Display the discounted amount and discount information .toFixed(2)
        const formattedAmount = formatNumber(amount);
        const formattedAmountToPay = formatNumber(amountToPay);
        const formattedDiscountedAmount = formatNumber(discountedAmount);
        const discountInfo = ` &#8358;${formattedDiscountedAmount}`;
        receiveAmount.innerHTML = discountInfo;
    }

    // Call the calculation function initially
    calculateTotalAmount();
}

function isWalletBalanceSufficient() {
    var formattedBalance = parseFloat(myWalletBalance.replace(/,/g, ''));
    var formattedAmount = parseFloat(discountedAmount.toString().replace(/,/g, ''));

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
    document.getElementById('airtime-topup-form').style.display = "none";
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
    setInterval(fetchUserInformation, 2000);
    checkService();
});