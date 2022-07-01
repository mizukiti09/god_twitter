<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\CleanArchitectureMiddleware;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class MyPageController extends Controller
{
    public function index(UserTwitterAccountsRepositoryInterface $repository)
    {
        $accounts = $repository->find();
        CleanArchitectureMiddleware::$view = view('pages.myPage', compact('accounts'));
    }
}
