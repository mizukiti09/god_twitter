<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CleanArchitectureMiddleware;

class DocumentController extends Controller
{
    public function first()
    {
        CleanArchitectureMiddleware::$view = view('pages.document.first');
    }

    public function autoFollow()
    {
        CleanArchitectureMiddleware::$view = view('pages.document.autoFollow');
    }

    public function autoLike()
    {
        CleanArchitectureMiddleware::$view = view('pages.document.autoLike');
    }

    public function autoTweet()
    {
        CleanArchitectureMiddleware::$view = view('pages.document.autoTweet');
    }
}
