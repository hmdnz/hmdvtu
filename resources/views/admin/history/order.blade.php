
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Order | ZaumaData</title>

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
        </div>
        <div class="conatiner-fluid content-inner mt-n5 py-0">
            <div class="row">
                <div class="col-md-7 mx-auto py-1" >
                    <div id="viewOrderInfo">
                        <div class="card  border mt-5 p-1">
                            <div class="card-header ">
                                <div class="header-title text-center">
                                    <img class="rounded-circle" src="{{ asset('main/img/logo-hr.png') }}" width="80" height="80">
                                    <h4 class="card-title text-center text-md-1 text-sm-5">Order Details</h4>
                                </div>
                            </div>
                            <div class="card-body p-1">
                                <ul class="list-group list-group-flush small-text">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>
                                            <b>Reference:</b> <span id="code">{{$order->reference}}</span>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>
                                            <b>Service:</b> <span id="type">{{$order->service}}</span>
                                        </div>
                                        <div>
                                            <b>Package:</b> <span id="code">{{$order->package->title}}</span>
                                        </div>
                                    </li>
                                    @if($order->service == 'Airtime')
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>Beneficiary :</b> <span id="amount">{{$order->beneficiary}}</span>
                                            </div>
                                        </li>
                                    @endif
                                    @if($order->service == 'Data')
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>size:</b><span id="amount">{{$order->package->size}}</span>
                                            </div>
                                            <div>
                                                <b>Validity:</b> <span >{{$order->package->validity}}</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>Beneficiary :</b> <span id="amount">{{$order->beneficiary}}</span>
                                            </div>
                                        </li>
                                    @endif
                                    @if($order->service == 'Data Card')
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>size:</b><span id="amount">{{$order->package->size}}</span>
                                            </div>
                                            <div>
                                                <b>Validity:</b> <span >{{$order->package->validity}}</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>Pin :</b> <span id="amount">{{$order->token}}</span>
                                            </div>
                                        </li>
                                    @endif
                                    @if($order->service == 'Bulk SMS')
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>Quantity:</b><span id="amount">{{$order->quantity}}</span>
                                            </div>
                                            <div>
                                                <b>SenderID:</b> <span >{{$order->smsSender}}</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>Message:</b><span>{{$order->smsMessage}}</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>Beneficiary :</b> <span id="amount">{{$order->beneficiary}}</span>
                                            </div>
                                        </li>
                                    @endif
                                    @if($order->service == 'Electricity')
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>DISCO : </b><span>{{$order->biller}}</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>Meter Type : </b> <span >{{$order->tokenType}}</span>
                                            </div>
                                            <div>
                                                <b>Meter No. : </b><span id="amount">{{$order->tokenMeterNo}}</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>Token : </b><span>{{$order->token}}</span>
                                            </div>
                                        </li>
                                    @endif
                                    @if($order->service == 'Cable')
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>Biller : </b><span>{{$order->biller}}</span>
                                            </div>
                                            <div>
                                                <b>IUC No. : </b><span id="amount">{{$order->tokenMeterNo}}</span>
                                            </div>
                                        </li>
                                    @endif
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>
                                            <b>Amount:</b> &#8358;<span id="amount">{{number_format($order->total, 2, '.', ',')}}</span>
                                        </div>
                                        <div>
                                            <b>Status:</b> <span >{{$order->status}}</span>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>
                                            <b>Date:</b> <span >{{$order->created_at}}</span>
                                        </div>
                                    </li>
                                    @if(isset($order->transaction))
                                        <li class="list-group-item d-flex justify-content-center">
                                            <div class="text-center">
                                                <h4>Transaction Details</h4>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>Reference:</b> <span id="code">{{$order->transaction->reference}}</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>Note:</b> <span id="total">{{$order->transaction->note}}</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>Type:</b><span id="total">{{$order->transaction->type}}</span>
                                            </div>
                                            <div>
                                                <b>Total Paid:</b> &#8358;<span id="total">{{number_format($order->transaction->amount, 2, '.', ',')}}</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>Balance Before:</b> &#8358;<span>{{number_format($order->transaction->balanceBefore, 2, '.', ',')}}</span>
                                            </div>
                                            <div>
                                                <b>Balance After:</b> &#8358;<span id="total">{{number_format($order->transaction->balanceAfter, 2, '.', ',')}}</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                                <b>Status:</b> <span >{{$order->transaction->status}}</span>
                                            </div>
                                            <div>
                                                <b>Date:</b> <span>{{$order->transaction->created_at}}</span>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                                <div class="p-2 mt-4 border-1 border-top">
                                    <h6>Provider:</h6>
                                    <p>{{$order->provider}}</p>

                                    <h6>Provider Reference:</h6>
                                    <p>{{$order->responseAPI}}</p>

                                    <h6>Response Message:</h6>
                                    <p>{{$order->responseMessage}}</p>
                                    
                                    <h6>Response Body:</h6>
                                    <p>{{$order->responseBody}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <center>
                        {{-- <button class="btn primary-btn" onclick="printContent('viewOrderInfo');">Print</button>
                        <button class="btn primary-btn" id="btnPrint">Save</button> --}}
                    </center>
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
  {{-- switch js --}}
  <script src="{{ asset('admin/js/switch.js') }}"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
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
    function printContent(el) {
            var restorepage = $('body').html();
            var printcontent = $('#' + el).clone();
            $('body').empty().html(printcontent);
            window.print();
            window.location.reload(true);
      }

      document.getElementById('btnPrint').addEventListener('click',
            Export);

        function Export() {
            html2canvas(document.getElementById('viewOrderInfo'), {
                onrendered: function(canvas) {
                    var data = canvas.toDataURL();
                    var docDefinition = {
                        content: [{
                            image: data,
                            width: 500
                        }]
                    };
                    pdfMake.createPdf(docDefinition).download("ZaumaData-Order.pdf");
                }
            });
        }

  </script>
</body>
</html>
