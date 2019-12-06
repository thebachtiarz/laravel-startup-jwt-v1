<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" class="brand-image img-circle" type="image" href="{{ apps_icon() }}">
    <title>{{ config('app_handler.app_name') }} | Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ online_asset() }}/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ online_asset() }}/dist/css/adminlte.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page bg-gradient-dark" id="view-lostpassword-form">
    <div class="login-box">
        <div class="login-logo">
            <a href="/" class="text-white" style="font-size: 40px; opacity: .8;"><b>Laravel</b> Apps</a>
        </div>
        <div class="card shadow-lg p-1 mb-5 bg-white rounded">
            <div class="card-body login-card-body">
                <p class="login-box-msg" id="view-lostpassword-msg">You forgot your password? Here you can easily retrieve a new password.</p>
                <div>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" id="form-email" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block btn-flat text-bold" id="form-submit"><i class="fas fa-paper-plane"></i>&ensp;Request new password</button>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-1">
                    <a href="/signin">Login</a>
                </p>
                <p class="mb-0">
                    <a href="/register" class="text-center">Register a new membership</a>
                </p>
            </div>
        </div>
    </div>
    <script src="{{ online_asset() }}/plugins/jquery/jquery.min.js"></script>
    @include('layouts.libraries._libraries', ['_lib' => ['_axios']])
    <script src="{{ online_asset() }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ online_asset() }}/dist/js/adminlte.min.js"></script>
    <script src="/js/app/libraries/await-sleep.min.js"></script>
    <script src="/js/app/auth/credentials-manager.min.js"></script>
    <script>
        $(document).on('keyup', '#form-email', function(e) {
            (event.keyCode === 13) ? (e.preventDefault(), $('#form-submit').click()) : ''
        });
        $(document).on('click', '#form-submit', function() {
            let email = $('#form-email').val();
            if (email) {
                submitLostPassword(email)
            }
            if (!email) {
                $('#form-email').attr('placeholder', "Email can't be empty")
            }
        });

        const submitLostPassword = (email) => {
            axios.post(`/api/auth/signin/lost`, {
                email
            }).then(response => {
                lostPasswordResponse(response.data)
            }).catch(error => {
                $('#view-lostpassword-msg').html(spanMessage('danger', error))
            });
        }

        const lostPasswordResponse = async (data) => {
            await sleep(1000);
            if (data.status == 'success') {
                $('#view-lostpassword-msg').html(spanMessage('success', data.message))
            } else {
                let error = '';
                data.message.email ? data.message.email.forEach(msg => error += spanMessage('info', msg)) : error += spanMessage('info', data.message), $('#view-lostpassword-msg').html(error)
            }

        }

        const spanMessage = (color, message) => `<p class="text-bold text-${color}">${message}</p>`;
    </script>
    <!-- </body></html> -->
