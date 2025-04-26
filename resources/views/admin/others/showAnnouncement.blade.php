
<!Doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Announcement | ZaumaData</title>

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

  @php( $page = 'announcements')
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
                                <h1>Announcement</h1>
                                  <p>View & Edit Announcements</p>
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
              <div class="col-sm-6 mx-auto">
                <div class="card">
                  <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                      <h4 class="card-title">View Announcement</h4>
                    </div>
                  </div>
      
                  <div class="card-body">
                    <form action="{{route('admin.editAnnouncement', [$announcement->id])}}" method="post">
                        @csrf
                        <input type="hidden" id="editannouncementId" value="{{ $announcement->id }}" name="id">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-label" for="fname">Title:</label>
                                <input type="text" class="form-control" name="title" id="title" value="{{ $announcement->title }}" required>
                                @error('title')
                                <span class="text-danger small">{{ $message}}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="fname">Body:</label>
                                <textarea class="form-control p-0" name="body" id="body" maxlength="250" rows="5" required>
                                  {{ $announcement->body }}
                                </textarea>
                                @error('body')
                                <span class="text-danger small">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>
                        <center>
                            <button type="submit" class="btn primary-btn" id="submitAddAdmin">Update Admin</button>
                        </center>
                    </form>
                  </div>
                </div>
              </div>
          </div>
      </div>
      @include('components.admin.footer')
  </main>

    <!-- Modal -->
    <!-- add announcement modal -->
    <div class="modal" id="addAnnouncementModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
              <h2>Add Announcement</h2>
                <form method="POST" action="{{route('admin.addAnnouncement')}}">
                    @csrf
                    <label for="amount">Title:</label>
                    <input type="text" id="title" name="title" class="form-control" required/>
                    <label for="amount">Body:</label>
                    <textarea name="body" class="form-control" required></textarea>
                    
                    <button type="submit" class="btn primary-btn m-3">Submit</button>
                    <button type="button" class="btn secondary-btn m-3" data-dismiss="modal" onclick="closeModal2('addAnnouncementModal')">Close</button>
                </form>
            </div>
            
  
            <!-- Modal footer -->
            <div class="modal-footer">
            </div>
          </div>
        </div>
      </div>
  
      <!-- edit announcement modal -->
      <div class="modal" id="editAnnouncementModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
              <h2>Edit Announcement</h2>
              <form id="editAnnouncementForm">
                <label for="amount">Title:</label>
                <input type="text" id="editAnnouncementTitle" name="title" class="form-control" />
                <input type="hidden" id="editAnnouncementId" name="announcementId" class="form-control" />
                <label >Body:</label>
                <textarea id="editAnnouncementBody" name="body" class="form-control"></textarea>
            </div>
            </form>
  
            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn secondary-btn" data-dismiss="modal" onclick="closeModal('editAnnouncementModal')">Close</button>
              <button type="button" class="btn primary-btn" onclick="updateAnnouncement(event)">Update</button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- view announcement modal -->
      <div class="modal" id="viewAnnouncementModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h3>View Announcement</h3>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
              <h5>Title : <span id="viewAnnouncementTitle"></span></h5>
              <p>Body : <span id="viewAnnouncementBody"></span></p>
              <p>Status : <span id="viewAnnouncementStatus"></span></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn secondary-btn" data-dismiss="modal" onclick="closeModal('viewAnnouncementModal')">Close</button>
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
    
  </script>
</body>
</html>