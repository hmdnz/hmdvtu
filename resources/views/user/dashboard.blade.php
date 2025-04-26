
<!doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="ZaumaData, Data, Airtime, bulk sms, cable subscription" />
    <meta name="developer" content="Shahuci Global Resources" />
    <meta name="website" content="www.sgr.com.ng" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Home | ZaumaData</title>
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
        
  @php( $page = 'index')
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
                                  <h1>Welcome {{ auth()->user()->firstName }}</h1>
                                  <p>Welcome to ZaumaData. We are happy to have you here</p>
                              </div>
                              <div>
                                  <!-- <a href="settings" class="btn btn-link btn-soft-light">
                                      <i class="fa fa-gear"></i>
                                      Settings
                                  </a> -->
                              </div>
                          </div>
                          <!-- <div class="col-12 text-danger ">
                              <marquee id="marquee"></marquee>
                          </div> -->
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
                  <div class="col-md-4 col-6">
                    <a href="/user/wallet" class="card rounded-2 p-0">
                      <div class="card-body p-2 py-4">
                            <div class="row">
                              <div class="col-md-4 text-center">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fas fa-piggy-bank fa-stack-1x fa-inverse"></i>
                                </span>
                              </div>
                              <div class="col-md-8 text-center">
                                  <p class="text-muted text-center">Main Wallet</p>
                                  <h5 class="card-title">&#8358; <span class="wallet-balance">{{auth()->user()->wallet->mainBalance}}</span></h5>
                              </div>
                          </div>
                      </div>
                    </a>
                  </div>
                  <!-- info card -->
                  <div class="col-md-4 col-6">
                    <a href="/user/wallet" class="card rounded-2 p-0">
                      <div class="card-body p-2 py-4">
                            <div class="row">
                              <div class="col-md-4 text-center">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fas fa-piggy-bank fa-stack-1x fa-inverse"></i>
                                </span>
                              </div>
                              <div class="col-md-8 text-center">
                                  <p class="text-muted text-center">Referral Wallet</p>
                                  <h5 class="card-title">&#8358; <span class="wallet-balance">{{auth()->user()->wallet->referralBalance}}</span></h5>
                              </div>
                          </div>
                      </div>
                    </a>
                  </div>
                  <!-- info card -->
                  <div class="col-md-4 col-6">
                    <a href="/user/wallet-topup" class="card rounded-2 p-0">
                      <div class="card-body p-2 py-4">
                            <div class="row">
                              <div class="col-md-4 text-center">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fas fa-credit-card fa-stack-1x fa-inverse"></i>
                                </span>
                              </div>
                              <div class="col-md-8 text-center">
                                  <p class="text-muted">Wallet</p>
                                  <h5 class=" card-title">Fund Wallet</h5>
                              </div>
                          </div>
                      </div>
                    </a>
                  </div>
                  <!-- info card -->
                  <div class="col-md-3 col-6">
                    <a href="/user/buy-airtime" class="card rounded-2 p-0">
                      <div class="card-body p-2 py-4">
                            <div class="row">
                              <div class="col-md-4 text-center">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fas fa-phone fa-stack-1x fa-inverse"></i>
                                </span>
                              </div>
                              <div class="col-md-8 text-center">
                                  <p class="text-muted">Airtime</p>
                                  <h5 class="card-title">Buy Airtime</h5>
                              </div>
                          </div>
                      </div>
                    </a>
                  </div>
                  <!-- info card -->
                  <div class="col-md-3 col-6">
                    <a href="/user/buy-data" class="card rounded-2 p-0">
                      <div class="card-body p-2 py-4">
                            <div class="row">
                              <div class="col-md-4 text-center">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fas fa-wifi fa-stack-1x fa-inverse"></i>
                                </span>
                              </div>
                              <div class="col-md-8 text-center">
                                  <p class="text-muted">Data</p>
                                  <h5 class=" card-title">Buy Data</h5>
                              </div>
                          </div>
                      </div>
                    </a>
                  </div>
                  <!-- info card -->
                  <div class="col-md-3 col-6">
                    <a href="/user/buy-cable" class="card rounded-2 p-0">
                      <div class="card-body p-2 py-4">
                            <div class="row">
                              <div class="col-md-4 text-center">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fas fa-wifi fa-stack-1x fa-inverse"></i>
                                </span>
                              </div>
                              <div class="col-md-8 text-center">
                                  <p class="text-muted">Cable</p>
                                  <h5 class=" card-title">Buy Cable</h5>
                              </div>
                          </div>
                      </div>
                    </a>
                  </div>
                  <!-- info card -->
                  <div class="col-md-3 col-6">
                    <a href="/user/buy-sms" class="card rounded-2 p-0">
                      <div class="card-body p-2 py-4">
                            <div class="row">
                              <div class="col-md-4 text-center">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fas fa-comments fa-stack-1x fa-inverse"></i>
                                </span>
                              </div>
                              <div class="col-md-8 text-center">
                                  <p class="text-muted">Bulk SMS</p>
                                  <h5 class=" card-title">Send SMS</h5>
                              </div>
                          </div>
                      </div>
                    </a>
                  </div>
                  <!-- info card -->
                  {{-- <div class="col-md-4 col-6">
                    <a href="buy-electricity" class="card rounded-2 p-0">
                      <div class="card-body p-2 py-4">
                            <div class="row">
                              <div class="col-md-4 text-center">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fas fa-plug fa-stack-1x fa-inverse"></i>
                                </span>
                              </div>
                              <div class="col-md-8 text-center">
                                  <p class="text-muted">Electricity</p>
                                  <h5 class=" card-title">Buy Token</h5>
                              </div>
                          </div>
                      </div>
                    </a>
                  </div>
                  <!-- info card -->
                  <div class="col-md-4 col-6">
                    <a href="buy-cable" class="card rounded-2 p-0">
                      <div class="card-body p-2 py-4">
                            <div class="row">
                              <div class="col-md-4 text-center">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fas fa-plug fa-stack-1x fa-inverse"></i>
                                </span>
                              </div>
                              <div class="col-md-8 text-center">
                                  <p class="text-muted">Cable</p>
                                  <h5 class="card-title">Buy Token</h5>
                              </div>
                          </div>
                      </div>
                    </a>
                  </div>
                  <!-- info card -->
                  <div class="col-md-4 col-6">
                    <a href="buy-education" class="card rounded-2 p-0">
                      <div class="card-body p-2 py-4">
                            <div class="row">
                              <div class="col-md-4 text-center">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fas fa-plug fa-stack-1x fa-inverse"></i>
                                </span>
                              </div>
                              <div class="col-md-8 text-center">
                                  <p class="text-muted">Education Pin</p>
                                  <h5 class=" card-title">Buy Token</h5>
                              </div>
                          </div>
                      </div>
                    </a>
                  </div>
                  <!-- info card -->
                  <div class="col-md-4 col-6">
                    <a href="airtime-cash" class="card rounded-2 p-0">
                      <div class="card-body p-2 py-4">
                            <div class="row">
                              <div class="col-md-4 text-center">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fas fa-sim-card fa-stack-1x fa-inverse"></i>
                                </span>
                              </div>
                              <div class="col-md-8 text-center">
                                  <p class="text-muted">Airtime-Cash</p>
                                  <h5 class=" card-title">Convert</h5>
                              </div>
                          </div>
                      </div>
                    </a>
                  </div>
                  <!-- info card -->
                  <div class="col-md-4 col-6">
                    <a href="referrals" class="card rounded-2 p-0">
                      <div class="card-body p-2 py-4">
                            <div class="row">
                              <div class="col-md-4 text-center">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fas fa-share fa-stack-1x fa-inverse"></i>
                                </span>
                              </div>
                              <div class="col-md-8 text-center">
                                  <p class="text-muted">Referrals</p>
                                  <h5 class="card-title"><span id="totalReferralsSpan">0</span></h5>
                              </div>
                          </div>
                      </div>
                    </a>
                  </div>
                  <!-- info card -->
                  <div class="col-md-4 col-6">
                    <a href="referrals" class="card rounded-2 p-0">
                      <div class="card-body p-2 py-4">
                          <div class="container">
                            <div class="row">
                              <div class="col-md-4 text-center">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fas fa-money-bill-1-wave fa-stack-1x fa-inverse"></i>
                                </span>
                              </div>
                              <div class="col-md-8 text-center">
                                  <p class="text-muted">Commision</p>
                                  <h5 class=" card-title">&#8358; <span id="referralBonus">0</span></h5>
                              </div>
                            </div>
                          </div>
                      </div>
                    </a>
                  </div> --}}
                  
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
</body>
</html>
