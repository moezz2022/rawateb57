<!DOCTYPE html>
<html lang="ar">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		@include('layouts.head')
	</head>

	<body class="main-body app sidebar-mini">
	
		@include('layouts.main-sidebar')		
		<!-- main-content -->
		<div class="main-content app-content">
			@include('layouts.main-header')			
			<!-- container -->
			<div class="container-fluid">
				@yield('page-header')
				@yield('content')
            	@include('layouts.footer')
				@include('layouts.footer-scripts')	
	</body>
</html>