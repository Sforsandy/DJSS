@extends('layouts.main')
@section('title', 'Event')
@section('css')
<!-- BEGIN PAGE VENDOR CSS-->
    <!-- END PAGE VENDOR CSS-->
    
    <!-- BEGIN PAGE LEVEL CSS-->
    <!-- END PAGE LEVEL CSS-->
@endsection
@section('js')
<!-- CK Editor -->
{{ Html::script('public/app-assets/bower_components/ckeditor/ckeditor.js') }}
<script type="text/javascript">
    // BEGIN PAGE VENDOR JS

    // END PAGE VENDOR JS
    // BEGIN PAGE LEVEL JS
    $(function () {
      $('.feeDiv').hide();
      $('.locationDiv').hide();
      var event_type_cr = '<?= $event_data->event_type ?>';
      if(event_type_cr == 2 || event_type_cr == 4)
      {
        $('.feeDiv').show();
      }
      if(event_type_cr == 3 || event_type_cr == 4)
      {
        $('.locationDiv').show();
      }
      CKEDITOR.config.basicEntities = false;
      CKEDITOR.config.autoParagraph = false;
      CKEDITOR.config.toolbar = [
      ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','Find'],
      ['NumberedList','BulletedList'],
      ['Link','Smiley','TextColor','BGColor'],
      [ 'list', 'indent', 'blocks', 'align', 'bidi' ], [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
      ['Styles','Format','Font','FontSize']];
      CKEDITOR.replace('event_description');
      CKEDITOR.replace('winner_details');

    });

    $(document).on('keyup', '#winner_prize,#runner_up1_prize,#runner_up2_prize', function() {
      var winner_prize  =   ($("#winner_prize").val() != '') ? $("#winner_prize").val() : 0 ;
      var runner_up1_prize  = ($("#runner_up1_prize").val() != '') ? $("#runner_up1_prize").val() : 0 ;
      var runner_up2_prize  = ($("#runner_up2_prize").val() != '') ? $("#runner_up2_prize").val() : 0 ;
      $("#total_prize").val(parseInt(winner_prize) + parseInt(runner_up1_prize) + parseInt(runner_up2_prize));
    });

    $("#event_type").change(function(e) {
      var event_type = $(this).val();
      if(event_type == 2 || event_type == 4)
      {
        $('.feeDiv').show();
      }else{ $('.feeDiv').hide();}
      if(event_type == 3 || event_type == 4)
      {
        $('.locationDiv').show();
      }else{ $('.locationDiv').hide();}
    });
    $("form#EventForm").submit(function(e) {
      e.preventDefault();
      var isvalidate=$("#EventForm").valid();
      if(isvalidate == false){
        return false;
      }
        var formData = new FormData(this);
        formData.append('event_description', CKEDITOR.instances.event_description.getData());
        formData.append('winner_details', CKEDITOR.instances.winner_details.getData());
        $.ajax({
          url: "{{ route('manage-event.update') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('manage-events',1000);
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
          <form role="form" id="EventForm" method="post" class="FormValidate">
            {{ csrf_field() }}
            <div class="box-body">
              <input type="hidden" class="form-control" id="event_id" name="event_id" value="{{ $event_data->id }}">
              <div class="col-md-6">
              <div class="form-group">
                <label for="event_name">Name</label>
                <input type="text" class="form-control" id="event_name" name="event_name" placeholder="Enter name" maxlength="150" required="" value="{{ $event_data->event_name }}">
              </div>
              <div class="form-group">
                <label for="event_description">Description</label>
                <textarea class="form-control" rows="3" id="event_description" name="event_description" placeholder="Enter Description" required="">{{ $event_data->event_description }}</textarea>
              </div>
              <div class="form-group">
                <label for="game">Game</label>
                <select class="form-control" id="game" name="game" required="">
                      <option value="">Select game</option>
                      @foreach ($games as $game)
                        <?php $selected = '';
                         if($game->id == $event_data->game)
                          {$selected = 'selected';} ?>
                        <option value="{{ $game->id }}"  <?= $selected ?> >{{ $game->game_name }}</option>
                      @endforeach
                    </select>
              </div>
              <div class="form-group">
                <label for="event_format">Format</label>
                <select class="form-control" id="event_format" name="event_format" required="">
                      <option value="">Select event format</option>
                      @foreach ($event_formats as $event_format)
                      <?php $selected = '';
                         if($event_format->id == $event_data->event_format)
                          {$selected = 'selected';} ?>
                        <option value="{{ $event_format->id }}" <?= $selected ?> >{{ $event_format->event_format_name }}</option>
                      @endforeach
                    </select>
              </div>
              <div class="form-group">
                <label for="event_type">Type</label>
                <select class="form-control" id="event_type" name="event_type" required="">
                      <option value="">Select event type</option>
                      @foreach ($event_types as $event_type)
                      <?php $selected = '';
                      if($event_type->id == $event_data->event_type)
                          {$selected = 'selected';} ?>
                        <option value="{{ $event_type->id }}" <?= $selected ?> >{{ $event_type->event_type_name }}</option>
                      @endforeach
                    </select>
              </div>
              <div class="form-group">
                <label for="wallet_type">Wallet Type</label>
                <select class="form-control" id="wallet_type" name="wallet_type">
                  <option value="">Select wallet type</option>
                  <option value="1" <?php echo $event_data->wallet_type == '1' ? 'selected' : ''; ?> >Paid only (Used deposited wallet only)</option>
                  <option value="2" <?php echo $event_data->wallet_type == '2' ? 'selected' : ''; ?> >Bonus only (Used bonus wallet only)</option>
                  <option value="3" <?php echo $event_data->wallet_type == '3' ? 'selected' : ''; ?> >Paid + Bonus only (Used deposited and bonus wallet both)</option>
                </select>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="bonus_wallet_per">Used Bonus Wallet Per (%)</label>
                  <input type="text" class="form-control OnlyNumber" id="bonus_wallet_per" name="bonus_wallet_per" placeholder="Enter bonus wallet per" value="{{ $event_data->bonus_wallet_per }}"  maxlength="3">
                </div>
                <div class="form-group col-md-6">
                  <label for="bonus_max_amt">Used Bonus Max Amount (Rs.)</label>
                  <input type="text" class="form-control OnlyNumber" id="bonus_max_amt" name="bonus_max_amt" placeholder="Enter bonus max amount" value="{{ $event_data->bonus_max_amt }}" maxlength="3">
                </div>
              </div>
              <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="text" class="form-control OnlyNumber" id="capacity" name="capacity" placeholder="Enter event capacity" maxlength="7" value="{{ $event_data->capacity }}">
              </div>
              @if(Auth::user()->hasRole('admin'))
              <div class="form-group feeDiv">
                <label for="fee">Fee</label>
                <input type="text" class="form-control OnlyDecimal" id="fee" name="fee" placeholder="Enter event fee" maxlength="7" value="{{ $event_data->fee }}" required>
              </div>
              @else
                <div class="form-group feeDiv">
                  <label for="fee">Fee</label>
                  <input type="hidden" class="form-control" id="fee" name="fee" value="{{ $event_data->fee }}">
                  <label class="form-control">{{ $event_data->fee }}</label>
                </div>
              @endif
              <div class="form-group locationDiv">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" placeholder="Enter event location" value="{{ $event_data->location }}"  required="">
              </div>
              <div class="form-group">
                <label for="team_size">Team Size</label>
                <input type="text" class="form-control OnlyNumber" id="team_size" name="team_size" placeholder="Enter event team size" value="{{ $event_data->team_size }}" maxlength="2" required="">
              </div>
              </div>
              <div class="col-md-6">
                @if(Auth::user()->hasRole('admin'))
                <!-- <div class="form-group">
                  <label for="winner_prize">Winner Prize</label>
                  <input type="text" class="form-control OnlyDecimal" id="winner_prize" name="winner_prize" placeholder="Enter event winner prize" maxlength="7" value="{{ $event_data->winner_prize }}">
                </div> -->
                @else
                <!-- <div class="form-group">
                  <label for="winner_prize">Winner Prize</label>
                  <label class="form-control">{{ $event_data->winner_prize }}</label>
                </div> -->
              @endif

              @if(Auth::user()->hasRole('admin'))
              <!-- <div class="form-group">
                <label for="runner_up1_prize">1<sup>st</sup> Runner-up Prize</label>
                <input type="text" class="form-control OnlyDecimal" id="runner_up1_prize" name="runner_up1_prize" placeholder="Enter event 1st runner-up prize" maxlength="7" value="{{ $event_data->runner_up1_prize }}">
              </div> -->
              @else
                <!-- <div class="form-group">
                  <label for="runner_up1_prize">1<sup>st</sup>Runner-up Prize</label>
                  <label class="form-control">{{ $event_data->runner_up1_prize }}</label>
                </div> -->
              @endif

              @if(Auth::user()->hasRole('admin'))
              <!-- <div class="form-group">
                <label for="runner_up2_prize">2<sup>nd</sup> Runner-up Prize</label>
                <input type="text" class="form-control OnlyDecimal" id="runner_up2_prize" name="runner_up2_prize" placeholder="Enter event 2nd runner-up prize" maxlength="7" value="{{ $event_data->runner_up2_prize }}">
              </div> -->
              @else
                <!-- <div class="form-group">
                  <label for="runner_up2_prize">2<sup>nd</sup> Runner-up Prize</label>
                  <label class="form-control">{{ $event_data->runner_up2_prize }}</label>
                </div> -->
              @endif
              @if(Auth::user()->hasRole('admin'))
              <div class="form-group">
                <label for="winner_details">Winner detail</label>
                <textarea class="form-control" rows="3" id="winner_details" name="winner_details" placeholder="Enter Winner detail" >{{ $event_data->winner_details }}</textarea>
              </div>
              @else
              <div class="form-group">
                <label for="winner_details">Winner detail</label>
                <textarea class="form-control" rows="3" id="winner_details" name="winner_details" placeholder="Enter Winner detail" readonly="">{{ $event_data->winner_details }}</textarea>
              </div>
              @endif
              @if(Auth::user()->hasRole('admin'))
              <div class="form-group">
                <label for="total_prize">Total Prize</label>
                <input type="text" class="form-control OnlyDecimal" id="total_prize" name="total_prize" placeholder="Enter event total prize" maxlength="7" value="{{ $event_data->total_prize }}">
              </div>
              @else
                <div class="form-group">
                  <label for="total_prize">Total Prize</label>
                  <label class="form-control">{{ $event_data->total_prize }}</label>
                </div>
              @endif
              <div class="form-group">
                <label for="schedule_date">Schedule Date</label>
                <input type="text" class="form-control datepicker" id="schedule_date" name="schedule_date" placeholder="Enter event schedule date" autocomplete="off" required="" value="{{ \Carbon\Carbon::parse($event_data->schedule_date)->format('d-m-Y') }}" readonly="">
              </div>
              <div class="form-group">
                <label for="schedule_time">Schedule Time</label>
                <input type="text" class="form-control timepicker" id="schedule_time" name="schedule_time" placeholder="Enter event schedule time" required="" value="{{ $event_data->schedule_time }}" readonly="">
              </div>
              <div class="form-group">
                <label for="access_details">Access Details</label>
                <input type="text" class="form-control" id="access_details" name="access_details" placeholder="Enter access details" value="{{ $event_data->access_details }}">
              </div>
              <div class="form-group">
                <label for="stream_url">Stream URL</label>
                <input type="text" class="form-control" id="stream_url" name="stream_url" placeholder="Enter stream url" value="{{ $event_data->stream_url }}">
              </div>
              <!-- <div class="form-group">
                <label for="discord_url">Discord URL</label>
                <input type="text" class="form-control" id="discord_url" name="discord_url" placeholder="Enter discord url" value="{{ $event_data->discord_url }}">
              </div> -->
              @if(Auth::user()->hasRole('admin'))
              <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required="">
                  <option value="">Select status</option>
                  <option value="0" <?php echo ($event_data->status == 0) ? 'selected' : ''; ?>>Upcomming</option>
                  <option value="1" <?php echo ($event_data->status == 1) ? 'selected' : ''; ?>>OnGoing</option>
                  <option value="2" <?php echo ($event_data->status == 2) ? 'selected' : ''; ?>>Past</option>
                </select>
              </div>
              @endif
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
