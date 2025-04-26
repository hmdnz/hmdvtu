
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Admin Sign In</title>

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
   {{-- style --}}
   <link rel="stylesheet" href="{{ asset('app/assets/css/style.css') }}">
</head>

<body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    
    <div class="wrapper">
        <section class="login-content">
            <div class="row m-0 align-items-center bg-light vh-100">
                <div class="col-md-4 mx-auto">
                        <div class="card d-flex justify-content-center mb-0 auth-card">
                            <div class="card-body">
                                <center>
                                    <img src="{{ asset('main/img/logo-vt.png') }}" width="130" height="100">
                                    <h5 class="text-center">Log In Into Account</h5> 
                                </center>  
                                <form id="login-form" action="{{ route('admin.login') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required>
                                                <!-- <small id="email-message"></small> -->
                                                @error('email')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder=" " required>
                                        </div>
                                        </div>
                                        <div class="col-lg-12 d-flex justify-content-between">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="customCheck1">
                                            <label class="form-check-label" for="customCheck1">Remember Me</label>
                                        </div>
                                        {{-- <a href="{{ route('user.forgetPassword')}}">Forgot Password?</a> --}}
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn primary-btn col-12" id="submitButton">Login</button>
                                    </div>
                                    <p class="mt-3 text-center">
                                    </p>
                                </form>
                            </div>
                        </div>
                </div>
            </div>
        </section>
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

    @if($errors->any())
        <script>
            let errorMessages = `{!! implode('<br>', $errors->all()) !!}`;
            toastr.error(errorMessages, 'Validation Errors', {timeOut: 5000, closeButton: true, progressBar: true, escapeHtml: false});
        </script>
    @endif
 
</body>

</html>