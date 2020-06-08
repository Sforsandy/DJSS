<form role="form" action="{{ route('email-verification') }}" id="EmailVerificationForm" method="post" name="f1">
  {{ csrf_field() }}
  <input type="hidden" name="email" value="{{ $email }}">
  <input type="hidden" name="id" value="{{ $id }}">
  <input type="hidden" name="time" value="{{ $time }}">
</form>
<script type="text/javascript">
  document.f1.submit();
</script>