@extends('layouts.main')
@section('title', 'product')
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
      $('.feeDiv').hide();
      $('.locationDiv').hide();
      $('.BonusDiv').hide();
      CKEDITOR.config.toolbar = [
      ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','Find'],
      ['NumberedList','BulletedList'],
      ['Link','Smiley','TextColor','BGColor'],
      [ 'list', 'indent', 'blocks', 'align', 'bidi' ], [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
      ['Styles','Format','Font','FontSize']];
      CKEDITOR.replace('Description');
    });

    $('#add').on('click',function(){
      console.log('anil');
      $('.anil').append('<div class="form-group">\
                  <label for="exampleInputEmail1">Name</label>\
                  <input type="text" class="form-control" id="exampleInputEmail1" name="name" placeholder="Enter email">\
                </div>');
    })

    $("form#productForm").submit(function(e) {
      e.preventDefault();
      var isvalidate=$("#productForm").valid();
      if(isvalidate == false){
        return false;
      }
        var formData = new FormData(this);
        formData.append('Description', CKEDITOR.instances.Description.getData());
        $.ajax({
          url: "{{ route('product.store') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              // RedirectUrl('manage-events',1000);
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
  </script>
@endsection
@section('content')
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="container">

      <!-- Main content -->
      <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Product</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="productForm" method="post" class="FormValidate">
              @csrf
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                </div>
                <div class="form-group">
                  <label for="Description">Description</label>
                  <textarea class="form-control" id="Description" name="Description" rows="1" cols="50" placeholder="Winner detail">
                </textarea>
                </div>
                <div class="form-group">
                  <label>Status</label>
                  <select class="form-control" name="status" id="status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <div class="anil">
            
          </div>
            <button type="button" class="btn btn-primary" id="add">add</button>

      </section>
      <!-- /.content -->
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
