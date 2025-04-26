
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
                                <h1>Packages ({{$packageCount}})</h1>
                                <p>List of all packages offered on the platform</p>
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
              </div>
          </div>
      </div>
      <div class="conatiner-fluid content-inner mt-n5 py-0">
          <div class="row">
            <div class="col-sm-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between">
                  <div class="header-title">
                    {{-- <h4 class="card-title">All Packages </h4> --}}
                  </div>
                </div>
    
                <div class="card-body p-0">
                    <div class="container col-12 row filter">
                        <h6 class="mb-1">Filter</h6>
                        <div class="col-md-3 col-6 form-group">
                            <select class="form-control" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-6 form-group">
                            <select class="form-control" id="providerFilter">
                                <option value="">All Providers</option>
                                <option value="SMEPlug">SMEPlug</option>
                                <option value="AlrahuzData">AlrahuzData</option>
                                <option value="EasyAccessAPI">EasyAccessAPI</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-6 form-group">
                            <select class="form-control" id="serviceFilter">
                                <option value="">All Services</option>
                                <option value="Airtime">Airtime</option>
                                <option value="Data">Data</option>
                                <option value="Cable">Cable</option>
                                <option value="Bulk SMS">Bulk SMS</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-6 form-group">
                            <select class="form-control" id="categoryFilter">
                                <option value="">All Categories</option>
                                <option value="VTU">VTU</option>
                                <option value="SME">SME</option>
                                <option value="CG">CG</option>
                                <option value="GIFTING">GIFTING</option>
                                <option value="DIRECT">DIRECT</option>
                                <option value="SPECIAL">SPECIAL</option>
                            </select>
                        </div>
                    </div>
                  <div class="table-responsive">
                    
                    <table id="example" class="table table-striped" data-toggle="data-table">
                      <thead>
                          <tr>
                              <th>S/N</th>
                              <th>Title</th>
                              <th>Provider</th>
                              <th>Service</th>
                              <th>Biller</th>
                              <th>Category</th>
                              <th>Validity</th>
                              <th>Cost</th>
                              <th>Price</th>
                              <th>Status</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody id="users-table-body">
                          @php($count = 1)
                          @foreach($packages as $package)
                              <tr>
                                  <td>{{$count}}</td>
                                  <td>{{$package->title}}</td>
                                  <td>{{$package->provider}}</td>
                                  <td>{{$package->service}}</td>
                                  <td>{{isset($package->billerID)?$package->biller->title:''}}</td>
                                  <td>{{$package->planType}}</td>
                                  <td>{{$package->validity}}</td>
                                  <td>&#8358; {{$package->cost}}</td>
                                  <td>&#8358; {{$package->price}}</td>
                                  <td>
                                      @if($package->status == 'Active')
                                          <span class="badge badge-success bg-success">Active</span>  
                                      @else
                                          <span class="badge badge-danger bg-danger">Inactive</span>  
                                      @endif  
                                  </td>
                                  <td>
                                      <a href="{{route('admin.packages.showEdit', [$package->id])}}" class="btn primary-btn px-2" >
                                          <i class="fa fa-edit small"></i>
                                      </a>
                                      @if($package->status == 'Active')
                                      <a href="{{ route('admin.packages.deactivate', [$package->id])}}" class="btn btn-warning px-2" >
                                        <i class="fa fa-arrow-down small"></i>
                                      </a>
                                      @else
                                      <a href="{{ route('admin.packages.activate', [$package->id])}}" class="btn btn-success px-2" >
                                          <i class="fa fa-arrow-up small"></i>
                                      </a>
                                      @endif
                                      <a onclick="deletePackage('{{$package->id}}')" class="btn btn-danger px-2" >
                                          <i class="fa fa-trash small"></i>
                                      </a>
                                  </td>
                              </tr>
                            @php($count++)
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

  {{-- delete Package --}}
  <div class="modal" id="deletePackageModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal body -->
          <div class="modal-body">
              <h2>Delete Package</h2>
              <form action="{{route('admin.packages.delete')}}" method="post">
                  @csrf
                  <label for="amount">Admin Email:</label>
                  <input type="email" name="email" class="form-control" required/>
                  <label for="amount">Admin Password:</label>
                  <input type="password" name="adminPassword" class="form-control" required/>
                  <input type="hidden" id="id" name="adminId" value="{{ auth()->user()->id}}" class="form-control" />
                  <input type="hidden" id="packageId" name="id"  class="form-control" />
                  <div class="mt-2">
                      <button type="button" class="btn secondary-btn" data-dismiss="modal" onclick="closeModal('deleteServiceModal')">Close</button>
                      <button type="submit" class="btn primary-btn">Delete</button>
                  </div>
              </form>
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
    
    function deletePackage(id) {
      // set the values in the modal
      document.getElementById('packageId').value = id;
      // open the modal
      const modal = new bootstrap.Modal(document.getElementById('deletePackageModal'));
        modal.show();
    }
    
    $(document).ready(function () {
        var table = $('#example').DataTable();

        // custom filters 
        $('#statusFilter, #providerFilter, #serviceFilter, #categoryFilter').on('change', function () {
            table.draw();
        });

        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            var status = $('#statusFilter').val();
            var provider = $('#providerFilter').val();
            var service = $('#serviceFilter').val();
            var category = $('#categoryFilter').val();
            var rowStatus = data[9]; // index based on status column position
            var rowProvider = data[2]; // index based on providers column position
            var rowService = data[3]; // index based on service column position
            var rowCategory = data[5]; // index based on category column position

            if (
                (status === "" || rowStatus === status) &&
                (provider === "" || rowProvider === provider) &&
                (service === "" || rowService === service) &&
                (category === "" || rowCategory === category)
            ) {
                return true;
            }
            return false;
        });
    });

  </script>
</body>
</html>