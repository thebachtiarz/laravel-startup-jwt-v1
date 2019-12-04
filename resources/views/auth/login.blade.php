<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app_handler.app_name') }} | Sign in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ offline_asset() }}/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ offline_asset() }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="{{ online_asset() }}/dist/css/adminlte.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page bg-gradient-dark">
    <div class="login-box">
        <div class="login-logo">
            <a href="/" class="text-white" style="font-size: 40px; opacity: .8;"><b>Laravel</b> Apps</a>
        </div>
        <div class="card shadow-lg p-1 mb-5 bg-white rounded">
            <div class="card-body login-card-body">
                <p class="login-box-msg" id="view-login-msg">Sign in to start your session</p>
                <div class="mb-3">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" id="form-email" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="form-password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-7">
                            <div class="icheck-primary">
                                <input type="checkbox" id="form-remember">
                                <label for="form-remember">Remember Me</label>
                            </div>
                        </div>
                        <div class="col-5">
                            <button type="submit" class="btn btn-primary btn-block btn-flat text-bold" id="form-submit"><i class="fas fa-sign-in-alt"></i>&ensp;Sign In</button>
                        </div>
                    </div>
                </div>
                <p class="mb-1">
                    <a href="/signin/lost">I forgot my password</a>
                </p>
                <p class="mb-0">
                    <a href="/register" class="text-center">Register a new membership</a>
                </p>
            </div>
        </div>
    </div>
    <script src="{{ online_asset() }}/plugins/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@0.19.0/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/node-forge@0.7.0/dist/forge.min.js"></script>
    <script src="{{ online_asset() }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ online_asset() }}/dist/js/adminlte.min.js"></script>
    <script>
        $(document).on('keyup', '#form-email', function(e) {
            if (event.keyCode === 13) {
                e.preventDefault(), $('#form-password').focus()
            }
        });

        $(document).on('keyup', '#form-password', function(e) {
            if (event.keyCode === 13) {
                e.preventDefault(), $('#form-submit').click()
            }
        });

        $(document).on('click', '#form-submit', function() {
            let email = $('#form-email').val();
            let password = $('#form-password').val();
            let remember = $('#form-remember').is(":checked");
            let encs = forge.md.sha384.create(),
                encn = forge.md.sha512.create();
            encs.update(password), encn.update(encs.digest().toHex());
            let encpass = encn.digest().toHex();
            if (email && password) {
                submitLogin(email, encpass, remember)
            }
            if (!email) {
                $('#form-email').attr('placeholder', "Email can't be empty")
            }
            if (!password) {
                $('#form-password').attr('placeholder', "Password can't be empty")
            }
        });

        const submitLogin = (email, password, remember) => {
            let login = {
                email,
                password,
                remember
            };
            console.table(login);
        }
    </script>
    <!-- </body></html> -->
