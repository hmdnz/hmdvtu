// getting elements
const activeReferralsSpan = document.getElementById("active_referrals");
const totalReferralsSpan = document.getElementById("total_referrals");
const totalPayoutSpan = document.getElementById("total_payout");
const unpaidBonusesSpan = document.getElementById("unpaid_bonuses");
const sattledReferralsSpan = document.getElementById("sattled_referrals");

function fetchAnnouncements() {
    fetch('../classes/User.php?f=fetch_announcements')
        .then(response => response.json())
        .then(data => {
            var latestAnnouncement = data[0].body;
            var allAnnouncements = " ** "
            for (let index = 0; index < data.length; index++) {
                allAnnouncements += data[index].body + " ** ";
            }
            announcementSpan.textContent = 'Announcement: ' + allAnnouncements;
            if(currentPageTitle == 'index') {
                document.getElementById('viewAnnouncementBody').textContent = latestAnnouncement;
                // Open the view modal
                $('#viewAnnouncementModal').modal('show');
            }
        })
        // .catch(error => {
        //     // Handle any errors
        //     console.error('Error:', error);
        // });
}

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

function countTotalReferrals() {
    // Fetch network operator data from the server
    fetch(`../classes/Referrals.php?f=count_all_referrals`)
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            totalReferralsSpan.textContent = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function countActiveReferrals() {
    // Fetch network operator data from the server
    fetch(`../classes/Referrals.php?f=count_active_referrals`)
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            activeReferralsSpan.textContent = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function countReferralPayout() {
    fetch(`../classes/Referrals.php?f=total_payout`)
    .then(response => response.json())
    .then(data => {
        // console.log(data);
        totalPayoutSpan.textContent = data;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function countSattledReferrals() {
    fetch(`../classes/Referrals.php?f=count_sattled_referrals`)
    .then(response => response.json())
    .then(data => {
        // console.log(data);
        sattledReferralsSpan.textContent = data;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
// all referrals
function fetchReferrals() {
    const table = $('#datatable').DataTable();
    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable').DataTable({
        ajax: {
            url: `../classes/Referrals.php?f=fetch_referrals`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    user: delivery.username,
                    referrer: delivery.referrerId,
                    status: delivery.status ===  1 ? '<span class="text-primary">Active</span>' : '<span class="text-success">Sattled</span>',
                    date: "<small>" + formatDateTime(delivery.createdAt) + "</small>"
                }));
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'user' },
            { data: 'referrer'},
            { data: 'status' },
            { data: 'date' }
        ]
    });
}
// active referrals
function fetchActiveReferrals() {
    const table = $('#datatable2').DataTable();
    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable2').DataTable({
        ajax: {
            url: `../classes/Referrals.php?f=fetch_active_referrals`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    user: delivery.username,
                    referrer: delivery.referrerId,
                    status: delivery.status ===  1 ? '<span class="text-primary">Active</span>' : '<span class="text-success">Sattled</span>',
                    date: "<small>" + formatDateTime(delivery.createdAt) + "</small>"
                }));
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'user' },
            { data: 'referrer'},
            { data: 'status' },
            { data: 'date' }
        ]
    });
}
// sattled referrals
function fetchSattledReferrals() {
    const table = $('#datatable3').DataTable();
    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable3').DataTable({
        ajax: {
            url: `../classes/Referrals.php?f=fetch_sattled_referrals`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    user: delivery.username,
                    referrer: delivery.referrerId,
                    status: delivery.status ===  1 ? '<span class="text-primary">Active</span>' : '<span class="text-success">Sattled</span>',
                    date: "<small>" + formatDateTime(delivery.createdAt) + "</small>"
                }));
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'user' },
            { data: 'referrer'},
            { data: 'status' },
            { data: 'date' }
        ]
    });
}
// referral commisions
function fetchReferralBonuses() {
    const table = $('#datatable4').DataTable();
    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable4').DataTable({
        ajax: {
            url: `../classes/Referrals.php?f=fetch_referral_bonuses`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    user: delivery.username,
                    referrer: delivery.referrerId,
                    amount: formatNumber(delivery.amount),
                    status: delivery.status ===  1 ? '<span class="text-primary">Active</span>' : '<span class="text-success">Transfered</span>',
                    date: "<small>" + formatDateTime(delivery.createdAt) + "</small>"
                }));
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'user' },
            { data: 'referrer'},
            { data: 'amount'},
            { data: 'status' },
            { data: 'date' }
        ]
    });
}

function setReferralTransfer(){
    document.getElementById("referralUserId").value = userId;
    document.getElementById("referralWalletId").value = walletId;
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

function generateReferralLink(){
    var rawLink = 'https://aarasheeddata.com.ng/auth/register?referral=' + userName;
    referralLink.value = rawLink;
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

document.addEventListener('DOMContentLoaded', function () {
    setInterval(countReferralPayout, 3000);
    setInterval(countActiveReferrals, 3000);
    setInterval(countTotalReferrals, 3000);
    setInterval(countSattledReferrals, 3000);
    fetchReferrals();
    fetchActiveReferrals();
    fetchSattledReferrals();
    fetchReferralBonuses();
});