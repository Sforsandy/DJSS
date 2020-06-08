@extends('layouts.main')
@section('title', 'Event Winner Request')
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
    var EventWinnerTable;
    $(function () {
      EventWinnerTable = $('#EventWinnerTable').DataTable( {
        processing: true,
        oLanguage: {
          sProcessing: "<i class='fa fa-spin fa-refresh'> </i> Loading"
        },
        serverSide: true,
        ajax: "{{ route('event-winner.getwinnerrequests') }}",
        columns: [
        { data: 'event_name', name: 'event_name' },
        // { data: 'schedule_date', name: 'schedule_date' },
        { data: 'user_name', name: 'user_name' },
        { data: 'mobile_no', name: 'mobile_no' },
        { data: 'game_screenshot', name: 'game_screenshot' },
        { data: 'winner_position', name: 'winner_position' },
        { data: 'action', name: 'action', orderable: false, searchable: false,width: '10%'}
        ]
      });
    });

    function winnerStatusChange(user_id,event_id,status){
      $('#winnerStatusChangeModal #user_id').val(user_id);
      $('#winnerStatusChangeModal #event_id').val(event_id);
      if(status == 1)
      {
        $("#winnerStatusChangeModal").modal("show");
      }
    }
      
    //  $.ajax({
    //   headers: {
    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //   },
    //   type: 'POST',
    //   url: '{{ route("event-winner.destroy") }}',
    //   data: {
    //     '_token': $('input[name=_token]').val(),
    //     'id': id
    //   },
    //   beforeSend: function() {
    //     $('.loadingoverlay').css('display', 'block');
    //   },
    //   success: function(response) {
    //     EventWinnerTable.ajax.reload();
    //   },
    //   complete: function() {
    //     $('.loadingoverlay').css('display', 'none');
    //   },
    // });
    // }

    $("form#winnerStatusChangeForm").submit(function(e) {
       e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
          url: "{{ route('event-winner.winner-request-change-status') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('event-winner/winner-request',1000);
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
            <h3 class="box-title">Event winner request list</h3>
          </div>
          <div class="box-body">
            <table id="EventWinnerTable" class="table table-bordered table-hover" width="100%">
                <thead>
                <tr>
                  <th>Event Name</th>
                  <!-- <th>Date</th> -->
                  <th>Player Name</th>
                  <th>Mobile No</th>
                  <th>Screenshot</th>
                  <th>Winner Position</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>

    <!-- CHANGE ROLE -->
<div class="modal fade" id="winnerStatusChangeModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Winner amount </h4>
        </div>
        <div class="modal-body">
          <form id="winnerStatusChangeForm" method="POST" role="form" onsubmit="return false">
            {{ csrf_field() }}
              <input type="hidden" class="form-control" name="user_id" id="user_id" >
              <input type="hidden" class="form-control" name="event_id" id="event_id" >
              <div class="form-group">
                <label for="winner_position">Winner position</label>
                <select class="form-control" id="winner_position" name="winner_position" required="">
                  <option value="">Select position</option>
                  @foreach ($winnerpositions as $winnerposition)
                  <option value="{{ $winnerposition->id }}">{{ $winnerposition->position }}</option>
                  @endforeach
                  </select>
              </div>
              <div class="form-group">
                <label for="amount">Amount</label>
                <input type="text" class="form-control OnlyNumber" id="amount" name="amount" placeholder="Enter amount">
              </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<!-- CHANGE ROLE END -->
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
