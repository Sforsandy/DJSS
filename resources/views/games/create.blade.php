@extends('layouts.main')
@section('title', 'Game')
@section('css')
<!-- BEGIN PAGE VENDOR CSS-->
    <!-- END PAGE VENDOR CSS-->
    
    <!-- BEGIN PAGE LEVEL CSS-->
    <!-- END PAGE LEVEL CSS-->
@endsection
@section('js')
<script type="text/javascript">
    // BEGIN PAGE VENDOR JS

    // END PAGE VENDOR JS
    // BEGIN PAGE LEVEL JS
    $(function () {

    });
    $("form#GameForm").submit(function(e) {
      e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
          url: "{{ route('game.store') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('game',1000);
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
          <form role="form" id="GameForm" method="post">
            {{ csrf_field() }}
            <div class="box-body">
              <div class="form-group">
                <label for="game_name">Name</label>
                <input type="text" class="form-control" id="game_name" name="game_name" placeholder="Enter name" maxlength="100">
              </div>
              <div class="form-group">
                <label for="game_image">Upload Image</label><small> Upload 512 x 512 image</small>
                <input type="file" class="form-control" id="game_image" name="game_image" placeholder="Upload Image">
              </div>
              <div class="form-group">
                <label for="game_banner">Game Banner</label><small> Upload 800 x 350 image</small>
                <input type="file" class="form-control" id="game_banner" name="game_banner" placeholder="Upload Image">
              </div>
              <div class="form-group">
                <div class="checkbox icheck">
                  <label>
                    <input type="checkbox" id="status" name="status"> Is Active
                  </label>
                </div>
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
