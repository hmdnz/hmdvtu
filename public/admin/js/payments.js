// getting elements
const allPaymentsSpan = document.getElementById("all_payments");
const yearPaymentsSpan = document.getElementById("year_payments");
const monthPaymentsSpan = document.getElementById("month_payments");
const todayPaymentsSpan = document.getElementById("today_payments");
const allPaymentsValueSpan = document.getElementById("all_payments_value");
const yearPaymentsValueSpan = document.getElementById("year_payments_value");
const monthPaymentsValueSpan = document.getElementById("month_payments_value");
const todayPaymentsValueSpan = document.getElementById("today_payments_value");

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

// counting all payments
function countAllPayments() {
    // Fetch network operator data from the server
    fetch(`../classes/Payments.php?f=count_all_payments`)
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            allPaymentsSpan.textContent = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
// counting payments this year
function countYearPayments() {
    // Fetch network operator data from the server
    fetch(`../classes/Payments.php?f=count_year_payments`)
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            yearPaymentsSpan.textContent = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
// counting payments this month
function countMonthPayments() {
    // Fetch network operator data from the server
    fetch(`../classes/Payments.php?f=count_month_payments`)
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            monthPaymentsSpan.textContent = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
// counting payments today
function countTodayPayments() {
    fetch(`../classes/Payments.php?f=count_today_payments`)
    .then(response => response.json())
    .then(data => {
        // console.log(data);
        todayPaymentsSpan.textContent = data;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
// all payments value
function getAllPaymentsValue() {
    fetch(`../classes/Payments.php?f=fetch_all_value`)
    .then(response => response.json())
    .then(data => {
        allPaymentsValueSpan.textContent = formatNumber(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
// curent year payment value
function getYearPaymentsValue() {
    fetch(`../classes/Payments.php?f=fetch_year_value`)
    .then(response => response.json())
    .then(data => {
        yearPaymentsValueSpan.textContent = formatNumber(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
// current month payment value
function getMonthPaymentsValue() {
    fetch(`../classes/Payments.php?f=fetch_month_value`)
    .then(response => response.json())
    .then(data => {
        monthPaymentsValueSpan.textContent = formatNumber(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
// today payment value
function getTodayPaymentsValue() {
    fetch(`../classes/Payments.php?f=fetch_today_value`)
    .then(response => response.json())
    .then(data => {
        todayPaymentsValueSpan.textContent = formatNumber(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
// all payments
function fetchPayments() {
    const table = $('#datatable').DataTable();
    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable').DataTable({
        ajax: {
            url: `../classes/Payments.php?f=fetch_Payments`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    user: delivery.username,
                    reference: delivery.reference,
                    channel: delivery.channel,
                    amount: `&#8358; ${formatNumber(delivery.amount)}`,
                    fees: `&#8358; ${formatNumber(delivery.fees)}`,
                    status: delivery.status ===  '1' ? '<span class="text-success">Success</span>' : '<span class="text-danger">Failed</span>',
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
            { data: 'reference'},
            { data: 'channel'},
            { data: 'amount'},
            { data: 'fees'},
            { data: 'status' },
            { data: 'date' }
        ]
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

document.addEventListener('DOMContentLoaded', function () {
    countAllPayments();
    countYearPayments();
    countMonthPayments();
    countTodayPayments();
    getAllPaymentsValue();
    getYearPaymentsValue();
    getMonthPaymentsValue();
    getTodayPaymentsValue();
    fetchPayments();
    // setInterval(countReferralUnpaid, 3000);
    // setInterval(countReferralPayout, 3000);
    // setInterval(countActiveReferrals, 3000);
    // setInterval(countTotalReferrals, 3000);
    // setInterval(countSattledReferrals, 3000);
    // // setInterval(fetchActiveReferrals, 3000);
    // fetchReferrals();
    // fetchActiveReferrals();
    // fetchSattledReferrals();
    // fetchReferralBonuses();
});