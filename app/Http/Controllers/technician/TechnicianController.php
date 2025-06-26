<?php

namespace App\Http\Controllers\technician;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TechnicianController extends Controller
{
    //
    public function dashboard()
    {
        return view('technician.dashboard.dasboard');
    }
}