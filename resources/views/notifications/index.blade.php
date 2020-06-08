@extends('layouts.main')
@section('title', 'Notification')
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
    $("form#NotificationForm").submit(function(e) {
      e.preventDefault();
      // var isvalidate=$("#NotificationForm").valid();
      // if(isvalidate == false){
      //   return false;
      // }
        var formData = new FormData(this);
        $.ajax({
          url: "{{ route('notification.send') }}",
          type: 'POST',
          data: formData,
          success: function (response) {
            if(response.success == 1)
            {
              ShowSuccess(response.message);
              RedirectUrl('send-notification',1000);
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
          <form role="form" id="NotificationForm" method="post">
            {{ csrf_field() }}
            <div class="box-body">
              <div class="form-group">
                <label for="event_id">Event</label>
                <select class="form-control" id="event_id" name="event_id" >
                  <option value="">All user</option>
                  @foreach ($events as $event)
                  <option value="{{ $event->id }}">{{ $event->event_name }} - {{ $event->schedule_time }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="notification_title">Title</label>
                <input type="text" class="form-control" id="notification_title" name="notification_title" placeholder="Enter title" maxlength="100" required="">
              </div>
              <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" rows="3" id="message" name="message" placeholder="Enter message" required="" maxlength="200"></textarea>
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
