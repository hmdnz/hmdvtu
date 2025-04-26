
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Wallets | ZaumaData</title>

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

  @php( $page = 'wallets')
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
                                <h1>Users' Wallets!</h1>
                          
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
            <div class="col-sm-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between">
                  <div class="header-title">
                    <h4 class="card-title">All Wallets ({{$walletCounts}})</h4>
                  </div>
                </div>
    
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                      <thead>
                        <tr>
                          <th>S/N</th>
                          <th>User</th>
                          <th>Wallet</th>
                          <th>Balance</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="users-table-body">
                          @php($count = 1)
                          @foreach($wallets as $wallet)
                              <tr>
                                  <td>{{$count}}</td>
                                  <td>{{isset($wallet->userID)?$wallet->user->username:''}}</td>
                                  <td>{{$wallet->identifier}}</td>
                                  <td>{{formatPrice($wallet->mainBalance, '₦', '2')}}</td>
                                  <td>
                                      @if($wallet->status == 'Active')
                                          <span class="badge badge-success bg-success">Active</span>  
                                      @else
                                          <span class="badge badge-danger bg-danger">Inactive</span>  
                                      @endif  
                                  </td>
                                  <td>
                                      <a href="{{ route('admin.walletPayments', [$wallet->id])}}" class="btn btn-primary px-2" >
                                        <i class="fa fa-piggy-bank small"></i>
                                      </a>
                                      <a href="{{ route('admin.walletTransactions', [$wallet->id])}}" class="btn btn-primary px-2" >
                                        <i class="fa fa-table small"></i>
                                      </a>
                                      <button id="fundWalletBtn" onclick="addFundToWallet({{$wallet->id}})" class="btn btn-success btn-sm">Fund</button> 
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
      <!-- fund wallet modal -->
      <div class="modal" id="fundWalletModalBox">
        <div class="modal-dialog">
          <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
              <h2>Top Up Wallet</h2>
              <form action="{{route('admin.walletTopUp')}}" method="post">
                @csrf
                <label for="amount">Admin Email:</label>
                <input type="email" name="email" class="form-control" required/>
                <label for="amount">Admin Password:</label>
                <input type="password" name="adminPassword" class="form-control" required/>
                <input type="hidden" id="id" name="adminId" value="{{ auth()->user()->id}}" class="form-control" />
                <input type="hidden" id="walletId" name="id"  class="form-control" />
                <label for="amount">Amount:</label>
                <input type="text" id="amount" name="amount" pattern="[0-9]+(\.[0-9]+)?" step="any" class="form-control" />
                <div class="mt-2">
                  <button type="button" class="btn secondary-btn" data-dismiss="modal" onclick="closeModal('fundWalletModalBox')">Close</button>
                  <button type="submit" class="btn primary-btn">Top Up</button>
              </div>
              </form>
          </div>
        </div>
      </div>
  </main>

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
    
    
    function addFundToWallet(walletId) {
      // Open the modal box
      $('#fundWalletModalBox').modal('show');
      var theWalletId = document.getElementById("walletId");

      theWalletId.value = walletId;
    }
    function editAdmin(id, firstName, lastName, username, role, email, phone, status) {
      // set the values in the modal
      document.getElementById('editAdminId').value = id;
      document.getElementById('editFirstName').value = firstName;
      document.getElementById('editLastName').value = lastName;
      document.getElementById('editUsername').value = username;
      document.getElementById('editRoleOption').label = role;
      document.getElementById('editEmail').value = email;
      document.getElementById('editPhone').value = phone;
      document.getElementById('editStatusOption').label = status;
        // alert(status);
      // open the modal
      const modal = new bootstrap.Modal(document.getElementById('editAdminModal'));
        modal.show();
    }
  </script>
</body>
</html>


