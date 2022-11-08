<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
  <!-- BEGIN: Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Oasis Connect">
    <meta name="keywords" content="oasis connect template, web app">
    <meta name="author" content="OASIS">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('uploads/favicon.png') }}">
    <title>Oasis Connect</title>
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
     <!-- BEGIN:datatable-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/daterange/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
    
    <!-- END: datatable-->
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap-extended.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/colors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/components.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/dark-layout.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/semi-dark-layout.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('app-assets/css/bootstrap-select.css') }}"> -->

    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.min.css') }}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <!-- END: Custom CSS-->

  </head>
  <!-- END: Head-->

  <!-- BEGIN: Body-->
  <body class="vertical-layout vertical-menu-modern dark-layout 2-columns  
    navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" 
    data-col="2-columns" data-layout="dark-layout">

    <!-- BEGIN: Header-->
    <div class="header-navbar-shadow"></div>
    <nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu 
      fixed-top navbar-dark">
      <div class="navbar-wrapper">
        <div class="navbar-container content">
          <div class="navbar-collapse" id="navbar-mobile">
            <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
              <ul class="nav navbar-nav">
                <li class="nav-item mobile-menu d-xl-none mr-auto">
                  <a class="nav-link nav-menu-main menu-toggle hidden-xs" 
                    href="javascript:void(0);">
                    <i class="ficon bx bx-menu"></i>
                  </a>
                </li>
              </ul>
                
            </div>
            
            <ul class="nav navbar-nav float-right">
                
              <li class="nav-item d-none d-lg-block">
                <a class="nav-link nav-link-expand">
                  <i class="ficon bx bx-fullscreen"></i>
                </a>
              </li>
                
              <li class="dropdown dropdown-user nav-item">
                <a class="dropdown-toggle nav-link dropdown-user-link" 
                  href="javascript:void(0);" 
                  data-toggle="dropdown">
                  <div class="user-nav d-sm-flex d-none">
                    <span class="user-name">{{ Session::get('name') }}</span>
                  </div>
                  <span>
                    <img class="round" src="{{ Session::get('avatar') ? Session::get('avatar') :
                        asset('uploads/default-dp.png') }}" 
                      alt="avatar" height="40" width="40">
                  </span>
                </a>
                
                <div class="dropdown-menu dropdown-menu-right pb-0">
                  <a class="dropdown-item" href="{{route('user-profile')}}">
                    <i class="bx bx-user mr-50"></i> Edit Profile
                  </a>
                  <div class="dropdown-divider mb-0"></div>
                  <a class="dropdown-item" href="{{route('logout')}}">
                    <i class="bx bx-power-off mr-50"></i> Logout
                  </a>
                </div>
              </li>

            </ul>
          </div>
        </div>
      </div>
    </nav>
    <!-- END: Header-->

    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" 
      data-scroll-to-active="true">
      <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item mr-auto">
            <a class="navbar-brand" href="{{ route('project', 1) }}">
              <div class="brand-logo">
                <img class="logo" src="{{ asset('app-assets/images/logo/logo-icon.png') }}" />
              </div>
              <h2 class="brand-text mb-0">OASIS CONNECT</h2>
            </a>
          </li>

          <li class="nav-item nav-toggle">
            <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
              <i class="bx bx-x d-block d-xl-none font-medium-4 primary toggle-icon"></i>
              <i class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block primary" 
                data-ticon="bx-disc"></i>
            </a>
          </li>
        </ul>
      </div>
      <div class="shadow-bottom"></div>
      <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" 
          data-menu="menu-navigation" data-icon-style="lines">
          <li class=" navigation-header text-truncate text-uppercase">
            <span data-i18n="Dashboard">Dashboard</span>
          </li>
          <li class="{{ (request()->is('project/*')) ? 'active' : '' }} nav-item">
            <a href="{{ route('project', 1) }}">
              <i class="bx bx-home" data-icon="desktop"></i>
              <span class="menu-title" data-i18n="">Dashboard</span>
            </a>
          </li>
          <li class="{{ (request()->is('studio/*')) ? 'active' : '' }} nav-item">
            <a href="{{ route('studio', 1) }}">
              <i class="bx bxs-dashboard" data-icon="dashboard"></i>
              <span class="menu-title" data-i18n="">Studios</span>
            </a>
          </li>
          
          <li class=" navigation-header text-truncate text-uppercase">
            <span data-i18n="Settings">Settings</span>
          </li>
          <!-- <li class=" nav-item"><a href="#" target="_blank"><i class="bx bx-file" data-icon="help"></i><span class="menu-title" data-i18n="">Projects</span></a> -->
          <!-- </li> -->
          <li class="{{ (request()->is('user-settings/*')) ? 'active' : '' }} nav-item">
            <a href="{{ route('user-settings',['page'=>1]) }}" >
              <i class="bx bx-group" data-icon="morph-folder"></i>
              <span class="menu-title" data-i18n="">Users</span>
            </a>
          </li>
          @if (Session::get('role') == 1)
          <li class="{{ (request()->is('settings/*')) ? 'active' : '' }} nav-item">
            <a href="{{ route('time-settings') }}" >
              <i class="bx bx-cog" data-icon="morph-folder"></i>
              <span class="menu-title" data-i18n="">Settings</span>
            </a>
          </li>
          @endif
          <!-- <li class=" nav-item"><a href="#" target="_blank"><i class="bx bx-slider" data-icon="morph-folder"></i><span class="menu-title" data-i18n="">Presets</span></a> -->
          <!-- </li> -->
        </ul>
      </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-12 mb-2 mt-1">
              
          </div>
        </div>
        <div class="content-body">

          @if ($message = Session::get('success'))

            <div class="alert alert-success alert-block alert-fade">

              <button type="button" class="close" data-dismiss="alert">×</button>    

              <strong>{{ $message }}</strong>

            </div>

          @endif

          @if ($message = Session::get('error'))

            <div class="alert alert-danger alert-block alert-fade">

              <button type="button" class="close" data-dismiss="alert">×</button>    

              <strong>{{ $message }}</strong>

            </div>

          @endif
          
          @error('error_msg')
                        <div class="error">{{ $message }}</div>
                    @enderror

          @yield('content')
              
        </div>
      </div>
    </div>
    <!-- END: Content-->    

    @if(Route::is('project') )
    
      @include('layouts/project_modal')
    
    @endif

    @if(Route::is('view-project') )
                    
      @include('layouts/project_view_modal')
    
    @endif

    @if(Route::is('studio') )
    
      @include('layouts/studio_modal')
    
    @endif

    @if(Route::is('view-studio') )
                    
      @include('layouts/studio_view_modal')
    
    @endif

    @if(Route::is('user-settings') )
    
      @include('layouts/user_modal')
      
    @endif

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-dark">
      <p class="clearfix mb-0"><span class="float-left d-inline-block">2021 &copy; OASIS CONNECT</span><span class="float-right d-sm-inline-block d-none">by<a class="text-uppercase" href="#" target="_blank">Oasis</a></span>
        <button class="btn btn-primary btn-icon scroll-top" type="button"><i class="bx bx-up-arrow-alt"></i></button>
      </p>
    </footer>
    <!-- END: Footer-->

    <!-- <script src="{{ asset('app-assets/js/core/libraries/jquery.min.js') }}"></script> -->

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS -->
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/moment.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/pickers/daterange/daterangepicker.js') }}"></script> 
    <script src="{{ asset('app-assets/vendors/js/extensions/moment-timezone.js') }}"></script>
    <!-- <script src="{{ asset('app-assets/vendors/js/extensions/moment-timezone.min.js') }}"></script> -->
    <!-- BEGIN: Page Vendor JS -->


    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('app-assets/js/scripts/configs/vertical-menu-dark.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app-menu.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/components.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/footer.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}" type="text/javascript"></script>
    <!-- END: Theme JS-->
    <script src="https://apis.google.com/js/api:client.js"></script>

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('app-assets/js/scripts/forms/select/form-select2.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/datatables/datatable.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/popover/popover.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/modal/components-modal.min.js') }}"></script>
    <!-- END: Page JS-->

    <!-- page specific scripts -->
    @yield('pagespecificscripts')

    <script>
  
  </script>

  </body>
  <!-- END: Body-->

</html>