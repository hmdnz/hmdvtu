
<!doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>@yield('title')</title>

   <!-- Favicon -->
   <link rel="shortcut icon" href="{{ asset('app/assets/images/logos/aarasheeddata2.png') }}" />

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
    @yield('contents')
    

   <!-- Library Bundle Script -->
   <script src="{{ asset('app/assets/js/core/libs.min.js') }}"></script>

   <!-- External Library Bundle Script -->
   <script src="{{ asset('app/assets/js/core/external.min.js') }}"></script>

   <!-- Widgetchart Script -->
   <script src="{{ asset('app/assets/js/charts/widgetcharts.js') }}"></script>

   <!-- mapchart Script -->
   <script src="{{ asset('app/assets/js/charts/vectore-chart.js') }}"></script>
   <script src="{{ asset('app/assets/js/charts/dashboard.js') }}"></script>

   <!-- fslightbox Script -->
   <script src="{{ asset('app/assets/js/plugins/fslightbox.js') }}"></script>

   <!-- Settings Script -->
   <script src="{{ asset('app/assets/js/plugins/setting.js') }}"></script>

   <!-- Slider-tab Script -->
   <script src="{{ asset('app/assets/js/plugins/slider-tabs.js') }}"></script>

   <!-- Form Wizard Script -->
   <script src="{{ asset('app/assets/js/plugins/form-wizard.js') }}"></script>

   <!-- AOS Animation Plugin-->

   <!-- App Script -->
   <script src="{{ asset('app/assets/js/hope-ui.js') }}" defer></script>
   <!-- this page Script -->
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
    
   @stack('script')
   
</body>

</html>