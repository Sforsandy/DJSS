@extends('layouts.main')
@section('title', 'Users')
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
    $("form#UserForm").submit(function(e) {
      e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
          url: "{{ route('user.update') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('users',1000);
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
          <form role="form" id="UserForm" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $data->id }}">
            <div class="box-body">
              <div class="form-group">
                <label for="event_format_name">First name</label>
                <input type="text" class="form-control" id="firstname"  value="{{ $data->firstname }}" name="firstname" placeholder="Enter firstname" maxlength="100">
              </div>
              <div class="form-group">
                <label for="event_format_name">Last name</label>
                <input type="text" class="form-control" id="lastname"  value="{{ $data->lastname }}" name="lastname" placeholder="Enter lastname" maxlength="100">
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email"  value="{{ $data->email }}" name="email" placeholder="Enter email" maxlength="100">
              </div>
              <div class="form-group">
                <label for="mobile_no">Mobile no</label>
                <input type="text" class="form-control" id="mobile_no"  value="{{ $data->mobile_no }}" name="mobile_no" placeholder="Enter mobile no" maxlength="10">
              </div>
              <div class="form-group">
                <label for="character_name">Character name</label>
                <input type="text" class="form-control" id="character_name"  value="{{ $data->character_name }}" name="character_name" placeholder="Enter character name" maxlength="100">
              </div>
              <div class="form-group">
                <label for="paymentupi">Payment UPI id</label>
                <input type="text" class="form-control" id="paymentupi"  value="{{ $data->paymentupi }}" name="paymentupi" placeholder="Enter payment UPI id" maxlength="250">
              </div>
              <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" name="gender" id="gender">
                  <option value="">Select gender</option>
                  <option value="male" <?php echo  ($data->gender == 'male') ? 'selected' : ''; ?>>Male</option>
                  <option value="female" <?php echo  ($data->gender == 'female') ? 'selected' : ''; ?>>Female</option>
                  <option value="other" <?php echo  ($data->gender == 'other') ? 'selected' : ''; ?>>Other</option>
                </select>
              </div>
              <div class="form-group">
                <label for="gender">Status</label>
                <div class="checkbox icheck">
                  <label>
                    <input type="checkbox" id="status" name="status" <?php echo  ($data->status == 1) ? 'checked' : ''; ?>> Is Active
                  </label>
                </div>
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
