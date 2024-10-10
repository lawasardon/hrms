<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function aquaLeaveList()
    {
        return view('leave.aqua.index');
    }
}
