<?php

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CleanArchitectureMiddleware;
use packages\Domain\Domain\User\AutoTweetDatasRepositoryInterface;
use packages\UseCase\Twitter\Tweet\TwitterAutoTweetUseCaseInterface;
use packages\Domain\Domain\User\UserTwitterAccountsRepositoryInterface;

class TwitterTweetController extends Controller
{
    public function list(
        UserTwitterAccountsRepositoryInterface $repository,
        AutoTweetDatasRepositoryInterface $t_repository
    ) {
        $accounts = $repository->find();

        foreach ($accounts as $account) {
            if ($account->auth_flg) {
                $auth_screen_name = $account->screen_name;
                $tweetList = $t_repository->getAllUserTweets($account->id);
            } else {
                $auth_screen_name = 'MyPage_auth_screen_name_null';
            }
        }
        $user_id = Auth::id();
        CleanArchitectureMiddleware::$view = view('pages.tweet.list', compact('accounts', 'user_id', 'auth_screen_name', 'tweetList'));
    }

    public function autoTweet(Request $request, TwitterAutoTweetUseCaseInterface $useCase)
    {
        $useCase->autoTweetSaveHandle(
            $request->user_id,
            $request->screen_name,
            $request->tweet_text,
            $request->date_value
        );
    }

    public function autoTweetOn(Request $request, TwitterAutoTweetUseCaseInterface $useCase)
    {
        $useCase->onAutoTweetHandle($request->user_id, $request->screen_name);
    }

    public function autoTweetStop(Request $request, TwitterAutoTweetUseCaseInterface $useCase)
    {
        $useCase->stopAutoTweetHandle($request->user_id);
    }

    public function tweetDelete(Request $request, TwitterAutoTweetUseCaseInterface $useCase)
    {
        $useCase->tweetDeleteHandle($request->tweet_id);
    }

    public function tweetEdit(Request $request, TwitterAutoTweetUseCaseInterface $useCase)
    {
        $useCase->tweetEditHandle(
            $request->tweet_id,
            $request->tweet_text,
            $request->tweet_time
        );
    }
}
