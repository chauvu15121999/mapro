<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mapro Admin</title>
    <base href="{{asset(' ')}}">
    <!-- plugins:css -->
    <link href="public/js/multiple-emails.css" rel="stylesheet">
    <link rel="stylesheet" href="public/backend/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="public/backend/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link href="public/backend/byme/app.css" rel="stylesheet">
    <link rel="stylesheet" href="public/backend/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="public/backend/assets/images/favicon.ico" />
{{-- <script src="public/js/app.js" type="text/javascript" charset="utf-8"  ></script> --}} <script src="public/backend/assets/vendors/js/vendor.bundle.base.js" ></script>
<style>
  body, html {
  height: 100%;
}
</style>
{{-- <script src="public/js/app.js" type="text/javascript" charset="utf-8"></script> --}}
  </head>
  <body>
    <div id="app" class="container-scroller">
      <!-- partial:partials/_navbar.html --> 
      <?php 
			  $teamtype = json_encode($teamtype);
		  ?>
        <App
        :teamtype="{{$teamtype}}"
        :user="{{Auth()->user()}}"
        ></App>
    </div>
    @yield('script')
    <script src="public/js/app.js" type="text/javascript" charset="utf-8" ></script>
    <script src="public/backend/assets/vendors/js/vendor.bundle.base.js" ></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="public/backend/assets/vendors/chart.js/Chart.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="https://rawgit.com/jackmoore/autosize/master/dist/autosize.min.js"></script>
    <script src="public/backend/assets/js/off-canvas.js"></script>
    <script src="public/backend/assets/js/hoverable-collapse.js"></script>
    <script src="public/backend/assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="public/backend/assets/js/dashboard.js"></script>
    <script src="public/backend/assets/js/todolist.js"></script>
    <!-- End custom js for this page -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.js " ></script> --}}
    {{-- <script src="public/backend/byme/app.js"  ></script>  --}}
   {{--  <script src="public/js/multiple-emails.js"></script> --}}


  </body>
  
</html>
