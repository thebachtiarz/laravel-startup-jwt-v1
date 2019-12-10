<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="icon" class="brand-image img-circle" type="image" href="{{ apps_icon() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ offline_asset() }}/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ offline_asset() }}/dist/css/adminlte.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @yield('header')
</head>

<body class="sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        @include('layouts.components._navbar')
        @include('layouts.components._sidebar')
        <div class="content-wrapper">
            @include('layouts.components._content_header')
            <div class="content">
                <div class="container-fluid">
                    @yield('content_body')
                </div>
            </div>
        </div>
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline"><i class="far fa-copyright"></i> Bachtiars Project</div>
            <strong>{{ config('app_handler.app_name') }}</strong>
        </footer>
    </div>
    <script src="{{ offline_asset() }}/plugins/jquery/jquery.min.js"></script>
    @include('layouts.libraries._libraries', ['_lib' => ['_axios', '_toastrjs']])
    <script src="{{ offline_asset() }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ offline_asset() }}/dist/js/adminlte.min.js"></script>
    <script src="/js/app/libraries/await-sleep.min.js"></script>
    <script async src="/js/app/master/master_home.min.js"></script>
    <script src="/js/app/auth/credentials-manager.min.js"></script>
    <script src="/js/app/auth/credentials-checker.min.js"></script>
    @yield('footer')
    <!-- </body></html> -->
