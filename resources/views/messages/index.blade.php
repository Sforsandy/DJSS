@extends('layouts.main')
@section('title', 'Messages')
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
    var TotalText = '160';
    $('#message').bind('keyup', function(e){
        CurrLength = $(this).val().length;
	    $('.TotalText').text((TotalText - CurrLength) +' / '+TotalText);

    });
    $("form#MessagesForm").submit(function(e) {
      e.preventDefault();
      var isvalidate=$("#MessagesForm").valid();
      if(isvalidate == false){
        return false;
      }
        var formData = new FormData(this);

        $.ajax({
          url: "{{ route('messages.send') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('messages',1000);
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
          <div class="box-header with-border">
            <h3 class="box-title">Messages</h3>
          </div>
          <div class="box-body">
            <form role="form" id="MessagesForm" method="post" class="FormValidate">
            {{ csrf_field() }}
            <div class="box-body">
              <div class="form-group">
                <label for="game">Game</label>
                <select class="form-control" id="game" name="game">
                      <option value="">All</option>
                      @foreach ($games as $game)
                        <option value="{{ $game->id }}">{{ $game->game_name }}</option>
                      @endforeach
                    </select>
              </div>
              <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" rows="3" id="message" name="message" placeholder="Enter message" required="" maxlength="160"></textarea>
                <label class='label label-success mt-5p pull-right TotalText'>160</label>
              </div>
            </div>

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
              <a href="{{ url()->previous() }}" class="btn btn-default">Back</a>
            </div>
          </form>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
