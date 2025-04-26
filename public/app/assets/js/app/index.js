const greetingSpan = document.getElementById("greetingSpan");
const captionTitle = document.getElementById("caption-title");
const walletBalance = document.getElementsByClassName("wallet-balance");
const walletBalanceArray = Array.from(walletBalance);
const accountName = document.getElementById("account-name-label");
const accountNumber = document.getElementById("account-number-label");
const accountBank = document.getElementById("account-bank-label");
const walletIdentifierSpan = document.getElementById("walletIdentifierSpan");
const announcementSpan = document.getElementById("marquee");
const countReferralsSpan = document.getElementById("totalReferralsSpan");
const referralsBonusSpan = document.getElementById("referralBonus");
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

async function fetchUserInformation() {
    await fetch('../classes/User.php?f=fetch_user_information')
        .then(response => response.json())
        .then(data => {
            var name = data.firstName + " " + data.lastName;
            userId = data.userId;
            pin = data.pin;
            userName = data.username;
            userEmail = data.email;
            captionTitle.textContent = formatSpecialChar(name);
            userFName = data.firstName;
            userLName = data.lastName;
            window.gender = data.gender;
            userPhone = data.phone;
            if (greetingSpan) {
                greetingSpan.textContent = name;
            }
            if(data.isVerified !== 1){
                document.getElementById("verifyAlert").style.display= "block";
            }else{
                document.getElementById("verifyAlert").style.display= "none";
            }
            walletBalanceArray.forEach(walletBalance => {
                walletBalance.textContent = Number(data.balance).toLocaleString();
            })
            walletId = data.walletId;
            senderWalletId = data.walletId;
            walletIdentifier = data.walletIdentifier;
            if (walletIdentifierSpan) {
                walletIdentifierSpan.innerHTML = data.walletIdentifier;
            }
            myWalletBalance = Number(data.balance).toLocaleString();

        })
        .catch(error => {
            // Handle any errors
            console.error('Error:', error);
        });
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
            announcementSpan.textContent = 'Announcement: ' + formatSpecialChar(allAnnouncements);
            if(currentPageTitle == 'index') {
                document.getElementById('viewAnnouncementBody').textContent = formatSpecialChar(latestAnnouncement);
                // Open the view modal
                $('#viewAnnouncementModal').modal('show');
            }
        })
        // .catch(error => {
        //     // Handle any errors
        //     console.error('Error:', error);
        // });
}
// resend verification link
function resendVerification() {
    fetch('../classes/Registration.php?f=resend_verification')
        .then(response => response.json())
        .then(data => {
            if(data) {
                alert('Verification email has been sent to your email');
            }else{
                alert("There is an error. Try agin later.");
            }
        })
        .catch(error => {
            // Handle any errors
            console.error('Error:', error);
        });
}
// fetching referrals count
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
// fetch referral bonus
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
// close b=modal box
function closeModal(modal) {
    $('#' + modal).modal('hide');
}
// format special characters
function formatSpecialChar(text){
    var doc = new DOMParser().parseFromString(text, "text/html");
  return doc.body.textContent;
}

fetchUserInformation();
document.addEventListener('DOMContentLoaded', function () {
    setInterval(fetchUserInformation, 5000);
    fetchAnnouncements();
    fetchReferralBonus();
    fetchReferrals();
});
