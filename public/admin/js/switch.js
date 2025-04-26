
// switch service category
function switchService(switchId, biller, status) {
    var formData = new FormData();
    formData.append("id", switchId);
    formData.append("biller", biller);
    formData.append("status", status);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post({
        url: "/admin/update-category",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.success) {                            
                toastr.success(data.message);
                setTimeout(function() {
                    window.location.reload();
                }, 10000);
            } else {
                toastr.error(data.message, {
                    CloseButton: true,
                    ProgressBar: true
                });
                setTimeout(function() {
                    window.location.reload();
                }, 10000);
            }
        }
    });
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
    // fetchSwitches();
});