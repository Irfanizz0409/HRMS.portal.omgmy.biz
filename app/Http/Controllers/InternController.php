<?php
// InternController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InternController extends Controller
{
    public function dashboard()
    {
        return view('intern.dashboard');
    }
}

?>