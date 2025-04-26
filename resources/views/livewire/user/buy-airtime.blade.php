<div class="col-md-9 mx-auto">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">Airtime Top Up</h4>
            </div>
        </div>
        <div class="card-body">
            <div class="container">
                @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit.prevent="submit">
                    @csrf
                    <div class="row">
                        @php($service = 'airtime')
                        <div class="form-group col-md-12">
                            <label class="form-label">Choose a Network</label>
                            <select class="form-control" wire:model="biller" id="biller" required>
                                <option selected value="">Choose..</option>
                                @foreach($billers as $biller)
                                    <option value="{{ $biller->id }}">{{ $biller->title }}</option>
                                @endforeach
                            </select>
                            @error('biller') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label class="form-label">Amount</label>
                            <input type="number" wire:model="amount" id="amount" class="form-control" required>
                            @error('amount') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label class="form-label">Choose a Category</label>
                            <select class="form-control" wire:model="category" id="category" required>
                                <option selected value="">Choose..</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                @endforeach
                            </select>
                            @error('category') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label class="form-label">Choose a Package</label>
                            <select class="form-control" wire:model="package" id="package" required>
                                <option selected value="">Choose..</option>
                                @foreach($packages as $pack)
                                    <option value="{{ $pack->id }}">{{ $pack->name }}</option>
                                @endforeach
                            </select>
                            @error('package') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label class="form-label">Phone Number</label>
                            <input type="text" wire:model="number" class="form-control" id="phone_number" required>
                            @error('number') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group col-md-12">
                            <label class="form-label">Total</label>
                            <input type="number" wire:model="total" name="total" class="form-control" id="total" required>
                            @error('total') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label class="form-label">Pin</label>
                            <input type="password" wire:model="pin" class="form-control" id="pin" maxlength="4" required>
                            @error('pin') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    

                    <button type="submit" id="submitBTN" class="btn secondary-btn col-12" wire:loading.attr="disabled">
                        <span wire:loading.remove>Recharge</span>
                        <span wire:loading>Submitting...</span>
                        <span id="total_span"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    // ELEMENTS
    const buyBTN = document.getElementById("submitBTN");
    const billerInput = document.getElementById("biller");
    const amountInput = document.getElementById("amount");
    const categorySelect = document.getElementById('category');
    const packageSelect = document.getElementById('package');
    const phoneNumberInput = document.getElementById('phone_number');
    const pinInput = document.getElementById('pin');
    const totalSpan = document.getElementById('total_span');

    // const values
    let service = 'Airtime';
    let category, categoryName = null;
    let biller, billerName = null;
    let packageID, packageName = null, packagePrice = 0;
    let amount, total = 0;

    // fetch categories
    function checkCategories(service, biller) {
        $.ajax({
            url: `/user/check-switches/${service}/${biller}`,
            type: 'GET',
            success: function (response) {
                // assign biller info
                biller = response.biller.id;
                billerName = response.biller.title;
                // removing existing categories
                while (categorySelect.options.length > 0) {
                    categorySelect.remove(0);
                }
                // append the data
                categorySelect.innerHTML = '<option value="">Choose..</option>';
                response.categories.forEach(category => {
                    const option = document.createElement('option');
                    option.id = category.title;
                    option.text = category.title;
                    option.value = category.id;
                    categorySelect.appendChild(option);
                });
            },
            error: function (error) {
                console.error('Error Fetching Categories:', error);
            }
        });
    } 

    // fetch packages
    function fetchPackages(service, biller, category) {
        buyBTN.disabled = true;
        $.ajax({
            url: `/user/fetch-packages/${biller}/${category}/${service}`,
            type: 'GET',
            success: function (response) {
                console.log(response);
                packageSelect.innerHTML = '<option value="">Choose..</option>';
                response.forEach(item => {
                    const option = document.createElement('option');
                    option.id = item.id;
                    option.text = item.title;
                    option.value = item.id;
                    packageSelect.appendChild(option);
                    packageSelect.addEventListener('change', () => {
                        packageID = item.id;
                        packagePrice = item.price;
                        packageName = item.title;
                        // process the package variables
                        processPackage(item);
                    });
                });
            },
            error: function (error) {
                console.error('Error while fetching packages :', error);
            }
        });
        
    }

    // process selected package
    function processPackage(package) {
        phoneNumberInput.addEventListener('input', calculateTotalAmount);
        amountInput.addEventListener('input', calculateTotalAmount);
        function calculateTotalAmount() {
            let price = amountInput.value;
            if (price != "") {
                phoneNumberInput.disabled = false;
            }
            let phoneNumberString = phoneNumberInput.value.trim();
            let phoneNumbers = phoneNumberString.split(/[,\n\s]+/); // Split numbers by comma, space, or new line
            const uniquePhoneNumbers = [...new Set(phoneNumbers)];
            let totalAmount = 0;
            const numberCounts = {}; // Track the count of each unique number
            uniquePhoneNumbers.forEach((phoneNumber) => {
                if (isValidNigeriaPhoneNumber(phoneNumber)) {
                    if (numberCounts[phoneNumber]) {
                        numberCounts[phoneNumber] += 1;
                    } else {
                        numberCounts[phoneNumber] = 1;
                    }
                }
            });
            let validNumbers = '';
            let validNumberCount = 0; // Track the count of valid numbers
            for (const phoneNumber in numberCounts) {
                const count = numberCounts[phoneNumber];
                validNumbers += `${phoneNumber}, `;
                validNumberCount++;
                buyBTN.disabled = false;
            }
            // Calculate the amount user should pay based on the phone numbers
            amount = price * validNumberCount;
            // Apply the discount to the amount
            total = amount * (1 - package.price / 100);
            
            // Display the discounted amount and discount information
            const formattedPrice = formatNumber(price);
            const formattedAmount = formatNumber(amount.toFixed(2));
            const formattedTotal = formatNumber(total.toFixed(1));
            document.getElementById('total').value = total;
            document.getElementById('billerName').value = billerName;
            document.getElementById('packageName').value = packageName;
            // total_summary.innerHTML = discountInfo;
            totalSpan.innerHTML = ` - Pay: &#8358;${formattedTotal} (${package.price}% discount)`;
            validNumbers = validNumbers.slice(0, -2); // Remove the trailing comma and space
            
        }
        // Call the calculation function initially
        calculateTotalAmount();
    }

    // Function to format the number with commas
    function formatNumber(number) {
        return new Intl.NumberFormat().format(number);
    }
    // format special characters
    function formatSpecialCar(text){
        var doc = new DOMParser().parseFromString(text, "text/html");
    return doc.body.textContent;
    }

    function isValidNigeriaPhoneNumber(phoneNumber) {
        // Validate the phone number format for Nigeria (11 digits)
        const nigeriaPhoneNumberRegex = /^(?:\+?234|0)?[789]\d{9}$/;
        return nigeriaPhoneNumberRegex.test(phoneNumber);
    }

    // handle biller change
    billerInput.addEventListener('change', () => {
        // Reset the biller category and package selection to empty
        billerInput.values = '';
        categorySelect.value = '';
        packageSelect.value = '';
        // store the selected biller
        biller = billerInput.value;
        console.log('billerinput', biller);
        // fetch categories
        checkCategories(service, biller);
    });

    // handle category change
    categorySelect.addEventListener('change', () => {
        // get the selected option
        let selectedOption = categorySelect.options[categorySelect.selectedIndex];
        // store the selected category
        category = selectedOption.value;
        categoryName = selectedOption.text;
    
        // fetch packages
        fetchPackages(service, biller, categoryName);
    });

    document.getElementById("billerName").addEventListener("change", function () {
        Livewire.emit("updateBillerName", this.value);
    });

    document.getElementById("packageName").addEventListener("change", function () {
        Livewire.emit("updatePackageName", this.value);
    });

</script>