        <!-- Title -->
        <title> مديرية التربية </title>
        <meta name="csrf-token" content="{{ csrf_token() }}">   
        <link rel="icon" href="{{ asset('assets/img/brand/logo57.png')}}" type="image/x-icon"/>
        <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}"></link>
        <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap-icons.css')}}"></link>
        <link href="{{ asset('assets/css/alertify.min.css')}}" rel="stylesheet">
        <link href="{{ asset('assets/css/default.min.css')}}" rel="stylesheet">
        <link href="assets/plugins/sweet-alert/sweetalert.css">
        <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
        <link href="{{ asset('assets/css/icons.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/css/sidemenu.css')}}">
        <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

@yield('css')
        <link href="{{ asset('assets/css/style.css')}}" rel="stylesheet">
        <link href="{{ asset('assets/css/main.css')}}" rel="stylesheet">
        <link href="{{ asset('assets/css/main2.css') }}" rel="stylesheet">