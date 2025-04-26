
const referralLink = document.getElementById("referralLink");
const referralCopy = document.getElementById("referralCopy");
const referralShare = document.getElementById("referralShare");
var userId ;
var walletId ;
var userName;
var userEmail = null;
var userFName = null;
var userLName = null;
var pin;
function fetchUserInformation() {
    fetch('../classes/User.php?f=fetch_user_information')
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
            if (greetingSpan) {
                greetingSpan.textContent = name;
            }
        })
        .catch(error => {
            // Handle any errors
            console.error('Error:', error);
        });
}

document.getElementById("referralCopy").addEventListener("click", function() {
    var copyText = document.getElementById("referralLink").value;
    copyToClipboard(copyText);
  });

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

  // format special characters
function formatSpecialCar(text){
    var doc = new DOMParser().parseFromString(text, "text/html");
  return doc.body.textContent;
}

function generateReferralLink(){
    var rawLink = 'https://aarasheeddata.com.ng/auth/register?referral=' + userName;
    referralLink.value = rawLink;
}

function fetchReferrals() {
    fetch(`../classes/Referrals.php?f=count_referrals`)
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            countReferralsSpan.textContent = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function fetchReferralBonus() {
    fetch(`../classes/Referrals.php?f=get_referral_bonus`)
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            referralsBonusSpan.textContent = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// alert(userName);
document.addEventListener('DOMContentLoaded', function () {
    setInterval(fetchUserInformation, 3000);
    setInterval(generateReferralLink, 3000);
    setInterval(fetchReferrals, 3000);
    setInterval(fetchReferralBonus, 3000);
});
