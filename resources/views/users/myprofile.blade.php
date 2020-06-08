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
      var country = '<?= $data->country ?>';
      $("#country").val(country).prop("selected", true);
      // var state = '<?= $data->state ?>';
      // $("#state").val(state).prop("selected", true);
      var state_id = $('#state').find(':selected').data('id');
      getCities(state_id);
    });

    $("#state").change(function(e) {
      var state_id = $(this).find(':selected').data('id');
      getCities(state_id);
    });
    function getCities(state_id)
    {
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: '{{ route("cities") }}',
        data: {
          '_token': $('input[name=_token]').val(),
          'state': state_id
        },
        success: function(response) {
          var CitiesList = '<option value="">Select City</option>';
          var city = '<?= $data->city ?>';
          $.each(response.data, function( index, value ) {
            var SelectedTxt = '';
            if(value.city_name == city)
            {
              SelectedTxt = 'selected';
            }
            CitiesList += '<option value='+value.city_name+' '+SelectedTxt+'>'+value.city_name+'</option>'
          });
          $("#city").html(CitiesList);
        },
      });
    }
    $("form#UserForm").submit(function(e) {
      e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
          url: "{{ route('user.update_user_profile') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('myprofile',1000);
            }
            else
            {
              ShowError(response.message);
            }
          },
          error: function(response)
          {
            ShowError(response.statusText);
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
            <div class="box-body">
              <div class="col-md-6">
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
                <label  class="form-control">{{ $data->mobile_no }}</label>
              </div>
              <div class="form-group">
                <label for="character_name">Character name</label>
                <input type="text" class="form-control" id="character_name"  value="{{ $data->character_name }}" name="character_name" placeholder="Enter character name" maxlength="100">
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
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="paymentupi">Payment UPI id</label>
                <input type="text" class="form-control" id="paymentupi"  value="{{ $data->paymentupi }}" name="paymentupi" placeholder="Enter payment UPI id" maxlength="250">
              </div>
              <div class="form-group">
                <label for="country">Country</label>
                <select class="form-control" name="country" id="country">
                  <option value="">Select country</option>
                  <option value="india">India</option>
                </select>
              </div>
              <div class="form-group">
                <label for="state">State</label>
                <select class="form-control" id="state" name="state">
                  <option value="">Select state</option>
                  @foreach ($states as $state)
                  <?php $selected = '';
                  if($state->state_name == $data->state)
                    {$selected = 'selected';} ?>
                  <option data-id="{{ $state->id }}" value="{{ $state->state_name }}" <?= $selected ?> >{{ $state->state_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="city">City</label>
                <select class="form-control" name="city" id="city">
                  <option value="">Select city</option>
                  <option value="surat">Surat</option>
                </select>
              </div>
              <div class="form-group">
                <label for="area">Area</label>
                <input type="text" class="form-control" id="area"  value="{{ $data->area }}" name="area" placeholder="Enter area" maxlength="250">
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
              </div>
              <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password">
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
