
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>{{ env("APP_NAME")}} | Categories </title>

   <!-- Favicon -->
   <link rel="shortcut icon" href="{{ asset('assets/img/logo.png') }}" />

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

    @php( $page = 'categories')
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
                                <h1>Service Categories</h1>
                                <p>Categories for all services on the platform</p>
                                </div>    
                                <div>
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
            <div class="col-sm-12">
                <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                    <h4 class="card-title">Categories</h4>
                    </div>
                </div>
    
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                        <thead>
                            <tr>
                            <th>S/N</th>
                            <th>Service</th>
                            <th>Category</th>
                            <th>MTN</th>
                            <th>AIRTEL</th>
                            <th>9MOBILE</th>
                            <th>GLO</th>
                            <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody id="users-table-body">
                            @php($count = 1)
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{$count}}</td>
                                    <td>{{$category->service}}</td>
                                    <td>{{$category->title}}</td>
                                    
                                    <td>
                                        @if($category->mtn == 'Active')                                    
                                            <a type="button" class="btn btn-success activate-btn btn-sm py-1 p-2" onclick="switchService({{$category->id}}, 'mtn', 'Inactive')" data-delivery-id="{{$category->id}}">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @else
                                            <a type="button" class="btn primary-btn delete-btn btn-sm py-1 p-2" onclick="switchService({{$category->id}}, 'mtn', 'Active')" data-delivery-id="{{$category->id}}">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        @endif  
                                    </td>
                                    <td>
                                        @if($category->airtel == 'Active')                                    
                                            <a type="button" class="btn btn-success activate-btn btn-sm py-1 p-2" onclick="switchService({{$category->id}}, 'airtel', 'Inactive')" data-delivery-id="{{$category->id}}">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @else
                                            <a type="button" class="btn primary-btn delete-btn btn-sm py-1 p-2" onclick="switchService({{$category->id}}, 'airtel', 'Active')" data-delivery-id="{{$category->id}}">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        @endif  
                                    </td>
                                    <td>
                                        @if($category->mobile == 'Active')                                    
                                            <a type="button" class="btn btn-success activate-btn btn-sm py-1 p-2" onclick="switchService({{$category->id}}, 'mobile', 'Inactive')" data-delivery-id="{{$category->id}}">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @else
                                            <a type="button" class="btn primary-btn delete-btn btn-sm py-1 p-2" onclick="switchService({{$category->id}}, 'mobile', 'Active')" data-delivery-id="{{$category->id}}">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        @endif  
                                    </td>
                                    <td>
                                        @if($category->glo == 'Active')                                    
                                            <a type="button" class="btn btn-success activate-btn btn-sm py-1 p-2" onclick="switchService({{$category->id}}, 'glo', 'Inactive')" data-delivery-id="{{$category->id}}">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @else
                                            <a type="button" class="btn primary-btn delete-btn btn-sm py-1 p-2" onclick="switchService({{$category->id}}, 'glo', 'Active')" data-delivery-id="{{$category->id}}">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        @endif  
                                    </td>
                                    <td>{{$category->updated_at}}</td>
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