const greetingSpan = document.getElementById("greetingSpan");
const captionTitle = document.getElementById("caption-title");
const roleCaption = document.getElementById("role-caption");
// const walletBalance = document.getElementsByClassName("wallet-balance");
var userId = null;
var userName = null;
var userEmail = null;
var userRole = null;
var myWalletBalance = null;

function fetchAdminInformation() {
    fetch('../classes/user.php?f=fetch_user_information')
        .then(response => response.json())
        .then(data => {
            var name = data.name;
            userId = data.userId;
            userName = data.username;
            userEmail = data.email;
            userRole = data.role;
            captionTitle.textContent = userName.toUpperCase();
            roleCaption.textContent = userRole;
            // captionTitle.textContent = name;
            if (greetingSpan) {
                greetingSpan.textContent = name;
            }

            //make it a role based access 
            if (data.role != "super-admin") {
                document.getElementById("adminPart").style.display = "none";
            }
        })
        .catch(error => {
            // Handle any errors
            console.error('Error:', error);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    fetchAdminInformation();
});


