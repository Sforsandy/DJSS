@extends('layouts.main')
@section('title', 'Id Verification')
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
    var IdVerificationTable;
    $(function () {
      IdVerificationTable = $('#IdVerificationTable').DataTable( {
        processing: true,
        oLanguage: {
          sProcessing: "<i class='fa fa-spin fa-refresh'> </i> Loading"
        },
        serverSide: true,
        ajax: "{{ route('account.getidproof') }}",
        columns: [
        { data: 'user_name', name: 'user_name' },
        { data: 'mobile_no', name: 'mobile_no' },
        { data: 'id_proof_image', name: 'id_proof_image' },
        { data: 'action', name: 'action', orderable: false, searchable: false,width: '10%'}
        ]
      });
    });

    function changeStatus(id,status){
     $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      url: '{{ route("account.idproofapproval") }}',
      data: {
        '_token': $('input[name=_token]').val(),
        'id': id,
        'status': status
      },
      beforeSend: function() {
        $('.loadingoverlay').css('display', 'block');
      },
      success: function(response) {
        IdVerificationTable.ajax.reload();
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
            <h3 class="box-title">Id Verification</h3>
          </div>
            <div class="box-body">
              <table id="IdVerificationTable" class="table table-bordered table-hover" width="100%">
                <thead>
                <tr>
                  <th>UserName</th>
                  <th>Mobile</th>
                  <th>ID Proof</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
        </div>
      </section>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
