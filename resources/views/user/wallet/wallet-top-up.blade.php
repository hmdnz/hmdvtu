
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="ZaumaData, Data, Airtime, bulk sms, cable subscription" />
    <meta name="developer" content="Shahuci Global Resources" />
    <meta name="website" content="www.sgr.com.ng" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fund Wallet | ZaumaData</title>
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
        
  @php( $page = 'wallet-topup')
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
                                    <h1>&#8358; <span class="wallet-balance">{{ (is_null(auth()->user()->wallet)) ? 'Unknown' : number_format(auth()->user()->wallet->mainBalance, 2, '.', ',') }}</span></h1>
                                    <p>Wallet Balance</p>
                                </div>
                                <div>
                                    <a href="{{route('user.walletTopUp')}}" class="btn btn-link btn-soft-light">
                                        <i class="fa fa-credit-card"></i>
                                        Fund Wallet
                                    </a>
                                </div>
                            </div>
                            <div class="col-12 text-danger ">
                                <marquee id="marquee"></marquee>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iq-header-img primary-bg">
                    {{-- <img src="{{ asset('app/assets/images/dashboard/top-header.png') }}" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX"> --}}
                </div>
            </div>
        </div>
        <div class="conatiner-fluid content-inner mt-n5 py-0">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="card" >
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="header-title">
                                <h4 class="card-title">Fund Wallet</h4>
                                <p class="mb-0"></p>Select a payment channel to fund your wallet</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row" id="accountRows">
                                @if(auth()->user()->isVerified == 1) 
                                    @if($RVAs->isEmpty())
                                        <div class="col-md-12 my-4">
                                            <center>
                                                <a href="{{route('user.newRVA')}}" class="btn btn-primary">Generate New Accounts</a>
                                            </center>    
                                        </div>  
                                    @else
                                        @foreach($RVAs as $account)                         
                                        <div class="col-md-12">
                                            <div class="container border rounded p-2 mb-2 ">
                                                <div class="container">
                                                    <h6>Automatic Bank Transfer </h6>
                                                    <p class="my-0 small text-muted">Account Name: {{$account->accountName}}</p>
                                                    <p class="my-0 small text-muted">Bank : {{$account->accountBank}}</p>
                                                    <p class="my-0 small text-muted">Account No. {{$account->accountNumber}}</p>
                                                    <p class="small mb-1 text-muted">Fee &#8358; 50 </p>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                    <div class="col-md-12">
                                        <div class="container border rounded p-2 mb-2 ">
                                                <h6>Pay With Monnify</h6>
                                                <form id="paymentForm" method="POST">
                                                    <input type="email" id="email-address" name="email" hidden/>
                                                    <input type="text" id="first-name" name="fname" hidden/>
                                                    <input type="text" id="last-name" name="lname" hidden/>
                                                    <input type="text" id="payUserName" name="username" hidden/>
                                                    <input type="text" id="payWalletID" name="wallet" hidden/>
                                                    <input type="text" id="payUserID" name="user" hidden/>
                                                    <input type="text" id="payTotal" name="total" hidden/>
                                                    <input type="text" id="payFees" name="fees" hidden/>
                                                    <div class="mb-3">
                                                        <label for="amount" class="form-label small">Amount</label>
                                                        <input type="number" id="amount-before" name="amount" class="form-control">
                                                        <input type="text" id="amount" hidden>
                                                        <small id="message"></small>
                                                    </div>
                                                    <p class="my-1">Fee &#8358; <span id="paystackFees">0</span> | Total : &#8358; <span id="paystackTotal">0.00</span></p>
                                                    <div id="payment-button-container col-12">
                                                        <button type="submit" name="paywithMonnify" id="pay-button" class="btn btn-primary col-12" onclick="processPayment(event)"  disabled>Pay with Monnify</button>
                                                    </div>
                                                </form>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-12 my-4">
                                        <p class="text-muted">Note: You need to verfy your identity using BVN before you can fund your wallet</p>
                                        <center>
                                            <a data-bs-toggle="modal" data-bs-target="#verifyAccountModal"  class="btn primary-btn">Verify Account</a>
                                        </center>    
                                    </div>  
                                @endif
                            </div>                
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('components.user.footer')
    </main>

    @include('user.partials.verifyAccount')
    
    
  <!-- service notification -->
  <div class="modal" id="serviceNotification">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Service Notification</h4>
                <!-- <button type="button" class="btn-close"  data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <p><span class="fw-bold">We are sorry</span> to notify you that this service is not currently available, but we are working to solve the problems and we will activate it as soon as possible.</p>
                <center>
                    <a href="/user/dashboard" type="button" class="btn primary-btn" id="">Okay</a>
                    <button type="button" class="btn primary-btn" data-dismiss="modal" onclick="closeModal('serviceNotification')">Close</button>
                </center>
            </div>
        </div>
    </div>
  </div>
  {{-- announcement module --}}
  <div class="modal mt-5" id="viewAnnouncementModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="text-center mx-auto"><i class="fa fa-bell"></i> Announcement</h3>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
          <p><span id="viewAnnouncementBody">Hi, Welcome to <b>Zauma Data</b>, we are happy to have you here. You can find all kind of VTU services here such as Data, Airtime, Data Card, Bulk SMS and many more at affordable price. Thank you.</span></p>
        </div>
        <div class="modal-footer ">
          <button type="button" class="btn primary-btn mx-auto" data-dismiss="modal" onclick="closeModal2('viewAnnouncementModal')">Close</button>
        </div>
      </div>
    </div>
  </div>
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
    
    const param1 = "{{env('MONNIFY_LIVE_API_KEY')}}";
    const param2 = "{{env('MONNIFY_LIVE_CONTRACT_CODE')}}";

    function formatNumber(number) {
        return new Intl.NumberFormat().format(number);
    }
    function closeModal(modal) {
        $('#' + modal).hide();
    }
    function closeModal2(modal) {
        $('#' + modal).modal("hide");    
    }
    function fetchAnnouncements() {
        $.ajax({
            url: `/user/get-announcement`,
            type: 'GET',
            success: function (response) {
                // Handle the response from the server
                var latestAnnouncement = response.body;
                if (!response || Object.keys(response).length === 0) {
                    console.log('Response body is empty or does not contain data.');
                } else {
                    document.getElementById('viewAnnouncementBody').textContent = latestAnnouncement;
                }
                // Open the view modal
                $('#viewAnnouncementModal').modal('show');
            },
            error: function (error) {
                // Handle errors
                console.error('Error:', error);
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        var verifyAccountModal = document.getElementById('verifyAccountModal');

        // When modal is shown, 
        verifyAccountModal.addEventListener('show.bs.modal', function(event) {
            let bankSelect = document.getElementById('bankSelect');
            bankSelect.innerHTML = '<option value="">Loading...</option>'; // Show loading state
            
            fetch('/api/others/banks', {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            })
            .then(response => response.json())
            .then(data => {
                bankSelect.innerHTML = '<option value="">Select Bank...</option>';
                data.data.forEach(bank => {
                    let option = document.createElement('option');
                    option.value = bank.code;
                    option.textContent = bank.name;
                    bankSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching banks:', error);
                bankSelect.innerHTML = '<option value="">Failed to load banks</option>';
            });
        });

        // Handle API Request
        document.getElementById('verifyButton').addEventListener('click', function() {
            var button = this;
            var icon = document.getElementById('verifyIcon');
            var text = document.getElementById('verifyText');
            var bvn = document.getElementById('bvnInput').value;
            var bank = document.getElementById('bankSelect').value;
            var accountNumber = document.getElementById('accountNumberInput').value;
            // Change button to loading state
            button.disabled = true;
            icon.classList.remove('fa-sync');
            icon.classList.add('fa-spinner', 'fa-spin'); // spinning effect
            text.textContent = "verifying...";
            
            const bodyData = {
                bvn: bvn,
                bank_code: bank,
                account_number: accountNumber,
                username: userName
            };
            // Make API Request
            fetch(`/api/kyc/bvn/account`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(bodyData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                  document.getElementById('verifyStatus').textContent = data.status;
                  document.getElementById('responseMessage').textContent = data.message;
                }
                else
                {
                    document.getElementById('verifyStatus').textContent = 'Failed';
                  document.getElementById('responseMessage').textContent = 'We cannot verify you identity at this time. Contact support.';
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                // restore button state
                button.disabled = false;
                icon.classList.remove('fa-spinner', 'fa-spin');
                icon.classList.add('fa-sync');
                text.textContent = "verify";
                // reload page after 3 seconds
                setTimeout(() => {
                    location.reload();
                }, 2000);
            });
        });
        
        @if($page == 'index')
            fetchAnnouncements();
        @endif
    });
    
</script>
{{-- monnify --}}
<script type="text/javascript" src="https://sdk.monnify.com/plugin/monnify.js"></script>
{{-- topup js --}}
<script src="{{ asset('app/assets/js/app/topup.js') }}"></script>

</body>
</html>
