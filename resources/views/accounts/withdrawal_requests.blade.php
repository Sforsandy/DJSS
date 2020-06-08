@extends('layouts.main')
@section('title', 'Withdrawal Requests')
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
    var WithdrawalRequestTable;
    $(function () {
      WithdrawalRequestTable = $('#WithdrawalRequestTable').DataTable( {
        processing: true,
        oLanguage: {
          sProcessing: "<i class='fa fa-spin fa-refresh'> </i> Loading"
        },
        serverSide: true,
        ajax: "{{ route('account.getwithdrawalrequests') }}",
        columns: [
        { data: 'user_name', name: 'user_name' },
        { data: 'bank_holder_name', name: 'bank_holder_name' },
        { data: 'paymentupi', name: 'paymentupi' },
        { data: 'mobile_no', name: 'mobile_no' },
        { data: 'amount', name: 'amount' },
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
        WithdrawalRequestTable.ajax.reload();
      },
      complete: function() {
        $('.loadingoverlay').css('display', 'none');
      },
    });
   }

     function changeRequestStatus(id,status){
       $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: '{{ route("account.changerequeststatus") }}',
        data: {
          '_token': $('input[name=_token]').val(),
          'id': id,
          'status': status
        },
        beforeSend: function() {
          $('.loadingoverlay').css('display', 'block');
        },
        success: function(response) {
          WithdrawalRequestTable.ajax.reload();
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
            <h3 class="box-title">Withdrawal Request</h3>
          </div>
            <div class="box-body">
              <table id="WithdrawalRequestTable" class="table table-bordered table-hover" width="100%">
                <thead>
                <tr>
                  <th>UserName</th>
                  <th>Bank Holder Name</th>
                  <th>Payment UPI</th>
                  <th>Mobile</th>
                  <th>Amount</th>
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
