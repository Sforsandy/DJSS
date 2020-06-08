@extends('layouts.main')
@section('title', 'Page not found')
@section('css')
<!-- BEGIN PAGE VENDOR CSS-->
    <!-- END PAGE VENDOR CSS-->
    
    <!-- BEGIN PAGE LEVEL CSS-->
    <!-- END PAGE LEVEL CSS-->
@endsection
    
@section('content')
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="container">
        <section class="content">
            <div class="error-content">
                <h2 class="headline text-red text-center"> 404</h2><h3 class="text-red text-center"><i class="fa fa-warning text-red"></i> Page Not Found !!
                </br><a href="{{ url()->previous() }}" class="btn btn-default mt-5p">Go Back</a>
            </h3>

            </div>
        </section>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
@section('js')
    <!-- BEGIN PAGE VENDOR JS-->
    <!-- END PAGE VENDOR JS-->
    
    <!-- BEGIN PAGE LEVEL JS-->
    <!-- END PAGE LEVEL JS-->
    
@endsection