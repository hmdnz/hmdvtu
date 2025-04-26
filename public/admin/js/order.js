function fetchRecentActivities() {
    const table = $('#datatable').DataTable();

    // Destroy the existing DataTable instance
    if ($.fn.DataTable.isDataTable(table)) {
        table.destroy();
    }

    $('#datatable').DataTable({
        ajax: {
            url: `../classes/order.php?f=fetch_orders`,
            type: 'GET',
            dataSrc: function (data) {
                return data.map(delivery => ({
                    package: delivery.type,
                    service: delivery.type,
                    orderCode: delivery.orderCode,
                    amount: '<span class="text-primary">&#8358;' + formatNumber(delivery.amount) + "</span>",
                    total: '<span class="text-primary">&#8358;' + formatNumber(delivery.total) + "</span>",
                    beneficiaries: delivery.beneficiaries,
                    status: delivery.delivery_status === '1' ? '<span class="text-success">Success</span>' : '<span class="text-danger">Failed</span>',
                    orderId: delivery.orderId
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
            { data: 'orderCode' },
            { data: 'package' },
            { data: 'amount' },
            { data: 'total' },
            { data: 'status' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <a type="button" data-bs-toggle="modal" class="btn btn-primary btn-sm" data-bs-target="#viewDeliveryInfo" data-delivery-id="${data.orderId}">
                            <i class="fa fa-eye"></i>
                        </a>`;
                }
            }
        ]
    });

    // Attach a click event listener to the eye buttons
    $('#datatable').on('click', '.btn-primary', function () {
        const orderId = $(this).data('delivery-id');

        // Fetch individual delivery details using the deliveryId
        fetchDeliveryDetails(orderId);
    });
}

function fetchDeliveryDetails(orderId) {
    // Display loading spinner
    $('#viewDeliveryDetailsModal .modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');

    // Hide the details until the response is received
    $('#viewDeliveryDetailsModal .modal-body ul').hide();

    // Perform AJAX request to fetch individual delivery details using the deliveryId
    $.ajax({
        url: `../classes/order.php?f=fetch_order_details&orderId=${orderId}`,
        type: 'GET',
        success: function (data) {
            // Update the spans with the fetched data
            $('#viewDeliveryDetails-code').text(data[0].orderCode);
            $('#viewDeliveryDetails-type').text(data[0].type);
            $('#viewDeliveryDetails-amount').text(formatNumber(data[0].amount));
            $('#viewDeliveryDetails-total').text(formatNumber(data[0].total));
            $('#viewDeliveryDetails-balance').text(formatNumber(data[0].balance));
            $('#viewDeliveryDetails-package').text(data[0].package_name);
            $('#viewDeliveryDetails-biller').text(data[0].biller_name);
            $('#viewDeliveryDetails-note').text(data[0].note);
            $('#viewDeliveryDetails-receipients').text(data[0].recipients);
            $('#viewDeliveryDetails-status').html(data[0].delivery_status === '1' ? '<span class="text-success">Success</span>' : '<span class="text-danger">Failed</span>');
            $('#viewDeliveryDetails-date').text(formatDateTime(data[0].createdAt));

            // Show the details and remove loading spinner
            $('#viewDeliveryDetailsModal .modal-body ul').show();
            $('#viewDeliveryDetailsModal .modal-body > div').remove();
        },
        error: function (xhr, status, error) {
            // Display an error message if the request fails
            $('#viewDeliveryDetailsModal .modal-body').html('<div class="text-center text-danger">Failed to fetch delivery details.</div>');
        }
    });
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



document.addEventListener('DOMContentLoaded', function () {
    fetchRecentActivities();
});