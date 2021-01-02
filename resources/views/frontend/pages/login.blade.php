<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mapro Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="public/backend/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="public/backend/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="public/backend/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="public/backend/assets/images/favicon.ico" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
           <div class="col-lg-4 d-none d-lg-block">
           		<image style = "position: fixed;"  src="public/frontend/assets/images/login-images1.png"/>
           </div>
            <div class="col-lg-4 col-mx-auto">
              <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                  <img src="public/backend/assets/images/logo.svg">
                </div>
                @if(count($errors) > 0 )
                  @foreach($errors->all() as $arr)
                      <div class="alert alert-danger">
                          {{$arr}} <br>
                      </div>
                  @endforeach
                @endif
                 @if((session('thongbao')))
                   <div class="alert alert-success">
                        {{session('thongbao')}} <br>
                    </div>
                @endif
                <h4>Hello! let's get started</h4>
                <h6 class="font-weight-light">Đăng nhập để tiếp tục vào Mapro</h6>
                <form action="login.html" method="post" class="pt-3">
                	@csrf
                  <div class="form-group">
                    <input type="email" name="Email" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Email">
                  </div>
                  <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password">
                  </div>
                  <div class="mt-3">
                    <input type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn"  value="Đăng nhập" />
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input"> nhớ tài khoản của tôi</label>
                    </div>
                    <a href="#" class="auth-link text-black">Quên mật khẩu?</a>
                  </div>
                  <div class="mb-2">
                    <button onclick="window.location.href='auth/google'" style="width: 100%;" type="button" class="btn btn-social-icon btn-google">Đăng nhập với  GOOGLE <i style="margin-left: 10px;" class="mdi mdi-google-plus"></i></button>
                  </div>
                  <div class="text-center mt-4 font-weight-light"> Chưa có tài khoản? <a href="register.html" class="text-primary">Đăng ký</a>
                  </div>
                </form>
              </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block">
           		<image style = "position: fixed;"  src="public/frontend/assets/images/login-images2.png"/>
           </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
	<script src="public/backend/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="public/backend/assets/js/off-canvas.js"></script>
    <script src="public/backend/assets/js/hoverable-collapse.js"></script>
    <script src="public/backend/assets/js/misc.js"></script>
    <!-- endinject -->
  </body>
</html>