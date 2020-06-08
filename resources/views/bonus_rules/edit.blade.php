@extends('layouts.main')
@section('title', 'Bonus Rules')
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
    $(function () {

    });
    $("form#BonusRoleForm").submit(function(e) {
      e.preventDefault();
        var formData = new FormData(this);
        formData.append('name', $("#rule option:selected").html());
        $.ajax({
          url: "{{ route('bonus-rule.update') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('bonus-rule',1000);
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
    // END PAGE LEVEL JS
</script>
    
@endsection
@section('content')
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="container">

      <!-- Main content -->
      <section class="content">
        <div class="box box-default">
          <form role="form" id="BonusRoleForm" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $data->id }}">
            <div class="box-body">
              <div class="form-group">
                <label for="rule">Rule</label><select class="form-control" id="rule" name="rule">
                  <option value="">Select Rule</option>
                  <option <?= ($data->rule == 'sign_with_refer_code') ? 'selected' : '';?> value="sign_with_refer_code">Sign with refer code</option>
                  <option <?= ($data->rule == 'referrer_earn') ? 'selected' : '';?> value="referrer_earn">Referrer user earn</option>
                  <option <?= ($data->rule == '3_paid_event_per_day') ? 'selected' : '';?> value="3_paid_event_per_day">3 Paid event per day</option>
                  <option <?= ($data->rule == '1paid_event_consecutive_3day') ? 'selected' : '';?> value="1paid_event_consecutive_3day">1 Paid event consecutive 3day</option>
                  <option <?= ($data->rule == '5paid_event_per_week') ? 'selected' : '';?> value="5paid_event_per_week">5 paid event per week</option>
                </select>
              </div>
              <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" class="form-control" id="amount"  value="{{ $data->amount }}" name="amount" placeholder="Enter Amount" max="10000">
              </div>
            </div>

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
              <a href="{{ url()->previous() }}" class="btn btn-default">Back</a>
            </div>
          </form>
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
