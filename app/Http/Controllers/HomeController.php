<?php

namespace App\Http\Controllers;

use App\Facades\Twitter;
use App\Http\Middleware\CleanArchitectureMiddleware;

class HomeController extends Controller
{
    public function index()
    {
        CleanArchitectureMiddleware::$view = view('pages.home');
    }
}
