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
    var CurrentRow = [];
    $(function () {
    //Date picker
        $('#payment_date').datepicker({
          autoclose: true,
          format: 'dd-mm-yyyy',
          startDate: "-30d"
        });
    });
    $("form#EventWinnerForm").submit(function(e) {
       e.preventDefault();
      var isvalidate=$("#EventWinnerForm").valid();
      if(isvalidate == false){
        return false;
      }
        var formData = new FormData(this);
        formData.append('game_id', $("#event_id option:selected").data('game'));
        $.ajax({
          url: "{{ route('event-winner.store') }}",
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
          CurrentRow = response.data;
          $.each(response.data, function( index, value ) {
            var character_name = '';
            if(value.character_name !=  null || value.character_name== '')
            {
              character_name = ' - '+value.character_name;
            }
            Users += '<option data-id='+index+' value='+value.id+'>'+value.firstname+' '+value.lastname+''+character_name+'</option>'
          });
          $("#user_id").html(Users);
        },
      });
    });
    $("#user_id").change(function(e) {
      var index = $(this).find(':selected').data('id');
      $("#payment_id").val('');
      if(index >= 0)
      {
        var Data = CurrentRow[index];
        $("#payment_id").val(Data.paymentupi);
      }
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
          <form role="form" id="EventWinnerForm" method="post" class="FormValidate">
            {{ csrf_field() }}
            <div class="box-body">
              <div class="form-group">
                <label for="event_id">Event</label>
                <select class="form-control" id="event_id" name="event_id" required="">
                  <option value="">Select event</option>
                  @foreach ($events as $event)
                  <option value="{{ $event->id }}" data-game="{{ $event->game }}">{{ $event->event_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="user_id">User</label>
                <select class="form-control" id="user_id" name="user_id" required="">
                </select>
              </div>
              <div class="form-group">
                <label for="winner_position">Winner position</label>
                <select class="form-control" id="winner_position" name="winner_position" required="">
                  <option value="">Select position</option>
                  <!-- <option value="1st">1st</option>
                  <option value="2nd">2nd</option>
                  <option value="3rd">3rd</option>
                  <option value="Other">Other</option> -->
                  @foreach ($winnerpositions as $winnerposition)
                  <option value="{{ $winnerposition->id }}">{{ $winnerposition->position }}</option>
                  @endforeach
                  </select>
              </div>
              <!-- <div class="form-group">
                <label for="amount">Amount</label>
                <input type="text" class="form-control OnlyNumber" id="amount" name="amount" placeholder="Enter amount">
              </div> -->
              <!-- <div class="form-group">
                <label for="payment_date">Payment Date</label>
                <input type="text" class="form-control" id="payment_date" name="payment_date" placeholder="Enter payment date" autocomplete="off" required="" readonly="" value="{{ date('d-m-Y') }}">
              </div>
              <div class="form-group">
                <label for="payment_id">Payment id</label>
                <input type="text" class="form-control" id="payment_id" name="payment_id" placeholder="Enter payment id" maxlength="50">
              </div> -->
              <!-- <div class="form-group">
                <label for="upload_screenshot">Upload screenshot</label>
                <input type="file" class="form-control" id="upload_screenshot" name="upload_screenshot" placeholder="Upload screenshot">
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
