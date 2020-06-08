<!DOCTYPE html >
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'Laravel') }} | @yield('title')</title>
  <link rel="icon" href="{{ URL::asset('public/image/logo.png') }}" type="image/gif" sizes="16x16">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Bootstrap 3.3.7 -->
  {{ Html::style('public/app-assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}
  <!-- Font Awesome -->
  {{ Html::style('public/app-assets/bower_components/font-awesome/css/font-awesome.min.css') }}
  <!-- {{ Html::style('public/app-assets/plugins/font-awesome/css/fontawesome.css" rel="stylesheet') }}
  {{ Html::style('public/app-assets/plugins/font-awesome/css/brands.css" rel="stylesheet') }}
  {{ Html::style('public/app-assets/plugins/font-awesome/css/solid.css" rel="stylesheet') }} -->
  <!-- Ionicons -->
  {{ Html::style('public/app-assets/bower_components/Ionicons/css/ionicons.min.css') }}
    <!-- DataTables -->
  {{ Html::style('public/app-assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}
  <!-- Toaster -->
  {{ Html::style('public/app-assets/plugins/toastr/toastr.min.css') }}
  <!-- bootstrap datepicker -->
  {{ Html::style('public/app-assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}
  <!-- Bootstrap time Picker -->
  {{ Html::style('public/app-assets/plugins/timepicker/bootstrap-timepicker.min.css') }}
  <!-- Theme style -->
  {{ Html::style('public/app-assets/dist/css/AdminLTE.min.css') }}
    <!-- iCheck -->
  {{ Html::style('public/app-assets/plugins/iCheck/square/blue.css') }}
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  {{ Html::style('public/app-assets/dist/css/skins/_all-skins.min.css') }}

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
   @yield('css')
   
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-140851505-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-140851505-1');
</script>

</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

  @include('layouts.includes.top-nav')
  <!-- Full Width Column -->
  <div class="content-wrapper">
    @yield('content')
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="container">
      <div class="col-md-8 pull-left-center">
        <strong>Copyright &copy; 2019 <a href="https://www.djss.com/" target="_blank">DJSS India</a>. All rights reserved.</strong>
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
    <!-- /.container -->
  </footer>
</div>
<!-- ./wrapper -->
@include('layouts.includes.footer')
@yield('js')
{!! Toastr::render() !!}