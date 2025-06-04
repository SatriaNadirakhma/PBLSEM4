<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    public function index()
    {
        return view('about.templateabout'); 
    }
}
