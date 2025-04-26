
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Referrals | ZaumaData</title>

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

  @php( $page = 'referrals')
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
                                <h1>Referrals</h1>
                                  <p>Referral History</p>
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
              <!-- info card -->
              <div class="col-md-3 col-6">
                <a href="#" class="card rounded-2 p-0">
                  <div class="card-body p-2 py-4">
                        <div class="row">
                          <div class="col-md-4 text-center">
                            <span class="fa-stack fa-2x">
                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                <i class="fas fa-share fa-stack-1x fa-inverse"></i>
                            </span>
                          </div>
                          <div class="col-md-8 text-center">
                              <p class="text-muted">All Referrals</p>
                                  <h5 class="card-title small"><span id="total_referrals">{{$countAllReferrals}}</span></h5>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
              <!-- info card -->
              <div class="col-md-3 col-6">
                <a href="#" class="card rounded-2 p-0">
                  <div class="card-body p-2 py-4">
                        <div class="row">
                          <div class="col-md-4 text-center">
                            <span class="fa-stack fa-2x">
                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                <i class="fas fa-share fa-stack-1x fa-inverse"></i>
                            </span>
                          </div>
                          <div class="col-md-8 text-center">
                              <p class="text-muted">Active</p>
                              <h5 class="card-title small"><span id="active_referrals">{{$countActiveReferrals}}</span></h5>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
              <!-- info card -->
              <div class="col-md-3 col-6">
                <a href="#" class="card rounded-2 p-0">
                  <div class="card-body p-2 py-4">
                        <div class="row">
                          <div class="col-md-4 text-center">
                            <span class="fa-stack fa-2x">
                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                <i class="fas fa-share fa-stack-1x fa-inverse"></i>
                            </span>
                          </div>
                          <div class="col-md-8 text-center">
                              <p class="text-muted">Sattled</p>
                              <h5 class="card-title small"><span id="sattled_referrals">{{$countSattledReferrals}}</span></h5>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
              <!-- info card -->
              <div class="col-md-3 col-6">
                <a href="#" class="card rounded-2 p-0">
                  <div class="card-body p-2 py-4">
                        <div class="row">
                          <div class="col-md-4 text-center">
                            <span class="fa-stack fa-2x">
                                <i class="fas fa-circle fa-stack-2x primary-text"></i>
                                <i class="fas fa-share fa-stack-1x fa-inverse"></i>
                            </span>
                          </div>
                          <div class="col-md-8 text-center">
                              <p class="text-muted">Payout</p>
                              <h5 class="card-title small">&#8358; <span id="total_payout">{{$sumOfCommissions}}</span></h5>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                  <div class="header-title">
                    <h4 class="card-title">Referrals</h4>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                      <table id="datatable" class="table table-striped" data-toggle="data-table">
                          <thead>
                              <tr>
                                  <th>S/N</th>
                                  <th>Referrer</th>
                                  <th>Beneficiary</th>
                                  <th>Commission</th>
                                  <th>Status</th>
                                  <th>Date</th>
                              </tr>
                          </thead>
                          <tbody>
                              @php($count =1)
                              @foreach($referrals as $referral)
                              <tr>
                                  <td>{{$count}}</td>
                                  <td>{{$referral->user->username}}</td>
                                  <td>{{$referral->referrer}}</td>
                                  <td>&#8358; {{number_format($referral->commission, 2, '.', ',')}}</td>
                                  <td>{{$referral->status}}</td>
                                  <td>{{$referral->created_at}}</td>
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
  {{-- switch js --}}
  <script src="{{ asset('admin/js/switch.js') }}"></script>

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