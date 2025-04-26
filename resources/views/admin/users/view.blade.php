
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>View User | ZaumaData</title>

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

    @php( $page = 'users')
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
                                <h1>View {{$user->username}}</h1>
                            
                                </div>
                                <div>
                                {{-- @if($user->isVerified !== 4)
                                    <a type="button" data-bs-toggle="modal" data-bs-target="#verifyUserModal" class="btn btn-link btn-soft-light">
                                        Verify User
                                    </a>
                                @endif --}}
                                    <a type="button" data-bs-toggle="modal" data-bs-target="#passwordUserModal" class="btn btn-link btn-soft-light">
                                        <i class="fas fa-undo"></i> Password
                                    </a>
                                    <a type="button" data-bs-toggle="modal" data-bs-target="#pinUserModal" class="btn btn-link btn-soft-light">
                                        <i class="fas fa-undo"></i> Pin
                                    </a>
                                    <a type="button" href="{{route('admin.users.logs',[$user->id])}}" class="btn btn-link btn-soft-light">
                                        <i class="fa fa-table"></i> Logs
                                    </a>
                                    <a type="button" data-bs-toggle="modal" data-bs-target="#deleteUserModal" class="btn btn-link btn-soft-light">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>
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
                <div class="col-11 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            User Information
                        </div>
                        <div class="card-body">
                            <nav>
                                <div class="mb-3 nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active d-flex align-items-center" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Profile</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-wallet" type="button" role="tab" aria-controls="nav-wallet" aria-selected="false">Wallet</button>
                                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-transactions" type="button" role="tab" aria-controls="nav-transactions" aria-selected="false">Transactions</button>
                                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-payments" type="button" role="tab" aria-controls="nav-payments" aria-selected="false">Payments</button>
                                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-logs" type="button" role="tab" aria-controls="nav-logs" aria-selected="false">Logs</button>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <h4 class="my-3">User Profile</h4>
                                    <div class="row text-dark">
                                        <div class="col-md-12">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>Name : </b> {{$user->firstName ." ". $user->lastName}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-12">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>Username : </b> {{$user->username}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>Date of Birth : </b> {{$user->dob}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>Gender : </b> {{$user->gender}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>BVN : </b> {{$user->bvn}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>NIN : </b> {{$user->nin}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-5">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>Phone : </b> {{$user->phone}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-7">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>Email : </b> {{$user->email}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>LGA : </b> {{$user->lga}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>State : </b> {{$user->state}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-12">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>Address : </b> {{$user->address}}
                                                </li>
                                            </ol>
                                        </div>
                                        <hr>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>IsVerified : </b> 
                                                    @if($user->isVerified == 1)
                                                        <span class="badge badge-success bg-dark">Verified</span> 
                                                    @else
                                                        <span class="badge badge-danger bg-danger">Not Verified</span>  
                                                    @endif 
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>BVN Verified : </b> 
                                                    @if($user->isVerified == 1)
                                                        <span class="badge badge-success bg-dark">Verified</span> 
                                                    @else
                                                        <span class="badge badge-danger bg-danger">Not Verified</span>  
                                                    @endif 
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-12">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>Official Name : </b> 
                                                    {{$user->verifiedName}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-12">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>Account Name : </b> 
                                                    {{$user->accountName}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>Bank Code : </b> 
                                                    {{$user->bankCode}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>Status : </b> 
                                                    @if($user->status == 'Active')
                                                        <span class="badge badge-success bg-success">Active</span>  
                                                    @else
                                                        <span class="badge badge-danger bg-danger">Inactive</span>  
                                                    @endif
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-12">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>Created At : </b> {{$user->created_at}}
                                                </li>
                                            </ol>
                                        </div>
                    
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="nav-wallet" role="tabpanel" aria-labelledby="nav-wallet-tab">
                                    <h4 class="my-3">User Wallet</h4>
                                    <div class="row text-dark">
                                        <div class="col-md-12">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>WalletID : </b> {{$user->wallet->identifier}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>Main Balance : </b> {{$user->wallet->mainBalance}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>Referral Balance : </b> {{formatPrice($user->wallet->referralBalance, '₦', '2')}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>All Time Orders Count : </b> {{getTotalOrdersCount($user->id)}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>All Time Orders Value : </b> {{formatPrice(getTotalOrdersValue($user->id), '₦', '2')}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>All Time Payments Count : </b> {{getTotalPaymentsCount($user->id)}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>All Time Payments Value : </b> {{formatPrice(getTotalPaymentsValue($user->id), '₦', '2')}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>All Time Transactions Count : </b> {{getTotalTransactionsCount($user->id)}}
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb bg-light p-2 rounded">
                                                <li class="breadcrumb-item">
                                                <b>All Time Transactions Value : </b> {{formatPrice(getTotalTransactionsValue($user->id), '₦', '2')}}
                                                </li>
                                            </ol>
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-transactions" role="tabpanel" aria-labelledby="nav-transactions-tab">
                                    <h4 class="my-3">User Transactions</h4>
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-striped" data-toggle="data-table">
                                        <thead>
                                            <tr>
                                            <th>S/N</th>
                                            <th>Type</th>
                                            <th>Balance Before</th>
                                            <th>Amount</th>
                                            <th>Balance After</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php($count = 1)
                                        @foreach($user->transactions->take(10) as $transaction)
                                        <tr>
                                            <td>{{$count}}</td>
                                            <td>{{$transaction->type}}</td>
                                            <td>&#8358; {{formatPrice($transaction->balanceBefore, '₦', '2')}}</td>
                                            <td>&#8358; {{formatPrice($transaction->amount, '₦', '2')}}</td>
                                            <td>&#8358; {{formatPrice($transaction->balanceAfter, '₦', '2')}}</td>
                                            <td>{{$transaction->status}}</td>
                                            <td>
                                                <a href="{{route('admin.showTransaction',[$transaction->id])}}" class="btn primary-btn"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                        @php($count++)
                                        @endforeach
                                        </tbody>
                                        </table>
                                    </div>
                                    <center>
                                        <a href="{{route('admin.walletTransactions', [$user->wallet->id])}}" class="btn primary-btn">View More</a>
                                    </center>
                                </div>
                                <div class="tab-pane fade" id="nav-payments" role="tabpanel" aria-labelledby="nav-payments-tab">
                                    <h4 class="my-3">User Payments</h4>
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-striped" data-toggle="data-table">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Channel</th>
                                                <th>Reference</th>
                                                <th>Balance Before</th>
                                                <th>Amount</th>
                                                <th>Balance After</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($count = 1)
                                            @foreach($user->payments->take(10) as $payment)
                                            <tr>
                                            <td>{{$count}}</td>
                                            <td>{{$payment->channel}}</td>
                                            <td>{{$payment->reference}}</td>
                                            <td>&#8358; {{formatPrice($payment->balanceBefore, '₦', '2')}}</td>
                                            <td>&#8358; {{formatPrice($payment->amount, '₦', '2')}}</td>
                                            <td>&#8358; {{formatPrice($payment->balanceAfter, '₦', '2')}}</td>
                                            <td>{{$payment->status}}</td>
                                            <td>
                                                <a href="{{route('admin.showPayment',[$payment->id])}}" class="btn primary-btn"><i class="fa fa-eye"></i></a>
                                            </td>
                                            </tr>
                                            @php($count++)
                                            @endforeach
                                        </tbody>
                                        </table>
                                    </div>
                                    <center>
                                        <a href="{{route('admin.walletPayments', [$user->wallet->id])}}" class="btn primary-btn">View More</a>
                                    </center>
                                </div>
                                <div class="tab-pane fade" id="nav-logs" role="tabpanel" aria-labelledby="nav-logs-tab">
                                    <h4 class="my-3">User Logs</h4>
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-striped" data-toggle="data-table">
                                        <thead>
                                            <tr>
                                            <th>S/N</th>
                                            <th>Username</th>
                                            <th>IPAddress</th>
                                            <th>Status</th>
                                            <th>Created On</th>
                                            <th>Updated On</th>
                                            </tr>
                                        </thead>
                                        <tbody id="users-table-body">
                                            @php($count = 1)
                                            @foreach($user->userLogs->take(10) as $log)
                                                <tr>
                                                    <td>{{$count}}</td>
                                                    <td>{{$log->username}}</td>
                                                    <td>{{$log->IPAddress}}</td>
                                                    <td>
                                                        {{$log->status}}  
                                                    </td>
                                                    <td>{{$log->created_at}}</td>
                                                    <td>{{$log->updated_at}}</td>
                                                </tr>
                                                @php($count++)
                                            @endforeach
                                        </tbody>
                                        </table>
                                    </div>
                                    <center>
                                        <a href="{{route('admin.users.logs', [$user->id])}}" class="btn primary-btn">View More</a>
                                    </center>
                                </div>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('components.admin.footer')
    </main>

    <!-- verify user modal -->
    <div class="modal" id="verifyUserModal">
        <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <h2>Verify User</h2>
                <p>Make sure you know this user before you verify him/her.</p>
                <form action="{{route('admin.users.verify', [$user->id])}}" method="post">
                    @csrf
                    <label for="amount">Admin Email:</label>
                    <input type="email" name="email" class="form-control" required/>
                    <label for="amount">Admin Password:</label>
                    <input type="password" name="password" class="form-control" required/>
                    <input type="hidden" id="id" name="adminId" value="{{ auth()->user()->id}}" class="form-control" />
                    <div class="mt-2">
                        <button type="button" class="btn secondary-btn" data-bs-dismiss="modal" onclick="closeModal('passwordUserModal')">Close</button>
                        <button type="submit" class="btn primary-btn">Verify</button>
                    </div>
                </form>
            </div>

        </div>
        </div>
    </div>
    <!-- reset user password modal -->
    <div class="modal" id="passwordUserModal">
        <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <h2>Reset User Pin</h2>
                <form action="{{route('admin.users.resetPassword', [$user->id])}}" method="post">
                    @csrf
                    <label for="amount">Admin Email:</label>
                    <input type="email" name="email" class="form-control" required/>
                    <label for="amount">Admin Password:</label>
                    <input type="password" name="adminPassword" class="form-control" required/>
                    <input type="hidden" id="id" name="adminId" value="{{ auth()->user()->id}}" class="form-control" />
                    <label for="amount">User's New Password:</label>
                    <input type="password" name="password" class="form-control" required/>
                    <label for="amount">Confirm New Password:</label>
                    <input type="password" name="password_confirmation" class="form-control" required/>
                    <div class="mt-2">
                        <button type="button" class="btn secondary-btn" data-bs-dismiss="modal" onclick="closeModal('pinUserModal')">Close</button>
                        <button type="submit" class="btn primary-btn">Reset</button>
                    </div>
                </form>
            </div>

        </div>
        </div>
    </div>
    <!-- reset user pin modal -->
    <div class="modal" id="pinUserModal">
        <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <h2>Reset User Pin</h2>
                <form action="{{route('admin.users.resetPin', [$user->id])}}" method="post">
                    @csrf
                    <label for="amount">Admin Email:</label>
                    <input type="email" name="email" class="form-control" required/>
                    <label for="amount">Admin Password:</label>
                    <input type="password" name="adminPassword" class="form-control" required/>
                    <input type="hidden" id="id" name="adminId" value="{{ auth()->user()->id}}" class="form-control" />
                    <label for="amount">User's New Pin:</label>
                    <input type="password" name="pin" class="form-control" required/>
                    <label for="amount">Confirm New Pin:</label>
                    <input type="password" name="pin_confirmation" class="form-control" required/>
                    <div class="mt-2">
                        <button type="button" class="btn secondary-btn" data-bs-dismiss="modal" onclick="closeModal('pinUserModal')">Close</button>
                        <button type="submit" class="btn primary-btn">Reset</button>
                    </div>
                </form>
            </div>

        </div>
        </div>
    </div>
    {{-- delete user --}}
    <div class="modal" id="deleteUserModal">
        <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <h2>Reset User Pin</h2>
                <form action="{{route('admin.users.delete', [$user->id])}}" method="post">
                    @csrf
                    <label for="amount">Admin Email:</label>
                    <input type="email" name="email" class="form-control" required/>
                    <label for="amount">Admin Password:</label>
                    <input type="password" name="adminPassword" class="form-control" required/>
                    <input type="hidden" id="id" name="adminId" value="{{ auth()->user()->id}}" class="form-control" />
                    <div class="mt-2">
                        <button type="button" class="btn secondary-btn" data-bs-dismiss="modal" onclick="closeModal('deleteUserModal')">Close</button>
                        <button type="submit" class="btn primary-btn">Delete</button>
                    </div>
                </form>
            </div>

        </div>
        </div>
    </div>


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
    
  </script>
</body>
</html>


