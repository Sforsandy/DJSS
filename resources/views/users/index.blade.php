@extends('layouts.main')
@section('title', 'Users')
@section('css')
<!-- BEGIN PAGE VENDOR CSS-->
    <!-- END PAGE VENDOR CSS-->
    {{ Html::style('public/app-assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}
    <!-- BEGIN PAGE LEVEL CSS-->
    <!-- END PAGE LEVEL CSS-->
@endsection
@section('js')
{{ Html::script('public/app-assets/bower_components/moment/min/moment.min.js') }}
{{ Html::script('public/app-assets/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}
<script type="text/javascript">
    // BEGIN PAGE VENDOR JS

    // END PAGE VENDOR JS
    // BEGIN PAGE LEVEL JS
    var UsersTable;
    var selectedStartDate = moment().subtract(29, 'days').format('YYYY-MM-DD');
    var selectedEndDate = moment().format('YYYY-MM-DD');
    $(function () {
      getUsers();

      var start = moment().subtract(29, 'days');
      var end = moment();

      function cb(start, end) {
        $('.ReportRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      }

      $('.ReportRange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last Week': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
          'Last 3 Month': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      }, cb);
      cb(start, end);
    });

    $('.ReportRange').on('apply.daterangepicker', function(ev, picker) {
      selectedStartDate = picker.startDate.format('YYYY-MM-DD');
      selectedEndDate = picker.endDate.format('YYYY-MM-DD');
      getUsers();
    });

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    function getUsers(argument) {
      UsersTable = $('#UsersTable').DataTable( {
        processing: true,
        responsive: true,
        scrollY:true,
        bDestroy: true,
        oLanguage: {
          sProcessing: "<i class='fa fa-spin fa-refresh'> </i> Loading"
        },
        serverSide: true,
        ajax: {
          "url": "{{ route('user.data') }}",
          "type": "POST",
          "data": {StartDate:selectedStartDate,EndDate:selectedEndDate}
        },
        // ajax: "{{ route('user.data') }}",
        columns: [
        { data: 'firstname', name: 'firstname' },
        { data: 'lastname', name: 'lastname' },
        { data: 'character_name', name: 'character_name' },
        { data: 'email', name: 'email' },
        { data: 'mobile_no', name: 'mobile_no' },
        { data: 'role', name: 'role' },
        { data: 'status', name: 'status' },
        { data: 'action', name: 'action', orderable: false, searchable: false,width: '10%'}
        ]
      });
    }

    // DELETE USER
    function deleteRow(id){
     $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      url: '{{ route("user.destroy") }}',
      data: {
        '_token': $('input[name=_token]').val(),
        'id': id
      },
      success: function(response) {
        UsersTable.ajax.reload();
        ShowSuccess('User deleted successfully.');
      }
    });
    }
    // DELETE USER END

    // ChagePassword 
    $(document).on('click', '#ChagePassword', function() {
      $('#ChangePassModal #user_id').val($(this).data('id'));
      $("#ChangePassModal").modal("show");
    });

    $(document).on('click', '#submitChangePasswordForm', function() {
      var formData = new FormData($('#ChangePasswordForm')[0]);
      $.ajax({
        type: 'POST',
        url: '{{ route("user.changepassword") }}',
        processData: false,
        contentType: false,
        data: formData,
        success: function(response) {
          if(response.success == 1)
          {
            ShowSuccess(response.message);
            $("#ChangePassModal").modal("hide");
            UsersTable.ajax.reload();
            $('#ChangePasswordForm').trigger("reset");
          }
          else
          {
            ShowError(response.message);
          }
        },
      });
    });
    // ChagePassword end

    // ChageRole 
    $(document).on('click', '#ChageRole', function() {
      $('#ChangeRoleModal #user_id').val($(this).data('id'));
      $("#ChangeRoleModal").modal("show");
    });

    $(document).on('click', '#submitChangeRoleForm', function() {
      var formData = new FormData($('#ChangeRoleForm')[0]);
      $.ajax({
        type: 'POST',
        url: '{{ route("user.changerole") }}',
        processData: false,
        contentType: false,
        data: formData,
        success: function(response) {
          if(response.success == 1)
          {
            ShowSuccess(response.message);
            $("#ChangeRoleModal").modal("hide");
            UsersTable.ajax.reload();
            $('#ChangeRoleForm').trigger("reset");
          }
          else
          {
            ShowError(response.message);
          }
        },
      });
    });
    // ChageRole end

    // UserTransactions
    $(document).on('click', '#UserTransactions', function() {
      var user_id = $(this).data('id');
      $("#transactionsModal").modal("show");
      getTransactions(user_id);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function getTransactions(user_id)
    {
      UserTransactionsTable = $('#UserTransactionsTable').DataTable( {
        processing: true,
        responsive: true,
        scrollY:true,
        bDestroy: true,
        oLanguage: {
          sProcessing: "<i class='fa fa-spin fa-refresh'> </i> Loading"
        },
        serverSide: true,
        // ajax: "{{ route('event.data') }}",
        ajax: {
          "url": "{{ route('user.transaction') }}",
          "type": "POST",
          "data": {user_id:user_id}
        },
        columns: [
        { data: 'event_name', name: 'event_name' },
        { data: 'txn_id', name: 'txn_id' },
        { data: 'txn_amount', name: 'txn_amount' },
        { data: 'txn_date', name: 'txn_date' },
        { data: 'status', name: 'status' },
        { data: 'resp_msg', name: 'resp_msg' }
        ]
      });
    }

    // UserTransactions end


    // REPORT
    function ExportReport()
    {
      window.open(BASE_URL+"/user-report/"+selectedStartDate+"/"+selectedEndDate, "_blank");
    }
    // REPORT END
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
            <h3 class="box-title">Users list</h3>
            <a class="btn btn-primary pull-right" href="{{ route('user.create') }}">Add New</a>
          </div>

          <div class="box-body">
            <div class="col-xs-12  col-md-12 p-lr-0">
              <a class="btn btn-primary pull-right mb-5p" onclick="ExportReport()">Export</a>
              <div class="pull-right mb-5p mr-5p ReportRange" style="">
              <i class="fa fa-calendar"></i>&nbsp;
              <span></span> <i class="fa fa-caret-down"></i>
            </div>
            </div>
            <table id="UsersTable" class="table table-bordered table-hover clear" width="100%">
                <thead>
                <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Character Name</th>
                  <th>Email</th>
                  <th>Mob No</th>
                  <th>Role</th>
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

<!-- CHANGE PASSWORD -->
<div class="modal fade" id="ChangePassModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Change user password </h4>
        </div>
        <div class="modal-body">
          <form id="ChangePasswordForm" method="POST" role="form" onsubmit="return false">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="password">New password</label>
              <input type="hidden" class="form-control" name="user_id" id="user_id" >
              <input type="text" class="form-control" name="password" id="password" placeholder="Enter new password">
            </div>
            <div class="form-group">
              <label for="password_confirmation">Confirm password</label>
              <input type="text" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Enter confirm password">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="submitChangePasswordForm">Submit</button>
        </div>
      </div>
    </div>
  </div>
<!-- CHANGE PASSWORD END -->

<!-- CHANGE ROLE -->
<div class="modal fade" id="ChangeRoleModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Change user role </h4>
        </div>
        <div class="modal-body">
          <form id="ChangeRoleForm" method="POST" role="form" onsubmit="return false">
            {{ csrf_field() }}
              <input type="hidden" class="form-control" name="user_id" id="user_id" >
              <div class="form-group">
                <label for="gender">Role</label>
                <select class="form-control" name="role" id="role">
                  <option value="3" selected="">General user</option>
                  <option value="2">Moderator</option>
                </select>
              </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="submitChangeRoleForm">Submit</button>
        </div>
      </div>
    </div>
  </div>
<!-- CHANGE ROLE END -->


    <!-- TRANSACTION LIST MODAL-->
<div class="modal fade" id="transactionsModal" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">User transactions</h4>
        </div>
        <div class="modal-body">
          <table id="UserTransactionsTable" class="table table-bordered table-hover" width="100%">
            <thead>
              <tr>
                <th>Event Name</th>
                <th>Txn Id</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Status</th>
                <th>Message</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
       <!--  <div class="modal-footer">
          <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
        </div> -->
      </div>
    </div>
  </div>
<!-- TRANSACTION LIST MODALEND -->

@endsection
