
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Reset Password</title>

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
                                <h5 class="text-center">Reset Password</h5> 
                                <p>Provide a new password to finish reseting the password.</p>
                            </center>  
                            <form id="login-form" action="{{ route('user.login') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="password" aria-describedby="password" placeholder="New Password">
                                            <div id="passwordError" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="confirm-password" aria-describedby="confirm-password" placeholder="Confirm New Password">
                                            <div id="passwordError2" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn primary-btn col-12" id="submitButton">Submit</button>
                                </div>
                                <p class="mt-3 text-center">
                                    Have account? <a href="{{ route('user.login')}}" class="text-underline secondary-text">Sign In.</a>
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