
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Sign Up</title>

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
                <div class="col-md-5 mx-auto">
                    <div class="row justify-content-center">
                        <div class="card auth-card d-flex justify-content-center mb-0">
                            <div class="card-body">
                                <center>
                                    <img src="{{ asset('main/img/logo-hr.png') }}" width="130" height="70">
                                    <h5 class="text-center">Create an Account</h5> 
                                </center>                       
                                <form id="registrationForm" action="{{ route('user.signup') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6 col-6">
                                            <div class="form-group my-1">
                                                <input type="text" class="form-control" name="firstName" id="firstName" placeholder="First Name" value="{{ old('firstName') }}" required>
                                                @error('firstName')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-6">
                                            <div class="form-group my-1">
                                                <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Last Name" value="{{ old('lastName') }}" required>
                                                @error('lastName')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group my-1">
                                                <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="{{ old('username') }}" required>
                                                @error('username')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group my-1">
                                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone Number" value="{{ old('phone') }}" required>
                                                @error('phone')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group my-1">
                                                <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
                                                @error('email')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-6">
                                            <div class="form-group my-1">
                                                <input type="password" class="form-control" name="password" placeholder="Password" id="password" required>
                                                @error('password')
                                                    <span class="text-danger small">{{ $message}}</span>
                                                    @enderror
                                            </div>
                                        </div>                  
                                        <div class="col-md-6 form-group my-1">
                                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" id="cpassword" required>
                                            @error('password_confirmation')
                                            <span class="text-danger small">{{ $message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group my-1">
                                                <input type="text" class="form-control" name="referralId" id="referralId" placeholder="Referral Code (optional)">
                                                @error('referralId')
                                                <span class="text-danger small">{{ $message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12 d-flex justify-content-center">
                                            <div class="form-check my-2">
                                                <input type="checkbox" class="form-check-input" id="customCheck1" required>
                                                <label class="form-check-label" for="customCheck1">I agree with the
                                                    <a href="{{route('privacy')}}" class="secondary-text">Privacy Policy</a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center ">
                                        <button type="submit" class="btn primary-btn col-12" >Register</button>
                                    </div>
                                    <p class=" text-center">
                                        Already have an Account <a href="{{ route('user.login') }}" class="text-underline secondary-text">Sign In</a>
                                    </p>
                                </form>
                            </div>
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
