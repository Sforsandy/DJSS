@extends('layouts.main')
@section('title', 'All Events')
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
    var id = '<?= @$_GET['id'] ?>';
    var ManageEventTable;
    var data_filter_status = -2;
    var data_filter_eventby = (id != '' ? id : -1);
    var data_filter_game = -1;
    $(function () {
      getEvents(data_filter_status,data_filter_eventby,data_filter_game);
    });

    $(document).on('click', '.FilterBtnStatus', function() {
      data_filter_status = $(this).attr('data-filter-status');
      // console.log($('.FilterBtnStatus.active').text());
      $('.FilterBtnStatus').removeClass('active');
      $(this).addClass('active');
      data_filter_eventby = $(".FilterBtnEventby.active").attr('data-filter-eventby');
      data_filter_game = $(".FilterBtnGame.active").attr('data-filter-game');
      page = 0;
      getEvents(data_filter_status,data_filter_eventby,data_filter_game,0)
    });
    $(document).on('click', '.FilterBtnEventby', function() {
      $('.FilterBtnEventby').removeClass('active');
      $(this).addClass('active');
      data_filter_eventby = $(this).attr('data-filter-eventby');
      data_filter_status = $(".FilterBtnStatus.active").attr('data-filter-status');
      data_filter_game = $(".FilterBtnGame.active").attr('data-filter-game');
      page = 0;
      getEvents(data_filter_status,data_filter_eventby,data_filter_game,0)
    });

    $(document).on('click', '.FilterBtnGame', function() {
      $('.FilterBtnGame').removeClass('active');
      $(this).addClass('active');
      data_filter_game = $(this).attr('data-filter-game');
      data_filter_status = $(".FilterBtnStatus.active").attr('data-filter-status');
      data_filter_eventby = $(".FilterBtnEventby.active").attr('data-filter-eventby');
      page = 0;
      getEvents(data_filter_status,data_filter_eventby,data_filter_game,0)
    });


     $(document).on('click', '.LoadMore', function() {
      page = $(this).attr('data-current-page');
      data_filter_status = $(".FilterBtnStatus.active").attr('data-filter-status');
      data_filter_eventby = $(".FilterBtnEventby.active").attr('data-filter-eventby');
      data_filter_game = $(".FilterBtnGame.active").attr('data-filter-game');
      if(page > 0)
      {
        getEvents(data_filter_status,data_filter_eventby,data_filter_game,page);
      }
     });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function getEvents(data_filter_status,data_filter_eventby,data_filter_game,page){
      $.ajax({
        type: 'POST',
        url: '{{ route("getevents") }}',
        data :{_token:$('input[name=_token]').val(),status:data_filter_status,created_by:data_filter_eventby,game:data_filter_game,page:page},
        success: function(response) {
          var EventLists = '';
          if(response.page == -1 && response.start > 0)
          {

            EventLists += '<h3 class="box-title text-center no-event-text" style="color:#fff">No more event found.</h3>';
            // $('.LoadMore').attr('data-current-page' ,response.page);
            $('.LoadMore').hide();
            $('.EventsList').append(EventLists);
            return false;
          }
          if(response.page == 1)
          {
            $('.LoadMore').show();
          }
          if(response.start == 0 && response.page == -1)
          {
            EventLists = '<h3 class="box-title text-center no-event-text" style="color:#fff">No Event found.</h3>';
            // $('.LoadMore').attr('data-current-page' ,response.page);
            $('.LoadMore').hide();
            $('.EventsList').html(EventLists);
             return false;
          }
          $.each(response.data, function( index, value ) {
            var total_prize = value.total_prize;
            if(total_prize == null || total_prize == '')
            {
                total_prize = '--';
            }
            var PriceText = '';
            if(value.event_types.id == 2 || value.event_types.id == 4)
            {
              PriceText = '₹';
            }
            EventLists += '<div class="col-xl-4 col-md-4 col-sm-6 col-xs-12 per-event-boxes" onClick="ShowEventDetails('+value.id+')">\
                <div class="info-box">\
                  <div class="info-box-icon bg-aqua">\
                    <img src="'+BASE_URL+'/public/uploads/games/'+value.games.game_image+'">\
                  </div>\
                  <div class="info-box-content">\
                    <span class="game-name-text">'+value.creaters.firstname+' '+value.creaters.lastname+'<span class="pull-right">'+PriceText+'</span>\</span>\
                    <span class="event-name-text">'+value.event_name+'</span>\
                    <span class="event-data-text text-w60"><i class="fa fa-calendar-o"></i>'+value.schedule_date+' | '+value.schedule_time+'</\span>\
                    <span class="event-data-text text-w40"><i class="fa fa-users"></i>'+value.event_joined+'/'+value.capacity+'</span>\
                    <span class="event-data-text text-w60"><i class="fa fa-trophy"></i>₹ '+total_prize+'</span>\
                    <span class="event-data-text text-w40"><i class="fa fa-gamepad"></i>'+value.event_formats.event_format_name+'</span>\
                  </div>\
                  <div class="game-name-div">\
                  <span class="event-name-text">'+value.games.game_name+'</span>\
                  </div>\
                </div>\
              </div>';
            
          });
          $('.LoadMore').attr('data-current-page' ,response.page);
          if(response.page == 1)
          {
            $('.EventsList').html(EventLists);
          }
          else
          {
            $('.EventsList').append(EventLists);
          }
        },
      });
    }

    function ShowEventDetails(id)
    {
      RedirectUrl('event-details?id='+id);
    }
    
    // END PAGE LEVEL JS
</script>
    
@endsection
@section('content')
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="container">

      <!-- Main content -->
      <section class="content">
        <div class="box box-default custom-header-box">
          <div class="box-header">
            <h3 class="box-title">Esport events</h3>
            <div class="btn-group pull-right">
              <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle FilterBtnEventbyStatus" data-toggle="dropdown">
                  Status  <span class="caret"></span></button>
                  <ul class="dropdown-menu FilterBtnList" role="menu">
                    <li><a class="FilterBtnStatus active" data-filter-status="-1">All</a></li>
                    <li><a class="FilterBtnStatus" data-filter-status="0">Upcoming</a></li>
                    <li><a class="FilterBtnStatus" data-filter-status="1">Ongoing</a></li>
                    <li><a class="FilterBtnStatus" data-filter-status="2">Past</a></li>
                  </ul>
              </div>
              <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle FilterBtnEventbyGame" data-toggle="dropdown">
                  Game  <span class="caret"></span></button>
                  <ul class="dropdown-menu FilterBtnList" role="menu">
                    <li><a class="FilterBtnGame active" data-filter-game="-1">All</a></li>
                    @foreach ($games as $game)
                    <li><a class="FilterBtnGame" data-filter-game="{{ $game->id }}">{{ $game->game_name }}</a></li>
                    @endforeach
                  </ul>
              </div>
              <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle FilterBtnEventbyTitle" data-toggle="dropdown">
                  Event by  <span class="caret"></span></button>
                  <ul class="dropdown-menu FilterBtnList" role="menu">
                    <li><a class="FilterBtnEventby active" data-filter-eventby="-1">All</a></li>
                    @foreach ($moderatorUsers as $moderator)
                    <li><a class="FilterBtnEventby" data-filter-eventby="{{ $moderator->id }}">{{ $moderator->firstname }} {{ $moderator->lastname }}</a></li>
                    @endforeach
                  </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="box box-default color-palette-box custom-box">
          <div class="box-body">
            <div class="row EventsList">
            </div>
            <div><a class="LoadMore btn-primary btn" data-current-page="0" style="width: 100%"> Load more </a></div>
          </div>
        </div>


        

        <div class="EventLists1">
          
        </div>
        

      </section>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection