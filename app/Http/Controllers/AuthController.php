<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function userLoggedIn()
    {
        return view('auth.user-login');

    }
}
