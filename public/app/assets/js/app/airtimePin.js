const networkItems = document.querySelectorAll('.network-item');
const operator_stepper = document.getElementById("operator-stepper");
const operator_dot = document.getElementById("operator_dot");
const operator_label = document.getElementById("operator_label");
const package_stepper = document.getElementById("package-stepper");
const package_dot = document.getElementById("package_dot");
const package_label = document.getElementById("package_label");
const number_stepper = document.getElementById("number-stepper");
const number_dot = document.getElementById("number_dot");
const number_label = document.getElementById("number_label");
const total_summary = document.getElementById("total_summary");
const quantity_stepper = document.getElementById("quantity-stepper");
const quantity_dot = document.getElementById("quantity_dot");
const quantity_label = document.getElementById("quantity_label");
const quantity_summary = document.getElementById("quantity_summary");
const buyAirtimePinButton = document.getElementById("buy-airtimePin-button");
const errorMessage = document.getElementById('error_message');
const buyAirtimePinButtonpaySpan = document.getElementById("buyAirtimePinButtonpaySpan");


let validReceipients = null;
let airtimePinTotalAmount = 0;
let airtimeNetworkOperator = null;
let airtimePackage = null;
let amountMoreRequired = 0;
let airtimePinPackagePrice = 0;
let airtimePinDeno = null;
let packageName = null;
let operatorName = null;
let airtimePinQuantity = 0;
let amountToPay = 0;

// user information
var userId = null;
var walletId = null;
var userName = null;
var userEmail = null;
var userFName = null;
var userLName = null;

function fetchUserInformation() {
    fetch('../classes/User.php?f=fetch_user_information')
        .then(response => response.json())
        .then(data => {
            var name = data.firstName + " " + data.lastName;
            userId = data.userId;
            userName = data.username;
            userEmail = data.email;
            userFName = data.firstName;
            userLName = data.lastName;
            window.gender = data.gender;
            userPhone = data.phone;
        })
        .catch(error => {
            // Handle any errors
            console.error('Error:', error);
        });
}
// check if the service is available
function checkService(){    
    fetch(`../classes/User.php?f=check_service_status&value=Data Card`)
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

function checkPinStatus(){    
    fetch(`../classes/User.php?f=check_pin_status`)
    .then(response => response.json())
    .then(data => {
        if(data){
            document.getElementById("pin-warning").style.display = "none";
        }else{
            document.getElementById("pin-warning").style.display = "block";
        }
    })
    .catch(error => {
        console.error('Error checking pin status:', error);
    });
}

document.getElementById("pin").addEventListener('input', () => {
    var pin = document.getElementById("pin").value;
    fetch(`../classes/User.php?f=check_pin&value=${pin}&userId=${userId}`)
    .then(response => response.json())
    .then(data => {
        if(data == true){
            buyAirtimePinButton.style.display = 'block';
            document.getElementById("pin-message").style.display = 'none';
        }else{
            buyAirtimePinButton.style.display = 'none';
            document.getElementById("pin-message").textContent = data;
            document.getElementById("pin-message").style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error checking pin status:', error);
    });
});

function fetchNetworkOperators() {
    buyAirtimePinButton.disabled = true;
    // Fetch network operator data from the server
    fetch('../classes/Data.php?f=fetch_network_operators')
        .then(response => response.json())
        .then(data => {
            // Get the container element
            const container = document.getElementById('networkOperatorsContainer');

            data.forEach(operator => {
                const networkItem = document.createElement('div');
                networkItem.className = 'form-check flex-box bg-light text-dark network-item';
                networkItem.style.background = `url('../cdn/biller_logo/${operator.logo}') no-repeat center`;
                networkItem.style.backgroundSize = '50px';

                const input = document.createElement('input');
                input.type = 'radio';
                input.className = 'form-check-input';
                input.id = operator.billerId;
                input.name = 'biller';
                input.value = operator.billerId;

                const label = document.createElement('label');
                label.className = 'form-check-label';
                label.htmlFor = operator.billerId;
                label.textContent = operator.title;

                // Append input and label to the network item
                networkItem.appendChild(input);
                networkItem.appendChild(label);

                // Append network item to the container
                container.appendChild(networkItem);

                // Attach event listener to the container
                networkItem.addEventListener('click', () => {
                    // Uncheck all other network operator items
                    networkItems.forEach(item => {
                        item.querySelector('input[type="radio"]').checked = false;
                    });

                    // Check the clicked network operator item
                    input.checked = true;
                    airtimeNetworkOperator = operator.billerId;
                    operatorName = operator.title;

                    // Call the function to fetch and show packages
                    fetchPackages(operator.billerId);                    
                });
            });


        })
        .catch(error => {
            console.error('Error fetching network operator data:', error);
        });

}
// Function to fetch and display packages
function fetchPackages(networkOperatorId) {
    buyAirtimePinButton.disabled = true;
    // Fetch packages data from the server
    fetch(`../classes/AirtimePin.php?f=fetch_packages&networkOperatorId=${networkOperatorId}`)
        .then(response => response.json())
        .then(data => {
            // Get the container element
            const container = document.getElementById('packagesContainer');

            // Clear existing package elements
            container.innerHTML = '';

            data.forEach(package => {
                const col = document.createElement('div');
                col.className = 'col-lg-3 col-md-4 col-sm-6 package-card';
                const card = document.createElement('div');
                card.className = 'card bg-light item-card';

                const cardBody = document.createElement('div');
                cardBody.className = 'card-body';

                const customControl = document.createElement('div');
                customControl.className = 'custom-control custom-radio';

                const input = document.createElement('input');
                input.type = 'radio';
                input.className = 'form-check-input custom-control-input item-radio';
                input.id = package.packageId;
                input.name = 'item';
                input.value = package.packageId;

                const label = document.createElement('label');
                label.className = 'custom-control-label pl-2';
                label.htmlFor = package.packageId;
                label.textContent = package.title;

                const price = document.createElement('p');
                price.className = 'text-center text-primary pt-1';
                price.innerHTML = `<strong>&#8358;${package.price}</strong>`;

                // Append elements to build the package card
                customControl.appendChild(input);
                customControl.appendChild(label);
                cardBody.appendChild(customControl);
                cardBody.appendChild(price);
                card.appendChild(cardBody);

                const duration = document.createElement('div');
                duration.className = 'col-12 bg-primary text-center text-light';
                duration.style.width = '100%';

                // Append card to the column
                col.appendChild(card);

                // Append column to the container
                container.appendChild(col);


                // Add click event listener to the package card
                card.addEventListener('click', () => {
                    // Set the clicked package as checked
                    input.checked = true;
                    airtimePackage = package.packageId;
                    airtimePinPackagePrice = package.price;
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
    buyAirtimePinButton.disabled = true;
    const quantityInput = document.getElementById('quantity');
    const totalSpan = document.getElementById('total-span');
    // generating pin denomination and variation
    let pinDeno = null;
    if(package.size == 'N100'){
        pinDeno = 100;
    }else if(package.size == 'N200'){
        pinDeno = 200;
    }else if(package.size == 'N400'){
        pinDeno = 400;
    }else if(package.size == 'N500'){
        pinDeno = 500;
    }else if(package.size == 'N750'){
        pinDeno = 750;
    }else if(package.size == 'N1000'){
        pinDeno = 1000;
    }else{
        pinDeno = 1500;
    }

    quantityInput.addEventListener('input', function () {
        const quantityInputString = this.value;
        const packagePrice = package.price;

        if(quantityInputString > 0){
            let totalAmount = quantityInputString * packagePrice;
            buyAirtimePinButton.disabled = false;
            // store the total amount
            airtimePinTotalAmount = totalAmount;
            airtimePinQuantity = quantityInputString;
            airtimePinDeno = pinDeno;
    
            totalSpan.textContent = formatNumber(totalAmount); // Update the total cost with formatted number
        }else{
            buyAirtimePinButton.disabled = true;
        }
        
    });

    // console.error(package);
}

function processBuyAirtimePin(event) {
    event.preventDefault();
    isSufficient = isWalletBalanceSufficient();
    // first check if the wallet balance is sufficient for the amount
    if (isSufficient) {
        errorMessage.style.display = "none";
        errorMessage.innerHTML = "";
        buyAirtimePinButtonpaySpan.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';
        //arrange the formData and submit
        let formData = new FormData();
        formData.append("userId", userId);
        formData.append("operator", airtimeNetworkOperator);
        formData.append("package", airtimePackage);
        formData.append("total", airtimePinTotalAmount);
        formData.append("pinDeno", airtimePinDeno);
        formData.append("quantity", airtimePinQuantity);
        formData.append("price", airtimePinPackagePrice);
        formData.append("status", 1);
        formData.append("name", userName);
        formData.append("email", userEmail);
        formData.append("operatorName", operatorName);
        formData.append("packageName", packageName);
        // console.log(formData);
        //send the request
        fetch("../classes/AirtimePin.php?f=buy_pin", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("There was a problem buying data");
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
                // alert(error.message);
            })
            .finally(() => {
                // Enable submit button and hide loading spinner
                buyDataButton.disabled = false;
                buyDataButtonpaySpan.innerHTML = "";
            });



    } else { //wallet balance not enough
        errorMessage.style.display = "block";
        errorMessage.innerHTML = "Your wallet balance is insufficient for this amount. You need  &#8358;" + amountMoreRequired + " more to proceed. You can topup your account now<br><button class='btn btn-primary text-center' id='quick_topup_btn' onclick='toggleQuickTopup(event)'>Topup Now</div>";
        // Scroll to the error message div
        errorMessage.scrollIntoView({ behavior: 'smooth' });
    }
}

function isWalletBalanceSufficient() {
    var formattedBalance = parseFloat(myWalletBalance.replace(/,/g, ''));
    var formattedAmount = parseFloat(airtimePinTotalAmount.toString().replace(/,/g, ''));

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
    fetchUserInformation();
    fetchNetworkOperators();
    checkPinStatus();
    checkService();
});