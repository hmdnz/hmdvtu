
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

  @php( $page = 'billers')
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
                                <h1>Billers</h1>
                                <p>List of all billers offered on the platform</p>
                              </div>    
                              <div>
                                  <a type="button" data-bs-toggle="modal" data-bs-target="#addbillerModal" class="btn btn-link btn-soft-light">
                                      <i class="fa fa-plus"></i>
                                      Biller
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
                    <h4 class="card-title">All Billers ({{$billerCount}})</h4>
                  </div>
                </div>
    
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                      <thead>
                          <tr>
                              <th>S/N</th>
                              <th>Title</th>
                              <th>Service</th>
                              <th>Variation</th>
                              <th>Status</th>
                              <th>Created On</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody id="users-table-body">
                          @php($count = 1)
                          @foreach($billers as $biller)
                              <tr>
                                  <td>{{$count}}</td>
                                  <td>{{$biller->title}}</td>
                                  <td>{{$biller->service}}</td>
                                  <td>{{$biller->variation}}</td>
                                  <td>
                                      @if($biller->status == 'Active')
                                          <span class="badge badge-success bg-success">Active</span>  
                                      @else
                                          <span class="badge badge-danger bg-danger">Inactive</span>  
                                      @endif  
                                  </td>
                                  <td>{{$biller->created_at}}</td>
                                  <td>
                                      <a onclick="editBiller('{{$biller->id}}', '{{$biller->title}}', '{{$biller->service}}', '{{$biller->variation}}')" class="btn btn-primary px-2" >
                                          <i class="fa fa-edit small"></i>
                                        </a>
                                      @if($biller->status == 'Active')
                                      <a href="{{ route('admin.billers.deactivate', [$biller->id])}}" class="btn btn-warning px-2" >
                                        <i class="fa fa-arrow-down small"></i>
                                      </a>
                                      @else
                                      <a href="{{ route('admin.billers.activate', [$biller->id])}}" class="btn btn-success px-2" >
                                          <i class="fa fa-arrow-up small"></i>
                                      </a>
                                      @endif
                                      <a onclick="deleteBiller('{{$biller->id}}')" class="btn btn-danger px-2" >
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

  {{-- add biller modal --}}
  <div class="modal" id="addBillerModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal body -->
          <div class="modal-body">
              <h2>Add Biller</h2>
              <form action="{{route('admin.billers.add')}}" method="post">
                  @csrf
                  <label for="amount">Title:</label>
                  <input type="text" name="title" class="form-control" required/>
                  <label for="amount">Service:</label>
                  <select name="service" class="form-control" required>
                      <option value="">Choose..</option>
                      <option>General</option>
                      <option>Airtime</option>
                      <option>Data</option>
                      <option>Cable</option>
                      <option>BulkSMS</option>
                      <option>Electricity</option>
                  </select>
                  <label for="amount">Status:</label>
                  <select name="status" class="form-control" required>
                      <option value="">Choose..</option>
                      <option>Active</option>
                      <option>Inactive</option>
                  </select>
                  <label for="amount">Variation:</label>
                  <input type="number" name="variation" class="form-control" required/>
                  <div class="mt-2">
                      <button type="button" class="btn secondary-btn" data-bs-dismiss="modal" onclick="closeModal('addBillerModal')">Close</button>
                      <button type="submit" class="btn primary-btn">Submit</button>
                  </div>
              </form>
          </div>

        </div>
      </div>
  </div>
  {{-- edit biller modal --}}
  <div class="modal" id="editBillerModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal body -->
          <div class="modal-body">
              <h2>Edit Biller</h2>
              <form action="{{route('admin.billers.edit')}}" method="post">
                  @csrf
                  <input type="hidden" name="id" id="editBillerId">
                  <label for="amount">Title:</label>
                  <input type="text" name="title" class="form-control" id="editBillerTitle" required/>
                  
                  <label for="amount">Service:</label>
                  <select name="service" class="form-control" required>
                      <option id="editBillerService"></option>
                      <option>General</option>
                      <option>Airtime</option>
                      <option>Data</option>
                      <option>Cable</option>
                      <option>BulkSMS</option>
                      <option>Electricity</option>
                  </select>
                  <label for="amount">Variation:</label>
                  <input type="number" name="variation" class="form-control" id="editBillerVariation" required/>
                  <div class="mt-2">
                      <button type="button" class="btn secondary-btn" data-bs-dismiss="modal" onclick="closeModal('editBillerModal')">Close</button>
                      <button type="submit" class="btn primary-btn">Submit</button>
                  </div>
              </form>
          </div>

        </div>
      </div>
  </div>
  {{-- delete biller --}}
  <div class="modal" id="deleteBillerModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal body -->
        <div class="modal-body">
            <h2>Delete Biller</h2>
            <form action="{{route('admin.billers.delete')}}" method="post">
                @csrf
                <label for="amount">Admin Email:</label>
                <input type="email" name="email" class="form-control" required/>
                <label for="amount">Admin Password:</label>
                <input type="password" name="adminPassword" class="form-control" required/>
                <input type="hidden" id="id" name="adminId" value="{{ auth()->user()->id}}" class="form-control" />
                <input type="hidden" id="billerId" name="id"  class="form-control" />
                <div class="mt-2">
                    <button type="button" class="btn secondary-btn" data-bs-dismiss="modal" onclick="closeModal('deletebillerModal')">Close</button>
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

    
  function editBiller(id, title, service, variation) {
    // set the values in the modal
    document.getElementById('editBillerId').value = id;
    document.getElementById('editBillerTitle').value = title;
    document.getElementById('editBillerService').text = service;
    document.getElementById('editBillerVariation').value = variation;
      // alert(status);
    // open the modal
    const modal = new bootstrap.Modal(document.getElementById('editBillerModal'));
      modal.show();
  }
  function deleteBiller(id) {
    // set the values in the modal
    document.getElementById('billerId').value = id;
      // alert(status);
    // open the modal
    const modal = new bootstrap.Modal(document.getElementById('deleteBillerModal'));
      modal.show();
  }
    
  </script>
</body>
</html>