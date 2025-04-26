
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
        
  @php( $page = 'referrals')
  @include('components.user.sidebar')
    <main class="main-content">
        <div class="position-relative iq-banner">
            <!--Nav Start-->
            @include('components.user.navbar')
            <div class="iq-navbar-header mb-4" style="height: 230px;">
                <div class="container-fluid iq-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flex-wrap d-flex justify-content-between align-items-center">
                                <div>
                                    <h1>&#8358; <span id="referral-balance">{{number_format(auth('web')->user()->wallet->referralBalance, 2, '.', ',')}}</span></h1>
                                    <p>Referral Bonus</p>
                                    <div class="btn btn-link secondary-bg wallet-id btn-sm">
                                        <span class="wallet-id-text" style="color: #fff;">Pending Referrals: <span id="activeReferrals">{{number_format($countPendingReferrals)}}</span></span>
                                    </div>
                                    <div class="btn btn-link secondary-bg wallet-id btn-sm">
                                        <span class="wallet-id-text" style="color: #fff;">Unsettled Referrals: &#8358; <span id="unsettledReferrals">{{number_format($sumOfCommissions)}}</span></span>
                                    </div>
                                    <div class="btn btn-link secondary-bg wallet-id btn-sm" id="referralCopy">
                                        <span class="wallet-id-text" style="color: #fff;">Referral ID : 
                                            <span id="referralCode">{{auth()->user()->username}}</span>
                                            <span class="ms-4" ><i class="fa fa-copy"></i></span>
                                        </span>
                                    </div>
                                    <span class="tooltiptext" id="copy-tooltip" style="display:none">Copied!</span>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center">
                                        <button id="transfer-funds" class="btn secondary-btn ml-3" type="button" data-bs-toggle="modal" data-bs-target="#transferBonusModal">
                                            <i class="fa fa-exchange"></i>
                                            Transfer Bonus
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-danger ">
                                <marquee id="marquee"></marquee>
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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                            <h4 class="card-title">All Referrals</h4>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                            <table id="datatable" class="table table-striped" data-toggle="data-table">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>User</th>
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
                                        <td>&#8358; {{number_format($referral->commission, 2, '.', ',')}}</td>
                                        <td>{{$referral->status}}</td>
                                        <td>{{$referral->created_at}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('components.user.footer')
    </main>

    <!-- Transfer Funds Modal -->
    <div class="modal fade" id="transferBonusModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="transferModalLabel">Transfer Bonus To Wallet</h5>
            </div>
            <div class="modal-body">
              <form id="transferForm" action="{{route('user.referralsTransfer')}}" method="POST">
                @csrf
                <div class="form-group" id="tranferFormBody">
                  <h4 for="walletIdentifier">Referral Balance: &#8358;<span id="referralBalance">{{number_format(auth('web')->user()->wallet->referralBalance, 2, '.', ',')}}</span></h4>
                    <p class="small">
                        Note! You can transfer your referral bonus only if they reach <b>&#8358; 500</b>
                        Provide your email and password to complete the transaction.
                    </p>
                    <input type="hidden" class="form-control" id="referralBalanceInput" value="{{auth('web')->user()->wallet->referralBalance}}" name="amount" >
                    <label for="amount">Email:</label>
                    <input type="email" name="email" class="form-control" required/>
                    <label for="amount">Password:</label>
                    <input type="password" name="password" class="form-control" required/>
                    <input type="hidden" id="id" name="userId" value="{{ auth()->user()->id}}" class="form-control" />
                    <input type="hidden" id="walletid" name="walletId" value="{{ auth()->user()->wallet->id}}" class="form-control" />
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" onclick="closeModal2('transferBonusModal')" data-dismiss="modal">Close</button>
                  <!-- <button type="button" class="btn btn-primary" id="proceedButton">Proceed</button> -->
                  <button type="submit" class="btn btn-primary" id="transferButton">Transfer</button>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
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
{{-- Referral js --}}
<script src="{{ asset('app/assets/js/app/referrals.js') }}"></script>

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