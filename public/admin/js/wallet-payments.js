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

function fetchWalletPayments(userId) {
    const table = $('#datatable').DataTable();

    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable').DataTable({
        ajax: {
            url: `../classes/wallet.php?f=fetch_wallet_payments&walletId= ${userId}`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    reference: delivery.reference,
                    gateway: delivery.gateway,
                    channel: delivery.channel,
                    fees: '<span class="text-primary">&#8358;' + formatNumber(delivery.fees) + "</span>",
                    total: '<span class="text-primary">&#8358;' + formatNumber(delivery.total) + "</span>",
                    status: delivery.status === '1' ? '<span class="text-success">Success</span>' : '<span class="text-danger">Failed</span>',
                    createdAt: "<small>" + formatDateTime(delivery.createdAt) + "</small>",
                    paymentId: delivery.paymentId,
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
            { data: 'gateway' },
            { data: 'channel' },
            { data: 'fees' },
            { data: 'total' },
            { data: 'status' },
            { data: 'createdAt' },
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
    fetchWalletPayments(wuserId);
    fetchWallet(walletId);
});

