
<!doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="ZaumaData, Data, Airtime, bulk sms, cable subscription" />
    <meta name="developer" content="Shahuci Global Resources" />
    <meta name="website" content="www.sgr.com.ng" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KYC - Set Pin | ZaumaData</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('main/img/logo.png') }}" />
    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="{{ asset('app/assets/css/core/libs.min.css') }}" />
    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="{{ asset('app/assets/css/hope-ui.css') }}" />
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('app/assets/css/toastr.css') }}">
    {{-- style --}}
    <link rel="stylesheet" href="{{ asset('app/assets/css/style.css') }}">
    <!-- FontAwesome 5-->
    <link href="{{ asset('general/fortawesome/font-awesome/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" type="text/css">
    
    @livewireStyles
</head>

<body class="" id="body">
        
    @php( $page = 'verify')
    @include('components.user.sidebar')
<main class="main-content">
    <div class="position-relative iq-banner">
        <!--Nav Start-->
        @include('components.user.navbar')
        <div class="iq-navbar-header" style="height: 215px;">
            <div class="container-fluid iq-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="flex-wrap d-flex justify-content-between align-items-center">
                            <div>
                                <h1>KYC</h1>
                                <p>Please set up your pin that you can use for transaction</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="iq-header-img primary-bg">
            </div>
        </div>
    </div>
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-md-7 mx-auto">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Set Pin</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form >
                            <div class="form-group">
                                <label for="newPin" class="form-label">New Pin</label>
                                <input type="password" class="form-control" name="pin" maxlength="4" id="newPin" required>
                                @error('pin')<span class="text-danger small">{{ $message}}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="confirmNewPin" class="form-label">Confirm New Pin</label>
                                <input type="password" class="form-control" name="pin_confirmation" maxlength="4" id="confirmNewPin" required>
                                @error('pin_confirmation')<span class="text-danger small">{{ $message}}</span>@enderror
                            </div>
                        </form>
                        <p><strong>Status:</strong> <span id="pinStatus"></span></p>
                        <p><strong>Response Message:</strong> <span id="responseMessage"></span></p>
                        <button id="pinButton" class="btn primary-btn my-1 p-2">
                            <i class="fa fa-sync" id="pinIcon"></i> <span id="pinText">Submit</span>
                        </button>
                        <a href="{{route('user.dashboard')}}" class="btn primary-btn my-1 p-2" id="nextBTN" disabled>Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.user.footer')
</main>

@livewireScripts
<!-- Library Bundle Script -->
<script src="{{ asset('app/assets/js/core/libs.min.js') }}"></script>
<!-- External Library Bundle Script -->
<script src="{{ asset('app/assets/js/core/external.min.js') }}"></script>
<!-- Settings Script -->
<script src="{{ asset('app/assets/js/plugins/setting.js') }}"></script>
<!-- App Script -->
<script src="{{ asset('app/assets/js/hope-ui.js') }}" defer></script>
<!-- Toastr js -->
<script src="{{ asset('app/assets/js/toastr.js') }}"></script>

@if (session()->has('message'))
<script>
    toastr.info("{{ session('message') }}");        
</script>
@endif
@if (session()->has('success'))
<script>
     toastr.success("{{ session('success') }}");        
</script>
 @endif
@if (session()->has('error'))
<script>
    toastr.error("{{ session('error') }}");        
</script>
@endif

@if($errors->any())
    <script>
        let errorMessages = `{!! implode('<br>', $errors->all()) !!}`;
        toastr.error(errorMessages, 'Validation Errors', {timeOut: 5000, closeButton: true, progressBar: true, escapeHtml: false});
    </script>
@endif

<script>
    var userName = "{{ auth()->user()->username}}";
    var userName23 = "{{ auth()->user()->username}}";
    var userEmail = "{{ auth()->user()->email}}";
    var userId = "{{ auth()->user()->id}}";
    var walletId = "{{ auth()->user()->wallet->id}}";

    function formatNumber(number) {
        return new Intl.NumberFormat().format(number);
    }
    function closeModal(modal) {
        $('#' + modal).hide();
    }
    function closeModal2(modal) {
        $('#' + modal).modal("hide");    
    }
    document.addEventListener("DOMContentLoaded", function() {  
        // Handle API Request
        document.getElementById('pinButton').addEventListener('click', function() {
            var button = this;
            var icon = document.getElementById('pinIcon');
            var text = document.getElementById('pinText');
            var pin = document.getElementById('newPin').value;
            var confirmNewPin = document.getElementById('confirmNewPin').value;
            // Change button to loading state
            button.disabled = true;
            icon.classList.remove('fa-sync');
            icon.classList.add('fa-spinner', 'fa-spin'); // spinning effect
            text.textContent = "Submitting...";
            
            const bodyData = {
                pin: pin,
                pin_confirmation: confirmNewPin,
                username: userName
            };
            // Make API Request
            fetch(`/user/set-pin`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(bodyData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status && data.status == 'success') {
                  document.getElementById('pinStatus').textContent = data.status;
                  document.getElementById('responseMessage').textContent = data.message;
                  document.getElementById('nextBTN').disabled = false;
                }
                else
                {
                    document.getElementById('pinStatus').textContent = 'Failed';
                    document.getElementById('responseMessage').textContent = data.message ?? 'We cannot store your pin at this moment.';
                    document.getElementById('nextBTN').disabled = true;
                }
                // Restore button state
                // button.disabled = false;
                icon.classList.remove('fa-spinner', 'fa-spin');
                icon.classList.add('fa-sync');
                text.textContent = "Submit";
            })
            .catch(error => console.error('Error:', error))
            
        });
        
    });
    
</script>

</body>
</html>

