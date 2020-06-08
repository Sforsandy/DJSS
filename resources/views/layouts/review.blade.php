<!DOCTYPE html>
<html class="loading" lang="{{ app()->getLocale() }}" data-textdirection="ltr">
 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>NovidForms - @yield('title')</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('/') }}/public/uploads/novidform_fav.png">
    <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">
    {{ Html::style('https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css') }}
    
    {{ Html::style('public/app-assets/css/vendors.min.css') }}
    {{ Html::style('public/app-assets/vendors/css/forms/toggle/switchery.min.css') }}
    {{ Html::style('public/app-assets/css/plugins/forms/switch.min.css') }}
    {{ Html::style('public/app-assets/css/core/colors/palette-switch.min.css') }}
    {{ Html::style('public/app-assets/vendors/css/extensions/toastr.css') }}
    {{ Html::style('public/app-assets/vendors/css/forms/selects/select2.min.css')}}
    <!-- END VENDOR CSS-->
    <!-- BEGIN CHAMELEON  CSS-->
    {{ Html::style('public/app-assets/css/app.min.css') }}
    <!-- END CHAMELEON  CSS-->
    <!-- BEGIN Page Level CSS-->
    {{ Html::style('public/app-assets/css/core/menu/menu-types/vertical-menu.min.css') }}
    {{ Html::style('public/app-assets/css/core/colors/palette-gradient.min.css') }}
    {{ Html::style('public/app-assets/css/plugins/extensions/toastr.min.css') }}
    @yield('css')
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    {{ Html::style('public/assets/css/style.css') }}
    <!-- END Custom CSS-->
    <!-- custom css -->
    <style>
        .loadingoverlay {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            display: none;
            right: 0;
            z-index: 999;
            bottom: 0;
            left: 0;
            background-color: rgba(0,0,0,.5);
        }
        .loading-wheel {
            width: 20px;
            height: 20px;
            margin-top: -40px;
            margin-left: -40px;
            
            position: absolute;
            top: 50%;
            left: 50%;
            
            border-width: 30px;
            border-radius: 50%;
            -webkit-animation: spin 1s linear infinite;
        }
        .style-2 .loading-wheel {
            border-style: double;
            border-color: #fff transparent;
        }
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0);
            }
            100% {
                -webkit-transform: rotate(-360deg);
            }
        }
    </style>
    <!-- end of custom css -->
  </head>

  <body class="vertical-layout" data-open="click" data-menu="vertical-menu">
        <div class="loadingoverlay style-2"><div class="loading-wheel"></div></div>
        @yield('content')
    
        <!-- BEGIN VENDOR JS-->
        {{ Html::script('public/app-assets/vendors/js/vendors.min.js') }}
        {{ Html::script('public/app-assets/vendors/js/forms/toggle/switchery.min.js') }}
        {{ Html::script('public/app-assets/js/scripts/forms/switch.min.js') }}
        <!-- BEGIN VENDOR JS-->
        @yield('js')
        {{ Html::script('public/app-assets/vendors/js/extensions/toastr.min.js') }}
        {{ Html::script('public/app-assets/vendors/js/forms/select/select2.full.min.js') }}

        <!-- BEGIN CHAMELEON  JS-->
        {{ Html::script('public/app-assets/js/core/app-menu.min.js') }}
        {{ Html::script('public/app-assets/js/core/app.min.js') }}
        {{ Html::script('public/app-assets/js/scripts/customizer.min.js') }}
        {{ Html::script('public/app-assets/vendors/js/jquery.sharrre.js') }}
        <!-- END CHAMELEON  JS-->
  </body>
  </html>