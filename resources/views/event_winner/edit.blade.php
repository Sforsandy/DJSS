@extends('layouts.main')
@section('title', 'Event Winner')
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
        $('#payment_date').datepicker({
          autoclose: true,
          format: 'dd-mm-yyyy',
          startDate: "-30d"
        });
      getEventUser('<?= $data->event_id  ?>')
    });
    $("form#EventForm").submit(function(e) {
      e.preventDefault();
        var formData = new FormData(this);
        formData.append('game_id', $("#event_id option:selected").data('game'));
        $.ajax({
          url: "{{ route('event-winner.update') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('event-winner',1000);
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
    $("#event_id").change(function(e) {
      var event_id = $(this).val();
      getEventUser(event_id)
    });

    function getEventUser(event_id) {
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: '{{ route("event-winner.geteventuser") }}',
        data: {
          '_token': $('input[name=_token]').val(),
          'event_id': event_id
        },
        success: function(response) {
          var Users = '<option value="">Select user</option>';
          var CurrUser  = '<?= $data->user_id ?>';
          $.each(response.data, function( index, value ) {
            var Select = '';
            if(value.id == CurrUser)
            {
              Select = 'selected';
            }
            var character_name = '';
            if(value.character_name !=  null || value.character_name== '')
            {
              character_name = ' - '+value.character_name;
            }
            console.log(Select);
            console.log(CurrUser);
            Users += '<option value='+value.id+' '+Select+'>'+value.firstname+' '+value.lastname+''+character_name+'</option>'
          });
          $("#user_id").html(Users);
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
          <form role="form" id="EventForm" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $data->id }}">
            <div class="box-body">
                <div class="form-group">
                  <label for="event_id">Event</label>
                  <select class="form-control" id="event_id" name="event_id" required="">
                    <option value="">Select event</option>
                    @foreach ($events as $event)
                    <?php $selected = '';
                         if($event->id == $data->event_id)
                          {$selected = 'selected';} ?>
                    <option value="{{ $event->id }}" data-game="{{ $event->game }}"  <?= $selected ?>>{{ $event->event_name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="user_id">User</label>
                  <select class="form-control" id="user_id" name="user_id" required="">
                    <option value="">Select user</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="winner_position">Winner position</label>
                  <select class="form-control" id="winner_position" name="winner_position" required="">
                    <option value="">Select position</option>
                    @foreach ($winnerpositions as $winnerposition)
                    <?php $selected = '';
                         if($winnerposition->id == $data->winner_position)
                          {$selected = 'selected';} ?>
                    <option value="{{ $winnerposition->id }}"  <?= $selected ?>>{{ $winnerposition->position }}</option>
                    @endforeach
                  </select>
                </div>
                <!-- <div class="form-group">
                  <label for="amount">Amount</label>
                  <input type="text" class="form-control OnlyNumber" value="{{ $winnerposition->amount }}" id="amount" name="amount" placeholder="Enter amount">
                </div> -->
                <!-- <div class="form-group">
                  <label for="payment_date">Payment Date</label>
                  <input type="text" class="form-control" id="payment_date" name="payment_date" placeholder="Enter payment date" autocomplete="off" required="" readonly="" value="{{ \Carbon\Carbon::parse($data->payment_date)->format('d-m-Y') }}">
                </div>
                <div class="form-group">
                  <label for="payment_id">Payment id</label>
                  <input type="text" class="form-control" id="payment_id" name="payment_id" placeholder="Enter payment id" maxlength="50" value="{{ $data->payment_id }}">
                </div>
                <a target='_blank' href='{{ url("public/uploads/winners")."/".$data->upload_screenshot }}' class='purple'>{{ $data->upload_screenshot }}</a>
                <div class="form-group">
                  <label for="upload_screenshot">Upload screenshot</label>
                  <input type="file" class="form-control" id="upload_screenshot" name="upload_screenshot" placeholder="Enter Upload screenshot">
                </div> -->
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
