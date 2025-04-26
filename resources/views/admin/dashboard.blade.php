
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>@yield('title') | Admin</title>

   <!-- Favicon -->
   <link rel="shortcut icon" href="{{ asset('main/img/logo.png') }}" />

   <!-- Library / Plugin Css Build -->
   <link rel="stylesheet" href="{{ asset('app/assets/css/core/libs.min.css') }}" />
   <!-- Hope Ui Design System Css -->
   <link rel="stylesheet" href="{{ asset('app/assets/css/hope-ui.css') }}" />
   <!-- Custom Css -->
   <link rel="stylesheet" href="{{ asset('app/assets/css/custom.min.css?v=2.0.0') }}" />
   <!-- Dark Css -->
   <link rel="stylesheet" href="{{ asset('app/assets/css/dark.min.css') }}" />
   <!-- Customizer Css -->
   <link rel="stylesheet" href="{{ asset('app/assets/css/customizer.min.css') }}" />
   <!-- RTL Css -->
   <link rel="stylesheet" href="{{ asset('app/assets/css/rtl.min.css') }}" />
   <!-- Toastr -->
   <link rel="stylesheet" href="{{ asset('app/assets/css/toastr.css') }}">
   <!-- STYLE -->
   <link rel="stylesheet" href="{{ asset('app/assets/css/style.css') }}">
   <!-- FontAwesome 5-->
   <link href="{{ asset('general/fortawesome/font-awesome/css/all.min.css') }}" rel="stylesheet" type="text/css">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" type="text/css">
   
   <style>
  </style>
</head>

<body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    @php( $page = 'index')
    @include('components.admin.sidebar')
    <main class="main-content">
        <div class="position-relative iq-banner">
            <!--Nav Start-->
            @include('components.admin.navbar')
            <div class="iq-navbar-header" style="height: 215px;">
                <div class="container-fluid iq-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flex-wrap d-flex justify-content-between align-items-center">
                                <div>
                                    <h1>Welcome {{ auth()->user()->firstName }}</h1>
                                    <p>Welcome to ZaumaData Admin Dashboard.</p>
                                </div>
                                <div>
                                    <!-- <a href="settings" class="btn btn-link btn-soft-light">
                                        <i class="fa fa-gear"></i>
                                        Settings
                                    </a> -->
                                </div>
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
                <div class="col-md-12 col-lg-12">
                    <div class="row">
                        <!-- info card -->
                        <div class="col-md-3">
                            <a href="{{route('admin.users')}}" class="card rounded-2 p-0">
                                <div class="card-body p-2 py-4">
                                    <div class="container p-0 row">
                                        <div class="col-4">
                                            <span class="fa-stack fa-2x">
                                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                                <i class="fas fa-users fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </div>
                                        <div class="col-8 ps-4 text-start">
                                            <p class="text-muted mt-1 mb-0">Users</p>
                                            <h5 class="card-title"><span id="users-counts">{{$usersCount}}</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- info card -->
                        <div class="col-md-3">
                            <a href="{{route('admin.historyPayments')}}" class="card rounded-2 p-0">
                                <div class="card-body p-2 py-4">
                                    <div class="container p-0 row">
                                        <div class="col-4">
                                            <span class="fa-stack fa-2x">
                                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                                <i class="fas fa-credit-card fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </div>
                                        <div class="col-8 ps-4 text-start">
                                            <p class="text-muted mt-1 mb-0">Payments</p>
                                            <h5 class=" card-title"><span id="payments-counts">{{$paymentsCount}}</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- info card -->
                        <div class="col-md-3">
                            <a href="{{route('admin.historyOrders')}}" class="card rounded-2 p-0">
                                <div class="card-body p-2 py-4">
                                    <div class="container p-0 row">
                                        <div class="col-4">
                                            <span class="fa-stack fa-2x">
                                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                                <i class="fas fa-shopping-cart fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </div>
                                        <div class="col-8 ps-4 text-start">
                                            <p class="text-muted mt-1 mb-0">Orders</p>
                                            <h5 class=" card-title"><span id="orders-counts">{{$ordersCount}}</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- info card -->
                        <div class="col-md-3">
                            <a href="{{route('admin.historyTransactions')}}" class="card rounded-2 p-0">
                                <div class="card-body p-2 py-4">
                                    <div class="container p-0 row">
                                        <div class="col-4">
                                            <span class="fa-stack fa-2x">
                                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                                <i class="fas fa-table fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </div>
                                        <div class="col-8 ps-4 text-start">
                                            <p class="text-muted mt-1 mb-0">Transactions</p>
                                            <h5 class="card-title"><span id="transactions-counts">{{$transactionsCount}}</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- info card -->
                        <div class="col-md-4">
                            <a href="{{route('admin.historyPayments')}}" class="card rounded-2 p-0">
                                <div class="card-body p-2 py-4">
                                    <div class="container p-0 row">
                                        <div class="col-4">
                                            <span class="fa-stack fa-2x">
                                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                                <i class="fas fa-credit-card fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </div>
                                        <div class="col-8 text-start">
                                            <p class="text-muted">Payments</p>
                                            <h5 class=" card-title">
                                                <span id="payment-total">
                                                    <i class="fas fa-spinner fa-spin"></i> <!-- Spinner -->
                                                </span> 
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- info card -->
                        <div class="col-md-4">
                            <a href="{{route('admin.historyOrders')}}" class="card rounded-2 p-0">
                                <div class="card-body p-2 py-4">
                                    <div class="container p-0 row">
                                        <div class="col-4">
                                            <span class="fa-stack fa-2x">
                                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                                <i class="fas fa-shopping-cart fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </div>
                                        <div class="col-8 text-start">
                                            <p class="text-muted">Orders</p>
                                            <h5 class=" card-title">
                                                <span id="order-total">
                                                    <i class="fas fa-spinner fa-spin"></i> <!-- Spinner -->
                                                </span> 
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- info card -->
                        <div class="col-md-4">
                            <a href="{{route('admin.historyTransactions')}}" class="card rounded-2 p-0">
                                <div class="card-body p-2 py-4">
                                    <div class="container p-0 row">
                                        <div class="col-4">
                                            <span class="fa-stack fa-2x">
                                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                                <i class="fas fa-table fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </div>
                                        <div class="col-8 text-start">
                                            <p class="text-muted">Transactions</p>
                                            <h5 class="card-title">
                                                <span id="transaction-total">
                                                    <i class="fas fa-spinner fa-spin"></i> <!-- Spinner -->
                                                </span> 
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        
                        <!-- info card -->
                        <div class="col-md-4">
                            <a href="#" class="card rounded-2 p-0">
                                <div class="card-body p-2 py-4">
                                    <div class="container p-0 row">
                                        <div class="col-4">
                                            <span class="fa-stack fa-2x">
                                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                                <i class="fas fa-briefcase fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </div>
                                        <div class="col-8 p-1 text-start">
                                            <p class="text-muted">Monnify Balance</p>
                                            <h5 class="card-title">
                                                <span id="monnify-balance">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                </span>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- info card -->
                        <div class="col-md-4">
                            <a href="#" class="card rounded-2 p-0">
                                <div class="card-body p-2 py-4">
                                    <div class="container p-0 row">
                                        <div class="col-4">
                                            <span class="fa-stack fa-2x">
                                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                                <i class="fas fa-briefcase fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </div>
                                        <div class="col-8 p-1 text-start">
                                            <p class="text-muted">Alrahuz Balance</p>
                                            <h5 class="card-title">
                                                <span id="alrahuzdata-balance">
                                                    <i class="fas fa-spinner fa-spin"></i> <!-- Spinner -->
                                                </span>    
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- info card -->
                        <div class="col-md-4">
                            <a href="#" class="card rounded-2 p-0">
                                <div class="card-body p-2 py-4">
                                    <div class="container p-0 row">
                                        <div class="col-4">
                                            <span class="fa-stack fa-2x">
                                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                                <i class="fas fa-briefcase fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </div>
                                        <div class="col-8 p-1 text-start">
                                            <p class="text-muted">EasyAccess Balance</p>
                                            <h5 class="card-title">
                                                <span id="easyaccess-balance">
                                                    <i class="fas fa-spinner fa-spin"></i> <!-- Spinner -->
                                                </span>    
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- info card -->
                        <div class="col-md-4">
                            <a href="#" class="card rounded-2 p-0">
                                <div class="card-body p-2 py-4">
                                    <div class="container p-0 row">
                                        <div class="col-4">
                                            <span class="fa-stack fa-2x">
                                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                                <i class="fas fa-briefcase fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </div>
                                        <div class="col-8 p-1 text-start">
                                            <p class="text-muted">BulkSMS Balance</p>
                                            <h5 class="card-title">
                                                <span id="bulksmsnigeria-balance">
                                                    <i class="fas fa-spinner fa-spin"></i> <!-- Spinner -->
                                                </span>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        @include('components.admin.footer')
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
  function formatNumber(number) {
      return new Intl.NumberFormat().format(number);
  }
  function closeModal(modal) {
      $('#' + modal).hide();
  }
  function closeModal2(modal) {
      $('#' + modal).modal("hide");    
  }
  
  document.addEventListener("DOMContentLoaded", function () {
        const bulkSMSNigeriaElement = document.getElementById("bulksmsnigeria-balance");
        const monnifyElement = document.getElementById("monnify-balance");
        const alrahuzdataElement = document.getElementById("alrahuzdata-balance");
        const easyaccessElement = document.getElementById("easyaccess-balance");
        const paymentTotalElement = document.getElementById("payment-total");
        const orderTotalElement = document.getElementById("order-total");
        const transactionTotalElement = document.getElementById("transaction-total");

        // fetch bulk sms total
        async function fetchInternalTotal() {
            try {
                // Show spinner
                paymentTotalElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                orderTotalElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                transactionTotalElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                // Make the API call
                let response = await fetch("/admin/info/internal-total", {
                    method: "GET",
                });

                let data = await response.json();
                
                if (data.status) {
                    // Format total with commas
                    let formattedPayment = new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN' }).format(data.payment_total);
                    let formattedTransaction = new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN' }).format(data.transaction_total);
                    let formattedOrder = new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN' }).format(data.order_total);
                    
                    // Replace spinner with balance
                    paymentTotalElement.innerHTML = formattedPayment;
                    transactionTotalElement.innerHTML = formattedTransaction;
                    orderTotalElement.innerHTML = formattedOrder;
                } else {
                    paymentTotalElement.innerHTML = "Failed to load";
                    transactionTotalElement.innerHTML = "Failed to load";
                    orderTotalElement.innerHTML = "Failed to load";
                }
            } catch (error) {
                console.error("Error fetching balance:", error);
                paymentTotalElement.innerHTML = "Error";
                transactionTotalElement.innerHTML = "Error";
                orderTotalElement.innerHTML = "Error";
            }
        }
        // fetch bulk sms balance
        async function fetchBulkSMSNigeriaBalance() {
            try {
                // Show spinner
                bulkSMSNigeriaElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                // Make the API call
                let response = await fetch("/admin/info/bulksmsnigeria", {
                    method: "GET",
                });

                let data = await response.json();
                
                if (data.status) {
                    // Format balance with commas
                    let formattedBalance = new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN' }).format(data.balance);
                    
                    // Replace spinner with balance
                    bulkSMSNigeriaElement.innerHTML = formattedBalance;
                } else {
                    bulkSMSNigeriaElement.innerHTML = "Failed to load";
                }
            } catch (error) {
                console.error("Error fetching balance:", error);
                bulkSMSNigeriaElement.innerHTML = "Error";
            }
        }
        // fetch monnify settlement balance
        async function fetchMonnifyBalance() {
            try {
                // Show spinner
                monnifyElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                // Make the API call
                let response = await fetch("/admin/info/monnify", {
                    method: "GET",
                });

                let data = await response.json();
                
                if (data.status) {
                    // Format balance with commas
                    let formattedBalance = new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN' }).format(data.balance);
                    
                    // Replace spinner with balance
                    monnifyElement.innerHTML = formattedBalance;
                } else {
                    monnifyElement.innerHTML = "Failed to load";
                }
            } catch (error) {
                console.error("Error fetching balance:", error);
                monnifyElement.innerHTML = "Error";
            }
        }
        // fetch alrahuzdata settlement balance
        async function fetchAlrahuzDataBalance() {
            try {
                // Show spinner
                alrahuzdataElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                // Make the API call
                let response = await fetch("/admin/info/alrahuzdata", {
                    method: "GET",
                });

                let data = await response.json();
                
                if (data.status) {
                    // Format balance with commas
                    let formattedBalance = new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN' }).format(data.balance);
                    
                    // Replace spinner with balance
                    alrahuzdataElement.innerHTML = formattedBalance;
                } else {
                    alrahuzdataElement.innerHTML = "Failed to load";
                }
            } catch (error) {
                console.error("Error fetching balance:", error);
                alrahuzdataElement.innerHTML = "Error";
            }
        }
        // fetch alrahuzdata settlement balance
        async function fetchEasyAccessBalance() {
            try {
                // Show spinner
                easyaccessElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                // Make the API call
                let response = await fetch("/admin/info/easyaccess", {
                    method: "GET",
                });

                let data = await response.json();
                
                if (data.status) {
                    // Format balance with commas
                    let formattedBalance = new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN' }).format(data.balance);
                    
                    // Replace spinner with balance
                    easyaccessElement.innerHTML = formattedBalance;
                } else {
                    easyaccessElement.innerHTML = "Failed to load";
                }
            } catch (error) {
                console.error("Error fetching balance:", error);
                easyaccessElement.innerHTML = "Error";
            }
        }

        fetchBulkSMSNigeriaBalance();
        fetchMonnifyBalance();
        fetchAlrahuzDataBalance();
        fetchEasyAccessBalance();
        fetchInternalTotal();
    });
</script>
</body>
</html>

