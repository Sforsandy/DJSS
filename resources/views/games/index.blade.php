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
    var GamesTable;
    $(function () {
      GamesTable = $('#GamesTable').DataTable( {
        processing: true,
        oLanguage: {
          sProcessing: "<i class='fa fa-spin fa-refresh'> </i> Loading"
        },
        serverSide: true,
        ajax: "{{ route('game.data') }}",
        columns: [
        { data: 'game_name', name: 'game_name' },
        { data: 'status', name: 'status', orderable: false, searchable: false,width: '10%'},
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
      url: '{{ route("game.destroy") }}',
      data: {
        '_token': $('input[name=_token]').val(),
        'id': id
      },
      beforeSend: function() {
        $('.loadingoverlay').css('display', 'block');
      },
      success: function(response) {
        GamesTable.ajax.reload();
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
            <h3 class="box-title">Game list</h3>
            <a class="btn btn-primary pull-right" href="{{ route('game.create') }}">Add New</a>
          </div>
          <div class="box-body">
            <table id="GamesTable" class="table table-bordered table-hover" width="100%">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Status</th>
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
