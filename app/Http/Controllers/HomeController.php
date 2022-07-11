<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CleanArchitectureMiddleware;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;
use packages\UseCase\Twitter\UserTwitterAccountUseCaseInterface;

class HomeController extends Controller
{
    public function index(UserTwitterAccountsRepositoryInterface $repo)
    {
        dd($repo->getAccount(14));
        CleanArchitectureMiddleware::$view = view('pages.home');
    }
}
