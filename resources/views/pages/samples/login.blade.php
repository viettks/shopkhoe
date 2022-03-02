<!DOCTYPE html>
<html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Skydash Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css')}}">
    <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.png')}}" />
  </head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="../../images/logo.svg" alt="logo">
              </div>
              <h4>Hello! let's get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <form class="pt-3">
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" id="Username" placeholder="Username">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="Password" placeholder="Password">
                </div>
                <div class="mt-3">
                  <a class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" id="btnLogin">SIGN IN</a>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input">
                      Keep me signed in
                    </label>
                  </div>
                  <a href="#" class="auth-link text-black">Forgot password?</a>
                </div>
                <div class="mb-2">
                  <button type="button" class="btn btn-block btn-facebook auth-form-btn">
                    <i class="ti-facebook mr-2"></i>Connect using facebook
                  </button>
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Don't have an account? <a href="register.html" class="text-primary">Create</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{ asset('vendor.bundle.base.js')}}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{ asset('js/off-canvas.js')}}"></script>
  <script src="{{ asset('js/hoverable-collapse.js')}}"></script>
  <script src="{{ asset('js/template.js')}}"></script>
  <script src="{{ asset('js/settings.js')}}"></script>
  <script src="{{ asset('js/todolist.js')}}"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <!-- endinject -->
  <script>
    $(document).ready(function() {
      $("#btnLogin").click(function(){
        if($("#Username").val() == null || $("#Username").val() == ""){
          alert("Vui lòng nhập số điện thoại");
          return;
        }

        if($("#Password").val() == null || $("#Password").val() == ""){
          alert("Vui lòng nhập mật khẩu!");
          return;
        }

        $.ajax({
        type: "POST"
        ,url: "/api/login"
        ,data: { user_name: $("#Username").val(), password : $("#Password").val() }
        ,dataType: "json"
        ,success: function(data) {
          var code = data.code;
          if(code!= 200){
            alert(data.message);
          }
          else{
            window.location.href = '/' + data.url; 
          }
        }
        ,error: function(xhr) {

        }
    });
      });
    });

  </script>
</body>

</html>
