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

function fetchWalletTransactions(walletId) {
    const table = $('#datatable').DataTable();

    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable').DataTable({
        ajax: {
            url: `../classes/wallet.php?f=fetch_wallet_transactions&walletId=${walletId}`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(details => ({
                    reference: details.orderCode,
                    amount: '<span class="text-primary">&#8358;' + formatNumber(details.amount) + "</span>",
                    balance: '<span class="text-primary">&#8358;' + formatNumber(details.balance) + "</span>",
                    note: details.packageName + " - " + details.note,
                    status: details.delivery_status === '1' ? '<span class="text-success">Success</span>' : '<span class="text-danger">Failed</span>',
                    date: formatDateTime(details.createdAt),
                    // transactionId: details.transactionId
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
            { data: 'reference' },
            { data: 'amount' },
            { data: 'balance' },
            { data: 'note' },
            { data: 'status' },
            { data: 'date' },
        ]
    });

    // // Attach a click event listener to the eye buttons
    // $('#datatable').on('click', '.btn-primary', function () {
    //     // const orderId = $(this).data('delivery-id');

    //     // Fetch individual delivery details using the deliveryId
    //     // fetchDeliveryDetails(orderId);
    // });

}
function fetchWallet(walletId) {
    fetch('../classes/wallet.php?f=fetch_wallet_details&walletId=' + walletId)
        .then(response => response.json())
        .then(wallets => {
            if (wallets.length > 0) {
                const wallet = wallets[0];
                // Populate wallet details in the modal
                document.getElementById('walletId').textContent = wallet.walletIdentifier;
                document.getElementById('walletUser').textContent = wallet.firstName + " " + wallet.lastName;
                document.getElementById('walletBalance').textContent = "₦" + formatNumber(parseFloat(wallet.balance).toFixed(2));

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
    fetchWalletTransactions(wuserId);
    fetchWallet(walletId);
});
