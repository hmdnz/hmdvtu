
const accountName = document.getElementById("account-name-label");
const accountNumber = document.getElementById("account-number-label");
const accountBank = document.getElementById("account-bank-label");
const announcementSpan = document.getElementById("marquee");
const amountInput = document.getElementById('amount-before');
const paystackTotalLabel = document.getElementById('paystackTotal')
const payUserName = document.getElementById("payUserName")
const payUserID = document.getElementById("payUserID")
const payWalletID = document.getElementById("payWalletID")
const payTotal = document.getElementById("payTotal")
const payFees = document.getElementById("payFees")
const amountInput1 = document.getElementById('amount');
const payButton = document.getElementById('pay-button');
const paystackFeesLabel = document.getElementById('paystackFees');
const defaultButtonText = payButton.innerText; // Store the default button text
const minimumAmount = 50; // Set the minimum amount
const maximumAmount = 1000000; // Set the maximum amount
var gFees = null;
var gAmount = null;
var gTotal = null;
// user information
var userFName = null;
var userLName = null;
var pin;
var userPhone = null;
var myWalletBalance = null;
var walletIdentifier = null;
var senderWalletId = null;

// handle amount input
amountInput.addEventListener('input', function () {
    const amount = parseFloat(amountInput.value);
    const PaystackFees = parseFloat(calculatingFees(amount));
    const totalAmount = amount + PaystackFees
    gFees = PaystackFees;
    gAmount = amount;
    gTotal = totalAmount;
    amountInput1.value = totalAmount;
    payUserName.value = userName;
    payUserID.value = userId;
    payWalletID.value = walletId;
    payFees.value = PaystackFees;
    payTotal.textContent = totalAmount;
    document.getElementById('payTotal').value =  totalAmount;
    amountInput1.value = totalAmount;
    const formattedAmount = formatCurrency(totalAmount); // Format amount with currency symbol
    paystackFeesLabel.textContent = PaystackFees;
    paystackTotalLabel.textContent = totalAmount;
    if (isNaN(totalAmount) || totalAmount <= 0 || totalAmount < minimumAmount || totalAmount > maximumAmount) {
        payButton.innerText = defaultButtonText;
        payButton.disabled = true;
        if (amount < minimumAmount) {
            showMessage(`Minimum amount is ${formatCurrency(minimumAmount)}`);
        } else if (amount > maximumAmount) {
            showMessage(`Maximum amount is ${formatCurrency(maximumAmount)}`);
        }
    } else {
        payButton.innerText = `Pay ${formattedAmount} with Monnify`;
        payButton.disabled = false;
        hideMessage();
    }
    // MonnifyInline.amount = amount * 100; // Convert amount to kobo (Monnify's base unit)
});

// Function to format the amount with currency symbol
function formatCurrency(amount) {
    return amount.toLocaleString('en-NG', { style: 'currency', currency: 'NGN' }).replace('NGN', '₦');
}

// format special characters
function formatSpecialCar(text){
    var doc = new DOMParser().parseFromString(text, "text/html");
  return doc.body.textContent;
}

// calculate paystackFees
function calculatingFees(amount) {
    const minimumAmount = 2500 // Minimum amount in Naira
    const fixedFee = 100 // Fixed fee in Naira
    const percentageFee = 0.015 // Percentage fee (1.5%)
    const maximumFee = 2000 // Maximum fee in Naira
    if (amount >= minimumAmount) {
      const percentageBasedFee = amount * percentageFee
      totalFee = Math.min(fixedFee + percentageBasedFee, maximumFee)
    } else {
      totalFee = amount * percentageFee
    }
    return totalFee
  }

// Function to show the message
function showMessage(message) {
    const messageElement = document.getElementById('message');
    messageElement.textContent = message;
    messageElement.style.display = 'block';
}

// Function to hide the message
function hideMessage() {
    const messageElement = document.getElementById('message');
    messageElement.style.display = 'none';
}

function processPayment(event) {
    event.preventDefault();
        MonnifySDK.initialize({
            amount: gTotal,
            currency: "NGN",
            reference: new String((new Date()).getTime()),
            customerFullName: userName,
            customerEmail: userEmail,
            apiKey: param1,
            contractCode: param2,
            paymentDescription: "Wallet Top Up",
            metadata: {
                "name": userName,
                "age": 0
            },
            onLoadStart: () => {
                console.log("loading has started");
            },
            onLoadComplete: () => {
                console.log("SDK is UP");
            },

            onComplete: function(response) {
                //Implement what happens when the transaction is completed.
                let formData = new FormData();
                formData.append("reference", response.paymentReference);
                formData.append("name", userName);
                formData.append("email", userEmail);
                formData.append("userId", userId);
                formData.append("walletId", walletId);
                formData.append("amount", gAmount);
                formData.append("fees", gFees);
                formData.append("total", gTotal);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: "/user/verify-payment",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.status) {                            
                            toastr.success(data.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 10000);
                        } else {
                            
                            toastr.error(data.message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            setTimeout(function() {
                                window.location.reload();
                            }, 10000);
                        }
                    }
                });
            },
            onClose: function(data) {
                //Implement what should happen when the modal is closed here
                console.log(data);
            }
        });
}
    
function topupWallet() {
    let fees = PaystackFees;
    let channel = "Online";
    let gateway = "Paystack";
    let reference = response.reference;
    let status = 1;
            formData = new FormData();
            formData.append("userId", userId);
            formData.append("walletId", walletId);
            formData.append("amount", amount);
            formData.append("fees", fees);
            formData.append("total", Number(amount) + Number(fees));
            formData.append("channel", channel);
            formData.append("reference", reference);
            formData.append("gateway", gateway);
            formData.append("status", status);
            formData.append("name", userName);
            formData.append("email", userEmail);
            fetch("../classes/Wallet.php?f=topup_wallet", {
                method: "POST",
                body: formData,
            })
            .then((response) =>  {
                
                    return response.json();
            })
}

function showSuccessMessage() {
    // After successful payment
    document.getElementById("error-msg").style.display = "none";
    document.getElementById('payment-form').style.display = 'none';
    document.getElementById('topup-success-message').style.display = 'block';

    // Clear form after 5 seconds
    setTimeout(function () {
        document.getElementById("error-msg").style.display = "none";
        document.getElementById('payment-form').reset();
        payButton.disabled = true;
        document.getElementById('payment-form').style.display = 'block';
        document.getElementById('topup-success-message').style.display = 'none';
    }, 5000); // Change the duration as needed (in milliseconds)
}

function generateReference() {
    const length = 20;
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let reference = '';

    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        reference += characters.charAt(randomIndex);
    }

    return reference;
}

// document.addEventListener('DOMContentLoaded', function () {
//     setInterval(fetchUserInformation, 3000);
// });