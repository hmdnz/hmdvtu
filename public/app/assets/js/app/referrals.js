// getting elements
const referralCode = document.getElementById("referralCode");
const referralCopy = document.getElementById("referralCopy");
const announcementSpan = document.getElementById("marquee");

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

function transferBonus(event) {
    event.preventDefault();
    var transferBonus = document.getElementById("referralBalanceInput").value;
    var referralUserId = document.getElementById("referralUserId").value;
    var referralWalletId = document.getElementById("referralWalletId").value;

    let formData = new FormData();
    formData.append("userId", referralUserId);
    formData.append("walletId", referralWalletId);
    formData.append("amount", transferBonus);
    formData.append("fees", 0);
    formData.append("total", transferBonus);
    formData.append("referrerId", userName);

    //send the request
    fetch("../classes/Referrals.php?f=transfer_bonus", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then((data) => {
        console.log(data);
        document.getElementById('transferForm').style.display = 'none';
        document.getElementById('successFull').style.display = 'block';

    })
    .catch(function(error) {
        console.log("Error occurred while tranfer. Please try again.");
        document.getElementById('error_message').style.display = 'block';
      });
}

function copyToClipboard(text) {
    var textarea = document.createElement("textarea");
    textarea.value = text;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand("copy");
    document.body.removeChild(textarea);

    var tooltip = document.getElementById("copy-tooltip");
    tooltip.style.display = "block";
    tooltip.style.visibility = "visible";
    setTimeout(function() {
      tooltip.style.visibility = "hidden";
    }, 2000);
}

function closeTransferModal() {
    $("#transferModal").modal("hide");    
}

referralCopy.addEventListener("click", function() {
    var text = document.getElementById("referralCode").innerText;
    copyToClipboard(text);
  });

document.addEventListener('DOMContentLoaded', function () {
   
});