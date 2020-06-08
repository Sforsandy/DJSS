<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'Laravel') }} | Forgot password</title>
  <link rel="icon" href="{{ URL::asset('public/image/logo-icon.png') }}" type="image/gif" sizes="16x16">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  {{ Html::style('public/app-assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}
  <!-- Font Awesome -->
  {{ Html::style('public/app-assets/bower_components/font-awesome/css/font-awesome.min.css') }}
  <!-- Ionicons -->
  {{ Html::style('public/app-assets/bower_components/Ionicons/css/ionicons.min.css') }}
  <!-- Theme style -->
  {{ Html::style('public/app-assets/dist/css/AdminLTE.min.css') }}
  <!-- iCheck -->
  {{ Html::style('public/app-assets/plugins/iCheck/square/blue.css') }}
  <!-- Toaster -->
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
<div class="login-box">
  <div class="login-logo">
    <!-- <a href="{{ url('/') }}"><b>{{ config('app.name', 'Laravel') }}</b></a> -->
    <img src="{{ URL::asset('public/image/logo-prime-flat.png') }}">
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Forgot Password? Enter your email id to retrieve it!!</p>

    <form method="POST" role="form" id="ForgotPasswordForm" onsubmit="return false">
    	{{ csrf_field() }}
      <!-- <div class="form-group has-feedback">
        <input type="text" class="form-control" name="mobile_no" placeholder="Mobile Number">
      </div> -->
      <div class="form-group">
        <input type="email" class="form-control" name="email" placeholder="Email">
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat" id="submitForgotPasswordForm">Submit</button>
          <a href="{{ url('/login') }}" class="btn btn-primary btn-block btn-flat">Login</a>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
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
<!-- jQuery 3 -->
{{ Html::script('public/app-assets/bower_components/jquery/dist/jquery.min.js') }}
<!-- Bootstrap 3.3.7 -->
{{ Html::script('public/app-assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}
<!-- iCheck -->
{{ Html::script('public/app-assets/plugins/iCheck/icheck.min.js') }}
<!-- Toastr -->
{{ Html::script('public/app-assets/plugins/toastr/toastr.min.js') }}
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });

  $(document).on('click', '#submitForgotPasswordForm', function() {
    var formData = new FormData($('#ForgotPasswordForm')[0]);
    $.ajax({
      type: 'POST',
      url: '{{ route("auth.forgotpassword") }}',
      processData: false,
      contentType: false,
      data: formData,
      beforeSend: function() {
        $('.loadingoverlay').css('display', 'block');
      },
      success: function(data) {
        if ((data.success == 1)) {
          toastr.success('New password send you email.');
          setTimeout(function(){
            window.location.href = BASE_URL+"/login";
          }, 2000);
          
        } else {
          var alrtList = '<ui>';
          $.each(data.errors, function( index, value ) {
            alrtList += '<li>'+value[0]+'</li>';
          });
          alrtList += '</ui>';
          toastr.error(alrtList);
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
{!! Toastr::render() !!}