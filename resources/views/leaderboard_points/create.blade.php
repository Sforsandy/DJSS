@extends('layouts.main')
@section('title', 'Leaderboard Point')
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
    $("form#LeaderboardPointForm").submit(function(e) {
      e.preventDefault();
        var formData = new FormData(this);
        formData.append('title', $("#point_condition option:selected").html());
        $.ajax({
          url: "{{ route('leaderboard-point.store') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('leaderboard-point',1000);
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
          <form role="form" id="LeaderboardPointForm" method="post">
            {{ csrf_field() }}
            <div class="box-body">
              <div class="form-group">
                <label for="point_condition">Point condition</label><select class="form-control" id="point_condition" name="point_condition">
                  <option value="daily_login">Daily Login</option>
                  <option value="join_contest">Join Contest</option>
                  <option value="winner">Winner</option>
                  <option value="runnerup">Runner-Up</option>
                  <option value="second_runnerup">Second Runner-Up</option>
                </select>
              </div>
              <div class="form-group">
                <label for="point">Point</label>
                <input type="number" class="form-control" id="point" name="point" placeholder="Enter point" max="100">
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
