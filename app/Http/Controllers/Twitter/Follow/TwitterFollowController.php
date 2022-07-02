<?php

namespace App\Http\Controllers\Twitter\Follow;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CleanArchitectureMiddleware;

class TwitterAutoFollowSearchController extends Controller
{
    public function index()
    {
        CleanArchitectureMiddleware::$view = view('pages.follow');
    }
}
