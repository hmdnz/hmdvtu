
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Payments | ZaumaData</title>

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

  @php( $page = 'payments')
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
                                <h1>Wallet Fundings</h1>
                                  <p>Wallet Funding History</p>
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
                    <h4 class="card-title">All Payments ({{$paymentCounts}})</h4>
                  </div>
                </div>
    
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                      <thead>
                          <tr>
                              <th>S/N</th>
                              <th>User</th>
                              <th>Gateway</th>
                              <th>Channel</th>
                              <th>Reference</th>
                              <th>Balance Before</th>
                              <th>Amount</th>
                              <th>Balance After</th>
                              <th>Status</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                        @php($count = 1)
                        @foreach($payments as $payment)
                        <tr>
                          <td>{{$count}}</td>
                          <td>{{isset($payment->userID)?$payment->user->username:''}}</td>
                          <td>{{$payment->gateway}}</td>
                          <td>{{$payment->channel}}</td>
                          <td>{{$payment->reference}}</td>
                          <th>{{formatPrice($payment->balanceBefore, '₦', '2')}}</th>
                          <th>{{formatPrice($payment->amount, '₦', '2')}}</th>
                          <th>{{formatPrice($payment->balanceAfter, '₦', '2')}}</th>
                          <td>{{$payment->status}}</td>
                          {{-- <td>
                              <a href="{{route('admin.showPayment',[$payment->id])}}" class="btn primary-btn"><i class="fa fa-eye"></i></a>
                          </td> --}}
                          <td>
                            <div class="dropdown">
                              <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton{{ $payment->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                  <i class="fa fa-ellipsis-v"></i>
                              </button>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $payment->id }}">
                                  <li>
                                      <a class="dropdown-item" href="{{ route('admin.showPayment', [$payment->id]) }}">
                                          <i class="fa fa-eye"></i> View Payment
                                      </a>
                                  </li>
                                  <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#requeryPaymentModal" 
                                        data-reference="{{ $payment->reference }}">
                                        <i class="fa fa-sync"></i> Requery Payment
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
  @include('admin.history.partials.RequeryPaymentModal')
  
    
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
      var requeryModal = document.getElementById('requeryPaymentModal');

      // When modal is shown, update reference
      requeryModal.addEventListener('show.bs.modal', function(event) {
        console.log("Modal is opening");
          var button = event.relatedTarget;  // Button that triggered the modal
          var reference = button.getAttribute('data-reference'); // Get reference from button
          console.log(reference);

          document.getElementById('paymentReference').textContent = reference;
          document.getElementById('requeryButton').setAttribute('data-reference', reference);
      });

      // Handle Requery API Request
      document.getElementById('requeryButton').addEventListener('click', function() {
          var reference = this.getAttribute('data-reference');

          // Make API Request
          fetch("{{ route('admin.requeryPayment') }}", {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: JSON.stringify({ reference: reference })
          })
          .then(response => response.json())
          .then(data => {
              if (data.status) {
                document.getElementById('paymentStatus').textContent = data.code;
                document.getElementById('paymentMessage').textContent = data.message;
              } else {
                document.getElementById('paymentStatus').textContent = data.code;
                document.getElementById('paymentMessage').textContent = data.message;
              }
          })
          .catch(error => console.error('Error:', error));
      });
    });
    
  </script>
</body>
</html>
