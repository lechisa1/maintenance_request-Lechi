<?php

namespace App\Http\Controllers\director;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class DirectorController extends Controller
{
    //
        public function dashboard(){
        return view('director.dashboard');
    }
}