<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" class="brand-image img-circle" type="image" href="{{ apps_icon() }}">
    <title>{{ config('app_handler.app_name') }} | Renew Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ online_asset() }}/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ online_asset() }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="{{ online_asset() }}/dist/css/adminlte.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page bg-gradient-dark" id="view-renewpassword-form">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ online_asset() }}/index2.html"><b>Admin</b>LTE</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg" id="view-renewpassword-msg">You are only one step a way from your new password, renew your password now.</p>
                <div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="form-password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="form-repassword" placeholder="Confirm Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block btn-flat text-bold" id="form-submit"><i class="fas fa-key"></i>&ensp;Change password</button>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-1">
                    <a href="login.html">Login</a>
                </p>
            </div>
        </div>
    </div>
    <script src="{{ online_asset() }}/plugins/jquery/jquery.min.js"></script>
    @include('layouts.libraries._libraries', ['_lib' => ['_axios', '_forgejs']])
    <script src="{{ online_asset() }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ online_asset() }}/dist/js/adminlte.min.js"></script>
    <script src="/js/app/libraries/await-sleep.min.js"></script>
    <script>
        $(document).on('keyup', '#form-password', function(e) {
            (event.keyCode === 13) ? (e.preventDefault(), $('#form-repassword').focus()) : ''
        });
        $(document).on('keyup', '#form-repassword', function(e) {
            (event.keyCode === 13) ? (e.preventDefault(), $('#form-submit').click()) : ''
        });
        $(document).on('click', '#form-submit', function() {
            let password = $('#form-password').val();
            let repassword = $('#form-repassword').val();
            if (password && repassword) {
                if (password == repassword) {
                    submitRenewPassword(encryptPassword(password), encryptPassword(repassword));
                } else {
                    $('#form-repassword').val(''), $('#form-repassword').attr('placeholder', "Please type password again")
                }
            }
            if (!password) {
                $('#form-password').attr('placeholder', "Password can't be empty")
            }
            if (!repassword) {
                $('#form-repassword').attr('placeholder', "Confirm Password can't be empty")
            }
        });

        const submitRenewPassword = (password, repassword) => {
            let url = new URLSearchParams(window.location.search);
            let access = url.get('_access');
            axios.post(`/api/auth/signin/lost/renew-password`, {
                password,
                repassword,
                access
            }).then(response => renewPasswordResponse(response.data)).catch(error => $('#view-renewpassword-msg').html(spanMessage('danger', error)));
        }

        const renewPasswordResponse = async (data) => {
            await sleep(1000);
            if (data.status == 'success') {
                $('#view-renewpassword-msg').html(spanMessage('success', data.message)), redirectTo('/signin')
            } else {
                $('#view-renewpassword-msg').html(spanMessage('info', data.message))
            }
        }

        const spanMessage = (color, message) => `<p class="text-bold text-${color}">${message}</p>`;
    </script>
    <!-- </body></html> -->
