
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Packages | ZaumaData</title>

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

  @php( $page = 'packages')
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
                                <h1>Packages</h1>
                              </div>
                              <div>
                                <a href="{{route('admin.packages.showAdd')}}" class="btn btn-link btn-soft-light">
                                  <i class="fa fa-plus"></i>
                                  Package
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
            <div class="col-sm-8 mx-auto">
              <div class="card">
                <div class="card-header d-flex justify-content-between">
                  <div class="header-title">
                    <h4 class="card-title">New Package</h4>
                  </div>
                </div>
    
                <div class="card-body">
                  <form action="{{route('admin.packages.add')}}" method="post">
                      @csrf
                      <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-label" for="fname">Title:</label>
                            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" required>
                            @error('title')
                            <span class="text-danger small">{{ $message}}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="amount">Provider:</label>
                            <select class="form-control" name="provider" required>
                              @if(null !== old('provider'))
                                  <option>{{old('provider')}}</option>
                              @else
                                <option value="">Choose..</option>
                              @endif
                              @foreach($providers as $provider)
                              <option value="{{$provider->key}}">{{$provider->title}}</option>
                              @endforeach
                            </select>
                            @error('service')
                            <span class="text-danger small">{{ $message}}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="amount">Biller:</label>
                            <select id="billerSelect" class="form-control" name="billerID">
                              @if(null !== old('billerID'))
                                  <option>{{old('billerID')}}</option>
                              @else
                                <option value="">Choose..</option>
                              @endif
                              @foreach($billers as $biller)
                              <option value="{{$biller->id}}">{{$biller->title}}</option>
                              @endforeach
                            </select>
                            @error('billerID')
                            <span class="text-danger small">{{ $message}}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="amount">Service:</label>
                            <select id="serviceSelect" class="form-control" name="service" required>
                              @if(null !== old('service'))
                                  <option>{{old('service')}}</option>
                              @else
                                <option value="">Choose..</option>
                              @endif
                              @foreach($services as $service)
                              <option>{{$service->title}}</option>
                              @endforeach
                            </select>
                            @error('service')
                            <span class="text-danger small">{{ $message}}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                          <label for="amount">Package Type:</label>
                          <select id="type" class="form-control" name="type" required>
                            @if(null !== old('type'))
                                <option>{{old('type')}}</option>
                            @else
                              <option value="">Choose..</option>
                            @endif
                            @foreach($services as $service)
                            <option>{{$service->title}}</option>
                            @endforeach
                          </select>
                          @error('type')
                          <span class="text-danger small">{{ $message}}</span>
                          @enderror
                        </div>
                        <div class="form-group col-md-6">
                          <label for="amount">Type of Plan:</label>
                          <select id="planType" class="form-control" name="planType">
                            @if(null !== old('planType'))
                                <option>{{old('planType')}}</option>
                            @else
                              <option value="">Choose..</option>
                            @endif
                            <option value="SME">SME</option>
                            <option value="COPORATE">COPORATE</option>
                            <option value="SPECIAL">SPECIAL</option>
                            <option value="CG">CG</option>
                            <option value="GIFTING">GIFTING</option>
                            <option value="CG_LITE">CG_LIGHT</option>
                            <option value="DIRECT">DIRECT</option>
                            <option value="VTU">VTU</option>
                            <option value="SHARE">SHARE</option>
                            <option value="STARTIMES">STARTIMES</option>
                            <option value="GOTV">GOTV</option>
                            <option value="DSTV">DSTV</option>
                            <option value="NECO">NECO</option>
                            <option value="WAEC">WAEC</option>
                            <option value="NBAIS">NBAIS</option>
                            <option value="NABTEB">NABTEB</option>
                          </select>
                          @error('planType')
                          <span class="text-danger small">{{ $message}}</span>
                          @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="add2">Cost(NGN)</label>
                            <input type="text" class="form-control" name="cost" id="cost" value="{{ old('cost') }}" required>
                            @error('cost')
                            <span class="text-danger small">{{ $message}}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                          <label class="form-label" for="add2">Price(NGN)</label>
                          <input type="text" class="form-control" name="price" id="price" value="{{ old('price') }}" required>
                          @error('price')
                          <span class="text-danger small">{{ $message}}</span>
                          @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="add2">Package Size</label>
                            <input type="text" class="form-control" name="size" id="size" value="{{ old('size') }}" >
                            @error('size')
                            <span class="text-danger small">{{ $message}}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="mobno">Validity</label>
                            <input type="text" class="form-control" name="validity" id="validity" value="{{ old('validity') }}" >
                            @error('validity')
                                <span class="text-danger small">{{ $message}}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="amount">API Code:</label>
                            <input type="text" id="planID" name="planID" class="form-control" value="{{ old('planID') }}"/>
                            @error('planID')
                                <span class="text-danger small">{{ $message}}</span>
                            @enderror
                        </div>
                      </div>
                      <button type="submit" class="btn primary-btn" id="submitAddAdmin">Add New Package</button>
                  </form>
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

