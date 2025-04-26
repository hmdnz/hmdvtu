
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Virtual Accounts | ZaumaData</title>

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

    @php( $page = 'virtual accounts')
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
                                <h1>Virtual Accounts</h1>
                            
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
            <div class="col-md-12 col-lg-12">
                <div class="row">
                    <!-- info card -->
                    <div class="col-md-4">
                        <a href="{{route('admin.users')}}" class="card rounded-2 p-0">
                            <div class="card-body p-2 py-4">
                                <div class="container p-0 row">
                                    <div class="col-4">
                                        <span class="fa-stack fa-2x">
                                            <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                            <i class="fas fa-piggy-bank fa-stack-1x fa-inverse"></i>
                                        </span>
                                    </div>
                                    <div class="col-8 ps-4 text-start">
                                        <p class="text-muted mt-1 mb-0">All </p>
                                        <h5 class="card-title"><span id="users-counts">{{$totalAccounts}}</span></h5>
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
                                            <i class="fas fa-piggy-bank fa-stack-1x fa-inverse"></i>
                                        </span>
                                    </div>
                                    <div class="col-8 ps-4 text-start">
                                        <p class="text-muted mt-1 mb-0">Active Accounts</p>
                                        <h5 class=" card-title"><span id="payments-counts">{{$activeAccounts}}</span></h5>
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
                                            <i class="fas fa-piggy-bank fa-stack-1x fa-inverse"></i>
                                        </span>
                                    </div>
                                    <div class="col-8 ps-4 text-start">
                                        <p class="text-muted mt-1 mb-0">Inactive Account</p>
                                        <h5 class=" card-title"><span id="orders-counts">{{$inactiveAccounts}}</span></h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
            <div class="col-sm-12">
                <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                    <h4 class="card-title">All Wallets </h4>
                    </div>
                </div>
    
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>User</th>
                            <th>Provider</th>
                            <th>Account Name</th>
                            <th>Account Number</th>
                            <th>Bank</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="users-table-body">
                            @php($count = 1)
                            @foreach($accounts as $account)
                                <tr>
                                    <td>{{$count}}</td>
                                    <td>{{isset($account->userID)?$account->user->username:''}}</td>
                                    <td>{{$account->provider}}</td>
                                    <td>{{$account->accountName}}</td>
                                    <td>{{$account->accountNumber}}</td>
                                    <td>{{$account->accountBank}}</td>
                                    <td>
                                        @if($account->status == 'Active')
                                            <span class="badge badge-success bg-success">Active</span>  
                                        @else
                                            <span class="badge badge-danger bg-danger">Inactive</span>  
                                        @endif  
                                    </td>
                                    <td>{{$account->created_at}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton{{ $account->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $account->id }}">
                                                @if($account->status == 'Active')
                                                <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deactivateAccountModal" 
                                                    data-id="{{ $account->id }}" 
                                                    data-provider="{{ $account->provider }}"
                                                    data-name="{{ $account->accountName }}"
                                                    data-number="{{ $account->accountNumber }}"
                                                    data-bank="{{ $account->accountBank }}"
                                                    data-status="{{ $account->status }}"  
                                                >
                                                    <i class="fa fa-arrow-down"></i> Deactivate
                                                </a>
                                                </li>
                                                @else
                                                <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#activateAccountModal" 
                                                    data-id="{{ $account->id }}" 
                                                    data-provider="{{ $account->provider }}"
                                                    data-name="{{ $account->accountName }}"
                                                    data-number="{{ $account->accountNumber }}"
                                                    data-bank="{{ $account->accountBank }}"
                                                    data-status="{{ $account->status }}"
                                                    >
                                                    <i class="fa fa-arrow-up"></i> Activate
                                                </a>
                                                </li>
                                                @endif
                                                {{-- <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteAccountModal" 
                                                        data-id="{{ $account->id }}" data-provider="{{ $account->provider }}">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </a>
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @php($count++)
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        @include('components.admin.footer')
        
    </main>

    @include('admin.wallets.partials.activateAccountModal')
    @include('admin.wallets.partials.inactivateAccountModal')


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
    
    document.addEventListener("DOMContentLoaded", function() {
        var activateModal = document.getElementById('activateAccountModal');

        // When modal is shown, 
        activateModal.addEventListener('show.bs.modal', function(event) {
        console.log("Modal is opening");
            var button = event.relatedTarget;  // Button that triggered the modal
            var accountID = button.getAttribute('data-id');
            var accountProvider = button.getAttribute('data-provider');
            var accountName = button.getAttribute('data-name');
            var accountNumber = button.getAttribute('data-number');
            var accountBank = button.getAttribute('data-bank');
            var accountStatus = button.getAttribute('data-status');

            document.getElementById('activateAccountName').textContent = accountName;
            document.getElementById('activateAccountNumber').textContent = accountNumber;
            document.getElementById('activateAccountBank').textContent = accountBank;
            document.getElementById('activateAccountProvider').textContent = accountProvider;
            document.getElementById('activateAccountStatus').textContent = accountStatus;
            document.getElementById('activateButton').setAttribute('data-id', accountID);
        });

        // Handle API Request
        document.getElementById('activateButton').addEventListener('click', function() {
            var button = this;
            var icon = document.getElementById('activateIcon');
            var text = document.getElementById('activateText');
            var id = this.getAttribute('data-id');
            // Change button to loading state
            button.disabled = true;
            icon.classList.remove('fa-sync');
            icon.classList.add('fa-spinner', 'fa-spin'); // spinning effect
            text.textContent = "Activating...";

            // Make API Request
            fetch("{{ route('admin.virtualAccounts.activate') }}", {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                document.getElementById('activateAccountStatus').textContent = data.code;
                document.getElementById('responseMessage').textContent = data.message;
                } else {
                document.getElementById('activateAccountStatus').textContent = data.code;
                document.getElementById('responseMessage').textContent = data.message;
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                // Restore button state
                button.disabled = false;
                icon.classList.remove('fa-spinner', 'fa-spin');
                icon.classList.add('fa-sync');
                text.textContent = "Activate";
                // Reload page after 3 seconds
                setTimeout(() => {
                    location.reload();
                }, 3000);
            });
        });

        var deactivateModal = document.getElementById('deactivateAccountModal');

        // When modal is shown, 
        deactivateModal.addEventListener('show.bs.modal', function(event) {
        console.log("Modal is opening");
            var button = event.relatedTarget;  // Button that triggered the modal
            var accountID = button.getAttribute('data-id');
            var accountProvider = button.getAttribute('data-provider');
            var accountName = button.getAttribute('data-name');
            var accountNumber = button.getAttribute('data-number');
            var accountBank = button.getAttribute('data-bank');
            var accountStatus = button.getAttribute('data-status');

            document.getElementById('deactivateAccountName').textContent = accountName;
            document.getElementById('deactivateAccountNumber').textContent = accountNumber;
            document.getElementById('deactivateAccountBank').textContent = accountBank;
            document.getElementById('deactivateAccountProvider').textContent = accountProvider;
            document.getElementById('deactivateAccountStatus').textContent = accountStatus;
            document.getElementById('deactivateButton').setAttribute('data-id', accountID);
        });

        // Handle API Request
        document.getElementById('deactivateButton').addEventListener('click', function() {
            var button = this;
            var icon = document.getElementById('deactivateIcon');
            var text = document.getElementById('deactivateText');
            var id = this.getAttribute('data-id');
            // Change button to loading state
            button.disabled = true;
            icon.classList.remove('fa-sync');
            icon.classList.add('fa-spinner', 'fa-spin'); // spinning effect
            text.textContent = "Deactivating...";

            // Make API Request
            fetch("{{ route('admin.virtualAccounts.deactivate') }}", {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                document.getElementById('deactivateAccountStatus').textContent = data.code;
                document.getElementById('responseMessage').textContent = data.message;
                } else {
                document.getElementById('deactivateAccountStatus').textContent = data.code;
                document.getElementById('responseMessage').textContent = data.message;
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                // Restore button state
                button.disabled = false;
                icon.classList.remove('fa-spinner', 'fa-spin');
                icon.classList.add('fa-sync');
                text.textContent = "Deactivate";
                // Reload page after 3 seconds
                setTimeout(() => {
                    location.reload();
                }, 3000);
            });
        });

        
    });
  </script>
</body>
</html>

