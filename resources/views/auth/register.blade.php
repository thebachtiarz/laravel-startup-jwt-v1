<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" class="brand-image img-circle" type="image" href="{{ apps_icon() }}">
    <title>{{ config('app_handler.app_name') }} | Registration Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ online_asset() }}/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ online_asset() }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="{{ online_asset() }}/dist/css/adminlte.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition register-page bg-gradient-dark" id="view-register-form">
    <script src="{{ online_asset() }}/plugins/jquery/jquery.min.js"></script>
    @include('layouts.libraries._libraries', ['_lib' => ['_axios', '_forgejs']])
    <script src="{{ online_asset() }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ online_asset() }}/dist/js/adminlte.min.js"></script>
    <script src="/js/app/libraries/await-sleep.min.js"></script>
    <script src="/js/app/auth/register_master.min.js"></script>
    <!-- </body></html> -->
