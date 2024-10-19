<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function userLoggedIn()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.user-login');
    }
}
