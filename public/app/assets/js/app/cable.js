const buyCableButton = document.getElementById("buy-cable-button");
const buyCableButtonpaySpan = document.getElementById("buyCableButtonpaySpan");
const company = document.getElementById("company");
const iuc = document.getElementById("iuc");
const customerName = document.getElementById('name');
const iucError = document.getElementById('iucError');
const packageContainer = document.getElementById("packageContainer");
const packageSelect = document.getElementById('newPackage');

let cableCompany = null;
let cableTotalAmount = 0;
let cablePackage = null;
let cablePackageName = null;
let amountMoreRequired = 0;
let cablePackagePrice = 0;
let cableIUC = null;
let cablePlan = null;

// check if the service is available
function checkService () {
    // Assuming you have a service ID, replace '123' with the actual service ID
    var serviceId = 'Cable';
    // Make AJAX request
    $.ajax({
        url: '/user/checkService/' + serviceId,
        type: 'GET',
        success: function (response) {
            // Handle the response from the server
            if(response.status){
                console.log('The service is active and working');
            }else{
                $("#network").prop("disabled", true);
                $('#serviceNotification').show();
            }
        },
        error: function (error) {
            // Handle errors
            console.error('Error:', error);
        }
    });
};
// close modal
function closeModal(modal) {
    // console.log(modal);
    $('#' + modal).remove();
    // $('#' + modal).modal('hide');
}

company.addEventListener('change', function () {
    // Remove all options from the second select element
    while (packageSelect.options.length > 0) {
        packageSelect.remove(0);
    }
    cableCompany = company.value;
    iuc.value = '';
    customerName.value = '';
});

iuc.addEventListener('input', function () {
    cableIUC = this.value;
    
    // verify device number
    verifyIUC(cableCompany, cableIUC);
    // fetch packages
    fetchPackages(cableCompany);
});

function verifyIUC(cableCompany, cableIUC) {
    // verify iuc
    fetch('/user/verify-iuc/' + cableIUC + '/'+ cableCompany)
    
        .then(response => response.json())
        .then(data => {
            
            console.log(data);
            if(data){
                customerName.value = data.name;
                fetchPackages(cableCompany);
            }else{
                customerName.value ='';
                iucError.style.display = 'none';
                iucError.innerText = 'Verification failed';
                iucError.style.display = 'block';
            }
            // 7032054653
        })
        .catch(error => {
            console.error('Error verifying  IUC/Smart card:', error);
        });

}

function fetchPackages(company) {
    
    packageContainer.style.display = 'block';
    var service = 'Cable'
    $.ajax({
        url: `/user/fetch-packages/${company}/${service}/${service}`,
        type: 'GET',
        success: function (response) {
            packageSelect.innerHTML = '';
            const selectedOption = document.createElement('option');
            selectedOption.label = 'Choose..';
            selectedOption.value = null;
            packageSelect.appendChild(selectedOption);
            response.forEach(package => {
                const option = document.createElement('option');
                option.id = package.id;
                option.label = package.title;
                option.value = package.id;
                // Append option to the packageSelect
                packageSelect.appendChild(option);
            });

            // Add change event listener to the package select element
            packageSelect.addEventListener('change', () => {
                const selectedPackageId = packageSelect.value;
                const selectedPackage = response.find(package => String(package.id) === selectedPackageId);
                if (selectedPackage) {
                    console.log(selectedPackage);
                    
                    cablePackage = selectedPackage.id;
                    document.getElementById("packageName").value = selectedPackage.title;
                    document.getElementById("total").value = selectedPackage.price;
                    document.getElementById("plan").value = selectedPackage.planID;
                } else {
                    // console.log("Not selected");
                }
            });
        },
        error: function (error) {
            // Handle errors
            console.error('Error:', error);
        }
    });
}

function processBuyCable(event) {
    event.preventDefault();
    isSufficient = isWalletBalanceSufficient();
    // first check if the wallet balance is sufficient for the amount
    if (isSufficient) {
        errorMessage.style.display = "none";
        errorMessage.innerHTML = "";
        buyCableButtonpaySpan.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';
        //arrange the formData and submit
        let formData = new FormData();
        formData.append("userId", userId);
        formData.append("package", cablePackage);
        formData.append("total", cableTotalAmount);
        formData.append("company", cableCompany);
        formData.append("plan", cablePlan);
        formData.append("iuc", cableIUC);
        formData.append("price", cablePackagePrice);
        formData.append("status", 1);
        formData.append("name", userName);
        formData.append("email", userEmail);
        formData.append("packageName", cablePackageName);

        //send the request
        fetch("../classes/Cable.php?f=buy_cable", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("There was a problem buying cable");
                } else {
                    return response.json();
                }
            })
            .then(data => {
                if (data.success) {
                    showDataSuccessMessage();
                } else {
                    errorMessage.style.display = "block";
                    errorMessage.innerText = data.message;
                    errorMessage.scrollIntoView({ behavior: 'smooth' });

                }
                console.log(data);
            })
            .catch((error) => {
                // console.error(error);
                // errorMessage.style.display = "block";
                // errorMessage.innerText = "An error occured. Try again later";
                // errorMessage.scrollIntoView({ behavior: 'smooth' });
                // alert(error.message);
            })
            .finally(() => {
                // Enable submit button and hide loading spinner
                buyCableButton.disabled = false;
                buyCableButton.disabled = true;
                buyCableButtonpaySpan.innerHTML = "";
            });
    } else { //wallet balance not enough
        errorMessage.style.display = "block";
        errorMessage.innerHTML = "Your wallet balance is insufficient for this amount. You need  &#8358;" + amountMoreRequired + " more to proceed.";
        // Scroll to the error message div
        errorMessage.scrollIntoView({ behavior: 'smooth' });
    }
}
// Function to format the number with commas
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
    checkService();
});
