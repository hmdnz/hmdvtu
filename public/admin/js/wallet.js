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

function fetchWallets() {
    const table = $('#datatable').DataTable();

    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable').DataTable({
        ajax: {
            url: `../classes/wallet.php?f=fetch_wallets`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    username: delivery.username,
                    wallet: delivery.walletIdentifier,
                    balance: '<span class="text-primary">&#8358;' + formatNumber(delivery.balance) + "</span>",
                    status: delivery.status === '1' ? '<span class="text-success">Success</span>' : '<span class="text-danger">Failed</span>',
                    walletId: delivery.walletId,
                    action: '<a href="wallet-transactions?user=' + delivery.userId + '&wallet=' + delivery.walletId + '" class="btn btn-info btn-sm"><i class="fa fa-table"></i></a> <a href="wallet-payments?user=' + delivery.userId + '&wallet=' + delivery.walletId + '" class="btn btn-info btn-sm"><i class="fa fa-piggy-bank"></i></a> &nbsp; <button id="fundWalletBtn" onclick="addFundToWallet(' + delivery.walletId + ')" class="btn btn-success btn-sm">Fund</button> '
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
            { data: 'username' },
            { data: 'wallet' },
            { data: 'balance' },
            { data: 'status' },
            { data: 'action' },
            // {
            //     data: null,
            //     render: function (data, type, row) {
            //         return `
            //             <a type="button" data-bs-toggle="modal" class="btn btn-primary btn-sm" data-bs-target="#viewDeliveryInfo" data-delivery-id="${data.orderId}">
            //                 <i class="fa fa-eye"></i>
            //             </a>`;
            //     }
            // }
        ]
    });

    // Attach a click event listener to the eye buttons
    $('#datatable').on('click', '.btn-primary', function () {
        // const orderId = $(this).data('delivery-id');

        // Fetch individual delivery details using the deliveryId
        // fetchDeliveryDetails(orderId);
    });
}

function addFundToWallet(walletId) {
    // Open the modal box
    $('#fundWalletModalBox').modal('show');
    var theWalletId = document.getElementById("theWalletId");

    theWalletId.value = walletId;
}

function addFunds() {
    var form = document.getElementById("addFundForm");
    var amountInput = document.getElementById("amount");
    var amount = amountInput.value;
    var formData = new FormData(form);
    formData.append("amount", amount);
    fetch('..//classes/wallet.php?f=add_fund_to_wallet', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            // Close the modal box
            $('#fundWalletModalBox').modal('hide');
            fetchWallets();
        })
        .catch(error => {
            console.error(error);
        });
}


function closeModal(modal) {
    $('#' + modal).modal('hide');
}


function viewWallet(walletId) {
    fetch('../classes/wallet.php?f=fetch_wallet_details&walletId=' + walletId)
        .then(response => response.json())
        .then(wallets => {
            if (wallets.length > 0) {
                const wallet = wallets[0];
                // Populate wallet details in the modal
                document.getElementById('walletId').textContent = wallet.walletIdentifier;
                document.getElementById('walletUser').textContent = wallet.firstName + " " + wallet.lastName;
                document.getElementById('walletBalance').textContent = "₦" + formatNumber(parseFloat(wallet.balance).toFixed(2));

                fetch('../classes/wallet.php?f=get_wallet_transactions&walletId=' + walletId)
                    .then(response => response.json())
                    .then(transactions => {
                        const recentTransactionsTableBody = document.getElementById('recentTransactions').getElementsByTagName('tbody')[0];
                        recentTransactionsTableBody.innerHTML = ''; // Clear any existing table rows

                        if (transactions.length > 0) {
                            transactions.forEach((transaction, index) => {
                                const row = document.createElement('tr');
                                const numberCell = document.createElement('td');
                                const descriptionCell = document.createElement('td');
                                const amountCell = document.createElement('td');
                                const transactionDate = document.createElement('td');

                                descriptionCell.textContent = transaction.reference;
                                numberCell.textContent = index + 1;
                                amountCell.innerHTML = "<span class='text-success'>+₦" + formatNumber(parseFloat(transaction.amount).toFixed(2)) + "</span>";
                                transactionDate.textContent = formatDateTime(transaction.createdAt);

                                row.appendChild(numberCell);
                                row.appendChild(descriptionCell);
                                row.appendChild(amountCell);
                                row.appendChild(transactionDate);
                                recentTransactionsTableBody.appendChild(row);

                            });
                        } else {
                            const noTransactionsMessage = document.getElementById('noTransactionsMessage');
                            noTransactionsMessage.classList.remove('d-none'); // Show the message if no transactions found
                        }
                    })
                    .catch(error => {
                        console.log('Error fetching transactions:', error);
                    });

                // Open the modal
                $('#viewWalletModal').modal('show');
            } else {
                // Handle case when no wallet details are found
                console.log('No wallet details found');
            }
        })
        .catch(error => {
            console.error(error);
            // Handle any errors that occur during the API request
        });
}


document.addEventListener('DOMContentLoaded', function () {
    fetchWallets();
});
