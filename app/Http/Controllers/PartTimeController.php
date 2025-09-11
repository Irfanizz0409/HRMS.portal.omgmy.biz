<?php
// PartTimeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartTimeController extends Controller
{
    public function dashboard()
    {
        return view('parttime.dashboard');
    }
}