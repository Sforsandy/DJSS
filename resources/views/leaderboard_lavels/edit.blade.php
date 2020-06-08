@extends('layouts.main')
@section('title', 'Leaderboard level')
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
    $("form#LeaderboardLavelForm").submit(function(e) {
      e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
          url: "{{ route('leaderboard-lavel.update') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('leaderboard-lavel',1000);
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
          <form role="form" id="LeaderboardLavelForm" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $data->id }}">
            <div class="box-body">
              <div class="form-group">
                <label for="start_point">Start Point</label>
                <input type="text" class="OnlyNumber form-control" id="start_point"  value="{{ $data->start_point }}" name="start_point" placeholder="Enter Start Point" max="10000">
              </div>
              <div class="form-group">
                <label for="end_point">End Point</label>
                <input type="text" class="OnlyNumber form-control" id="end_point"  value="{{ $data->end_point }}" name="end_point" placeholder="Enter End Point" max="10000">
              </div>
              <div class="form-group">
                <label for="lavel">Level</label>
                <input type="text" class="form-control" id="lavel"  value="{{ $data->lavel }}" name="lavel" placeholder="Enter level">
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
