<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function view_login()
    {
        return view('auth.login');
    }

    public function view_register()
    {
        return view('auth.register');
    }

    public function view_lostpassword()
    {
        return view('auth.lostpassword');
    }

    public function view_renewpassword()
    {
        return view('auth.renewpassword');
    }

    public function view_home()
    {
        return view('body.homepage');
    }
}
