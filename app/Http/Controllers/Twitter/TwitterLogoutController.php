<?php

namespace App\Http\Controllers\Twitter;

use App\Http\Controllers\Controller;
use packages\UseCase\Twitter\Auth\TwitterAuthUseCaseInterface;

class TwitterLogoutController extends Controller
{
    // twitterアカウントの認証解除
    public function handle(TwitterAuthUseCaseInterface $useCase)
    {
        $useCase->logoutHandle();
        return redirect('https://twitter.com/logout');
    }
}
