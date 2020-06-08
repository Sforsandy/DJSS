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
    var EventRulesTable;
    $(function () {
      EventRulesTable = $('#EventRulesTable').DataTable( {
        processing: true,
        oLanguage: {
          sProcessing: "<i class='fa fa-spin fa-refresh'> </i> Loading"
        },
        serverSide: true,
        ajax: "{{ route('event-rule.data') }}",
        columns: [
        { data: 'rules', name: 'rules' },
        { data: 'event_name', name: 'event_name' },
        { data: 'game_name', name: 'game_name' },
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
      url: '{{ route("event-rule.destroy") }}',
      data: {
        '_token': $('input[name=_token]').val(),
        'id': id
      },
      beforeSend: function() {
        $('.loadingoverlay').css('display', 'block');
      },
      success: function(response) {
        EventRulesTable.ajax.reload();
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
            <h3 class="box-title">Event rules list</h3>
            <a class="btn btn-primary pull-right" href="{{ route('event-rule.create') }}">Add New</a>
          </div>
          <div class="box-body">
            <table id="EventRulesTable" class="table table-bordered table-hover" width="100%">
                <thead>
                <tr>
                  <th>Rules</th>
                  <th>Event Name</th>
                  <th>Game Name</th>
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
