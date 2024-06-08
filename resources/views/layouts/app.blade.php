@include('partials.header')

<body class="dark:bg-slate-900">
<!-- Start Navbar -->
@include('partials.nav')
<!--end header-->
<!-- End Navbar -->

@yield('main-content')
<!-- Start Footer -->
@include('partials.footer')
