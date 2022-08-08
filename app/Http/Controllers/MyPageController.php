<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CleanArchitectureMiddleware;

class MyPageController extends Controller
{
    public function index()
    {
        CleanArchitectureMiddleware::$view = view('pages.myPage');
    }
}
