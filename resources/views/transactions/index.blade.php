@extends('layouts.main')
@section('title', 'My Transactions')
@section('css')
    <!-- BEGIN PAGE VENDOR CSS-->
      <!-- daterange picker -->
    {{ Html::style('public/app-assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}
    <!-- END PAGE VENDOR CSS-->
    
    <!-- BEGIN PAGE LEVEL CSS-->
    <!-- END PAGE LEVEL CSS-->
@endsection
@section('js')
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
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
                $('#TransactionsRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        
            $('#TransactionsRange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                   'Today': [moment(), moment()],
                   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                   'Last Week': [moment().subtract(6, 'days'), moment()],
                   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                //   'This Month': [moment().startOf('month'), moment().endOf('month')],
                   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                   'Last 3 Month': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);
          cb(start, end);
        
        });
        
        $('#TransactionsRange').on('apply.daterangepicker', function(ev, picker) {
          console.log(picker.startDate.format('YYYY-MM-DD'));
          console.log(picker.endDate.format('YYYY-MM-DD'));
          selectedStartDate = picker.startDate.format('YYYY-MM-DD');
          selectedEndDate = picker.endDate.format('YYYY-MM-DD');
          getTransactions();
        });

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    var EventTransactionsTable;
    function getTransactions()
    {
        EventTransactionsTable = $('#EventTransactionsTable').DataTable( {
        processing: true,
        responsive: true,
        scrollY:true,
        bDestroy: true,
        oLanguage: {
          sProcessing: "<i class='fa fa-spin fa-refresh'> </i> Loading"
        },
        serverSide: true,
        ajax: {
          "url": "{{ route('transaction.data') }}",
          "type": "POST",
          "data": {StartDate:selectedStartDate,EndDate:selectedEndDate}
        },
        columns: [
        { data: 'event_name', name: 'event_name' },
        { data: 'txn_id', name: 'txn_id' },
        { data: 'txn_amount', name: 'txn_amount' },
        { data: 'txn_date', name: 'txn_date' },
        { data: 'status', name: 'status' },
        { data: 'resp_msg', name: 'resp_msg' }
        ],
        dom: 'Bfrtip',
        buttons: [
        { extend: 'pdf', text: 'Export',className: 'btn btn-primary' }
        ]
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
            <h3 class="box-title">My Transactions</h3>
          </div>
          <div class="box-body">
            <div id="TransactionsRange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
              <i class="fa fa-calendar"></i>&nbsp;
              <span></span> <i class="fa fa-caret-down"></i>
            </div>
            <table id="EventTransactionsTable" class="table table-bordered table-hover" width="100%">
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
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
