@extends('layouts.main')
@section('title', 'Event Details')
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
      // $( ".datepicker" ).datepicker({ minDate: new Date() });
    });
    $(document).on('click', '.JoinEventBtn', function() {
     $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      url: '{{ route("joinevent") }}',
      data: {
        'event_id': '<?php echo Illuminate\Support\Facades\Input::get('id'); ?>'
      },
      success: function(response) {
        if(response.success == 2)
        {
          RedirectUrl('login');
        }
        if(response.success == 1)
        {
          ShowSuccess(response.message);
          setTimeout(function(){ location.reload(true); }, 2000);
          
        }
        if(response.success == 0)
        {
          ShowError(response.message);
        }
      },
    });
    });

    $(".JoinPaidEventBtn").click(function(event){
      if(!confirm ("Redirect to payment gateway."))
       event.preventDefault();
   });
    // END PAGE LEVEL JS
</script>
    
@endsection
@section('content')
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="container">
    <!-- Main content -->
      <section class="content">
          
<!-- Game image and event name + details -->
      <div class="row">
        <div class="col-md-3" style="text-align:center;">
            <img src='{{ url("public/uploads/games")."/".$event_data->games->game_image }}' style="padding-bottom:20px;" />
        </div>
        <div class="col-md-9">
          <div class="box box-solid">
            <div class="box-header with-border callout callout-info">
              <i class="fa fa-calendar"></i>

              <h3 class="box-title">{{ $event_data->event_name }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php echo $event_data->event_description;?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                @if($event_data->status == 0 || $event_data->status == 1)
                  @if($eventflag->joinexist == 1)
                  <button class="btn btn-success">Joined</button>
                  @elseif($eventflag->is_full == 1)
                  <button class="btn btn-success">Full</button>
    
                  @elseif($eventflag->joinexist == 0 && ($event_data->event_type == 1 || $event_data->event_type == 3))
                  <button type="submit" class="btn btn-primary JoinEventBtn" <?php echo  ($event_data->created_by == @Auth::user()->id) ? 'disabled' : ''; ?>>Join Now</button>
    
                  @elseif($eventflag->joinexist == 0 && ($event_data->event_type == 2 || $event_data->event_type == 4))
                  <form action="{{ route('joinpaidevent') }}" method="POST" role="form" id="JoinPaidEventForm" class="pull-left mr-5p">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ Illuminate\Support\Facades\Input::get('id') }}" name="event_id">
                    <button type="submit" class="btn btn-primary JoinPaidEventBtn" <?php echo  ($event_data->created_by == @Auth::user()->id) ? 'disabled' : ''; ?> >Join Now</button>
                  </form>
                  @endif
              @endif

                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#AccessDetailsModal">
                ACCESS DETAILS
              </button>

                  <a href="{{ route('events') }}" class="btn btn-default">Back</a>
                  

            </div>
        </div>
      </div>
      </div>


<!-- Details displayed like event date, time, format and fee -->      
       <div class="row">
        <div class="col-md-3" style="text-align:center;">
            <div class="box box-solid">
                <div class="box-body">
                <table class="no-border-tbl">
                  <tbody>
                      <tr>
                          <td class="evnt-dtl-title"><i class="fa fa-calendar-o"></i> Event Date: </td>
                        <td>{{ \Carbon\Carbon::parse($event_data->schedule_date)->format('d-m-Y') }}</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div> 
        </div>
        <div class="col-md-3" style="text-align:center;">
            <div class="box box-solid">
                <div class="box-body">
                <table class="no-border-tbl">
                  <tbody>
                      <tr>
                          <td class="evnt-dtl-title"><i class="fa fa-clock-o"></i> Event Time: </td>
                      <td>{{ \Carbon\Carbon::parse($event_data->schedule_time)->format('h:i A') }}</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div> 
        </div>
        <div class="col-md-3" style="text-align:center;">
            <div class="box box-solid">
                <div class="box-body">
                <table class="no-border-tbl">
                  <tbody>
                      <tr>
                          <td class="evnt-dtl-title"><i class="fa fa-gamepad"></i> Event Format: </td>
                      <td>{{ $event_data->event_formats->event_format_name  }}</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div> 
        </div>
        <div class="col-md-3" style="text-align:center;">
            <div class="box box-solid">
                <div class="box-body">
                <table class="no-border-tbl">
                  <tbody>
                      <tr>
                          <td class="evnt-dtl-title"><i class="fa fa-rupee"></i> Event Fee: </td>
                      <td>{{ ($event_data->fee > 0 ) ? '₹ '.$event_data->fee : 'Free'  }}</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>  
        </div>        
      </div>

<!-- Details displayed like event capacity and location -->      
      <div class="row">
        <div class="col-md-3" style="text-align:center;">
            <div class="box box-solid">
                <div class="box-body">
                <table class="no-border-tbl">
                  <tbody>
                    <tr>
                      <td class="evnt-dtl-title"><i class="fa fa-users"></i> Capacity: </td>
                      <td> {{ $eventflag->tolaljoin }} / {{ $event_data->capacity }}</td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div> 
        </div>
        <div class="col-md-9" style="text-align:center;">
            <div class="box box-solid">
                <div class="box-body">
                <table class="no-border-tbl">
                  <tbody>
                    @if($event_data->event_type == 3 || $event_data->event_type == 4)
                    <tr>
                      <td class="evnt-dtl-title"> &nbsp;<i class="fa fa-map-marker"></i> Event Location: </td>
                      <td>{{ $event_data->location }} </td>
                    </tr>
                    @endif
                    @if($event_data->event_type == 1 || $event_data->event_type == 2)
                    <tr>
                      <td class="evnt-dtl-title"> &nbsp;<i class="fa fa-map-marker"></i> Event Location</td>
                      <td>ONLINE!!</td>
                    </tr>                    
                    @endif
                    </tbody>
                </table>
                </div>
            </div> 
        </div>
      </div>     

<!-- Event winners section -->
@if($event_winners->count() > 0)
        <div class="row">        
        <div class="col-md-12">
            <div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa fa-trophy"></i>

              <h3 class="box-title">EVENT WINNERS</h3>
            </div>                
                <div class="box-body">                
                
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="4000">
                  <ol class="carousel-indicators">
                    @foreach ($event_winners  as $keySlider => $winnerSlider)
                    <li data-target="#carousel-example-generic" data-slide-to="{{ $keySlider }}" class="{{ ($keySlider == 0) ? 'active' : '' }}"></li>
                    @endforeach
                  </ol>
                  <div class="carousel-inner" width="100%">
                    @foreach ($event_winners  as $keyValue => $winnerValue)
                    <div class="item {{ ($keyValue == 0) ? 'active' : '' }}">
                      <?php
                      if($winnerValue->upload_screenshot != '')
                      {
                        $WinnerImg = url('public/uploads/winners').'/'.$winnerValue->upload_screenshot;
                      }
                      else
                      {
                        $WinnerImg = url('public/image').'/winner-default-img.jpg';
                      }
                      ?>
                      <img src="{{ $WinnerImg }}" alt="First slide">

                      <div class="carousel-caption">
                        <?php 
                        $userData = App\User::find($winnerValue->user_id);
                        $winnerPositionData = App\WinnerPosition::find($winnerValue->winner_position);
                        ?>
                        <span class="user-winner-position-text">{{ $winnerPositionData->position }} Winner<br></span>
                        <span class="user-winner-position-text">{{ $userData->firstname }} {{ $userData->lastname }}</span>
                        <span class="user-winner-position-text">{{ $userData->character_name }}</span>
                        
                      </div>
                    </div>
                    @endforeach
                  </div>
                  <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                    <span class="fa fa-angle-left"></span>
                  </a>
                  <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                    <span class="fa fa-angle-right"></span>
                  </a>
                </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- event rules & prize pool section -->
    <div class="row">
        <div class="col-md-8">
          <div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa fa-exclamation"></i>
              <h3 class="box-title">RULES</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                @if($event_rules_blobal->count() > 0)
                <div class="panel box box-danger">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" class="">
                        Global Rules
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="true" style="">
                    <div class="box-body">
                        <ul>
                          @foreach ($event_rules_blobal as $rule)
                          <li>{{ $rule->rules }}</li>
                          @endforeach
                        </ul>
                    </div>
                  </div>
                </div>
                @endif
                @if($game_rules->count() > 0)
                <div class="panel box box-danger">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed" aria-expanded="{{ ($event_rules_blobal->count() > 0) ? 'false' : 'true' }}">
                        {{ $event_data->games->game_name }} - Rules
                      </a>
                    </h4>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse {{ ($event_rules_blobal->count() > 0) ? '' : 'in' }}" aria-expanded="{{ ($event_rules_blobal->count() > 0) ? 'false' : 'true' }}">
                    <div class="box-body">
                        <ul>
                          @foreach ($game_rules as $Gamerule)
                          <li>{{ $Gamerule->rules }}</li>
                          @endforeach
                        </ul>
                    </div>
                  </div>
                </div>
                @endif
                @if($event_rules->count() > 0)
                <div class="panel box box-danger">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed" aria-expanded="{{ ($event_rules_blobal->count() > 0 || $game_rules->count() > 0 ) ? 'false' : 'true' }}">
                        {{ $event_data->event_name }} - Rules
                      </a>
                    </h4>
                  </div>
                  <div id="collapseThree" class="panel-collapse collapse {{ ($event_rules_blobal->count() > 0 || $game_rules->count() > 0 ) ? '' : 'in' }}" aria-expanded="{{ ($event_rules_blobal->count() > 0 || $game_rules->count() > 0 ) ? 'false' : 'true' }}">
                    <div class="box-body">
                        <ul>
                          @foreach ($event_rules as $Eventrule)
                          <li>{{ $Eventrule->rules }}</li>
                          @endforeach
                        </ul>
                    </div>
                  </div>
                </div>
                @endif
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>        
        <div class="col-md-4">
          <div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa fa-trophy"></i>

              <h3 class="box-title text-yellow">TOTAL PRIZE: ₹ {{ $event_data->total_prize }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body ">
                    <!--<table class="table EventPrizePoolTbl no-border-tbl text-yellow">
                      <tbody>
                        <tr>
                          <td class="evnt-pricepoll-title"><i class="fa fa-trophy"></i> Total Prize: </td>
                          <td >₹ {{ $event_data->total_prize }}</td>
                        </tr>
                        <tr>
                          <td class="evnt-pricepoll-title"><i class="fa fa-trophy"></i> Winner Prize</td>
                          <td>₹ {{ $event_data->winner_prize }}</td>
                        </tr>
                        <tr>
                          <td class="evnt-pricepoll-title"> <i class="fa fa-trophy"></i> 1st Runner-Up Prize</td>
                          <td>₹ {{ $event_data->runner_up1_prize }}</td>
                        </tr>
                        <tr>
                          <td class="evnt-pricepoll-title"><i class="fa fa-trophy"></i> 2nd Runner-Up Prize</td>
                          <td>₹ {{ $event_data->runner_up2_prize }}</td>
                        </tr> 
                      </tbody>
                    </table> -->
                    <?php echo $event_data->winner_details;?>
                <!-- <h4>Instructions:</h4>
                <label>Send us the below details through our community forum: <a target="_blank" href="https://community.gamerzbyte.com/forum/6-customer-support/">Click here</a></label>
                <ul>
                  <li>Scanned ID/Address Proof.</li>
                  <li>UPI ID for Prize Money Transfer.</li>
                  <li>Your Contact No.</li>
                  <li>Winner Screenshot<br>You need to contact us with these details within 24 hours of the event ending.</li>
                  <li> If you fail to contact us within 24 hours, we would assume you have renounced your winnings.</li>
                </ul> -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
</div>

<!-- LIVE Stream -->          
    <div class="row">
        <div class="col-md-8">
      <h2 class="page-header text-aqua">LIVE STREAM</h2>
      <div class="text-aqua">
                @if($event_data->stream_url == '')
                <label>The LIVE Stream URL will be added soon.</label>
                @else
                <iframe id="player" type="text/html" width="100%" height="390"
                src="{{ $event_data->stream_url }}?enablejsapi=1&origin=https://events.gamerzbyte.com"
                frameborder="0"></iframe><br/>
                <!-- <iframe width="560" height="315" src="{{ $event_data->stream_url }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
                @endif          
          </div>
        </div>
        <!-- Autogenerated QR Code -->
        <div class="col-md-4">
            <div class="box box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-calendar"></i>
                    <h3 class="box-title">QR CODE</h3>            
                </div>
                <div class="box-body" style="text-align:center;">
                    {!! QrCode::size(200)->generate(Request::fullUrl()); !!}
                </div>
            </div>    
        </div>        
    </div>  

      </section>
      <!-- /.content -->
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    
    <!-- EVENT ACCESS DETAILS MODAL -->
<div class="modal fade" id="AccessDetailsModal" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
          <span aria-hidden="true"></span></button>
          <h4 class="modal-title">EVENT ACCESS DETAILS</h4>
        </div>
        <div class="modal-body">

                @if($eventflag->joinexist == 1)

                  @if($event_data->access_details != '')
                  {{ $event_data->access_details }}
                  @else
                  <label>Event access details will be updated 10-15 minutes before the actual event start time. Please be patient and refresh the page only when required.</label>                  @endif
                @endif
                <ul>
                  <li>Be sure to read and follow the Event Rules.</li>
                  <li>For support contact us on Discord: <a target="_blank" href="https://discord.gg/JS8jEzs">https://discord.gg/JS8jEzs</a> | Community Forums: <a target="_blank" href="https://community.gamerzbyte.com">https://community.gamerzbyte.com</a></li>
                </ul>                

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<!-- EVENT RULES MODAL END -->
@endsection
