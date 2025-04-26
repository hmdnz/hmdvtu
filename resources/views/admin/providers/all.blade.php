
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Providers | ZaumaData</title>

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

  @php( $page = 'providers')
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
                                <h1>Providers</h1>
                                <p>List of all providers we use on our platform</p>
                              </div>    
                              <div>
                                  <a type="button" data-bs-toggle="modal" data-bs-target="#addProviderModal" class="btn btn-link btn-soft-light">
                                      <i class="fa fa-plus"></i>
                                      provider
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
            <div class="col-sm-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between">
                  <div class="header-title">
                    <h4 class="card-title">All Providers ({{$providerCount}})</h4>
                  </div>
                </div>
    
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                      <thead>
                          <tr>
                              <th>S/N</th>
                              <th>Title</th>
                              <th>Key</th>
                              <th>Service</th>
                              <th>Status</th>
                              <th>Created On</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody id="users-table-body">
                          @php($count = 1)
                          @foreach($providers as $provider)
                              <tr>
                                  <td>{{$count}}</td>
                                  <td>{{$provider->title}}</td>
                                  <td>{{$provider->key}}</td>
                                  <td>{{$provider->service}}</td>
                                  <td>
                                      @if($provider->status == 'Active')
                                          <span class="badge badge-success bg-success">Active</span>  
                                      @else
                                          <span class="badge badge-danger bg-danger">Inactive</span>  
                                      @endif  
                                  </td>
                                  <td>{{$provider->created_at}}</td>
                                  <td>
                                      <a onclick="editProvider('{{$provider->id}}', '{{$provider->title}}', '{{$provider->key}}', '{{$provider->service}}')" class="btn btn-primary px-2" >
                                          <i class="fa fa-edit small"></i>
                                        </a>
                                      @if($provider->status == 'Active')
                                      <a href="{{ route('admin.providers.deactivate', [$provider->id])}}" class="btn btn-warning px-2" >
                                        <i class="fa fa-arrow-down small"></i>
                                      </a>
                                      @else
                                      <a href="{{ route('admin.providers.activate', [$provider->id])}}" class="btn btn-success px-2" >
                                          <i class="fa fa-arrow-up small"></i>
                                      </a>
                                      @endif
                                      <a onclick="deleteProvider('{{$provider->id}}')" class="btn btn-danger px-2" >
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

  {{-- add provider modal --}}
  <div class="modal" id="addProviderModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal body -->
          <div class="modal-body">
              <h2>Add Provider</h2>
              <form action="{{route('admin.providers.add')}}" method="post">
                  @csrf
                  <div class="form-group">
                    <label for="amount">Title:</label>
                    <input type="text" name="title" class="form-control" required/>
                  </div>
                  <div class="form-group">
                    <label for="key">Key:</label>
                    <input type="text" id="key" name="key" class="form-control" required/>
                  </div>
                  <div class="form-group">
                    <label for="amount">Service:</label>
                    <select name="service" class="form-control" required>
                        <option value="">Choose..</option>
                        <option>All</option>
                        @foreach($services as $item)
                        <option>{{$item->title}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="amount">Status:</label>
                    <select name="status" class="form-control" required>
                        <option value="">Choose..</option>
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                  </div>
                  <div class="mt-2">
                      <button type="button" class="btn secondary-btn" data-bs-dismiss="modal" onclick="closeModal('addProviderModal')">Close</button>
                      <button type="submit" class="btn primary-btn">Submit</button>
                  </div>
              </form>
          </div>
          
        </div>
      </div>
  </div>
  {{-- edit provider modal --}}
  <div class="modal" id="editProviderModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal body -->
          <div class="modal-body">
              <h2>Edit provider</h2>
              <form action="{{route('admin.providers.edit')}}" method="post">
                  @csrf
                  <input type="hidden" name="id" id="editProviderId">
                  <div class="form-group">
                    <label for="amount">Title:</label>
                    <input type="text" name="title" class="form-control" id="editProviderTitle" required/>
                  </div>
                  <div class="form-group">
                    <label for="editProviderKey">Key:</label>
                    <input type="text" id="editProviderKey" name="key" class="form-control" required/>
                  </div>
                  <div class="form-group">
                    <label for="editProviderService">Service:</label>
                    <select name="service" class="form-control" required>
                        <option id="editProviderServiceOption">Choose..</option>
                        <option>All</option>
                        @foreach($services as $item)
                        <option>{{$item->title}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="mt-2">
                      <button type="button" class="btn secondary-btn" data-bs-dismiss="modal" onclick="closeModal('editProviderModal')">Close</button>
                      <button type="submit" class="btn primary-btn">Submit</button>
                  </div>
              </form>
          </div>

        </div>
      </div>
  </div>
  {{-- delete provider --}}
  <div class="modal" id="deleteProviderModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal body -->
        <div class="modal-body">
            <h2>Delete provider</h2>
            <form action="{{route('admin.providers.delete')}}" method="post">
                @csrf
                <div class="form-group">
                  <label for="amount">Admin Email:</label>
                  <input type="email" name="email" class="form-control" required/>
                </div>
                <div class="form-group">
                  <label for="amount">Admin Password:</label>
                  <input type="password" name="adminPassword" class="form-control" required/>
                </div>
                <input type="hidden" id="id" name="adminId" value="{{ auth()->user()->id}}" class="form-control" />
                <input type="hidden" id="providerId" name="id"  class="form-control" />
                <div class="mt-2">
                    <button type="button" class="btn secondary-btn" data-bs-dismiss="modal" onclick="closeModal('deleteProviderModal')">Close</button>
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
    
    
    function editProvider(id, title, key, service) {
      // set the values in the modal
      document.getElementById('editProviderId').value = id;
      document.getElementById('editProviderTitle').value = title;
      document.getElementById('editProviderKey').value = key;
      document.getElementById('editProviderServiceOption').value = service;
      document.getElementById('editProviderServiceOption').label = service;
        // alert(status);
      // open the modal
      const modal = new bootstrap.Modal(document.getElementById('editProviderModal'));
        modal.show();
    }
    function deleteProvider(id) {
      // set the values in the modal
      document.getElementById('providerId').value = id;
        // alert(status);
      // open the modal
      const modal = new bootstrap.Modal(document.getElementById('deleteProviderModal'));
        modal.show();
    }


  </script>
</body>
</html>
