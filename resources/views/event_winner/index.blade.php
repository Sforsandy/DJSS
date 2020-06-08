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
    var EventWinnerTable;
    $(function () {
      EventWinnerTable = $('#EventWinnerTable').DataTable( {
        processing: true,
        oLanguage: {
          sProcessing: "<i class='fa fa-spin fa-refresh'> </i> Loading"
        },
        serverSide: true,
        ajax: "{{ route('event-winner.data') }}",
        columns: [
        { data: 'event_name', name: 'event_name' },
        { data: 'user_name', name: 'user_name' },
        { data: 'winner_position', name: 'winner_position' },
        // { data: 'payment_date', name: 'payment_date' },
        // { data: 'payment_id', name: 'payment_id' },
        // { data: 'upload_screenshot', name: 'upload_screenshot' },
        { data: 'action', name: 'action', orderable: false, searchable: false,width: '10%'}
        ]
      });
    });

    function deleteRow(id){
     $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      url: '{{ route("event-winner.destroy") }}',
      data: {
        '_token': $('input[name=_token]').val(),
        'id': id
      },
      beforeSend: function() {
        $('.loadingoverlay').css('display', 'block');
      },
      success: function(response) {
        EventWinnerTable.ajax.reload();
      },
      complete: function() {
        $('.loadingoverlay').css('display', 'none');
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
          <div class="box-header with-border">
            <h3 class="box-title">Event winner list</h3>
            <a class="btn btn-primary pull-right" href="{{ route('event-winner.create') }}">Add New</a>
          </div>
          <div class="box-body">
            <table id="EventWinnerTable" class="table table-bordered table-hover" width="100%">
                <thead>
                <tr>
                  <th>Event Name</th>
                  <th>User Name</th>
                  <th>Winner position</th>
                  <!-- <th>Payment date</th>
                  <th>Payment id</th>
                  <th>Screenshot</th> -->
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
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
