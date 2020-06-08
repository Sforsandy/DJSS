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
    $(function () {

    });
    $("form#PromoCodeForm").submit(function(e) {
      e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
          url: "{{ route('promo-code.store') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('promo-code',1000);
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
          <form role="form" id="PromoCodeForm" method="post">
            {{ csrf_field() }}
            <div class="box-body">
              <div class="form-group">
                <label for="user_id">User</label>
                <select class="form-control" id="user_id" name="user_id">
                  <option value="">All user</option>
                  @foreach ($users as $user)
                  <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }} - {{ $user->mobile_no }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="promocode">Promo Code</label>
                <input type="text" class="form-control" id="promocode" name="promocode" placeholder="Enter Promo Code" max="20">
              </div>
              <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter Amount" max="10000">
              </div>
              <div class="form-group">
                <label for="used_per_user">Used Per User</label>
                <input type="number" class="form-control" id="used_per_user" name="used_per_user" placeholder="Enter Used Per User" max="10">
              </div>
              <div class="form-group">
                <label for="credit_wallat_type">Amount Credit Wallat Type</label>
                <select class="form-control" id="credit_wallat_type" name="credit_wallat_type">
                  <option value="">Select Wallat</option>
                  <option value="1">Deposited</option>
                  <option value="2">Winnings</option>
                  <option value="3">Bonus</option>
                </select>
              </div>
              <div class="form-group">
                <label for="expire_date">Expire Date</label>
                <input type="text" class="form-control datepicker" id="expire_date" name="expire_date" placeholder="Enter Expire date" autocomplete="off" readonly="">
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
