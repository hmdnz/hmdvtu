
<!doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="ZaumaData, Data, Airtime, bulk sms, cable subscription" />
    <meta name="developer" content="Shahuci Global Resources" />
    <meta name="website" content="www.sgr.com.ng" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile | ZaumaData</title>
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
        
  @php( $page = 'profile')
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
                                    <h1>Profile </h1>
                                    <p>Update your profile by filing your personal information</p>
                                </div>
                                {{-- <div>
                                    <a href="{{route('user.profile')}}" class="btn btn-link btn-soft-light">
                                        <i class="fa fa-gear"></i>
                                        Settings
                                    </a>
                                </div> --}}
                            </div>
                            <!-- <div class="col-12 text-danger ">
                                <marquee id="marquee"></marquee>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="iq-header-img primary-bg">

                </div>
            </div>
        </div>
        <div class="conatiner-fluid content-inner mt-n5 py-0">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Profile</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <form id="registrationForm" action="{{route('user.profile')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="userId" value="{{auth()->user()->id}}">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="full-name" class="form-label">First Name*</label>
                                                <input type="text" class="form-control" name="firstName" id="firstName" value="{{ auth()->user()->firstName }}" required>
                                                @error('firstName')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="last-name" class="form-label">Last Name*</label>
                                                <input type="text" class="form-control" name="lastName" id="lastName" value="{{ auth()->user()->lastName }}" required>
                                                @error('lastName')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="username" class="form-label">Username*</label>
                                                <input type="text" class="form-control" name="username" id="username" value="{{ auth()->user()->username }}" readonly>
                                                @error('username')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="dob" class="form-label">Date of Birth*</label>
                                                <input type="date" class="form-control" name="dob" id="dob" value="{{ auth()->user()->dob }}" required>
                                                @error('dob')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="gender" class="form-label">Gender</label>
                                                <select class="form-control" name="gender" id="gender" placeholder=" ">
                                                    <option value="">
                                                        {{ isset(auth()->user()->gender) ? auth()->user()->gender : 'Choose..' }}
                                                    </option>
                                                    <option>Female</option>
                                                    <option>Male</option>
                                                </select>
                                                @error('gender')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="bvn" class="form-label">BVN*</label>
                                                <input type="text" class="form-control" name="bvn" id="bvn" value="{{ auth()->user()->bvn }}" required>
                                                @error('bvn')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="nin" class="form-label">NIN*</label>
                                                <input type="text" class="form-control" name="nin" id="nin" value="{{ auth()->user()->nin }}" required>
                                                @error('nin')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="phone" class="form-label">Phone No.*</label>
                                                <input type="text" class="form-control" name="phone" id="phone" value="{{ auth()->user()->phone }}" readonly>
                                                @error('phone')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="email" class="form-label">Email*</label>
                                                <input type="email" class="form-control" name="email" id="email" value="{{ auth()->user()->email }}" readonly>
                                                @error('email')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="state" class="form-label">State</label>
                                                <select class="form-control" name="state" id="state" placeholder=" ">
                                                    @php($state = auth()->user()->state)
                                                    @if(empty($state))
                                                        <option value="">Choose..</option>
                                                    @else
                                                        <option>{{auth()->user()->state}}</option>
                                                    @endif
                                                    @include('components.user.state-list')
                                                </select>
                                                @error('state')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="address" class="form-label">Address</label>
                                                <input type="text" class="form-control" name="address" id="address" value="{{ auth()->user()->address }}" required>
                                                @error('address')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center ">
                                        <button type="submit" id="updateBtn" type="submit" class="btn btn-primary col-12">Update</button>
                                    </div>
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
</body>
</html>