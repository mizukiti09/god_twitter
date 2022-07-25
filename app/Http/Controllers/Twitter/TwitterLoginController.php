<?php

namespace App\Http\Controllers\Twitter;

use App\Http\Controllers\Controller;
use packages\UseCase\Twitter\Auth\TwitterAuthUseCaseInterface;

class TwitterLoginController extends Controller
{
    /**
     * Twitterの認証ページヘユーザーをリダイレクト
     *
     * @return \Illuminate\Http\Response
     */
    public function twitterLogin(TwitterAuthUseCaseInterface $useCase)
    {
        return $useCase->loginRedirectProvider();
    }
    /**
     * Twitterからユーザー情報を取得(Callback先)
     */
    public function twitterCallback(TwitterAuthUseCaseInterface $useCase)
    {
        return $useCase->loginHandleProviderCallback();
    }
}
