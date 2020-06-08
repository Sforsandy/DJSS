@extends('layouts.main')
@section('title', 'My Account')
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
    $("form#IdProofForm").submit(function(e) {
      e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
          url: "{{ route('account.upload_id_proof') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('myaccount',1000);
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

    $("form#UpiIdForm").submit(function(e) {
      e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
          url: "{{ route('account.update_upi_data') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('myaccount',1000);
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

    $("form#EmailVerificationForm").submit(function(e) {
      e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
          url: "{{ route('account.email_verification') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('myaccount',1000);
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
        <div class="row">

          <div class="col-md-4">
            <div class="box box-default collapsed-box">
              <div class="box-header with-border">
                <h3 class="box-title">Email Verification</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                  </button>
                </div>
              </div>
              @if(Auth::user()->email_verified == 1)
              <div class="box-body">
                <div class="form-group">
                  <label>Verified email.</label>
                  <label class="form-control">{{ Auth::user()->email }}</label>
                </div>
              </div>
              @else
              <div class="box-body">
                <form role="form" id="EmailVerificationForm" method="post">
                  {{ csrf_field() }}
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" maxlength="200">
                  </div>
                  <button type="submit" class="btn btn-sm btn-info btn-flat pull-right">Submit</button>
                </form>
              </div>
              @endif
            </div>
          </div>

          <div class="col-md-4">
            <div class="box box-default collapsed-box">
              <div class="box-header with-border">
                <h3 class="box-title">ID Verification</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                  </button>
                </div>
              </div>
              @if(Auth::user()->id_proof_verified == 0 || Auth::user()->id_proof_verified == 3)
              <div class="box-body">
                <form role="form" id="IdProofForm" method="post">
                  @if(Auth::user()->id_proof_verified == 3)
                  <h3>Your id proof is not verified.Please upload again.</h3>
                  @endif
                  {{ csrf_field() }}
                  <div class="form-group">
                    <label for="proof_type">Proof type</label>
                    <select class="form-control" id="proof_type" name="proof_type">
                      <option value="">Select Proof</option>
                      <option value="PAN Card">PAN Card</option>
                      <option value="Aadhar Card">Aadhar Card</option>
                      <option value="Driving Licence">Driving Licence</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="id_proof_image">Upload Id Proof Image</label><small> Max Size 1MB</small>
                    <input type="file" class="form-control" id="id_proof_image" name="id_proof_image" placeholder="Upload id proof image">
                  </div>
                  <button type="submit" class="btn btn-sm btn-info btn-flat pull-right">Submit</button>
                </form>
              </div>
              @elseif(Auth::user()->id_proof_verified == 1)
              <div class="box-body">
                <h3>Please wait 24~48 hours for id proof approval.</h3>
              </div>
              @elseif(Auth::user()->id_proof_verified == 2)
              <div class="box-body">
                <h3>Your id proof is verified</h3>
              </div>
              @endif
            </div>
          </div>

          <div class="col-md-4">
            <div class="box box-default collapsed-box">
              <div class="box-header with-border">
                <h3 class="box-title">UPI ID</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                  </button>
                </div>
              </div>
              @if(empty(Auth::user()->bank_holder_name) || empty(Auth::user()->paymentupi))
              <div class="box-body">
                <form role="form" id="UpiIdForm" method="post">
                  {{ csrf_field() }}
                  <div class="form-group">
                    <label for="bank_holder_name">Bank Holder Name</label>
                    <input type="text" class="form-control" id="bank_holder_name" name="bank_holder_name" placeholder="Enter bank holder name" maxlength="200">
                  </div>

                  <div class="form-group">
                    <label for="paymentupi">UPI ID</label>
                    <input type="text" class="form-control" id="paymentupi" name="paymentupi" placeholder="Enter UPI ID" maxlength="200">
                  </div>
                  <button type="submit" class="btn btn-sm btn-info btn-flat pull-right">Submit</button>
                </form>
              </div>
              @else
              <div class="box-body">
                <div class="form-group">
                  <label for="bank_holder_name">Bank Holder Name</label>
                  <lable class="form-control">{{ Auth::user()->bank_holder_name }}</lable>
                </div>

                <div class="form-group">
                  <label for="paymentupi">UPI ID</label>
                  <lable class="form-control">{{ Auth::user()->paymentupi }}</lable>
                </div>
              </div>
              @endif
            </div>
          </div>


        </div>
      </section>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
