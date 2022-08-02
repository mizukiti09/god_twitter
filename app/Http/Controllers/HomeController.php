<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CleanArchitectureMiddleware;

class HomeController extends Controller
{
    public function index()
    {
        CleanArchitectureMiddleware::$view = view('pages.home');
    }
}
