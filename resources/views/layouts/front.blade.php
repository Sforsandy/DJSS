<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <title>Novid Technology</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">
  <meta name="robots" content="noindex">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  {{ Html::style('public/uploads/novidform_fav.png') }}
  {{ Html::style('public/uploads/novidform_fav.png') }}
  
  {{ Html::style('https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,600,700,700i|Montserrat:300,400,500,600,700') }}

  {{ Html::style('public/front/assets/lib/bootstrap/css/bootstrap.min.css') }}

  {{ Html::style('public/front/assets/lib/lightbox/css/lightbox.min.css') }}
  {{ Html::style('public/front/assets/lib/owlcarousel/assets/owl.carousel.min.css') }}
  {{ Html::style('public/front/assets/lib/ionicons/css/ionicons.min.css') }}
  {{ Html::style('public/front/assets/lib/animate/animate.min.css') }}
  {{ Html::style('public/front/assets/lib/font-awesome/css/font-awesome.min.css') }}

  {{ Html::style('public/front/assets/css/style.css') }}
  @yield('style')
</head>

<body>
  
  @include('layouts.includes.front.header')
    
  @yield('content')
    
  @include('layouts.includes.front.footer')
    
  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
  <div id="preloader"></div>
  
  {{ Html::script('public/front/assets/lib/jquery/jquery.min.js') }}
  {{ Html::script('public/front/assets/lib/jquery/jquery-migrate.min.js') }}
  {{ Html::script('public/front/assets/lib/bootstrap/js/bootstrap.bundle.min.js') }}
  {{ Html::script('public/front/assets/lib/easing/easing.min.js') }}
  {{ Html::script('public/front/assets/lib/mobile-nav/mobile-nav.js') }}
  {{ Html::script('public/front/assets/lib/wow/wow.min.js') }}
  {{ Html::script('public/front/assets/lib/waypoints/waypoints.min.js') }}
  {{ Html::script('public/front/assets/lib/counterup/counterup.min.js') }}
  {{ Html::script('public/front/assets/lib/owlcarousel/owl.carousel.min.js') }}
  {{ Html::script('public/front/assets/lib/isotope/isotope.pkgd.min.js') }}
  {{ Html::script('public/front/assets/lib/lightbox/js/lightbox.min.js') }}
  <!-- Contact Form JavaScript File -->
  {{ Html::script('public/front/assets/contactform/contactform.js/') }}
  {{ Html::script('public/front/assets/js/main.js') }}
    
  @yield('script')
</body>
</html>