<?php 
// echo "<pre>";
// echo $paramList->checkSum;
// print_r($paramList);die;
?>
<html>
<head>
<title>Merchant Check Out Page</title>
</head>
<body>
	<center><h1>Please do not refresh this page...</h1></center>
		<form method="post" action="{{ config('app.PAYTM_TXN_URL') }}" name="f1">
			{{ csrf_field() }}
		<table border="1">
			<tbody>
			@foreach ($paramList as $key => $value)
			<input type="hidden" name="{{ $key }}" value="{{ $value }}">
			@endforeach
			<input type="hidden" name="CHECKSUMHASH" value="{{ $paramList->checkSum }}">
			</tbody>
		</table>
		<script type="text/javascript">
			document.f1.submit();
		</script>
	</form>
</body>
</html>