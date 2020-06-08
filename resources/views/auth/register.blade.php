<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'Laravel') }} | Register</title>
  <link rel="icon" href="{{ URL::asset('public/image/logo-icon.png') }}" type="image/gif" sizes="16x16">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Bootstrap 3.3.7 -->
  {{ Html::style('public/app-assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}
  <!-- Font Awesome -->
  {{ Html::style('public/app-assets/bower_components/font-awesome/css/font-awesome.min.css') }}
  <!-- Ionicons -->
  {{ Html::style('public/app-assets/bower_components/Ionicons/css/ionicons.min.css') }}
  <!-- Theme style -->
  {{ Html::style('public/app-assets/dist/css/AdminLTE.min.css') }}

  {{ Html::style('public/app-assets/plugins/toastr/toastr.min.css') }}

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    <!-- Common style -->
  {{ Html::style('public/css/common.css') }}
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <script>
    var BASE_URL = '';
    (function() {
      BASE_URL  = '{{ url('/') }}';
    })();
    </script>
</head>
<body class="hold-transition login-page">
<div class="register-box">
  <div class="login-logo">
    <!-- <a href="{{ url('/') }}"></a> -->
    <img src="{{ URL::asset('public/image/logo-prime-flat.png') }}">
  </div>
  <!-- /.login-logo -->
  <div class="register-box-body">
    <p class="register-box-msg">Quick Registration Form</p>

    <form id="userForm" class="FormValidate" method="POST" role="form" onsubmit="return false"> <!-- action="{{ route('register') }}"  -->
        {{ csrf_field() }}
      <div class="form-group">
        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Firstname" maxlength="100" required>
      </div>
      <div class="form-group">
        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Lastname" maxlength="100">
      </div>
      <div class="form-group">
        <input type="email" class="form-control" name="email" autocomplete="new-email" id="email" placeholder="Email" maxlength="100" required>
      </div>
      <div class="form-group">
        <input type="password" class="form-control" name="password" id="password" placeholder="Password" minlength="6" required>
      </div>
      <div class="form-group">
        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm password" minlength="6" required>
      </div>
      <div class="form-group">
        <input type="text" class="form-control OnlyNumber" name="mobile_no" id="mobile_no" placeholder="Mobile no" maxlength="10" required>
      </div>
      <div class="form-group">
        <input type="text" class="form-control" name="character_name" id="character_name" placeholder="Character name" maxlength="100">
      </div>
      <div class="form-group">
        <input type="text" class="form-control" name="refer_code" id="refer_code" placeholder="Refer code" maxlength="8">
      </div>
      <div class="form-group">
        <select class="form-control" name="gender" id="gender" required>
          <option value="">Select gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat" id="submitUserForm">Sign Up</button>
          <a href="{{ url('/login') }}" class="btn btn-primary btn-block btn-flat">Login</a>
        </div>
        <!-- /.col -->
      </div>
    </form>
<br/>
<div style="text-align:center;"><strong>QR CODE</strong><br/>{!! QrCode::size(200)->generate(Request::fullUrl()); !!}</div>

  </div>
  <!-- /.register-box-body -->
</div>
<!-- /.register-box -->
<footer class="custom-footer">
    <div class="col-md-12">
      <div class="col-md-8 pull-left-center">
        <strong>Copyright &copy; <?php echo date('Y');?> <a href="https://www.gamerzbyte.com/" target="_blank">GamerzByte India</a>. All rights reserved.</strong>
      </div>
      <div class="col-md-4 pr-0">
        <!-- <b>Version</b> 2.4.0 -->
        <ul class="nav navbar-nav footer-nav pull-right-center">
          <li class="">
            <a href="https://www.gamerzbyte.com/terms-of-service/" target="_blank" class="">Terms of Service</a>
          </li>
          <li class="">
            <a href="https://www.gamerzbyte.com/privacy-policy/"target="_blank">Privacy Policy</a>
          </li>
        </ul>
      </div>
    </div>
  </footer>

  
<!-- OPT MODAL -->
<div class="modal fade" id="OtpModal" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Please enter OTP to verify your number</h4>
        </div>
        <div class="modal-body">
          <form id="otpForm" method="POST" role="form" onsubmit="return false">
            {{ csrf_field() }}
            <div class="form-group">
              <input type="text" class="form-control OnlyNumber" name="otp_number" id="otp_number" placeholder="4 digit OTP" maxlength="4">
              <input type="hidden" class="form-control" name="token" id="token">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> -->
          <button type="button" class="btn btn-primary" id="submitOtpForm">Submit</button>
        </div>
      </div>
    </div>
  </div>
<!-- OPT MODAL END -->


<!-- jQuery 3 -->
{{ Html::script('public/app-assets/bower_components/jquery/dist/jquery.min.js') }}
<!-- Bootstrap 3.3.7 -->
{{ Html::script('public/app-assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}
<!-- validate -->
{{ Html::script('public/app-assets/plugins/jquery-validation/jquery.validate.js') }}
<!-- iCheck -->
{{ Html::script('public/app-assets/plugins/iCheck/icheck.min.js') }}
<!-- bootstrap datepicker -->
{{ Html::script('public/app-assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}
<!-- bootstrap time picker -->
{{ Html::script('public/app-assets/plugins/timepicker/bootstrap-timepicker.min.js') }}
<!-- Toastr -->
{{ Html::script('public/app-assets/plugins/toastr/toastr.min.js') }}
<!-- Common js -->
{{ Html::script('public/js/common.js') }}
<script>
  $(function () {
   
  });

  $(document).on('click', '#submitUserForm', function() {
    var isvalidate=$("#userForm").valid();
    if(isvalidate == false){
      return false;
    }
    var formData = new FormData($('#userForm')[0]);
    $.ajax({
      type: 'POST',
      url: '{{ route("auth.validate") }}',
      processData: false,
      contentType: false,
      data: formData,
      beforeSend: function() {
        $('.loadingoverlay').css('display', 'block');
      },
      success: function(response) {
        if ((response.success == 1)) {
          $("#otp_number").val('');
          $("#token").val('');
          $("#token").val(response.token);
          $("#OtpModal").modal("show");
          
        } else {
          ShowError(response.message);
          // var alrtList = '<ui>';
          // $.each(response.errors, function( index, value ) {
          //   alrtList += '<li>'+value[0]+'</li>';
          // });
          // alrtList += '</ui>';
          // toastr.error(alrtList);
        }
      },
      complete: function() {
        $('.loadingoverlay').css('display', 'none');
      },
    });
  });

  $(document).on('click', '#submitOtpForm', function() {
    var formData = new FormData($('#otpForm')[0]);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      url: '{{ route("auth.register") }}',
      data: {
        '_token': $('input[name=_token]').val()
        ,'otp_number': $('#otp_number').val()
        ,'token': $('#token').val()
        ,'firstname' : $("#firstname").val()
        ,'lastname' : $("#lastname").val()
        ,'email' : $("#email").val()
        ,'password' : $("#password").val()
        ,'password_confirmation' : $("#password_confirmation").val()
        ,'mobile_no' : $("#mobile_no").val()
        ,'gender' : $("#gender").val()
        ,'refer_code' : $("#refer_code").val()
      },
      beforeSend: function() {
        $('.loadingoverlay').css('display', 'block');
      },
      success: function(response) {
        if ((response.success == 1)) {
          $("#OtpModal").modal("hide");
          ShowSuccess(response.message);
          setTimeout(function(){
            window.location.href = BASE_URL+"/login";
          }, 2000);
          
        } else {
         ShowError(response.message);
        }
      },
      complete: function() {
        $('.loadingoverlay').css('display', 'none');
      },
    });
  });
</script>
</body>
</html>
