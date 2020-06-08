@extends('layouts.main')
@section('title', 'Manage Event')
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
    var ManageEventTable;
    var data_filter_status = -1;
    var data_filter_eventby = -1;
    var selectedStartDate = moment().format('YYYY-MM-DD');
    var selectedEndDate = moment().add(29, 'days').format('YYYY-MM-DD');
    $(function () {
      getEvents(data_filter_status);

      var start = moment();
      var end = moment().add(29, 'days');

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
          'Next 30 Days': [moment() ,moment().add(29, 'days')],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
          'Last 3 Month': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      }, cb);
      cb(start, end);

    });
    

    $(document).on('click', '.FilterBtnStatus', function() {
      data_filter_status = $(this).attr('data-filter-status');
      $('.FilterBtnStatus').removeClass('active');
      $(this).addClass('active');
      getEvents(data_filter_status,data_filter_eventby)
    });
    $(document).on('click', '.FilterBtnEventby', function() {
      data_filter_status = $(".FilterBtnStatus.active").attr('data-filter-status');
      data_filter_eventby = $(this).attr('data-filter-eventby');
      getEvents(data_filter_status,data_filter_eventby)
    });
    $('.ReportRange').on('apply.daterangepicker', function(ev, picker) {
      selectedStartDate = picker.startDate.format('YYYY-MM-DD');
      selectedEndDate = picker.endDate.format('YYYY-MM-DD');
      getEvents(data_filter_status,data_filter_eventby);
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function getEvents(data_filter_status,data_filter_eventby)
    {
      ManageEventTable = $('#ManageEventTable').DataTable( {
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
          "url": "{{ route('event.data') }}",
          "type": "POST",
          "data": {status:data_filter_status,created_by:data_filter_eventby,StartDate:selectedStartDate,EndDate:selectedEndDate}
        },
        columns: [
        { data: 'id', name: 'id' },
        { data: 'event_name', name: 'event_name' },
        { data: 'game_name', name: 'game_name' },
        { data: 'event_type_name', name: 'event_type_name' },
        { data: 'event_format_name', name: 'event_format_name' },
        { data: 'capacity', name: 'capacity' },
        { data: 'event_joined', name: 'event_joined' },
        { data: 'fee', name: 'fee' },
        { data: 'status', name: 'status' },
        { data: 'schedule_datetime', name: 'schedule_datetime' },
        { data: 'action', name: 'action', orderable: false, searchable: false,width: '10%'}
        ]
      });
    }

    function deleteRow(id){
     $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      url: '{{ route("manage-event.destroy") }}',
      data: {
        '_token': $('input[name=_token]').val(),
        'id': id
      },
      beforeSend: function() {
        $('.loadingoverlay').css('display', 'block');
      },
      success: function(response) {
        ManageEventTable.ajax.reload();
      },
      complete: function() {
        $('.loadingoverlay').css('display', 'none');
      },
    });
    }


    // UserJoinList
    $(document).on('click', '#UserJoinList', function() {
      var event_id = $(this).data('id');
      $("#UserJoinListModal").modal("show");
      getEventJoinedUser(event_id);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function getEventJoinedUser(event_id)
    {
      UserJoinListTable = $('#UserJoinListTable').DataTable( {
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
          "url": "{{ route('manage-event.getjoineduser') }}",
          "type": "POST",
          "data": {event_id:event_id},
          dataSrc: function ( response ) {
            $('.TotalUsers').text(response.recordsTotal);
             return response.data;
            }
        },
        columns: [
        { data: 'username', name: 'username' },
        { data: 'character_name', name: 'character_name' },
        { data: 'joined_date', name: 'joined_date' }
        ]
      });
    }

    // UserJoinList end

    // UserJoinList
    $(document).on('click', '#SendMessages', function() {
      $('#event_id').val("");
      $('#message').val("");
      var event_id = $(this).data('id');
      $("#SendMessagesModal").modal("show");
      $('#event_id').val(event_id);
    });

     var TotalText = '160';
    $('#message').bind('keyup', function(e){
        CurrLength = $(this).val().length;
      $('.TotalText').text((TotalText - CurrLength) +' / '+TotalText);

    });
    $("form#MessagesForm").submit(function(e) {
      e.preventDefault();
      var isvalidate=$("#MessagesForm").valid();
      if(isvalidate == false){
        return false;
      }
        var formData = new FormData(this);

        $.ajax({
          url: "{{ route('eventmessages.send') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              $("#SendMessagesModal").modal("hide");
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

    // UserJoinList end
    // REPORT
    function ExportReport()
    {
      window.open(BASE_URL+"/event-report/"+selectedStartDate+"/"+selectedEndDate, "_blank");
    }
    // REPORT END



    // AddUserInEvent
    $(document).on('click', '#AddUserInEvent', function() {
      resetValidation('#AddUserInEventForm');
      $('#add_user_event_id').val("");
      $('#txn_id').val("");
      $('#user_id').val("").prop("selected",true);
      $('#message').val("");
      var event_id = $(this).data('id');
      $("#AddUserInEventModal").modal("show");
      $('#add_user_event_id').val(event_id);
    });

    $("form#AddUserInEventForm").submit(function(e) {
      e.preventDefault();
      var isvalidate=$("#AddUserInEventForm").valid();
      if(isvalidate == false){
        return false;
      }
        var formData = new FormData(this);

        $.ajax({
          url: "{{ route('event.adduser') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              getEvents(data_filter_status);
              $("#AddUserInEventModal").modal("hide");
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
    // AddUserInEvent End
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
            <h3 class="box-title">Events list</h3>
            <!-- <a class="btn btn-primary pull-right" href="{{ route('event.create') }}">Add New</a> -->
            <div class="btn-group pull-right">
              <a class="btn btn-primary active FilterBtnStatus" data-filter-status="-1" >All</a>
              <a class="btn btn-primary FilterBtnStatus" data-filter-status="0" >Upcoming</a>
              <a class="btn btn-primary FilterBtnStatus" data-filter-status="1" >Ongoing</a>
              <a class="btn btn-primary FilterBtnStatus" data-filter-status="2" >Past</a>
              @if(Auth::user()->hasRole('admin'))
              <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle FilterBtnEventbyTitle" data-toggle="dropdown">
                  Event by  <span class="caret"></span></button>
                  <ul class="dropdown-menu FilterBtnList" role="menu">
                    <li><a class="FilterBtnEventby" data-filter-eventby="-1">All</a></li>
                    <li><a class="FilterBtnEventby" data-filter-eventby="1">Admin</a></li>
                    @foreach ($moderatorUsers as $moderator)
                    <li><a class="FilterBtnEventby" data-filter-eventby="{{ $moderator->id }}">{{ $moderator->firstname }} {{ $moderator->lastname }}</a></li>
                    @endforeach
                  </ul>
              </div>
              @endif
            </div>
          </div>
          <div class="box-body">
            <div class="col-xs-12  col-md-12 p-lr-0">
              <a class="btn btn-primary pull-right mb-5p" onclick="ExportReport()">Export</a>
              <div class="pull-right mb-5p mr-5p ReportRange" style="">
              <i class="fa fa-calendar"></i>&nbsp;
              <span></span> <i class="fa fa-caret-down"></i>
            </div>
            </div>
            <table id="ManageEventTable" class="table table-bordered table-hover" width="100%">
                <thead>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Game</th>
                  <th>Type</th>
                  <th>Format</th>
                  <th>Capacity</th>
                  <th>Joined</th>
                  <th>Fee</th>
                  <th>Status</th>
                  <th>Scheduled</th>
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

    <!-- User LIST MODAL-->
<div class="modal fade" id="UserJoinListModal" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Joined user list</h4>
        </div>
        <div class="modal-body">
            <h4 class="modal-title">Total Users: <span class="TotalUsers" style="font-weight: bold;"></span></h4>
          <table id="UserJoinListTable" class="table table-bordered table-hover" width="100%">
            <thead>
              <tr>
                <th>User Name</th>
                <th>Character Name</th>
                <th>Joined Date</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<!-- User LIST MODALEND -->

<!-- User LIST MODAL-->
<div class="modal fade" id="SendMessagesModal" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Send Message</h4>
        </div>
        <div class="modal-body">
          <form role="form" id="MessagesForm" method="post" class="FormValidate">
            {{ csrf_field() }}
            <div class="box-body">
              <input type="hidden" name="event_id" id="event_id">
              <div class="form-group">
                <!-- <label for="message">Message</label> -->
                <textarea class="form-control" rows="3" id="message" name="message" placeholder="Enter message" required="" maxlength="160"></textarea>
                <label class='label label-success mt-5p pull-right TotalText'>160</label>
              </div>
            </div>

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<!-- User LIST MODALEND -->

<!-- ADD USER IN EVENT MODAL -->
<div class="modal fade" id="AddUserInEventModal" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add user in event</h4>
        </div>
        <div class="modal-body">
          <form role="form" id="AddUserInEventForm" method="post" class="FormValidate">
            {{ csrf_field() }}
            <div class="box-body">
              <input type="hidden" name="event_id" id="add_user_event_id">
              <div class="form-group">
                <label for="user_id">User</label>
                <select class="form-control" id="user_id" name="user_id" required="">
                  <option value="">Select user</option>
                  @foreach ($users as $user)
                  <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }} - {{ $user->mobile_no }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="txn_id">Transaction id</label>
                <input type="text" class="form-control" id="txn_id" name="txn_id" placeholder="Enter transaction id" maxlength="200" required="">
              </div>
            </div>

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<!-- ADD USER IN EVENT MODAL END -->
@endsection
