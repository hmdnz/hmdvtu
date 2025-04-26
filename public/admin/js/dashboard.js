const usersCounts = document.getElementById("users-counts");
const walletsCounts = document.getElementById("wallets-counts");
const paymentsCounts = document.getElementById("payments-counts");
const packagesCounts = document.getElementById("packages-counts");
const ordersCounts = document.getElementById("orders-counts");
const transactionsCounts = document.getElementById("transactions-counts");
var totalUsers = 0;
var totalAdmins = 0;
var totalWallets = 0;
var totalPayments = 0;
var totalPackages = 0;
var totalOrders = 0;
var totalTransactions = 0;

function fetchUsersCounts() {
    fetch('../classes/Index.php?f=users_counts')
    .then(response => response.json())
    .then(data => {
        console.log(data);
        totalUsers = data;
        usersCounts.textContent = data;
        // roleCaption.textContent = userRole;
        //    var userGender = data.gender;
        //    var userPhone = data.phone;
        // captionTitle.textContent = name;
    })
    .catch(error => {
        // Handle any errors
        console.error('Error:', error);
    });
}

function fetchWalletsCounts() {
    fetch('../classes/Index.php?f=wallets_counts')
    .then(response => response.json())
    .then(data => {
        console.log(data);
        totalWallets = data;
        walletsCounts.textContent = data;
    })
    .catch(error => {
        // Handle any errors
        console.error('Error:', error);
    });
}

function fetchPaymentsCounts() {
    fetch('../classes/Index.php?f=payments_counts')
    .then(response => response.json())
    .then(data => {
        console.log(data);
        totalWallets = data;
        paymentsCounts.textContent = data;
    })
    .catch(error => {
        // Handle any errors
        console.error('Error:', error);
    });
}

function fetchPackagesCounts() {
    fetch('../classes/Index.php?f=packages_counts')
    .then(response => response.json())
    .then(data => {
        console.log(data);
        totalPackages = data;
        packagesCounts.textContent = data;
    })
    .catch(error => {
        // Handle any errors
        console.error('Error:', error);
    });
}

function fetchOrdersCounts() {
    fetch('../classes/Index.php?f=orders_counts')
    .then(response => response.json())
    .then(data => {
        console.log(data);
        totalOrders = data;
        ordersCounts.textContent = data;
    })
    .catch(error => {
        // Handle any errors
        console.error('Error:', error);
    });
}

function fetchTransactionsCounts() {
    fetch('../classes/Index.php?f=transactions_counts')
    .then(response => response.json())
    .then(data => {
        console.log(data);
        totalTransactions = data;
        transactionsCounts.textContent = data;
    })
    .catch(error => {
        // Handle any errors
        console.error('Error:', error);
    });
}


document.addEventListener('DOMContentLoaded', function () {
    fetchUsersCounts();
    fetchWalletsCounts();
    fetchPaymentsCounts();
    fetchPackagesCounts();
    fetchOrdersCounts();
    fetchTransactionsCounts();

});
