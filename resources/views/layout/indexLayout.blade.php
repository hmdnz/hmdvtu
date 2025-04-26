
<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>ZaumaData - @yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords" content="Zauma Data, Data, Airtime, bulk sms, cable subscription" />
    <meta name="developer" content="Shahuci Global Resources" />
    <meta name="website" content="www.sgr.com.ng" />

    <!-- Favicons -->
    <link href="{{ asset('main/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('main/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('main/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('main/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('main/vendor/aos/aos.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('main/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('main/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('main/css/main.css') }}" rel="stylesheet">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('app/assets/css/toastr.css') }}">
    <!-- FontAwesome 5-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" type="text/css">
    
</head>

<body class="  ">
        @yield('contents')
        
    
    <!-- Vendor JS Files -->
    <script src="{{ asset('main/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('main/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('main/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('main/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('main/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('main/vendor/waypoints/noframework.waypoints.js') }}"></script>
    <script src="{{ asset('main/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('main/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('main/js/main.js') }}"></script>
    <!-- Toastr js -->
    <script src="{{ asset('app/assets/js/toastr.js') }}"></script>

   @if (session()->has('message'))
   <script>
            toastr.info("{{ session('message') }}");        
    </script>
    @endif
    
   @stack('script2')

</body>

</html>