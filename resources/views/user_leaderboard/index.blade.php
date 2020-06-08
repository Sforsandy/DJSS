@extends('layouts.main')
@section('title', 'Leaderboard')
@section('css')
    <!-- BEGIN PAGE VENDOR CSS-->
      <!-- daterange picker -->
    {{ Html::style('public/app-assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}
    <!-- END PAGE VENDOR CSS-->
    
    <!-- BEGIN PAGE LEVEL CSS-->
    <!-- END PAGE LEVEL CSS-->
@endsection
@section('js')
  <!-- date-range-picker -->
  {{ Html::script('public/app-assets/bower_components/moment/min/moment.min.js') }}
  {{ Html::script('public/app-assets/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}
<script type="text/javascript">
    // BEGIN PAGE VENDOR JS

    // END PAGE VENDOR JS
    // BEGIN PAGE LEVEL JS

    var selectedStartDate = moment().subtract(29, 'days').format('YYYY-MM-DD');
      var selectedEndDate = moment().format('YYYY-MM-DD');
        $(document).ready(function() {
            getTransactions();
    });
    $(function() {

            var start = moment().subtract(29, 'days');
            var end = moment();
        
            function cb(start, end) {
                $('#leaderbordsRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        
            $('#leaderbordsRange').daterangepicker({
                startDate: start,
                endDate: end,
                showCustomRangeLabel: false,
                ranges: {
                   'Today': [moment(), moment()],
                   'Week': [moment().subtract(6, 'days'), moment()],
                   'Month': [moment().subtract(29, 'days'), moment()]
                }
            }, cb);
          cb(start, end);
        
        });
        
        $('#leaderbordsRange').on('apply.daterangepicker', function(ev, picker) {
          selectedStartDate = picker.startDate.format('YYYY-MM-DD');
          selectedEndDate = picker.endDate.format('YYYY-MM-DD');
          getTransactions();
        });

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    var UserLeaderboardTable;
    function getTransactions()
    {
        UserLeaderboardTable = $('#UserLeaderboardTable').DataTable( {
        processing: true,
        responsive: true,
        scrollY:true,
        bDestroy: true,
        oLanguage: {
          sProcessing: "<i class='fa fa-spin fa-refresh'> </i> Loading"
        },
        serverSide: true,
        ajax: {
          "url": "{{ route('user-leaderboard.data') }}",
          "type": "POST",
          "data": {StartDate:selectedStartDate,EndDate:selectedEndDate}
        },
        columns: [
        { data: 'user_name', name: 'user_name' },
        { data: 'total_point', name: 'total_point' },
        { data: 'ranking', name: 'ranking' },
        { data: 'lavel', name: 'lavel' }
        ]
        ,ordering: false,
        // columnDefs: [{
        //   orderable: false,
        //   targets: "total_point"
        // }]
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
            <h3 class="box-title">Leaderboard</h3>
          </div>

          <div class="box-body">
            <div class="row">
              <div id="leaderbordsRange">
              <i class="fa fa-calendar"></i>&nbsp;
              <span></span> <i class="fa fa-caret-down"></i>
            </div>
            </div>
            
            <table id="UserLeaderboardTable" class="table table-bordered table-hover" width="100%">
                <thead>
                <tr>
                  <th>User Name</th>
                  <th>Points</th>
                  <th>Rank</th>
                  <th>Level</th>
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
