<!DOCTYPE html>
<html lang="ar">
	<head>
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="Description" content="">
		<meta name="Keywords" content=""/>
		@include('layouts.head')
	</head>
	<body class="main-body bg-primary-transparent">
		@yield('content')		
		@include('layouts.footer-scripts')	
	</body>
</html>