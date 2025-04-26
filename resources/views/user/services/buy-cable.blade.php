
<!doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="ZaumaData, Data, Airtime, bulk sms, cable subscription" />
    <meta name="developer" content="Shahuci Global Resources" />
    <meta name="website" content="www.sgr.com.ng" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Buy Cable | ZaumaData</title>
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
    @php( $page = 'buy-cable')
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
                                    <h1>Buy Cable Subscription </h1>
                                    <p>We have the cheapest data rate</p>
                                </div>
                                <div>
                                    <a href="{{route('user.walletTopUp')}}" class="btn btn-link secondary-btn">
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
                <div class="col-md-9 mx-auto">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Cable Subscription</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <form id="cable-form" action="{{route('user.cable')}}" method="post">
                                    @csrf
                                    <input type="" name="userID" class="form-control" value="{{ auth()->user()->id }}" required>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="form-label" for="fname">Select Cable TV Company</label>
                                            <select class="form-control" name="billerID" id="company" required>
                                                <option selected value="">Choose..</option>
                                                @foreach($billers as $biller)
                                                <option value="{{$biller->id}}">{{$biller->title}}</option>
                                                @endforeach
                                            </select>
                                            @error('billerID')
                                            <span class="text-danger small">{{ $message}}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="form-label" for="fname">Meter Type</label>
                                            <select class="form-control" name="meterType" id="type" required>
                                                <option selected value="">Choose..</option>
                                                <option>Prepaid</option>
                                                <option>postpaid</option>
                                            </select>
                                            @error('meterType')
                                            <span class="text-danger small">{{ $message}}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="form-label" for="fname">SmartCard/IUC Number</label>
                                            <input type="text" class="form-control" name="meterNumber" id="iuc" required/>
                                            {{-- <span class="text-success" id="customerName" style="display: none;"></span>
                                            <span class="text-danger" id="iucError" style="display: none;"></span> --}}
                                            @error('meterNumber')
                                            <span class="text-danger small">{{ $message}}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="name" class="form-label">Customer Name</label>
                                            <input type="text" class="form-control" name="customerName" placeholder="Customer Name" id="name" readonly>
                                            @error('customerName')
                                                <span class="text-danger small">{{ $message}}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-120" id="packageContainer">
                                            <label class="form-label" for="fname">Choose a Package</label>
                                            <select class="form-control" name="packageID" id="newPackage">
                                                <option selected value="">Choose..</option>
                                            </select>
                                            @error('packageID')
                                            <span class="text-danger small">{{ $message}}</span>
                                            @enderror
                                        </div>  
                                        <div class="form-group col-md-12">
                                            <label class="form-label" for="pin">Pin</label><br>
                                            <span class="text-danger" id="pin-warning" style="display: none;">You need to create pin before you proceed, click <a href='./profile'>here</a> to create one.</span>
                                            <input type="password" class="form-control" maxlength="4" name="pin" id="pin" required />
                                            @error('pin')
                                            <span class="text-danger small">{{ $message}}</span>
                                            @enderror
                                            <input type="text" name="packageName" class="form-control" id="packageName">                   
                                            <input type="text" name="total" class="form-control" id="total">                
                                            <input type="text" name="planID" class="form-control" id="plan">
                                        </div>           
                                    </div>
                                    <button type="submit"  id="buy-cable-button" class="btn secondary-btn col-12"><span id='buyCableButtonpaySpan'></span> Generate Token </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('components.user.footer')
    </main>
    
    
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
              // console.log(response.body);
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

  @if($page == 'index')
      fetchAnnouncements();
  @endif
</script>
{{-- cable js --}}
<script src="{{ asset('app/assets/js/app/cable.js') }}"></script>
</body>
</html>
