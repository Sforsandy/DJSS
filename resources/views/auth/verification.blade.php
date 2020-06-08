<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'Laravel') }} | Verification</title>
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
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <!-- <a href="{{ url('/') }}"><b>{{ config('app.name', 'Laravel') }}</b></a> -->
    <img src="{{ URL::asset('public/image/logo-prime-flat.png') }}">
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Verify mobile number</p>
    
    
    <form id="sendOtpForm" method="POST" role="form" onsubmit="return false">
      {{ csrf_field() }}
      <div class="form-group">
        <input type="text" class="form-control OnlyNumber" autocomplete="new_mobile_no" name="mobile_no" id="mobile_no" placeholder="Mobile Number" maxlength="10">
      </div>
      <div class="form-group otp_number_div">
        <input type="text" class="form-control OnlyNumber" name="otp_number" id="otp_number" placeholder="4 digit OTP" maxlength="4">
        <input type="hidden" class="form-control" name="token" id="token">
        <input type="hidden" class="form-control" name="id" id="id" value="{{ session('user') }}">
      </div>
      <div class="row">
        <div class="col-xs-12 pull-right">
          <button type="button" class="btn btn-primary btn-block btn-flat VerifyForm" id="submitSendOtpForm" class="text-center">Send OTP</button >
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
    var BASE_URL = '';
    (function() {
      BASE_URL  = '{{ url('/') }}';
    })();
  $(function () {
    $(".otp_number_div").hide();
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
  $(document).on('keypress', '.OnlyNumber', function(event) {
     if (event.which != 8 && event.which != 0 && (event.which < 48 || event.which > 57)) {
      
        return false;
    }
});
// ONLY NUMBER INPUT END
function ShowSuccess(Messages) {
  if($.isPlainObject(Messages))
  {
    var MsgList = '<ui>';
    $.each(Messages, function( index, value ) {
      MsgList += '<li>'+value[0]+'</li>';
    });
    MsgList += '</ui>';
    toastr.success(MsgList);
  }else{toastr.success(Messages);}
}
function ShowError(Messages) {
  if($.isPlainObject(Messages))
  {
    var MsgList = '<ui>';
    $.each(Messages, function( index, value ) {
      MsgList += '<li>'+value[0]+'</li>';
    });
    MsgList += '</ui>';
    toastr.error(MsgList);
  }else{toastr.error(Messages);}
  
}
$(document).on('click', '#submitSendOtpForm', function() {
    var formData = new FormData($('#sendOtpForm')[0]);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      url: '{{ route("verifymobile.send_otp") }}',
      data: {
        '_token': $('input[name=_token]').val()
        ,'mobile_no' : $("#mobile_no").val()
      },
      beforeSend: function() {
        $('.loadingoverlay').css('display', 'block');
      },
      success: function(response) {
        if ((response.success == 1)) {
          ShowSuccess(response.message);
          $(".otp_number_div").show();
          $("#token").val(response.token);
          $("#mobile_no").attr('readonly','readonly');
          $(".VerifyForm").attr('id','submitVerifyForm');
          $('.VerifyForm').text('Submit OTP');
        } else {
         ShowError(response.message);
        }
      },
      complete: function() {
        $('.loadingoverlay').css('display', 'none');
      },
    });
  });

$(document).on('click', '#submitVerifyForm', function() {
    var formData = new FormData($('#sendOtpForm')[0]);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      url: '{{ route("verifymobile.verify_otp") }}',
      data: {
        '_token': $('input[name=_token]').val()
        ,'mobile_no' : $("#mobile_no").val()
        ,'otp_number' : $("#otp_number").val()
        ,'token' : $("#token").val()
        ,'id' : $("#id").val()
      },
      beforeSend: function() {
        $('.loadingoverlay').css('display', 'block');
      },
      success: function(response) {
        if ((response.success == 1)) {
          ShowSuccess(response.message);
          setTimeout(function(){
            var reqUrl = response.reqUrl;
            if(reqUrl == '' || reqUrl == null)
            {
              reqUrl = 'events';
            }
            window.location.href = BASE_URL+'/'+reqUrl;
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
{!! Toastr::render() !!}