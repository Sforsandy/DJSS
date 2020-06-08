@extends('layouts.main')
@section('title', 'Payment status')
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
            <h3 class="box-title">Payment status</h3>
          </div>
          <div class="box-body">
            <table  class="table table-hover payment-status-tbl">
              <tbody>
                <tr>
                  <td class="payment-detail-td">Event Name</td>
                  <td class="payment-data-td">{{ $data->events->event_name }}</td>
                </tr>
                <tr>
                  <td class="payment-detail-td">Tnx Id</td>
                  <td class="payment-data-td">{{ $data->txn_id }}</td>
                </tr>
                <tr>
                  <td class="payment-detail-td">Amount</td>
                  <td class="payment-data-td">{{ $data->txn_amount }}</td>
                </tr>
                <tr>
                  <td class="payment-detail-td">Date</td>
                  <td class="payment-data-td">{{ \Carbon\Carbon::parse($data->txn_date)->format('d-m-Y h:i:s A') }}</td>
                </tr>
                <tr>
                  <td class="payment-detail-td">Status</td>
                  <td class="payment-data-td">{{ $data->status }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <a href="{{ route('events') }}" class="btn btn-default">Back</a>
          </div>
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
