
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>{{ env("APP_NAME")}} | Switches </title>

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

    @php( $page = 'switches')
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
                                <h1>Provider Switches</h1>
                                <p>Switch for all service providers on the platform</p>
                                </div>    
                                
                              <div>
                                <a type="button" data-bs-toggle="modal" data-bs-target="#addSwitchModal" class="btn secondary-btn">
                                    <i class="fa fa-plus"></i>
                                    Add Switch
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
                    <h4 class="card-title">Provider Switches</h4>
                    </div>
                </div>
    
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                        <thead>
                            <tr>
                            <th>S/N</th>
                            <th>Service</th>
                            <th>Context Type</th>
                            <th>Context Value</th>
                            <th>Provider</th>
                            <th>status</th>
                            <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="users-table-body">
                            @php($count = 1)
                            @foreach($switches as $switch)
                                <tr>
                                    <td>{{$count}}</td>
                                    <td>{{$switch->service->title ?? 'Unknown'}}</td>
                                    <td>{{$switch->context_type}}</td>
                                    <td>
                                        @if($switch->context_type === 'category')
                                            Category: <strong>{{ ucfirst($switch->category_title) }}</strong><br>
                                            Biller: <strong>{{ $switch->biller->title ?? 'Unknown' }}</strong>
                                        @elseif($switch->context_type === 'biller')
                                            Biller: <strong>{{ $switch->biller->title ?? 'Unknown' }}</strong>
                                        @elseif($switch->context_type === 'service')
                                            Service: <strong>{{ $switch->service->title ?? 'Unknown' }}</strong>
                                        @endif
                                    </td>
                                    <td>{{$switch->provider->title ?? "N/A"}}</td>
                                    <td>{{$switch->status}}</td>
                                    <td>
                                        <a class="btn primary-btn px-2" data-bs-toggle="modal" data-bs-target="#editSwitchModal{{ $switch->id }}">
                                            <i class="fa fa-edit small"></i>
                                        </a>
                                        <a class="btn btn-danger px-2" data-bs-toggle="modal" data-bs-target="#deleteSwitchModal{{ $switch->id }}">
                                            <i class="fa fa-trash small"></i>
                                        </a>
                                    </td>
                                </tr>
                                @php($count++)
                                <!-- Modal -->
                                <div class="modal fade" id="editSwitchModal{{ $switch->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $switch->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                    <form action="{{ route('admin.switches.update', $switch->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $switch->id }}">Edit Default Provider</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                
                                        <div class="modal-body">
                                            <!-- Service Provider Select -->
                                            <div class="mb-3">
                                            <label for="service_provider_id_{{ $switch->id }}" class="form-label">Service Provider</label>
                                            <select name="service_provider_id" id="service_provider_id_{{ $switch->id }}" class="form-select" required>
                                                @foreach($providers as $provider)
                                                <option value="{{ $provider->id }}" {{ $switch->provider_id == $provider->id ? 'selected' : '' }}>
                                                    {{ $provider->title }} ({{ $provider->status }})
                                                </option>
                                                @endforeach
                                            </select>
                                            </div>
                                        </div>
                                
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn primary-btn">Update</button>
                                        </div>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                                @include('admin.others.partials.delete_switch_modal')
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

    {{-- MODAL --}}
    @include('admin.others.partials.add_switch_modal')

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

    function deleteSwitch(id) {
      // set the values in the modal
      document.getElementById('deleteSwitchId').value = id;
      // open the modal
      const modal = new bootstrap.Modal(document.getElementById('deleteSwitchModal'));
        modal.show();
    }
    
  </script>
</body>
</html>