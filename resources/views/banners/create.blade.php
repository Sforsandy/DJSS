@extends('layouts.main')
@section('title', 'Banner')
@section('css')
<!-- BEGIN PAGE VENDOR CSS-->
    <!-- END PAGE VENDOR CSS-->
    
    <!-- BEGIN PAGE LEVEL CSS-->
    <!-- END PAGE LEVEL CSS-->
@endsection
@section('js')
<!-- CK Editor -->
{{ Html::script('public/app-assets/bower_components/ckeditor/ckeditor.js') }}
<script type="text/javascript">
    // BEGIN PAGE VENDOR JS

    // END PAGE VENDOR JS
    // BEGIN PAGE LEVEL JS
    $(function () {
      CKEDITOR.config.toolbar = [
      ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','Find'],
      ['NumberedList','BulletedList'],
      ['Link','Smiley','TextColor','BGColor'],
      [ 'list', 'indent', 'blocks', 'align', 'bidi' ], [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
      ['Styles','Format','Font','FontSize']];
      CKEDITOR.replace('banner_data');
    });
    $("form#BannerForm").submit(function(e) {
      e.preventDefault();
        var formData = new FormData(this);
        formData.append('banner_data', CKEDITOR.instances.banner_data.getData());
        $.ajax({
          url: "{{ route('banner.store') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('banner',1000);
            }
            else
            {
              ShowError(response.message);
            }
          },
          cache: false,
          contentType: false,
          processData: false
        });

    });
    // END PAGE LEVEL JS
</script>
    
@endsection
@section('content')
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="container">

      <!-- Main content -->
      <section class="content">
        <div class="box box-default">
          <form role="form" id="BannerForm" method="post">
            {{ csrf_field() }}
            <div class="box-body">
              <!-- <div class="form-group">
                <label for="banner_url">Url</label>
                <input type="text" class="form-control" id="banner_url" name="banner_url" placeholder="Enter url">
              </div> -->
              <div class="form-group">
                <label for="banner_image">Banner Image</label><small> Upload 800 x 450 image</small>
                <input type="file" class="form-control" id="banner_image" name="banner_image" placeholder="Upload Image">
              </div>
              <div class="form-group">
                <label for="banner_data">Banner data</label>
                <textarea class="form-control" id="banner_data" name="banner_data" rows="1" cols="50">
                </textarea>
              </div>
            </div>

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
              <a href="{{ url()->previous() }}" class="btn btn-default">Back</a>
            </div>
          </form>
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
