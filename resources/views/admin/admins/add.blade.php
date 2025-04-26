
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

  @php( $page = 'admins')
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
                                <h1>Admins!</h1>
                          
                              </div>
                              <div>
                                <a type="button" href="{{route('admin.admins.add')}}" class="btn btn-link btn-soft-light">
                                  <i class="fa fa-plus"></i>
                                  New Admin
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
            <div class="col-sm-6 mx-auto">
              <div class="card">
                <div class="card-header d-flex justify-content-between">
                  <div class="header-title">
                    <h4 class="card-title">New Admin</h4>
                  </div>
                </div>
    
                <div class="card-body">
                  <form action="{{route('admin.admins.add')}}" method="post">
                      @csrf
                      <div class="row">
                      <div class="form-group col-md-6">
                          <label class="form-label" for="fname">FirstName:</label>
                          <input type="text" class="form-control" name="firstName" id="firstName" value="{{ old('firstName') }}" required>
                          @error('firstName')
                          <span class="text-danger small">{{ $message}}</span>
                          @enderror
                      </div>
                      <div class="form-group col-md-6">
                          <label class="form-label" for="fname">LastName:</label>
                          <input type="text" class="form-control" name="lastName" id="lastName" value="{{ old('lastName') }}" required>
                          @error('lastName')
                          <span class="text-danger small">{{ $message}}</span>
                          @enderror
                      </div>
                      {{-- <div class="form-group col-md-6">
                          <label class="form-label" for="add1">Username</label>
                          <input type="text" class="form-control" name="username" id="username" value="{{ old('username') }}" required>
                          @error('username')
                          <span class="text-danger small">{{ $message}}</span>
                          @enderror
                      </div> --}}
                      <div class="form-group col-md-12">
                          <label class="form-label">Role</label>
                          <select class="selectpicker form-control" name="role" id="role" data-style="py-0">
                              @if(null !== old('role'))
                                  <option>{{old('role')}}</option>
                              @else
                              <option value="">Choose..</option>
                              @endif
                              <option value="super-admin">Super-Admin</option>
                              <option value="admin">Admin</option>
                              <option value="moderator">Moderator</option>
                          </select>
                          @error('role')
                          <span class="text-danger small">{{ $message}}</span>
                          @enderror
                      </div>
                      <div class="form-group col-md-7">
                          <label class="form-label" for="add2">Email</label>
                          <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required>
                          @error('email')
                          <span class="text-danger small">{{ $message}}</span>
                          @enderror
                      </div>
                      <div class="form-group col-md-5">
                          <label class="form-label" for="add2">Phone Number</label>
                          <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') }}" required>
                          @error('phone')
                          <span class="text-danger small">{{ $message}}</span>
                          @enderror
                      </div>
                      <div class="form-group col-md-6">
                          <label class="form-label" for="mobno">Password</label>
                          <input type="password" class="form-control" name="password" id="password" required>
                          @error('password')
                              <span class="text-danger small">{{ $message}}</span>
                          @enderror
                      </div>
                      <div class="form-group col-md-6">
                          <label class="form-label" for="mobno">Confirm Password</label>
                          <input type="password" class="form-control" name="password_confirmation" id="cpassword">
                          @error('password_confirmation')
                              <span class="text-danger small">{{ $message}}</span>
                          @enderror
                      </div>
                      </div>
                      <button type="submit" class="btn btn-primary" id="submitAddAdmin">Add New Admin</button>
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
      </div>
      @include('components.admin.footer')
  </main>
  <!-- The NewAdmin Modal -->
  <div class="modal" id="newAdmin">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New Admin</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
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