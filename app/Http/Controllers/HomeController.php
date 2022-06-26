<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CleanArchitectureMiddleware;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        CleanArchitectureMiddleware::$view = view('pages.home');
    }
}
