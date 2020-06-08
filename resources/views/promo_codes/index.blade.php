@extends('layouts.main')
@section('title', 'Promo Code')
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
    var PromoCodeTable;
    $(function () {
      PromoCodeTable = $('#PromoCodeTable').DataTable( {
        processing: true,
        oLanguage: {
          sProcessing: "<i class='fa fa-spin fa-refresh'> </i> Loading"
        },
        serverSide: true,
        ajax: "{{ route('promo-code.data') }}",
        columns: [
        { data: 'promocode', name: 'promocode' },
        { data: 'amount', name: 'amount' },
        { data: 'used_per_user', name: 'used_per_user' },
        { data: 'credit_wallat_type', name: 'credit_wallat_type' },
        { data: 'expire_date', name: 'expire_date' },
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
      url: '{{ route("promo-code.destroy") }}',
      data: {
        '_token': $('input[name=_token]').val(),
        'id': id
      },
      beforeSend: function() {
        $('.loadingoverlay').css('display', 'block');
      },
      success: function(response) {
        PromoCodeTable.ajax.reload();
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
            <h3 class="box-title">Promocode list</h3>
            <a class="btn btn-primary pull-right" href="{{ route('promo-code.create') }}">Add New</a>
          </div>
          <div class="box-body">
            <table id="PromoCodeTable" class="table table-bordered table-hover" width="100%">
                <thead>
                <tr>
                  <th>Promocode</th>
                  <th>Amount</th>
                  <th>Use Per User</th>
                  <th>Credit Wallat</th>
                  <th>Expire Date</th>
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
