@extends('layouts.main')
@section('title', 'Event Rules')
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

    var IS_ADMIN = "<?php echo  Auth::user()->hasRole('admin'); ?>"
    $(function () {

    });
    $("form#EventRulesForm").submit(function(e) {
      e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
          url: "{{ route('event-rule.store') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('event-rule',1000);
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

    $("#game_id").change(function(e) {
      // var state_id = $(this).find(':selected').data('id');
      var game_id = $(this).find(':selected').val();
      getGameEvents(game_id);
    });

    function getGameEvents(game_id)
    {
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: '{{ route("game-events") }}',
        data: {
          '_token': $('input[name=_token]').val(),
          'game': game_id
        },
        success: function(response) {
          var EventList = '';
          if(IS_ADMIN == 1)
          {
            EventList = '<option value="">All</option>';
          }
          $.each(response.data, function( index, value ) {
            EventList += '<option value='+value.id+'>'+value.event_name+'</option>'
          });
          $("#event_id").html(EventList);
        },
      });
    }
    // END PAGE LEVEL JS
</script>
    
@endsection
@section('content')
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="container">

      <!-- Main content -->
      <section class="content">
        <div class="box box-default">
          <form role="form" id="EventRulesForm" method="post">
            {{ csrf_field() }}
            <div class="box-body">
              <div class="form-group">
                <label for="rules">Rules</label>
                <input type="text" class="form-control" id="rules" name="rules" placeholder="Enter rule">
              </div>
              <div class="form-group">
                <label for="game_id">Game</label>
                <select class="form-control" id="game_id" name="game_id">
                      @if(Auth::user()->hasRole('admin'))
                      <option value="">All</option>
                      @endif
                      @if(Auth::user()->hasRole('moderator'))
                      <option value="">Select game</option>
                      @endif
                      @foreach ($games as $game)
                        <option value="{{ $game->id }}">{{ $game->game_name }}</option>
                      @endforeach
                    </select>
              </div>
              <div class="form-group">
                <label for="event_id">Event</label>
                <select class="form-control" id="event_id" name="event_id">
                      @if(Auth::user()->hasRole('admin'))
                      <option value="">All</option>
                      @endif
                      @foreach ($events as $event)
                        <option value="{{ $event->id }}">{{ $event->event_name }}</option>
                      @endforeach
                    </select>
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
