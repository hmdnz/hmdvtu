
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Orders | ZaumaData</title>

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

  @php( $page = 'orders')
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
                                <h1>Orders ({{$orderCounts}})</h1>
                                  <p>Order History</p>
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
                    {{-- <h4 class="card-title">All Orders ({{$orderCounts}})</h4> --}}
                  </div>
                </div>
    
                <div class="card-body p-0">
                  <div class="container col-12 row filter">
                      <h6 class="mb-1">Filter</h6>
                      <div class="col-md-4 col-6 form-group">
                          <select class="form-control" id="statusFilter">
                              <option value="">All Status</option>
                              <option value="Initiated">Initiated</option>
                              <option value="Pending">Pending</option>
                              <option value="Failed">Failed</option>
                              <option value="Completed">Completed</option>
                          </select>
                      </div>
                      <div class="col-md-4 col-6 form-group">
                          <select class="form-control" id="providerFilter">
                              <option value="">All Providers</option>
                              <option value="SMEPlug">SMEPlug</option>
                              <option value="AlrahuzData">AlrahuzData</option>
                              <option value="EasyAccessAPI">EasyAccessAPI</option>
                          </select>
                      </div>
                      <div class="col-md-4 col-6 form-group">
                          <select class="form-control" id="serviceFilter">
                              <option value="">All Services</option>
                              <option value="Airtime">Airtime</option>
                              <option value="Data">Data</option>
                              <option value="Cable">Cable</option>
                              <option value="Bulk SMS">Bulk SMS</option>
                          </select>
                      </div>
                  </div>
                  <div class="table-responsive">
                    <table id="example" class="table table-striped" data-toggle="data-table">
                      <thead>
                          <tr>
                              <th>S/N</th>
                              <th>User</th>
                              <th>Provider</th>
                              <th>Reference</th>
                              <th>Provider Ref</th>
                              <th>Service</th>
                              <th>Package</th>
                              <th>Amount</th>
                              <th>Total</th>
                              <th>Status</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                        @php($count = 1)
                        @foreach($orders as $order)
                        <tr>
                          <td>{{$count}}</td>
                          <td>{{isset($order->userID)?$order->user->username:''}}</td>
                          <td>{{$order->provider}}</td>
                          <td>{{$order->reference}}</td>
                          <td>{{$order->responseAPI}}</td>
                          <td>{{$order->service}}</td>
                          <td>{{isset($order->packageID)?$order->package->title:''}}</td>
                          <td>&#8358; {{number_format($order->price, 2, '.', ',')}}</td>
                          <td>&#8358; {{number_format($order->total, 2, '.', ',')}}</td>
                          <td>{{$order->status}}</td>
                          <td>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton{{ $order->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $order->id }}">
                                    @if($order->status == 'Initiated' || ($order->status == 'Pending' || $order->status == 'pending') )
                                      @if($order->service == 'Airtime')
                                        <li>
                                          <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#requeryOrderModal" 
                                            data-id="{{ $order->id }}" 
                                            data-provider="{{ $order->provider }}"
                                            data-reference="{{ $order->reference }}"
                                            data-status="{{ $order->status }}"  
                                          >
                                              <i class="fa fa-arrow-down"></i> Requery Airtime
                                          </a>
                                        </li>
                                        @endif
                                        @if($order->service == 'Data')
                                        <li>
                                          <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#requeryOrderModal" 
                                            data-id="{{ $order->id }}" 
                                            {{-- data-provider="{{ $order->provider }}" --}}
                                            data-reference="{{ $order->reference }}"
                                            data-status="{{ $order->status }}"  
                                          >
                                              <i class="fa fa-arrow-down"></i> Requery Data
                                          </a>
                                        </li>
                                        @endif
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{route('admin.showOrder',[$order->id])}}" >
                                            <i class="fa fa-eye"></i> View Order
                                        </a>
                                    </li>
                                </ul>
                            </div>
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
      @include('components.admin.footer')
  </main>
  @include('admin.history.partials.requeryOrderModal')

    
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

    document.addEventListener("DOMContentLoaded", function() {
        var requeryModal = document.getElementById('requeryOrderModal');

        // When modal is shown, 
        requeryModal.addEventListener('show.bs.modal', function(event) {
          console.log("Modal is opening");
            var button = event.relatedTarget;  // Button that triggered the modal
            var reference = button.getAttribute('data-reference');
            var orderStatus = button.getAttribute('data-status');

            document.getElementById('orderReference').textContent = reference;
            document.getElementById('orderStatus').textContent = orderStatus;
            document.getElementById('requeryButton').setAttribute('data-reference', reference);
        });

        // Handle API Request
        document.getElementById('requeryButton').addEventListener('click', function() {
            var button = this;
            var icon = document.getElementById('requeryIcon');
            var text = document.getElementById('requeryText');
            var reference = this.getAttribute('data-reference');
            // Change button to loading state
            button.disabled = true;
            icon.classList.remove('fa-sync');
            icon.classList.add('fa-spinner', 'fa-spin'); // spinning effect
            text.textContent = "Requerying...";

            // Make API Request
            fetch(`/admin/history/orders/requery/${reference}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                // body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                  document.getElementById('orderStatus').textContent = data.code;
                  document.getElementById('responseMessage').textContent = data.message;
                } else {
                  document.getElementById('orderStatus').textContent = data.code;
                  document.getElementById('responseMessage').textContent = data.message;
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                // Restore button state
                button.disabled = false;
                icon.classList.remove('fa-spinner', 'fa-spin');
                icon.classList.add('fa-sync');
                text.textContent = "Requery";
                // Reload page after 3 seconds
                setTimeout(() => {
                    location.reload();
                }, 3000);
            });
        });
    });

    $(document).ready(function () {
        var table = $('#example').DataTable();

        // custom filters 
        $('#statusFilter, #providerFilter, #serviceFilter').on('change', function () {
            table.draw();
        });

        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            var status = $('#statusFilter').val();
            var provider = $('#providerFilter').val();
            var service = $('#serviceFilter').val();
            var rowStatus = data[9]; // index based on status column position
            var rowProvider = data[2]; // index based on providers column position
            var rowService = data[5]; // index based on service column position

            if (
                (status === "" || rowStatus === status) &&
                (provider === "" || rowProvider === provider) &&
                (service === "" || rowService === service) 
            ) {
                return true;
            }
            return false;
        });
    });
    
  </script>
</body>
</html>
