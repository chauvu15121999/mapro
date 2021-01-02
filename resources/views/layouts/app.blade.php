<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mapro</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../public/backend/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../public/backend/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../public/backend/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../public/backend/assets/images/favicon.ico" />
  </head>
  <body id="app-layout">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="../"><img src="../public/backend/assets/images/logo.svg" alt="logo" /></a>
          <a class="navbar-brand brand-logo-mini" href="../"><img src="../public/backend/assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <div class="search-field d-none d-md-block">
            <form class="d-flex align-items-center h-100" action="#">
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>
                </div>
                <input type="text" class="form-control bg-transparent border-0" placeholder="Search projects">
              </div>
            </form>
          </div>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-text">
                  <p class="mb-1 text-black">{{ Auth::user()->user_name }}</p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="{{ url('/logout') }}">
                  <i class="mdi mdi-cached mr-2 text-success"></i> logout </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ url('/') }}">
                  <i class="mdi mdi-logout mr-2 text-primary"></i> Home </a>
              </div>
            </li>
        </div>
      </nav >
       @yield('content')
      <!-- partial -->

    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../public/backend/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../public/backend/assets/vendors/chart.js/Chart.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../public/backend/assets/js/off-canvas.js"></script>
    <script src="../public/backend/assets/js/hoverable-collapse.js"></script>
    <script src="../public/backend/assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="../public/backend/assets/js/dashboard.js"></script>
    <script src="../public/backend/assets/js/todolist.js"></script>
    <!-- End custom js for this page -->
 
  </body>
</html>  
